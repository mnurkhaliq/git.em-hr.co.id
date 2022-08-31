@extends('layouts.administrator')

@section('title', 'Timesheet Setting')

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
                <h4 class="page-title">Setting Timesheet</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Timesheet</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="{{ !isset($tab) ? 'active' : '' }}"><a href="#Category" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Category</span></a></li>
                        <li role="presentation" class="{{ isset($tab) && $tab == 'activity' ? 'active' : '' }}"><a href="#Activity" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Activity</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade {{ !isset($tab) ? 'active in' : '' }}" id="Category">
                            <h3 class="box-title m-b-0">Setting for Timesheet Category Activity</h3>
                            <a href="{{ route('administrator.setting-timesheet.create') }}" class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Category</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Category Name</th>
                                            <th>Total Approval</th>
                                            <th>Status</th>
                                            <th width="40%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($categories as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->settingApproval->count() }}</td>
                                            <td>
                                                @if(!$item->deleted_at)
                                                    Active
                                                @else
                                                    Disabled
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('administrator.setting-timesheet.edit', $item->id) }}" style="float: left; margin-right:5px"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> Edit</button></a>
                                                <form hidden id="permanent-delete-category-{{$item->id}}" action="{{ route('administrator.setting-timesheet.destroy', $item->id) }}" onsubmit="return confirm('Delete this category will also delete its activity and cant be undone, continue?')" method="post" style="margin-left: 5px;">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="delete_status" value="1">
                                                </form>
                                                <button onclick="permanentDeleteCategory('{{$item->id}}')" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> Delete</button>
                                                @if(!$item->deleted_at)
                                                    <form hidden id="delete-category-{{$item->id}}" action="{{ route('administrator.setting-timesheet.destroy', $item->id) }}" onsubmit="return confirm('Disable this category will also disable its activity, continue?')" method="post" style="margin-left: 5px;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                    </form>
                                                    <button onclick="deleteCategory('{{$item->id}}')" class="btn btn-warning btn-xs m-r-5"><i class="ti-lock"></i> Disable</button>
                                                @else
                                                    <form hidden id="restore-category-{{$item->id}}" action="{{ route('administrator.setting-timesheet.restore', $item->id) }}" onsubmit="return confirm('Activate this category will also activate its activity, continue?')" method="post" style="margin-left: 5px;">
                                                        {{ csrf_field() }}
                                                    </form>
                                                    <button onclick="restoreCategory('{{$item->id}}')" class="btn btn-success btn-xs m-r-5"><i class="ti-key"></i> Activate</button>
                                                @endif
                                                <button onclick="assignTo('{{$item->id}}')" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign Approval</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <br />
                            <h3 class="box-title m-b-0">Summary Table</h3>
                            <div class="row">
                                <div class="col-md-offset-8 col-md-4">
                                    <form action="{{ route('administrator.setting-timesheet.index') }}" method="GET">
                                        <input type="hidden" name="activity_category" value="{{ $activity_category }}" />
                                        <div class="col-md-offset-4 col-md-7">
                                            <select name="summary_category" class="form-control form-control-line" autocomplete="off">
                                                <option value="" selected>- Select Category -</option>
                                                @foreach($categories as $no => $item)
                                                <option value="{{ $item->id }}" {{ $summary_category == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 p-0">
                                            <button type="submit" id="filter_view" class="btn btn-default btn-sm pull-right btn-outline" autocomplete="off"> <i class="fa fa-search-plus"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Category Name</th>
                                            <th>User NIK</th>
                                            <th>User Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($no = 0)
                                        @foreach($summary_categories as $item)
                                        @foreach($item->settingApproval as $approver)
                                        <tr>
                                            <td>{{ ++$no }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $approver->user->nik }}</td>
                                            <td>{{ $approver->user->name }}</td>
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade {{ isset($tab) && $tab == 'activity' ? 'active in' : '' }}" id="Activity">
                            <h3 class="box-title m-b-0">Setting for Timesheet Activity</h3>
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{ route('administrator.setting-timesheet.create-activity') }}" class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Activity</a>
                                </div>
                                <div class="col-md-4">
                                    <form action="{{ route('administrator.setting-timesheet.index') }}" method="GET">
                                        <input type="hidden" name="tab" value="activity" />
                                        <input type="hidden" name="summary_category" value="{{ $summary_category }}" />
                                        <div class="col-md-offset-4 col-md-7">
                                            <select name="activity_category" class="form-control form-control-line" autocomplete="off">
                                                <option value="" selected>- Select Category -</option>
                                                @foreach($categories as $no => $item)
                                                <option value="{{ $item->id }}" {{ $activity_category == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 p-0">
                                            <button type="submit" id="filter_view" class="btn btn-default btn-sm pull-right btn-outline" autocomplete="off"> <i class="fa fa-search-plus"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <br />
                            <div class="table-responsive">
                                <table class="data_table_no_pagging display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Category Name</th>
                                            <th>Activity Name</th>
                                            <th>Status</th>
                                            <th width="40%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activities as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->timesheetCategory->name }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @if(!$item->deleted_at)
                                                    Active
                                                @else
                                                    Disabled
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('administrator.setting-timesheet.edit-activity', $item->id) }}" style="float: left; margin-right:5px"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> Edit</button></a>
                                                <form hidden id="permanent-delete-activity-{{$item->id}}" action="{{ route('administrator.setting-timesheet.destroy-activity', $item->id) }}" onsubmit="return confirm('Delete this activity cant be undone, continue?')" method="post" style="margin-left: 5px;">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <input type="hidden" name="delete_status" value="1">
                                                </form>
                                                <button onclick="permanentDeleteActivity('{{$item->id}}')" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> Delete</button>
                                                @if(!$item->deleted_at)
                                                    <form hidden id="delete-activity-{{$item->id}}" action="{{ route('administrator.setting-timesheet.destroy-activity', $item->id) }}" onsubmit="return confirm('Disable this activity?')" method="post" style="margin-left: 5px;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}
                                                    </form>
                                                    <button onclick="deleteActivity('{{$item->id}}')" class="btn btn-warning btn-xs m-r-5"><i class="ti-lock"></i> Disable</button>
                                                @else
                                                    @if(!$item->timesheetCategory->deleted_at)
                                                        <form hidden id="restore-activity-{{$item->id}}" action="{{ route('administrator.setting-timesheet.restore-activity', $item->id) }}" onsubmit="return confirm('Activate this activity?')" method="post" style="margin-left: 5px;">
                                                            {{ csrf_field() }}
                                                        </form>
                                                        <button onclick="restoreActivity('{{$item->id}}')" class="btn btn-success btn-xs m-r-5"><i class="ti-key"></i> Activate</button>
                                                    @else
                                                        <button type="button" class="btn btn-success btn-xs m-r-5" disabled><i class="ti-key"></i> Activate</button>
                                                        <label class="label-warning">Activate category first</label>
                                                    @endif
                                                @endif
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
                        <input type="hidden" id="idCategory">
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
@section('footer-script')
<style>
    .label-warning {
        background: #ffa82b;
        border: 1px solid #ffa82b;
        border-radius: 3px;
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
        color: white;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    function deleteCategory(id) {
        $('#delete-category-'+id).submit();
    }

    function restoreCategory(id) {
        $('#restore-category-'+id).submit();
    }

    function permanentDeleteCategory(id) {
        $('#permanent-delete-category-'+id).submit();
    }

    function deleteActivity(id) {
        $('#delete-activity-'+id).submit();
    }

    function restoreActivity(id) {
        $('#restore-activity-'+id).submit();
    }

    function permanentDeleteActivity(id) {
        $('#permanent-delete-activity-'+id).submit();
    }

    function assignTo(v){
        $('#searchUser').val('')

        $.ajax({
            url: "setting-timesheet/user-list-for-assignment/"+v,
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function (data) {
                if (data.message == 'success') {
                    $('#idCategory').val(v)
                    $('#tableList').find('tr:gt(0)').remove()
                    let checkAll = data.data.length ? true : false
                    for (let i = 0; i < data.data.length; i++) {
                        let num = i + 1;
                        let pos = data.data[i].position != null ? data.data[i].position : '-'
                        let div = data.data[i].division != null ? data.data[i].division : '-'
                        $('#tableList tr:last').after(
                            '<tr class="search">' +
                            '<td><input id="checkUser' + num + '" type="checkbox" class="checkUser" ' +
                            (data.data[i].setting_approval_timesheet_transaction_item_id ? 'checked' : '') + '></td>' +
                            '<td><input id="idUser-' + num + '" type="hidden" value="' + data.data[i]
                            .id + '">' + num + '</td>' +
                            '<td>' + data.data[i].nik + '</td>' +
                            '<td>' + data.data[i].name + '</td>' +
                            '<td>' + pos + '</td>' +
                            '<td>' + div + '</td>' +
                            '</tr>'
                        )
                        if (!data.data[i].setting_approval_timesheet_transaction_item_id)
                            checkAll = false
                    }
                    $('#tableList tr:last').after(
                        '<tr>' +
                        '<td colspan="6"><button id="assignCategory" type="button" class="btn btn-primary btn-xs m-r-5"><i class="ti-check"></i> Assign</button>' +
                        '</tr>'
                    )
                    $('#modal-assign').modal('show')
                    assignCategory()
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

    function assignCategory() {
        $('#assignCategory').click(function () {
            var arr_check = []
            // var arr_uncheck = []
            var id_user = []
            // var id_user_uncheck = []
            var category_id = $('#idCategory').val()

            $('.checkUser').each(function (i) {
                if ($(this).prop('checked') == true)
                    arr_check.push(i + 1)
                // else
                //     arr_uncheck.push(i + 1)
            })

            for (var i = 0; i < arr_check.length; i++) {
                id_user.push($('#idUser-' + arr_check[i]).val())
            }

            // for (var i = 0; i < arr_uncheck.length; i++) {
            //     id_user_uncheck.push($('#idUser-' + arr_uncheck[i]).val())
            // }

            $.ajax({
                url: "{{ route('administrator.setting-timesheet.assign-approval') }}",
                type: "POST",
                data: {
                    '_token': '{{csrf_token()}}',
                    'category_id': category_id,
                    'user_id': id_user,
                    // 'user_id_uncheck': id_user_uncheck
                },
                dataType: "JSON",
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        type: "success"
                    }).then(function () {
                        // $('#modal-assign').modal('hide')
                        window.location.href = "{{ route('administrator.setting-timesheet.index') }}";
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