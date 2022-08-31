@extends('layouts.administrator')

@section('title', 'Setting Performance Management')

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
                <h4 class="page-title">Setting Performance Management</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="{{ route('administrator.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting Performance Management</li>
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
                                    <th width="10%">STATUS</th>
                                    <th width="10%">MIN RATE</th>
                                    <th width="10%">MAX RATE</th>
                                    <th width="10%">LOCK</th>
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
                ajax: {"url": "{{ route('ajax.table.kpi_period') }}", "type": "GET"},
                columnDefs: [

                {
                    "render": function (data, type, row) {
                        // here you can convert data from base64 to hex and return it
                        if (data == 1) {
                            label = 'badge badge-success';
                            st = 'PUBLISHED';
                        } else {
                            label = 'badge badge-warning';
                            st = 'DRAFT';
                        }
                        data = "<span  class='"+label+ "'> " + st + "</span ><br>";
                        return data
                    },
                    "targets": 2
                },{
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            if (data == 1) {
                                data='<center><span class="text-danger" title="Period is locked" style="font-size: 25px;"><i class="fa fa-lock"></i></span></center>';
                            } else {
                                data='';
                            }
                            return data
                        },
                        "targets": 5
                    }
                    ,{
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            console.log(row);
                            if (row.is_lock == 1) {
                                data='<a href="setting-performance/'+row.id+'/edit"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> detail</button></a>';
                            }
                            return data
                        },
                        "targets": 6
                    }
            ],
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "period"},
                    { "data": "status"},
                    { "data": "min_rate"},
                    { "data": "max_rate"},
                    { "data": "is_lock", "orderable": false, "searchable": false},
                    { "data": 'action', "orderable": false, "searchable": false}
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
                text: 'Once deleted, you will not be able to recover this period!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "setting-performance/"+id,
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
@endsection
@endsection
