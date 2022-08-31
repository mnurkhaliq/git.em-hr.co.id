@extends('layouts.administrator')

@section('title', 'Timesheet Employee')

@section('sidebar')

@endsection

@section('content')

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Timesheet Employee</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Timesheet Employee</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Detail Timesheet</h3>
                        <hr />
                        <br />
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

                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label class="col-md-12">NIK / Employee Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $data->user->nik .' - '. $data->user->name  }}" disabled />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Position</label>
                            <div class="col-md-6">
                                <input type="text" disabled class="form-control jabatan" value="{{ isset($data->user->structure->position) ? $data->user->structure->position->name:''}}{{ isset($data->user->structure->division) ? ' - '. $data->user->structure->division->name:''}}{{ isset($data->user->structure->title) ? ' - '. $data->user->structure->title->name:'' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Period Start Date</label>
                            <label class="col-md-9">Period End Date</label>
                            <div class="col-md-3">
                                <input type="text" disabled class="form-control" value="{{ $data->start_date }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" disabled class="form-control" value="{{ $data->end_date }}">
                            </div>
                        </div>
                       
                        <div class="clearfix"></div>

                        <div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>CATEGORY</th>
                                        <th>ACTIVITY</th>
                                        <th>DESCRIPTION</th>
                                        <th>DATE</th>
                                        <th>DAY</th>
                                        <th>START TIME</th>
                                        <th>END TIME</th>
                                        <th>DURATION</th>
                                        <th>MANAGE</th>
                                    </tr>
                                </thead>
                                <tbody class="table-content-lembur">
                                    @foreach($data->timesheetPeriodTransaction as $no => $item)
                                    <tr>
                                        <input type="hidden" name="transactions[{{ $no }}][id]" class="form-control"  value="{{ $item->id }}" disabled>
                                        <td>{{ $no+1 }}</td>
                                        <td><input type="text" disabled class="form-control" value="{{ $item->timesheet_category_id ? $item->timesheetCategory->name : 'Other Category' }}"></td>
                                        <td><input type="text" disabled class="form-control" value="{{ $item->timesheet_activity_id ? $item->timesheetActivity->name : $item->timesheet_activity_name }}"></td>
                                        <td><input type="text" disabled class="form-control" value="{{ $item->description }}" /></td>
                                        <td><input type="text" disabled class="form-control" value="{{ $item->date }}" /></td>
                                        <td><input type="text" disabled class="form-control" value="{{ date('l', strtotime($item->date)) }}" /></td>
                                        <td><input type="text" disabled class="form-control" value="{{ $item->start_time }}" /></td>
                                        <td><input type="text" disabled class="form-control" value="{{ $item->end_time }}" /></td>
                                        <td><input type="text" disabled class="form-control" value="{{ $item->total_time }}" /></td>
                                        <td>
                                            <div class="radio reject icheck-danger col-xs-6 p-0">
                                                <input class="approval" type="radio" id="danger-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][is_approved]" disabled {{ $item->status == 3 ? 'checked' : '' }} value="0">
                                                <label for="danger-{{ $no }}"></label>
                                            </div>
                                            <div class="radio icheck-success col-xs-6 p-0 text-right">
                                                <input class="approval" type="radio" id="success-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][is_approved]" disabled {{ $item->status == 2 ? 'checked' : '' }} value="1">
                                                <label for="success-{{ $no }}"></label>
                                            </div>
                                        </td>
                                        <td></td>
                                        @if($item->status == 3)
                                            <td style="background-color: #ff7676;"></td>
                                        @elseif($item->status == 2)
                                            <td style="background-color: #53e69d;"></td>
                                        @else
                                            <td style="background-color: #ffa82b;"></td>
                                        @endif
                                    </tr>
                                    @if($item->approval_note)
                                    <tr class="approval-note">
                                        <td></td>
                                        <td>APPROVAL NOTE</td>
                                        <td colspan="7">
                                            <input type="text" name="transactions[{{ $no }}][note]" disabled class="form-control" value="{{ $item->approval_note }}" />
                                        </td>
                                        <td colspan="2"></td>
                                        @if($item->status == 3)
                                            <td style="background-color: #ff7676;"></td>
                                        @elseif($item->status == 2)
                                            <td style="background-color: #53e69d;"></td>
                                        @else
                                            <td style="background-color: #ffa82b;"></td>
                                        @endif
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="clearfix"></div>
                        <br />

                        <div class="form-group">                            
                            <input type="hidden" name="id" value="{{ $data->id }}">

                            <div class="clearfix"></div>
                            <br />

                            <a href="{{ route('administrator.timesheetCustom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>    
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@section('footer-script')
<link href="https://www.cssscript.com/demo/pure-css-checkbox-radio-button-replacement-bootstrap-icheck/icheck-bootstrap.css" rel="stylesheet" type="text/css">
<style>
    .table-hover>tbody>tr:hover {
        background-color: white !important;
    }
    .approval-note > td {
        border: 0px !important;
        vertical-align: middle !important;
        padding-top: 0 !important;
    }
    .radio label::after {
        background-color: rgba(255, 255, 255, 0) !important;
    }
    .radio>input[type=radio]:first-child:not(:checked)+label::before, .checkbox>input[type=checkbox]:first-child:not(:checked)+label::before, .radio>input[type=radio]:first-child:not(:checked)+input[type=hidden]+label::before, .checkbox>input[type=checkbox]:first-child:not(:checked)+input[type=hidden]+label::before, .radio>input[type=radio]:first-child:checked+label::before, .checkbox>input[type=checkbox]:first-child:checked+label::before, .radio>input[type=radio]:first-child:checked+input[type=hidden]+label::before, .checkbox>input[type=checkbox]:first-child:checked+input[type=hidden]+label::before {
        position: inherit !important;
    }
    .reject>input[type=radio]:first-child:checked+label::before {
        content: "\e014" !important;
    }
    .radio+.radio, .checkbox+.checkbox {
        margin-top: 0 !important;
    }
    .radio {
        padding: 13% 0!important;
    }
    .radio>input[type=radio]:first-child:not(:checked)+label::before, .radio>input[type=radio]:first-child:not(:checked)+input[type=hidden]+label::before, .radio>input[type=radio]:first-child:checked+label::before, .radio>input[type=radio]:first-child:checked+input[type=hidden]+label::before {
        border-radius: 0 !important;
    }
</style>
@endsection

@endsection
