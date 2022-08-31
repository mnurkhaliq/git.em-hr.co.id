@extends('layouts.administrator')

@section('title', 'Timesheet List')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                <h4 class="page-title">Manage Timesheet List</h4>
            </div>
            <div class="col-lg-10 col-sm-9 col-md-9 col-xs-12">
                <form method="POST" action="{{ route('timesheet.index') }}" id="filter-form" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" value="view">
                    <input type="hidden" name="reset" value="0">
                    <input type="hidden" name="eksport" value="0">

                    <div style="padding-left:0; float: right;">
                        <div class="btn-group m-l-10 m-r-10 pull-right">
                            <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action
                                <i class="fa fa-gear"></i>
                            </a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="javascript:void(0)" onclick="reset_filter()"><i class="fa fa-refresh"></i> Reset Filter </a></li>
                                <li><a href="javascript:void(0)" onclick="eksportTimesheet()"><i class="fa fa-download"></i> Export </a></li>
                            </ul>
                        </div>
                        <button id="filter_view" class="btn btn-default btn-sm btn-outline pull-right"> <i class="fa fa-search-plus"></i></button>
                    </div>
                    <div class="col-md-10 p-0 pull-right">
                        <div class="col-md-2 pull-right">
                            <select name="activity" class="form-control form-control-line" id="activity">
                                <option value="" selected>- Activity -</option>
                                @foreach(getTimesheetActivity(\Session::get('category')) as $item)
                                <option {{ $item->id == \Session::get('activity') ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                <option {{ 'other' == \Session::get('activity') ? 'selected' : '' }} value="other">Other Activity</option>
                            </select>
                        </div>
                        <div class="col-md-2 pull-right">
                            <select name="category" class="form-control form-control-line" id="category">
                                <option value="" selected>- Category -</option>
                                @foreach(getTimesheetCategory() as $item)
                                <option {{ $item->id == \Session::get('category') ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                <option {{ 'other' == \Session::get('category') ? 'selected' : '' }} value="other">Other Category</option>
                            </select>
                        </div>
                        <div class="col-md-2 pull-right">
                            <select name="position" class="form-control form-control-line" id="position">
                                <option value="" selected>- Position -</option>
                                @foreach(getStructureName() as $item)
                                <option {{ $item['id'] == \Session::get('position') ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 pull-right">
                            <input type="text" name="filter_end" class="form-control datepicker form-control-line" id="filter_end" placeholder="End Date" value="{{ \Session::get('filter_end') }}">
                        </div>
                        <div class="col-md-2 pull-right">
                            <input type="text" name="filter_start" class="form-control datepicker form-control-line" id="filter_start" placeholder="Start Date" value="{{ \Session::get('filter_start') }}" />
                        </div>
                        <div class="col-md-2 pull-right">
                            <input type="text" name="timesheet_name" id="nama_nik" class="form-control form-control-line autocomplete-karyawan" placeholder="Nik / Name" value="{{ \Session::get('timesheet_name')}}">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <table id="tableTimesheet" class="data_table_no_pagging table table-background">
                        <thead>
                            <tr>
                                <th rowspan="1">No</th>
                                <th rowspan="1">NIK</th>
                                <th rowspan="1">Name</th>
                                <th rowspan="1">Position</th>
                                <th rowspan="1">Timesheet Category*</th>
                                <th rowspan="1">Timesheet Activity*</th>
                                <th rowspan="1">Description*</th>
                                <th rowspan="1">Date</th>
                                <th rowspan="1">Day</th>
                                <th rowspan="1">Start Time*</th>
                                <th rowspan="1">End Time*</th>
                                <th rowspan="1">Duration*</th>
                                <th rowspan="1">Note*</th>
                                <th rowspan="1">Action</th>
                        </thead>
                        <tbody>
                            <?php $no = $data->firstItem(); ?>
                            @foreach($data as $no => $item)
                            <tr>
                                <td>{{ $no+1 }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->position }}</td>
                                <td>{{ $item->category ?: 'Other Category' }}</td>
                                <td>{{ $item->activity }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->date }}</td>
                                <td>{{ date('l', strtotime($item->date)) }}</td>
                                <td>{{ $item->start_time}}</td>
                                <td>{{ $item->end_time}}</td>
                                <td>{{ $item->total_time}}</td>
                                <td>{{ $item->admin_note }}</td>
                                <td>
                                    <button onclick="editTimesheet('{{ $item->transaction_id }}', '{{ $item->category_id }}', '{{ $item->activity_id }}', '{{ $item->activity }}', '{{ $item->description }}', '{{ $item->start_time }}', '{{ $item->end_time }}', '{{ $item->total_time }}', '{{ $item->admin_note }}')" type="button" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> Edit </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-m-6 pull-left text-left">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries</div>
                    <div class="col-md-6 pull-right text-right">{{ $data->appends($_GET)->render() }}</div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')

    <div id="modal-admin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Edit Timesheet</h4>
                </div>
                <form id="form-timesheet" method="POST" action="{{ route('timesheet.store') }}" autocomplete="off" class="form-horizontal frm-modal-cuti">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-12">Category <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <select required name="modal_category_id" class="form-control form-control-line" id="modal-category_id">
                                    <option value="" selected>- Category -</option>
                                    @foreach(getTimesheetCategory() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                    <option value="other">Other Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="modal-field-activity_id">
                            <label class="col-md-12">Activity <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <select required name="modal_activity_id" class="form-control form-control-line" id="modal-activity_id"></select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;" id="modal-field-activity_name">
                            <label class="col-md-12">Activity Name <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input required type="text" name="modal_activity_name" class="form-control" id="modal-activity_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Description <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <textarea required name="modal_description" class="form-control" id="modal-description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Start Time <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input required type="text" name="modal_start_time" class="form-control time-picker" id="modal-start_time">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">End Time <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input required type="text" name="modal_end_time" class="form-control time-picker" id="modal-end_time">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Duration <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input required readonly type="text" name="modal_total_time" class="form-control" id="modal-total_time">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Note</label>
                            <div class="col-md-12">
                                <textarea name="modal_admin_note" class="form-control" id="modal-admin_note"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="modal_id" id="modal-id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" id="btn_submit" class="btn btn-info btn-sm">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>


@section('js')
<style>
    .clockpicker-popover {
        z-index: 999999 !important;
    }
</style>
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script>
    function eksportTimesheet() {
        $("input[name='eksport']").val(1);
        $("#filter-form").submit();

        $("input[name='eksport']").val(0);
    }

    $("#category").change(function() {
        $.ajax({
            url: "{{route('timesheet.get-activity')}}",
            type: 'GET',
            data: {
                'id': $(this).val()
            },
            success: function(response){
                $("#activity").html('');
                $('#activity').append('<option value="" selected>- Activity -</option>');
                $.each(response, function(i, data){
                    $('<option>', {
                        value: data.id,
                        text: data.name
                    }).html(data.name).appendTo('#activity')
                });
                $('#activity').append('<option value="other">Other Activity</option>');
            }
        });
    });

    $("#modal-category_id").change(function() {
        $("#modal-activity_id").html('');
        $('#modal-activity_id').append('<option value="" selected>- Activity -</option>');
        $('#modal-activity_id').change();

        if($(this).val() && $(this).val() != 'other') {
            $('#modal-field-activity_id').show();

            $.ajax({
                url: "{{route('timesheet.get-activity')}}",
                type: 'GET',
                data: {
                    'id': $(this).val()
                },
                success: function(response){
                    $.each(response, function(i, data){
                        $('<option>', {
                            value: data.id,
                            text: data.name
                        }).html(data.name).appendTo('#modal-activity_id')
                    });
                    $('#modal-activity_id').append('<option value="other">Other Activity</option>');
                    if(first) {
                        $('#modal-activity_id').val(modalActivityID ? modalActivityID : 'other').change();
                        first = false;
                    }
                }
            });
        } else if($(this).val() == 'other') {
            $('#modal-field-activity_id').hide();
            $('#modal-activity_id').append('<option value="other" selected>Other Activity</option>').change();
            first = false;
        } else {
            $('#modal-field-activity_id').hide();
            first = false;
        }
    });

    $("#modal-activity_id").change(function() {
        if($(this).val() != 'other') {
            $('#modal-field-activity_name').hide();
            $('#modal-activity_name').val(modalActivityNAME);
        } else {
            $('#modal-field-activity_name').show();
            if(!first)
                $('#modal-activity_name').val('');
        }
    });

    $("#modal-start_time, #modal-end_time").change(function() {
        let end = moment($("#modal-end_time").val(), "HH:mm");
        let start = moment($("#modal-start_time").val(), "HH:mm");
        let duration = moment.duration(end.diff(start));
        let hours = parseInt(duration.asHours());
        let minutes = parseInt(duration.asMinutes())%60;

        $("#modal-total_time").val(moment(hours, "HH").format("HH")+':'+moment(minutes, "mm").format("mm"));
    });

    $('.time-picker').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true
    });

    var modalActivityID = null;
    var modalActivityNAME = null;
    var first = true;
    function editTimesheet(id, category_id, activity_id, activity, description, start_time, end_time, total_time, admin_note) {
        first = true;
        modalActivityID = activity_id;
        modalActivityNAME = activity
        $('#modal-id').val(id);
        $('#modal-category_id').val(category_id ? category_id : 'other').change();
        $('#modal-activity_name').val(activity);
        $('#modal-description').val(description);
        $('#modal-start_time').val(start_time);
        $('#modal-end_time').val(end_time);
        $('#modal-total_time').val(total_time);
        $('#modal-admin_note').val(admin_note);
        if(!modalActivityID) {
            $('#modal-field-activity_name').show();
            $('#modal-activity_name').val(modalActivityNAME);
        }
        $("#modal-admin").modal("show");
    }

    $("#modal-admin").on('hide.bs.modal', function(){
        $('#modal-id').val('');
        $('#modal-category_id').val('');
        $('#modal-activity_id').val('');
        $('#modal-activity_name').val('');
        $('#modal-description').val('');
        $('#modal-start_time').val('');
        $('#modal-end_time').val('');
        $('#modal-total_time').val('');
        $('#modal-admin_note').val('');
        $('#modal-field-activity_name').hide();
    });

    $("#btn_submit").click(function(){
        bootbox.confirm('Save timesheet data changes?', function(result){
            if(result) {
                $('#form-timesheet').submit();
            }
        });
    });
</script>
@endsection
@endsection