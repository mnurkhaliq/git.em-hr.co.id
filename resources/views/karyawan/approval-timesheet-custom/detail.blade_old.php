@extends('layouts.karyawan')

@section('title', 'Approval Timesheet')

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
                <h4 class="page-title">Form Approval Timesheet</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Approval Timesheet</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.approval.timesheet-custom.proses') }}" id="form-timesheet" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Timesheet Detail Approval</h3>
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
                         <?php
                            $readonly = ''; 
                            if($history->is_approved != NULL)
                            {
                                $readonly = ' readonly="true"'; 
                            }
                        ?>
                        
                        <div class="form-group">
                            <label class="col-md-12">NIK / Employee Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $data->user->nik .' - '. $data->user->name  }}" readonly="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Position</label>
                            <div class="col-md-6">
                                <input type="text" readonly="true" class="form-control" value="{{ isset($data->user->structure->position) ? $data->user->structure->position->name:''}}{{ isset($data->user->structure->division) ? '-'. $data->user->structure->division->name:'' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Period Start Date</label>
                            <label class="col-md-9">Period End Date</label>
                            <div class="col-md-3">
                                <input type="text" readonly="true" class="form-control" value="{{ $data->start_date }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" readonly="true" class="form-control" value="{{ $data->end_date }}">
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
                                    @foreach($data->timesheetTransaction as $no => $item)
                                    <tr>
                                        <input type="hidden" name="transactions[{{ $no }}][id]" class="form-control"  value="{{ $item->id }}" readonly="true">
                                        <td>{{ $no+1 }}</td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->timesheet_category_id ? $item->timesheetCategory->name : 'Other Category' }}"></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->timesheet_activity_id ? $item->timesheetActivity->name : $item->timesheet_activity_name }}"></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->description }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->date }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ date('l', strtotime($item->date)) }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->start_time }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->end_time }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->total_time }}" /></td>
                                        <td>
                                            <div class="radio reject icheck-danger col-xs-6 p-0">
                                                <input class="approval" type="radio" id="danger-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][is_approved]" {{ $item->status != 1 ? 'readonly' : '' }} {{ (isset(old('transactions')[$no]['is_approved']) && old('transactions')[$no]['is_approved'] === "0") || $item->status == 3 ? 'checked' : '' }} value="0">
                                                <label for="danger-{{ $no }}"></label>
                                            </div>
                                            <div class="radio icheck-success col-xs-6 p-0 text-right">
                                                <input class="approval" type="radio" id="success-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][is_approved]" {{ $item->status != 1 ? 'readonly' : '' }} {{ (isset(old('transactions')[$no]['is_approved']) && old('transactions')[$no]['is_approved'] === "1") || $item->status == 2 ? 'checked' : '' }} value="1">
                                                <label for="success-{{ $no }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    @php($approvalNote = $history->historyApprovalTimesheetNote()->where('timesheet_transaction_id', $item->id)->first())
                                    @if($approvalNote && $approvalNote->note && !isset(old('transactions')[$no]['is_approved']))
                                    <tr class="approval-note" id="approval-note-{{ $no }}">
                                        <td></td>
                                        <td>APPROVAL NOTE</td>
                                        <td colspan="7">
                                            <input type="text" name="transactions[{{ $no }}][note]" {{ $item->status != 1 ? 'readonly' : '' }} class="form-control" value="{{ $approvalNote->note }}" />
                                        </td>
                                    </tr>
                                    @else
                                    <tr class="approval-note" id="approval-note-{{ $no }}" style="display: {{ !isset(old('transactions')[$no]['is_approved']) || (isset(old('transactions')[$no]['is_approved']) && old('transactions')[$no]['is_approved'] === "1") ? 'none' : '' }}">
                                        <td></td>
                                        <td>APPROVAL NOTE</td>
                                        <td colspan="7">
                                            <input type="text" name="transactions[{{ $no }}][note]" class="form-control" value="{{ old('transactions')[$no]['note'] }}" />
                                        </td>
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

                            <a href="{{ route('karyawan.approval.timesheet-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            @if($history->is_approved === NULL and $data->status < 2)
                            <a class="btn btn-sm btn-primary waves-effect waves-light m-r-10" id="btn_submit">Submit</a>
                            @endif

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
<script type="text/javascript">
    $(".approval[readonly]").closest("div").find("label").css("opacity", "0.5")

    $(".approval[readonly]").click(function() {
        return false
    })

    $(".approval").change(function() {
        if($(this).val() == 1) {
            $('#approval-note-'+$(this).data('index')).hide()
            $('#approval-note-'+$(this).data('index')).find('input').val('')
        } else {
            $('#approval-note-'+$(this).data('index')).show()
        }
    });

    $("#btn_submit").click(function(){
        bootbox.confirm('Submit approval timesheet?', function(result){
            if(result) {
                submit = true;
                $('#form-timesheet').submit();
            }
        });
    });

    var submit = false;
    @if($history->is_approved === NULL and $data->status < 2)
    $(window).bind('beforeunload', function(){
        if(!submit)
            return 'Changes you made may not be saved.';
    });
    @endif
</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
