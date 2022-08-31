@extends('layouts.administrator')

@section('title', 'Setting Recruitment')

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
                <h4 class="page-title">Setting Recruitment</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting Recruitment</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#RecruitmentEntitlement" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Recruitment Entitlement</span></a></li>
                        <li role="presentation" class=""><a href="#JobCategory" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Job Category</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="RecruitmentEntitlement">
                            <h3 class="box-title m-b-0">Assign to Recruitment Entitlement</h3>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_search display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Activity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Entitle Recruitment</td>
                                            <td>
                                                <button onclick="assignToEntitle(1)" type="button"
                                                    class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i>
                                                    assign</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Not Entitle Recruitment</td>
                                            <td>
                                                <button onclick="assignToEntitle(null)" type="button"
                                                    class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i>
                                                    assign</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                        <div role="tabpanel" class="tab-pane fade in" id="JobCategory">
                            <h3 class="box-title m-b-0">Job Category</h3>
                            <button class="btn btn-success btn-sm  hidden-sm waves-effect waves-light" type="button" onclick="addCategory()"><i class="fa fa-plus"></i> Add Category</button>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table id="mytable" class="data_table_no_search display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Category</th>
                                            <th>Action</th>
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
        </div>
        <!-- ============================================================== -->
    </div>
    <div class="modal fade none-border" id="modal-assign">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Assign Users to Recruitment Entitlement</strong></h4>
                </div>
                <div class="modal-body" id="modal-assign-body">
                    <div class="table-responsive">
                        <input type="hidden" id="entitleId">
                        <input type="text" class="form-control" id="searchUser">
                        <br />
                        <table class="table" id="tableList">
                            <tr>
                                <th><input type="checkbox" id="checkTopUser"></th>
                                <th>NO</th>
                                <th>NIK</th>
                                <th>NAME</th>
                                <th>POSITION</th>
                                <th>DIVISION</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div  class="modal fade none-border" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Add Job Category</strong></h4>
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
                    <h4 class="modal-title"><strong>Update Job Category</strong></h4>
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
@section('footer-script')
<style>
    #mytable {
        margin-top: 0 !important;
    }
</style>
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript">
    function assignToEntitle(v) {
        $('#searchUser').val('')

        $.ajax({
            url: "setting-recruitment/user-list-for-assignment/1",
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function (data) {
                if (data.message == 'success') {
                    $('#entitleId').val(v)
                    $('#tableList').find('tr:gt(0)').remove()
                    let checkAll = data.data.length ? true : false
                    for (let i = 0; i < data.data.length; i++) {
                        let num = i + 1;
                        let pos = data.data[i].position != null ? data.data[i].position : '-'
                        let div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableList tr:last').after(
                            '<tr class="search">' +
                            '<td><input id="checkUser' + num + '" type="checkbox" class="checkUser" ' +
                            (data.data[i].recruitment_entitle == v ? 'checked' : '') + '></td>' +
                            '<td><input id="idUser-' + num + '" type="hidden" value="' +
                            data.data[i].id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (data.data[i].recruitment_entitle != v)
                            checkAll = false
                    }
                    $('#tableList tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignEntitle" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign').modal('show')
                    assignEntitle()
                    defaultCheckAll(checkAll)
                    $('.checkUser').click(function () {
                        defaultCheckAll()
                    })
                } else {
                    $('#tableList').find('tr:gt(0)').remove()
                    $('#tableList tr:last').after(
                        '<tr>' +
                        '<td colspan="6">No data.</td>' +
                        '</tr>'
                    )
                    $('#modal-assign').modal('show')
                }
            }
        })
    }

    function assignEntitle() {
        $('#assignEntitle').click(function () {
            var arr_check = []
            var arr_uncheck = []
            var id_user = []
            var id_user_uncheck = []
            var recruitment_entitle = $('#entitleId').val()

            $('.checkUser').each(function (i) {
                if ($(this).prop('checked') == true)
                    arr_check.push(i + 1)
                else
                    arr_uncheck.push(i + 1)
            })

            for (var i = 0; i < arr_check.length; i++) {
                id_user.push($('#idUser-' + arr_check[i]).val())
            }

            for (var i = 0; i < arr_uncheck.length; i++) {
                id_user_uncheck.push($('#idUser-' + arr_uncheck[i]).val())
            }

            $.ajax({
                url: "{{ route('administrator.setting-recruitment.assign-entitle') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'recruitment_entitle': recruitment_entitle,
                    'user_id': id_user,
                    'user_id_uncheck': id_user_uncheck
                },
                dataType: "JSON",
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        type: "success"
                    }).then(function () {
                        $('#modal-assign').modal('hide')
                    });
                },
            })
        })
    }

    function defaultCheckAll(checkAll = false) {
        if (($('.checkUser:visible:checked').length == $('.checkUser:visible').length && $('.checkUser:visible')
                .length) || checkAll)
            $('#checkTopUser').prop('checked', true)
        else
            $('#checkTopUser').prop('checked', false)
    }

    $(function () {
        $('#searchUser').keyup(function () {
            var val = $(this).val().toLowerCase()
            $('#tableList tr.search').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
            })
            defaultCheckAll()
        })
        $('#checkTopUser').click(function () {
            $('.checkUser:visible').prop('checked', $(this).prop('checked'))
        })
    });

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
            lengthChange: false,
            searching: false,
            oSearch: { "bSmart": false, "bRegex": true },
            processing: true,
            serverSide: true,
            fixedHeader: true,
            scrollCollapse: true,
            scrollX: true,
            ajax: {"url": "{{ route('ajax.table.job_category') }}", "type": "GET","data":{}},
            columns: [
                {
                    "data": "id",
                    "orderable": false,
                    "name":"id"
                },
                { "data": "name"},
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

    function addCategory() {
        $('#modal-add').modal('show');
    }

    function remove(id) {
        swal({
            title: 'Are you sure?',
            text: "Once deleted, you will not be able to recover job category!",
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "job-category/"+id,
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
        $.ajax({
            url: 'job-category/get/'+id,
            type: "GET",
            data:{'_token':"{{csrf_token()}}"},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                if (data.status == 'success') {
                    $("#name").val(data.data.name);
                    $('#job_category_id').val(id);
                    $('#modal-edit').modal('show');
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
    }

    function reload_table()
    {
        $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
    }


    $('#form').on('submit',function () {
        var form = $('#form')[0]; // You need to use standart javascript object here
        var formData = new FormData(form);
        formData.append('_token','{{csrf_token()}}');
        $.ajax({
            url: "{{route('administrator.job-category.store')}}",
            type: "POST",
            data:formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status == 'success') {
                    swal("Success!", data.message, "success");
                    $('#form')[0].reset();
                    $('#modal-add').modal('hide');
                    reload_table();
                } else {
                    swal("Failed!", data.message, "error");
                }
                console.log(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
        return false;
    });

    $('#form-edit').on('submit',function () {
        var form = $('#form-edit')[0]; // You need to use standart javascript object here
        var formData = new FormData(form);
        formData.append('_token','{{csrf_token()}}');
        $.ajax({
            url: "job-category/"+formData.get('id'),
            type: "POST",
            data:formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status == 'success') {
                    swal("Success!", data.message, "success");
                    $('#form-edit')[0].reset();
                    $('#modal-edit').modal('hide');
                    reload_table();
                } else {
                    swal("Failed!", data.message, "error");
                }
                console.log(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
        return false;
    });
</script>
@endsection

@endsection