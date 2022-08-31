@extends('layouts.karyawan')

@section('title', 'KPI Surveys')

@section('sidebar')

@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title" style="overflow: inherit;">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">KPI Surveys</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <div style="padding-left:0; float: right;">
                        <div class="btn-group m-l-10 m-r-10 pull-right">
                            <a href="javascript:void(0)" aria-expanded="true" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action 
                                <i class="fa fa-gear"></i>
                            </a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="javascript:void(0)" onclick="addEmployee()"><i class="fa fa-plus"></i> Add Employee</a></li>
                                <li><a href="javascript:void(0)" onclick="addImport()" class="check-filter" style="display: none;"> <i class="fa fa-upload"></i> Import</a></li>
                                <li><a href="javascript:void(0)" onclick="download_list()"><i class="fa fa-download"></i> Download Excel</a></li>
                                <li><a href="javascript:void(0)" onclick="download_detail()" class="check-filter" style="display: none;"><i class="fa fa-download"></i> Download Detail Excel</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select name="status" CLASS="form-control" id="status">
                                <option value="-1">- Select Status -</option>
                                <option {{ \Session::get('status') == 0 ? 'selected' : '' }} value="0">Draft</option>
                                <option {{ \Session::get('status') == 1 ? 'selected' : '' }} value="1">Self Reviewed</option>
                                <option {{ \Session::get('status') == 2 ? 'selected' : '' }} value="2">Final Reviewed</option>
                                <option {{ \Session::get('status') == 3 ? 'selected' : '' }} value="3">Acknowledged</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select name="position" CLASS="form-control" id="position">
                                <option value="0">- Select Posisi - </option>
                                <?php $positions = getJuniorPositions()?>
                                @foreach($positions as $position)
                                    <option {{ $position->id == \Session::get('position') ? 'selected' : '' }} value="{{$position->id}}">{{$position->position}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <div class="form-group  m-b-0">
                            <select name="period" CLASS="form-control" id="period">
                                <?php $periods = get_kpi_periods()?>
                                <option value="0">- Select Period -</option>
                                @foreach($periods as $period)
                                    @if($period->status==1 && $period->is_lock==1)
                                        <option {{ $period->id == \Session::get('period') ? 'selected' : '' }} value="{{$period->id}}">{{ date("d F Y", strtotime($period->start_date))." - ".date("d F Y", strtotime($period->end_date)) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">

                        <div class="table-responsive">
                            <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center">NO</th>
                                    <th width="15%">PERIOD</th>
                                    <th width="10%">NIK</th>
                                    <th width="20%">NAME</th>
                                    <th width="20%">POSITION</th>
                                    <th width="10%">STATUS</th>
                                    <th width="10%">FINAL SCORE</th>
                                    <th  width="10%">ACTION</th>
                                </tr>
                                </thead>
                                <tbody id="data_item">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- ============================================================== -->
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
    <div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Import Data</h4> </div>
                        <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('karyawan.kpi-survey.import') }}">
                        <input type="hidden" id="import_id_period" name="import_id_period" />
                        <input type="hidden" id="import_id_position" name="import_id_position" />
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3">File (xls)</label>
                                <div class="col-md-9">
                                    <input type="file" name="file" class="form-control" />
                                </div>
                            </div>
                            <a href="javascript:void(0)" onclick="download_import()"><i class="fa fa-download"></i> Download Sample Excel</a>
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
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        var id_period = $('#period').val(),id_position=$('#position').val(),id_status=$('#status').val(), empty="";

        $( document ).ready(function() {
            $('#period').on('change',function () {
                id_period = $(this).val();
                $('#import_id_period').val($(this).val());
                loadData();
            }).change();
            $('#position').on('change',function () {
                id_position = $(this).val();
                $('#import_id_position').val($(this).val());
                loadData();
            }).change();
            $('#status').on('change',function () {
                id_status = $(this).val();
                loadData();
            }).change();
        });
        loadData();
        function loadData(){
            // if(id_period==0){
            //     empty = "Select period first!";
            // }
            // else{
                empty = "No data available in table";
            // }
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
                    sProcessing: "loading...",
                    sEmptyTable: empty
                },
                oSearch: { "bSmart": false, "bRegex": true },
                processing: true,
                serverSide: true,
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {"url": "{{ route('ajax.table.kpi_survey') }}", "type": "GET","data":{"id_period":id_period,"id_position":id_position,"id_status":id_status}},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "period", "searchable": false},
                    { "data": "nik","name":"u.nik"},
                    { "data": "name","name":"u.name"},
                    { "data": "position", "searchable": false},
                    { "data": "status"},
                    { "data": "final_score"},
                    { "data": 'action', "orderable": false, "searchable": false}
                ],
                columnDefs:[

                    {
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            if (data == 0) {
                                label = 'btn btn-warning btn-xs';
                                st = 'DRAFT';
                            } else if (data == 1)  {
                                label = 'btn btn-info btn-xs';
                                st = 'SELF REVIEWED';
                            }
                            else if (data == 2)  {
                                label = 'btn btn-info btn-xs';
                                st = 'FINAL REVIEWED';
                            }
                            else if (data == 3)  {
                                label = 'btn btn-success btn-xs';
                                st = 'ACKNOWLEDGED';
                            }
                            data = "<label  class='"+label+ "'> " + st + "</label ><br>";
                            return data
                        },
                        "targets": 5
                    },
                    {
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            if (data == null) {
                                data = '<i>Not yet</i>';
                            }
                            return data
                        },
                        "targets": 6
                    }
                    ],
                order: [[1, 'asc']],
                rowCallback: function(row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                }
            });
        };
        function remove(id) {
            swal({
                title: 'Are you sure?',
                text: "Once deleted, you will not be able to recover this user's survey!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "kpi-survey/"+id,
                        type: "DELETE",
                        data:{'_token':"{{csrf_token()}}"},
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status == 'success') {
                                swal("Success!", data.message, "success");
                                reload_table();
                            } else {
                                swal("Failed!", data.message, "error");
                            }
                            console.log();
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
        function reload_table()
        {
            $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
        }
    </script>
    <script type="text/javascript" language="javascript" >

        var orig_default = -1;

        function setDefault() {
            if (orig_default < 0) {orig_default = document.getElementById("period").selectedIndex;}
        }

        function testReset() {
            if (orig_default >= 0) {
                document.getElementById("period").selectedIndex = orig_default;
            }
        }

        $("#btn_import").click(function(){
            $("#form-upload").submit();
            $("#form-upload").hide();
            $('.div-proses-upload').show();
        });

        function addImport() {
            $("#modal_import").modal("show");
            $('.div-proses-upload').hide();
            $("#form-upload").show();
        }
        function download_import() {
            window.location.href = "{{route('karyawan.kpi-survey.download-import')}}?id_period="+id_period+"&id_position="+id_position;
        }
        function download_list() {
            window.location.href = "{{route('karyawan.kpi-survey.download')}}?id_period="+id_period+"&id_position="+id_position+"&id_status="+id_status;
        }
        function download_detail() {
            window.location.href = "{{route('karyawan.kpi-survey.download-detail')}}?id_period="+id_period+"&id_position="+id_position+"&id_status="+id_status;
        }
        $("#period, #position").change(function() { 
            if ($('#period').val() != 0 && $('#position').val() != 0) {
                $('.check-filter').show();
            } else {
                $('.check-filter').hide();
            }
        });
    </script>
    <body onbeforeunload='setDefault();'>
@endsection
@endsection
