<?php

namespace App\Http\Controllers\Administrator;

use App\Models\PayrollCycle;
use App\Models\PayrollDeductionsEmployee;
use App\Models\PayrollDeductionsEmployeeHistory;
use App\Models\PayrollEarningsEmployee;
use App\Models\PayrollEarningsEmployeeHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PayrollPtkp;
use App\Models\PayrollPPH;
use App\Models\PayrollOthers;
use App\Models\PayrollCountry;
use App\Models\PayrollUMR;
use App\Models\PayrollEarnings;
use App\Models\PayrollDeductions;
use App\Models\Setting;
use App\Models\PayrollNpwp;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PayrollSettingController extends Controller
{   

	public function __construct(\Maatwebsite\Excel\Excel $excel)
	{
	    parent::__construct();
        $this->middleware('module:13');
	    $this->excel = $excel;
	}

    /**
     * [index description]
     * @return [type] [description]
     */
    public function index(Request $request)
    {
        $user = \Auth::user();
        $params['lock'] = Setting::where('description', 'lock_payroll')->where('project_id', $user->project_id)->orderBy('id', 'ASC')->get();

        if(!$params['lock']->count()){
            Setting::create([
                'key' => 'button_lock',
                'value' => 1,
                'description' => 'lock_payroll',
                'user_created' => $user->id,
                'project_id' => $user->project_id, 
            ]);
            Setting::create([
                'key' => 'payslip_lock',
                'value' => 0,
                'description' => 'lock_payroll',
                'user_created' => $user->id,
                'project_id' => $user->project_id, 
            ]);
            Setting::create([
                'key' => 'schedule_lock',
                'value' => 0,
                'description' => 'lock_payroll',
                'user_created' => $user->id,
                'project_id' => $user->project_id, 
            ]);

            $params['lock'] = Setting::where('description', 'lock_payroll')->where('project_id', $user->project_id)->orderBy('id', 'ASC')->get();
        }

        $params['prorate'] = get_setting('prorate');

        if($user->project_id != NULL)
        {   
            $params['earnings'] = PayrollEarnings::where('payroll_earnings.project_id', $user->project_id)->select('payroll_earnings.*')->get();
            $params['deductions']= PayrollDeductions::where('payroll_deductions.project_id', $user->project_id)->select('payroll_deductions.*')->get();
        }else{
            $params['earnings'] = PayrollEarnings::all();
            $params['deductions']= PayrollDeductions::all();
        }

        $params['ptkp']     = PayrollPtkp::all();
        $params['pph']      = PayrollPPH::all();
        $params['others']   = PayrollOthers::all();
        $params['country']  = PayrollCountry::all();
        $params['umr']      = PayrollUMR::all();
        $params['npwp']     = PayrollNpwp::all();

        if ($request->tab) {
            $params['tab'] = $request->tab;
        }

        return view('administrator.payroll-setting.index')->with($params);
    }

    /**
     * [deletePtkp description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deletePtkp($id)
    {       
        $pph = PayrollPtkp::where('id', $id)->first();
        $pph->delete();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'ptkp'])->with('message-success', 'PTKP Setting deleted successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'PTKP Setting deleted successfully');
    }

    /**
     * [editOthers description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editOthers($id)
    {
        $params['data'] = PayrollOthers::where('id', $id)->first();

        return view('administrator.payroll-setting.edit-others')->with($params);
    }

    /**
     * [deleteOthers description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteOthers($id)
    {       
        $pph = PayrollOthers::where('id', $id)->first();
        $pph->delete();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'others'])->with('message-success', 'Others Setting deleted successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Others Setting deleted successfully');
    }

    /**
     * [updateOthers description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateOthers(Request $request, $id)
    {
        $data           = PayrollOthers::where('id', $id)->first();
        $data->label    = $request->label;
        $data->value    = preg_replace('/[^0-9]/', '',$request->value);
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'others'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [addCountry description]
     */
    public function addCountry()
    {
        return view('administrator.payroll-setting.add-country');
    }

    /**
     * [storeCountry description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeCountry(Request $request)
    {
        $data           = new PayrollCountry();
        $data->name     = $request->name;
        $data->code     = $request->code;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'country'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [editCountry description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editCountry($id)
    {
        $params['data'] = PayrollCountry::find($id);

        return view('administrator.payroll-setting.edit-country')->with($params);
    }

    /**
     * [updateCountry description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateCountry(Request $request, $id)
    {
        $data           = PayrollCountry::where('id', $id)->first();
        $data->name     = $request->name;
        $data->code     = $request->code;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'country'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [deleteCountry description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteCountry($id)
    {       
        PayrollCountry::destroy($id);

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'country'])->with('message-success', 'Country Setting deleted successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Country Setting deleted successfully');
    }

    public function importCountry(Request $request)
    {       
        if ($request->hasFile('file')) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }

            foreach ($rows as $key => $item) {
                if (empty($item[0]) || empty($item[1]) || $key == 0) {
                    continue;
                }

                $data           = new PayrollCountry();
                $data->name     = $item[0];
                $data->code     = $item[1];
                $data->save();
            }
        }

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'country'])->with('message-success', 'Data imported successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data imported successfully');
    }

    public function storeLock(Request $request){
        if (isset($request->button_lock)) {
            Setting::where('key', 'button_lock')->update([
                'value' => 1,
            ]);
        } else {
            Setting::where('key', 'button_lock')->update([
                'value' => 0,
            ]);
        }

        if (isset($request->payslip_lock)) {
            Setting::where('key', 'payslip_lock')->update([
                'value' => 1,
            ]);
        } else {
            Setting::where('key', 'payslip_lock')->update([
                'value' => 0,
            ]);
        }

        if (isset($request->schedule_lock)) {
            Setting::where('key', 'schedule_lock')->update([
                'value' => 1,
            ]);
        } else {
            Setting::where('key', 'schedule_lock')->update([
                'value' => 0,
            ]);
        }

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'lock'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    public function storeProrate(Request $request){
        update_setting('prorate', $request->button_prorate_type);

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'prorate'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    public function storeCycle(Request $request){
        $user = \Auth::user();
        if ($user->project_id != NULL) {
            $cycle = PayrollCycle::where('project_id', $user->project_id)->where('key_name', $request->key_name ?: 'attendance')->first();
        } else {
            $cycle = PayrollCycle::whereNull('project_id')->where('key_name', $request->key_name ?: 'attendance')->first();
        }
        if($request->start_date!=0 && $request->end_date!=0) {
            if (!$cycle) {
                $cycle = new PayrollCycle();
                $cycle->key_name = $request->key_name ?: 'attendance';
            }
            $cycle->start_date = $request->start_date;
            $cycle->end_date = $request->end_date;
            $cycle->project_id = $user->project_id;
            $cycle->save();
        }
        else{
            if($cycle)
                $cycle->delete();
        }

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => $request->key_name ? 'cyclePayroll' : 'cycleAttendance'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    public function storeNpwp(Request $request){
        $user = \Auth::user();

        if($request->npwp)
        {
            if($user->project_id != NULL)
            {
                $id = 0;
                for($i=0; $i < count($request->npwp); $i++){
                    $id = $id+1;
                    $npwp = PayrollNpwp::where('id_payroll_npwp', $id)->where('project_id',$user->project_id)->get();
                    if(count($npwp) < 1){
                        $npwp = new PayrollNpwp();
                        $npwp->label = $request->label[$i];
                        $npwp->value = $request->npwp[$i];
                        $npwp->id_payroll_npwp = $id;
                        $npwp->project_id = $user->project_id;
                        $npwp->save();
                    }else{
                        $npwp = PayrollNpwp::where('id_payroll_npwp', $id)->where('project_id',$user->project_id)->first();
                        $npwp->label = $request->label[$i];
                        $npwp->value = $request->npwp[$i];
                        $npwp->save();
                    }
                }
            }else{
                $id = 0;
                for($i=0; $i < count($request->npwp); $i++){
                    $id = $id+1;
                    $npwp = PayrollNpwp::where('id_payroll_npwp', $id)->whereNull('project_id')->get();
                    if(count($npwp) < 1){
                        $npwp = new PayrollNpwp();
                        $npwp->label = $request->label[$i];
                        $npwp->value = $request->npwp[$i];
                        $npwp->id_payroll_npwp = $id;
                        $npwp->project_id = $user->project_id;
                        $npwp->save();
                    }else{
                        $npwp = PayrollNpwp::where('id_payroll_npwp', $id)->whereNull('project_id')->first();
                        $npwp->label = $request->label[$i];
                        $npwp->value = $request->npwp[$i];
                        $npwp->save();
                    }
                }
            }
        }

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'npwp'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }
    public function editNpwp($id)
    {
        $params['data'] = PayrollNpwp::where('id', $id)->first();

        return view('administrator.payroll-setting.edit-npwp')->with($params);
    }
    public function updateNpwp(Request $request, $id)
    {
        $data           = PayrollNpwp::where('id', $id)->first();
        $data->label    = $request->label;
        $data->value    = $request->value;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'npwp'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [addUMR description]
     */
    public function addUMR()
    {
        return view('administrator.payroll-setting.add-umr');
    }

    /**
     * [storeUMR description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeUMR(Request $request)
    {
        $data           = new PayrollUMR();
        $data->label    = $request->label;
        $data->value    = preg_replace('/[^0-9]/', '',$request->value);
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'umr'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [editUMR description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editUMR($id)
    {
        $params['data'] = PayrollUMR::find($id);

        return view('administrator.payroll-setting.edit-umr')->with($params);
    }

    /**
     * [updateUMR description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateUMR(Request $request, $id)
    {
        $data           = PayrollUMR::where('id', $id)->first();
        $data->label    = $request->label;
        $data->value    = preg_replace('/[^0-9]/', '',$request->value);
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'umr'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [deleteUMR description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteUMR($id)
    {       
        PayrollUMR::destroy($id);

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'umr'])->with('message-success', 'UMR Setting deleted successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'UMR Setting deleted successfully');
    }

    public function userToBeAssigned()
    {
        $data = User::whereIn('access_id',[1,2])
            ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
            ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
            ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
            ->select(
                'users.id',
                'users.payroll_umr_id as payroll_umr_id',
                'users.nik',
                'users.name',
                'organisasi_division.name as division',
                'organisasi_position.name as position'
            )->get();

        if (count($data) > 0) {
            return \Response::json([
                'message' => 'success',
                'data' => $data,
            ]);
        } else {
            return \Response::json([
                'message' => 'failed',
            ]);
        }
    }

    public function assignUMR(Request $request)
    {
        if ($request->user_id) {
            User::whereIn('id', $request->user_id)->update([
                'payroll_umr_id' => $request->payroll_umr_id,
            ]);
        }

        if ($request->user_id_uncheck) {
            User::whereIn('id', $request->user_id_uncheck)->where('payroll_umr_id', $request->payroll_umr_id)->update([
                'payroll_umr_id' => null,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Assign UMR success']);
    }

    /**
     * [addPayrollCycle description]
     */
    public function addPayrollCycle()
    {
        return view('administrator.payroll-setting.add-payroll-cycle');
    }

    /**
     * [storePayrollCycle description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storePayrollCycle(Request $request)
    {
        $data               = new PayrollCycle();
        $data->key_name     = 'payroll_custom';
        $data->label        = $request->label;
        $data->start_date   = $request->start_date;
        $data->end_date     = $request->end_date;
        $data->project_id   = Auth::user()->project_id;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'cyclePayroll'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [editPayrollCycle description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editPayrollCycle($id)
    {
        $params['data'] = PayrollCycle::find($id);

        return view('administrator.payroll-setting.edit-payroll-cycle')->with($params);
    }

    /**
     * [updatePayrollCycle description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updatePayrollCycle(Request $request, $id)
    {
        $data               = PayrollCycle::where('id', $id)->first();
        $data->label        = $request->label;
        $data->start_date   = $request->start_date;
        $data->end_date     = $request->end_date;
        $data->project_id   = Auth::user()->project_id;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'cyclePayroll'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [deletePayrollCycle description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deletePayrollCycle($id)
    {       
        PayrollCycle::destroy($id);

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'cyclePayroll'])->with('message-success', 'Payroll Cycle Setting deleted successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Payroll Cycle Setting deleted successfully');
    }

    public function userToBeAssignedPayrollCycle()
    {
        $data = User::whereIn('access_id',[1,2])
            ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
            ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
            ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
            ->select(
                'users.id',
                'users.payroll_cycle_id as payroll_cycle_id',
                'users.nik',
                'users.name',
                'organisasi_division.name as division',
                'organisasi_position.name as position'
            )->get();

        if (count($data) > 0) {
            return \Response::json([
                'message' => 'success',
                'data' => $data,
            ]);
        } else {
            return \Response::json([
                'message' => 'failed',
            ]);
        }
    }

    public function assignPayrollCycle(Request $request)
    {
        if ($request->user_id) {
            User::whereIn('id', $request->user_id)->update([
                'payroll_cycle_id' => $request->payroll_cycle_id,
            ]);
        }

        if ($request->user_id_uncheck) {
            User::whereIn('id', $request->user_id_uncheck)->where('payroll_cycle_id', $request->payroll_cycle_id)->update([
                'payroll_cycle_id' => null,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Assign Payroll Cycle success']);
    }

    /**
     * [addAttendanceCycle description]
     */
    public function addAttendanceCycle()
    {
        return view('administrator.payroll-setting.add-attendance-cycle');
    }

    /**
     * [storeAttendanceCycle description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeAttendanceCycle(Request $request)
    {
        $data               = new PayrollCycle();
        $data->key_name     = 'attendance_custom';
        $data->label        = $request->label;
        $data->start_date   = $request->start_date;
        $data->end_date     = $request->end_date;
        $data->project_id   = Auth::user()->project_id;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'cycleAttendance'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [editAttendanceCycle description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editAttendanceCycle($id)
    {
        $params['data'] = PayrollCycle::find($id);

        return view('administrator.payroll-setting.edit-attendance-cycle')->with($params);
    }

    /**
     * [updateAttendanceCycle description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateAttendanceCycle(Request $request, $id)
    {
        $data               = PayrollCycle::where('id', $id)->first();
        $data->label        = $request->label;
        $data->start_date   = $request->start_date;
        $data->end_date     = $request->end_date;
        $data->project_id   = Auth::user()->project_id;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'cycleAttendance'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [deleteAttendanceCycle description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteAttendanceCycle($id)
    {       
        PayrollCycle::destroy($id);

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'cycleAttendance'])->with('message-success', 'Attendance Cycle Setting deleted successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Attendance Cycle Setting deleted successfully');
    }

    public function userToBeAssignedAttendanceCycle()
    {
        $data = User::whereIn('access_id',[1,2])
            ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
            ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
            ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
            ->select(
                'users.id',
                'users.attendance_cycle_id as attendance_cycle_id',
                'users.nik',
                'users.name',
                'organisasi_division.name as division',
                'organisasi_position.name as position'
            )->get();

        if (count($data) > 0) {
            return \Response::json([
                'message' => 'success',
                'data' => $data,
            ]);
        } else {
            return \Response::json([
                'message' => 'failed',
            ]);
        }
    }

    public function assignAttendanceCycle(Request $request)
    {
        if ($request->user_id) {
            User::whereIn('id', $request->user_id)->update([
                'attendance_cycle_id' => $request->attendance_cycle_id,
            ]);
        }

        if ($request->user_id_uncheck) {
            User::whereIn('id', $request->user_id_uncheck)->where('attendance_cycle_id', $request->attendance_cycle_id)->update([
                'attendance_cycle_id' => null,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Assign Attendance Cycle success']);
    }

    /**
     * [addPPH description]
     */
    public function addPPH()
    {
        return view('administrator.payroll-setting.add-pph');
    }

    /**
     * [addPPH description]
     */
    public function editPPH($id)
    {
        $params['data'] = PayrollPPH::where('id', $id)->first();

        return view('administrator.payroll-setting.edit-pph')->with($params);
    }

    /**
     * [deletePPH description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deletePPH($id)
    {       
        $pph = PayrollPPH::where('id', $id)->first();
        $pph->delete();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'pph'])->with('message-success', 'PPH Setting deleted successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'PPH Setting deleted successfully');
    }


    /**
     * [addOthers description]
     */
    public function addOthers()
    {
        return view('administrator.payroll-setting.add-others');
    }   

    /**
     * [updatePPH description]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updatePPH(Request $request, $id)
    {
        $data                   = PayrollPPH::where('id', $id)->first();
        $data->batas_bawah      = preg_replace('/[^0-9]/', '', $request->batas_bawah);
        $data->batas_atas       = preg_replace('/[^0-9]/', '',$request->batas_atas);
        $data->tarif            = $request->tarif;
        $data->pajak_minimal    = preg_replace('/[^0-9]/', '',$request->pajak_minimal);
        $data->akumulasi_pajak  = preg_replace('/[^0-9]/', '',$request->akumulasi_pajak);
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'pph'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [storePPH description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storePPH(Request $request)
    {
        $data                   = new PayrollPPH();
        $data->batas_bawah      = $request->batas_bawah;
        $data->batas_atas       = $request->batas_atas;
        $data->tarif            = $request->tarif;
        $data->pajak_minimal    = $request->pajak_minimal;
        $data->akumulasi_pajak  = $request->akumulasi_pajak;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'pph'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [storeOthers description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeOthers(Request $request)
    {
        $data           = new PayrollOthers();
        $data->label    = $request->label;
        $data->value    = $request->value;
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'others'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * [editPtkp description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function editPtkp($id)
    {
        $params['data'] = PayrollPtkp::where('id', $id)->first();

        return view('administrator.payroll-setting.edit-ptkp')->with($params);
    }

    /**
     * [storePPH description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updatePtkp(Request $request, $id)
    {
        $data                       = PayrollPtkp::find($id);
        $data->bujangan_wanita      = preg_replace('/[^0-9]/', '',$request->bujangan_wanita);
        $data->menikah              = preg_replace('/[^0-9]/', '',$request->menikah);
        $data->menikah_anak_1       = preg_replace('/[^0-9]/', '',$request->menikah_anak_1);
        $data->menikah_anak_2       = preg_replace('/[^0-9]/', '',$request->menikah_anak_2);
        $data->menikah_anak_3       = preg_replace('/[^0-9]/', '',$request->menikah_anak_3);
        $data->save();

        return \Redirect::route('administrator.payroll-setting.index', ['tab' => 'ptkp'])->with('message-success', 'Data saved successfully');
        // return redirect()->route('administrator.payroll-setting.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * Store Earnings
     * @return redirect
     */
    public function storeEarnings(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'title'  => 'required',
            'taxable' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->route('administrator.payroll-setting.index')->with('message-error', "Title should not be empty");
        }
        $data              = new PayrollEarnings();
        $data->title       = $request->title;
        $data->taxable     = $request->taxable;
        $data->project_id  = $user->project_id;

        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data->user_created = $user->id;
        } 
        $data->save();

        return redirect()->route('administrator.payroll-setting.index')->with('message-success', __('general.message-data-saved-success'));
    }

    /**
     * Store Deductions
     * @return redirect
     */
    public function storeDeductions(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make(request()->all(), [
            'title'  => 'required',
            'taxable' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->route('administrator.payroll-setting.index')->with('message-error', "Title should not be empty");
        }
        $data              = new PayrollDeductions();
        $data->title       = $request->title;
        $data->taxable     = $request->taxable;
        $data->project_id  = $user->project_id;
        $user = \Auth::user();
        if($user->project_id != NULL)
        {
            $data->user_created = $user->id;
        } 
        $data->save();

        return redirect()->route('administrator.payroll-setting.index')->with('message-success', __('general.message-data-saved-success'));
    }

    /**
     * Delete Earnings
     * @param  integer $id
     * @return redirect
     */
    public function deleteEarnings($id)
    {
        $data = PayrollEarnings::where('id', $id)->first();
        if($data)
        {
            $count        = PayrollEarningsEmployee::where('payroll_earning_id',$data->id)->count();
            $countHistory = PayrollEarningsEmployeeHistory::where('payroll_earning_id',$data->id)->count();
            if($count > 0 || $countHistory > 0)
                return redirect()->route('administrator.payroll-setting.index')->with('message-error', 'Can not delete data!');
            $data->delete();
        }
        return redirect()->route('administrator.payroll-setting.index')->with('message-success', __('general.message-data-deleted'));
    }

    /**
     * Delete Deductions
     * @param  integer $id
     * @return redirect
     */
    public function deleteDeductions($id)
    {
        $data = PayrollDeductions::where('id', $id)->first();
        if($data)
        {
            $count        = PayrollDeductionsEmployee::where('payroll_deduction_id',$data->id)->count();
            $countHistory = PayrollDeductionsEmployeeHistory::where('payroll_deduction_id',$data->id)->count();
            if($count > 0 || $countHistory > 0)
                return redirect()->route('administrator.payroll-setting.index')->with('message-error', 'Can not delete data!');
            $data->delete();
        }
        return redirect()->route('administrator.payroll-setting.index')->with('message-success', __('general.message-data-deleted'));
    }

    /**
     * Store General
     * @param  Request $request
     * @return redirect
     */
    public function storeGeneral(Request $request)
    {
        $user = \Auth::user();
        if($request->setting)
        {
            if($user->project_id != NULL)
            {
                foreach($request->setting as $key => $value)
                {
                    $setting = Setting::where('key', $key)->where('project_id',$user->project_id)->first();
                    if(!$setting)
                    {
                        $setting = new Setting();
                        $setting->key = $key;
                    }
                    $setting->user_created = $user->id;
                    $setting->project_id = $user->project_id;
                    $setting->value = $value;
                    $setting->save();
                }
            }else{
                foreach($request->setting as $key => $value)
                {
                    $setting = Setting::where('key', $key)->first();
                    if(!$setting)
                    {
                        $setting = new Setting();
                        $setting->key = $key;
                    }
                    $setting->value = $value;
                    $setting->save();
                }
            }
        }
        return redirect()->route('administrator.payroll-setting.index')->with('message-success', __('general.message-data-saved-success'));
    }
}
