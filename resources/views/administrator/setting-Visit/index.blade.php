@extends('layouts.administrator')

@section('title', 'Visit Setting')

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
                <h4 class="page-title">Setting Master Visit</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Visit</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#VisitActivity" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Visit Activity</span></a></li>
                        <li role="presentation" class=""><a href="#VisitType" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Visit Type</span></a></li>
                        <li role="presentation" class=""><a href="#BranchPic" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Branch PIC</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="VisitActivity">
                            <h3 class="box-title m-b-0">Setting for Visit Activity</h3>
                            <a href="{{ route('administrator.setting-Visit.create') }}" class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Visit Activity</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Category</th>
                                            <th>Activity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->CategoryActivityVisit->master_category_name}}</td>
                                            <td>{{ $item->activityname }}</td>
                                            <td>
                                                <a href="{{ route('administrator.setting-Visit.edit', $item->id) }}" style="float: left; margin-right:5px"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.setting-Visit.destroy', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post" style="margin-left: 5px;">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="VisitType">
                            <h3 class="box-title m-b-0">Assign Visit Type For Users</h3>
                            <br />
                            <div class="table-responsive">
                                <table id="data_table_no_pagging" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Visit Type</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listtype as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{$item->master_visit_type_name}}</td>
                                            <td></td>
                                            <td>
                                                <button onclick="assignTo('{{$item->id}}')" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="BranchPic">
                            <h3 class="box-title m-b-0">Setting for Branch PIC</h3>
                            <a href="{{ route('administrator.setting-Visit.create-branch-pic') }}" class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Branch PIC</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table id="data_table2_no_search" class="stripe" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Branch</th>
                                            <th>Branch PIC</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cabangpicmaster as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->branchname->name}}</td>
                                            <td>{{ $item->picname }}</td>
                                            <td>
                                                <a href="{{ route('administrator.setting-Visit.destroy-branch-pic', $item->id) }}" onclick="return confirm('Are you sure you want to delete this item?')"> <button class="btn btn-danger btn-xs m-r-5"><i class="fa fa-trash"></i> delete</button></a>
                                                <button onclick="assignTo2('{{$item->id}}')" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>
                                            </td>
                                        </tr>
                                        @endforeach

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
                    <h4 class="modal-title"><strong>Assign To</strong></h4>
                </div>
                <div class="modal-body" id="modal-assign-body">
                    <div class="table-responsive">
                        <input type="hidden" id="IdVisitType">
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
    <div class="modal fade none-border" id="modal-assign2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Assign To Users Visit Type Lock</strong></h4>
                </div>
                <div class="modal-body" id="modal-assign2-body">
                    <div class="table-responsive">
                        <input type="hidden" id="IdBranchPicMaster">
                        <input type="text" class="form-control" id="searchUser2">
                        <br />
                        <table class="table" id="tableList2">
                            <tr>
                                <th><input type="checkbox" id="checkTopUser2"></th>
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
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript">
    
    function assignTo(v) {
        $.ajax({
            url: "setting-Visit/user-list-for-assignment/" + v,
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function(data) {
                if (data.message == 'success') {
                    $('#IdVisitType').val(v)
                    $('#tableList').find('tr:gt(0)').remove()
                    for (var i = 0; i < data.data.length; i++) {
                        var num = i + 1;
                        if (data.data[i].master_visit_type_id == v) {
                            var pos = data.data[i].position != null ? data.data[i].position : '-'
                            var div = data.data[i].division != null ? data.data[i].division : '-'
                            $('#tableList tr:last').after(
                                '<tr class="search">' +
                                '<td><input id="checkUser' + num + '" type="checkbox" class="checkUser" checked></td>' +
                                '<td><input id="idUser' + num + '" type="hidden" value="' + data.data[i].id + '">' + num + '</td>' +
                                '<td>' + data.data[i].nik + '</td>' +
                                '<td>' + data.data[i].name + '</td>' +
                                '<td>' + pos + '</td>' +
                                '<td>' + div + '</td>' +
                                '</tr>'
                            )
                        } else {
                            var pos = data.data[i].position != null ? data.data[i].position : '-'
                            var div = data.data[i].division != null ? data.data[i].division : '-'
                            $('#tableList tr:last').after(
                                '<tr class="search">' +
                                '<td><input id="checkUser' + num + '" type="checkbox" class="checkUser"></td>' +
                                '<td><input id="idUser' + num + '" type="hidden" value="' + data.data[i].id + '">' + num + '</td>' +
                                '<td>' + data.data[i].nik + '</td>' +
                                '<td>' + data.data[i].name + '</td>' +
                                '<td>' + pos + '</td>' +
                                '<td>' + div + '</td>' +
                                '</tr>'
                            )
                        }
                    }
                    $('#tableList tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignVisitType" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign').modal('show')
                    assign()
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
        // $('#modal-assign').modal('show')
    }

    function assign() {
        $('#assignVisitType').click(function() {
            var arr_check = []
            var arr_uncheck = []
            var id_user = []
            var id_user_uncheck = []
            var id_master_visit_type = $('#IdVisitType').val()

            $('.checkUser').each(function(i) {
                if ($(this).prop('checked') == true) {
                    arr_check.push(i + 1)
                }
            })

            $('.checkUser').each(function(i) {
                if (!$(this).prop('checked') == true) {
                    arr_uncheck.push(i + 1)
                }
            })

            for (var i = 0; i < arr_check.length; i++) {
                id_user.push($('#idUser' + arr_check[i]).val())
            }

            for (var i = 0; i < arr_uncheck.length; i++) {
                id_user_uncheck.push($('#idUser' + arr_uncheck[i]).val())
            }
            console.log(id_user)

            $.ajax({
                url: "{{ route('administrator.setting-Visit.assign-visittype') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'master_visit_type_id': id_master_visit_type,
                    'user_id': id_user,
                    'user_id_uncheck': id_user_uncheck
                },
                dataType: "JSON",
                success: function(data) {
                    swal({title: "Success!", text: data.message, type:"success"}).then(function(){ 
                        location.reload();
                        }
                        );
                    //swal("Success!", text:data.message, "success");
                    //$('#VisitType')[0].reset()
                    //location.reload()
                    //console.log(data)
                },
            })
        })
    }

    function assignTo2(v) {
        $.ajax({
            url: "setting-Visit/user-list-for-assignmentpic/" + v,
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function(data) {
                if (data.message == 'success') {
                    $('#IdBranchPicMaster').val(v)
                    $('#tableList2').find('tr:gt(0)').remove()
                    for (var i = 0; i < data.data.length; i++) {
                        var num = i + 1;
                        if (data.data[i].cabangpicmaster_id == v) {
                            var pos2 = data.data[i].position != null ? data.data[i].position : '-'
                            var div2 = data.data[i].division != null ? data.data[i].division : '-'
                            $('#tableList2 tr:last').after(
                                '<tr class="search">' +
                                '<td><input id="checkUser2' + num + '" type="checkbox" class="checkUser2" checked></td>' +
                                '<td><input id="idUser2' + num + '" type="hidden" value="' + data.data[i].id + '">' + num + '</td>' +
                                '<td>' + data.data[i].nik + '</td>' +
                                '<td>' + data.data[i].name + '</td>' +
                                '<td>' + pos2 + '</td>' +
                                '<td>' + div2 + '</td>' +
                                '</tr>'
                            )
                        } else {
                            var pos2 = data.data[i].position != null ? data.data[i].position : '-'
                            var div2 = data.data[i].division != null ? data.data[i].division : '-'
                            $('#tableList2 tr:last').after(
                                '<tr class="search">' +
                                '<td><input id="checkUser2' + num + '" type="checkbox" class="checkUser2"></td>' +
                                '<td><input id="idUser2' + num + '" type="hidden" value="' + data.data[i].id + '">' + num + '</td>' +
                                '<td>' + data.data[i].nik + '</td>' +
                                '<td>' + data.data[i].name + '</td>' +
                                '<td>' + pos2 + '</td>' +
                                '<td>' + div2 + '</td>' +
                                '</tr>'
                            )
                        }
                    }
                    $('#tableList2 tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignBranchPic" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign2').modal('show')
                    assign2()
                } else {
                    $('#tableList2').find('tr:gt(0)').remove()
                    $('#tableList2 tr:last').after(
                        '<tr>' +
                        '<td colspan="6">No data.</td>' +
                        '</tr>'
                    )
                    $('#modal-assign2').modal('show')
                }
            }
        })
        // $('#modal-assign').modal('show')
    }

    function assign2() {
        $('#assignBranchPic').click(function() {

            var arr_check2 = []
            var arr_uncheck2 = []
            var id_user2 = []
            var id_user_uncheck2 = []
            var cabangpicmaster_id = $('#IdBranchPicMaster').val()

            $('.checkUser2').each(function(i) {
                if ($(this).prop('checked') == true) {
                    arr_check2.push(i + 1)
                }
            })

            $('.checkUser2').each(function(i) {
                if (!$(this).prop('checked') == true) {
                    arr_uncheck2.push(i + 1)
                }
            })

            for (var i = 0; i < arr_check2.length; i++) {
                id_user2.push($('#idUser2' + arr_check2[i]).val())
            }

            for (var i = 0; i < arr_uncheck2.length; i++) {
                id_user_uncheck2.push($('#idUser2' + arr_uncheck2[i]).val())
            }
            // console.log(id_user)

            $.ajax({
                url: "{{ route('administrator.setting-Visit.assign-branchpic') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'cabangpicmaster_id': cabangpicmaster_id,
                    'user_id2': id_user2,
                    'user_id_uncheck2': id_user_uncheck2
                },
                dataType: "JSON",
                success: function(data) {
                    swal({title: "Success!", text: data.message, type:"success"}).then(function(){ 
                        location.reload();
                        }
                        );
                    // console.log(data)
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

    function defaultCheckAll2(checkAll = false) {
        if (($('.checkUser2:visible:checked').length == $('.checkUser2:visible').length && $('.checkUser2:visible')
                .length) || checkAll)
            $('#checkTopUser2').prop('checked', true)
        else
            $('#checkTopUser2').prop('checked', false)
    }

    $(function () {
        $('#searchUser').keyup(function () {
            var val = $(this).val().toLowerCase()
            $('#tableList tr.search').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
            })
            defaultCheckAll()
        })
        $('#searchUser2').keyup(function () {
            var val = $(this).val().toLowerCase()
            $('#tableList2 tr.search').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
            })
            defaultCheckAll2()
        })
        $('#checkTopUser').click(function () {
            $('.checkUser:visible').prop('checked', $(this).prop('checked'))
        })
        $('#checkTopUser2').click(function () {
            $('.checkUser2:visible').prop('checked', $(this).prop('checked'))
        })
    });
    </script>
@endsection

@endsection