@extends('layouts.administrator')

@section('title', 'Setting Overtime')

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
                <h4 class="page-title">Setting Overtime</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting Overtime</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="{{ !$tab ? 'active' : '' }}"><a href="#OvertimeEntitlement"
                                aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span
                                    class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Overtime
                                    Entitlement</span></a></li>
                        <li role="presentation" class="{{ $tab == 'payment' ? 'active' : '' }}"><a href="#PaymentType"
                                aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span
                                    class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Overtime
                                    Payment Setting</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ !$tab ? 'active' : '' }} in"
                            id="OvertimeEntitlement">
                            <h3 class="box-title m-b-0">Assign to Overtime Entitlement</h3>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
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
                                            <td>Entitle Overtime</td>
                                            <td>
                                                <button onclick="assignToEntitle(1)" type="button"
                                                    class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i>
                                                    assign</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Not Entitle Overtime</td>
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
                        <div role="tabpanel" class="tab-pane fade {{ $tab == 'payment' ? 'active' : '' }} in"
                            id="PaymentType">
                            <h3 class="box-title m-b-0">Setting and Assign for Overtime Payment</h3>
                            <a href="{{ route('administrator.setting-overtime-sheet.create') }}"
                                class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i
                                    class="fa fa-plus"></i> Add Payment Setting</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table id="data_table_no_pagging" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($overtimePayrolls as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->overtimePayrollType->name}}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <button onclick="assignToSetting('{{ $item->id }}')" type="button"
                                                    style="float: left; margin-right:10px"
                                                    class="btn btn-primary btn-xs"><i class="ti-check"></i>
                                                    assign</button>
                                                <a href="{{ route('administrator.setting-overtime-sheet.edit', $item->id) }}"
                                                    style="float: left; margin-right:10px"> <button
                                                        class="btn btn-info btn-xs"><i class="fa fa-edit"></i>
                                                        edit</button></a>
                                                <form
                                                    action="{{ route('administrator.setting-overtime-sheet.destroy', $item->id) }}"
                                                    onsubmit="return confirm('Delete this data?')" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-danger btn-xs m-r-5"><i
                                                            class="ti-trash"></i> delete</button>
                                                </form>
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
                    <h4 class="modal-title"><strong>Assign Users to Overtime Entitlement</strong></h4>
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
    <div class="modal fade none-border" id="modal-assign2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Assign Users to Overtime Payment Setting</strong></h4>
                </div>
                <div class="modal-body" id="modal-assign2-body">
                    <div class="table-responsive">
                        <input type="hidden" id="SettingId">
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
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}"
    rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript">
    function assignToEntitle(v) {
        $('#searchUser').val('')

        $.ajax({
            url: "setting-overtime-sheet/user-list-for-assignment/1",
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
                            (data.data[i].overtime_entitle == v ? 'checked' : '') + '></td>' +
                            '<td><input id="idUser-' + num + '" type="hidden" value="' + data.data[i]
                            .id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (data.data[i].overtime_entitle != v)
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
            var overtime_entitle = $('#entitleId').val()

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
                url: "{{ route('administrator.setting-overtime-sheet.assign-entitle') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'overtime_entitle': overtime_entitle,
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

    function assignToSetting(v) {
        $('#searchUser2').val('')

        $.ajax({
            url: "setting-overtime-sheet/user-list-for-assignment/2",
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function (data) {
                if (data.message == 'success') {
                    $('#SettingId').val(v)
                    $('#tableList2').find('tr:gt(0)').remove()
                    let checkAll = data.data.length ? true : false
                    for (let i = 0; i < data.data.length; i++) {
                        let num = i + 1;
                        let pos = data.data[i].position != null ? data.data[i].position : '-'
                        let div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableList2 tr:last').after(
                            '<tr class="search">' +
                            '<td><input id="checkUser2' + num +
                            '" type="checkbox" class="checkUser2" ' + (data.data[i]
                                .overtime_payroll_id == v ? 'checked' : '') + '></td>' +
                            '<td><input id="idUser2-' + num + '" type="hidden" value="' + data.data[i]
                            .id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (data.data[i].overtime_payroll_id != v)
                            checkAll = false
                    }
                    $('#tableList2 tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignSetting" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign2').modal('show')
                    assignSetting()
                    defaultCheckAll2(checkAll)
                    $('.checkUser2').click(function () {
                        defaultCheckAll2()
                    })
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
    }

    function assignSetting() {
        $('#assignSetting').click(function () {

            var arr_check2 = []
            var arr_uncheck2 = []
            var id_user2 = []
            var id_user_uncheck2 = []
            var overtime_payroll_id = $('#SettingId').val()

            $('.checkUser2').each(function (i) {
                if ($(this).prop('checked') == true)
                    arr_check2.push(i + 1)
                else
                    arr_uncheck2.push(i + 1)
            })

            for (var i = 0; i < arr_check2.length; i++) {
                id_user2.push($('#idUser2-' + arr_check2[i]).val())
            }

            for (var i = 0; i < arr_uncheck2.length; i++) {
                id_user_uncheck2.push($('#idUser2-' + arr_uncheck2[i]).val())
            }

            $.ajax({
                url: "{{ route('administrator.setting-overtime-sheet.assign-setting') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'overtime_payroll_id': overtime_payroll_id,
                    'user_id': id_user2,
                    'user_id_uncheck': id_user_uncheck2
                },
                dataType: "JSON",
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        type: "success"
                    }).then(function () {
                        $('#modal-assign2').modal('hide')
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