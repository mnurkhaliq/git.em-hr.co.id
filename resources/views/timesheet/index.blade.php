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
                <div class="col-md-12 pull-right" style="padding:0px;">
                    <form method="POST" action="{{ route('timesheet.index') }}" id="filter-form" autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" value="view">
                        <input type="hidden" name="reset" value="0">
                        <input type="hidden" name="eksport" value="0">
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div style="padding-left:0; float: right;">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                        <li><a class="toggle-vis" data-column="1" style="color:blue;">NIK</a></li> 
                                        <li><a class="toggle-vis" data-column="2" style="color:blue;">Name</a></li> 
                                        <li><a class="toggle-vis" data-column="3" style="color:blue;">Position</a></li>
                                        <li><a class="toggle-vis" data-column="4" style="color:blue;">Division</a></li>
                                        <li><a class="toggle-vis" data-column="5" style="color:blue;">Timesheet Category</a></li>
                                        <li><a class="toggle-vis" data-column="6" style="color:blue;">Timesheet Activity</a></li> 
                                        <li><a class="toggle-vis" data-column="7" style="color:blue;">Description</a></li>
                                        <li><a class="toggle-vis" data-column="8" style="color:blue;">Date</a></li>
                                        <li><a class="toggle-vis" data-column="9" style="color:blue;">Day</a></li>
                                        <li><a class="toggle-vis" data-column="10" style="color:blue;">Start Time</a></li> 
                                        <li><a class="toggle-vis" data-column="11" style="color:blue;">End Time</a></li> 
                                        <li><a class="toggle-vis" data-column="12" style="color:blue;">Duration</a></li> 
                                        <li><a class="toggle-vis" data-column="13" style="color:blue;">Note</a></li> 
                                        <li><a class="toggle-vis" data-column="14" style="color:blue;">Action</a></li> 
                                    </ul>
                                </div>
                                <div class="btn-group m-l-4 m-r-4 pull-right" style="padding-left:3px; padding-right:3px;">
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
                        </div>
                        <div class="col-md-10 p-0 pull-right">
                            <div class="col-md-7 pull-right" style="padding: 0">
                                <div class="col-md-3 pull-right">
                                    <select name="activity" class="form-control form-control-line" id="activity">
                                        <option value="" selected>- Activity -</option>
                                        @foreach(getTimesheetActivity(\Session::get('category')) as $item)
                                        <option {{ $item->id == \Session::get('activity') ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                        <option {{ 'other' == \Session::get('activity') ? 'selected' : '' }} value="other">Other Activity</option>
                                    </select>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <select name="category" class="form-control form-control-line" id="category">
                                        <option value="" selected>- Category -</option>
                                        @foreach(getTimesheetCategory() as $item)
                                        <option {{ $item->id == \Session::get('category') ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <select name="division" class="form-control form-control-line" id="division">
                                        <option value="" selected>- Division -</option>
                                        @foreach($division as $item)
                                        <option {{ $item['id'] == \Session::get('division') ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 pull-right">
                                    <select name="position" class="form-control form-control-line" id="position">
                                        <option value="" selected>- Position -</option>
                                        @foreach($position as $item)
                                        <option {{ $item['id'] == \Session::get('position') ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 pull-right" style="padding: 0">
                                <div class="col-md-6 pull-right">
                                    <input type="text" name="filter_end" class="form-control datepicker form-control-line" id="filter_end" placeholder="End Date" value="{{ \Session::get('filter_end') }}">
                                </div>
                                <div class="col-md-6 pull-right">
                                    <input type="text" name="filter_start" class="form-control datepicker form-control-line" id="filter_start" placeholder="Start Date" value="{{ \Session::get('filter_start') }}" />
                                </div>
                            </div>
                            <div class="col-md-2 pull-right">
                                <input type="text" name="timesheet_name" id="nama_nik" class="form-control form-control-line autocomplete-karyawan" placeholder="Nik / Name" value="{{ \Session::get('timesheet_name')}}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="1">No</th>
                                    <th rowspan="1">NIK</th>
                                    <th rowspan="1">Name</th>
                                    <th rowspan="1">Position</th>
                                    <th rowspan="1">Division</th>
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
                                
                            </tbody>
                        </table>
                    </div>
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
                    <button type="button" class="close dismiss" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Edit Timesheet</h4>
                </div>
                <form id="form-timesheet" method="POST" action="{{ route('timesheet.store') }}" onsubmit="return confirm('Save timesheet data changes?')" autocomplete="off" class="form-horizontal frm-modal-cuti">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-12">Category <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <select required name="modal_category_id[]" class="form-control form-control-line" id="modal-category_id">
                                    <option value="" selected>- Category -</option>
                                    @foreach(getTimesheetCategory() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="modal-field-activity_id">
                            <label class="col-md-12">Activity <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <select required name="modal_activity_id[]" class="form-control form-control-line" id="modal-activity_id"></select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;" id="modal-field-activity_name">
                            <label class="col-md-12">Activity Name <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input required type="text" name="modal_activity_name[]" class="form-control" id="modal-activity_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Description</label>
                            <div class="col-md-12">
                                <textarea name="modal_description[]" class="form-control" id="modal-description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Start Time <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input required type="text" name="modal_start_time[]" class="form-control time-picker" id="modal-start_time">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">End Time <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <input required type="text" name="modal_end_time[]" class="form-control time-picker" id="modal-end_time">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Duration</label>
                            <div class="col-md-12">
                                <input readonly type="text" name="modal_total_time[]" class="form-control" id="modal-total_time">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Note</label>
                            <div class="col-md-12">
                                <textarea name="modal_admin_note[]" class="form-control" id="modal-admin_note"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="modal_id" id="modal-id">
                        <div class="form-group" style="margin-bottom: 0;">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-warning btn-sm pull-right" id="split">Split</button>
                            </div>
                        </div>
                    </div>
                    <div id="split-field"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm dismiss">Close</button>
                        <button type="submit" class="btn btn-info btn-sm">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

@section('footer-script')
<style>
    .clockpicker-popover {
        z-index: 999999 !important;
    }
</style>
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script>
    var t;
    loadData();

    function loadData(){
        $('#mytable').DataTable().destroy();
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };
        t = $("#mytable").DataTable({
            searching: false,
            ordering: true,
            lengthChange: true,
            pageLength: 50,
            initComplete: function() {
                var api = this.api();
                $('#mytable_filter input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
            },
            oLanguage: {
                sProcessing: "loading..."
            },
            oSearch: { "bSmart": false, "bRegex": true },
            processing: true,
            serverSide: true,
            fixedHeader: true,
            scrollCollapse: true,
            scrollX: true,
            ajax: {
                "url": "{{ route('timesheet.table') }}",
                "type": "GET",
                "data": {
                    "filter_start": $('input[name="filter_start"]').val(),
                    "filter_end": $('input[name="filter_end"]').val(),
                    "timesheet_name": $('input[name="timesheet_name"]').val(),
                    "category": $('select[name="category"]').val(),
                    "activity": $('select[name="activity"]').val(),
                    "position": $('select[name="position"]').val(),
                    "division": $('select[name="division"]').val(),
                }
            },
            columns: [
                { "data": "transaction_id", "orderable": false },
                { "data": "nik" },
                { "data": "username" },
                { "data": "position" },
                { "data": "division" },
                { "data": "column_category", "name": "category" },
                { "data": "activity" },
                { "data": "description" },
                { "data": "date" },
                { "data": "column_date", "orderable": false },
                { "data": "start_time" },
                { "data": "end_time" },
                { "data": "total_time" },
                { "data": "admin_note" },
                { "data": "column_action", "orderable": false },
            ],
            order: [
                [8, 'desc'],
                [10, 'desc'],
                [11, 'desc'],
            ],
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });
    };

    $('#modal-admin').modal({backdrop: 'static', keyboard: false}).modal('hide')

    $('.dismiss').click(function() {
        if (confirm("updates made will be discarded, are you sure?"))
            $('#modal-admin').modal('hide')
    })

    function reset_filter()
    {
        $("#filter-form input.form-control, #filter-form select").val("");
        $("#filter-form input[name='action']").val('');
        $("input[name='reset']").val(1);
        $("#filter-form").submit();
    }

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
        let start = moment($("#modal-start_time").val(), "HH:mm");
        let end = moment($("#modal-end_time").val(), "HH:mm");

        if(start <= end)
            duration = moment.duration(start.diff(end));
        else
            duration = moment.duration(start.diff(end.add(1, "days")));

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
        $("#split-field").html('');
    });

    $("#split").click(function() {
        var html = '<div class="modal-body" style="border-top: 1px solid #e5e5e5;">';
        html += '<div class="form-group">'+
                    '<label class="col-md-12">Category <span class="text-danger">*</span></label>'+
                    '<div class="col-md-12">'+
                        '<select required name="modal_category_id[]" class="form-control form-control-line modal-category_id">'+
                            '<option value="" selected>- Category -</option>'+
                            @foreach(getTimesheetCategory() as $item)
                            '<option value="'+{{ $item->id }}+'">'+"{{ $item->name }}"+'</option>'+
                            @endforeach
                        '</select>'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group modal-field-activity_id" style="display: none;">'+
                    '<label class="col-md-12">Activity <span class="text-danger">*</span></label>'+
                    '<div class="col-md-12">'+
                        '<select required name="modal_activity_id[]" class="form-control form-control-line modal-activity_id">'+
                            '<option value="" selected>- Activity -</option>'+    
                        '</select>'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group modal-field-activity_name" style="display: none;">'+
                    '<label class="col-md-12">Activity Name <span class="text-danger">*</span></label>'+
                    '<div class="col-md-12">'+
                        '<input required type="text" name="modal_activity_name[]" class="form-control modal-activity_name">'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group">'+
                    '<label class="col-md-12">Description</label>'+
                    '<div class="col-md-12">'+
                        '<textarea name="modal_description[]" class="form-control modal-description"></textarea>'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group">'+
                    '<label class="col-md-12">Start Time <span class="text-danger">*</span></label>'+
                    '<div class="col-md-12">'+
                        '<input required type="text" name="modal_start_time[]" class="form-control time-picker modal-start_time">'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group">'+
                    '<label class="col-md-12">End Time <span class="text-danger">*</span></label>'+
                    '<div class="col-md-12">'+
                        '<input required type="text" name="modal_end_time[]" class="form-control time-picker modal-end_time">'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group">'+
                    '<label class="col-md-12">Duration</label>'+
                    '<div class="col-md-12">'+
                        '<input readonly type="text" name="modal_total_time[]" class="form-control modal-total_time">'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group">'+
                    '<label class="col-md-12">Note</label>'+
                    '<div class="col-md-12">'+
                        '<textarea name="modal_admin_note[]" class="form-control modal-admin_note"></textarea>'+
                    '</div>'+
                '</div>';
        html += '<div class="form-group" style="margin-bottom: 0;">'+
                    '<div class="col-md-12">'+
                        '<button type="button" class="btn btn-danger btn-sm pull-right" onclick="hapus_(this)"><i class="fa fa-trash"></i></button>'+
                    '</div>'+
                '</div>';
        html += '</div>';

        $("#split-field").append(html)

        $('.time-picker').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true
        });

        first = true

        $(".modal-category_id").change(function() {
            let parent = $(this).parent().parent().parent()
            parent.find('.modal-activity_id').html('');
            parent.find('.modal-activity_id').append('<option value="" selected>- Activity -</option>');
            parent.find('.modal-activity_id').change();

            if($(this).val() && $(this).val() != 'other') {
                parent.find('.modal-field-activity_id').show();

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
                            }).html(data.name).appendTo(parent.find('.modal-activity_id'))
                        });
                        parent.find('.modal-activity_id').append('<option value="other">Other Activity</option>');
                        if(first) {
                            parent.find('.modal-activity_id').val($('#modal-activity_id').val() ? $('#modal-activity_id').val() : 'other').change();
                            first = false;
                        }
                    }
                });
            } else if($(this).val() == 'other') {
                parent.find('.modal-field-activity_id').hide();
                parent.find('.modal-activity_id').append('<option value="other" selected>Other Activity</option>').change();
                first = false;
            } else {
                parent.find('.modal-field-activity_id').hide();
                first = false;
            }
        });

        $(".modal-activity_id").change(function() {
            let parent = $(this).parent().parent().parent()
            if($(this).val() != 'other') {
                parent.find('.modal-field-activity_name').hide();
                parent.find('.modal-activity_name').val(modalActivityNAME);
            } else {
                parent.find('.modal-field-activity_name').show();
                if(!first)
                    parent.find('.modal-activity_name').val('');
            }
        });

        $(".modal-start_time, .modal-end_time").change(function() {
            let parent = $(this).parent().parent().parent()
            let start = moment(parent.find(".modal-start_time").val(), "HH:mm");
            let end = moment(parent.find(".modal-end_time").val(), "HH:mm");

            if(start <= end)
                duration = moment.duration(start.diff(end));
            else
                duration = moment.duration(start.diff(end.add(1, "days")));

            let hours = parseInt(duration.asHours());
            let minutes = parseInt(duration.asMinutes())%60;

            parent.find(".modal-total_time").val(moment(hours, "HH").format("HH")+':'+moment(minutes, "mm").format("mm"));
        });

        $("#split-field").find('.modal-body').last().find('.modal-category_id').val($('#modal-category_id').val()).change();
        $("#split-field").find('.modal-body').last().find('.modal-activity_id').val($('#modal-activity_id').val()).change();
        $("#split-field").find('.modal-body').last().find('.modal-activity_name').val($('#modal-activity_name').val()).change();
        $("#split-field").find('.modal-body').last().find('.modal-description').val($('#modal-description').val()).change();
        $("#split-field").find('.modal-body').last().find('.modal-start_time').val($('#modal-start_time').val()).change();
        $("#split-field").find('.modal-body').last().find('.modal-end_time').val($('#modal-end_time').val()).change();
        $("#split-field").find('.modal-body').last().find('.modal-total_time').val($('#modal-total_time').val()).change();
        $("#split-field").find('.modal-body').last().find('.modal-admin_note').val($('#modal-admin_note').val()).change();
    })

    function hapus_(el) {
        $(el).parent().parent().parent().remove();
    }

    $('a.toggle-vis').on('click', function (e) {
        e.preventDefault();
        e.target.style.color == 'blue' ? $(this).addClass('change-toggle') : $(this).removeClass('change-toggle');
        e.target.style.color = e.target.style.color == 'blue' ? 'red' : 'blue';
        // console.log($(this).attr('href'))
        // $($(this).attr('href')).click(function(e) {
        //     e.stopPropagation();
        // })
        // $($(this).attr('href')).prop("checked", !$($(this).attr('href')).prop("checked"));
        // if((e.target).tagName == 'INPUT') return true; 
        
        // Get the column API object
        var column = t.column($(this).attr('data-column'));
 
        // Toggle the visibility
        column.visible(!column.visible());
    });
</script>
@endsection
@endsection