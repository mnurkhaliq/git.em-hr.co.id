<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DateTimeZone;
use File;

class AttendanceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function clock(Request $request)
    {
        $user = Auth::user();
        info('Clock Start Company '.$request->company.', NIK '.$user->nik);
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'date_shift' => 'required',
            'type' => 'required|in:0,1' // 0 Clock in, 1 Clock out
        ]);

        $attendance_type = $request->status? $request->status : "normal";
        $shift_type = $request->shift_status? $request->shift_status : "normal";

        if ($validator->fails()) { // Jika parameter tidak sesuai
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Input validation)');
            if($request->type == 0){ // clock in
                return redirect()->route('karyawan.clock-in')->with('message-error', $validator->getMessageBag()->first());
            }
            else{
                return redirect()->route('karyawan.clock-out')->with('message-error', $validator->getMessageBag()->first());
            }
        }

        $currentDate      = date('Y-m-d');

        //cek remote
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
            info($absensi);
            if($request->type == 0){ // clock in
                return redirect()->route('karyawan.clock-in')->with('message-error',  'Clock item is not found or expired!');
            }
            else{
                return redirect()->route('karyawan.clock-out')->with('message-error',  'Clock item is not found or expired!');
            }
        }
        if($request->type == 0 && $absensi){ // kalau mau clock in lagi
            if(is_null($absensi->clock_out)) { // kalau belum clock out
                info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Please clock out your last item before doing clock in again!)');
                if($request->type == 0){ // clock in
                    return redirect()->route('karyawan.clock-in')->with('message-error',  'Please clock out your last item before doing clock in again!');
                }
                else{
                    return redirect()->route('karyawan.clock-out')->with('message-error',  'Please clock out your last item before doing clock in again!');
                }
            }
            if($shift && $shift_type == 'normal' && $normal_absensi && $normal_absensi->date_shift == $today) {    // kalau ada shiftnya dan clock in normal dan pernah clock in normal
                info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (You can only normal clock in once!)');
                if($request->type == 0){ // clock in
                    return redirect()->route('karyawan.clock-in')->with('message-error',  'You can only normal clock in once!');
                }
                else{
                    return redirect()->route('karyawan.clock-out')->with('message-error',  'You can only normal clock in once!');
                }
            }
            if($shift && AbsensiItem::where('user_id', $user->id)->whereRaw("CONCAT(`date`, ' ', `clock_in`,':00') >= '".$today."'")->where('shift_id', $shift->id)->first()) {
                info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (You can only clock in once with same shift!)');
                if($request->type == 0){ // clock in
                    return redirect()->route('karyawan.clock-in')->with('message-error',  'You can only clock in once with same shift!');
                }
                else{
                    return redirect()->route('karyawan.clock-out')->with('message-error',  'You can only clock in once with same shift!');
                }
            }
        }
        if($request->type == 0 && $shift && !count($shift->details->where('day', date('l')))){
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (No shift for today!)');
            if($request->type == 0){ // clock in
                return redirect()->route('karyawan.clock-in')->with('message-error',  'No shift for today!');
            }
            else{
                return redirect()->route('karyawan.clock-out')->with('message-error',  'No shift for today!');
            }
        }
        if($request->type == 0 && $shift && ((!$shift->is_holiday && count(hari_libur(\Carbon\Carbon::parse($date_shift)->startOfDay(), \Carbon\Carbon::parse($date_shift)->endOfDay()))) || (!$shift->is_collective && CutiBersama::where('dari_tanggal', \Carbon\Carbon::parse($date_shift)->format('Y-m-d'))->where('impacttoleave', 0)->first()))) {
            info('Clock Failed Company '.$request->company.', NIK '.$user->nik.' (Today is public holiday!)');
            if($request->type == 0){ // clock in
                return redirect()->route('karyawan.clock-in')->with('message-error',  'Today is public holiday!');
            }
            else{
                return redirect()->route('karyawan.clock-out')->with('message-error',  'Today is public holiday!');
            }
        }

        if($request->type == 0)
            $imageName = uniqid().'-in.jpg';
        else
            $imageName = uniqid().'-out.jpg';


        if($request->company){
            $company_url = session('company_url','umum');
            $path = public_path().'/upload/attendance/'.strtolower($company_url).'/'.$date_shift.'/'.$user->id.'/';
        }else{
            $path = public_path().'/upload/attendance/umum/'.$date_shift.'/'.$user->id.'/';
        }

        if (!File::exists($path)) {
            if($request->company){
                $company_url = session('company_url','umum');
                $path = public_path().'/upload/attendance/'.strtolower($company_url).'/'.$date_shift.'/'.$user->id;
            }else{
                $path = public_path().'/upload/attendance/umum/'.$date_shift.'/'.$user->id;
            }
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if($request->company){
            $company_url = session('company_url','umum');
            $path ='upload/attendance/'.strtolower($company_url).'/'.$date_shift.'/'.$user->id.'/';
        }else{
            $path ='upload/attendance/umum/'.$date_shift.'/'.$user->id.'/';
        }

        //base64 from webcam to image
        $img = $request->image;
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $path . $imageName;
        file_put_contents($file, $image_base64);

        if($request->type == 0){ // clock in
            $absensi             = new AbsensiItem();
            $absensi->user_id    = $user->id;
            $absensi->date       = $today;
            $absensi->date_shift = $date_shift;
            $absensi->timetable  = date('l', strtotime($today));
            $absensi->absensi_device_id = 11;

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
                    $absensi->pic = $file;
                }else{
                    $absensi->pic = $file;
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
                $absensi->pic_out = $file;
            }else{
                $absensi->pic_out = $file;
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
        if($request->type == 0){
            return redirect()->route('karyawan.dashboard')->with('message-success', 'Clock in successfully processed');
        }
        else{
            return redirect()->route('karyawan.dashboard')->with('message-success', 'Clock Out successfully processed'); 
        }
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

        return response()->json($shift);
    }

    public function clockIn(){
        $data = $this->absensiToday();
        //dd($data);
        return view('karyawan.attendance.clock-in')->with($data);
    }

    public function clockOut(){
        $data = $this->absensiToday();
        //dd($data);
        return view('karyawan.attendance.clock-out')->with($data);
    }

    public function detailClockIn(){
        $data = $this->absensiToday();
        //dd($data);
        return view('karyawan.attendance.detail-clock-in')->with($data);
    }

    public function detailClockOut(){
        $data = $this->absensiToday();
        //dd($data); 
        return view('karyawan.attendance.detail-clock-out')->with($data);
    }

    public function ajaxStatistic(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $items  = AbsensiItem::where('user_id',auth()->user()->id)
            ->where('date','>=',$start_date)
            ->where('date','<=',$end_date)
            ->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc");

        $statistic = [];
        $statistic['total_attendance'] = (clone $items)->count();
        $statistic['total_late']       = (clone $items)->whereNotNull('late')->count();
        $statistic['total_early']      = (clone $items)->whereNotNull('early')->count();
        $statistic['total_forget']     = (clone $items)->whereNull('clock_out')->count();
        $data_items                          = (clone $items)->get();
        $minutesWork                   = 0;
        foreach ($data_items as $item){
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

        return $statistic;
    }

    public function absensiToday(){
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
            $data['lat_office']   = $currentUser->branch->latitude;
            $data['long_office']  = $currentUser->branch->longitude;
            $data['radius']       = $currentUser->branch->radius;
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
            $data['lat_office']   = $remoteAttendance->latitude;
            $data['long_office']  = $remoteAttendance->longitude;
            $data['radius']       = $remoteAttendance->radius;
        }
        else{
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

        $currentUser = Auth::user();
        $start_date = date('Y-m-d', strtotime($currentUser->join_date));
        $end_date = $currentDate;

        $items  = AbsensiItem::where('user_id',$currentUser->id)
            ->where('date','>=',$start_date)
            ->where('date','<=',$end_date)
            ->orderByRaw("CONCAT(`date`, ' ', `clock_in`,':00') desc");

        $statistic = [];
        $statistic['total_attendance'] = (clone $items)->count();
        $statistic['total_late']       = (clone $items)->whereNotNull('late')->count();
        $statistic['total_early']      = (clone $items)->whereNotNull('early')->count();
        $statistic['total_forget']     = (clone $items)->whereNull('clock_out')->count();
        $data_items                          = (clone $items)->get();
        $minutesWork                   = 0;
        foreach ($data_items as $item){
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


        $data['statistic']     = $statistic;

        return $data;
    }

}

