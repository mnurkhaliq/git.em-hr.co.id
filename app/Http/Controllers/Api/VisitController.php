<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Middleware\ConnectionMiddleware;
use App\Models\Cabang;
use App\Models\MasterCategoryVisit;
use App\Models\MasterVisitType;
use App\Models\SettingActivityVisit;
use App\Models\VisitList;
use App\Models\VisitPict;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisitController extends BaseApiController
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
    public function getVisitParams()
    {
        try {
            // get branches data
            $branches = [];
            if (Auth::user()->master_visit_type_id == 1) { // user visit_type is Lock
                $branches = Cabang::where('project_id', Auth::user()->project_id)->whereNotNull('longitude')->whereNotNull('latitude')->whereNotNull('radius')->whereHas('usersBranchVisit', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })->with(['cabangPicMasters' => function ($query) {
                    $query->whereHas('cabangPics', function ($query) {
                        $query->where('user_id', Auth::user()->id);
                    });
                }])->get();
            }

            $data = [
                'branches' => $branches,
                'category' => MasterCategoryVisit::where('id', Auth::user()->master_category_visit_id)->with('settingVisitActivities')->first(), // get category data
                'type' => MasterVisitType::find(Auth::user()->master_visit_type_id), // get type data
            ];

            return response()->json(
                [
                    'status' => 'success',
                    'data' => $data,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // unset other key input
            $temp = array_intersect_key($request->all(), array_flip([
                'cabang_id',
                'visit_time',
                'timezone',
                'timetable',
                'isoutbranch',
                'justification',
                'longitude',
                'latitude',
                'locationname',
                'placename',
                'isotheractivityname',
                'activityname',
                'description',
                'radius_visit',
                'branchlongitude',
                'branchlatitude',
                'isotherpic',
                'picname',
                'signature',
                'setting_visit_activity_id',
                'master_visit_type_id',
                'master_category_visit_id',
            ]));

            // manage variables
            $temp['user_id'] = Auth::user()->id;
            $temp['timezone'] = $request->timezone ?: ($request->cabang_id ? Cabang::find($request->cabang_id)->timezone : app('App\Http\Controllers\Api\GlobalFunctionController')->getServerTimezone());
            $temp['visit_time'] = $request->visit_time ?: app('App\Http\Controllers\Api\GlobalFunctionController')->getDate($temp['timezone'], 'Y-m-d H:i:s');
            $temp['timetable'] = $request->timetable ?: app('App\Http\Controllers\Api\GlobalFunctionController')->getDate($temp['timezone'], 'l');
            $temp['setting_visit_activity_id'] = $request->isotheractivityname ? null : ($request->setting_visit_activity_id ?: SettingActivityVisit::where('activityname', $request->activityname)->where('master_category_visit_id', $request->master_category_visit_id)->where('isactive', true)->first()->id);
            $temp['point'] = $request->isotheractivityname ? 1 : SettingActivityVisit::find($temp['setting_visit_activity_id'])->point;

            // move image
            if (isset($request->signature) && $request->hasFile('signature')) {
                $fileName = date('H.i.s') . '.jpg';
                $path = env('PATH_VISIT_UPLOAD') . '/signature/' . ($request->company ? strtolower($request->company) : 'umum') . '/' . date('Y-m-d') . '/' . Auth::user()->id;
                if (!is_dir(env('PATH_STORAGE_UPLOAD_SAAS') . $path)) {
                    mkdir(env('PATH_STORAGE_UPLOAD_SAAS') . $path, 0755, true);
                }
                $request->file('signature')->move(env('PATH_STORAGE_UPLOAD_SAAS') . $path, $fileName);
                $temp['signature'] = env('PATH_STORAGE_TUNNEL_SAAS') . $path . '/' . $fileName;
            }

            // insert visit_list data
            $visitId = VisitList::create($temp)->id;

            // loop visit_picts data
            if ($request->file('photos')) {
                foreach ($request->file('photos') as $index => $value) {

                    // manage variables
                    $temp = [];
                    $temp['visit_list_id'] = $visitId;
                    if (isset($request->captions[$index]) && $request->captions[$index]) {
                        $temp['photocaption'] = $request->captions[$index];
                    }

                    // move image
                    $fileName = date('H.i.s') . '-' . $index . '.jpg';
                    $path = env('PATH_VISIT_UPLOAD') . '/pict/' . ($request->company ? strtolower($request->company) : 'umum') . '/' . date('Y-m-d') . '/' . Auth::user()->id;
                    if (!is_dir(env('PATH_STORAGE_UPLOAD_SAAS') . $path)) {
                        mkdir(env('PATH_STORAGE_UPLOAD_SAAS') . $path, 0755, true);
                    }
                    $value->move(env('PATH_STORAGE_UPLOAD_SAAS') . $path, $fileName);
                    $temp['photo'] = env('PATH_STORAGE_TUNNEL_SAAS') . $path . '/' . $fileName;

                    // insert visit_pict data
                    VisitPict::create($temp);
                }
            }

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data is collected',
                ],
                200
            );
        } catch (\Exception $e) {
            info($e);
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                500
            );
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVisitFilterParams()
    {
        try {
            $data = [
                'branches' => Cabang::where('project_id', Auth::user()->project_id)->whereNotNull('longitude')->whereNotNull('latitude')->whereNotNull('radius')->get(), // get branches data
                'categories' => MasterCategoryVisit::all(), // get categories data
                'types' => MasterVisitType::all(), // get types data
            ];

            return response()->json(
                [
                    'status' => 'success',
                    'data' => $data,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                500
            );
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // date validation
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date|date_format:Y-m-d|before_or_equal:end_date|start_range_to:' . $request->end_date . ',30',
                'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $validator->errors()->first(),
                    ],
                    401
                );
            }

            // get visit histories data and filter date
            $histories = VisitList::where('user_id', Auth::user()->id)->with(['cabang', 'visitPicts', 'type', 'category'])->whereBetween('visit_time', [Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])->orderBy('visit_time', 'DESC');

            // other filter
            foreach (array_intersect_key($request->all(), array_flip([
                'cabang_id',
                // 'visit_time',
                // 'timezone',
                // 'timetable',
                'isoutbranch',
                // 'justification',
                // 'longitude',
                // 'latitude',
                // 'locationname',
                // 'placename',
                'isotheractivityname',
                // 'activityname',
                // 'description',
                // 'radius_visit',
                // 'branchlongitude',
                // 'branchlatitude',
                'isotherpic',
                // 'picname',
                'point',
                'setting_visit_activity_id',
                'master_visit_type_id',
                'master_category_visit_id',
            ])) as $index => $value) {
                if ($value == -1) {
                    $histories = $histories->whereNull($index);
                } else if ($value) {
                    $histories = $histories->where($index, $value);
                }
            }

            foreach (array_intersect_key($request->all(), array_flip([
                'visit_time',
                'timezone',
                'timetable',
                'justification',
                'longitude',
                'latitude',
                'locationname',
                'placename',
                'activityname',
                'description',
                'radius_visit',
                'branchlongitude',
                'branchlatitude',
                'picname',
            ])) as $index => $value) {
                if ($value == -1) {
                    $histories = $histories->whereNull($index);
                } else if ($value) {
                    $histories = $histories->where($index, 'like', '%' . $value . '%');
                }
            }

            // collect
            $totalData = $histories->get()->count();
            $histories = $histories->paginate(10);

            $data = [
                'current_page' => $histories->currentPage(), // get current page number
                'total_page' => $histories->total() ? $histories->lastPage() : $histories->total(), // get last page number
                'total_data' => $totalData,
                'histories' => $histories->getCollection()->transform(function ($value) { // formating output data
                    return $value;
                }),
                'visits' => $histories->getCollection()->transform(function ($value) { // formating output data
                    return $value;
                }),
                'statistic' => [ // get visit statistic data
                    'visit_today' => VisitList::where('user_id', Auth::user()->id)->whereDate('visit_time', Carbon::today())->count(),
                    'visit_week' => VisitList::where('user_id', Auth::user()->id)->whereBetween('visit_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                    'visit_month' => VisitList::where('user_id', Auth::user()->id)->whereBetween('visit_time', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count(),
                ],
            ];

            return response()->json(
                [
                    'status' => 'success',
                    'data' => $data,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ],
                500
            );
        }
    }
}
