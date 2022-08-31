@extends('layouts.administrator')

@section('title', 'Karyawan')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Employee Form</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10 pull-right" onclick="submit()"><i class="fa fa-save"></i> Save Employee Data </button>
                <a href="{{ route('administrator.karyawan.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10 pull-right"><i class="fa fa-arrow-left"></i> Back </a>
            </div>
        </div>
    <div class="row">
        <form class="form-horizontal" id="form-karyawan" enctype="multipart/form-data" action="{{ route('administrator.karyawan.update', $data->id ) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="career_action" name="career_action" value="1">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    <ul class="nav nav-tabs" role="tablist" id="myTab">
                        <li role="presentation" class="{{ !$tab ? 'active' : '' }}"><a href="#biodata" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Personal Information</span></a></li>

                        <li role="presentation" class=""><a href="#dependent" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Dependent</span></a></li>

                        <li role="presentation" class=""><a href="#education" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Education</span></a></li>

                        <li role="presentation" class=""><a href="#certification" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Training</span></a></li>

                        <li role="presentation" class=""><a href="#department" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Branch and Position</span></a></li>

                        <li role="presentation" class=""><a href="#rekening_bank" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Bank Account</span></a></li>

                        <li role="presentation" class=""><a href="#inventaris" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Facilities</span></a></li>
                        
                        <li role="presentation" class=""><a href="#shift" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Shift</span></a></li>

                        {{--@if(isset($payroll->salary))--}}
                        {{--<li role="presentation" class=""><a href="#payroll" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Payroll</span></a></li>--}}
                        {{--@endif--}}

                        @if(checkModuleAdmin(4))
                        <li role="presentation" class=""><a href="#cuti" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Leave</span></a></li>
                        @endif

                        @if(checkModuleAdmin(15) || checkModuleAdmin(17))
                        <li role="presentation" class=""><a href="#attendance" aria-controls="attendance" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Attendance</span></a></li>
                        @endif

                        @if(checkModuleAdmin(28))
                        <li role="presentation" class=""><a href="#VisitAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Visit</span></a></li>
                        @endif

                        @if(checkModuleAdmin(7))
                        <li role="presentation" class=""><a href="#OvertimeAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Overtime</span></a></li>
                        @endif

                        @if(checkModuleAdmin(13))
                        <li role="presentation" class=""><a href="#PayrollAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Payroll</span></a></li>
                        @endif

                        @if(checkModuleAdmin(27))
                        <li role="presentation" class=""><a href="#RecruitmentAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Recruitment</span></a></li>
                        @endif

                        @if(checkModuleAdmin(34))
                        <li role="presentation" class="{{ $tab == 'contract' ? 'active' : '' }}"><a href="#contract" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Contract</span></a></li>
                        @endif
                        
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade" id="attendance">
                            <table id="tableAttendance" class="data_table_no_pagging table table-background">
                                <thead class="header" style="background: #f5f5f5;">
                                    <tr>
                                        <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">No</th>
                                        <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Date</th>
                                        <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Day</th>
                                        <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Shift</th>
                                        <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Shift</th>
                                        <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Clock</th>
                                        <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Branch</th>
                                        <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Late CLOCK In</th>
                                        <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Early CLOCK Out</th>
                                        <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Duration</th>
                                    </tr>
                                    <tr>
                                        <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">In</th>
                                        <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Out</th>
                                        <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">In</th>
                                        <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Out</th>
                                        <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">In</th>
                                        <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Out</th>
                                    </tr>
                                </thead>
                                <tbody class="no-padding-td">
                                    <?php $i = 1; ?>
                                    @foreach($dates as $no => $date)
                                    <tr>
                                        <td>{{$i}}</td>
                                        @if(date('l', strtotime($date)) == 'Sunday')
                                        <td class="tanggalAbsen" style="color:red;">{{$date}}</td>
                                        @else
                                        <td class="tanggalAbsen">{{$date}}</td>
                                        @endif
                                        <td id="hariAbsen{{$date}}" style="color: {{$shiftDay[$no] || !$shiftSchedule['shift'][$no] ? 'blue' : 'black'}}">{{date('l', strtotime($date))}}</td>
                                        <td id="shift{{$date}}">{{$shiftSchedule['shift'][$no] ?: 'No Shift'}}</td>
                                        <td id="shiftIn{{$date}}">{{$shiftSchedule['shift_in'][$no]}}</td>
                                        <td id="shiftOut{{$date}}">{{$shiftSchedule['shift_out'][$no]}}</td>
                                        <td id="clockIn{{$date}}"></td>
                                        <td id="clockOut{{$date}}"></td>
                                        <td id="branchIn{{$date}}"></td>
                                        <td id="branchOut{{$date}}"></td>
                                        <td id="lateIn{{$date}}"></td>
                                        <td id="earlyOut{{$date}}"></td>
                                        <td id="duration{{$date}}"></td>
                                    </tr>
                                    <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="shift">
                            <form class="form-control">
                                <div class="form-group">
                                    <label class="col-md-12">Current Shift</label>
                                    <div class="col-md-3">
                                        <select id="optShift" name="shift_id" class="form-control">
                                            <option value=""> - Select Shift - </option>
                                            @foreach(get_shift_attendance($data->cabang_id) as $item)
                                            <option value="{{ $item->id }}" {{ old('shift_id', $data->shift_id) == $item->id ? 'selected' : '' }} >{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <table id="tableShift" class="data_table_no_pagging table table-background">
                                <thead>
                                    <tr>
                                        <th rowspan="1">No</th>
                                        <th rowspan="1">Change Date</th>
                                        <th rowspan="1">Shift Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shiftScheduleChange as $no => $item)
                                    <tr>
                                        <td >{{ $no+1 }}</td>
                                        <td >{{ $item->change_date}}</td>
                                        <td >{{ $item->shift->name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{--@if(isset($payroll->salary))--}}
                        {{--<div role="tabpanel" class="tab-pane fade" id="payroll">--}}
                            {{--<h3 class="box-title m-b-0">Payroll</h3>--}}
                            {{--<hr />--}}
                            {{--<div class="clearfix"></div>--}}
                             {{--<form class="form-horizontal"method="POST">--}}

                                {{--<div class="col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Salary</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" name="salary" readonly="true" value="{{ number_format($payroll->salary) }}" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">JKK (Accident) + JK (Death)</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" name="jkk" readonly="true" value="{{ $payroll->jkk or "" }}" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Call Allowance</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" name="call_allow" readonly="true" value="@if($payroll) {{number_format($payroll->call_allow) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Yearly Bonus, THR or others     </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" name="bonus" readonly="true" value="@if($payroll) {{ number_format($payroll->bonus) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Gross Income Per Year </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="gross_income" value="@if($payroll) {{ number_format($payroll->gross_income) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Burden Allowance    </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="burden_allow" value="@if($payroll) {{ number_format($payroll->burden_allow) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Jamsostek Premium Paid by Employee (JHT dan pension) {{ !empty($payroll->jamsostek) ? $payroll->jamsostek .'%' : '' }}   </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="jamsostek_result" value="@if($payroll) {{ number_format($payroll->jamsostek_result) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Total Deduction ( 3 + 4 )</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="total_deduction" value="@if($payroll) {{ number_format($payroll->total_deduction) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">NET Yearly Income  ( 2 - 5 )    </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="net_yearly_income" value="@if($payroll) {{ number_format($payroll->net_yearly_income) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Untaxable Income </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="untaxable_income" value="@if($payroll) {{ number_format($payroll->untaxable_income) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Taxable Yearly Income  ( 6 - 7)</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="taxable_yearly_income" value="@if($payroll) {{ number_format($payroll->taxable_yearly_income) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">5%    ( 0-50 million)</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="income_tax_calculation_5" value="@if($payroll) {{ number_format($payroll->income_tax_calculation_5) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">15%  ( 50 - 250 million)</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="income_tax_calculation_15" value="@if($payroll) {{ number_format($payroll->income_tax_calculation_15) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">25%  ( 250-500 million)</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="income_tax_calculation_25" value="@if($payroll) {{ number_format($payroll->income_tax_calculation_25) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">30%  ( > 500 million)</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="income_tax_calculation_30" value="@if($payroll) {{ number_format($payroll->income_tax_calculation_30) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Yearly Income Tax</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="yearly_income_tax" value="@if($payroll) {{ number_format($payroll->yearly_income_tax) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Monthly Income Tax  </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="monthly_income_tax" value="@if($payroll) {{ number_format($payroll->monthly_income_tax) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Basic Salary </label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="basic_salary" value="@if($payroll) {{ number_format($payroll->basic_salary) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Less : Tax, Pension & Jamsostek (Monthly)</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="less" value="@if($payroll) {{ number_format($payroll->less) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label class="col-md-3">Take Home Pay</label>--}}
                                        {{--<div class="col-md-6">--}}
                                           {{--<input type="text" readonly="true" name="thp" value="@if($payroll) {{ number_format($payroll->thp) }} @else 0 @endif" class="form-control">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                            {{--<div class="clearfix"></div>--}}
                        {{--</div>--}}
                        {{--@endif--}}
                        <div role="tabpanel" class="tab-pane fade" id="cuti">
                            <h3 class="box-title m-b-0">Leave</h3>
                            <a class="btn btn-info btn-xs" id="add_cuti"><i class="fa fa-plus"></i> Add</a>
                            <div class="clearfix"></div>
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Leave / Permit Type</th>
                                            <th>Quota</th>
                                            <th>Leave Taken</th>
                                            <th>Leave Balance</th>
                                            <td>#</td>
                                        </tr>
                                    </thead>
                                    <tbody class="table_cuti">
                                        @foreach($data->cuti as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ isset($item->cuti->description) ? $item->cuti->description : '' }}</td>
                                            <td>{{ $item->kuota ?: 0 }}</td>
                                            @if(!isset($item->cuti_terpakai))
                                            <td>0</td>
                                            @else
                                            <td>{{ $item->cuti_terpakai }}</td>
                                            @endif

                                            @if(!isset($item->sisa_cuti ))
                                            <td>{{ $item->kuota ?: 0 }}</td>
                                            @else
                                            <td>{{ $item->sisa_cuti  }}</td>
                                            @endif
                                          

                                            <td>
                                                <a onclick="edit_cuti({{ $item->id }}, {{ $item->cuti_id }}, {{ empty($item->kuota) ? 0 : $item->kuota }}, {{ empty($item->cuti_terpakai) ? 0 : $item->cuti_terpakai }}, '{{ $item->cuti->jenis_cuti }}')" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> </a>
                                                <a onclick="return confirm('This leave data already saved, are you sure to delete?')" href="{{ route('administrator.karyawan.delete-cuti', $item->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </a>
                                                <input type="hidden" class="cuti-id" value="{{ $item->cuti_id }}" />
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <br />
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="inventaris">
                            <table class="table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="70" class="text-center">NO</th>
                                        <th>ASSET NUMBER</th>
                                        <th>ASSET NAME</th>
                                        <th>ASSET TYPE</th>
                                        <th>SERIAL/PLAT NUMBER</th>
                                        <th>PURCHASE/RENTAL DATE</th>
                                        <th>ASSET CONDITION</th>
                                        <th>STATUS ASSET</th>
                                        <th>PIC</th>
                                        <th>HANDOVER DATE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data->assets as $no => $item)
                                     @if(!isset($item->asset_type->name))
                                        <?php  ?>
                                     @endif
                                        <tr>
                                            <td class="text-center">{{ $no+1 }}</td>   
                                            <td>{{ $item->asset_number }}</td>
                                            <td>{{ $item->asset_name }}</td>
                                            <td>{{ isset($item->asset_type->name) ? $item->asset_type->name : ''  }}</td>
                                            <td>{{ $item->asset_sn }}</td>
                                            <td>{{ format_tanggal($item->purchase_date) }}</td>
                                            <td>{{ $item->asset_condition }}</td>
                                            <td>{{ $item->assign_to }}</td>
                                            <td>{{ isset($item->user->name) ? $item->user->name : '' }}</td>
                                            <td>{{ $item->handover_date != "" ?  format_tanggal($item->handover_date) : '' }}</td>
                                            <td>
                                                @if($item->handover_date === NULL)
                                                    <span class="badge badge-warning">Waiting Acceptance</span>
                                                @endif

                                                @if($item->handover_date !== NULL && $item->status==1)
                                                    <span class="badge badge-success">Accepted</span>
                                                @endif

                                                @if($item->handover_date !== NULL && $item->status==2)
                                                    <label class="badge badge-info">Waiting Returned</label>
                                                @endif

                                                @if($item->handover_date !== NULL && $item->status==3)
                                                    <label class="badge badge-danger">Rejected</label>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br />
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="rekening_bank">
                            <div class="form-group">
                                <label class="col-md-12">Name of Account</label>
                                <div class="col-md-6">
                                    <input type="text" name="nama_rekening" class="form-control" value="{{ old('nama_rekening', $data->nama_rekening) }}"  />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Account Number</label>
                                <div class="col-md-6">
                                   <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening', $data->nomor_rekening) }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name of Bank</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="bank_id">
                                        <option value="">Choose Bank</option>
                                        @foreach(get_bank() as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('bank_id', $data->bank_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="department">
                        @if(get_setting('struktur_organisasi') == 3)
                            <div class="form-group">
                                <label class="col-md-12">Branch <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select class="form-control" name="branch_id" id="branch_id" {{ ($data->non_active_date && \Carbon\Carbon::now() >= $data->non_active_date) || $data->is_exit ? 'disabled' : '' }}>
                                    <option value=""> - choose - </option>
                                    @foreach(cabang() as $item)
                                    <option value="{{ $item["id"] }}" {{ $item["id"] == old('branch_id', $data->cabang_id) ? 'selected' : '' }}>{{ $item["name"] }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Position <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select class="form-control" name="structure_organization_custom_id" {{ ($data->non_active_date && \Carbon\Carbon::now() >= $data->non_active_date) || $data->is_exit ? 'disabled' : '' }}>
                                    <option value=""> - choose - </option>
                                    @foreach($structure as $item)
                                    <option value="{{ $item["id"] }}" {{ $item["id"] == old('structure_organization_custom_id', $data->structure_organization_custom_id) ? 'selected' : '' }}>{{ $item["name"] }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Project</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="custom_project_id">
                                    <option value=""> - choose - </option>
                                    @foreach($project as $item)
                                    <option value="{{ $item["id"] }}" {{ $item["id"] == old('custom_project_id', $data->custom_project_id) ? 'selected' : '' }}>{{ $item["name"] }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        @else
                            <div class="form-group">
                                <label class="col-md-12">Office Type</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="branch_type">
                                        <option value=""> - none - </option>
                                        @foreach(['HO', 'BRANCH'] as $item)
                                        <option {{ strtoupper(old('branch_type', $data->branch_type)) == $item ? ' selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group section-cabang" style="{{ old('branch_type', $data->branch_type) == "HO" ? 'display:none' : ''  }}">
                                <label class="col-md-3">Branch</label>
                                <div class="clearfix"></div>
                                <div class="col-md-3">
                                    <select class="form-control" name="cabang_id">
                                        <option value="">Choose Branch</option>
                                        @foreach(get_cabang() as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('cabang_id', $data->cabang_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="clearfix" /></div>
                                <br class="clearfix" />
                                <br>
                                <div class="col-md-12">
                                    <label><input type="checkbox" name="is_pic_cabang" value="1" {{ old('is_pic_cabang', $data->is_pic_cabang) == 1 ? 'checked' : '' }}> Branch PIC</label>
                                </div>
                                <div class="clearfix"></div>
                                <hr />
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Director</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="empore_organisasi_direktur">
                                        <option value=""> Choose </option>
                                        @foreach(empore_list_direktur() as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('empore_organisasi_direktur', $data->empore_organisasi_direktur) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Manager</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="empore_organisasi_manager_id">
                                        <option value=""> Choose </option>
                                        @foreach($list_manager as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('empore_organisasi_manager_id', $data->empore_organisasi_manager_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Staff</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="empore_organisasi_staff_id">
                                        <option value=""> Choose </option>
                                        @foreach($list_staff as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == old('empore_organisasi_staff_id', $data->empore_organisasi_staff_id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        </div>
                        <!-- Tab Visit  -->
                        <div role="tabpanel" class="tab-pane fade" id="VisitAssign" >
                            <div class="form-group">
                                <label class="col-md-12">Visit Type</label>
                                <div class="col-md-6">
                                    <select class="form-control " id="DivVisitType" name="master_visit_type_id">
                                        <option value=""> - VisitType - </option>
                                        @foreach($VisitTypeList as $item)
                                        <option value="{{ $item["id"] }}" {{ $item["id"]== old('master_visit_type_id', $data->master_visit_type_id) ? 'selected' : '' }}>{{ $item["master_visit_type_name"] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="DivBranch" name="DivBranch" style="display: none">
                                <label class="col-md-12">Branch</label>
                                <div class="col-md-6">
                                    @foreach(cabangvisit() as $item)
                                    <input type="checkbox" name="userbranchs[]" value="{{ $item["id"] }}" {{in_array( $item->id, $branchsuser)? 'checked' : '' }}> {{ $item["name"] }}</input>
                                    <br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Visit Activity Category Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="master_category_visit_id">
                                        <option value=""> - choose Visit Activity Category Name - </option>
                                        @foreach($CategoryVisitList as $item)
                                        <option value="{{ $item["id"] }}" {{ $item["id"]== old('master_category_visit_id', $data->master_category_visit_id) ? 'selected' : '' }}>{{ $item["master_category_name"] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" id="idUser" value="{{$data->id}}">
                            <table id="tableVisit" class="data_table_no_pagging table table-background">
                                <thead>
                                    <tr>
                                        <th rowspan="1">No</th>
                                        <th rowspan="1">NIK</th>
                                        <th rowspan="1">Name</th>
                                        <th rowspan="1">Visit Type</th>
                                        <th rowspan="1">Visit Category</th>
                                        <th rowspan="1">Date</th>
                                        <th rowspan="1">Day</th>
                                        <th rowspan="1">Timezone</th>
                                        <th rowspan="1">Branch Name / Place Name</th>
                                        <th rowspan="1">Location Name</th>
                                        <th rowspan="1">Activity Name</th>
                                        <th rowspan="1">PIC Name</th>
                                        <th rowspan="1">Visit Point</th>
                                </thead>
                                <tbody>
                                    @foreach($visitlist as $no => $item)
                                    <tr>
                                        <td >{{ $no+1 }}</td>
                                        <td >{{ $item->nik}}</td>
                                        <td >{{ $item->username}}</td>
                                        <td >{{ $item->master_visit_type_name}}</td>
                                        <td >{{ $item->master_category_name}}</td>
                                        
                                        <td class="tanggalVisit" id="tanggal{{$no+1}}">
                                            @if(!empty($item->longitude) || !empty($item->latitude) || !empty($item->pic))
                                            <a href="javascript:void(0)" data-title="Visit Detail <?=$item->username?> <?=date('d F Y h:i:s A', strtotime($item->visit_time))?>" data-longitude="<?=$item->longitude?>" data-signature="/<?=$item->signature?>" data-description="<?=$item->description?>" data-visittype="<?= $item->master_visit_type_id ?>" data-isoutbranch="<?= $item->isoutbranch ?>" data-visitid="<?=$item->id?>" data-latitude="<?=$item->latitude?>" data-picname="<?=$item->picname?>" data-time="<?=$item->visit_time?>" data-long-branch="<?=$item->branchlongitude?>" data-lat-branch="<?=$item->branchlatitude?>" data-radius-branch="<?=$item->radius_visit?>" data-activity-name="<?=$item->activityname?>" data-justification="{{$item->justification}}" data-placename="{{$item->placename}}" data-cabang="{{$item->cabangDetail?$item->cabangDetail->name:""}}"  data-location="{{$item->locationname}}" onclick="detail_visit(this)" title="Mobile Visit"> {{ $item->visit_time }}</a>
                                            <i title="Mobile Visit" class="fa fa-location-arrow right" style="font-size: 20px;"></i>
                                            @else
                                            {{ $item->visit_time }}
                                            @endif
                                        </td>
                                        
                                        @if($item->timetable == 'Sunday')
                                        <td class="hariAbsen" id="hari{{$no+1}}" style="color:red;">{{ $item->timetable }}</td>
                                        @else
                                        <td class="hariAbsen" id="hari{{$no+1}}">{{ $item->timetable }}</td>
                                        @endif
                                        <td >{{ $item->timezone}}</td>
                                        @if($item->master_visit_type_name == 'Unlock' || ( $item->master_visit_type_name == 'Lock' && $item->isoutbranch == 1 ))
                                        <td>{{ $item->placename}}</td>
                                        @else
                                        <td>{{ $item->cabang_name}}</td>
                                        @endif
                                        <td >{{ $item->locationname}}</td>
                                        <td >{{ $item->activityname}}</td>
                                        <td >{{ $item->picname }}</td>
                                        <td >{{ $item->point }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- END Tab Visit  -->        
                        <!-- Tab Overtime  -->
                        <div role="tabpanel" class="tab-pane fade" id="OvertimeAssign" >
                            <div class="form-group">
                                <label class="col-md-12">Overtime Entitlement</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="overtime_entitle">
                                        <option value="1" {{ old('overtime_entitle', $data->overtime_entitle) ? 'selected' : '' }}>Entitle Overtime</option>
                                        <option value="" {{ !old('overtime_entitle', $data->overtime_entitle) ? 'selected' : '' }}>Not Entitle Overtime</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Overtime Payment Setting</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="overtime_payroll_id">
                                        <option value="" hidden selected> - Select setting - </option>
                                        @foreach($OvertimePayroll as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('overtime_payroll_id', $data->overtime_payroll_id) ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END Tab Overtime  -->
                        <!-- Tab Payroll  -->
                        <div role="tabpanel" class="tab-pane fade" id="PayrollAssign" >
                            <div class="form-group">
                                <label class="col-md-12">UMR Setting</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="payroll_umr_id">
                                        <option value=""> - Select setting - </option>
                                        @foreach($PayrollUMR as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('payroll_umr_id', $data->payroll_umr_id) ? 'selected' : '' }}>{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">PTKP</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ get_status_ptkp($data->id) }}" disabled> 
                                </div>
                                <div class="col-md-4" style="color: red" id="cycle_status">
                                    *Will be updated every January 1
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Payroll Cycle</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="payroll_cycle_id">
                                        <option value=""> - Select setting - </option>
                                        @foreach($PayrollCycle as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('payroll_cycle_id', $data->payroll_cycle_id) ? 'selected' : '' }}>{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Attendance Cycle</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="attendance_cycle_id">
                                        <option value=""> - Select setting - </option>
                                        @foreach($AttendanceCycle as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('attendance_cycle_id', $data->attendance_cycle_id) ? 'selected' : '' }}>{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END Tab Payroll  -->
                        <!-- Tab Recruitment  -->
                        <div role="tabpanel" class="tab-pane fade" id="RecruitmentAssign" >
                            <div class="form-group">
                                <label class="col-md-12">Recruitment Entitlement</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="recruitment_entitle">
                                        <option value="1" {{ old('recruitment_entitle', $data->recruitment_entitle) ? 'selected' : '' }}>Entitle Recruitment</option>
                                        <option value="" {{ !old('recruitment_entitle', $data->recruitment_entitle) ? 'selected' : '' }}>Not Entitle Recruitment</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END Tab Recruitment  -->
                        <div role="tabpanel" class="tab-pane fade {{ !$tab ? 'active in' : '' }}" id="biodata">
                            {{ csrf_field() }}
                            <div class="col-md-6" style="padding-left: 0">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        @if(!empty($data->foto))
                                        <img src="{{ asset('storage/foto/'. $data->foto) }}" style="width: 200px;" />
                                        @elseif($data->jenis_kelamin=='Female')
                                        <img src="{{ asset('images/Birthday_Female_Icon.png') }}" id="result_change_photo" style="width: 150px;" />
                                        @elseif($data->jenis_kelamin=='Male')
                                        <img src="{{ asset('images/Birthday_Male_Icon.png') }}" id="result_change_photo" style="width: 150px;" />
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-info btn-xs" onclick="open_dialog_photo()"><i class="fa fa-upload"></i> Change Photo</button>
                                        <input type="file" id="change_photo" name="foto" class="form-control" style="display: none;"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Name <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="name" style="text-transform: uppercase" class="form-control " value="{{ old('name', $data->name) }}"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Employee Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="employee_number" class="form-control " value="{{ old('employee_number', $data->employee_number) }}"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Attendance Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="absensi_number" class="form-control " value="{{ old('absensi_number', $data->absensi_number) }}"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">NIK <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="nik" value="{{ old('nik', $data->nik) }}" class="form-control"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Ext</label>
                                    <div class="col-md-10">
                                        <input type="text" name="ext" value="{{ old('ext', $data->ext) }}" class="form-control"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Place of Birth</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $data->tempat_lahir) }}" class="form-control"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Date of Birth(yyyy-mm-dd) <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir', $data->tanggal_lahir) }}" class="form-control datepicker2" required> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Marital Status <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control " name="marital_status">
                                            <option value="">- Marital Status -</option>
                                            <option value="Bujangan/Wanita" {{ old('marital_status', $data->marital_status) == "Bujangan/Wanita" ? 'selected' : '' }}>Single</option>
                                            <option value="Menikah" {{ old('marital_status', $data->marital_status) == "Menikah" ? 'selected' : '' }}>Married</option>
                                            <option value="Menikah Anak 1" {{ old('marital_status', $data->marital_status) == "Menikah Anak 1" ? 'selected' : '' }}>Married with 1 Child</option>
                                            <option value="Menikah Anak 2" {{ old('marital_status', $data->marital_status) == "Menikah Anak 2" ? 'selected' : '' }}>Married with 2 Child</option>
                                            <option value="Menikah Anak 3" {{ old('marital_status', $data->marital_status) == "Menikah Anak 3" ? 'selected' : '' }}>Married with 3 Child</option>
                                        </select>
                                   
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Gender <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control " name="jenis_kelamin">
                                            <option value=""> - Gender - </option>
                                            @foreach(['Male', 'Female'] as $item)
                                                <option {{ old('jenis_kelamin', $data->jenis_kelamin) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Blood Type</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control  " value="{{ old('blood_type', $data->blood_type) }}" name="blood_type">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" value="{{ old('email', $data->email) }}" class="form-control " name="email" id="example-email"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Password</label>
                                    <div class="col-md-10">
                                        <input type="password" autocomplete="new-password" name="password" class="form-control " value="{{ old('password', $data->password) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Confirm Password</label>
                                    <div class="col-md-10">
                                        <input type="password" autocomplete="new-password" name="confirm" class="form-control " value="{{ old('confirm', $data->password) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Join Date(yyyy-mm-dd) <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" id="join_date" name="join_date" class="form-control  datepicker2" value="{{ old('join_date', (($data->join_date == '0000-00-00' || $data->join_date == null) ? '' : date('Y-m-d', strtotime($data->join_date)))) }}">
                                    </div>
                                </div>
                                <div id="resign_container" class="form-group {{ old('organisasi_status', $data->organisasi_status) && old('organisasi_status', $data->organisasi_status) != 'Permanent' ? 'hidden' : '' }}">
                                    <label class="col-md-12">Resign Date(yyyy-mm-dd) <input type="checkbox" name="status" id="check_status" class="" value="2" {{old('status', $data->status) == 2?'checked':''}} {{ ($data->resign_date && \Carbon\Carbon::now() >= $data->resign_date) || $data->is_exit ? 'disabled' : '' }}></label>
                                    <div class="col-md-10">
                                        <input type="text" name="resign_date" placeholder="Resign Date" class="form-control datepicker2 {{old('status', $data->status) == 2?'':'hidden'}}" value="{{ old('resign_date', (($data->resign_date == '0000-00-00' || $data->resign_date == null) ? '' : date('Y-m-d', strtotime($data->resign_date)))) }}" {{ ($data->resign_date && \Carbon\Carbon::now() >= $data->resign_date) || $data->is_exit ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Last Work/Login Date(yyyy-mm-dd)</label>
                                    <div class="col-md-10">
                                        <input type="text" name="inactive_date" class="form-control  datepicker2" value="{{ old('inactive_date', ($data->inactive_date == '0000-00-00' || $data->inactive_date == null ? '' : date('Y-m-d', strtotime($data->inactive_date)))) }}" {{ ($data->inactive_date && \Carbon\Carbon::now() >= $data->inactive_date) || $data->is_exit ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Employee Status <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="organisasi_status" id="organisasi_status" {{ ($data->non_active_date && \Carbon\Carbon::now() >= $data->non_active_date) || $data->is_exit ? 'disabled' : '' }}>
                                            <option value="">- Select - </option>
                                            @foreach(['Permanent', 'Contract', 'Internship', 'Outsource', 'Freelance', 'Consultant'] as $item)
                                            <option {{ old('organisasi_status', $data->organisasi_status) == $item ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="contract_container" class="form-group row {{ old('organisasi_status', $data->organisasi_status) && old('organisasi_status', $data->organisasi_status) != 'Permanent' ? '' : 'hidden' }}">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status Contract</label>
                                        <select class="form-control" name="status_contract" {{ ($data->end_date_contract && \Carbon\Carbon::now() >= $data->end_date_contract) || $data->is_exit ? 'disabled' : '' }}>
                                            <option value="">- Select - </option>
                                            @foreach(['Sent', 'Returned'] as $item)
                                                <option {{ old('status_contract', $data->status_contract) == $item ? 'selected' : '' }} value="{{$item}}">Contract {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Start Date(yyyy-mm-dd)</label>
                                        <input type="text" name="start_date_contract" class="form-control  datepicker2" value="{{ old('start_date_contract', (($data->start_date_contract == '0000-00-00' || $data->start_date_contract == null) ? '' : date('Y-m-d', strtotime($data->start_date_contract)))) }}" {{ ($data->end_date_contract && \Carbon\Carbon::now() >= $data->end_date_contract) || $data->is_exit ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-md-3">
                                        <label>End Date(yyyy-mm-dd)</label>
                                        <input type="text" name="end_date_contract" class="form-control  datepicker2" value="{{ old('end_date_contract', (($data->end_date_contract == '0000-00-00' || $data->end_date_contract == null) ? '' : date('Y-m-d', strtotime($data->end_date_contract)))) }}" {{ ($data->end_date_contract && \Carbon\Carbon::now() >= $data->end_date_contract) || $data->is_exit ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Foreigners <input type="checkbox" name="foreigners_status" id="foreigners_status" class="" value="1" {{ old('foreigners_status', $data->foreigners_status) == 1 ? 'checked' : '' }}></label>
                                    <div class="col-md-10">
                                        <select class="form-control {{ old('foreigners_status', $data->foreigners_status) == 1 ? '' : 'hidden' }}" name="payroll_country_id">
                                            <option value="">- Select Country - </option>
                                            @foreach($payrollCountry as $item)
                                                <option {{ old('payroll_country_id', $data->payroll_country_id) == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding-left: 0">
                                <div class="form-group">
                                    <label class="col-md-12">NPWP Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="npwp_number" class="form-control "  value="{{ old('npwp_number', $data->npwp_number) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">BPJS Employment Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="bpjs_number" value="{{ old('bpjs_number', $data->bpjs_number) }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">BPJS Health Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="jamsostek_number" value="{{ old('jamsostek_number', $data->jamsostek_number) }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">ID Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="ktp_number" value="{{ old('ktp_number', $data->ktp_number) }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Passport Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="passport_number" value="{{ old('passport_number', $data->passport_number) }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">KK Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kk_number" class="form-control " value="{{ old('kk_number', $data->kk_number) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Telephone</label>
                                    <div class="col-md-12">
                                        <input type="number" value="{{ old('telepon', $data->telepon) }}" name="telepon" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Mobile 1</label>
                                    <div class="col-md-12">
                                        <input type="number" value="{{ old('mobile_1', $data->mobile_1) }}" name="mobile_1" class="form-control  "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Mobile 2</label>
                                    <div class="col-md-12">
                                        <input type="number" value="{{ old('mobile_2', $data->mobile_2) }}" name="mobile_2" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Emergency Contact Name</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('emergency_name', $data->emergency_name) }}" name="emergency_name" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Emergency Contact Relationship</label>
                                    <div class="col-md-12">
                                        <input type="text" value="{{ old('emergency_relationship', $data->emergency_relationship) }}" name="emergency_relationship" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Emergency Contact Number</label>
                                    <div class="col-md-12">
                                        <input type="number" value="{{ old('emergency_contact', $data->emergency_contact) }}" name="emergency_contact" class="form-control "> </div>
                                </div>
                               <div class="form-group">
                                    <label class="col-md-12">Religion</label>
                                    <div class="col-md-12">
                                        <select class="form-control " name="agama">
                                            <option value=""> - Religion - </option>
                                            @foreach(agama() as $item)
                                                <option value="{{ $item }}" {{ old('agama', $data->agama) == $item ? 'selected' : '' }}> {{ $item }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Current Address</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control " name="current_address">{{ old('current_address', $data->current_address) }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">ID Addres</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control " name="id_address">{{ old('id_address', $data->id_address) }}</textarea>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-md-12">Foto</label>
                                    <div class="col-md-12">
                                        <input type="file" name="foto" class="form-control " />
                                        @if(!empty(\Auth::user()->foto))
                                        <img src="{{ asset('storage/foto/'. $data->foto) }}" style="width: 200px;" />
                                        @endif
                                    </div>
                                </div> -->
                                <div class="form-group">
                                    <label class="col-md-12">ID Picture</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_ktp" name="foto_ktp" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_ktp()" class="btn btn-default preview_ktp" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                            @if(!empty($data->foto_ktp))
                                                <a onclick="show_ktp('{{ $data->foto_ktp }}')" class="btn btn-default btn-xs show_ktp" style="height: 35px;width: 100px"><i class="fa fa-search-plus"></i>View</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Family Card</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_kk" name="foto_kk" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_kk()" class="btn btn-default preview_kk" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                            @if(!empty($data->foto_kk))
                                                <a onclick="show_kk('{{ $data->foto_kk }}')" class="btn btn-default btn-xs show_kk" style="height: 35px;width: 100px"><i class="fa fa-search-plus"></i>View</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Driver's license</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_sim" name="foto_sim" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_sim()" class="btn btn-default preview_sim" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                            @if(!empty($data->foto_sim))
                                                <a onclick="show_sim('{{ $data->foto_sim }}')" class="btn btn-default btn-xs show_sim" style="height: 35px;width: 100px"><i class="fa fa-search-plus"></i>View</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Curriculum Vitae</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_cv" name="foto_cv" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_cv()" class="btn btn-default preview_cv" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                            @if(!empty($data->foto_cv))
                                                <a onclick="show_cv('{{ $data->foto_cv }}')" class="btn btn-default btn-xs show_cv" style="height: 35px;width: 100px"><i class="fa fa-search-plus"></i>View</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="dependent">
                            <h3 class="box-title m-b-0">Dependent</h3><a class="btn btn-info btn-sm" id="btn_modal_dependent"><i class="fa fa-plus"></i> Add</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Relationship</th>
                                            <th>Contact Number</th>
                                            <th>Place of birth</th>
                                            <th>Date of birth</th>
                                            <th>Date of death</th>
                                            <th>Education level</th>
                                            <th>Occupation</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="dependent_table">
                                        @foreach($data->userFamily as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->hubungan }}</td>
                                            <td>{{ $item->contact }}</td>
                                            <td>{{ $item->tempat_lahir }}</td>
                                            <td>{{ $item->tanggal_lahir }}</td>
                                            <td>{{ $item->tanggal_meninggal }}</td>
                                            <td>{{ $item->jenjang_pendidikan }}</td>
                                            <td>{{ $item->pekerjaan }}</td>
                                            <td>
                                                <a href="javascript:;" onclick="edit_dependent({{ $item->id }}, '{{ $item->nama }}', '{{ $item->hubungan }}', '{{ $item->contact }}', '{{ $item->tempat_lahir }}', '{{ $item->tanggal_lahir }}', '{{ $item->tanggal_meninggal }}', '{{ $item->jenjang_pendidikan }}', '{{ $item->pekerjaan }}', '{{ $item->tertanggung }}')" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> </a>
                                                <a href="{{ route('administrator.karyawan.delete-dependent', $item->id) }}" onclick="return confirm('Delete this data?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="education">
                            <h3 class="box-title m-b-0">Education</h3><a class="btn btn-info btn-sm" id="btn_modal_education"><i class="fa fa-plus"></i> Add</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Education</th>
                                            <th>Year of Start</th>
                                            <th>Year of Graduate</th>
                                            <th>School Name</th>
                                            <th>Major</th>
                                            <th>Grade</th>
                                            <th>City</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="education_table">
                                        @foreach($data->userEducation as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->pendidikan }}</td>
                                            <td>{{ $item->tahun_awal }}</td>
                                            <td>{{ $item->tahun_akhir }}</td>
                                            <td>{{ $item->fakultas }}</td>
                                            <td>{{ $item->jurusan }}</td>
                                            <td>{{ $item->nilai }}</td>
                                            <td>{{ $item->kota }}</td>
                                            <td>
                                                <a class="btn btn-default btn-xs" onclick="edit_education({{ $item->id }}, '{{ $item->pendidikan }}', '{{ $item->tahun_awal }}', '{{ $item->tahun_akhir }}', '{{ $item->fakultas }}', '{{ $item->jurusan }}', '{{ $item->nilai }}', '{{ $item->kota }}')"><i class="fa fa-edit"></i></a>
                                                <a href="{{ route('administrator.karyawan.delete-education', $item->id) }}" onclick="return confirm('Delete this data?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table><br /><br />
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="certification">
                            <h3 class="box-title m-b-0">Training</h3> <a class="btn btn-info btn-sm" id="btn_modal_certification"><i class="fa fa-plus"></i> Add</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Organizer</th>
                                            <th>Certificate Number</th>
                                            <th>Score</th>
                                            <th>Description</th>
                                            <th>Certificate</th>
                                        </tr>
                                    </thead>
                                    <tbody class="certification_table">
                                        @foreach($data->userCertification as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->organizer }}</td>
                                            <td>{{ $item->certificate_number }}</td>
                                            <td>{{ $item->score }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                @if(!empty($item->certificate_photo))
                                                    <a onclick="show_certificate('{{ $item->certificate_photo }}')" class="btn btn-default btn-xs" style="height: 35px;width: 100px"><i class="fa fa-search-plus"></i>View</a>
                                                @else 
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-default btn-xs" onclick="edit_certification({{ $item->id }}, '{{ $item->name }}', '{{ $item->date }}', '{{ $item->organizer }}', '{{ $item->certificate_number }}', '{{ $item->score }}', '{{ $item->description }}')"><i class="fa fa-edit"></i></a>
                                                <a href="{{ route('administrator.karyawan.delete-certification', $item->id) }}" onclick="return confirm('Delete this data?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table><br /><br />
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade  {{ $tab == 'contract' ? 'active in' : '' }}" id="contract">
                            <h3 class="box-title m-b-0">Contract</h3> <a class="btn btn-info btn-sm" id="btn_modal_contract"><i class="fa fa-plus"></i> Add</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Contract Number</th>
                                            <th>Contract Type</th>
                                            <th>Contract Start Date</th>
                                            <th>Contract End Date</th>
                                            <th>Contract Sent</th>
                                            <th>Contract Return</th>
                                            <th>Contract File</th>
                                        </tr>
                                    </thead>
                                    <tbody class="contract_table">
                                        @foreach($data->userContract as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->number }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->start_date != null && $item->start_date!= '0000-00-00' ? $item->start_date : '' }}</td>
                                            <td>{{ $item->end_date != null && $item->end_date!= '0000-00-00' ? $item->end_date : ''}}</td>
                                            <td>{{ $item->contract_sent != null && $item->contract_sent!= '0000-00-00' ? $item->contract_sent : '' }}</td>
                                            <td>{{ $item->return_contract!= null && $item->return_contract!= '0000-00-00' ? $item->return_contract : '' }}</td><td>
                                            @if(!empty($item->file_contract))
                                                <a onclick="show_contract('{{ $item->file_contract }}')" class="btn btn-default btn-xs" style="height: 35px;width: 100px"><i class="fa fa-search-plus"></i>View</a>
                                            @else 
                                            -
                                            @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-default btn-xs" onclick="edit_contract({{ $item->id }}, '{{ $item->number }}', '{{ $item->type }}', '{{ $item->start_date }}', '{{ $item->end_date }}', '{{ $item->contract_sent }}', '{{ $item->return_contract }}')"><i class="fa fa-edit"></i></a>
                                                <a href="{{ route('administrator.karyawan.delete-contract', $item->id) }}" onclick="return confirm('Delete this data?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table><br /><br />
                            </div>
                        </div>
                    </div>
                    <br style="clear: both;" />
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.row -->
    <!-- ============================================================== -->
</div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>

<div id="modal_detail_visit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Visit</h4> </div>
                <div class="modal-body">
                <div><b style="font-size: large">Activity Name : </b>
                <p id="Visit_activity_name"></p>
                   
                <div><b style="font-size: medium">Description : </b>
                <p id="description"></p>
                        </div>
                        <div>
                        <b style="font-size: medium" class="text-center">Location Name : </b>
                        <p id="location_name"></p>
                        <b style="font-size: medium">Visit Location Map</b>
                        </div>
                        <div id="map" style="height: 254px; width: 100%;">
                        </div>
                        <div class="form-group">
                            <br>
                            <label class="col-md-6">Latitude </label>
                            <label class="col-md-6">Longitude </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-latitude" readonly="true">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-longitude" readonly="true">
                            </div>
                            <br>
                        </div>
                        <div id="container_justification">
                            <br>
                            <br>
                            <b style="font-size: medium" id="title_justification">Note : </b>
                            <p id="justification"></p>
                        </div>
                        <div>
                            <b style="font-size: medium">Branch Name / Place Name : </b>
                            <p id="branch_name"></p>
                        </div>
                <form class="form-horizontal frm-modal-inventaris">
                        <div class="form-group text-center">
                            <table class="table table-hover" id="tableListVisitPict">
                            <tr>
                            <th class="text-center">Photo</th>
                            </tr>
                            </table>
                        </div>
                        <div>
                            <b style="font-size: medium">PIC Name : </b>
                            <p id="picname"></p>
                        </div>
                        <b style="font-size: medium">Signature : </b>
                        <div class="col-md-12 signature">
                        </div>
                        
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal content education  -->
<div id="modal_detail_attendance" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Attendance</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-inventaris-lainnya">
                        <div class="form-group">
                            <div class="col-md-12 input_pic">
                            </div>
                        </div>
                       <div class="form-group text-center">
                           <b><p style="font-size: large" id="attendance_type"></p></b>
                           <div id="map" style="height: 254px; width: 100%;">

                           </div>

                       </div>
                       <div id="container_justification">
                           <b style="font-size: large" id="title_justification">Note : </b>
                           <p id="justification"></p>
                       </div>
                       <div>
                           <b style="font-size: medium">Branch : </b>
                           <p id="branch_name"></p>
                       </div>
                       <div>
                           <b style="font-size: medium">Location : </b>
                           <p id="location_name"></p>
                       </div>
                        <div class="form-group">
                            <label class="col-md-6">Latitude </label>
                            <label class="col-md-6">Longitude </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-latitude" readonly="true">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-longitude" readonly="true">
                            </div>
                        </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal content dependent  -->
<div id="modal_dependent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Dependent</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-dependent">
                        <div class="form-group">
                            <label class="col-md-12">Name</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-nama">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Relationship</label>
                            <div class="col-md-12">
                                <select class="form-control modal-hubungan">
                                    <option value="">Choose Relationship</option>
                                    <option value="Suami">Husband</option>
                                    <option value="Istri">Wife</option>
                                    <option value="Ayah Kandung">Father</option>
                                    <option value="Ibu Kandung">Mother</option>
                                    <option value="Anak 1">First Child</option>
                                    <option value="Anak 2">Second Child</option>
                                    <option value="Anak 3">Third Child</option>
                                    <option value="Anak 4">Fourth Child</option>
                                    <option value="Anak 5">Fifth Child</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Contact Number</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-contact">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Place of birth</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-tempat_lahir">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Date of birth(yyyy-mm-dd)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker2 modal-tanggal_lahir">
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-12">Date of death(yyyy-mm-dd)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker2 modal-tanggal_meninggal">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Education level</label>
                            <div class="col-md-12">
                                <select class="form-control modal-jenjang_pendidikan">
                                    <option value="">Choose Education Level</option>
                                    <option value="TK">TK</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA / SMK">SMA / SMK</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Occupation</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-pekerjaan" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Dependent</label>
                            <div class="col-md-12">
                                <select class="form-control modal-tertanggung">
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="action_dependent" value="insert">
                        <input type="hidden" name="id_dependent">
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_dependent">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content education  -->
<div id="modal_education" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Education</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-education">
                        <div class="form-group">
                            <label class="col-md-3">Education</label>
                            <div class="col-md-9">
                                <select class="form-control modal-pendidikan">
                                    <option value="">Coose Education</option>
                                    <option>SD</option>
                                    <option>SMP</option>
                                    <option>SMA/SMK</option>
                                    <option>D1</option>
                                    <option>D2</option>
                                    <option>D3</option>
                                    <option>S1</option>
                                    <option>S2</option>
                                    <option>S3</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">School Name / University</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-fakultas" name="modal-fakultas" id="modal-fakultas"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Year of Start</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control modal-tahun_awal" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Year of Graduate</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control modal-tahun_akhir" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Major</label>
                            <div class="col-md-9">
                                <select class="form-control modal-jurusan">
                                    <option value="">Choose Major</option>
                                    @foreach(get_jurusan() as $item)
                                    <option>{{ $item->name }}</option>
                                    @endforeach
                                    @foreach(get_program_studi() as $item)
                                    <option>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Grade</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-nilai" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">City</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-kota" placeholder="City / District"  name="modal-kota" id="modal-kota">
                            </div>
                        </div>
                        <input type="hidden" name="action_education" value="insert" />
                        <input type="hidden" name="id_education" value="">
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_education">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content certification  -->
<div id="modal_certification" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Training</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-certification" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-name" name="modal-name" id="modal-name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Date(yyyy-mm-dd)</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datepicker2 modal-date" name="modal-date" id="modal-date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Organizer</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-organizer" name="modal-organizer" id="modal-organizer"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Certificate Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-certificate_number" name="modal-certificate_number" id="modal-certificate_number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Score</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-score" name="modal-score" id="modal-score"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control modal-description" name="modal-description" id="modal-description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Certificate Photo</label>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <input type="file" id="modal-certificate_photo" name="modal-certificate_photo" class="form-control modal-certificate_photo" accept="image/*, application/pdf"/>
                                </div>
                                <div class="col-md-6">
                                    <a onclick="preview_certificate()" class="btn btn-default preview_certificate" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                </div>
                                {{-- <output id='result_certicate'/> --}}
                            </div>
                        </div>
                        <input type="hidden" name="action_certification" value="insert" />
                        <input type="hidden" name="id_certification" value="">
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_certification">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content education  -->
<div id="modal_inventaris_mobil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Tambah Inventaris Mobil</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-inventaris">
                        <div class="form-group">
                            <label class="col-md-12">Tipe Mobil</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-tipe_mobil">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Tahun</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-tahun">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="col-md-12">No Polisi</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-no_polisi">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="col-md-12">Status Mobil</label>
                            <div class="col-md-12">
                                <select class="form-control modal-status_mobil">
                                    <option value="">- none -</option>
                                    <option>Rental</option>
                                    <option>Perusahaan</option>
                                </select>
                            </div>
                       </div>
                       <input type="hidden" name="id_inventaris_mobil">
                       <input type="hidden" name="action_inventaris_mobil" value="insert">
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_inventaris_mobil">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_cuti" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Leave / Permit Type</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-cuti">
                        <div class="form-group">
                            <label class="col-md-12">Leave Name <span class="text-danger">*</span></label>
                            <div class="col-md-12" >
                                <select class="form-control modal-jenis_cuti " id="jenis_cuti" name="jenis_cuti">
                                    <!-- @foreach(get_master_cuti() as $i)
                                    <option value="{{ $i->id }}">{{ $i->description }}</option>
                                    @endforeach
                                    
                                    @foreach(get_master_cuti() as $item)
                                    <option value="{{ $item->id }}" data-kuota="{{ get_kuota_cutiAnnual($item->id,$data->join_date) }}" >{{ $item->description }}</option>
                                    @endforeach
                                    
                                    @foreach(get_master_cuti() as $item)
                                    <option value="{{ $item->id }}" data-id="{{$item->id}}" data-leavetype="{{$item->jenis_cuti}}" data-kuota="{{$item->kuota}}">{{ $item->description }}
                                    </option>
                                    @endforeach -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Leave Type</label>
                            <div class="col-md-12">
                                <input type="text" id="leavetype"  name="leavetype" readonly="true" class="form-control modal-leavetype">
                            </div>
                       </div>
                      
                        <div class="form-group">
                            <label class="col-md-12">Quota</label>
                            <div class="col-md-12">
                                <input type="number" id="kuota" readonly="true" class="form-control modal-kuota">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="col-md-12">Leave Taken <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input type="text" id="leavetaken" class="form-control modal-terpakai">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="col-md-12">Leave Balance</label>
                            <div class="col-md-12">
                                <input type="text" id="leavebalance" readonly="true" class="form-control modal-sisa_cuti">
                            </div>
                       </div>
                       <input type="hidden" name="action_cuti" value="insert" />
                       <input type="hidden" name="cuti_id" />
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_cuti">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
 
<!-- modal content education  -->
<div id="modal_inventaris_lainnya" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Tambah Inventaris Lainnya</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-inventaris-lainnya">
                        <div class="form-group">
                            <label class="col-md-12">Jenis Inventaris</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-inventaris-jenis">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-md-12">
                                <textarea class="form-control modal-inventaris-description"></textarea>
                            </div>
                       </div>
                        <input type="hidden" name="id_inventaris_lainnya">
                        <input type="hidden" name="action_inventaris_lainnya" value="insert">
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_inventaris_lainnya">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <div id="modalcontent">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div id="modal_file_cv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_cv"  style="display: none" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_sim" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_sim"  style="display: none" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_kk" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_kk"  style="display: none" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_ktp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_ktp"  style="display: none" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_certificate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_certificate"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content contract  -->
<div id="modal_contract" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Contract</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-contract" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-md-3">Contract Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-number" name="modal-number" id="modal-number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Contract Type</label>
                            <div class="col-md-9">
                                <select id="modal-contract_type" class="form-control modal-contract_type" name="modal-contract_type">
                                    <option value="" disabled selected>--Contract Type--</option>
                                    <option value="Main Contract">Main Contract</option>
                                    <option value="Amendment">Amendment</option>
                                    <option value="SKB">SKB</option>
                                    <option value="Parklaking">Parklaking</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="form_contract_start_date">
                            <label class="col-md-3">Contract Start Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-contract_start_date" name="modal-contract_start_date" id="modal-contract_start_date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Contract End Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-contract_end_date" name="modal-contract_end_date" id="modal-contract_end_date"/>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label class="col-md-3">Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-date_contract" name="modal-date_contract" id="modal-date_contract"/>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label class="col-md-3">Contract Sent</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-contract_sent" name="modal-contract_sent" id="modal-contract_sent"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Contract Return</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-return_contract" name="modal-return_contract" id="modal-return_contract"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Contract File</label>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <input type="file" id="modal-file_contract" name="modal-file_contract" class="form-control modal-file_contract" accept="image/*, application/pdf"/>
                                </div>
                                <div class="col-md-6">
                                    <a onclick="preview_contract()" class="btn btn-default preview_contract" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                </div>
                                {{-- <output id='result_certicate'/> --}}
                            </div>
                        </div>
                        <input type="hidden" name="action_contract" value="insert" />
                        <input type="hidden" name="id_contract" value="">
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_contract">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_contract" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_contract"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
<style type="text/css">
    .ui-autocomplete{
        z-index: 9999999 !important;
    }
    #map {
        height: 300px; 
        width: 100%;
    }
</style>
@section('footer-script')

<script src="{{ asset('js/administrator/karyawan-edit.js') }}"></script>

    
<?php
    if($data->jabatan_cabang == 'Head'){
?>
    <style type="text/css">
        .head-branch-select { display: block; }
    </style>
<?php
    }
?>

<?php
    if($data->jabatan_cabang == 'Staff'){
?>
    <style type="text/css">
        .staff-branch-select { display: block; }
    </style>
<?php
    }
?>
    <style type="text/css">
        .no-padding-td td {
            padding-top:2px !important;
            padding-bottom:2px !important;
        }
        .staff-branch-select, .head-branch-select {
            display: none;
        }
        .swal {
            margin: 10px;
        }

    </style>
    <!-- Date picker plugins css -->
    <link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker-employee/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Date Picker Plugin JavaScript -->
    <script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker-employee/bootstrap-datepicker.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.js"></script>
    <script type="text/javascript">

        function submit() {
            @if(get_setting('struktur_organisasi') == 3 && $career)
                $(document).on('click', '.SwalBtn1', function() {
                    $('#career_action').val(1)
                    checkResign()
                });
                $(document).on('click', '.SwalBtn2', function() {
                    $('#career_action').val(2)
                    checkResign()
                });
                $(document).on('click', '.SwalBtn3', function() {
                    swal.clickConfirm();
                });
                
                console.log('branch', ["{{ $career->cabang_id }}", $('select[name="branch_id"]').val(), "{{ $career->cabang_id }}" != $('select[name="branch_id"]').val()]);
                console.log('position', ["{{ $career->structure_organization_custom_id }}", $('select[name="structure_organization_custom_id"]').val(), "{{ $career->structure_organization_custom_id }}" != $('select[name="structure_organization_custom_id"]').val()]);
                console.log('status', ["{{ $career->status }}", $('select[name="organisasi_status"]').val(), "{{ $career->status }}" != $('select[name="organisasi_status"]').val()]);
                console.log('start date', ["{{ $career->start_date ? date('Y-m-d', strtotime($career->start_date)) : '' }}", $('input[name="start_date_contract"]').val(), "{{ $career->start_date ? date('Y-m-d', strtotime($career->start_date)) : '' }}" != $('input[name="start_date_contract"]').val()]);
                console.log('end date', ["{{ $career->end_date  ? date('Y-m-d', strtotime($career->end_date)) : '' }}", $('input[name="end_date_contract"]').val(), "{{ $career->end_date  ? date('Y-m-d', strtotime($career->end_date)) : '' }}" != $('input[name="end_date_contract"]').val()]);

                if (
                    "{{ $career->cabang_id }}" != $('select[name="branch_id"]').val() ||
                    "{{ $career->structure_organization_custom_id }}" != $('select[name="structure_organization_custom_id"]').val() ||
                    "{{ $career->status }}" != $('select[name="organisasi_status"]').val() ||
                    "{{ $career->start_date ? date('Y-m-d', strtotime($career->start_date)) : '' }}" != $('input[name="start_date_contract"]').val() ||
                    "{{ $career->end_date  ? date('Y-m-d', strtotime($career->end_date)) : '' }}" != $('input[name="end_date_contract"]').val()
                ) {
                    swal({
                        html: "This data changes will affect employee's career history, choose what to do" +
                            "<br>" +
                            '<button type="button" role="button" tabindex="0" class="SwalBtn1 swal btn btn-success">' + 'Update lastest history' + '</button>' +
                            '<button type="button" role="button" tabindex="0" class="SwalBtn2 swal btn btn-primary">' + 'Create new history' + '</button>' +
                            '<button type="button" role="button" tabindex="0" class="SwalBtn3 swal btn btn-danger">' + 'Cancel' + '</button>',
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                } else {
                    checkResign()
                }
            @else
                checkResign()
            @endif
        }

        function checkResign() {
            $(document).on('click', '.CheckBtn1', function() {
                realSubmit()
            });
            $(document).on('click', '.CheckBtn2', function() {
                swal.clickConfirm();
            });
            
            console.log('inactive_date', ["{{ $data->inactive_date }}", $("input[name='inactive_date']").val(), "{{ $data->inactive_date }}" != $("input[name='inactive_date']").val()]);
            console.log('resign_date', ["{{ $data->resign_date }}", $("input[name='resign_date']").val(), "{{ $data->resign_date }}" != $("input[name='resign_date']").val()]);
            console.log('end_date_contract', ["{{ $data->end_date_contract }}", $("input[name='end_date_contract']").val(), "{{ $data->end_date_contract }}" != $("input[name='end_date_contract']").val()]);

            if (
                ($("input[name='inactive_date']").val() && "{{ $data->inactive_date }}" != $("input[name='inactive_date']").val()) ||
                ($("input[name='resign_date']").val() && "{{ $data->resign_date }}" != $("input[name='resign_date']").val()) ||
                ($("input[name='end_date_contract']").val() && "{{ $data->end_date_contract }}" != $("input[name='end_date_contract']").val())
            ) {
                swal({
                    html: "Are you sure you want to fill or change resign/end contract/last work/login date? Once the date has passed, it cannot be change again" +
                        "<br>" +
                        '<button type="button" role="button" tabindex="0" class="CheckBtn1 swal btn btn-success">' + 'Submit' + '</button>' +
                        '<button type="button" role="button" tabindex="0" class="CheckBtn2 swal btn btn-danger">' + 'Cancel' + '</button>',
                    showCancelButton: false,
                    showConfirmButton: false
                });
            } else {
                realSubmit()
            }
        }

        function realSubmit() {
            if (!$("input[name='resign_date']").val()) {
                $("#check_status").prop('checked', false)
                $("input[name='resign_date']").addClass('hidden')
            }
            $("select[name='branch_id']").removeAttr('disabled');
            $("select[name='structure_organization_custom_id']").removeAttr('disabled');
            $("select[name='organisasi_status']").removeAttr('disabled');
            $('#form-karyawan').submit()
        }

        function show_ktp(img)
        {
            var images = ['png','gif','jpg','jpeg'];
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                $('#modalcontent').html('<embed src="{{ asset('storage/fotoktp/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
                $('#modal_file').modal('show');
            }
            else if(images.includes(ext)){
                $('#modalcontent').html('<img src="{{ asset('storage/fotoktp/')}}/'+ img +'" style = \'width: 100%;\' />');
                $('#modal_file').modal('show');
            }
            else{
                alert("Filetype is not supported!");
            }
        }

        function preview_ktp()
        {
            $('#modal_file_ktp').modal('show');
        }

        function show_kk(img)
        {
            var images = ['png','gif','jpg','jpeg'];
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                $('#modalcontent').html('<embed src="{{ asset('storage/fotokk/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
                $('#modal_file').modal('show');
            }
            else if(images.includes(ext)){
                $('#modalcontent').html('<img src="{{ asset('storage/fotokk/')}}/'+ img +'" style = \'width: 100%;\' />');
                $('#modal_file').modal('show');
            }
            else{
                alert("Filetype is not supported!");
            }
        }

        function preview_kk()
        {
            $('#modal_file_kk').modal('show');
        }

        function show_sim(img)
        {
            var images = ['png','gif','jpg','jpeg'];
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                $('#modalcontent').html('<embed src="{{ asset('storage/fotosim/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
                $('#modal_file').modal('show');
            }
            else if(images.includes(ext)){
                $('#modalcontent').html('<img src="{{ asset('storage/fotosim/')}}/'+ img +'" style = \'width: 100%;\' />');
                $('#modal_file').modal('show');
            }
            else{
                alert("Filetype is not supported!");
            }
        }

        function preview_sim()
        {
            $('#modal_file_sim').modal('show');
        }

        function show_cv(img)
        {
            var images = ['png','gif','jpg','jpeg'];
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf') {
                $('#modalcontent').html('<embed src="{{ asset('storage/fotocv/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
                $('#modal_file').modal('show');
            } else if(images.includes(ext)) {
                $('#modalcontent').html('<img src="{{ asset('storage/fotocv/')}}/'+ img +'" style = \'width: 100%;\' />');
                $('#modal_file').modal('show');
            } else {
                alert("Filetype is not supported!");
            }
        }

        function preview_cv()
        {
            $('#modal_file_cv').modal('show');
        }

        function show_certificate(img)
        {
            var images = ['png','gif','jpg','jpeg'];
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                $('#modalcontent').html('<embed src="{{ asset('storage/certificate/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
                $('#modal_file').modal('show');
            }
            else if(images.includes(ext)){
                $('#modalcontent').html('<img src="{{ asset('storage/certificate/')}}/'+ img +'" style = \'width: 100%;\' />');
                $('#modal_file').modal('show');
            }
            else{
                alert("Filetype is not supported!");
            }
        }

        function show_contract(img)
        {
            var images = ['png','gif','jpg','jpeg'];
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                $('#modalcontent').html('<embed src="{{ asset('storage/contract/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
                $('#modal_file').modal('show');
            }
            else if(images.includes(ext)){
                $('#modalcontent').html('<img src="{{ asset('storage/contract/')}}/'+ img +'" style = \'width: 100%;\' />');
                $('#modal_file').modal('show');
            }
            else{
                alert("Filetype is not supported!");
            }
        }

        function preview_certificate()
        {
            $('#modal_file_certificate').modal('show');
        }
        function preview_contract()
        {
            $('#modal_file_contract').modal('show');
        }
    </script>

    {{-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApcqhDgYwp6yKi4Xs-V6QIcd0KDyzu5d8"></script> --}}
    
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTctq_RFrwKOd84ZbvJYvU3MEcrLmPNJ8"
    async defer></script>
    
    <script type="text/javascript">
        window.onload = function() {
            //Check File API support
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementById("modal-certificate_photo");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_certicate");
                    $("#result_certicate").html("");
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics and pdf
                        if (!file.type.match('image') && !file.type === 'application/pdf')
                            continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            if(!file.type.match('image')){
                                div.innerHTML = "<embed src='" + picFile.result + "' >";
                            } else {
                                div.innerHTML = "<img width='100%' src='" + picFile.result + "' />";
                            }
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });

                var filesInput = document.getElementById("modal-file_contract");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_contract");
                    $("#result_contract").html("");
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics and pdf
                        if (!file.type.match('image') && !file.type === 'application/pdf')
                            continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            if(!file.type.match('image')){
                                div.innerHTML = "<embed src='" + picFile.result + "' >";
                            } else {
                                div.innerHTML = "<img width='100%' src='" + picFile.result + "' />";
                            }
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });

                var filesInput = document.getElementById("change_photo");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics and pdf
                        if (!file.type.match('image'))
                            continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            if(file.type.match('image')){
                                $('#result_change_photo').attr('src', picFile.result);
                            }
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });

            } else {
                console.log("Your browser does not support File API");
            }
        }

    $("select[name='jenis_cuti']").on('change', function(){


        var el = $(this).find(":selected");
        var jenis_cuti = $("#jenis_cuti").val();
            var joindate = join_date.value;
            var leavetype = $(el).data('leavetype');
            $("#leavetype").val(leavetype);
            // if(leavetype=='Permit')
            // {
            //     $('.modal-sisa_cuti').val("-");
            //     //$('.modal-terpakai').val(0);
            //     $("#leavetaken").val("-");
            //     $('.modal-kuota').val($(el).data('kuota'));
            // }
            // else
            // {
                $("#leavetaken").val(0);
                var url = '{{ route("administrator.karyawan.get-annual", ":cuti_id/:join_date") }}';
            url = url.replace(':cuti_id', jenis_cuti );
            url = url.replace(':join_date', joindate );
            var kuotacuti = $('#kuota').val();
            $.ajax({
                type: "GET",
                url:url,
                data: {
                    kuotacuti: kuotacuti
                },

                success: function(data) {
                    $('.modal-kuota').val(data);
                    $('.modal-sisa_cuti').val(data);
                }
            });
            // }
    });

    $(document).ready(function(){
        var checkVal = $("#DivVisitType").val();
        if(checkVal=='1'){
            $("#DivBranch").show();
        }
        else{
            $("#DivBranch").hide();
        }

        optJenisCuti()
    });

    $('#optShift').change(function() {
        optJenisCuti()
    })

    function optJenisCuti() {
        $("#jenis_cuti").html('')
        $.ajax({
            url: "{{route('ajax.leave.list')}}",
            type: 'GET',
            data: {
                'user_id': $('#idUser').val(),
                'shift_id': $('#optShift').val()
            },
            success: function(data){
                $.each(data, function(i, data){
                    $("#jenis_cuti").append('<option value="'+data.id+'" data-id="'+data.id+'" data-leavetype="'+data.jenis_cuti+'" data-kuota="'+data.kuota+'">'+data.description+'</option>')
                })
            }
        });
    }

    jQuery(function($) {
    $('#DivVisitType').on('change', function() {
      if ( this.value == '1')
      {
        $("#DivBranch").show();
      }
       else  
      {
        $("#DivBranch").hide();
      }
    });
    });

        $('#branch_id').on('change', function(){
            var branch_id = $(this).val()
            $.ajax({
                url: "{{route('shift.list')}}",
                type: 'GET',
                data: {'branch_id': branch_id},
                dataType: 'JSON',
                contentType: 'application/json',
                success: function(data){
                    if(data.message == 'success'){
                        var temp = $('#optShift')
                        temp.empty()
                        $('#optShift').append("<option value=''> - Select Shift - </option>");
                        $.each(data.data, function(i, data){
                            $('<option>', {
                                value: data.id,
                                text: data.name
                            }).html(data.name).appendTo('#optShift')
                        })
                    }
                    else{
                        var temp = $('#optShift')
                        temp.empty()
                        $('#optShift').append("<option value=''> - Select Shift - </option>");
                    }
                    // console.log(data)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR);
                    // console.log(textStatus);
                    // console.log(errorThrown);
                }
            })
        })

        $('#tableAttendance').ready(function(){
            var user_id = $('#idUser').val()
            $.ajax({
                url: 'ajax-edit',
                type: 'GET',
                dataType: 'JSON',
                contentType: 'application/json',
                processData: false,
                success: function(data){
                    // console.log(data)
                    if(data.message == 'success'){
                        if(data.absensi_item.length > 0){
                            $('.tanggalAbsen').each(function(i){
                                for(var i = 0; i < data.absensi_item.length; i++){
                                    if($(this).text() == data.absensi_item[i].date){
                                        var baru = i+1
                                        var asset = "'upload/attendance/'"
                                        if(data.absensi_item[i].long || data.absensi_item[i].lat || data.absensi_item[i].pic){
                                            var long_office = 'data-long-office="'+data.absensi_item[i].long_office_in+'"'
                                            var lat_office = 'data-lat-office="'+data.absensi_item[i].lat_office_in+'"'
                                            var radius_office = 'data-radius-office="'+data.absensi_item[i].radius_office_in+'"'
                                            if(data.absensi_item[i].long_office_in == null){
                                                long_office = 'data-long-office'
                                            }
                                            if(data.absensi_item[i].lat_office_in == null){
                                                lat_office = 'data-lat-office'
                                            }
                                            if(data.absensi_item[i].radius_office_in == null){
                                                radius_office = 'data-radius-office'
                                            }
                                            var attendance_type = '';
                                            var attendance_day = '';
                                            if(data.absensi_item[i].attendance_type_in == 'remote')
                                                attendance_type = '(R)';
                                            else if(data.absensi_item[i].attendance_type_in == 'out_of_office')
                                                attendance_type = '(O)';
                                            $('#clockIn'+data.absensi_item[i].date_shift).html(
                                                '<a href="javascript:void(0)" data-pic="{{asset("upload/attendance")}}'+data.absensi_item[i].pic+'" data-title="Clock In '+data.absensi_item[i].date+' '+data.absensi_item[i].clock_in+'" data-long="'+data.absensi_item[i].long+'" data-lat="'+data.absensi_item[i].lat+'" data-time="'+data.absensi_item[i].clock_in+'" '+long_office+' '+lat_office+' '+radius_office+' data-justification="'+data.absensi_item[i].justification_in+'" data-attendance-type="'+data.absensi_item[i].attendance_type_in+'" data-cabang="'+data.absensi_item[i].cabang_in+'" data-location="'+data.absensi_item[i].location_name_in+'" title="Mobile Attendance" onclick="detail_attendance(this)">'+data.absensi_item[i].clock_in+' '+attendance_type+'</a>'+
                                                '<i title="Mobile Attendance" class="fa fa-mobile pull-right" style="font-size: 20px;"></i>'
                                            )
                                        }
                                        else{
                                            $('#clockIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].clock_in)
                                        }
                                        if(data.absensi_item[i].long_out || data.absensi_item[i].lat_out || data.absensi_item[i].pic_out){
                                            var long_office = 'data-long-office="'+data.absensi_item[i].long_office_out+'"'
                                            var lat_office = 'data-lat-office="'+data.absensi_item[i].lat_office_out+'"'
                                            var radius_office = 'data-radius-office="'+data.absensi_item[i].radius_office_out+'"'
                                            if(data.absensi_item[i].long_office_out == null){
                                                long_office = 'data-long-office'
                                            }
                                            if(data.absensi_item[i].lat_office_out == null){
                                                lat_office = 'data-lat-office'
                                            }
                                            if(data.absensi_item[i].radius_office_out == null){
                                                radius_office = 'data-radius-office'
                                            }
                                            var attendance_type = '';
                                            var attendance_day = '';
                                            if(data.absensi_item[i].attendance_type_out == 'remote')
                                                attendance_type = '(R)';
                                            else if(data.absensi_item[i].attendance_type_out == 'out_of_office')
                                                attendance_type = '(O)';
                                            if(data.absensi_item[i].date != data.absensi_item[i].date_out)
                                                attendance_day += '(ND)';
                                            $('#clockOut'+data.absensi_item[i].date_shift).html(
                                                '<a href="javascript:void(0)" data-pic="{{asset("upload/attendance")}}'+data.absensi_item[i].pic_out+'" data-title="Clock Out '+data.absensi_item[i].date_out+' '+data.absensi_item[i].clock_out+'" data-long="'+data.absensi_item[i].long_out+'" data-lat="'+data.absensi_item[i].lat_out+'" data-time="'+data.absensi_item[i].clock_out+'" '+long_office+' '+lat_office+' '+radius_office+' data-justification="'+data.absensi_item[i].justification_out+'" data-attendance-type="'+data.absensi_item[i].attendance_type_out+'" data-cabang="'+data.absensi_item[i].cabang_in+'" data-location="'+data.absensi_item[i].location_name_out+'" title="Mobile Attendance" onclick="detail_attendance(this)">'+data.absensi_item[i].clock_out+' '+attendance_type+' '+attendance_day+'</a>'+
                                                '<i title="Mobile Attendance" class="fa fa-mobile pull-right" style="font-size: 20px;"></i>'
                                            )
                                        }
                                        else{
                                            $('#clockOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].clock_out)
                                        }
                                        $('#lateIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].late)
                                        $('#earlyOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].early)
                                        $('#duration'+data.absensi_item[i].date_shift).html(data.absensi_item[i].work_time)

                                        $('#branchIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].cabang_in);
                                        $('#branchOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].cabang_out);
                                        $('#shift'+data.absensi_item[i].date_shift).html(data.absensi_item[i].shift_name ? data.absensi_item[i].shift_name : 'No Shift');
                                        $('#shiftIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].shift_in);
                                        $('#shiftOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].shift_out);
                                        $('#hariAbsen'+data.absensi_item[i].date_shift).css('color', data.absensi_item[i].shift_detail_id || !data.absensi_item[i].shift_name ? 'blue' : 'black');
                                    }
                                }
                            })
                        }

                        if(data.holidays.length > 0){
                            $('.tanggalAbsen').each(function(i){
                                for(var y = 0; y < data.holidays.length; y++){
                                    if($(this).text() == data.holidays[y].tanggal){
                                        $(this).css('color', 'red')
                                    }
                                }
                            })
                        }
                    }
                    else{
                        
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR);
                    // console.log(textStatus);
                    // console.log(errorThrown);
                }
            })
        })
        $('#tableVisit').ready(function() {
            var user_id = $('#idUser').val()
            $.ajax({
                url: '{{route("visit.ajax-holiday")}}',
                type: 'GET',
                dataType: 'JSON',
                contentType: 'application/json',
                processData: false,
                success: function(data) {
                    // // console.log(data)
                    if (data.message == 'success') {
                        if (data.holidays.length > 0) {
                            $('.tanggalVisit').each(function(i) {
                                // console.log(i)
                                var baru = i + 1
                                if ($('#holiday' + baru).val() == 0) {
                                    for (var y = 0; y < data.holidays.length; y++) {
                                        if ($(this).text() == data.holidays[y].tanggal) {
                                            $(this).css('color', 'red')
                                        }
                                    }
                                }
                            })
                        }
                    } else {

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR);
                    // console.log(textStatus);
                    // console.log(errorThrown);
                }
            })
        })

        function detail_visit(el)
        {
            var idlist = $(el).data('visitid');
            var url = '{{ route("administrator.karyawan.visit-pict", ":visitid") }}';
            url = url.replace(':visitid', idlist );
            var pathsignature = $(el).data('signature');
            var visittype = $(el).data('visittype');
            var isoutbranch = $(el).data('isoutbranch');
            var img = '<img src="' + pathsignature + '" style="width:100%;" />';
            $('#modal_detail_visit .modal-title').html($(el).data('title'));
            $('.signature').html(img);
            $(".input-latitude").val($(el).data('latitude'));
            $(".input-longitude").val($(el).data('longitude'));
            $("#modal_detail_visit").modal("show");
            $('#idvisit').html($(el).data('visitid'));
            $('#Visit_activity_name').html($(el).data('activity-name'));
            $('#picname').html($(el).data('picname'));
            $('#justification').html($(el).data('justification'));
            $('#location_name').html($(el).data('location'));
            if (visittype==2 || ( visittype==1 && isoutbranch==1))
            {
                $('#branch_name').html($(el).data('placename'));
                
            }
            else
            {
                $('#branch_name').html($(el).data('cabang'));
            }
            $('#description').html($(el).data('description'));


            // The location of Uluru
            var userLoc = {lat: $(el).data('latitude'), lng: $(el).data('longitude')};
            var icon = "{{asset('images/icon/icon_man.png')}}";
            // The map, centered at Uluru
            setTimeout(function(){
                var map = new google.maps.Map(
                    document.getElementById('map'));
                // The marker, positioned at Uluru
                var userMarker = new google.maps.Marker({position: userLoc, map: map,icon: icon});
                var bounds = new google.maps.LatLngBounds();
                bounds.extend(userMarker.getPosition());
                var padding = 0;

                if($(el).data('lat-branch')!="" && $(el).data('long-branch')!="") {
                    var branchLoc = {lat: $(el).data('lat-branch'), lng: $(el).data('long-branch')};
                    var radius = $(el).data('radius-branch');
                    var distance = getDistance(userLoc.lat,userLoc.lng,branchLoc.lat,branchLoc.lng);
                    var color;
                    if(distance > radius){
                        color = "#FF0000";
                        padding = 0;
                    }
                    else{
                        color = "#7cb342";
                        padding = 100;
                    }

                    var cityCircle = new google.maps.Circle({
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color,
                        fillOpacity: 0.35,
                        map: map,
                        center: branchLoc,
                        radius: radius
                    });
                    // console.log("City Circle colored : "+color);

                    bounds.extend(branchLoc);
                }
                map.fitBounds(bounds,padding);
            }, 1000);
        
        
            $.ajax({       
        url:url,
        type: "GET",
        dataType: "JSON",
        contentType: "application/json",
        processData: false,
        success: function(data){
            if(data.message == 'success'){
                $('#IdVisit').val(idlist)
                $('#tableListVisitPict').find('tr:gt(0)').remove()
                for(var i = 0; i < data.data.length; i++){
                    var num = i+1;
                    if(data.data[i].visit_list_id == idlist){
                     $('#tableListVisitPict tr:last').after(
                            '<tr>'+
                            '<td width=100% style="text-align: center; vertical-align: middle;"><img src="/'+data.data[i].photo+'" style="width:100%;height:70%;text-align: center;"></img> <br> <br> <p>'+data.data[i].photocaption+'</p> </td>'+'</tr>'
                        )
                    }
                    else{
                        $('#tableListVisitPict tr:last').after(
                            '<tr>'+
                            '<td width=100% style="text-align: center; vertical-align: middle;"><img src="/'+data.data[i].photo+'" style="width:100%;height:70%;text-align: center;"></img> <br> <br> <p>'+data.data[i].photocaption+'</p> </td>'+'</tr>'
                        )
                    }
                }
            }
            else{
                $('#tableListVisitPict').find('tr:gt(0)').remove()
                $('#tableListVisitPict tr:last').after(
                    '<tr>'+
                        '<td colspan="1">No data.</td>'+
                    '</tr>'
                )
                $('#modal_detail_visit .modal-title').html($(el).data('title'));
            }
        }
        }
        )
        }

        function detail_attendance(el)
        {
            var img = '<img src="'+ $(el).data('pic') +'" style="width:100%;" />';
            $('#modal_detail_attendance .modal-title').html($(el).data('title'));
            $('.input_pic').html(img);
            $(".input-latitude").val($(el).data('lat'));
            $(".input-longitude").val($(el).data('long'));
            $("#modal_detail_attendance").modal("show");

            if($(el).data('attendance-type')=='remote'){
                $('#attendance_type').html("Remote Attendance");
            }
            else if($(el).data('attendance-type')=='out_of_office'){
                $('#attendance_type').html("Out of Office Attendance");
            }
            else{
                $('#attendance_type').html("Normal Attendance");
            }
            if($(el).data('attendance-type')=='out_of_office') {
                $('#title_justification').html('Justification : ');
            }
            else{
                $('#title_justification').html('Note : ');
            }
            $('#justification').html($(el).data('justification'));
            $('#location_name').html($(el).data('location'));
            $('#branch_name').html($(el).data('cabang'));


            // The location of Uluru
            var userLoc = {lat: $(el).data('lat'), lng: $(el).data('long')};
            var icon = "{{asset('images/icon/icon_man.png')}}";
            // The map, centered at Uluru
            setTimeout(function(){
                var map = new google.maps.Map(
                    document.getElementById('map'));
                // The marker, positioned at Uluru
                var userMarker = new google.maps.Marker({position: userLoc, map: map,icon: icon});
                var bounds = new google.maps.LatLngBounds();
                bounds.extend(userMarker.getPosition());
                var padding = 0;
                if($(el).data('lat-office')!="" && $(el).data('long-office')!="") {
                    var officeLoc = {lat: $(el).data('lat-office'), lng: $(el).data('long-office')};
                    var radius = $(el).data('radius-office');
                    var distance = getDistance(userLoc.lat,userLoc.lng,officeLoc.lat,officeLoc.lng);
                    var color;
                    if(distance > radius){
                        color = "#FF0000";
                        padding = 0;
                    }
                    else{
                        color = "#7cb342";
                        padding = 100;
                    }

                    var cityCircle = new google.maps.Circle({
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color,
                        fillOpacity: 0.35,
                        map: map,
                        center: officeLoc,
                        radius: radius
                    });
                    // console.log("City Circle colored : "+color);

                    bounds.extend(officeLoc);
                }
                map.fitBounds(bounds,padding);
            }, 1000);
        }
        function getDistance(lat1,lon1,lat2,lon2) {

            var R = 6371000; // Radius of the earth in m
            var dLat = deg2rad(lat2-lat1);  // deg2rad below
            var dLon = deg2rad(lon2-lon1);
            var a =
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var d = R * c; // Distance in km
            return d;
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180)
        }

        jQuery('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
        }).on('change', function(){
            $('.datepicker').hide();
        });;

        $("#modal-fakultas").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('ajax.get-university') }}",
                    method:"POST",
                    data: {'word' : request.term, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType:"json",
                    success:function(data)
                    {
                        response(data);
                    }
                })
            },
            select: function( event, ui ) {
                $("input[name='modal-fakultas']").val(ui.item.id)
            },
            showAutocompleteOnFocus: true
        });

        $("#modal-kota").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('ajax.get-city') }}",
                    method:"POST",
                    data: {'word' : request.term, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType:"json",
                    success:function(data)
                    {
                        response(data);
                    }
                })
            },
            select: function( event, ui ) {
                $("input[name='modal-kota']").val(ui.item.id)
            },
            showAutocompleteOnFocus: true
        });

        var el_dependent;
        var el_education;
        var el_certification;
        var el_cuti;
        var el_contract;

        function open_dialog_photo()
        {
            $("input[name='foto']").trigger('click');   
        }

        $(".modal-terpakai, .modal-kuota").on("input", function(){

            if($('.modal-terpakai').val() == "" || $('.modal-terpakai').val() == 0)
            {
                $('.modal-sisa_cuti').val($('.modal-kuota').val());
            }
            else
            {
                $('.modal-sisa_cuti').val(parseInt($('.modal-kuota').val()) - parseInt($(".modal-terpakai").val()) );
            }
        });

        function edit_inventaris_mobil(id, tipe_mobil, tahun, no_polisi, status_mobil)
        {
            $('.modal-tipe_mobil').val(tipe_mobil);
            $('.modal-tahun').val(tahun);
            $('.modal-no_polisi').val(no_polisi);
            $('.modal-status_mobil').val(status_mobil);

            $("#modal_inventaris_mobil").modal('show');
            $("input[name='id_inventaris_mobil']").val(id);
        }

        function edit_cuti(id, jenis_cuti, kuota, terpakai, leave_type)
        {   
            $("#jenis_cuti").attr('disabled', true);

            $('.modal-jenis_cuti').val(jenis_cuti);
            $('.modal-leavetype').val(leave_type);
            $('.modal-kuota').val(kuota);
            $('.modal-terpakai').val(terpakai);
            $('.modal-sisa_cuti').val(parseInt(kuota) - parseInt(terpakai));

            $("input[name='cuti_id']").val(id);

            $("#modal_cuti").modal('show');
        }

        function edit_row_cuti(el, jenis_cuti, kuota, terpakai, leave_type)
        {
            el_cuti = el;

            $("#jenis_cuti").attr('disabled', true);

            $('.modal-jenis_cuti').val(jenis_cuti);
            $('.modal-leavetype').val(leave_type);
            $('.modal-kuota').val(kuota);
            $('.modal-terpakai').val(terpakai);
            $('.modal-sisa_cuti').val(parseInt(kuota) - parseInt(terpakai));

            $("input[name='action_cuti']").val('update');
            $("#modal_cuti").modal('show');
        }

        function edit_education(id, pendidikan, tahun_awal, tahun_akhir, fakultas, jurusan, nilai, kota)
        {
            $('.modal-pendidikan').val(pendidikan);
            $('.modal-fakultas').val(fakultas);
            $('.modal-tahun_awal').val(tahun_awal);
            $('.modal-tahun_akhir').val(tahun_akhir);
            $('.modal-jurusan').val(jurusan);
            $('.modal-nilai').val(nilai);
            $('.modal-kota').val(kota);

            $("#modal_education").modal("show");

            $("input[name='action_education']").val('update');
            $("input[name='id_education']").val(id);
        }

        function update_row_education(el, pendidikan, tahun_awal, tahun_akhir, fakultas, jurusan, nilai, kota)
        {
            el_education = el;

            $('.modal-pendidikan').val(pendidikan);
            $('.modal-fakultas').val(fakultas);
            $('.modal-tahun_awal').val(tahun_awal);
            $('.modal-tahun_akhir').val(tahun_akhir);
            $('.modal-jurusan').val(jurusan);
            $('.modal-nilai').val(nilai);
            $('.modal-kota').val(kota);

            $("#modal_education").modal("show");

            $("input[name='action_education']").val('update');
        }

        function update_row_dependent(el, nama, hubungan, contact, tempat_lahir, tanggal_lahir, tanggal_meninggal, jenjang_pendidikan, pekerjaan, tertanggung)
        {
            $("input[name='action_dependent']").val('update');

            $('.modal-nama').val(nama);
            $('.modal-hubungan').val(hubungan);
            $('.modal-contact').val(contact);
            $('.modal-tempat_lahir').val(tempat_lahir);
            $('.modal-tanggal_lahir').val(tanggal_lahir);
            $('.modal-tanggal_meninggal').val(tanggal_meninggal);
            $('.modal-jenjang_pendidikan').val(jenjang_pendidikan);
            $('.modal-pekerjaan').val(pekerjaan);
            $('.modal-tertanggung').val(tertanggung);

            $('#modal_dependent').modal('show');

            el_dependent = el;
        }

        function edit_dependent(id, nama, hubungan, contact, tempat_lahir, tanggal_lahir, tanggal_meninggal, jenjang_pendidikan, pekerjaan, tertanggung)
        {
            $("input[name='id_dependent']").val(id);

            $('.modal-nama').val(nama);
            $('.modal-hubungan').val(hubungan);
            $('.modal-contact').val(contact);
            $('.modal-tempat_lahir').val(tempat_lahir);
            $('.modal-tanggal_lahir').val(tanggal_lahir);
            $('.modal-tanggal_meninggal').val(tanggal_meninggal);
            $('.modal-jenjang_pendidikan').val(jenjang_pendidikan);
            $('.modal-pekerjaan').val(pekerjaan);
            $('.modal-tertanggung').val(tertanggung);

            $('#modal_dependent').modal('show');
        }

        function update_row_certification(el, name, date, organizer, certificate_number, score, description)
        {
            $("input[name='action_certification']").val('update');

            $('.modal-name').val(name);
            $('.modal-date').val(date);
            $('.modal-organizer').val(organizer);
            $('.modal-certificate_number').val(certificate_number);
            $('.modal-score').val(score);
            $('.modal-description').val(description);
            $('.modal-certificate_photo').val(certificate_photo);
            $('#modal_certification').modal('show');

            el_certification = el;
        }

        function edit_certification(id, name, date, organizer, certificate_number, score, description, certificate_photo)
        {
            $("input[name='id_certification']").val(id);

            $('.modal-name').val(name);
            $('.modal-date').val(date);
            $('.modal-organizer').val(organizer);
            $('.modal-certificate_number').val(certificate_number);
            $('.modal-score').val(score);
            $('.modal-description').val(description);
            //$('.modal-certificate_photo').val(certificate_photo);
            $("#modal-certificate_photo").val('')
            $(".preview_certificate").hide();
            $('#modal_certification').modal('show');
        }

        function update_row_contract(el, number, type, start_date, end_date, contract_sent, return_contract, file_contract)
        {
            $("input[name='action_contract']").val('update');

            $('.modal-number').val(number);
            $('.modal-contract_type').val(type);
            $('.modal-contract_start_date').val(start_date);
            $('.modal-contract_end_date').val(end_date);
            $('.modal-contract_sent').val(contract_sent);
            $('.modal-return_contract').val(return_contract);
            $('.modal-file_contract').val(file_contract);
            $('#modal_certification').modal('show');

            el_contract = el;
        }

        function edit_contract(id, number, type, start_date, end_date, contract_sent, return_contract, file_contract)
        {
            $("input[name='id_contract']").val(id);

            $('.modal-number').val(number);
            $('.modal-contract_type').val(type);
            if(type=='Amendment'){
                $('#form_contract_start_date').addClass('hidden');
            }
            $('.modal-contract_start_date').val(start_date);
            $('.modal-contract_end_date').val(end_date);
            $('.modal-return_contract').val(return_contract);
            $('.modal-contract_sent').val(contract_sent);
            $('.modal-file_contract').val(file_contract);
            $('#modal_contract').modal('show');
        }

        $("select[name='branch_type']").on('change', function(){

            if($(this).val() == 'BRANCH')
            {
                $(".section-cabang").show();
            }
            else
            {
                $(".section-cabang").hide();
            }
        });


        /**
         * Inventaris Lainnya
         *
         */
        var el_inventaris_lainnya;
        $("#add_inventaris_lainnya").click(function(){

            $("#modal_inventaris_lainnya").modal('show');
        });

        $("#add_modal_inventaris_lainnya").click(function(){

            var el = '<tr>';
            var modal_jenis         = $('.modal-inventaris-jenis').val();
            var modal_description   = $('.modal-inventaris-description').val();
           

            el +='<td>'+ (parseInt($('.table_inventaris_lainnya tr').length) + 1)  +'</td>';
            el +='<td>'+ modal_jenis +'</td>';
            el +='<td>'+ modal_description +'</td>';
            el +='<td><a class="btn btn-default btn-xs" onclick="update_row_inventaris_lainnya(this,\''+ modal_jenis +'\',\''+ modal_description +'\')"><i class="fa fa-edit"></i></a><a class="btn btn-danger btn-xs" onclick="return delete_row_dependent(el);"><i class="fa fa-trash"></i></a></td>';
            el +='<input type="hidden" name="inventaris_lainnya[jenis][]" value="'+ modal_jenis +'" />';
            el +='<input type="hidden" name="inventaris_lainnya[description][]" value="'+ modal_description +'" />';

            if($("input[name='action_inventaris_lainnya']").val() == 'update')
            {
                $(el_inventaris_lainnya).parent().parent().remove();
            }

            var id = $("input[name='id_inventaris_lainnya']").val();
            if(id != "")
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-inventaris-lainnya') }}',
                    data: {'id' : id, 'jenis' : modal_jenis,'description' : modal_description,  '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {
                        window.location.href = '{{ route('administrator.karyawan.edit', $data->id) }}';
                    }
                });

                return false;
            }

            $('.table_inventaris_lainnya').append(el);
            $('#modal_inventaris_lainnya').modal('hide');
            $('form.frm-modal-inventaris-lainnya').trigger('reset');
        });

        function update_row_inventaris_lainnya(el, jenis, description)
        {
            el_inventaris_lainnya = el;

            $('.modal-inventaris-jenis').val(jenis);
            $('.modal-inventaris-description').val(description);
            $("input[name='action_inventaris_lainnya']").val('update');
            $('#modal_inventaris_lainnya').modal('show');
        }

        function edit_inventaris_lainnya(id,jenis, description)
        {
            $("input[name='id_inventaris_lainnya']").val(id);
            $('.modal-inventaris-jenis').val(jenis);
            $('.modal-inventaris-description').val(description);

            $('#modal_inventaris_lainnya').modal('show');
        }
        /**
         * End Inventaris Lainnya
         */
        $('#join_date').change(function(){
            var join_date            = $("#join_date").val();
          
        });

        $("#add_cuti").click(function(){            
            let array = [];
            $(".cuti-id").each(function() {
                array.push($(this).val());
            });

            $("#jenis_cuti").removeAttr('disabled');
            $("#jenis_cuti option").show();
            $("#jenis_cuti option").each(function() {
                if(array.includes($(this).attr('value')))
                    $(this).hide();
            });
           
            if (join_date.value != "")
            {
                $("input[name='cuti_id']").val("");
                $("form.frm-modal-cuti").trigger('reset');
                $('.modal-kuota').val("");
                $('#jenis_cuti').val("");
                $('#leavetype').val("");
                $('#leavepermit').val("");
                $("#modal_cuti").modal('show');
               // window.alert(join_date.value)
            }
            else
            {
            bootbox.alert('<label style="color: red;">Please Select Join Date First </label>');
            }
        });


        $("select[name='empore_organisasi_direktur']").on('change', function(){
            var id  = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-manager-by-direktur') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {
                    var el = '<option value="">Choose </option>';

                    $(data.data).each(function(k,v){
                        // console.log(v);
                       el += '<option value="'+ v.id +'">'+ v.name +'</option>';
                    });

                    $("select[name='empore_organisasi_manager_id']").html(el);
                }
            });
        });


        $("select[name='empore_organisasi_manager_id']").on('change', function(){
            var id  = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-staff-by-manager') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {
                    var el = '<option value="">Choose </option>';

                    $(data.data).each(function(k,v){
                        // console.log(v);
                       el += '<option value="'+ v.id +'">'+ v.name +'</option>';
                    });

                    $("select[name='empore_organisasi_staff_id']").html(el);
                }
            });
        });

        $("#add_modal_cuti").click(function(){
            if($('#jenis_cuti').val() == "" || $('#kuota').val() == "" || $('#leavetaken').val() == "")
            {
                bootbox.alert('Please Complete All Form');
                return false;
            }

            var jenis_cuti = $('.modal-jenis_cuti :selected');
            var type = '\''+$('.modal-leavetype').val()+'\'';
            var kuota = $('.modal-kuota').val();
            var terpakai = $('.modal-terpakai').val() == "" ? 0 : $('.modal-terpakai').val();

            var el = '<tr><td>'+ (parseInt($('.table_cuti tr').length) + 1) +'</td><td>'+ jenis_cuti.text() +'</td><td>'+ kuota +'</td>';
            
            let balance = terpakai == '-' ? '-' : parseInt(kuota) - parseInt(terpakai);

            el += '<td>'+ terpakai +'</td>';
            el += '<td>'+ balance +'</td>';
            el += '<td><a class="btn btn-default btn-xs" onclick="edit_row_cuti(this,'+ jenis_cuti.val() +','+ kuota +','+ terpakai +','+ type +')" style="margin-right: 3px;"><i class="fa fa-edit"></i></a><a class="btn btn-danger btn-xs remove-cuti"><i class="fa fa-trash"></i></a></td>';
            el += '<input type="hidden" class="cuti-id" value="'+ jenis_cuti.val() +'" />';
            el += '<input type="hidden" name="cuti[cuti_id][]" value="'+ jenis_cuti.val() +'" />';
            el += '<input type="hidden" name="cuti[kuota][]" value="'+ kuota +'" />';
            el += '<input type="hidden" name="cuti[terpakai][]" value="'+ terpakai +'" />';
            el += '</tr>';

            var id = $("input[name='cuti_id']").val();

            $("form.frm-modal-cuti").trigger('reset');
            $('.modal-kuota').val("");
            $('#jenis_cuti').val("");
            $('#leavetype').val("");
            $('#leavepermit').val("");
            $("#jenis_cuti").removeAttr('disabled');

            if(id != "")
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-cuti') }}',
                    data: {'id' : id, 'cuti_id' : jenis_cuti.val(), 'kuota' : kuota, 'terpakai': terpakai, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {
                        window.location.href = '{{ route('administrator.karyawan.edit', $data->id) }}';
                    }
                });

                return false;
            }

            var act = $("input[name='action_cuti']").val();
            if(act == 'update')
            {
                $(el_cuti).parent().parent().remove();

                $("input[name='action_cuti']").val('insert')
            }

            $('.table_cuti').append(el);

            $("#modal_cuti").modal('hide');
        });

        $('table').on('click','tr a.remove-cuti',function(e){
            e.preventDefault();
            $(this).closest('tr').remove()
        })

        /**
         * Inventasi Mobil
         *
         */
        $("#add_inventaris_mobil").click(function(){

            $("#modal_inventaris_mobil").modal('show');
        });
        var el_inventaris_mobil;
        $("#add_modal_inventaris_mobil").click(function(){

            var el = '<tr>';
            var modal_tipe_mobil            = $('.modal-tipe_mobil').val();
            var modal_tahun                 = $('.modal-tahun').val();
            var modal_no_polisi             = $('.modal-no_polisi').val();
            var modal_status_mobil          = $('.modal-status_mobil').val();

            el += '<td>'+ (parseInt($('.table_mobil tr').length) + 1)  +'</td>';
            el +='<td>'+ modal_tipe_mobil +'</td>';
            el +='<td>'+ modal_tahun +'</td>';
            el +='<td>'+ modal_no_polisi +'</td>';
            el +='<td>'+ modal_status_mobil +'</td>';
            el +='<td><a class="btn btn-default btn-xs" onclick="update_row_inventaris_mobil(this,\''+ modal_tipe_mobil +'\',\''+ modal_tahun +'\',\''+ modal_no_polisi +'\',\''+ modal_status_mobil +'\')"><i class="fa fa-edit"></i></a></td>';

            el +='<input type="hidden" name="inventaris_mobil[tipe_mobil][]" value="'+ modal_tipe_mobil +'" />';
            el +='<input type="hidden" name="inventaris_mobil[tahun][]" value="'+ modal_tahun +'" />';
            el +='<input type="hidden" name="inventaris_mobil[no_polisi][]" value="'+ modal_no_polisi +'" />';
            el +='<input type="hidden" name="inventaris_mobil[status_mobil][]" value="'+ modal_status_mobil +'" />';
            if($("input[name='action_inventaris_mobil']").val() == 'update')
            {
                $(el_inventaris_mobil).parent().parent().remove();
            }

            var id = $("input[name='id_inventaris_mobil']").val();
            if(id != "")
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-inventaris-mobil') }}',
                    data: {'id' : id, 'tipe_mobil' : modal_tipe_mobil,'tahun' : modal_tahun, 'no_polisi': modal_no_polisi, 'status_mobil': modal_status_mobil,  '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {

                        $("input[name='id_inventaris_mobil']").val("");

                        window.location.href = '{{ route('administrator.karyawan.edit', $data->id) }}';
                    }
                });

                return false;
            }

            $('.table_mobil').append(el);
            $('#modal_inventaris_mobil').modal('hide');
            $('form.frm-modal-inventaris-mobil').trigger('reset');
        });

        function update_row_inventaris_mobil(el,tipe_mobil,tahun,no_polisi,status_mobil)
        {
            el_inventaris_mobil = el;

            $('.modal-tipe_mobil').val(tipe_mobil);
            $('.modal-tahun').val(tahun);
            $('.modal-no_polisi').val(no_polisi);
            $('.modal-status_mobil').val(status_mobil);

            $('#modal_inventaris_mobil').modal('show');
            $("input[name='action_inventaris_mobil']").val('update');
        }
        /**
         * End Inventaris Mobil
         */

         $("#add_modal_dependent").click(function(){

            var el = '<tr>';
            var modal_nama                  = $('.modal-nama').val();
            var modal_hubungan              = $('.modal-hubungan').val();
            var modal_contact               = $('.modal-contact').val();
            var modal_tempat_lahir          = $('.modal-tempat_lahir').val();
            var modal_tanggal_lahir         = $('.modal-tanggal_lahir').val();
            var modal_tanggal_meninggal     = $('.modal-tanggal_meninggal').val();
            var modal_jenjang_pendidikan    = $('.modal-jenjang_pendidikan').val();
            var modal_pekerjaan             = $('.modal-pekerjaan').val();
            var modal_tertanggung           = $('.modal-tertanggung').val();

            $('.modal-nama, .modal-hubungan, .modal-contact, .modal-tempat_lahir, .modal-tanggal_lahir').val("");

            var id = $("input[name='id_dependent']").val();

            if(id != "")
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-dependent') }}',
                    data: {'id' : id, 'nama' : modal_nama, 'hubungan': modal_hubungan, 'contact': modal_contact, 'tempat_lahir': modal_tempat_lahir, 'tanggal_lahir': modal_tanggal_lahir, 'tanggal_meninggal' : modal_tanggal_meninggal, 'jenjang_pendidikan' : modal_jenjang_pendidikan, 'pekerjaan' : modal_pekerjaan,'tertanggung': modal_tertanggung, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {

                        $("input[name='id_dependent']").val("");

                        window.location.href = '{{ route('administrator.karyawan.edit', $data->id) }}';
                    }
                });

                return false;
            }

            el += '<td>'+ (parseInt($('.dependent_table tr').length) + 1)  +'</td>';
            el +='<td>'+ modal_nama +'</td>';
            el +='<td>'+ modal_hubungan +'</td>';
            el +='<td>'+ modal_contact +'</td>';
            el +='<td>'+ modal_tempat_lahir +'</td>';
            el +='<td>'+ modal_tanggal_lahir +'</td>';
            el +='<td>'+ modal_tanggal_meninggal +'</td>';
            el +='<td>'+ modal_jenjang_pendidikan +'</td>';
            el +='<td>'+ modal_pekerjaan +'</td>';
            el +='<input type="hidden" name="dependent[nama][]" value="'+ modal_nama +'" />';
            el +='<input type="hidden" name="dependent[hubungan][]" value="'+ modal_hubungan +'" />';
            el +='<input type="hidden" name="dependent[contact][]" value="'+ modal_contact +'" />';
            el +='<input type="hidden" name="dependent[tempat_lahir][]" value="'+ modal_tempat_lahir +'" />';
            el +='<input type="hidden" name="dependent[tanggal_lahir][]" value="'+ modal_tanggal_lahir +'" />';
            el +='<input type="hidden" name="dependent[tanggal_meninggal][]" value="'+ modal_tanggal_meninggal +'" />';
            el +='<input type="hidden" name="dependent[jenjang_pendidikan][]" value="'+ modal_jenjang_pendidikan +'" />';
            el +='<input type="hidden" name="dependent[pekerjaan][]" value="'+ modal_pekerjaan +'" />';
            el +='<input type="hidden" name="dependent[tertanggung][]" value="'+ modal_tertanggung +'" />';
            el += '<td>';
            el += '<a onclick="update_row_dependent(this,\''+ modal_nama +'\',\''+ modal_hubungan +'\',\''+ modal_contact +'\',\''+ modal_tempat_lahir +'\',\''+ modal_tanggal_lahir +'\',\''+ modal_tanggal_meninggal +'\',\''+ modal_jenjang_pendidikan +'\',\''+ modal_pekerjaan +'\',\''+ modal_tertanggung +'\')" class="btn btn-default btn-xs"><i class="fa fa-edit"></i></a>';
            el += '<a onclick="delete_row_dependent(this)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>';
            el += '</td>';

            var act = $("input[name='action_dependent']").val();
            if(act == 'update')
            {
                $(el_dependent).parent().parent().remove();

                $("input[name='action_dependent']").val('insert')
            }

            $('.dependent_table').append(el);
            $('#modal_dependent').modal('hide');

            $('.frm-modal-dependent').trigger('reset');
        });

        function delete_row_dependent(el)
        {
            if(confirm('Delete this data?'))
            {
                $(el).parent().parent().remove();
            }
        }

        $("#add_modal_education").click(function(){

            var el = '<tr>';
            var modal_pendidikan            = $('.modal-pendidikan').val();
            var modal_fakultas              = $('.modal-fakultas').val();
            var modal_tahun_awal            = $('.modal-tahun_awal').val();
            var modal_tahun_akhir           = $('.modal-tahun_akhir').val();
            var modal_jurusan               = $('.modal-jurusan').val();
            var modal_nilai                 = $('.modal-nilai').val();
            var modal_kota                  = $('.modal-kota').val();

            var id = $("input[name='id_education']").val();

            if(id != "")
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-education') }}',
                    data: {'id' : id, 'pendidikan' : modal_pendidikan, 'tahun_awal': modal_tahun_awal, 'tahun_akhir': modal_tahun_akhir, 'fakultas': modal_fakultas, 'jurusan' : modal_jurusan, 'nilai' : modal_nilai, 'kota' : modal_kota, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {

                        $("input[name='id_education']").val("");

                        window.location.href = '{{ route('administrator.karyawan.edit', $data->id) }}';
                    }
                });

                return false;
            }

            el += '<td>'+ (parseInt($('.education_table tr').length) + 1 )  +'</td>';
            el +='<td>'+ modal_pendidikan +'</td>';
            el +='<td>'+ modal_tahun_awal +'</td>';
            el +='<td>'+ modal_tahun_akhir +'</td>';
            el +='<td>'+ modal_fakultas +'</td>';
            el +='<td>'+ modal_jurusan +'</td>';
            el +='<td>'+ modal_nilai +'</td>';
            el +='<td>'+ modal_kota +'</td>';
            el +='<input type="hidden" name="education[pendidikan][]" value="'+ modal_pendidikan +'" />';
            el +='<input type="hidden" name="education[tahun_awal][]" value="'+ modal_tahun_awal +'" />';
            el +='<input type="hidden" name="education[tahun_akhir][]" value="'+ modal_tahun_akhir +'" />';
            el +='<input type="hidden" name="education[fakultas][]" value="'+ modal_fakultas +'" />';
            el +='<input type="hidden" name="education[jurusan][]" value="'+ modal_jurusan +'" />';
            el +='<input type="hidden" name="education[nilai][]" value="'+ modal_nilai +'" />';
            el +='<input type="hidden" name="education[kota][]" value="'+ modal_kota +'" />';
            el +='<td><a class="btn btn-default btn-xs" onclick="update_row_education(this,\''+ modal_pendidikan +'\',\''+ modal_tahun_awal +'\',\''+ modal_tahun_akhir +'\',\''+ modal_fakultas +'\',\''+ modal_jurusan +'\', \''+ modal_nilai +'\',\''+ modal_kota +'\')"><i class="fa fa-edit"></i></a>';
            el +='<a class="btn btn-danger btn-xs" onclick="delete_row_dependent(this)"><i class="fa fa-trash"></i></a></td>';
            $('.education_table').append(el);

            var act = $("input[name='action_education']").val();
            if(act == 'update')
            {
                $(el_education).parent().parent().remove();

                $("input[name='action_education']").val('insert')
            }

            $('#modal_education').modal('hide');
            $('form.frm-modal-education').trigger('reset');
        });

        $("#add_modal_certification").click(function(){

            var el = '<tr>';
            var modal_name                  = $('.modal-name').val();
            var modal_date                  = $('.modal-date').val();
            var modal_organizer             = $('.modal-organizer').val();
            var modal_certificate_number    = $('.modal-certificate_number').val();
            var modal_score                 = $('.modal-score').val();
            var modal_description           = $('.modal-description').val();
            var modal_certificate_photo    = $('.modal-certificate_photo').val();

            var id = $("input[name='id_certification']").val();

            data = new FormData();
            data.append('certificate_photo', $('.modal-certificate_photo')[0].files[0]);
            data.append('_token', $("meta[name='csrf-token']").attr('content'));
            data.append('name', modal_name)
            data.append('date', modal_date)
            data.append('organizer', modal_organizer)
            data.append('certificate_number', modal_certificate_number)
            data.append('score', modal_score)
            data.append('description', modal_description)
            data.append('id', id)
            data.append('user_id', $('#idUser').val())

            if(id != "")
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-certification') }}',
                    //mimeType: "multipart/form-data",
                    data: data,
                    mimeType: "multipart/form-data",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {

                        $("input[name='id_certification']").val("");

                        window.location.href = '{{ route('administrator.karyawan.edit', $data->id) }}';
                    }
                });
                return false;
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.add-certification') }}',
                    //mimeType: "multipart/form-data",
                    data: data,
                    mimeType: "multipart/form-data",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        window.location.href = '{{ route('administrator.karyawan.edit', $data->id) }}';
                    }
                });
                return false;
            }

            el +='<td>'+ (parseInt($('.certification_table tr').length) + 1 )  +'</td>';
            el +='<td>'+ modal_name +'</td>';
            el +='<td>'+ modal_date +'</td>';
            el +='<td>'+ modal_organizer +'</td>';
            el +='<td>'+ modal_certificate_number +'</td>';
            el +='<td>'+ modal_score +'</td>';
            el +='<td>'+ modal_description +'</td>';
            el +='<td>'+ modal_certificate_photo +'</td>';
            el +='<input type="hidden" name="certification[name][]" value="'+ modal_name +'" />';
            el +='<input type="hidden" name="certification[date][]" value="'+ modal_date +'" />';
            el +='<input type="hidden" name="certification[organizer][]" value="'+ modal_organizer +'" />';
            el +='<input type="hidden" name="certification[certificate_number][]" value="'+ modal_certificate_number +'" />';
            el +='<input type="hidden" name="certification[score][]" value="'+ modal_score +'" />';
            el +='<input type="hidden" name="certification[description][]" value="'+ modal_description +'" />';
            el +='<input type="hidden" name="certificate_photo[]" value="'+ $('.modal-certificate_photo').val() +'" />';
            el +='<td><a class="btn btn-default btn-xs" onclick="update_row_certification(this,\''+ modal_name +'\',\''+ modal_date +'\',\''+ modal_organizer +'\',\''+ modal_certificate_number +'\',\''+ modal_score +'\', \''+ modal_description +'\', \''+ modal_certificate_photo +'\')"><i class="fa fa-edit"></i></a>';
            el +='<a class="btn btn-danger btn-xs" onclick="delete_row_cetification(this)"><i class="fa fa-trash"></i></a></td>';
            $('.certification_table').append(el);

            var act = $("input[name='action_certification']").val();
            if(act == 'update')
            {
                $(el_certification).parent().parent().remove();

                $("input[name='action_certification']").val('insert')
            }

            $('#modal_certification').modal('hide');
            $('form.frm-modal-certification').trigger('reset');
        });

        function delete_row_certification(el)
        {
            if(confirm('Delete this data?'))
            {
                $(el).parent().parent().remove();
            }
        }

        $("#add_modal_contract").click(function(){

            var el = '<tr>';
            var modal_number                  = $('.modal-number').val();
            var modal_type                  = $('.modal-contract_type').val();
            var modal_start_date                  = $('.modal-contract_start_date').val();
            var modal_end_date                  = $('.modal-contract_end_date').val();
            var modal_date_contract                = $('.modal-date_contract').val();
            var modal_contract_sent           = $('.modal-contract_sent').val();
            var modal_return_contract    = $('.modal-return_contract').val();
            var modal_file_contract    = $('.modal-file_contract').val();

            var id = $("input[name='id_contract']").val();

            data = new FormData();
            data.append('file_contract', $('.modal-file_contract')[0].files[0]);
            data.append('_token', $("meta[name='csrf-token']").attr('content'));
            data.append('number', modal_number)
            data.append('type', modal_type)
            data.append('start_date', modal_start_date)
            data.append('end_date', modal_end_date)
            data.append('date', modal_date_contract)
            data.append('contract_sent', modal_contract_sent)
            data.append('return_contract', modal_return_contract)
            data.append('id', id)
            data.append('user_id', $('#idUser').val())

            if(id != "")
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-contract') }}',
                    //mimeType: "multipart/form-data",
                    data: data,
                    mimeType: "multipart/form-data",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {

                        console.log(data);
                        window.location.href = data.url
                    }
                });
                return false;
            }
            else{
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.add-contract') }}',
                    //mimeType: "multipart/form-data",
                    data: data,
                    mimeType: "multipart/form-data",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        window.location.href = data.url;
                    }
                });
                return false;
            }

            el +='<td>'+ (parseInt($('.contract_table tr').length) + 1 )  +'</td>';
            el +='<td>'+ modal_number +'</td>';
            el +='<td>'+ modal_date_contract +'</td>';
            el +='<td>'+ modal_contract_sent +'</td>';
            el +='<td>'+ modal_return_contract +'</td>';
            el +='<td>'+ modal_file_contract +'</td>';
            el +='<input type="hidden" name="contract[number][]" value="'+ modal_number +'" />';
            el +='<input type="hidden" name="contract[date][]" value="'+ modal_date_contract +'" />';
            el +='<input type="hidden" name="contract[contract_sent][]" value="'+ modal_contract_sent +'" />';
            el +='<input type="hidden" name="contract[return_contract][]" value="'+ modal_return_contract +'" />';
            el +='<td><a class="btn btn-default btn-xs" onclick="update_row_contract(this,\''+ modal_number +'\',\''+ modal_date_contract +'\',\''+ modal_contract_sent +'\',\''+ modal_return_contract +'\',\''+ modal_file_contract +'\')"><i class="fa fa-edit"></i></a>';
            el +='<a class="btn btn-danger btn-xs" onclick="delete_row_contract(this)"><i class="fa fa-trash"></i></a></td>';
            $('.contract_table').append(el);

            var act = $("input[name='action_contract']").val();
            if(act == 'update')
            {
                $(el_contract).parent().parent().remove();

                $("input[name='action_contract']").val('insert')
            }

            $('#modal_contract').modal('hide');
            $('form.frm-modal-contract').trigger('reset');  
        });

        function delete_row_contract(el)
        {
            if(confirm('Delete this data?'))
            {
                $(el).parent().parent().remove();
            }
        }

        $("#btn_modal_dependent").click(function(){

            $('#modal_dependent input, #modal_dependent textarea, #modal_dependent select, #modal_dependent radio').val("");
            $('#modal_dependent').modal('show');

        });

        $("#btn_modal_education").click(function(){

            $('#modal_education input, #modal_education textarea, #modal_education select, #modal_education radio').val("");
            $('#modal_education').modal('show');

        });

        $("#btn_modal_certification").click(function(){

            $('#modal_certification input, #modal_certification textarea, #modal_certification select, #modal_certification radio').val("");
            $("#modal-certificate_photo").val('')
            $(".preview_certificate").hide();
            $('#modal_certification').modal('show');

        });

        $("#btn_modal_contract").click(function(){

            $('#modal_contract input, #modal_contract textarea, #modal_contract select, #modal_contract radio').val("");
            $("#modal-file_contract").val('')
            $(".preview_contract").hide();
            $('#modal_contract').modal('show');

        });

        function get_kabupaten(el)
        {
            var id = $(el).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-kabupaten-by-provinsi') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value="">Choose District</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id_kab +"\">"+ v.nama +"</option>";
                    });

                    $(el).parent().find('select').html(html_);
                }
            });
        }

        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });


        $("select[name='division_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-department-by-division') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value=""> Choose Department</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id +"\">"+ v.name +"</option>";
                    });

                    $("select[name='department_id'").html(html_);
                }
            });
        });

        $("select[name='department_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-section-by-department') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value=""> Choose Section</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id +"\">"+ v.name +"</option>";
                    });

                    $("select[name='section_id'").html(html_);
                }
            });
        });

        $("select[name='provinsi_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-kabupaten-by-provinsi') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value=""> Choose Districts</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id_kab +"\">"+ v.nama +"</option>";
                    });

                    $("select[name='kabupaten_id'").html(html_);
                }
            });
        });

        $("select[name='kabupaten_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.get-kecamatan-by-kabupaten') }}',
                    data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {

                        var html_ = '<option value=""> Choose Sub-District</option>';

                        $(data.data).each(function(k, v){
                            html_ += "<option value=\""+ v.id_kec +"\">"+ v.nama +"</option>";
                        });

                        $("select[name='kecamatan_id'").html(html_);
                    }
            });
        });

        $("select[name='kecamatan_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.get-kelurahan-by-kecamatan') }}',
                    data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {

                        var html_ = '<option value=""> Choose Sub-District</option>';

                        $(data.data).each(function(k, v){
                            html_ += "<option value=\""+ v.id_kel +"\">"+ v.nama +"</option>";
                        });

                        $("select[name='kelurahan_id']").html(html_);
                    }
            });
        });
        $("#organisasi_status").on('change',function(){
            if(!$(this).val() || $(this).val()=='Permanent'){
                $('#resign_container').removeClass('hidden');
                $('#contract_container').addClass('hidden');
                $("select[name='status_contract']").val('');
                $("input[name='start_date_contract']").datepicker('setDate','');
                $("input[name='end_date_contract']").datepicker('setDate','');
            }
            else{
                $('#contract_container').removeClass('hidden');
                $('#resign_container').addClass('hidden');
                $("#check_status").prop('checked', false).trigger('change');
                if ($("input[name='end_date_contract']").val()) {
                    $("input[name='inactive_date']:not(:disabled)").datepicker('setDate', $("input[name='end_date_contract']").val());
                }
            }
        });
        $("#modal-contract_type").on('change',function(){
            if(!$(this).val() || $(this).val()=='Amendment'){
                $('#form_contract_start_date').addClass('hidden');
            }
            else{
                $('#form_contract_start_date').removeClass('hidden');
            }
        });
        $("#check_status").change(function() {
            if(this.checked) {
                //Do stuff
                $("input[name='resign_date']").removeClass('hidden');
            }
            else{
                $("input[name='resign_date']").addClass('hidden');
                $("input[name='resign_date']").datepicker('setDate','');
            }
        });
        $("#foreigners_status").change(function() {
            if(this.checked) {
                $("select[name='payroll_country_id']").removeClass('hidden');
            } else {
                $("select[name='payroll_country_id']").addClass('hidden');
                $("select[name='payroll_country_id']").val('');
            }
        });
        $("input[name='resign_date']").datepicker().on("change", function (e) {
            if (!$("input[name='resign_date']").val()) {
                $("#check_status").prop('checked', false)
                $("input[name='resign_date']").addClass('hidden')
            }
            $("input[name='inactive_date']:not(:disabled)").datepicker('setDate', e.target.value);
        });
        $("input[name='end_date_contract']").datepicker().on("change", function (e) {
            $("input[name='inactive_date']:not(:disabled)").datepicker('setDate', e.target.value);
        });
    </script>
@endsection

@endsection
