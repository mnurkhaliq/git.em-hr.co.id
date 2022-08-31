@extends('layouts.administrator')

@section('title', 'Shift Setting')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Shift Setting</h4> </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div id="showSetting">
                    <div class="white-box p-l-1 p-r-1">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="{{ !$tab ? 'active' : '' }}"><a href="#shift" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="true"> Shift</a></li>
                            <li role="presentation" class="{{ $tab == 'list' ? 'active' : '' }}"><a href="#shiftlist" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Shift List</span></a></li>
                            @if(checkModuleAdmin(30))
                            <li role="presentation" class="{{ $tab == 'schedule' ? 'active' : '' }}"><a href="#shiftschedule" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Shift Schedule Change</span></a></li>
                            @endif
                            <li role="presentation" class=""><a href="#shiftchange" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Shift Change History</span></a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade {{ !$tab ? 'active in' : '' }}" id="shift">
                                <h3 class="box-title m-b-0">Shift</h3>
                                <form class="form-horizontal" id="form-shift" enctype="multipart/form-data" name="form_shift" method="POST">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    </br>
                                    <div class="col-md-6 p-l-0 p-r-0">
                                        <div class="form-group">
                                            <label class="col-md-12">Shift Name</label>
                                            <div class="col-md-6">
                                                <input autocomplete="off" id="shift-name" type="text" class="form-control" name="shift_name">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Branch</label>
                                            <div class="col-md-6">
                                            {{--<label>Select Branch</label>--}}
                                                <select name="branch_id" class="form-control" id="branch_id" >
                                                    <?php $branches = get_branches()?>
                                                    <option value="0">- Select Branch -</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Workdays</label>
                                            <div class="col-md-2">
                                                <label><input id="workdays" class="workdays" type="radio" name="workdays" value="dynamic"> Dynamic</label>
                                            </div>
                                            <div class="col-md-10">
                                                <label><input id="workdays" class="workdays" type="radio" name="workdays" value="fixed"> Fixed</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Public Holiday ("On" will ignore public holidays)</label>
                                            <div class="col-md-2">
                                                <label><input id="public_holiday" class="public_holiday" type="radio" name="public_holiday" value="1"> On</label>
                                            </div>
                                            <div class="col-md-10">
                                                <label><input id="public_holiday" class="public_holiday" type="radio" name="public_holiday" value="0"> Off</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Collective Leave ("On" will ignore collective leave)</label>
                                            <div class="col-md-2">
                                                <label><input id="collective_leave" class="collective_leave" type="radio" name="collective_leave" value="1"> On</label>
                                            </div>
                                            <div class="col-md-10">
                                                <label><input id="collective_leave" class="collective_leave" type="radio" name="collective_leave" value="0"> Off</label>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-md-12">Default Shift Time</label>
                                            <div class="col-md-6">
                                                <input autocomplete="off" id="clock_in_default" type="text" class="form-control" name="clock_in" placeholder="Clock In">
                                            </div>
                                            <div class="col-md-6">
                                                <input autocomplete="off" id="clock_out_default" type="text" class="form-control" name="clock_out" placeholder="Clock Out">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-l-0 p-r-0">
                                        <table class="table">
                                            <tr>
                                                <th><input id="check-top" type="checkbox"></th>
                                                <th>Day</th>
                                                <th style="max-width:100px">Clock In</th>
                                                <th style="max-width:100px">Clock Out</th>
                                                <th>Work Hour(s)</th>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" class="check-child"></td>
                                                <td style="vertical-align:middle;" id="day1">Monday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(1)" class="form-control clock-in" type="text" name="clock_in" id="clock_in1"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(1)" class="form-control clock-out" type="text" name="clock_out" id="clock_out1"></td>
                                                <td style="vertical-align:middle;" id="work_hour1" class="work-hour">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" class="check-child"></td>
                                                <td style="vertical-align:middle;" id="day2">Tuesday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(2)" class="form-control clock-in" type="text" name="clock_in" id="clock_in2"></td>
                                                <td style="vertical-align:middle;" style="max-width:200px;"><input autocomplete="off" onchange="workHour(2)" class="form-control clock-out" type="text" name="clock_out" id="clock_out2"></td>
                                                <td style="vertical-align:middle;" id="work_hour2" class="work-hour">0<td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" class="check-child"></td>
                                                <td style="vertical-align:middle;" id="day3">Wednesday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(3)" class="form-control clock-in" type="text" name="clock_in" id="clock_in3"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(3)" class="form-control clock-out" type="text" name="clock_out" id="clock_out3"></td>
                                                <td style="vertical-align:middle;" id="work_hour3" class="work-hour">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" class="check-child"></td>
                                                <td style="vertical-align:middle;" id="day4">Thursday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(4)" class="form-control clock-in" type="text" name="clock_in" id="clock_in4"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(4)" class="form-control clock-out" type="text" name="clock_out" id="clock_out4"></td>
                                                <td style="vertical-align:middle;" id="work_hour4" class="work-hour">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" class="check-child"></td>
                                                <td style="vertical-align:middle;" id="day5">Friday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(5)" class="form-control clock-in" type="text" name="clock_in" id="clock_in5"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(5)" class="form-control clock-out" type="text" name="clock_out" id="clock_out5"></td>
                                                <td style="vertical-align:middle;" id="work_hour5" class="work-hour">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" class="check-child"></td>
                                                <td style="vertical-align:middle;" id="day6">Saturday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(6)" class="form-control clock-in" type="text" name="clock_in" id="clock_in6"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(6)" class="form-control clock-out" type="text" name="clock_out" id="clock_out6"></td>
                                                <td style="vertical-align:middle;" id="work_hour6" class="work-hour">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" class="check-child"></td>
                                                <td style="vertical-align:middle;" id="day7">Sunday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(7)" class="form-control clock-in" type="text" name="clock_in" id="clock_in7"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHour(7)" class="form-control clock-out" type="text" name="clock_out" id="clock_out7"></td>
                                                <td style="vertical-align:middle;" id="work_hour7" class="work-hour">0</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <br />
                                    <div class="col-md-12">
                                        <button id="saveShift" type="button" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Save</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 'list' ? 'active in' : '' }}" id="shiftlist">
                                <h3 class="box-title m-b-0">Shift List</h3>
                                <table id="data_table_no_search" class="display nowrap">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>NAME</th>
                                        <th>BRANCH</th>
                                        <th>TOTAL EMPLOYEE(s)</th>
                                        <th>WORKDAYS TYPE</th>
                                        <th>PUBLIC HOLIDAY</th>
                                        <th>COLLECTIVE LEAVE</th>
                                        <th>MANAGE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $no => $item)
                                    <tr>
                                        <td>{{$no+1}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->branch}}</td>
                                        <td>{{$item->total_employees}}</td>
                                        <td>{{strtoupper($item->workdays)}}</td>
                                        <td>{{$item->is_holiday == '1' ? 'On' : 'Off'}}</td>
                                        <td>{{$item->is_collective == '1' ? 'On' : 'Off'}}</td>
                                        <td>
                                            <button onclick="editShift('{{$item->id}}')" type="button" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit </button>
                                            <button onclick="deleteShift('{{$item->id}}')" type="button" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> Delete</button>
                                            <button onclick="assignTo('{{$item->id}}')" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                            @if(checkModuleAdmin(30))
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 'schedule' ? 'active in' : '' }}" id="shiftschedule">
                                <h3 class="box-title m-b-0">Shift Schedule Change</h3>
                                <div class="table-responsive">
                                    <div class="form-group col-md-11">
                                        <a href="javascript:void(0)" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#modal_import">Import Excel <i class="fa fa-upload"></i></a>
                                    </div>
                                    <div class="form-group col-md-1 pull-right">
                                        <button class="btn btn-sm btn-info pull-right" type="button" id="addSchedule" autocomplete="off">Add New Data</button>
                                    </div>
                                    <table class="display nowrap dataTable no-footer scheduleTable" role="grid" aria-describedby="data_table_no_search_info"></table>
                                </div>
                            </div>
                            @endif
                            <div role="tabpanel" class="tab-pane fade" id="shiftchange">
                                <h4 class="box-title m-b-0">Summary Table</h4>
                                <br>
                                <div class="table-responsive">
                                    <table class="display nowrap dataTable no-footer displayScheduleTable" role="grid" aria-describedby="data_table_no_search_info"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display:none" id="editSetting">
                    <div class="white-box p-l-1 p-r-1">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" id="shift-tab" class="active"><a href="#shift-edit" aria-controls="general" role="tab" data-toggle="tab" aria-expanded="true"> Shift</a></li>
                            <li role="presentation" id="shift-list-tab" class=""><a href="#shiftlist-edit" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Shift List</span></a></li>
                            @if(checkModuleAdmin(30))
                            <li role="presentation" id="shift-schedule-tab" class=""><a href="#shiftschedule-edit" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Shift Schedule Change</span></a></li>
                            @endif
                            <li role="presentation" id="shift-change-tab" class=""><a href="#shiftchange-edit" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Shift Change History</span></a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="shift-edit">
                                <h3 class="box-title m-b-0">Shift</h3>
                                <form class="form-horizontal" id="form-shift-edit" enctype="multipart/form-data" name="form_shift_edit" method="POST">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    </br>
                                    <div class="col-md-6 p-l-0 p-r-0">
                                        <div class="form-group">
                                            <label class="col-md-12">Shift Name</label>
                                            <div class="col-md-6">
                                                <input autocomplete="off" id="shift-name-edit" type="text" class="form-control" name="shift_name_edit">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Branch</label>
                                            <div class="col-md-6">
                                            {{--<label>Select Branch</label>--}}
                                                <select name="branch_id_edit" class="form-control" id="branch_id_edit" >
                                                    <?php $branches = get_branches()?>
                                                    <option value="0">- Select Branch -</option>
                                                    @foreach($branches as $branch)
                                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Workdays</label>
                                            <div class="col-md-2">
                                                <label><input id="workdays_edit1" class="workdays_edit" type="radio" name="workdays_edit" value="dynamic"> Dynamic</label>
                                            </div>
                                            <div class="col-md-10">
                                                <label><input id="workdays_edit2" class="workdays_edit" type="radio" name="workdays_edit" value="fixed"> Fixed</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Public Holiday ("On" will ignore public holidays)</label>
                                            <div class="col-md-2">
                                                <label><input id="public_holiday_edit1" class="public_holiday_edit" type="radio" name="public_holiday_edit" value="1"> On</label>
                                            </div>
                                            <div class="col-md-10">
                                                <label><input id="public_holiday_edit2" class="public_holiday_edit" type="radio" name="public_holiday_edit" value="0"> Off</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Collective Leave ("On" will ignore collective leave)</label>
                                            <div class="col-md-2">
                                                <label><input id="collective_leave_edit1" class="collective_leave_edit" type="radio" name="collective_leave_edit" value="1"> On</label>
                                            </div>
                                            <div class="col-md-10">
                                                <label><input id="collective_leave_edit2" class="collective_leave_edit" type="radio" name="collective_leave_edit" value="0"> Off</label>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-md-12">Default Shift Time</label>
                                            <div class="col-md-6">
                                                <input autocomplete="off" id="clock_in_default_edit" type="text" class="form-control" name="clock_in" placeholder="Clock In">
                                            </div>
                                            <div class="col-md-6">
                                                <input autocomplete="off" id="clock_out_default_edit" type="text" class="form-control" name="clock_out" placeholder="Clock Out">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-l-0 p-r-0">
                                        <table class="table">
                                            <tr>
                                                <th><input id="check-top-edit" type="checkbox"></th>
                                                <th>Day</th>
                                                <th style="max-width:100px">Clock In</th>
                                                <th style="max-width:100px">Clock Out</th>
                                                <th>Work Hour(s)</th>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" id="check1" class="check-child-edit"></td>
                                                <td style="vertical-align:middle;" class="days" id="day_edit1">Monday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(1)" class="form-control clock-in-edit" type="text" name="clock_in" id="clock_in_edit1"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(1)" class="form-control clock-out-edit" type="text" name="clock_out" id="clock_out_edit1"></td>
                                                <td style="vertical-align:middle;" id="work_hour_edit1" class="work-hour-edit">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" id="check2" class="check-child-edit"></td>
                                                <td style="vertical-align:middle;" class="days" id="day_edit2">Tuesday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(2)" class="form-control clock-in-edit" type="text" name="clock_in" id="clock_in_edit2"></td>
                                                <td style="vertical-align:middle;" style="max-width:200px;"><input autocomplete="off" onchange="workHourEdit(2)" class="form-control clock-out-edit" type="text" name="clock_out" id="clock_out_edit2"></td>
                                                <td style="vertical-align:middle;" id="work_hour_edit2" class="work-hour-edit">0<td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" id="check3" class="check-child-edit"></td>
                                                <td style="vertical-align:middle;" class="days" id="day_edit3">Wednesday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(3)" class="form-control clock-in-edit" type="text" name="clock_in" id="clock_in_edit3"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(3)" class="form-control clock-out-edit" type="text" name="clock_out" id="clock_out_edit3"></td>
                                                <td style="vertical-align:middle;" id="work_hour_edit3" class="work-hour-edit">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" id="check4" class="check-child-edit"></td>
                                                <td style="vertical-align:middle;" class="days" id="day_edit4">Thursday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(4)" class="form-control clock-in-edit" type="text" name="clock_in" id="clock_in_edit4"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(4)" class="form-control clock-out-edit" type="text" name="clock_out" id="clock_out_edit4"></td>
                                                <td style="vertical-align:middle;" id="work_hour_edit4" class="work-hour-edit">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" id="check5" class="check-child-edit"></td>
                                                <td style="vertical-align:middle;" class="days" id="day_edit5">Friday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(5)" class="form-control clock-in-edit" type="text" name="clock_in" id="clock_in_edit5"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(5)" class="form-control clock-out-edit" type="text" name="clock_out" id="clock_out_edit5"></td>
                                                <td style="vertical-align:middle;" id="work_hour_edit5" class="work-hour-edit">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" id="check6" class="check-child-edit"></td>
                                                <td style="vertical-align:middle;" class="days" id="day_edit6">Saturday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(6)" class="form-control clock-in-edit" type="text" name="clock_in" id="clock_in_edit6"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(6)" class="form-control clock-out-edit" type="text" name="clock_out" id="clock_out_edit6"></td>
                                                <td style="vertical-align:middle;" id="work_hour_edit6" class="work-hour-edit">0</td>
                                            </tr>
                                            <tr>
                                                <td style="vertical-align:middle;"><input type="checkbox" id="check7" class="check-child-edit"></td>
                                                <td style="vertical-align:middle;" class="days" id="day_edit7">Sunday</td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(7)" class="form-control clock-in-edit" type="text" name="clock_in" id="clock_in_edit7"></td>
                                                <td style="vertical-align:middle;" style="max-width:100px;"><input autocomplete="off" onchange="workHourEdit(7)" class="form-control clock-out-edit" type="text" name="clock_out" id="clock_out_edit7"></td>
                                                <td style="vertical-align:middle;" id="work_hour_edit7" class="work-hour-edit">0</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <br />
                                    <div class="col-md-12">
                                        <button id="cancelUpdate" type="button" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Cancel</button>
                                        <button id="updateShift" type="button" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Save</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="shiftlist-edit">
                                <h3 class="box-title m-b-0">Shift List</h3>
                                <table id="data_table_no_search" class="display nowrap dataTable no-footer" role="grid" aria-describedby="data_table_no_search_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="#: activate to sort column descending" style="width:0px">NO</th>
                                        <th class="sorting" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="NAME: activate to sort column descending" style="width:0px">NAME</th>
                                        <th class="sorting" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="BRANCH: activate to sort column descending" style="width:0px">BRANCH</th>
                                        <th class="sorting" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="TOTAL EMPLOYEE(s): activate to sort column descending" style="width:0px">TOTAL EMPLOYEE(s)</th>
                                        <th class="sorting" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="WORKDAYS TYPE: activate to sort column descending" style="width:0px">WORKDAYS TYPE</th>
                                        <th class="sorting" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="PUBLIC HOLIDAY: activate to sort column descending" style="width:0px">PUBLIC HOLIDAY</th>
                                        <th class="sorting" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="COLLECTIVE LEAVE: activate to sort column descending" style="width:0px">COLLECTIVE LEAVE</th>
                                        <th class="sorting" tabindex="0" aria-controls="data_table_no_search" rowspan="1" colspan="1" aria-sort="ascending" aria-label="MANAGE: activate to sort column descending" style="width:0px">MANAGE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $no => $item)
                                    @if($no%2==0)
                                    <tr role="row" class="odd">
                                    @else
                                    <tr role="row" class="even">
                                    @endif
                                        <td class="sorting_1">{{$no+1}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->branch}}</td>
                                        <td>{{$item->total_employees}}</td>
                                        <td>{{strtoupper($item->workdays)}}</td>
                                        <td>{{$item->is_holiday == '1' ? 'On' : 'Off'}}</td>
                                        <td>{{$item->is_collective == '1' ? 'On' : 'Off'}}</td>
                                        <td>
                                            <button onclick="editShift('{{$item->id}}')" type="button" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit </button>
                                            <button onclick="deleteShift('{{$item->id}}')" type="button" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> Delete</button>
                                            <button onclick="assignTo('{{$item->id}}')" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                            @if(checkModuleAdmin(30))
                            <div role="tabpanel" class="tab-pane fade" id="shiftschedule-edit">
                                <h3 class="box-title m-b-0">Shift Schedule Change</h3>
                                <div class="table-responsive">
                                    <div class="form-group col-md-11">
                                        <a href="javascript:void(0)" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#modal_import">Import Excel <i class="fa fa-upload"></i></a>
                                    </div>
                                    <div class="form-group col-md-1 pull-right">
                                        <button class="btn btn-sm btn-info pull-right" type="button" id="addSchedule" autocomplete="off">Add New Data</button>
                                    </div>
                                    <table class="display nowrap dataTable no-footer scheduleTable" role="grid" aria-describedby="data_table_no_search_info"></table>
                                </div>
                            </div>
                            @endif
                            <div role="tabpanel" class="tab-pane fade" id="shiftchange-edit">
                                <h4 class="box-title m-b-0">Summary Table</h4>
                                <br>
                                <div class="table-responsive">
                                    <table class="display nowrap dataTable no-footer displayScheduleTable" role="grid" aria-describedby="data_table_no_search_info"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>                
        </div>
    </div>

    <div class="modal fade none-border" id="modal-assign">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Assign To</strong></h4>
                </div>
                <div class="modal-body" id="modal-assign-body">
                    <div class="table-responsive">
                        <input type="hidden" id="idShift">
                        <input type="text" class="form-control" id="searchUser">
                        <br />
                        <table class="table" id="tableList">
                            <tr>
                                <th><input type="checkbox" id="checkTopUser"></th>
                                <th>NO</th>
                                <th>NIK</th>
                                <th>NAME</th>
                                <th>POSITION</th>
                                <th>DIVISION</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade none-border" id="modal-assign2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Assign Users to Shift Schedule Change</strong></h4>
                </div>
                <div class="modal-body" id="modal-assign2-body">
                    <div class="table-responsive">
                        <input type="hidden" id="SettingId">
                        <input type="text" class="form-control" id="searchUser2">
                        <br />
                        <table class="table" id="tableList2">
                            <tr>
                                <th><input type="checkbox" id="checkTopUser2"></th>
                                <th>NO</th>
                                <th>NIK</th>
                                <th>NAME</th>
                                <th>POSITION</th>
                                <th>DIVISION</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div  class="modal fade none-border" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Add New Shift Schedule Change</strong></h4>
                </div>
                <form id="scheduleForm">
                    <input type="hidden" id="schedule_id" name="schedule_id">
                    <div class="modal-body" id="modal-add-body">
                        <div class="form-group col-md-12">
                            <label>Shift</label>
                            <div>
                                <select required name="shift_id" class="form-control" id="shift_id">
                                    <option value="" selected hidden> - Select Shift - </option>
                                    @foreach($list as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Change Date</label>
                            <div>
                                <input required autocomplete="off" type="text" id="change_date" name="change_date" class="form-control datepicker">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success save-event waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(checkModuleAdmin(30))
    <div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Import Data</h4>
                </div>
                <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('shift-schedule.import') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">File (xls)</label>
                            <div class="col-md-9">
                                <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="file" class="form-control" />
                            </div>
                        </div>
                        <a href="{{ asset('storage/sample/Sample-Shift-Schedule.xlsx') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <label class="btn btn-info btn-sm" id="btn_import">Import</label>
                    </div>
                </form>
                <div style="text-align: center;display: none;" class="div-proses-upload">
                    <h3>Uploading !</h3>
                    <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
                </div>
            </div>
        </div>
    </div>
    @endif
    @include('layouts.footer')
</div>

<style type="text/css" media="screen">
    .clockpicker-popover { z-index: 9999 !important; }
</style>
@section('js')
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript">

var id_shift = ''
var shift_name = ''
var workdays = ''
var holiday = ''
var collective = ''
var branch = '0'
var scheduleTable = null
var displayScheduleTable = null
var users = []

function assignTo(v){
    $.ajax({
        url: "attendance/user-list-for-assignment/"+v,
        type: "GET",
        dataType: "JSON",
        contentType: "application/json",
        processData: false,
        success: function(data){
            if(data.message == 'success'){
                $('#idShift').val(v)
                $('#tableList').find('tr:gt(0)').remove()
                for(var i = 0; i < data.data.length; i++){
                    var num = i+1;
                    if(data.data[i].shift_id == v){
                        var pos = data.data[i].position != null ? data.data[i].position : '-'
                        var div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableList tr:last').after(
                            '<tr class="search">'+
                                '<td><input id="checkUser'+num+'" type="checkbox" class="checkUser" checked></td>'+
                                '<td><input id="idUser'+num+'" type="hidden" value="'+data.data[i].id+'">'+num+'</td>'+
                                '<td>'+data.data[i].nik+'</td>'+
                                '<td>'+data.data[i].name+'</td>'+
                                '<td>'+pos+'</td>'+
                                '<td>'+div+'</td>'+
                            '</tr>'
                        )
                    }
                    else{
                        var pos = data.data[i].position != null ? data.data[i].position : '-'
                        var div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableList tr:last').after(
                            '<tr class="search">'+
                                '<td><input id="checkUser'+num+'" type="checkbox" class="checkUser"></td>'+
                                '<td><input id="idUser'+num+'" type="hidden" value="'+data.data[i].id+'">'+num+'</td>'+
                                '<td>'+data.data[i].nik+'</td>'+
                                '<td>'+data.data[i].name+'</td>'+
                                '<td>'+pos+'</td>'+
                                '<td>'+div+'</td>'+
                            '</tr>'
                        )
                    }
                }
                $('#tableList tr:last').after(
                    '<tr>'+
                        '<td colspan="6"><button id="assignShift" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>'+
                    '</tr>'
                )
                $('#modal-assign').modal('show')
                assign()
            }
            else{
                $('#tableList').find('tr:gt(0)').remove()
                $('#tableList tr:last').after(
                    '<tr>'+
                        '<td colspan="6">No data.</td>'+
                    '</tr>'
                )
                $('#modal-assign').modal('show')
            }
        }
    })
    // $('#modal-assign').modal('show')
}

function workHour(v){
    var a = moment.utc($('#clock_in'+v).val(), "HH:mm")
    var b = moment.utc($('#clock_out'+v).val(), "HH:mm")
    if(a != '' && b != ''){
        var d = moment.duration(b.diff(a))
        var s = moment.utc(+d).format('H:mm');
        $('#work_hour'+v).html(s != 'Invalid date' ? s : '0')
    }
}

function workHourEdit(v){
    var a = moment.utc($('#clock_in_edit'+v).val(), "HH:mm")
    var b = moment.utc($('#clock_out_edit'+v).val(), "HH:mm")
    if(a != '' && b != ''){
        var d = moment.duration(b.diff(a))
        var s = moment.utc(+d).format('H:mm');
        $('#work_hour_edit'+v).html(s != 'Invalid date' ? s : '0')
    }
}

function deleteShift(id){
    swal({
        title: 'Are you sure?',
        text: "Once deleted, you will not be able to recover this data!",
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: "attendance/shift-delete/"+id,
                type: "DELETE",
                data:{'_token':"{{csrf_token()}}"},
                dataType: "JSON",
                success: function (data) {
                    if (data.status == 'success') {
                        swal("Success!", data.message, "success");
                        window.location.href = "{{ url('shift-setting') }}"+"?tab=list";
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        } else {

        }
    });
}

function editShift(id){
    $.ajax({
        url: "attendance/shift-edit/"+id,
        type: "GET",
        dataType: "JSON",
        contentType: "application/json",
        processData: false,
        success: function(data){
            // console.log(data.detail[0].day)
            if(data.message == 'success'){
                $('.check-child-edit').attr('checked', false)
                $('#form-shift-edit')[0].reset()
                $('#shift-name-edit').val(data.data.name)
                $('#branch_id_edit').val(data.data.branch_id)
                id_shift = data.data.id

                if(data.data.workdays == 'dynamic'){
                    workdays = 'dynamic'
                    $('.clock-in-edit').attr('disabled', false)
                    $('.clock-out-edit').attr('disabled', false)
                    $('#workdays_edit1').attr('checked', true)
                    $('#workdays_edit2').attr('checked', false)
                    $('#clock_in_default_edit').attr('disabled', true)
                    $('#clock_out_default_edit').attr('disabled', true)
                    $('.days').each(function(i){
                        for(var y = 0; y < data.detail.length; y++){
                            var baru = i+1
                            if($(this).text() == data.detail[y].day){
                                // $(this).html('ashduia')
                                $('#check'+baru).attr('checked', true)
                                $('#clock_in_edit'+baru).val(data.detail[y].clock_in)
                                $('#clock_out_edit'+baru).val(data.detail[y].clock_out)
                            }

                            // $('#check'+baru).attr('checked', false)
                        }
                    })

                    $('.work-hour-edit').each(function(i){
                        var angka = i+1
                        var a = moment.utc($('#clock_in_edit'+angka).val(), "HH:mm")
                        var b = moment.utc($('#clock_out_edit'+angka).val(), "HH:mm")
                        if(a != '' && b != ''){
                            var d = moment.duration(b.diff(a))
                            var s = moment.utc(+d).format('H:mm');
                            $('#work_hour_edit'+angka).html(s != 'Invalid date' ? s : '0')
                        }
                    })
                }
                else{
                    workdays = 'fixed'
                    $('#workdays_edit2').attr('checked', true)
                    $('#workdays_edit1').attr('checked', false)
                    $('#clock_in_default_edit').attr('disabled', false)
                    $('#clock_out_default_edit').attr('disabled', false)
                    $('#clock_in_default_edit').val(data.detail[0].clock_in)
                    $('#clock_out_default_edit').val(data.detail[0].clock_out)
                    $('.clock-in-edit').attr('disabled', true)
                    $('.clock-out-edit').attr('disabled', true)
                    $('.clock-in-edit').val(data.detail[0].clock_in)
                    $('.clock-out-edit').val(data.detail[0].clock_out)
                    $('.days').each(function(i){
                        for(var y = 0; y < data.detail.length; y++){
                            var baru = i+1
                            if($(this).text() == data.detail[y].day){
                                // $(this).html('ashduia')
                                $('#check'+baru).attr('checked', true)
                                $('#clock_in_edit'+baru).val(data.detail[y].clock_in)
                                $('#clock_out_edit'+baru).val(data.detail[y].clock_out)
                            }

                            // $('#check'+baru).attr('checked', false)
                        }
                    })

                    $('.work-hour-edit').each(function(i){
                        var angka = i+1
                        var a = moment.utc($('#clock_in_edit'+angka).val(), "HH:mm")
                        var b = moment.utc($('#clock_out_edit'+angka).val(), "HH:mm")
                        if(a != '' && b != ''){
                            var d = moment.duration(b.diff(a))
                            var s = moment.utc(+d).format('H:mm');
                            $('#work_hour_edit'+angka).html(s != 'Invalid date' ? s : '0')
                        }
                    })
                }

                if(data.data.is_holiday == '1') {
                    holiday = '1'
                    $('#public_holiday_edit1').attr('checked', true)
                    $('#public_holiday_edit2').attr('checked', false)
                } else {
                    holiday = '0'
                    $('#public_holiday_edit2').attr('checked', true)
                    $('#public_holiday_edit1').attr('checked', false)
                }

                if(data.data.is_collective == '1') {
                    collective = '1'
                    $('#collective_leave_edit1').attr('checked', true)
                    $('#collective_leave_edit2').attr('checked', false)
                } else {
                    collective = '0'
                    $('#collective_leave_edit2').attr('checked', true)
                    $('#collective_leave_edit1').attr('checked', false)
                }

                $('#showSetting').hide()
                $('#editSetting').show()
                $('#shift-list-tab').attr('class', '')
                $('#shift-tab').attr('class', 'active')
                $('#shiftlist-edit').attr('class', 'tab-pane fade')
                $('#shift-edit').attr('class', 'tab-pane fade active in')
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    })
}

function assign(){
    $('#assignShift').click(function(){
        var arr_check = []
        var arr_uncheck = []
        var id_user = []
        var id_user_uncheck = []
        var id_shift = $('#idShift').val()

        $('.checkUser').each(function(i){
            if($(this).prop('checked') == true){
                arr_check.push(i+1)
            }
        })

        $('.checkUser').each(function(i){
            if(!$(this).prop('checked') == true){
                arr_uncheck.push(i+1)
            }
        })

        for(var i = 0; i < arr_check.length; i++){
            id_user.push($('#idUser'+arr_check[i]).val())
        }

        for(var i = 0; i < arr_uncheck.length; i++){
            id_user_uncheck.push($('#idUser'+arr_uncheck[i]).val())
        }
        // console.log(id_user)

        $.ajax({
            url: "{{ route('administrator.attendance.assign-shift') }}",
            type: "POST",
            data: {'_token': '{{csrf_token()}}', 'shift_id': id_shift, 'user_id': id_user, 'user_id_uncheck': id_user_uncheck},
            dataType: "JSON",
            success: function(data){
                swal("Success!", data.message, "success");
                $('#form-shift-edit')[0].reset()
                window.location.href = "{{ url('shift-setting') }}"+"?tab=list";
                // console.log(data)
            },
        })
    })
}

$(function(){
    //toggle `popup` / `inline` mode
    //$.fn.editable.defaults.mode = 'toggle';
    $('#searchUser').keyup(function(){
        var val = $(this).val().toLowerCase()
        $('#tableList tr.search').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        })
    })

    $('#cancelUpdate').click(function(){
        $('#showSetting').show()
        $('#editSetting').hide()
    })

    $('#saveShift').click(function(){
        var arr_check = []
        var day = []
        var clockIn = []
        var clockOut = []

        shift_name = $('#shift-name').val()

        branch = $('#branch_id').val()

        if(shift_name == '' || shift_name == null){
            alert('Shift Name cannot be empty!')
        }
        else if(branch == 0 || branch == '' || branch == null){
            alert('You have to set the branch')
        }
        else if(workdays == '' || workdays == null){
            alert('You have to choose the workdays options!')
        }
        else if(holiday == '' || holiday == null){
            alert('You have to choose the holiday options!')
        }
        else if(collective == '' || collective == null){
            alert('You have to choose the collective leave options!')
        }
        else{
            $('.check-child').each(function(i){
                if($(this).prop('checked') == true){
                    arr_check.push(i+1)
                }
            })

            for(var i = 0; i < arr_check.length; i++){
                day.push($('#day'+arr_check[i]).text())
                if($('#clock_in'+arr_check[i]).val()){
                    clockIn.push($('#clock_in'+arr_check[i]).val())
                }
                if($('#clock_out'+arr_check[i]).val()){
                    clockOut.push($('#clock_out'+arr_check[i]).val())
                }
            }

            if(arr_check.length == 0){
                alert('You have to check at least 1 day of the week')
            }
            else if(day.length != arr_check.length || clockIn.length != arr_check.length || clockOut.length != arr_check.length){
                alert('Make sure you have filled all of the fields you selected')
            }
            else{
                $.ajax({
                    url: "{{ route('administrator.attendance.shift-save') }}",
                    type: "POST",
                    data: {'_token': '{{csrf_token()}}', 'shift_name': shift_name, 'workdays': workdays, 'holiday': holiday, 'collective': collective, 'day': day, 'clock_in': clockIn, 'clock_out': clockOut, 'branch_id': branch},
                    dataType: "JSON",
                    success: function(data){
                        swal("Success!", data.message, "success");
                        $('#form-shift')[0].reset();
                        window.location.href = "{{ url('shift-setting') }}"+"?tab=list";
                    },
                })
            }
        }
    })

    $('#updateShift').click(function(){
        var arr_check = []
        var day = []
        var clockIn = []
        var clockOut = []

        shift_name = $('#shift-name-edit').val()

        branch = $('#branch_id_edit').val()

        if(shift_name == '' || shift_name == null){
            alert('Shift Name cannot be empty!')
        }
        else if(branch == 0 || branch == '' || branch == null){
            alert('You have to set the branch')
        }
        else if(workdays == '' || workdays == null){
            alert('You have to choose the workdays options!')
        }
        else if(holiday == '' || holiday == null){
            alert('You have to choose the holiday options!')
        }
        else if(collective == '' || collective == null){
            alert('You have to choose the collective leave options!')
        }
        else{
            $('.check-child-edit').each(function(i){
                if($(this).prop('checked') == true){
                    arr_check.push(i+1)
                }
            })

            for(var i = 0; i < arr_check.length; i++){
                day.push($('#day_edit'+arr_check[i]).text())
                if($('#clock_in_edit'+arr_check[i]).val()){
                    clockIn.push($('#clock_in_edit'+arr_check[i]).val())
                }
                if($('#clock_out_edit'+arr_check[i]).val()){
                    clockOut.push($('#clock_out_edit'+arr_check[i]).val())
                }
            }

            if(arr_check.length == 0){
                alert('You have to check at least 1 day of the week')
            }
            else if(day.length != arr_check.length || clockIn.length != arr_check.length || clockOut.length != arr_check.length){
                console.log(arr_check)
                console.log(day)
                console.log(clockIn)
                console.log(clockOut)
                alert('Make sure you have filled all of the fields you selected')
            }
            else{
                $.ajax({
                    url: "{{ route('administrator.attendance.shift-update') }}",
                    type: "POST",
                    data: {'_token': '{{csrf_token()}}', 'shift_name': shift_name, 'workdays': workdays, 'holiday': holiday, 'collective': collective, 'day': day, 'clock_in': clockIn, 'clock_out': clockOut, 'branch_id': branch, 'id': id_shift},
                    dataType: "JSON",
                    success: function(data){
                        swal("Success!", data.message, "success");
                        $('#form-shift-edit')[0].reset()
                        window.location.href = "{{ url('shift-setting') }}"+"?tab=list";
                    },
                })
            }
        }
    })

    $('#clock_in_default').focus(function(){
        if(workdays == '' || workdays == null){
            alert('You have to choose the workdays options first!')
            $('#workdays').focus();
            $('#clock_in_default').attr('disabled', true);
            $('#clock_out_default').attr('disabled', true);
        }
    })

    $('#clock_out_default').focus(function(){
        if(workdays == '' || workdays == null){
            alert('You have to choose the workdays options first!')
            $('#clock_in_default').attr('disabled', true);
            $('#clock_out_default').attr('disabled', true);
        }
    })

    $('#clock_in_default_edit').focus(function(){
        if(workdays == '' || workdays == null){
            alert('You have to choose the workdays options first!')
            $('#workdays_edit').focus();
            $('#clock_in_default_edit').attr('disabled', true);
            $('#clock_out_default_edit').attr('disabled', true);
        }
    })

    $('#clock_out_default_edit').focus(function(){
        if(workdays == '' || workdays == null){
            alert('You have to choose the workdays options first!')
            $('#clock_in_default_edit').attr('disabled', true);
            $('#clock_out_default_edit').attr('disabled', true);
        }
    })

    $('.clock-in').attr('disabled', true)
    $('.clock-out').attr('disabled', true)

    // $('.clock-in-edit').attr('disabled', true)
    // $('.clock-out-edit').attr('disabled', true)

    $('#check-top').click(function(){
        $('.check-child').prop('checked', $(this).prop('checked'))
    })

    $('#checkTopUser').click(function(){
        $('.checkUser').prop('checked', $(this).prop('checked'))
    })

    $('#check-top-edit').click(function(){
        $('.check-child-edit').prop('checked', $(this).prop('checked'))
    })

    $('.check-child').change(function(){
        if(!$(this).prop('checked')){
            $('#check-top').prop('checked', false)
        }
    })

    $('.check-child-edit').change(function(){
        if(!$(this).prop('checked')){
            $('#check-top-edit').prop('checked', false)
        }
    })

    $('.workdays').change(function(){
        if($(this).val() == 'dynamic'){
            workdays = 'dynamic'
            $('.clock-in').attr('disabled', false)
            $('.clock-out').attr('disabled', false)
            $('#clock_in_default').attr('disabled', true)
            $('#clock_out_default').attr('disabled', true)
            $('.work-hour').html('0')
            $('.clock-in').val('')
            $('.clock-out').val('')
            $('#clock_in_default').val('')
            $('#clock_out_default').val('')
        }
        else{
            workdays = 'fixed'
            $('.clock-in').attr('disabled', true)
            $('.clock-out').attr('disabled', true)
            $('#clock_in_default').attr('disabled', false)
            $('#clock_out_default').attr('disabled', false)
            $('.work-hour').html('0')
            $('.clock-in').val('')
            $('.clock-out').val('')
            $('#clock_in_default').val('')
            $('#clock_out_default').val('')
        }
    })

    $('.workdays_edit').change(function(){
        if($(this).val() == 'dynamic'){
            workdays = 'dynamic'
            $('.clock-in-edit').attr('disabled', false)
            $('.clock-out-edit').attr('disabled', false)
            $('#clock_in_default_edit').attr('disabled', true)
            $('#clock_out_default_edit').attr('disabled', true)
            $('.work-hour-edit').html('0')
            $('.clock-in-edit').val('')
            $('.clock-out-edit').val('')
            $('#clock_in_default_edit').val('')
            $('#clock_out_default_edit').val('')
        }
        else{
            workdays = 'fixed'
            $('.clock-in-edit').attr('disabled', true)
            $('.clock-out-edit').attr('disabled', true)
            $('#clock_in_default_edit').attr('disabled', false)
            $('#clock_out_default_edit').attr('disabled', false)
            $('.work-hour-edit').html('0')
            $('.clock-in-edit').val('')
            $('.clock-out-edit').val('')
            $('#clock_in_default_edit').val('')
            $('#clock_out_default_edit').val('')
        }
    })

    $('.public_holiday').change(function(){
        if($(this).val() == '1'){
            holiday = '1'
        }
        else{
            holiday = '0'
        }
    })

    $('.public_holiday_edit').change(function(){
        if($(this).val() == '1'){
            holiday = '1'
        }
        else{
            holiday = '0'
        }
    })

    $('.collective_leave').change(function(){
        if($(this).val() == '1'){
            collective = '1'
        }
        else{
            collective = '0'
        }
    })

    $('.collective_leave_edit').change(function(){
        if($(this).val() == '1'){
            collective = '1'
        }
        else{
            collective = '0'
        }
    })

    $('#clock_in_default').change(function(){
        $('.clock-in').val($(this).val())
        // var all = document.querySelectorAll("[class^=work-hour]")
        // for(var i = 0; i < all.length; i++){
        //     document.getElementById
        // }
        var a = moment.utc($('#clock_in_default').val(), "HH:mm")
        var b = moment.utc($('#clock_out_default').val(), "HH:mm")
        var d = moment.duration(b.diff(a))
        var s = moment.utc(+d).format('H:mm');
        $('.work-hour').html(s != 'Invalid date' ? s : '0')
    })

    $('#clock_out_default').change(function(){
        $('.clock-out').val($(this).val())
        var a = moment.utc($('#clock_in_default').val(), "HH:mm")
        var b = moment.utc($('#clock_out_default').val(), "HH:mm")
        var d = moment.duration(b.diff(a))
        var s = moment.utc(+d).format('H:mm');
        $('.work-hour').html(s != 'Invalid date' ? s : '0')
    })

    $('#clock_in_default_edit').change(function(){
        $('.clock-in-edit').val($(this).val())
        var a = moment.utc($('#clock_in_default_edit').val(), "HH:mm")
        var b = moment.utc($('#clock_out_default_edit').val(), "HH:mm")
        var d = moment.duration(b.diff(a))
        var s = moment.utc(+d).format('H:mm');
        $('.work-hour-edit').html(s != 'Invalid date' ? s : '0')
    })

    $('#clock_out_default_edit').change(function(){
        $('.clock-out-edit').val($(this).val())
        var a = moment.utc($('#clock_in_default_edit').val(), "HH:mm")
        var b = moment.utc($('#clock_out_default_edit').val(), "HH:mm")
        var d = moment.duration(b.diff(a))
        var s = moment.utc(+d).format('H:mm');
        $('.work-hour-edit').html(s != 'Invalid date' ? s : '0')
    })

    $('.edit_inline').editable(
    {
        url: '{{ route('ajax.post-edit-inline') }}',
        ajaxOptions:{
          type:'post'
        },
        params : {'table' : 'absensi_setting'},
        success: function(data) {
            console.log(data);
        }
      }
    );

    // Clock pickers
    $("input[name='clock_in'], input[name='clock_out']").clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
});

// ===============================================SCHEDULE=================================================== //

$(document).ready(function() {
    initScheduleTable();
    initDisplayScheduleTable();

    $.ajax({
        url: "{{ URL::to('shift-schedule/users') }}",
        type: "GET",
        success: function (data) {
            if (data.message == 'success') {
                users = data.data
            }
        }
    })

    $('.datepicker').datepicker("option", "minDate", moment().add('d', 1).toDate());
    $('.datepicker').datepicker("option", "dateFormat", "yy-mm-dd");
});

function initDisplayScheduleTable() {
    displayScheduleTable = $('.displayScheduleTable').DataTable( {
        ajax: "{{ URL::to('shift-schedule/display') }}",
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
            "targets": [0],
            "searchable": false,
            "orderable": false,
            "visible": true
        }],
        columns: [
            {
                data: 'id',
                title: 'No',
                width: 1,
                className: 'id'
            },
            {
                data: 'shift_schedule_change.change_date',
                title: 'Change Date'
            },
            {
                data: 'shift_schedule_change.shift.name',
                title: 'Shift Name'
            },
            {
                data: 'user.nik',
                title: 'User NIK'
            },
            {
                data: 'user.name',
                title: 'User Name'
            },
        ],
    });

    displayScheduleTable.on('order.dt search.dt', function () {
        displayScheduleTable.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();
}

function initScheduleTable() {
    scheduleTable = $('.scheduleTable').DataTable( {
        ajax: "{{ URL::to('shift-schedule') }}",
        order: [
            [1, "desc"]
        ],
        columnDefs: [{
            "targets": [0],
            "searchable": false,
            "orderable": false,
            "visible": true
        }],
        columns: [
            {
                data: 'id',
                title: 'No',
                width: 1,
                className: 'id'
            },
            {
                data: 'change_date',
                title: 'Change Date'
            },
            {
                data: 'shift.name',
                title: 'Shift Name'
            },
            {
                data: null,
                title: 'Action',
                render: function (data) {
                    return (new Date(data.change_date) > new Date() ? 
                        '<button id="edit" type="button" class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> Edit </button>'+
                        '<button id="delete" type="button" class="btn btn-danger btn-xs m-r-10"><i class="ti-trash"></i> Delete</button>'
                    : (!data.shift_schedule_change_employees.length ? '<button id="delete" type="button" class="btn btn-danger btn-xs m-r-10"><i class="ti-trash"></i> Delete</button>' : ''))+'<button id="assign" type="button" class="btn btn-primary btn-xs"><i class="ti-check"></i> Assign</button>'
                }
            },
        ],
    });

    scheduleTable.on('order.dt search.dt', function () {
        scheduleTable.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    $('.scheduleTable tbody').on('click', 'button', function () {
        var data = scheduleTable.row($(this).parents('tr')).data()
        if (this.id == 'delete')
            scheduleDelete(data)
        else if (this.id == 'edit')
            scheduleEdit(data)
        else
            scheduleAssign(data)
    })
}

$(document).on('hide.bs.modal', '#modal-add', function () {
    $('#modal-add #shift_id').html('')
    $('#modal-add #shift_id').append('<option value="" selected hidden> - Select Shift - </option>')

    @foreach($list as $item)
        $('#modal-add #shift_id').append('<option value="{{ $item->id }}">{{ $item->name }}</option>')
    @endforeach
    
    $('#modal-add .modal-title strong').html('Add New Shift Schedule Change')
    $('#modal-add #schedule_id').val('')
    $('#modal-add #shift_id').val('')
    $('#modal-add #change_date').val('')
})

$('#addSchedule').click(function () {
    $('#modal-add').modal('show');
})

function scheduleDelete(data) {
    swal({
        title: 'Are you sure?',
        text: "Once deleted, you will not be able to recover this data!",
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then((result) => {
        if (result) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'DELETE',
                url: "{{ URL::to('shift-schedule') }}/" + data.id,
                success: function(response){
                    scheduleTable.ajax.reload()
                    displayScheduleTable.ajax.reload()
                    swal(response.type, response.title, response.type)
                }
            })
        }
    })
}

function scheduleEdit(data) {
    $('#modal-add #shift_id').html('')
    $('#modal-add #shift_id').append('<option value="" selected hidden> - Select Shift - </option>')

    $.ajax({
        url: "{{ URL::to('shift-schedule/shifts') }}/"+data.shift.branch_id,
        type: "GET",
        success: function (response) {
            response.data.forEach(function(item, index) {
                $('#modal-add #shift_id').append('<option value="'+item.id+'" '+(item.id == data.shift_id ? 'selected' : '')+'>'+item.name+'</option>')
            });
        }
    })

    $('#modal-add .modal-title strong').html('Edit Shift Schedule Change')
    $('#modal-add #schedule_id').val(data.id)
    $('#modal-add #change_date').val(data.change_date)
    $('#modal-add').modal('show');
}

$("#scheduleForm").submit(function(e) {
    e.preventDefault();
    scheduleSubmit ();
});

function scheduleSubmit () {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: !$('#modal-add #schedule_id').val() ? 'POST' : 'PATCH',
        url: !$('#modal-add #schedule_id').val() ? "{{ URL::to('shift-schedule') }}" : "{{ URL::to('shift-schedule') }}/" + $('#modal-add #schedule_id').val(),
        data: {
            shift_id: $('#modal-add #shift_id').val(),
            change_date: $('#modal-add #change_date').val()
        },
        success: function(response){
            $('#modal-add').modal('hide');
            scheduleTable.ajax.reload()
            displayScheduleTable.ajax.reload()
            swal(response.type, response.title, response.type)
        }
    })
}

function scheduleAssign(data){
    $('#searchUser2').val('')
    if (users.length) {
        $('#SettingId').val(data.id)
        $('#tableList2').find('tr:gt(0)').remove()
        let checkAll = userExists(data.shift.branch_id, users, 'cabang_id')
        let j = 0
        for (let i = 0; i < users.length; i++) {
            if (data.shift.branch_id == users[i].cabang_id) {
                let num = ++j;
                let pos = users[i].position != null ? users[i].position : '-'
                let div = users[i].division != null ? users[i].division : '-'
                $('#tableList2 tr:last').after(
                    '<tr class="search">' +
                    '<td><input id="checkUser2' + num +
                    '" type="checkbox" class="checkUser2" ' + (userExists(users[i].id, data.shift_schedule_change_employees) ? 'checked' : '') + ' ' + (new Date(data.change_date) <= new Date() ? 'disabled' : '') + '></td>' +
                    '<td><input id="idUser2-' + num + '" type="hidden" value="' + users[i]
                    .id + '">' + num + '</td>' +
                    '<td>' + users[i].nik + '</td>' +
                    '<td>' + users[i].name + '</td>' +
                    '<td>' + pos + '</td>' +
                    '<td>' + div + '</td>' +
                    '</tr>'
                )
                if (!userExists(users[i].id, data.shift_schedule_change_employees))
                    checkAll = false
            }
        }
        $('#tableList2 tr:last').after(
            '<tr>' +
            '<td colspan="6"><button id="assignSetting" type="button" class="btn btn-primary btn-xs m-r-5" ' + (new Date(data.change_date) <= new Date() ? 'disabled' : '') + '><i class="ti-check"></i> Assign</button>' +
            '</tr>'
        )
        if (new Date(data.change_date) <= new Date()) {
            $('#checkTopUser2').attr('disabled', true);
        } else {
            $('#checkTopUser2').removeAttr('disabled');
        }
        $('#modal-assign2').modal('show')
        assignSetting()
        defaultCheckAll2(checkAll)
        $('.checkUser2').click(function () {
            defaultCheckAll2()
        })
    } else {
        $('#tableList2').find('tr:gt(0)').remove()
        $('#tableList2 tr:last').after(
            '<tr>' +
            '<td colspan="6">No data.</td>' +
            '</tr>'
        )
        $('#modal-assign2').modal('show')
    }
}

function userExists(id, arr, param = 'user_id') {
    return arr.some(function(el) {
        return el[param] == id;
    }); 
}

function assignSetting() {
    $('#assignSetting').click(function () {

        var arr_check2 = []
        var id_user2 = []
        var shift_id = $('#SettingId').val()

        $('.checkUser2').each(function (i) {
            if ($(this).prop('checked') == true)
                arr_check2.push(i + 1)
        })

        for (var i = 0; i < arr_check2.length; i++) {
            id_user2.push($('#idUser2-' + arr_check2[i]).val())
        }

        $.ajax({
            url: "{{ URL::to('shift-schedule/assign') }}/"+shift_id,
            type: "POST",
            data: {
                '_token': '{{csrf_token()}}',
                'user_id': id_user2,
            },
            dataType: "JSON",
            success: function (data) {
                scheduleTable.ajax.reload()
                displayScheduleTable.ajax.reload()
                swal({
                    title: "Success!",
                    text: data.message,
                    type: "success"
                }).then(function () {
                    $('#modal-assign2').modal('hide')
                });
            },
        })
    })
}

function defaultCheckAll2(checkAll = false) {
    if (($('.checkUser2:visible:checked').length == $('.checkUser2:visible').length && $('.checkUser2:visible')
            .length) || checkAll)
        $('#checkTopUser2').prop('checked', true)
    else
        $('#checkTopUser2').prop('checked', false)
}

$(function () {
    $('#searchUser2').keyup(function () {
        var val = $(this).val().toLowerCase()
        $('#tableList2 tr.search').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        })
        defaultCheckAll2()
    })

    $('#checkTopUser2').click(function () {
        $('.checkUser2:visible').prop('checked', $(this).prop('checked'))
    })
});

$("#btn_import").click(function(){
    if ($("input[type='file']").val() == "") {
        bootbox.alert('File can not be empty');
        return false;
    }
    $("#form-upload").submit();
    $("#form-upload").hide();
    $('.div-proses-upload').show();
});
</script>
@endsection
@endsection
