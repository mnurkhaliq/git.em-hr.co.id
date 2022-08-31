@extends('layouts.administrator')

@section('title', 'End Contract Mail Scheduler Setting')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">End Contract Mail Scheduler Setting</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
                <button type="button" class="btn btn-info" onclick="form_setting.submit()"><i class="fa fa-save"></i> Save Setting</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 p-l-0">
                <form class="form-horizontal" id="form-setting" name="form_setting" enctype="multipart/form-data" action="{{ route('administrator.setting.contract-email-save') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="white-box">
                        <h3 class="m-t-0">Email Setting</h3>
                        <hr />
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="contract_mail" class="switch-input" {{ get_setting('contract_mail') ? 'checked' : '' }}/>
                                <label style="margin-left: 5px;">Mail Scheduler</label>
                                <input type="radio" name="setting[contract_mail]" id="contract_mail_on" value="1" {{ get_setting('contract_mail') ? 'checked' : '' }} hidden>
                                <input type="radio" name="setting[contract_mail]" id="contract_mail_off" value="0" {{ get_setting('contract_mail') ? '' : 'checked' }} hidden>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Send Before End Contract in Days</label>
                            <div class="col-md-12">
                                <input type="number" min=0 class="form-control" name="setting[contract_mail_before]" value="{{ get_setting('contract_mail_before') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Mail Subject</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="setting[contract_mail_subject]" value="{{ get_setting('contract_mail_subject') }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Mail Body</label>
                            <div class="col-md-12">
                                <textarea class="form-control" name="setting[contract_mail_body]" style="height: 120px;" id="ckeditor">{{ get_setting('contract_mail_body') }}</textarea>
                            </div>
                            <label class="col-md-12 text-danger">*Use $name as employee name and $date as end contract date</label>
                        </div>
                    </div>
                </form>   
            </div>
            <div class="col-md-6 p-l-0">
                <div class="white-box">
                    <h3 class="m-t-0">Assign to CC</h3>
                    <hr />
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
                                    <td>Entitle CC</td>
                                    <td>
                                        <button onclick="assignToEntitle(1)" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> assign</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Not Entitle CC</td>
                                    <td>
                                        <button onclick="assignToEntitle(null)" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> assign</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 p-l-0">
                <form class="form-horizontal" method="POST" action="{{ route('administrator.setting.contract-email-test-send') }}">
                    {{ csrf_field() }}
                    <div class="white-box">
                        <h3 class="m-t-0">Send Test Email</h3>
                        <hr />
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="email" required class="form-control" name="to" placeholder="To" />
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info btn-sm">Send Test <i class="fa fa-paper-plane"></i> </button>
                            </div>
                        </div>
                    </div>
                </form>      
            </div>
        </div>
    </div>
    <div class="modal fade none-border" id="modal-assign">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Assign Users to CC Entitlement</strong></h4>
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
    @include('layouts.footer')
</div>
@section('js')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'ckeditor' );

    $('#contract_mail').on('change',function () {
        if ($(this).is(':checked')) {
            $("#contract_mail_on").prop("checked", true);
        }
        else {
            $("#contract_mail_off").prop("checked", true);
        }
    });

    function assignToEntitle(v) {
        $('#searchUser').val('')

        $.ajax({
            url: "user-list-for-assignment/1",
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
                            (data.data[i].contract_mail_cc_entitle == v ? 'checked' : '') + '></td>' +
                            '<td><input id="idUser-' + num + '" type="hidden" value="' +
                            data.data[i].id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (data.data[i].contract_mail_cc_entitle != v)
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
            var contract_mail_cc_entitle = $('#entitleId').val()

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
                url: "{{ route('administrator.setting.assign-entitle') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'contract_mail_cc_entitle': contract_mail_cc_entitle,
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
</script>
@endsection
@endsection
