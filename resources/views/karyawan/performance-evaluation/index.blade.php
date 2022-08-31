@extends('layouts.karyawan')

@section('title', 'Performance Evaluation')

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
                    <h4 class="page-title">Performance Evaluation</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    {{--<a href="{{ route('Manager.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>--}}
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Performance Evaluation</li>
                    </ol>
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
                                    <th width="30" class="text-center">NO</th>
                                    <th>PERIOD</th>
                                    <th>POSITION</th>
                                    <th width="10%">SUPERVISOR</th>
                                    <th width="10%">STATUS</th>
                                    <th width="10%">RATE</th>
                                    <th width="10%">FINAL SCORE</th>
                                    <th  width="10%">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
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
                ajax: {"url": "{{ route('ajax.table.performance_evaluation') }}", "type": "GET"},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "period"},
                    { "data": "position"},
                    { "data": "supervisor"},
                    { "data": "status"},
                    { "data": "rate"},
                    {
                        "data": null,
                        render: function (data) {
                            return data.status == 2 || data.status == 3 ? data.final_score : ''
                        }
                    },
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
                        "targets": 4
                    },
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
        function reload_table()
        {
            $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
        }
    </script>
@endsection
@endsection
