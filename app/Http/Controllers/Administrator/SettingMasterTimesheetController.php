<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\TimesheetActivity;
use App\Models\TimesheetCategory;
use App\Models\SettingApprovalTimesheetTransactionItem;
use Illuminate\Http\Request;
use App\User;

class SettingMasterTimesheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:29');
    }
    public function index(Request $request)
    {
        if ($request->activity_category) {
            $params['activity_category'] = $request->activity_category;
            $params['activities'] = TimesheetActivity::where('delete_status', 0)->where('timesheet_category_id', $request->activity_category)->withTrashed()->orderBy('timesheet_category_id')->orderBy('id')->get();
        } else {
            $params['activity_category'] = null;
            $params['activities'] = TimesheetActivity::where('delete_status', 0)->withTrashed()->orderBy('timesheet_category_id')->orderBy('id')->get();
        }

        if ($request->summary_category) {
            $params['summary_category'] = $request->summary_category;
            $params['summary_categories'] = TimesheetCategory::where('delete_status', 0)->withTrashed()->where('id', $request->summary_category)->get();
            $params['categories'] = TimesheetCategory::where('delete_status', 0)->withTrashed()->get();
        } else {
            $params['summary_category'] = null;
            $params['summary_categories'] = TimesheetCategory::where('delete_status', 0)->withTrashed()->get();
            $params['categories'] = $params['summary_categories'];
        }

        if ($request->tab) {
            $params['tab'] = $request->tab;
        }

        return view('administrator.setting-timesheet.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrator.setting-timesheet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = new TimesheetCategory();
        $data->name = $request->name;
        $data->save();
        $data->delete();

        return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data saved successfully !');
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
        $params['data'] = TimesheetCategory::withTrashed()->find($id);

        return view('administrator.setting-timesheet.edit')->with($params);
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
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = TimesheetCategory::withTrashed()->find($id);
        $data->name = $request->name;
        $data->save();

        return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->delete_status) {
            TimesheetCategory::withTrashed()->find($id)->update(['delete_status' => $request->delete_status]);
            TimesheetActivity::withTrashed()->where('timesheet_category_id', $id)->update(['delete_status' => $request->delete_status]);
        }

        TimesheetCategory::destroy($id);

        return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data disabled successfully');
    }

    public function restore($id)
    {
        TimesheetCategory::withTrashed()->find($id)->restore();
        TimesheetActivity::withTrashed()->where('timesheet_category_id', $id)->where('delete_status', 0)->restore();

        return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data activated successfully');
    }

    public function createActivity()
    {
        $params['categories'] = TimesheetCategory::where('delete_status', 0)->withTrashed()->get();

        return view('administrator.setting-timesheet.create-activity')->with($params);
    }

    public function storeActivity(Request $request)
    {
        $this->validate($request, [
            'timesheet_category_id' => 'required|exists:timesheet_categories,id',
            'name' => 'required',
        ], [
            'timesheet_category_id.required' => 'The category field is required',
        ]);

        $data = new TimesheetActivity();
        $data->timesheet_category_id = $request->timesheet_category_id;
        $data->name = $request->name;
        $data->save();
        $data->delete();

        // return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data saved successfully !');
        return \Redirect::route('administrator.setting-timesheet.index', ['tab' => 'activity'])->with('message-success', 'Data saved successfully !');
    }

    public function editActivity($id)
    {
        $params['categories'] = TimesheetCategory::where('delete_status', 0)->withTrashed()->get();
        $params['data'] = TimesheetActivity::withTrashed()->find($id);

        return view('administrator.setting-timesheet.edit-activity')->with($params);
    }

    public function updateActivity(Request $request, $id)
    {
        $this->validate($request, [
            'timesheet_category_id' => 'required|exists:timesheet_categories,id',
            'name' => 'required',
        ]);

        $data = TimesheetActivity::withTrashed()->find($id);
        $data->timesheet_category_id = $request->timesheet_category_id;
        $data->name = $request->name;
        $data->save();

        // return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data saved successfully');
        return \Redirect::route('administrator.setting-timesheet.index', ['tab' => 'activity'])->with('message-success', 'Data saved successfully');
    }

    public function destroyActivity(Request $request, $id)
    {
        if ($request->delete_status) {
            TimesheetActivity::withTrashed()->find($id)->update(['delete_status' => $request->delete_status]);
        }

        TimesheetActivity::destroy($id);

        // return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data disabled successfully');
        return \Redirect::route('administrator.setting-timesheet.index', ['tab' => 'activity'])->with('message-success', 'Data disabled successfully');
    }

    public function restoreActivity($id)
    {
        TimesheetActivity::withTrashed()->find($id)->restore();

        // return redirect()->route('administrator.setting-timesheet.index')->with('message-success', 'Data activated successfully');
        return \Redirect::route('administrator.setting-timesheet.index', ['tab' => 'activity'])->with('message-success', 'Data activated successfully');
    }

    public function userToBeAssigned($id)
    {
        $data = User::whereIn('access_id',[1,2])
            ->leftJoin('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')
            ->leftJoin('organisasi_position', 'structure_organization_custom.organisasi_position_id', '=', 'organisasi_position.id')
            ->leftJoin('organisasi_division', 'structure_organization_custom.organisasi_division_id', '=', 'organisasi_division.id')
            ->leftjoin('setting_approval_timesheet_transaction_item', function ($join) use ($id) {
                $join->on('users.id', '=', 'setting_approval_timesheet_transaction_item.user_id')
                    ->where('setting_approval_timesheet_transaction_item.timesheet_category_id', $id);
            })
            ->select(
                'users.id',
                'setting_approval_timesheet_transaction_item.id as setting_approval_timesheet_transaction_item_id',
                'users.nik',
                'users.name',
                'organisasi_division.name as division',
                'organisasi_position.name as position'
            )->where('users.access_id', '!=', '3')->get();

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

    public function assignApproval(Request $request)
    {
        SettingApprovalTimesheetTransactionItem::where('timesheet_category_id', $request->category_id)->delete();

        foreach ($request->user_id ?: [] as $key => $value) {
            $data = new SettingApprovalTimesheetTransactionItem;
            $data->timesheet_category_id = $request->category_id;
            $data->user_id = $value;
            $data->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Assign approval success']);
    }
}
