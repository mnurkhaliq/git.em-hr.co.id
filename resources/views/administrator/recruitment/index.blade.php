@extends('layouts.administrator')

@section('title', 'Recruitment')

@section('sidebar')

@endsection

@section('content')
    <link href="{{ asset('js/recruitment-request/general.css') }}" rel="stylesheet">
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Recruitment</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    {{--<a href="{{ route('Manager.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>--}}
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Recruitment</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <div class="form-group col-md-2 pull-right">
                                {{--<a class="btn btn-md btn-info pull-right" href="{{route('administrator.recruitment-request.create')}}">Add Request</a>--}}
                            </div>
                            <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="2%" class="text-center">NO</th>
                                    <th width="10%">REQUEST NUMBER</th>
                                    <th width="10%">POSITION</th>
                                    <th width="10%">BRANCH</th>
                                    <th width="5%">HEADCOUNT</th>
                                    <th width="10%">REQUEST DATE</th>
                                    <th width="10%">TARGET</th>
                                    <th  width="5%">ACTION</th>
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
        <!-- BEGIN MODAL -->
        <div  class="modal fade none-border" id="modal-detail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Recruitment Request Approval</strong></h4>
                    </div>
                    <form id="form">
                        <div class="modal-body" id="modal-add-body">
                            <div class="form-group col-xs-12">
                                <table class="table-history" width="100%">
                                    <tr>
                                        <td width="30%">Request Number</td>
                                        <td width="30"> : </td>
                                        <td id="approval_request_number"></td>
                                    </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td> : </td>
                                        <td id="approval_position"></td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td> : </td>
                                        <td id="approval_branch"></td>
                                    </tr>
                                    <tr>
                                        <td>Date Request</td>
                                        <td> : </td>
                                        <td id="approval_date_request"></td>
                                    </tr>
                                </table>

                            </div>
                            <hr/>
                            <div class="form-group col-xs-12" id="approval">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
@section('js')

    <script>var url = "{{route('ajax.get-recruitment-request-approval')}}"</script>
    <script src="{{ asset('js/recruitment-request/general.js') }}"></script>
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
                ajax: {"url": "{{ route('ajax.table.recruitment.admin') }}", "type": "GET"},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "request_number"},
                    { "data": "job_position"},
                    { "data": "branch","name":"c.name"},
                    { "data": "headcount"},
                    { "data": "request_date","searchable":false},
                    { "data": "target","searchable":false,
                        "render": function(data, type, row){
                            if(data == '1')
                                return "INTERNAL";
                            else if(data == '2')
                                return "EXTERNAL";
                            else
                                return "INTERNAL & EXTERNAL";
                        }},

                    { "data": 'action', "orderable": false, "searchable": false,
                        "render": function(data, type, row){
                                var btn = '<a href="recruitment/'+row['id']+'"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-user-plus"></i> Recruitment</button></a>';
                                return btn;

                        }
                    }
                ],
                order: [[1, 'desc']],
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
