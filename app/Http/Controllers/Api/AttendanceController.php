<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Http\Resources\AbsensiItemResources;
use App\Models\AbsensiItem;
use App\Models\Cabang;
use App\Models\RemoteAttendance;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\ShiftDetail;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\CutiBersama;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DateTimeZone;

class AttendanceController extends BaseApiController
{
    public function __construct()
    {
        $this->middleware(ConnectionMiddleware::class);
        $this->middleware('jwt.auth');
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if($validator->fails())
            return response()->json(['status' => 'error','message'=>$validator->errors()->first()], 401);

        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        $currentUser = Auth::user();
        $items  = AbsensiItem::where('user_id',$currentUser->id)
            ->where('date','>=',$start_date)
            ->where('date','<=',$end_date)
            ->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc");


        $statistic = [];
        $statistic['total_attendance'] = (clone $items)->count();
        $statistic['total_late']       = (clone $items)->whereNotNull('late')->count();
        $statistic['total_early']      = (clone $items)->whereNotNull('early')->count();
        $statistic['total_forget']     = (clone $items)->whereNull('clock_out')->count();
        $data                          = (clone $items)->get();
        $minutesWork                   = 0;
        foreach ($data as $item){
            if($item->work_time){
                $temp = explode(":",$item->work_time);
                if(count($temp)>=2){
                    $hour         = (int)$temp[0];
                    $minutes      = (int)$temp[1];
                    $minutesWork += ($hour*60)+$minutes;
                }
            }
        }
        $statistic['total_work_hour']  = round((double)$minutesWork/60,1);


        $data = [
            'current_page'  => ($request->page?(int)$request->page:1),
            'total_page'    => $items->count()==0?0:((int)($items->count()/10)+1),
            'attendances'   => AbsensiItemResources::collection($items->paginate(10)),
            'statistic'     => $statistic,
        ];

        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $data
            ],
            200
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClockStatus(Request $request){
        $currentUser = Auth::user();
        $data = [
            'server_time' => date("Y-m-d H:i:s"),
            'out_of_office' => $currentUser->structure?$currentUser->structure->remote_attendance:0
        ];
        $date = null;
        $data['timezone'] = null;
        $data['type']     = 'server';
        if($currentUser->branch && $currentUser->branch->timezone != null){


            $data['timezone'] = $currentUser->branch->timezone;
            $data['branch_time']  = $this->getDatetime($currentUser->branch->timezone);
            $data['type']         = 'branch';
        }
        else{
            $data['branch_time']  = null;
        }
        $currentDate      = date('Y-m-d');
        $remoteAttendance = RemoteAttendance::where('user_id',$currentUser->id)
            ->where('start_date','<=',$currentDate)
            ->where('end_date','>=',$currentDate)
            ->first();

        if($remoteAttendance){
            $data['remote_time']  = $this->getDatetime($remoteAttendance->timezone);
            $data['type']         = 'remote';
            $data['timezone']     = $remoteAttendance->timezone;
        }else{
            $data['remote_time']  = null;
        }
        $data['remote_attendance']   = $remoteAttendance;

        if($data['timezone'] == null){
            $data['timezone'] = $this->getServerTimezone();
        }
        $today = $this->getDate($data['timezone']);
        $todayDateTime = $this->getDatetime($data['timezone']);
        $previousDateTime = date_create($todayDateTime)->modify('-1 days')->format('Y-m-d H:i:s');
        $previousDay = date_create($todayDateTime)->modify('-1 days')->format('Y-m-d');
        $absensi = AbsensiItem::where('user_id', $currentUser->id)->whereRaw("CONCAT(`date`, ' ', `clock_in`,':00') >= '".$previousDateTime."'")->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc");
        
        $normal_absensi = clone $absensi;
        $normal_absensi = $normal_absensi->where('shift_type', 'normal')->first();

        $absensi = $absensi->first();

        $shift = Shift::find($currentUser->shift_id);

        if((!$shift && $absensi && !is_null($absensi->clock_out)) || ($shift && $absensi && !is_null($absensi->clock_out) && (!$normal_absensi || ($normal_absensi && $normal_absensi->date_shift != $today)))) { // Jika dia tidak punya shift dan sudah clock out || dia punya shift dan absensinya sudah lewat kemarin
            $absensi = null;
        }

        $data['absensi'] = $absensi;
        $data['date_shift'] = $today;
        if($absensi) {
            $data['date_shift'] = $absensi->date_shift;
        } else if($shift && !$normal_absensi) { // Kalau dia tidak absen dalam 24 jam terakhir namun punya shift
            $shiftDetail = ShiftDetail::where('shift_id', $shift->id)->whereRaw("day = '".date('l', strtotime($previousDateTime))."'")->first();  // Cek dulu shift previous day
            $timeNow = $this->getDatetime($data['timezone'],true);
            if($shiftDetail && $shiftDetail->clock_in > $shiftDetail->clock_out && $shiftDetail->clock_out >= $timeNow){ // jika previous day overlap & masih masuk shiftnya...
                $data['date_shift'] = $previousDay;
            }
        }
        
        $data['shift'] = !$shift ? $shift : (count($shiftRoaster = $shift->details->where('day', date('l'))) && ($shift->is_holiday || !count(hari_libur(\Carbon\Carbon::parse($data['date_shift'])->startOfDay(), \Carbon\Carbon::parse($data['date_shift'])->endOfDay()))) && ($shift->is_collective || !CutiBersama::where('dari_tanggal', \Carbon\Carbon::parse($data['date_shift'])->format('Y-m-d'))->where('impacttoleave', 0)->first()) ? [$shiftRoaster->first()] : []);

        $company = [
            'title' => null,
            'attendance_company' => null,
            'attendance_news' => null,
            'attendance_logo' => null
        ];
        $settings = Setting::where(function ($query){
            $query->where('key','like','attendance%')
                ->orWhere('key','=','title');
        })->where('project_id',$currentUser->project_id)
            ->get();
        foreach ($settings as $setting){
            $company[$setting->key] = $setting->value;
        }
        $data['settings'] = $company;
        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $data
            ],
            200
        );
    }

    public function clock(Request $request)
    {
        $user = Auth::user();
        info('Clock Start Company '.$request->company.', NIK '.$user->nik);
        $validator = Validator::make($request->all(), [
            'foto' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'date_shift' => 'required',
            'type' => 'required|in:0,1' // 0 Clock in, 1 Clock out
        ]);

        $attendance_type = $request->status?$request->status:"normal";
        $shift_type = $request->shift_status?$request->shift_status:"normal";

        if ($validator->fails()) { // Jika parameter tidak sesuai
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Input validation)');
            return response()->json(['status' => 'error', 'message' => $validator->getMessageBag()->first()], 403);
        }

        $currentDate      = date('Y-m-d');
        $remoteAttendance = RemoteAttendance::where('user_id',$user->id)
            ->where('start_date','<=',$currentDate)
            ->where('end_date','>=',$currentDate)
            ->first();
        if($attendance_type == 'out_of_office'){ // remote off location
            $timezone = $user->branch ? $user->branch->timezone : $this->getServerTimezone();
        }
        else if($remoteAttendance){ // remote by task
            $timezone = $remoteAttendance->timezone;
        }
        else{
            $timezone = $user->branch ? $user->branch->timezone : $this->getServerTimezone();
        }
        $currentDateTime = $this->getDatetime($timezone);
        $currentHourMin = $this->getDatetime($timezone, true);

        $userBranch = User::with('branch')->find($user->id);

        $today = $this->getDate($timezone);

        $shift = Shift::where('id', $shift_type == 'normal' ? $user->shift_id : $request->shift_id)->first();

        $previousDateTime = date_create($currentDateTime)->modify('-1 days')->format('Y-m-d H:i:s');
        $previousDay = date_create($currentDateTime)->modify('-1 days')->format('Y-m-d');
        $absensi = AbsensiItem::where('user_id', $user->id)->whereRaw("CONCAT(`date`, ' ', `clock_in`,':00') >= '".$previousDateTime."'")->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc"); // cek data absensi 24 jam terakhir

        $normal_absensi = clone $absensi;
        $normal_absensi = $normal_absensi->where('shift_type', 'normal')->first();

        $absensi = $absensi->first();

        $date_shift = $today;
        if($request->type == 1 && $absensi) {
            $date_shift = $absensi->date_shift;
        } else if($shift && $shift_type == 'normal') { // Kalau dia tidak absen dalam 24 jam terakhir namun punya shift
            $shiftDetail = ShiftDetail::where('shift_id', $shift->id)->whereRaw("day = '".date('l', strtotime($previousDateTime))."'")->first();  // Cek dulu shift previous day
            if($shiftDetail && $shiftDetail->clock_in > $shiftDetail->clock_out && $shiftDetail->clock_out >= $currentHourMin){ // jika previous day overlap & masih masuk shiftnya...
                $date_shift = $previousDay;
            }
        }

        if ($date_shift != $request->date_shift) {
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Changed date_shift!)');
            info('date_shift request ==> '.$request->date_shift);
            info('date_shift generate ==> '.$date_shift);
        }
        
        // if(($request->type == 1 && !$absensi) || ($request->date_shift != $today && $request->date_shift != $previousDay)){ // jika clock out tpi tidak punya absensi || clock out hari yang sudah lewat
        if($request->type == 1 && !$absensi){ // jika clock out tpi tidak punya absensi
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Clock item is not found or expired!)');
            info('request type ==> '.($request->type ? 'clock out' : 'clock in'));
            // info('date shift params ==> '.$request->date_shift);
            // info('today ==> '.$today);
            // info('yesterday ==> '.$previousDay);
            // info('yesterday time ==> '.$previousDateTime);
            info($absensi);
            return response()->json(['status' => 'error', 'message' => 'Clock item is not found or expired!'], 404);
        }
        if($request->type == 0 && $absensi){ // kalau mau clock in lagi
            if(is_null($absensi->clock_out)) { // kalau belum clock out
                info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Please clock out your last item before doing clock in again!)');
                return response()->json(['status' => 'error', 'message' => 'Please clock out your last item before doing clock in again!'], 404);
            }
            if($shift && $shift_type == 'normal' && $normal_absensi && $normal_absensi->date_shift == $today) {    // kalau ada shiftnya dan clock in normal dan pernah clock in normal
                info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (You can only normal clock in once!)');
                return response()->json(['status' => 'error', 'message' => 'You can only normal clock in once!'], 404);
            }
            if($shift && AbsensiItem::where('user_id', $user->id)->whereRaw("CONCAT(`date`, ' ', `clock_in`,':00') >= '".$today."'")->where('shift_id', $shift->id)->first()) {
                info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (You can only clock in once with same shift!)');
                return response()->json(['status' => 'error', 'message' => 'You can only clock in once with same shift!'], 404);
            }
        }
        if($request->type == 0 && $shift && !count($shift->details->where('day', date('l')))){
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (No shift for today!)');
            return response()->json(['status' => 'error', 'message' => 'No shift for today!'], 404);
        }
        if($request->type == 0 && $shift && ((!$shift->is_holiday && count(hari_libur(\Carbon\Carbon::parse($date_shift)->startOfDay(), \Carbon\Carbon::parse($date_shift)->endOfDay()))) || (!$shift->is_collective && CutiBersama::where('dari_tanggal', \Carbon\Carbon::parse($date_shift)->format('Y-m-d'))->where('impacttoleave', 0)->first()))) {
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Today is public holiday!)');
            return response()->json(['status' => 'error', 'message' => 'Today is public holiday!'], 404);
        }

        if($request->type == 0)
            $imageName = date('H:i:s').'-in.jpg';
        else
            $imageName = date('H:i:s').'-out.jpg';

//        $path = env('PATH_ATTENDANCE_UPLOAD').'/'.$user->id.'/'.$today;
        if($request->company){
            $path = env('PATH_ATTENDANCE_UPLOAD').'/'.strtolower($request->company).'/'.$date_shift.'/'.$user->id;
        }else{
            $path = env('PATH_ATTENDANCE_UPLOAD').'/umum/'.$date_shift.'/'.$user->id;
        }
//        $path = storage_path('attendance').'/'.$user->id.'/'.$today;
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        $request->file('foto')->move($path,$imageName);

        if($request->type == 0){ // clock in
            $absensi             = new AbsensiItem();
            $absensi->user_id    = $user->id;
            $absensi->date       = $today;
            $absensi->date_shift = $date_shift;
            $absensi->timetable  = date('l', strtotime($today));
            $absensi->absensi_device_id = 10;

            if ($shift_type != 'normal') {
                $absensi->shift_type = $shift_type;
                $absensi->shift_justification = $request->shift_justification;
            }

            $absensi->shift_id   = null;
            $absensi->is_holiday = 0;
            if($shift){
                $shiftDetail = ShiftDetail::where('shift_id', $shift->id)->whereRaw("day = '".date('l', strtotime($absensi->date_shift))."'")->first();
                if(!$shiftDetail){
                    $absensi->shift_id   = $shift->id;
                    $absensi->is_holiday = $shift->is_holiday;
                }
                else{
                    $absensi->shift_id   = $shift->id;
                    $absensi->is_holiday = $shift->is_holiday;

                    $awal  = strtotime($absensi->date_shift .' '. $shiftDetail->clock_in .':00');
                    $akhir = strtotime($currentDateTime);
                    $diff  = $akhir - $awal;
                    if($diff > 0){ // kalau telat
                        $jam   = floor($diff / (60 * 60));
                        $menit = floor(($diff - $jam * (60 * 60)) / 60);
                        $jam = $jam <= 9 ? "0".$jam : $jam;
                        $menit = $menit <= 9 ? "0".$menit : $menit;
                        $absensi->late = $jam .':'. $menit;
                    }
                }

            }

            if(is_null($absensi->clock_in) || $absensi->clock_in == ""){
                $absensi->clock_in = $currentHourMin;
                $absensi->lat = $request->latitude;
                $absensi->long = $request->longitude;
                if($request->company){
                    $absensi->pic = '/'.strtolower($request->company).'/'.$date_shift.'/'.$user->id.'/'.date('H:i:s').'-in.jpg';
                }else{
                    $absensi->pic = '/umum/'.$date_shift.'/'.$user->id.'/'.date('H:i:s').'-in.jpg';
                }
            }

            if($attendance_type == 'out_of_office'){
                $absensi->attendance_type_in   = "out_of_office";
            }
            else if($remoteAttendance){
                $absensi->attendance_type_in   = "remote";
            }
            else{
                $absensi->attendance_type_in  = "normal";
                $absensi->cabang_id_in        = $user->cabang_id;
            }
            $absensi->justification_in = $request->justification;
            $absensi->location_name_in = $request->address;

            if($attendance_type != 'out_of_office') {
                if ($remoteAttendance) {
                    $absensi->lat_office_in = $remoteAttendance->latitude;
                    $absensi->long_office_in = $remoteAttendance->longitude;
                    $absensi->radius_office_in = $remoteAttendance->radius;

                } else if ($userBranch->branch != null) {
                    $absensi->lat_office_in = $userBranch->branch->latitude;
                    $absensi->long_office_in = $userBranch->branch->longitude;
                    $absensi->radius_office_in = $userBranch->branch->radius;
                }
            }
            $message = "Clock in success!";
        }
        else{ //1 clock out
            $absensi->date_out  = $today;
            $absensi->clock_out = $currentHourMin;
            $absensi->lat_out   = $request->latitude;
            $absensi->long_out  = $request->longitude;
            if($request->company){
                $absensi->pic_out = '/'.strtolower($request->company).'/'.$date_shift.'/'.$user->id.'/'.date('H:i:s').'-out.jpg';
            }else{
                $absensi->pic_out = '/umum/'.$date_shift.'/'.$user->id.'/'.date('H:i:s').'-out.jpg';
            }

            if(isset($absensi->shift_id))
            {
                $shiftDetail = ShiftDetail::where('shift_id', $absensi->shift_id)->whereRaw("day = '".date('l', strtotime($absensi->date_shift))."'")->first();
                if($shiftDetail) {
                    if($shiftDetail->clock_in <= $shiftDetail->clock_out) {
                        $day = $absensi->date_shift;
                    }
                    else{   // Jika overlap day, pilih hari esok sebagai cut off
                        $day = date_create($absensi->date_shift)->modify('+1 days')->format('Y-m-d');
                    }

                    $akhir = strtotime($day . ' ' . $shiftDetail->clock_out . ':00'); //waktu batas clockout
                    $awal = strtotime($currentDateTime);

                    $diff = $akhir - $awal; // selisih waktu batas dan checkout
                    if ($diff > 0) {
                        $jam = floor($diff / (60 * 60));
                        $menit = floor(($diff - $jam * (60 * 60)) / 60);
                        $jam = $jam <= 9 ? "0" . $jam : $jam;
                        $menit = $menit <= 9 ? "0" . $menit : $menit;
                        $absensi->early = $jam . ':' . $menit;
                    } else {
                        $absensi->early = null;
                    }
                }
            }
            else{
                $absensi->early = null;
            }

            if($absensi->clock_in!="") {
                $akhir = strtotime($this->getDate($timezone) . ' ' . $absensi->clock_out . ':00'); //waktu checkin
                $awal = strtotime($absensi->date . ' ' . $absensi->clock_in . ':00'); // waktu checkout
                $diff = $akhir - $awal;
                if ($diff >= 0) // menghitung waktu kerja
                {
                    $jam = floor($diff / (60 * 60));
                    $menit = floor(($diff - $jam * (60 * 60)) / 60);
                    $jam = $jam <= 9 ? "0" . $jam : $jam;
                    $menit = $menit <= 9 ? "0" . $menit : $menit;
                    $absensi->work_time = $jam . ':' . $menit;
                }
            }

            if($attendance_type == 'out_of_office'){
                $absensi->attendance_type_out   = "out_of_office";
            }
            else if($remoteAttendance){
                $absensi->attendance_type_out   = "remote";
            }
            else{
                $absensi->attendance_type_out   = "normal";
                $absensi->cabang_id_out          = $user->cabang_id;
            }

            $absensi->justification_out = $request->justification;
            $absensi->location_name_out = $request->address;

            if($attendance_type != 'out_of_office') {
                if ($remoteAttendance) {
                    $absensi->lat_office_out = $remoteAttendance->latitude;
                    $absensi->long_office_out = $remoteAttendance->longitude;
                    $absensi->radius_office_out = $remoteAttendance->radius;

                } else if ($userBranch->branch != null) {
                    $absensi->lat_office_out = $userBranch->branch->latitude;
                    $absensi->long_office_out = $userBranch->branch->longitude;
                    $absensi->radius_office_out = $userBranch->branch->radius;
                }
            }
            $message = "Clock out success!";
        }

        $absensi->timezone              = $timezone;

        $absensi->save();
        info('Clock Finish Company '.$request->company.', NIK '.$user->nik);
        return response()->json(
            [
                'status' => 'success',
                'message'=>$message
            ],
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    function getDashboardFilter(Request $request){
        $user = Auth::user();
        $data = [
            'position'  => get_all_position_name(),
            'position_list'  => OrganisasiPosition::select('id', 'name')->orderBy('organisasi_position.name', 'asc')->get(),
            'division_list'  => OrganisasiDivision::select('id', 'name')->orderBy('organisasi_division.name', 'asc')->get(),
            'branch'    => Cabang::select(['id','name'])->where('project_id',$user->project_id)->orderBy('name','asc')->get()
        ];
        return response()->json([
            'status' => 'success',
            'message'=> 'Success',
            'data'  => $data
        ],200);
    }

    function getDashboardData(Request $request){

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'type' => 'required|in:all,present,absent'
        ]);

        if($validator->fails())
            return response()->json(['status' => 'error','message'=>$validator->errors()->first()], 401);

        $date           = $request->date;
        $type           = $request->type; // all, present, absent
        $position_id    = $request->position_id;
        $branch_id      = $request->branch_id;
        $name           = $request->name;

        $items  = AbsensiItem::rightJoin('users as u', function ($join) use ($date,$position_id,$branch_id) {
            $join->on('u.id', '=', 'absensi_item.user_id')->whereDate('absensi_item.date','=',$date);
        })
            ->whereIn('u.access_id',[1,2])
            ->where(function($query) use ($date) {
                $query->whereNull('non_active_date')->orWhere('non_active_date', '>', $date);
            })->where(function($query) use ($date) {
                $query->whereNull('join_date')->orWhere('join_date', '<=', $date);
            })
            ->orderBy('absensi_item.clock_in','desc')
            ->select(['absensi_item.*','u.id as id_user']);

        if($position_id)
            $items = $items->where('u.structure_organization_custom_id',$position_id);
        if($branch_id)
            $items = $items->where('u.cabang_id',$branch_id);
        if($name)
            $items = $items->where('u.name', 'like', '%' . $name . '%');

        $statistic = [];
        $statistic['total_employee'] = (clone $items)->distinct('u.id')->count('u.id');
        $statistic['total_present']  = (clone $items)->whereNotNull('absensi_item.id')->distinct('u.id')->count('u.id');
        $statistic['total_absent']   = (clone $items)->whereNull('absensi_item.id')->distinct('u.id')->count('u.id');
        $statistic['total_late']     = (clone $items)->whereNotNull('absensi_item.late')->count();
        $statistic['total_early']    = (clone $items)->whereNotNull('absensi_item.early')->count();


        if($type == 'present')
            $items = $items->whereNotNull('absensi_item.id');
        if($type == 'absent')
            $items = $items->whereNull('absensi_item.id');

        $totalData = $items->get()->count();
        $items = $items->paginate(10);
        $data = [
            'current_page'  => $items->currentPage(),
            'total_page'    => $items->total() ? $items->lastPage() : $items->total(),
            'total_data'    => $totalData,
            'attendances'   => AbsensiItemResources::collection($items),
            'statistic'     => $statistic,
        ];

        return response()->json([
            'status' => 'success',
            'message'=> 'Success',
            'data'  => $data
        ],200);
    }

    function getDashboardDataRange(Request $request){

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|date_format:Y-m-d|before_or_equal:end_date|start_range_to:' . $request->end_date . ',30',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        if($validator->fails())
            return response()->json(['status' => 'error','message'=>$validator->errors()->first()], 401);

        $filter_start   = $request->start_date;
        $filter_end     = $request->end_date;
        $position_id    = $request->position_id;
        $division_id    = $request->division_id;
        $branch_id      = $request->branch_id;
        $name           = $request->name;

        $items = AbsensiItem::rightJoin('users', function ($join) use ($filter_start, $filter_end) {
            $join->on('users.id', '=', 'absensi_item.user_id')->whereBetween('absensi_item.date', [$filter_start, $filter_end]);
        })
            ->leftJoin('structure_organization_custom', 'structure_organization_custom.id', '=', 'users.structure_organization_custom_id')
            ->where(function($query) use ($filter_start) {
                $query->whereNull('users.non_active_date')->orWhere('users.non_active_date', '>', ($filter_start ?: \Carbon\Carbon::now()));
            })->where(function($query) use ($filter_end) {
                $query->whereNull('users.join_date')->orWhere('users.join_date', '<=', ($filter_end ?: \Carbon\Carbon::now()));
            })
            ->whereIn('users.access_id', [1,2])
            ->orderBy('absensi_item.date', 'DESC')
            ->orderBy('absensi_item.clock_in', 'DESC')
            ->select(['absensi_item.*','users.id as id_user']);
        
        if(!empty($name)) {
            $items = $items->where(function($table) use ($name) {
                $table->where('users.name', 'LIKE', '%' . $name . '%')->orWhere('users.nik', 'LIKE', '%' . $name . '%');
            });
        }

        if(!empty($branch_id)) {
            $items = $items->where('users.cabang_id', $branch_id);
        }
        
        if(!empty($position_id)) {
            $items = $items->where('structure_organization_custom.organisasi_position_id', $position_id);
        }
        if(!empty($division_id)) {
            $items = $items->where('structure_organization_custom.organisasi_division_id', $division_id);
        }

        $statistic = [];
        $statistic['total_employee'] = (clone $items)->distinct('users.id')->count('users.id');
        $statistic['total_present']  = (clone $items)->whereNotNull('absensi_item.id')->distinct('users.id')->count('users.id');
        $statistic['total_absent']   = (clone $items)->whereNull('absensi_item.id')->distinct('users.id')->count('users.id');
        $statistic['total_late']     = (clone $items)->whereNotNull('absensi_item.late')->count();
        $statistic['total_early']    = (clone $items)->whereNotNull('absensi_item.early')->count();

        $items = $items->whereNotNull('absensi_item.id');

        $totalData = $items->get()->count();
        $items = $items->paginate(10);
        $data = [
            'current_page'  => $items->currentPage(),
            'total_page'    => $items->total() ? $items->lastPage() : $items->total(),
            'total_data'    => $totalData,
            'attendances'   => AbsensiItemResources::collection($items),
            'statistic'     => $statistic,
        ];

        return response()->json([
            'status' => 'success',
            'message'=> 'Success',
            'data'  => $data
        ],200);
    }

    private function getDatetime($timezone,$hour = false){
        if($timezone == 'WIB'){
            $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        }
        else if($timezone == 'WITA'){
            $date = new DateTime("now", new DateTimeZone('Asia/Shanghai'));
        }
        else if($timezone == 'WIT'){
            $date = new DateTime("now", new DateTimeZone('Asia/Tokyo'));
        }
        else{
            $date = new DateTime("now");
        }
        if(!$hour)
            return $date->format('Y-m-d H:i:s');
        else
            return $date->format('H:i');
    }
    public function getDate($timezone, $format = 'Y-m-d'){
        if($timezone == 'WIB'){
            $date = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
        }
        else if($timezone == 'WITA'){
            $date = new DateTime("now", new DateTimeZone('Asia/Shanghai'));
        }
        else if($timezone == 'WIT'){
            $date = new DateTime("now", new DateTimeZone('Asia/Tokyo'));
        }
        else{
            $date = new DateTime("now");
        }
        return $date->format($format);
    }
    public function getServerTimezone(){
        $timezone = null;
        $utc =  date('Z') / 3600;
        if($utc == '7')
            $timezone = 'WIB';
        else if($utc == '8')
            $timezone = 'WITA';
        else if($utc == '9')
            $timezone = 'WIT';
        return $timezone;
    }

    public function getOtherShift() {
        $user = Auth::user();

        $absensi = AbsensiItem::select('shift_id')->where('user_id', $user->id)->whereRaw("CONCAT(`date`, ' ', `clock_in`,':00') >= '".$this->getDate($user->branch ? $user->branch->timezone : $this->getServerTimezone())."'")->whereNotNull('clock_out')->whereNotNull('shift_id')->groupBy('shift_id')->pluck('shift_id')->toArray();

        $shift = Shift::where('id', '!=', $user->shift_id)->where('branch_id', $user->cabang_id)->whereNotIn('id', $absensi)->whereHas('details', function ($query) {
            $query->where('day', date('l'));
        });

        if (count(hari_libur(\Carbon\Carbon::now()->startOfDay(), \Carbon\Carbon::now()->endOfDay()))) {
            $shift = $shift->where('is_holiday', 1);
        }
        if (CutiBersama::where('dari_tanggal', \Carbon\Carbon::now()->format('Y-m-d'))->where('impacttoleave', 0)->first()) {
            $query->where('is_collective', 1);
        }

        $shift = $shift->get();

        return response()->json(
            [
                'status' => 'success',
                'message'=>'Data is collected',
                'data' => $shift
            ],
            200
        );
    }
}

