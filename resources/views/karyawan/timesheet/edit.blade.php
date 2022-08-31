@extends('layouts.karyawan')

@section('title', 'Timesheet')

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
                <h4 class="page-title">Form Timesheet</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Timesheet</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form-timesheet" action="{{ route('karyawan.timesheet.store') }}" enctype="multipart/form-data" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Timesheet Edit Form</h3>
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
                                <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name }}" readonly="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Position</label>
                            <div class="col-md-6">
                                <input type="text" readonly="true" class="form-control jabatan" 
                                value="{{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Period Start Date</label>
                            <label class="col-md-9">Period End Date</label>
                            <div class="col-md-3">
                                <input type="text" readonly="true" class="form-control" name="start_date" value="{{ $data->start_date }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" readonly="true" class="form-control" name="end_date" value="{{ $data->end_date }}">
                            </div>
                        </div>
                        
                        <div class="clearfix"></div>

                        <div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th width="10%">CATEGORY</th>
                                        <th width="10%">ACTIVITY</th>
                                        <th>ACTIVITY NAME</th>
                                        <th>DESCRIPTION</th>
                                        <th width="10%">DATE <span class="text-danger">*</span></th>
                                        <th width="10%">DAY</th>
                                        <th width="7%">START TIME <span class="text-danger">*</span></th>
                                        <th width="7%">END TIME <span class="text-danger">*</span></th>
                                        <th width="7%">DURATION</th>
                                        <th>MANAGE</th>
                                    </tr>
                                </thead>
                                <tbody class="table-content-timesheet">
                                    @if(!old('transactions'))
                                    @foreach($data->timesheetPeriodTransaction as $no => $item)
                                    <tr>
                                        <input type="hidden" name="transactions[{{ $no }}][id]" class="form-control" value="{{ $item->id }}" readonly="true">
                                        <input type="hidden" name="transactions[{{ $no }}][status]" class="form-control" value="{{ $item->status }}" readonly="true">
                                        <td>{{ $no+1 }}</td>
                                        <td>
                                            <select class="form-control category" id="category-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][timesheet_category_id]" {{ $item->status == 3 || $item->status == 4 ? '' : 'disabled' }}>
                                                <option value="">- Select Category -</option>
                                                @foreach(($item->status == 3 || $item->status == 4 ? getAvailableTimesheetCategory() : getTimesheetCategory()) as $val)
                                                <option value="{{ $val->id }}" {{ $item->timesheet_category_id == $val->id ? 'selected' : '' }}>{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control activity" id="activity-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][timesheet_activity_id]" {{ ($item->status == 3 || $item->status == 4) && getAvailableTimesheetCategory()->where('id', $item->timesheet_category_id)->first() ? '' : 'disabled' }}>
                                                <option value="">Other Activity</option>
                                                @foreach(($item->status == 3 || $item->status == 4 ? getAvailableTimesheetActivity($item->timesheet_category_id) : getTimesheetActivity($item->timesheet_category_id)) as $val)
                                                <option value="{{ $val->id }}" {{ $item->timesheet_activity_id == $val->id ? 'selected' : '' }}>{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" id="activity_name-{{ $no }}" name="transactions[{{ $no }}][timesheet_activity_name]" {{ ($item->status == 3 || $item->status == 4) && !getAvailableTimesheetActivity($item->timesheet_category_id)->where('id', $item->timesheet_activity_id)->first() && getAvailableTimesheetCategory()->where('id', $item->timesheet_category_id)->first() ? '' : 'readonly' }} class="form-control activity_name" value="{{ $item->timesheet_activity_id ? $item->timesheetActivity->name : $item->timesheet_activity_name }}"></td>
                                        <td><input type="text" name="transactions[{{ $no }}][description]" {{ $item->status == 3 || $item->status == 4 ? '' : 'readonly' }} class="form-control" value="{{ $item->description }}" /></td>
                                        <td><input type="text" data-index="{{ $no }}" name="transactions[{{ $no }}][date]" {{ $item->status == 3 || $item->status == 4 ? '' : 'readonly' }} class="form-control date" value="{{ $item->date }}" /></td>
                                        <td><input type="text" id="day-{{ $no }}" name="transactions[{{ $no }}][day]" readonly class="form-control" value="{{ date('l', strtotime($item->date)) }}" /></td>
                                        <td><input type="text" id="start_time-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][start_time]" {{ $item->status == 3 || $item->status == 4 ? '' : 'readonly' }} class="form-control time-picker" value="{{ $item->start_time }}" /></td>
                                        <td><input type="text" id="end_time-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][end_time]" {{ $item->status == 3 || $item->status == 4 ? '' : 'readonly' }} class="form-control time-picker" value="{{ $item->end_time }}" /></td>
                                        <td><input type="text" id="total_time-{{ $no }}" name="transactions[{{ $no }}][total_time]" readonly="true" class="form-control" value="{{ $item->total_time }}" /></td>
                                        @if($item->status == 4 && !$item->approval_note)
                                        <td class="delete"><a class="btn btn-danger btn-xs" onclick="hapus_(this)"><i class="fa fa-trash"></i></a></td>
                                        @else
                                        <td></td>
                                        @endif
                                        @if($item->status == 3)
                                            <td style="background-color: #ff7676;"></td>
                                        @elseif($item->status == 2)
                                            <td style="background-color: #53e69d;"></td>
                                        @elseif($item->status == 1)
                                            <td style="background-color: #ffa82b;"></td>
                                        @else
                                            <td style="background-color: #e4e7ea;"></td>
                                        @endif
                                    </tr>
                                    @if($item->approval_note)
                                    <tr class="approval-note">
                                        <td></td>
                                        <td>APPROVAL NOTE</td>
                                        <td colspan="8">
                                            <input type="text" name="transactions[{{ $no }}][note]" readonly class="form-control" value="{{ $item->approval_note }}" />
                                        </td>
                                        <td></td>
                                        @if($item->status == 3)
                                            <td style="background-color: #ff7676;"></td>
                                        @elseif($item->status == 2)
                                            <td style="background-color: #53e69d;"></td>
                                        @elseif($item->status == 1)
                                            <td style="background-color: #ffa82b;"></td>
                                        @else
                                            <td style="background-color: #e4e7ea;"></td>
                                        @endif
                                    </tr>
                                    @endif
                                    @endforeach
                                    @else
                                    @foreach(old('transactions') as $no => $item)
                                    <tr>
                                        <input type="hidden" name="transactions[{{ $no }}][id]" class="form-control" value="{{ $item['id'] }}" readonly="true">
                                        <input type="hidden" name="transactions[{{ $no }}][status]" class="form-control" value="{{ $item['status'] }}" readonly="true">
                                        <td>{{ $no+1 }}</td>
                                        <td>
                                            <select class="form-control category" id="category-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][timesheet_category_id]" {{ $item['status'] == 3 || $item['status'] == 4 ? '' : 'disabled' }}>
                                                <option value="">- Select Category -</option>
                                                @foreach(($item['status'] == 3 || $item['status'] == 4 ? getAvailableTimesheetCategory() : getTimesheetCategory()) as $val)
                                                <option value="{{ $val->id }}" {{ $item['timesheet_category_id'] == $val->id ? 'selected' : '' }}>{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control activity" id="activity-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][timesheet_activity_id]" {{ ($item['status'] == 3 || $item['status'] == 4) && $item['timesheet_category_id'] ? '' : 'disabled' }}>
                                                <option value="">Other Activity</option>
                                                @foreach(($item['status'] == 3 || $item['status'] == 4 ? getAvailableTimesheetActivity($item['timesheet_category_id']) : getTimesheetActivity($item['timesheet_category_id'])) as $val)
                                                <option value="{{ $val->id }}" {{ $item['timesheet_activity_id'] == $val->id ? 'selected' : '' }}>{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" id="activity_name-{{ $no }}" name="transactions[{{ $no }}][timesheet_activity_name]" {{ ($item['status'] == 3 || $item['status'] == 4) && !$item['timesheet_activity_id'] && $item['timesheet_category_id'] ? '' : 'readonly' }} class="form-control activity_name" value="{{ $item['timesheet_activity_name'] }}"></td>
                                        <td><input type="text" name="transactions[{{ $no }}][description]" {{ $item['status'] == 3 || $item['status'] == 4 ? '' : 'readonly' }} class="form-control" value="{{ $item['description'] }}" /></td>
                                        <td><input type="text" data-index="{{ $no }}" name="transactions[{{ $no }}][date]" {{ $item['status'] == 3 || $item['status'] == 4 ? '' : 'readonly' }} class="form-control date" value="{{ $item['date'] }}" /></td>
                                        <td><input type="text" id="day-{{ $no }}" name="transactions[{{ $no }}][day]" readonly class="form-control" value="{{ $item['date'] ? date('l', strtotime($item['date'])) : '' }}" /></td>
                                        <td><input type="text" id="start_time-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][start_time]" {{ $item['status'] == 3 || $item['status'] == 4 ? '' : 'readonly' }} class="form-control time-picker" value="{{ $item['start_time'] }}" /></td>
                                        <td><input type="text" id="end_time-{{ $no }}" data-index="{{ $no }}" name="transactions[{{ $no }}][end_time]" {{ $item['status'] == 3 || $item['status'] == 4 ? '' : 'readonly' }} class="form-control time-picker" value="{{ $item['end_time'] }}" /></td>
                                        <td><input type="text" id="total_time-{{ $no }}" name="transactions[{{ $no }}][total_time]" readonly="true" class="form-control" value="{{ $item['total_time'] }}" /></td>
                                        @if($item['status'] == 4 && !isset($item['note']))
                                        <td class="delete"><a class="btn btn-danger btn-xs" onclick="hapus_(this)"><i class="fa fa-trash"></i></a></td>
                                        @else
                                        <td></td>
                                        @endif
                                        @if($item['status'] == 3)
                                            <td style="background-color: #ff7676;"></td>
                                        @elseif($item['status'] == 2)
                                            <td style="background-color: #53e69d;"></td>
                                        @elseif($item['status'] == 1)
                                            <td style="background-color: #ffa82b;"></td>
                                        @else
                                            <td style="background-color: #e4e7ea;"></td>
                                        @endif
                                    </tr>
                                    @if(isset($item['note']))
                                    <tr class="approval-note">
                                        <td></td>
                                        <td>APPROVAL NOTE</td>
                                        <td colspan="8">
                                            <input type="text" name="transactions[{{ $no }}][note]" readonly class="form-control" value="{{ $item['note'] }}" />
                                        </td>
                                        <td></td>
                                        @if($item['status'] == 3)
                                            <td style="background-color: #ff7676;"></td>
                                        @elseif($item['status'] == 2)
                                            <td style="background-color: #53e69d;"></td>
                                        @elseif($item['status'] == 1)
                                            <td style="background-color: #ffa82b;"></td>
                                        @else
                                            <td style="background-color: #e4e7ea;"></td>
                                        @endif
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <span class="text-danger">(*) Minimum requirements for draft if timesheet exists</span>
                            @if($data->status == 3 || $data->status == 4)
                            <a class="btn btn-info btn-xs pull-right" id="add"><i class="fa fa-plus"></i> Add</a>
                            @endif
                        </div>

                        <div class="clearfix"></div>
                        <br />

                        <div class="form-group">                            
                            <input type="hidden" name="status"  id="status" value="">
                            <input type="hidden" name="id"  id="id" value="{{ $data->id }}">

                            <div class="clearfix"></div>
                            <br />

                            <a href="{{ route('karyawan.timesheet.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            @if($data->status == 3 || $data->status == 4)
                            <a class="btn btn-sm btn-warning waves-effect waves-light m-r-10" id="btn_draft"><i class="fa fa-save"> </i> Save Draft</a>
                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Submit Timesheet</a>
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
    @include('layouts.footer')
</div>
@section('footer-script')
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<style>
    .delete {
        text-align: center;
    }
    .form-control {
        border-radius: 4px !important;
    }
    .table-hover>tbody>tr:hover {
        background-color: white !important;
    }
    .approval-note > td {
        border: 0px !important;
        vertical-align: middle !important;
        padding-top: 0 !important;
    }
</style>
<script>
    function hapus_(el) {
        $(el).parent().parent().remove();
    }

    var index = $('.category').length - 1;

    initRows();
    function initRows() {
        $(".date").datepicker("destroy").datepicker({
            dateFormat:"yy-mm-dd",
            minDate: "{{ $data->start_date }}",
            maxDate: "{{ $data->end_date }}",
            changeMonth: true
        }).off('change').change(function() {
            let idx = $(this).data('index')
            $("#day-"+idx).val($(this).val() ? moment($(this).val()).format('dddd') : '');
        });

        $('.time-picker').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true
        });

        $(".time-picker").off('change').change(function() {
            let idx = $(this).data('index')
            let start = moment($("#start_time-"+idx).val(), "HH:mm");
            let end = moment($("#end_time-"+idx).val(), "HH:mm");

            if(start <= end)
                duration = moment.duration(start.diff(end));
            else
                duration = moment.duration(start.diff(end.add(1, "days")));

            let hours = parseInt(duration.asHours());
            let minutes = parseInt(duration.asMinutes())%60;

            $("#total_time-"+idx).val(moment(hours, "HH").format("HH")+':'+moment(minutes, "mm").format("mm"));
        });

        $(".category").off('change').change(function() {
            let activity = $('#activity-'+$(this).data('index'));
            let idx = $(this).data('index')
            activity.html('');
            activity.append('<option value="" selected>Other Activity</option>');
            activity.change();

            if($(this).val()) {
                activity.removeAttr('disabled');

                $.ajax({
                    url: "{{route('karyawan.timesheet.get-activity')}}",
                    type: 'GET',
                    data: {
                        'id': $(this).val()
                    },
                    success: function(response){
                        $.each(response, function(i, data){
                            $('<option>', {
                                value: data.id,
                                text: data.name
                            }).html(data.name).appendTo('#activity-'+idx)
                        });
                    }
                });
            } else {
                activity.attr('disabled', true);
                $('#activity_name-'+idx).attr('readonly', true)
            }
        });

        $(".activity").off('change').change(function() {
            let idx = $(this).data('index')
            if($(this).val()) {
                $('#activity_name-'+idx).attr('readonly', true)
                $('#activity_name-'+idx).val($('#activity-'+idx+' option:selected').text());
            } else {
                if($('#category-'+idx).val())
                    $('#activity_name-'+idx).removeAttr('readonly')
                $('#activity_name-'+idx).val('');
            }
        });
    }

    $("#add").click(function(){
        index++;

        var html = '<tr>';
            html += '<input type="hidden" name="transactions['+index+'][id]" class="form-control" value="" readonly="true">';
            html += '<input type="hidden" name="transactions['+index+'][status]" class="form-control" value="4" readonly="true">';
            html += '<td>'+(index+1)+'</td>';
            html += '<td>'+
                        '<select class="form-control category" id="category-'+index+'" data-index="'+index+'" name="transactions['+index+'][timesheet_category_id]">'+
                            '<option value="">- Select Category -</option>'+
                        '</select>'+
                    '</td>';
            html += '<td>'+
                        '<select class="form-control activity" id="activity-'+index+'" data-index="'+index+'" name="transactions['+index+'][timesheet_activity_id]" disabled>'+
                            '<option value="">Other Activity</option>'+
                        '</select>'+
                    '</td>';
            html += '<td><input type="text" id="activity_name-'+index+'" name="transactions['+index+'][timesheet_activity_name]" readonly class="form-control activity_name" /></td>';
            html += '<td><input type="text" name="transactions['+index+'][description]" class="form-control" /></td>';
            html += '<td><input type="text" data-index="'+index+'" name="transactions['+index+'][date]" class="form-control date" /></td>';
            html += '<td><input type="text" id="day-'+index+'" name="transactions['+index+'][day]" readonly class="form-control" /></td>';
            html += '<td><input type="text" id="start_time-'+index+'" data-index="'+index+'" name="transactions['+index+'][start_time]" class="form-control time-picker" /></td>';
            html += '<td><input type="text" id="end_time-'+index+'" data-index="'+index+'" name="transactions['+index+'][end_time]" class="form-control time-picker" /></td>';
            html += '<td><input type="text" id="total_time-'+index+'" name="transactions['+index+'][total_time]" readonly class="form-control" value="00:00" /></td>';
            html += '<td class="delete"><a class="btn btn-danger btn-xs" onclick="hapus_(this)"><i class="fa fa-trash"></i></a></td>';
            html += '<td style="background-color: #e4e7ea;"></td>';
            html += '</tr>';

        $('.table-content-timesheet').append(html);

        $.ajax({
            url: "{{route('karyawan.timesheet.get-category')}}",
            type: 'GET',
            data: {
                'id': $(this).val()
            },
            success: function(response){
                $.each(response, function(i, data){
                    $('<option>', {
                        value: data.id,
                        text: data.name
                    }).html(data.name).appendTo('#category-'+index)
                });
            }
        });

        initRows();
    });

    $("#btn_submit").click(function(){
        $('#status').val(1);
        $('select[disabled]').attr('readonly', true)
        $('select[disabled]').removeAttr('disabled');
        bootbox.confirm('Submit timesheet?', function(result){
            if(result) {
                submit = true;
                $('#form-timesheet').submit();
            } else {
                $('select[readonly]').attr('disabled', true)
                $('select[readonly]').removeAttr('readonly');
            }
        });
    });

    $("#btn_draft").click(function(){
        $('#status').val(4);
        $('select[disabled]').attr('readonly', true)
        $('select[disabled]').removeAttr('disabled');
        bootbox.confirm('Save timesheet as draft?', function(result){
            if(result) {
                submit = true;
                $('#form-timesheet').submit();
            } else {
                $('select[readonly]').attr('disabled', true)
                $('select[readonly]').removeAttr('readonly');
            }
        });
    });

    var submit = false;
    @if($data->status == 3 || $data->status == 4)
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
