@extends('layouts.administrator')

@section('title', 'Remote Attendance')

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
                    <h4 class="page-title">Remote Attendance</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
{{--                    <a href="{{ route('administrator.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>--}}
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Remote Attendance</li>
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
                                <a class="btn btn-sm btn-info pull-right" type="button" href="{{route('administrator.remote-attendance.create')}}">Add Remote Attendance</a>
                            </div>

                            <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center">NO</th>
                                    <th width="15%">NIK</th>
                                    <th width="10%">NAME</th>
                                    <th width="10%">LOCATION</th>
                                    <th width="10%">START DATE</th>
                                    <th width="10%">END DATE</th>
                                    <th width="10%">ACTION</th>
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
    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Add Remote Attendance</strong></h4>
                </div>
                <form id="form">
                <div class="modal-body" id="modal-add-body">
                        <div class="form-group col-md-12">
                            <label>Category Name</label>
                            <div>
                                <input type="text" name="name" class="form-control" placeholder="Category Name" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success save-event waves-effect waves-light">Submit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div  class="modal fade none-border" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Update Remote Attendance</strong></h4>
                </div>
                <form id="form-edit">
                    <div class="modal-body" id="modal-add-body">
                        <div class="form-group col-md-12">
                            <label>Category Name</label>
                            <div>
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" id="job_category_id" name="id">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Category Name" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success save-event waves-effect waves-light">Update</button>
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
                oSeddrch: { "bSmart": false, "bRegex": true },
                processing: true,
                serverSide: true,
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {"url": "{{ route('ajax.table.remote_attendance') }}", "type": "GET","data":{}},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "nik",name:'u.nik'},
                    { "data": "name",name:'u.name'},
                    { "data": "location_name"},
                    { "data": "start_date"},
                    { "data": "end_date"},
                    { "data": 'action', "orderable": false, "searchable": false}
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
        function remove(id) {
            swal({
                title: 'Are you sure?',
                text: "Once deleted, you will not be able to recover Remote Attendance!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "remote-attendance/"+id,
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

        function edit(id) {
            window.location.href = '/administrator/remote-attendance/'+id+'/edit';
        }

        function reload_table()
        {
            $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
        }


    </script>


@endsection
@endsection
