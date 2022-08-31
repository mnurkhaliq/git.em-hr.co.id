<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\TrainingResource;
use App\Http\Resources\TrainingTransportationTypeResource;
use App\Models\Airports;
use App\Models\HistoryApprovalTraining;
use App\Models\Kabupaten;
use App\Models\Seaports;
use App\Models\Stations;
use App\Models\Training;
use App\Models\TrainingAllowance;
use App\Models\TrainingDaily;
use App\Models\TrainingOther;
use App\Models\TrainingTransportation;
use App\Models\TrainingTransportationReport; 
use App\Models\TrainingAllowanceReport; 
use App\Models\TrainingDailyReport; 
use App\Models\TrainingOtherReport; 
use App\Models\TrainingTransportationType;
use App\Models\TrainingType;
use App\Models\TransferSetting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TrainingController extends BaseApiController
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status', '[1,2,3]');
        if (!isJsonValid($status)) {
            return response()->json(['status' => 'error', 'message' => 'Status is invalid'], 404);
        }

        $status = json_decode($status);
        $histories = Training::where(['user_id' => $user->id])->where(function ($query) use ($status) {
            $query->whereIn('status', $status)->orWhereIn('status_actual_bill', $status);
        })->orderBy('created_at', 'DESC');
        $totalData = $histories->get()->count();
        $histories = $histories->paginate(10);
        $data = [
            'current_page' => $histories->currentPage(), // get current page number
            'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
            'total_data' => $totalData,
            'business_trips' => TrainingResource::collection($histories),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        info($request->all());
        $user = Auth::user();
        $approval = $user->approval;
        if ($approval == null) {
            return response()->json(['status' => 'error', 'message' => 'Your position is not defined yet. Please contact your admin!'], 403);
        } else if (count($approval->itemsTraining) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
        }

        $validator = Validator::make($request->all(), [
            "training_type_id" => "required|exists:training_type,id",
            "lokasi_kegiatan" => "required|in:Dalam Negeri,Luar Negeri",
            "tempat_tujuan" => "required",
            "topik_kegiatan" => "required",
            "tanggal_kegiatan_start" => "required|date",
            "tanggal_kegiatan_end" => "required|date",
            "pengambilan_uang_muka" => "integer",
            "tanggal_pengajuan" => "date|required_if:pengambilan_uang_muka,>,0",
            "tipe_perjalanan" => "required|in:Tidak Ada,Sekali Jalan,Pulang Pergi",
            "transportasi_berangkat" => "required_if:selection,Sekali Jalan,Pulang Pergi|in:Pesawat,Kapal,Kereta,Lainnya",
            "tanggal_berangkat" => "date",
            "waktu_berangkat" => "date_format:H:i",
            // "rute_dari_berangkat" => "",
            // "rute_tujuan_berangkat" => "",
            "tipe_kelas_berangkat" => "required_if:selection,Sekali Jalan,Pulang Pergi|in:Ekonomi,Bisnis,Executive,First Class",
            // "nama_transportasi_berangkat" => "",
            "transportasi_pulang" => "required_if:selection,Pulang Pergi|in:Pesawat,Kapal,Kereta,Lainnya",
            "tanggal_pulang" => "date",
            "waktu_pulang" => "date_format:H:i",
            // "rute_dari_pulang" => "",
            // "rute_tujuan_pulang" => "",
            "tipe_kelas_berangkat" => "required_if:selection,Pulang Pergi|in:Ekonomi,Bisnis,Executive,First Class",
            // "nama_transportasi_pulang" => "",
            // "pergi_bersama" => "",
            // "note" => "",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        if ($request->lokasi_kegiatan == 'Dalam Negeri') {
            $kab = Kabupaten::where('nama', $request->tempat_tujuan)->first();
            if (!$kab) {
                return response()->json(['status' => 'error', 'message' => 'Destination is not found!'], 404);
            }
        }

        $data = new Training();
        $data->user_id = $user->id;

        // Form Kegiatan
        // $data->jenis_training = $request->jenis_training;
        $data->training_type_id = $request->training_type_id;
        $data->cabang_id = $request->cabang_id;
        $data->lokasi_kegiatan = $request->lokasi_kegiatan;
        $data->tempat_tujuan = $request->tempat_tujuan;
        $data->topik_kegiatan = $request->topik_kegiatan;
        $data->tanggal_kegiatan_start = $request->tanggal_kegiatan_start;
        $data->tanggal_kegiatan_end = $request->tanggal_kegiatan_end;
        $data->pengambilan_uang_muka = $request->pengambilan_uang_muka ?: 0;
        if ($data->pengambilan_uang_muka > 0) {
            $data->tanggal_pengajuan = $request->tanggal_pengajuan;
            $data->tanggal_penyelesaian = Carbon::parse($request->tanggal_pengajuan)->startOfDay()->addDays(get_setting('settlement_duration') ?: 10);
        }
        $data->status = 1;
        $data->tipe_perjalanan = $request->tipe_perjalanan;
        $data->is_transfer = $request->pengambilan_uang_muka != NULL ? 0 : 1;
        $data->is_transfer_claim =0;
        $data->number = 'BT-' . Carbon::now()->format('dmY') . '/' . \Auth::user()->nik . '-' . (Training::where('user_id', \Auth::user()->id)->count() + 1);

        // Form Perjalanan
        if ($request->tipe_perjalanan != 'Tidak Ada') {
            $data->transportasi_berangkat = $request->transportasi_berangkat;
            $data->tanggal_berangkat = $request->tanggal_berangkat;
            $data->waktu_berangkat = $request->waktu_berangkat;
            $data->rute_dari_berangkat = $request->rute_dari_berangkat;
            $data->rute_tujuan_berangkat = $request->rute_tujuan_berangkat;
            $data->tipe_kelas_berangkat = $request->tipe_kelas_berangkat;
            $data->nama_transportasi_berangkat = $request->nama_transportasi_berangkat;

            if ($request->tipe_perjalanan == 'Pulang Pergi') {
                $data->transportasi_pulang = $request->transportasi_pulang;
                $data->tanggal_pulang = $request->tanggal_pulang;
                $data->waktu_pulang = $request->waktu_pulang;
                $data->rute_dari_pulang = $request->rute_dari_pulang;
                $data->rute_tujuan_pulang = $request->rute_tujuan_pulang;
                $data->tipe_kelas_pulang = $request->tipe_kelas_pulang;
                $data->nama_transportasi_pulang = $request->nama_transportasi_pulang;
            }

            $data->pergi_bersama = $request->pergi_bersama;
            $data->note = $request->note;
        }
        $data->save();

        $historyApproval = $user->approval->itemsTraining;
        $settingApprovalItem = $historyApproval[0]->structure_organization_custom_id;
        foreach ($historyApproval as $level => $value) {
            # code...
            $history = new HistoryApprovalTraining();
            $history->training_id = $data->id;
            $history->setting_approval_level_id = ($level + 1);
            $history->structure_organization_custom_id = $value->structure_organization_custom_id;
            $history->save();
        }
        $historyApprov = HistoryApprovalTraining::where('training_id', $data->id)->orderBy('setting_approval_level_id', 'asc')->get();

        $userApproval = user_approval_custom($settingApprovalItem);
        $params = getEmailConfig();
        $db = Config::get('database.default', 'mysql');

        $params['data'] = $data;
        $params['value'] = $historyApprov;
        $params['view'] = 'email.training-approval-custom';
        $params['subject'] = get_setting('mail_name') . ' - Business Trip';
        if ($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if (empty($value->email)) {
                    continue;
                }

                $params['email'] = $value->email;
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Business Trip and currently waiting your approval.</p>';
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);
            $params['text'] = '<p> ' . $data->user->name . '  / ' . $data->user->nik . ' applied for Business Trip and currently waiting your approval.</p>';
        }

        $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
        info("structure id " . $settingApprovalItem);
        info($userApprovalTokens);
        
        foreach (user_approval_id($settingApprovalItem) as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $data, 'business_trip_approval');
        }

        if (count($userApprovalTokens) > 0) {
            $config = [
                'title' => "Business Trip Approval",
                'content' => strip_tags($params['text']),
                'type' => 'business_trip_approval',
                'firebase_token' => $userApprovalTokens,
            ];
            $notifData = [
                'id' => $data->id,
            ];
            info($userApprovalTokens);
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Your Business Trip request has successfully submitted',
            ], 201);
    }

    public function getParams(Request $request)
    {
        if ($request->type == 'create') {
            $user = Auth::user();
            $approval = $user->approval;
            if ($approval == null) {
                return response()->json(['status' => 'error', 'message' => 'Your position is not defined yet. Please contact your admin!'], 403);
            } else if (count($approval->itemsTraining) == 0) {
                return response()->json(['status' => 'error', 'message' => 'Setting approval is not defined yet. Please contact your admin!'], 403);
            }
        } else {
            if (!$request->user_id) {
                return response()->json(['status' => 'error', 'message' => 'User ID is required!'], 403);
            }

            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'User is not found!'], 404);
            }
        }

        if ($user->project_id != null) {
            $data['training_type'] = TrainingType::join('users', 'users.id', '=', 'training_type.user_created')->where('users.project_id', $user->project_id)->select('training_type.*')->get();
        } else {
            $data['training_type'] = TrainingType::all();
        }

        $data['settlement_duration'] = get_setting('settlement_duration') ?: 10;

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }

    public function getClaimParams($id)
    {
        $data['transportation_type'] = TrainingTransportationTypeResource::collection(TrainingTransportationType::all());
        $data['acomodation'] = TrainingTransportation::where('training_id', $id)->get();
        $data['allowance'] = TrainingAllowance::where('training_id', $id)->get();
        $data['daily'] = TrainingDaily::where('training_id', $id)->get();
        $data['other'] = TrainingOther::where('training_id', $id)->get();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    public function getAirports(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     "type" => "required|in:Pesawat,Kapal,Kereta"
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        // }

        $airports = [];
        if ($request->type == 'Pesawat') {
            $airports = Airports::where('name', 'LIKE', "%" . $request->word . "%")->orWhere('code', 'LIKE', '%' . $request->word . '%')->orWhere('cityName', 'LIKE', '%' . $request->word . '%')->orWhere('countryName', 'LIKE', '%' . strtoupper($request->word) . '%')->groupBy('code')->limit(10)->get();
        } else if ($request->type == 'Kapal') {
            $airports = Seaports::where('name', 'LIKE', "%" . $request->word . "%")->orWhere('code', 'LIKE', '%' . $request->word . '%')->orWhere('cityName', 'LIKE', '%' . $request->word . '%')->orWhere('countryName', 'LIKE', '%' . strtoupper($request->word) . '%')->groupBy('code')->limit(10)->get();
        } else if ($request->type == 'Kereta') {
            $airports = Stations::where('name', 'LIKE', "%" . $request->word . "%")->orWhere('code', 'LIKE', '%' . $request->word . '%')->orWhere('cityName', 'LIKE', '%' . $request->word . '%')->orWhere('countryName', 'LIKE', '%' . strtoupper($request->word) . '%')->groupBy('code')->limit(10)->get();
        }

        $data = [];
        foreach ($airports as $k => $item) {
            $data[$k] = $item;
            $data[$k]['value'] = $item->name . ' - ' . $item->cityName;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }

    public function getCity(Request $request)
    {
        $city = Kabupaten::where('nama', 'LIKE', "%" . $request->word . "%")->limit(10)->get();

        $data = [];
        foreach ($city as $k => $item) {
            $data[$k] = $item;
            $data[$k]['value'] = $item->nama;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }

    public function getPlafond(Request $request)
    {
        $position = \Auth::user()->structure->position->id;
        $validator = Validator::make($request->all(), [
            'lokasi_kegiatan' => "required|in:Dalam Negeri,Luar Negeri",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $plafond = \App\Models\Kabupaten::select('provinsi_detail_allowance.type')
            ->where('kabupaten.nama', $request->tempat_tujuan)
            ->join('provinsi_detail_allowance', 'provinsi_detail_allowance.id_prov', '=', 'kabupaten.id_prov')
            ->first();

        if ($request->lokasi_kegiatan == 'Dalam Negeri') {
            if (!$plafond || ($plafond && !$plafond->type)) {
                $data = new \App\Models\PlafondDinas();
                $data->tunjangan_makanan = 0;
                $data->tunjangan_harian = 0;
            } else {
                $data = \App\Models\PlafondDinas::where('organisasi_position_id', $position)->where('plafond_type', $plafond->type)->first();
                if (!$data) {
                    $data = new \App\Models\PlafondDinas();
                    $data->tunjangan_makanan = 0;
                    $data->tunjangan_harian = 0;
                }
            }
        } else {
            $data = \App\Models\PlafondDinasLuarNegeri::where('organisasi_position_id', $position)->first();
            if (!$data) {
                $data = new \App\Models\PlafondDinasLuarNegeri();
                $data->tunjangan_makanan = 0;
                $data->tunjangan_harian = 0;
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $data['business_trip'] = new TrainingResource(Training::findOrFail($id));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getApproval(Request $request)
    {
        $status = $request->status ?: "all";
        $user = Auth::user();
        $approval = null;
        $cek_transfer_approve = TransferSetting::where('user_id', auth()->user()->id)->first();

        if ($status == 'ongoing') {
            $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                $join->on('training.id', '=', 'h.training_id')
                    ->where('h.setting_approval_level_id', '=', DB::raw('(select min(setting_approval_level_id) from history_approval_training where training_id = training.id and (is_approved is null or (is_approved_claim is null and training.status = 2)))'))
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->where(function ($query) {
                    $query->where('training.status', 1)->orWhere('training.status_actual_bill', 1);
                })
                ->orderBy('created_at', 'DESC')
                ->select('training.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function ($query) {
                                $query->where('training.status', 2)->orWhere('training.status_actual_bill', 2);
                            })
                            ->where(function($query) {
                                $query->where('training.is_transfer','!=',1)->orWhere('training.is_transfer_claim','!=',1)->where('training.status_actual_bill', '=', 2);
                            });
                        })
                        ->orderBy('created_at', 'DESC')
                        ->groupBy('training.id')
                        ->select('training.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function ($query) {
                                $query->where('training.status', 2)->orWhere('training.status_actual_bill', 2);
                            })
                            ->where(function($query) {
                                $query->where('training.is_transfer','!=',1)->orWhere('training.is_transfer_claim','!=',1)->where('training.status_actual_bill', '=', 2);
                            });
                        })
                        ->orderBy('created_at', 'DESC')
                        ->select('training.*');
                    
                    foreach($transfer->get() as $no => $tf){
                        $total_disetujui = $tf->sub_total_1_disetujui + $tf->sub_total_2_disetujui + $tf->sub_total_3_disetujui + $tf->sub_total_4_disetujui;
                        $total = $tf->sub_total_1_disetujui + $tf->sub_total_2_disetujui + $tf->sub_total_3_disetujui + $tf->sub_total_4_disetujui - $tf->pengambilan_uang_muka;

                        if($total_disetujui != 0 && $total != 0 ){
                            $cek[$no] = $tf->id;
                        }
                        if($total_disetujui == 0){
                            $cek[$no] = $tf->id;
                        }
                    }
                    //return $cek;
                    if(isset($cek)){
                        $approval = $transfer->whereIn('training.id', $cek)->get()->merge($approval->get());
                    }
                    else{
                        $approval = $transfer->get()->merge($approval->get());
                    }
                    $approvalId = $approval->pluck('id');
                    $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id');})
                        ->orderBy('training.created_at','DESC')
                        ->whereIn('training.id', $approvalId)
                        ->groupBy('training.id')
                        ->select('training.*');
                    
                }

        } else if ($status == 'history') {
            $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                $join->on('training.id', '=', 'h.training_id')
                    ->whereNotNull('h.is_approved')
                    ->where(function ($query) {
                        $query->where('training.status_actual_bill', '!=', 1)->orWhereNull('training.status_actual_bill')->orWhereNotNull('h.is_approved_claim');
                    })
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('training.created_at', 'DESC')
                ->select('training.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function ($query) {
                                $query->where('training.status_actual_bill', '!=', 1)->orWhereNull('training.status_actual_bill');
                            });
                        })
                        ->where('training.is_transfer', '=',1)
                        ->orWhere('training.is_transfer_claim', '=',1)
                        ->orderBy('training.created_at', 'DESC')
                        ->groupBy('training.id')
                        ->select('training.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id')
                            ->whereNotNull('h.is_approved')
                            ->where(function ($query) {
                                $query->where('training.status_actual_bill', '!=', 1)->orWhereNull('training.status_actual_bill');
                            });
                        })
                        ->where('training.is_transfer', '=',1)
                        ->orWhere('training.is_transfer_claim', '=',1)
                        ->orderBy('training.created_at', 'DESC')
                        ->select('training.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    
                    $approvalId = $approval->pluck('id');
                    $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id');})
                        ->orderBy('training.created_at','DESC')
                        ->whereIn('training.id', $approvalId)
                        ->groupBy('training.id')
                        ->select('training.*');
                    
                }
        } else if ($status == 'all') {
            $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                $join->on('training.id', '=', 'h.training_id')
                    ->where('h.structure_organization_custom_id', '=', $user->structure_organization_custom_id);
            })
                ->orderBy('created_at', 'DESC')
                ->select('training.*');

                if($approval->count() == 0 && $cek_transfer_approve!=null){
                    $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id');
                        })
                        ->orderBy('created_at', 'DESC')
                        ->groupBy('training.id')
                        ->select('training.*');
                }
                else if($approval->count() > 0 && $cek_transfer_approve!=null){
                    $transfer = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id');
                        })
                        ->orderBy('created_at', 'DESC')
                        ->select('training.*');
                    
                    $approval = $transfer->get()->merge($approval->get());
                    
                    $approvalId = $approval->pluck('id');
                    $approval = Training::join('history_approval_training as h', function ($join) use ($user) {
                        $join->on('training.id', '=', 'h.training_id');})
                        ->orderBy('training.created_at','DESC')
                        ->whereIn('training.id', $approvalId)
                        ->groupBy('training.id')
                        ->select('training.*');
                    
                }
        }
        $totalData = $approval->get()->count();
        $approval = $approval->paginate(10);
        $data = [
            'current_page' => $approval->currentPage(), // get current page number
            'total_page' => $approval->total() ? $approval->lastPage() : $approval->total(), // get last page number
            'total_data' => $totalData,
            'business_trips' => TrainingResource::collection($approval),
        ];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully',
                'data' => $data,
            ], 200);
    }

    public function approve(Request $request)
    {
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'business_trip.id' => 'required|exists:training,id',
            'approval.note' => "required",
            'approval.is_approved' => "required|in:1,0",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $training = Training::find($request->business_trip['id']);
        $params = getEmailConfig();
        $params['data'] = $training;
        $params['value'] = $training->historyApproval;
        $params['subject'] = get_setting('mail_name') . ' - Business Trip';
        $params['view'] = 'email.training-approval-custom';

        $approval = HistoryApprovalTraining::where(['training_id' => $training->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->first();
        $approval->approval_id = $user->id;
        $approval->is_approved = $request->approval['is_approved'];
        $approval->date_approved = date('Y-m-d H:i:s');
        $approval->note = $request->approval['note'];
        $approval->save();

        $db = Config::get('database.default', 'mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if ($approval->is_approved == 0) { // Jika rejected
            $training->status = 3;
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $training->user->name . '</strong>,</p> <p>  Submission of your Business Trip <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if (!empty($training->user->email)) {
                $params['email'] = $training->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Business Trip";
            $notifType = "business_trip";    
            if ($training->user->firebase_token) {
                array_push($userApprovalTokens, $training->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower($request->company), $training->user->id, $training, $notifType);
        } else if ($approval->is_approved == 1) {
            $lastApproval = $training->historyApproval->last();
            if ($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id) {
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $training->user->name . '</strong>,</p> <p>  Submission of your Business Trip <strong style="color: green;">APPROVED</strong>.</p>';
                $training->status = 2;
                Config::set('database.default', 'mysql');
                if (!empty($training->user->email)) {
                    $params['email'] = $training->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Business Trip";
                $notifType = "business_trip";
                if ($training->user->firebase_token) {
                    array_push($userApprovalTokens, $training->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower($request->company), $training->user->id, $training, $notifType);
                $userApproval = TransferSetting::get();
                if($userApproval && $training->pengambilan_uang_muka > 0) {        
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if ($value->user->email == "") continue;
                        $params['total']    = $training->pengambilan_uang_muka;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $training->user->name .'  / '.  $training->user->nik .' applied for Business Trip and currently waiting your payment.</p>';
                        $params['email'] = $value->user->email;
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);

                        $notifTitle = "Transfer Business Trip";
                        $notifType  = "transfer_business_trip_approve";
                        if($value->user->firebase_token) {
                            array_push($userApprovalTokens, $value->user->firebase_token);
                            $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                            $userApprovalTokens = [];
                        }
                        \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $training, $notifType);
                    }
                    Config::set('database.default', $db);
                }

            } else {
                $training->status = 1;
                $nextApproval = HistoryApprovalTraining::where(['training_id' => $training->id, 'setting_approval_level_id' => ($approval->setting_approval_level_id + 1)])->first();
                if ($nextApproval) {
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if ($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") {
                                continue;
                            }

                            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $training->user->name . '  / ' . $training->user->nik . ' applied for Business Trip and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> ' . $training->user->name . '  / ' . $training->user->nik . ' applied for Business Trip and currently waiting your approval.</p>';
                        $notifTitle = "Business Trip Approval";
                        $notifType = "business_trip_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $training->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $training, $notifType);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Business Trip Successfully Processed !',
            ], 200);
    }

    public function sentNotif($title, $content, $type, $token, $id){
        if(count($token) > 0){
            $config = [
                'title' => $title,
                'content' => strip_tags($content),
                'type' => $type,
                'firebase_token' => $token
            ];
            $notifData = [
                'id' => $id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }
        return 'sent notif success';
    }

    public function claim(Request $request)
    {
        $user = Auth::user();
        $company_url = ($request->company ? $request->company : "umum") . '/';
        $validator = Validator::make($request->all(), [
            'business_trip.id' => "required|exists:training,id",
            'business_trip.status_actual_bill' => "required|in:1,4",
            'business_trip.sub_total_1' => "integer",
            'business_trip.sub_total_2' => "integer",
            'business_trip.sub_total_3' => "integer",
            'business_trip.sub_total_4' => "integer",
            'acomodation' => "array",
            'acomodation.*.date_acomodation' => "nullable|date",
            'acomodation.*.training_transportation_type_id' => "nullable|exists:training_transportation_type,id",
            // 'acomodation.*.nominal_acomodation' => "required_with:acomodation.*|integer",
            // 'acomodation.*.note_acomodation' => "required_with:acomodation.*",
            'allowance' => "array",
            'allowance.*.date_allowance' => "nullable|date",
            'allowance.*.meal_plafond' => "integer",
            // 'allowance.*.morning' => "required_with:allowance.*|integer",
            // 'allowance.*.afternoon' => "required_with:allowance.*|integer",
            // 'allowance.*.evening' => "required_with:allowance.*|integer",
            // 'allowance.*.note_allowance' => "required_with:allowance.*",
            'daily' => "array",
            'daily.*.date_daily' => "nullable|date",
            'daily.*.daily_plafond' => "integer",
            // 'daily.*.nominal_daily' => "required_with:daily.*|integer",
            // 'daily.*.note_daily' => "required_with:daily.*",
            'other' => "array",
            'other.*.date_other' => "nullable|date",
            // 'other.*.nominal_other' => "required_with:other.*|integer",
            // 'other.*.note_other' => "required_with:other.*",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        $validator->sometimes('acomodation.*.date_acomodation', 'required_with:acomodation.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        $validator->sometimes('acomodation.*.training_transportation_type_id', 'required_with:acomodation.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        $validator->sometimes('allowance.*.date_allowance', 'required_with:allowance.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        $validator->sometimes('allowance.*.meal_plafond', 'required_with:allowance.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        $validator->sometimes('daily.*.date_daily', 'required_with:daily.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        $validator->sometimes('daily.*.daily_plafond', 'required_with:daily.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        $validator->sometimes('other.*.date_other', 'required_with:other.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        $validator->sometimes('other.*.description_other', 'required_with:other.*', function ($request) {
            return $request->business_trip['status_actual_bill'] == 1;
        });
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }
        if (!isset($request->acomodation) && !isset($request->allowance) && !isset($request->daily) && !isset($request->other) && $request->business_trip['status_actual_bill'] == 1) {
            return response()->json(['status' => 'error', 'message' => 'Cant submit without bill !'], 404);
        }

        $training = Training::find($request->business_trip['id']);
        $training->sub_total_1 = 0;
        $training->sub_total_2 = 0;
        $training->sub_total_3 = 0;
        $training->sub_total_4 = 0;
        $training->status_actual_bill = $request->business_trip['status_actual_bill'] ? $request->business_trip['status_actual_bill'] : 1;
        $training->date_submit_actual_bill = date('Y-m-d');

        TrainingTransportation::where('training_id', $training->id)->delete();
        if ($request->acomodation) {
            foreach ($request->acomodation as $key => $item) {
                $acomodation = new TrainingTransportation();
                $acomodation->training_id = $training->id;
                $acomodation->date = isset($item['date_acomodation']) ? $item['date_acomodation'] : null;
                $acomodation->training_transportation_type_id = isset($item['training_transportation_type_id']) ? $item['training_transportation_type_id'] : null;
                $training->sub_total_1 += ($acomodation->nominal = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_acomodation']) ? $item['nominal_acomodation'] : 0));
                $acomodation->note = isset($item['note_acomodation']) ? $item['note_acomodation'] : null;

                if ($request->file('file_struk_acomodation') && isset($request->file('file_struk_acomodation')[$key])) {
                    $image = $request->file('file_struk_acomodation')[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $destinationPath = public_path('storage/file-acomodation/') . $company_url;
                    $image->move($destinationPath, $name);
                    $acomodation->file_struk = $company_url . $name;
                } else if (isset($request->file_struk_acomodation[$key])) {
                    $acomodation->file_struk = $request->file_struk_acomodation[$key];
                }
                $acomodation->save();
            }
        }

        TrainingAllowance::where('training_id', $training->id)->delete();
        if ($request->allowance) {
            foreach ($request->allowance as $key => $item) {
                $form = new TrainingAllowance();
                $form->training_id = $training->id;
                $form->date = isset($item['date_allowance']) ? $item['date_allowance'] : null;
                $form->meal_plafond = preg_replace('/[^0-9]/', '', isset($item['meal_plafond']) ? $item['meal_plafond'] : 0);
                $training->sub_total_2 += ($form->morning = (int) preg_replace('/[^0-9]/', '', isset($item['morning']) ? $item['morning'] : 0));
                $training->sub_total_2 += ($form->afternoon = (int) preg_replace('/[^0-9]/', '', isset($item['afternoon']) ? $item['afternoon'] : 0));
                $training->sub_total_2 += ($form->evening = (int) preg_replace('/[^0-9]/', '', isset($item['evening']) ? $item['evening'] : 0));
                $form->note = isset($item['note_allowance']) ? $item['note_allowance'] : null;

                if ($request->file('file_struk_allowance') && isset($request->file('file_struk_allowance')[$key])) {
                    $image = $request->file('file_struk_allowance')[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $destinationPath = public_path('storage/file-allowance/') . $company_url;
                    $image->move($destinationPath, $name);
                    $form->file_struk = $company_url . $name;
                } else if (isset($request->file_struk_allowance[$key])) {
                    $form->file_struk = $request->file_struk_allowance[$key];
                }
                $form->save();
            }
        }

        TrainingDaily::where('training_id', $training->id)->delete();
        if ($request->daily) {
            foreach ($request->daily as $key => $item) {
                $daily = new TrainingDaily();
                $daily->training_id = $training->id;
                $daily->date = isset($item['date_daily']) ? $item['date_daily'] : null;
                $daily->daily_plafond = preg_replace('/[^0-9]/', '', isset($item['daily_plafond']) ? $item['daily_plafond'] : 0);
                $training->sub_total_3 += ($daily->daily = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_daily']) ? $item['nominal_daily'] : 0));
                $daily->note = isset($item['note_daily']) ? $item['note_daily'] : null;

                if ($request->file('file_struk_daily') && isset($request->file('file_struk_daily')[$key])) {
                    $image = $request->file('file_struk_daily')[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $destinationPath = public_path('storage/file-daily/') . $company_url;
                    $image->move($destinationPath, $name);
                    $daily->file_struk = $company_url . $name;
                } else if (isset($request->file_struk_daily[$key])) {
                    $daily->file_struk = $request->file_struk_daily[$key];
                }
                $daily->save();
            }
        }

        TrainingOther::where('training_id', $training->id)->delete();
        if ($request->other) {
            foreach ($request->other as $key => $item) {
                $other = new TrainingOther();
                $other->training_id = $training->id;
                $other->date = isset($item['date_other']) ? $item['date_other'] : null;
                $other->description = isset($item['description_other']) ? $item['description_other'] : null;
                $training->sub_total_4 += ($other->nominal = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_other']) ? $item['nominal_other'] : 0));
                $other->note = isset($item['note_other']) ? $item['note_other'] : null;

                if ($request->file('file_struk_other') && isset($request->file('file_struk_other')[$key])) {
                    $image = $request->file('file_struk_other')[$key];
                    $name = md5(rand() . $image->getClientOriginalName() . time()) . "." . $image->getClientOriginalExtension();
                    $destinationPath = public_path('storage/file-other/') . $company_url;
                    $image->move($destinationPath, $name);
                    $other->file_struk = $company_url . $name;
                } else if (isset($request->file_struk_other[$key])) {
                    $other->file_struk = $request->file_struk_other[$key];
                }
                $other->save();
            }
        }

        $training->sub_total_1 = isset($request->business_trip['sub_total_1']) ? $request->business_trip['sub_total_1'] : $training->sub_total_1;
        $training->sub_total_2 = isset($request->business_trip['sub_total_2']) ? $request->business_trip['sub_total_2'] : $training->sub_total_2;
        $training->sub_total_3 = isset($request->business_trip['sub_total_3']) ? $request->business_trip['sub_total_3'] : $training->sub_total_3;
        $training->sub_total_4 = isset($request->business_trip['sub_total_4']) ? $request->business_trip['sub_total_4'] : $training->sub_total_4;
        $training->save();

        if ($training->status_actual_bill == 1 || $training->status_actual_bill == 4) {
            HistoryApprovalTraining::where('training_id', $training->id)->update([
                'approval_id_claim' => null,
                'is_approved_claim' => null,
                'date_approved_claim' => null,
            ]);
        }

        if ($training->status_actual_bill == 1) {
            $historyApprov = HistoryApprovalTraining::where('training_id', $training->id)->orderBy('setting_approval_level_id', 'asc')->get();
            if (count($historyApprov) > 0) {
                $settingApprovalItem = $historyApprov[0]->structure_organization_custom_id;
                $userApproval = user_approval_custom($settingApprovalItem);
                $params = getEmailConfig();
                $db = Config::get('database.default', 'mysql');

                $params['data'] = $training;
                $params['value'] = $historyApprov;
                $params['view'] = 'email.training-approval-custom';
                $params['subject'] = get_setting('mail_name') . ' - Claim Business Trip';
                if ($userApproval) {
                    Config::set('database.default', 'mysql');
                    foreach ($userApproval as $key => $value) {
                        if (empty($value->email)) {
                            continue;
                        }

                        $params['email'] = $value->email;
                        $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $training->user->name . '  / ' . $training->user->nik . ' applied for Claim of Business Trip and currently waiting your approval.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
                    $params['text'] = '<p> ' . $training->user->name . '  / ' . $training->user->nik . ' applied for Claim of Business Trip and currently waiting your approval.</p>';
                }
            }

            $userApprovalTokens = user_approval_firebase_tokens($settingApprovalItem);
            info("structure id " . $settingApprovalItem);
            info($userApprovalTokens);
        
            foreach (user_approval_id($settingApprovalItem) as $value) {
                \FRDHelper::setNewData(strtolower($request->company), $value, $training, 'training_approval');
            }
    
            if (count($userApprovalTokens) > 0) {
                $config = [
                    'title' => "Claim Business Trip Approval",
                    'content' => strip_tags($params['text']),
                    'type' => 'training_approval',
                    'firebase_token' => $userApprovalTokens,
                ];
                $notifData = [
                    'id' => $training->id,
                ];
                info($userApprovalTokens);
                $db = Config::get('database.default', 'mysql');
                Config::set('database.default', 'mysql');
                dispatch((new \App\Jobs\SendPushTokens($notifData, $config))->onQueue('push'));
                Config::set('database.default', $db);
            }
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Claim Business Trip Successfully Processed !',
            ], 200);
    }

    public function approveClaim(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'business_trip.id' => "required|exists:training,id",
            'business_trip.sub_total_1_approved' => "integer",
            'business_trip.sub_total_2_approved' => "integer",
            'business_trip.sub_total_3_approved' => "integer",
            'business_trip.sub_total_4_approved' => "integer",
            'acomodation' => "array",
            'acomodation.*.id' => "required_with:acomodation.*|exists:training_transportation,id",
            // 'acomodation.*.nominal_acomodation_approved' => "required_with:acomodation.*|integer",
            'allowance' => "array",
            'allowance.*.id' => "required_with:allowance.*|exists:training_allowance,id",
            // 'allowance.*.morning_approved' => "required_with:allowance.*|integer",
            // 'allowance.*.afternoon_approved' => "required_with:allowance.*|integer",
            // 'allowance.*.evening_approved' => "required_with:allowance.*|integer",
            'daily' => "array",
            'daily.*.id' => "required_with:daily.*|exists:training_daily,id",
            // 'daily.*.nominal_daily_approved' => "required_with:daily.*|integer",
            'other' => "array",
            'other.*.id' => "required_with:other.*|exists:training_other,id",
            // 'other.*.nominal_other_approved' => "required_with:other.*|integer",
            'approval.note_claim' => "required",
            'approval.is_approved_claim' => "required|in:1,0",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()], 401);
        }

        $training = Training::find($request->business_trip['id']);
        $history =  HistoryApprovalTraining::where('training_id',$request->business_trip['id'])->where('structure_organization_custom_id', \Auth::user()->structure_organization_custom_id)->first();

        $training->sub_total_1_disetujui = 0;
        $training->sub_total_2_disetujui = 0;
        $training->sub_total_3_disetujui = 0;
        $training->sub_total_4_disetujui = 0;

        if ($request->acomodation) {
            foreach ($request->acomodation as $key => $item) {
                $acomodation = TrainingTransportation::find($item['id']);
                $training->sub_total_1_disetujui += ($acomodation->nominal_approved = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_acomodation_approved']) ? $item['nominal_acomodation_approved'] : 0));
                $acomodation->note_approval = isset($item['note_acomodation_approved']) ? $item['note_acomodation_approved'] : NULL;
                $acomodation->save();

                $report = new TrainingTransportationReport;
                $report->training_id = $request->business_trip['id'];
                $report->training_transportation_id = $item['id'];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->approved = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_acomodation_approved']) ? $item['nominal_acomodation_approved'] : 0);
                $report->note = isset($item['note_acomodation_approved']) ? $item['note_acomodation_approved'] : NULL;
                $report->save();

            }
        }
        if ($request->allowance) {
            foreach ($request->allowance as $key => $item) {
                $allowance = TrainingAllowance::find($item['id']);
                $training->sub_total_2_disetujui += ($allowance->morning_approved = (int) preg_replace('/[^0-9]/', '', isset($item['morning_approved']) ? $item['morning_approved'] : 0));
                $training->sub_total_2_disetujui += ($allowance->afternoon_approved = (int) preg_replace('/[^0-9]/', '', isset($item['afternoon_approved']) ? $item['afternoon_approved'] : 0));
                $training->sub_total_2_disetujui += ($allowance->evening_approved = (int) preg_replace('/[^0-9]/', '', isset($item['evening_approved']) ? $item['evening_approved'] : 0));
                $allowance->note_approval = isset($item['note_allowance_approved']) ? $item['note_allowance_approved'] : NULL;
                $allowance->save();

                $report = new TrainingAllowanceReport;
                $report->training_id = $request->business_trip['id'];
                $report->training_allowance_id =$item['id'];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->morning_approved              = (int) preg_replace('/[^0-9]/', '', isset($item['morning_approved']) ? $item['morning_approved'] : 0);
                $report->afternoon_approved            = (int) preg_replace('/[^0-9]/', '', isset($item['afternoon_approved']) ? $item['afternoon_approved'] : 0);
                $report->evening_approved              = (int) preg_replace('/[^0-9]/', '', isset($item['evening_approved']) ? $item['evening_approved'] : 0);
                $report->note = isset($item['note_allowance_approved']) ? $item['note_allowance_approved'] : NULL;
                $report->save();
            }
        }
        if ($request->daily) {
            foreach ($request->daily as $key => $item) {
                $daily = TrainingDaily::find($item['id']);
                $training->sub_total_3_disetujui += ($daily->daily_approved = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_daily_approved']) ? $item['nominal_daily_approved'] : 0));
                $daily->note_approval = isset($item['note_daily_approved']) ? $item['note_daily_approved'] : NULL;
                $daily->save();

                $report = new TrainingDailyReport;
                $report->training_id = $request->business_trip['id'];
                $report->training_daily_id = $item['id'];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->approved = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_daily_approved']) ? $item['nominal_daily_approved'] : 0);
                $report->note = isset($item['note_daily_approved']) ? $item['note_daily_approved'] : NULL;
                $report->save();
            }
        }
        if ($request->other) {
            foreach ($request->other as $key => $item) {
                $other = TrainingOther::find($item['id']);
                $training->sub_total_4_disetujui += ($other->nominal_approved = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_other_approved']) ? $item['nominal_other_approved'] : 0));
                $other->note_approval = isset($item['note_other_approved']) ? $item['note_other_approved'] : NULL;
                $other->save();

                $report = new TrainingOtherReport;
                $report->training_id = $request->business_trip['id'];
                $report->training_other_id = $item['id'];
                $report->level_id = $history->setting_approval_level_id;
                $report->approval_id = \Auth::user()->id;
                $report->approved = (int) preg_replace('/[^0-9]/', '', isset($item['nominal_other_approved']) ? $item['nominal_other_approved'] : 0);
                $report->note = isset($item['note_other_approved']) ? $item['note_other_approved'] : NULL;
                $report->save();
            }
        }

        $training->sub_total_1_disetujui = isset($request->business_trip['sub_total_1_approved']) ? $request->business_trip['sub_total_1_approved'] : $training->sub_total_1_disetujui;
        $training->sub_total_2_disetujui = isset($request->business_trip['sub_total_2_approved']) ? $request->business_trip['sub_total_2_approved'] : $training->sub_total_2_disetujui;
        $training->sub_total_3_disetujui = isset($request->business_trip['sub_total_3_approved']) ? $request->business_trip['sub_total_3_approved'] : $training->sub_total_3_disetujui;
        $training->sub_total_4_disetujui = isset($request->business_trip['sub_total_4_approved']) ? $request->business_trip['sub_total_4_approved'] : $training->sub_total_4_disetujui;
        $total_reimbursement_disetujui =  $training->sub_total_1_disetujui + $training->sub_total_2_disetujui + $training->sub_total_3_disetujui + $training->sub_total_4_disetujui - $training->pengambilan_uang_muka;

        $params = getEmailConfig();
        $params['data'] = $training;
        $params['value'] = $training->historyApproval;
        $params['subject'] = get_setting('mail_name') . ' - Claim Business Trip';
        $params['view'] = 'email.training-approval-custom';

        $approval = HistoryApprovalTraining::where(['training_id' => $training->id, 'structure_organization_custom_id' => $user->structure_organization_custom_id])->first();
        $approval->approval_id_claim = $user->id;
        $approval->is_approved_claim = $request->approval['is_approved_claim'];
        $approval->date_approved_claim = date('Y-m-d H:i:s');
        $approval->note_claim = $request->approval['note_claim'];
        $approval->save();

        $db = Config::get('database.default', 'mysql');

        $notifTitle = "";
        $notifType = "";
        $userApprovalTokens = [];
        $userApprovalIds = [];

        if ($approval->is_approved_claim == 0) { // Jika rejected
            $training->status_actual_bill = 3;
            $params['text'] = '<p><strong>Dear Sir/Madam ' . $training->user->name . '</strong>,</p> <p>  Submission of your Claim of Business Trip <strong style="color: red;">REJECTED</strong>.</p>';
            Config::set('database.default', 'mysql');
            if ($training->user->email && $training->user->email != "") {
                $params['email'] = $training->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
            }
            Config::set('database.default', $db);

            $notifTitle = "Claim Business Trip";
            $notifType = "training_reject";    
            if ($training->user->firebase_token) {
                array_push($userApprovalTokens, $training->user->firebase_token);
                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                $userApprovalTokens = [];
            }
            \FRDHelper::setNewData(strtolower($request->company), $training->user->id, $training, $notifType);
            $other = TrainingOtherReport::where('training_id', $request->id)->delete();
            $allowance = TrainingAllowanceReport::where('training_id', $request->id)->delete();
            $transport = TrainingTransportationReport::where('training_id', $request->id)->delete();
            $daily = TrainingDailyReport::where('training_id', $request->id)->delete();

        } else if ($approval->is_approved_claim == 1) {
            $lastApproval = $training->historyApproval->last();
            if ($approval->structure_organization_custom_id == $lastApproval->structure_organization_custom_id) {
                $params['text'] = '<p><strong>Dear Sir/Madam ' . $training->user->name . '</strong>,</p> <p>  Submission of your Claim of Business Trip <strong style="color: green;">APPROVED</strong>.</p>';
                $training->status_actual_bill = 2;
                Config::set('database.default', 'mysql');
                if ($training->user->email && $training->user->email != "") {
                    $params['email'] = $training->user->email;
                    $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                    dispatch($job);
                }
                Config::set('database.default', $db);

                $notifTitle = "Claim Business Trip";
                $notifType = "training";
                if ($training->user->firebase_token) {
                    array_push($userApprovalTokens, $training->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower($request->company), $training->user->id, $training, $notifType);
                if($total_reimbursement_disetujui > 0){
                    $userApproval = TransferSetting::get();
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->user->email == "") continue;
                            $params['total']    = $total_reimbursement_disetujui;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $training->user->name .'  / '.  $training->user->nik .' applied for Claim of Business Trip and currently waiting your payment lack from business trip.</p>';
                            $params['email'] = $value->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                                
                            $notifTitle = "Transfer Claim Business Trip";
                            $notifType  = "transfer_claim_business_trip";
                            if($value->user->firebase_token) {
                                array_push($userApprovalTokens, $value->user->firebase_token);
                                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                                $userApprovalTokens = [];
                            }
                            \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $training, $notifType);
                        }
                        Config::set('database.default', $db);
                    }
                }
                elseif($total_reimbursement_disetujui < 0){
                    $userApproval = TransferSetting::get();
                    if($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->user->email == "") continue;
                            $params['total']    = -1 * $total_reimbursement_disetujui;
                            $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $training->user->name .'  / '.  $training->user->nik .' applied for Claim of Business Trip and total approved was greater than what was claimed, so she/he had to return the excess.</p>';
                            $params['email'] = $value->user->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                            
                            $notifTitle = "Transfer Claim Business Trip";
                            $notifType  = "transfer_claim_business_trip";
                            if($value->user->firebase_token) {
                                array_push($userApprovalTokens, $value->user->firebase_token);
                                $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                                $userApprovalTokens = [];
                            }
                            \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $training, $notifType);
                        }
                        Config::set('database.default', $db);
                    }

                    Config::set('database.default', 'mysql');
                    if($training->user->email && $training->user->email != "") {
                        $params['email'] = $training->user->email;
                        $params['total']    = -1 * $total_reimbursement_disetujui;
                        $params['text']     = '<p><strong>Dear Sir/Madam '. $training->user->name .'</strong>,</p> <p> Total claimed is less than the total approved, so you must return the excess. which will be followed up by the company.</p>';
                        $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                        dispatch($job);
                    }
                    Config::set('database.default', $db);
    
                    $userApprovalTokens = [];
                    $notifTitle = "Transfer Claim Business Trip";
                    $notifType  = "transfer_back_claim_business_trip_more";
                    if($training->user->firebase_token) {
                        array_push($userApprovalTokens, $training->user->firebase_token);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                        $userApprovalTokens = [];
                    }
                    \FRDHelper::setNewData(strtolower($request->company), $training->user->id, $training, $notifType);
                }
                elseif($total_reimbursement_disetujui == 0){
                    $training->is_transfer_claim = 1;
                }
            } else {
                $training->status_actual_bill = 1;
                $nextApproval = HistoryApprovalTraining::where(['training_id' => $training->id, 'setting_approval_level_id' => ($approval->setting_approval_level_id + 1)])->first();
                if ($nextApproval) {
                    $userApproval = user_approval_custom($nextApproval->structure_organization_custom_id);
                    if ($userApproval) {
                        Config::set('database.default', 'mysql');
                        foreach ($userApproval as $key => $value) {
                            if ($value->email == "") {
                                continue;
                            }

                            $params['text'] = '<p><strong>Dear Sir/Madam ' . $value->name . '</strong>,</p> <p> ' . $training->user->name . '  / ' . $training->user->nik . ' applied for Claim of Business Trip and currently waiting your approval.</p>';
                            $params['email'] = $value->email;
                            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                            dispatch($job);
                        }
                        Config::set('database.default', $db);

                        $params['text'] = '<p> ' . $training->user->name . '  / ' . $training->user->nik . ' applied for Claim of Business Trip and currently waiting your approval.</p>';
                        $notifTitle = "Claim Business Trip Approval";
                        $notifType = "training_approval";
                        $userApprovalTokens = user_approval_firebase_tokens($nextApproval->structure_organization_custom_id);
                        $userApprovalIds = user_approval_id($nextApproval->structure_organization_custom_id);
                        $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $training->id);
                        $userApprovalTokens = [];
                    }
                }
            }
        }
        $training->save();

        foreach ($userApprovalIds as $value) {
            \FRDHelper::setNewData(strtolower($request->company), $value, $training, $notifType);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Form Claim Business Trip Successfully Processed !',
            ], 200);
    }

    public function detailTransfer($id)
    {   
        $params['data'] = cek_transfer_setting_user($id);

        if (!$params['data']) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Your position for transfer is not defined yet. Please contact your admin!'
                ], 403);
        }

        $data['business_trip'] = new TrainingResource(Training::find($id));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get detail data transfer business trip',
                'data' => $data
            ], 200);
    }


    public function transfer(Request $request){
        //dd($request);
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'business_trip.id'                        => 'required|exists:training,id',
            'business_trip.disbursement'              => "required|in:Transfer,Next Payroll",
            'business_trip.is_transfer'               => "required|in:1,0",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = Training::find($request->business_trip['id']);
        $data->is_transfer= $request->business_trip['is_transfer'];
        $data->disbursement = $request->business_trip['disbursement'];
        $data->is_transfer_by = auth()->user()->id;

        if($request->hasFile('transfer_proof'))
        {
            $image = $request->transfer_proof;
            $name = md5($request->business_trip['id'].'transfer_proof').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
            $destinationPath = public_path('storage/training-custom/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = []; 

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Business Trip';
        $params['view']     = 'email.training-transfer';
        $params['total']    = $data->pengambilan_uang_muka;
        if($data->disbursement=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip will be merged with the next payroll.</p>';
        }
        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Transfer Business Trip";
        $notifType  = "transfer_business_trip";

        \FRDHelper::setNewData(strtolower($request->company), $data->user->id, $data, $notifType);

        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $data->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Business Trip Transfer Successfully Processed!',
            ], 200);
    }

    public function transferClaim(Request $request){
        //dd($request);
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'business_trip.id'                        => 'required|exists:training,id',
            'business_trip.disbursement_claim'              => "required|in:Transfer,Next Payroll",
            'business_trip.is_transfer_claim'               => "required|in:1,0",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = Training::find($request->business_trip['id']);
        $data->is_transfer_claim= $request->business_trip['is_transfer_claim'];
        $data->disbursement_claim = $request->business_trip['disbursement_claim'];
        $data->is_transfer_claim_by = auth()->user()->id;

        if($request->hasFile('transfer_proof_claim'))
        {
            $image = $request->transfer_proof_claim;
            $name = md5($request->business_trip['id'].'transfer_proof_claim').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
            $destinationPath = public_path('storage/training-custom/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof_claim = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $db = Config::get('database.default','mysql');
        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Claim Business Trip';
        $params['view']     = 'email.training-transfer';
        $params['total']    = $data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui + $data->sub_total_4_disetujui - $data->pengambilan_uang_muka;
        if($data->disbursement_claim=='Transfer'){
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip Claim has been transfered.</p>';
        }else{
            $params['text']     = '<p><strong>Dear Sir/Madam '. $data->user->name .'</strong>,</p> <p>  Submission of your Business Trip Claim will be merged with the next payroll.</p>';
        }
        Config::set('database.default', 'mysql');

        if(!empty($data->user->email)) {
            $params['email'] = $data->user->email;
            $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
            dispatch($job);
        }
        Config::set('database.default', $db);
        
        $notifTitle = "Transfer Claim Business Trip";
        $notifType  = "transfer_claim_business_trip_less";

        \FRDHelper::setNewData(strtolower($request->company), $data->user->id, $data, $notifType);

        if($data->user->firebase_token) {
            array_push($userApprovalTokens, $data->user->firebase_token);
        }

        if(count($userApprovalTokens) > 0){
            $config = [
                'title' => $notifTitle,
                'content' => strip_tags($params['text']),
                'type' => $notifType,
                'firebase_token' => $userApprovalTokens
            ];
            $notifData = [
                'id' => $data->id
            ];
            $db = Config::get('database.default', 'mysql');
            Config::set('database.default', 'mysql');
            dispatch((new \App\Jobs\SendPushTokens($notifData,$config))->onQueue('push'));
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Business Trip Transfer Claim Successfully Processed !',
            ], 200);
    }

    public function transferUser($id)
    {
        $params['data']         = Training::where('id', $id)->first();

        if (!IsAccess($params['data'])) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'You don\'t have permission to perform this action!'
                ], 403);
        }

        $data['business_trip'] = new TrainingResource(Training::find($id));

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully get detail data transfer business trip',
                'data' => $data
            ], 200);
    }

    public function prosesTransferUser(Request $request){
        info($request->all());
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'business_trip.id'                        => 'required|exists:training,id',
            'business_trip.is_transfer_claim'               => "required|in:1,0",
        ], [
            "required" => 'Complete the field before submit',
            "required_if" => 'Complete the field before submit',
            "required_with" => 'Complete the field before submit',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()->first()],401);
        }

        $data = Training::find($request->business_trip['id']);
        $data->is_transfer_claim= $request->business_trip['is_transfer_claim'];
        $data->is_transfer_claim_by = auth()->user()->id;

        if($request->hasFile('transfer_proof_claim'))
        {
            $image = $request->transfer_proof_claim;
            $name = md5($request->business_trip['id'].'transfer_proof_claim_by_user').'.'.$image->getClientOriginalExtension();
            $company_url = ($request->company ? $request->company : "umum") . '/';
            $destinationPath = public_path('storage/training-custom/transfer-proof/').$company_url;
            $image->move($destinationPath, $name);
            $data->transfer_proof_claim = $company_url.$name;
        }
        $data->save();

        $userApprovalTokens = [];

        $params             = getEmailConfig();
        $params['data']     = $data;
        $params['value']    = $data->historyApproval;
        $params['subject']  = get_setting('mail_name') . ' - Transfer Claim Business Trip';
        $params['view']     = 'email.training-transfer';
        
        $db = Config::get('database.default','mysql');

        $params['total']    = -1 * ($data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui + $data->sub_total_4_disetujui - $data->pengambilan_uang_muka);
        $userApproval = TransferSetting::get();

        if($userApproval) {
            Config::set('database.default', 'mysql');
            foreach ($userApproval as $key => $value) {
                if ($value->user->email == "") continue;
                $params['text']     = '<p><strong>Dear Sir/Madam '. $value->user->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' has been sent transfer proof business trip.</p>';
                $params['email'] = $value->user->email;
                $job = (new \App\Jobs\SendEmail($params))->onQueue('email');
                dispatch($job);
                
                $notifTitle = "Transfer Claim Business Trip";
                $notifType  = "transfer_claim_business_trip_more";
                if($value->user->firebase_token) {
                    array_push($userApprovalTokens, $value->user->firebase_token);
                    $this->sentNotif($notifTitle, $params['text'], $notifType, $userApprovalTokens, $data->id);
                    $userApprovalTokens = [];
                }
                \FRDHelper::setNewData(strtolower($request->company), $value->user->id, $data, $notifType);
            }
            Config::set('database.default', $db);
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Business Trip Transfer Claim Successfully Processed !',
            ], 200);
    }
}
