@extends('layouts.administrator')

@section('title', 'Employee Attendance')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title" style="overflow: inherit;">
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                    <h4 class="page-title">Manage Attendance</h4>
                </div>
                <div class="col-lg-10 col-sm-9 col-md-9 col-xs-12">
                    <div class="col-md-12 pull-right" style="padding:0px;">
                        <form method="POST" action="{{ route('attendance.index') }}" id="filter-form" autocomplete="off">
                            {{ csrf_field() }}
                            <input type="hidden" name="action" value="view">
                            <input type="hidden" name="reset" value="0">
                            <input type="hidden" name="eksport" value="0">
                            <input type="hidden" name="import" value="0">

                            <div class="col-md-2 pull-right" style="padding:0px;">
                                <div style="padding-left:0; float: right;">
                                    <div class="btn-group pull-right">
                                        <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                            <li><a class="toggle-vis" data-column="1" style="color:blue;">NIK</a></li> 
                                            <li><a class="toggle-vis" data-column="2" style="color:blue;">Name</a></li> 
                                            <li><a class="toggle-vis" data-column="3" style="color:blue;">Date</a></li>
                                            <li><a class="toggle-vis" data-column="4" style="color:blue;">Day</a></li>
                                            <li><a class="toggle-vis" data-column="5" style="color:blue;">Shift</a></li>
                                            <li><a class="toggle-vis" data-column="6" style="color:blue;">Shift (in)</a></li> 
                                            <li><a class="toggle-vis" data-column="7" style="color:blue;">Shift (Out)</a></li>
                                            <li><a class="toggle-vis" data-column="8" style="color:blue;">Clock (in)</a></li>
                                            <li><a class="toggle-vis" data-column="9" style="color:blue;">Clock (Out)</a></li>
                                            <li><a class="toggle-vis" data-column="10" style="color:blue;">Branch (in)</a></li> 
                                            <li><a class="toggle-vis" data-column="11" style="color:blue;">Branch (Out)</a></li> 
                                            <li><a class="toggle-vis" data-column="12" style="color:blue;">Timezone</a></li> 
                                            <li><a class="toggle-vis" data-column="13" style="color:blue;">Late CLOCK In</a></li> 
                                            <li><a class="toggle-vis" data-column="14" style="color:blue;">Early CLOCK Out</a></li> 
                                            <li><a class="toggle-vis" data-column="15" style="color:blue;">Duration</a></li> 
                                        </ul>
                                    </div>
                                    <div class="btn-group m-l-4 m-r-4 pull-right" style="padding-left:3px; padding-right:3px;">
                                        <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action
                                            <i class="fa fa-gear"></i>
                                        </a>
                                        <ul role="menu" class="dropdown-menu">
                                            <li><a href="javascript:void(0)" onclick="reset_filter()"><i class="fa fa-refresh"></i> Reset Filter </a></li>
                                            <li><a href="javascript:void(0)" onclick="eksportAttendance()"><i class="fa fa-download"></i> Export </a></li>
                                            <li><a href="javascript:void(0)" data-toggle="modal" data-target="#modal_import"><i class="fa fa-upload"></i> Import </a></li>
                                        </ul>
                                    </div>
                                    <button id="filter_view" class="btn btn-default btn-sm btn-outline"> <i class="fa fa-search-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-10 p-0 pull-right">
                                <div class="col-md-6 pull-right" style="padding: 0">
                                    <div class="col-md-4 pull-right">
                                        <select name="branch" class="form-control form-control-line" id="branch">
                                            <option value="" selected>- Branch -</option>
                                            @foreach(cabang() as $item)
                                                <option {{ $item->id == \Session::get('branch') ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 pull-right">
                                        <select name="division" class="form-control form-control-line" id="division">
                                            <option value="" selected>- Division -</option>
                                            @foreach($division as $item)
                                                <option {{ $item['id'] == \Session::get('division') ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 pull-right">
                                        <select name="position" class="form-control form-control-line" id="position">
                                            <option value="" selected>- Position -</option>
                                            @foreach($position as $item)
                                                <option {{ $item['id'] == \Session::get('position') ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 pull-right" style="padding: 0">
                                    <div class="col-md-6 pull-right">
                                        <input type="text"  name="filter_end" class="form-control datepicker form-control-line" id="filter_end" placeholder="End Date" value="{{ \Session::get('filter_end') }}">
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <input type="text" name="filter_start" class="form-control datepicker form-control-line" id="filter_start" placeholder="Start Date" value="{{ \Session::get('filter_start') }}" />
                                    </div>
                                </div>
                                <div class="col-md-2 pull-right">
                                    <input type="text" name="attendance_name" id="nama_nik" class="form-control form-control-line autocomplete-karyawan" placeholder="Nik / Name" value="{{ \Session::get('attendance_name')}}">
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
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">NIK</th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Date</th>
                                    <th rowspan="2">Day</th>
                                    <th rowspan="2">Shift</th>
                                    <th colspan="2" style="text-align: center;">Shift</th>
                                    <th colspan="2" style="text-align: center;">Clock</th>
                                    <th colspan="2" style="text-align: center;">Branch</th>
                                    <th rowspan="2">Timezone</th>
                                    <th rowspan="2">Late CLOCK In</th>
                                    <th rowspan="2">Early CLOCK Out</th>
                                    <th rowspan="2">Duration</th>
                                </tr>
                                <tr>
                                    <th>In</th>
                                    <th>Out</th>
                                    <th width="10%">In</th>
                                    <th width="10%">Out</th>
                                    <th>In</th>
                                    <th>Out</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
    <div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" class="form-horizontal" action="{{ route('attendance.import') }}" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Import Attendance</h4>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-12">File </label>
                            <div class="col-md-6">
                                <input type="file" class="form-control" required name="file">
                            </div>
                            <div class="col-md-12">
                                <a href="{{ asset('storage/sample/Sample-Attendance.xlsx') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info waves-effect btn-sm">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                            <b style="font-size: medium" id="title_justification">Note : </b>
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

    <div id="modal_import_attendance" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Import Data</h4> </div>
                <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('attendance.import') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">File (xls)</label>
                            <div class="col-md-9">
                                <input type="file" name="file" class="form-control" />
                            </div>
                        </div>
                        <a href="{{ asset('storage/sample/Sample-Attendance.xlsx') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
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
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <style>
        table.dataTable tbody th, table.dataTable tbody td {
            padding: 8px 5px;
        }
        .fa.pull-right {
            margin-left: 0.1em;
        }
        table.dataTable thead th, table.dataTable thead td {
            padding: 10px 20px;
        }
    </style>
@section('js')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTctq_RFrwKOd84ZbvJYvU3MEcrLmPNJ8"
            async defer>
            </script>

    <script type="text/javascript">
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
                    "url": "{{ route('attendance.table') }}",
                    "type": "GET",
                    "data": {
                        "filter_start": $('input[name="filter_start"]').val(),
                        "filter_end": $('input[name="filter_end"]').val(),
                        "attendance_name": $('input[name="attendance_name"]').val(),
                        "branch": $('select[name="branch"]').val(),
                        "position": $('select[name="position"]').val(),
                        "division": $('select[name="division"]').val(),
                    }
                },
                columns: [
                    { "data": "id", "orderable": false },
                    { "data": "nik" },
                    { "data": "username" },
                    { "data": "column_date", "name": "date" },
                    { "data": "timetable" },
                    { "data": "column_shift", "name": "shift" },
                    { "data": "shift_in" },
                    { "data": "shift_out" },
                    { "data": "column_clock_in", "name": "clock_in" },
                    { "data": "column_clock_out", "name": "clock_out" },
                    { "data": "column_branch_in", "name": "ci.name" },
                    { "data": "column_branch_out", "name": "co.name" },
                    { "data": "timezone" },
                    { "data": "late" },
                    { "data": "early" },
                    { "data": "work_time" },
                ],
                order: [
                    [3, 'desc'],
                    [8, 'desc'],
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

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });

        $("#filter_view").click(function(){
            if($('#filter_start').val() > $('#filter_end').val()){
                alert('Tanggal Tidak Boleh Backdate!');
            }else{
                $("#filter-form input[name='action']").val('view');
                $("#filter-form").submit();
            }
        });
        
        function reset_filter()
        {
            $("#filter-form input.form-control, #filter-form select").val("");
            $("input[name='reset']").val(1);
            $("#filter-form").submit();
        }

        function importAttendance(){
            $('#modal_import_attendance').modal('show');
            $('.div-proses-upload').hide();
            $("#form-upload").show();
        }

        $("#btn_import").click(function(){

            $("#form-upload").submit();
            $("#form-upload").hide();
            $('.div-proses-upload').show();

        });

        function eksportAttendance(){
            $("input[name='eksport']").val(1);
            $("#filter-form").submit();

            $("input[name='eksport']").val(0);
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
    <script>
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
                    console.log("City Circle colored : "+color);

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

        $(".autocomplete-karyawan").autocomplete({
            minLength:0,
            limit: 25,
            source: function( request, response ) {
                $.ajax({
                    url: "{{ route('ajax.get-karyawan') }}",
                    method : 'POST',
                    data: {
                        'name': request.term,'_token' : $("meta[name='csrf-token']").attr('content')
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function( event, ui ) {
                $( "input[name='id']" ).val(ui.item.id);
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });


    </script>
@endsection
@endsection
