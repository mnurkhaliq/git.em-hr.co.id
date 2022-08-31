@extends('layouts.administrator')

@section('title', 'Add Bank CV Option Setting')

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
                <h4 class="page-title">Form Bank CV Option Setting</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Bank CV Option Setting</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data"
                action="{{ route('administrator.setting-bank-cv.store') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Add Bank CV Option Setting</h3>
                        <hr />

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-12">Name<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_dropdown" class="switch-input" />
                                <label style="margin-left: 5px;">Dropdown</label>
                                <input type="radio" name="is_dropdown" id="is_dropdown_on" value="1" hidden>
                                <input type="radio" name="is_dropdown" id="is_dropdown_off" value="0" checked hidden>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_date" class="switch-input" />
                                <label style="margin-left: 5px;">Show Updated Date</label>
                                <input type="radio" name="is_date" id="is_date_on" value="1" hidden>
                                <input type="radio" name="is_date" id="is_date_off" value="0" checked hidden>
                            </div>
                        </div>
                        <div class="form-group" id="date_name" style="display:none">
                            <label class="col-md-12">Updated Date Field Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="date_name" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_list" class="switch-input" />
                                <label style="margin-left: 5px;">Show in List</label>
                                <input type="radio" name="is_list" id="is_list_on" value="1" hidden>
                                <input type="radio" name="is_list" id="is_list_off" value="0" checked hidden>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_filter" class="switch-input" />
                                <label style="margin-left: 5px;">Show as Filter</label>
                                <input type="radio" name="is_filter" id="is_filter_on" value="1" hidden>
                                <input type="radio" name="is_filter" id="is_filter_off" value="0" checked hidden>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-default waves-effect waves-light m-r-10" onclick="document.getElementById('cancel').submit()"><i class="fa fa-arrow-left"></i> Cancel</button>
                            <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save</button>
                            <br style="clear: both;" />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
            <form id="cancel" action="{{ route('administrator.setting-bank-cv.index') }}" method="GET"></form>
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@section('js')
<script>
    $('#is_dropdown').on('change',function () {
        if ($(this).is(':checked')) {
            $("#is_dropdown_on").prop("checked", true);
        }
        else {
            $("#is_dropdown_off").prop("checked", true);
        }
    });

    $('#is_date').on('change',function () {
        if ($(this).is(':checked')) {
            $("#is_date_on").prop("checked", true);
            $("#date_name").show();
            $("#date_name input").attr("required", true);
        }
        else {
            $("#is_date_off").prop("checked", true);
            $("#date_name").hide();
            $("#date_name input").val('').removeAttr("required");
        }
    });

    $('#is_list').on('change',function () {
        if ($(this).is(':checked'))
            $("#is_list_on").prop("checked", true);
        else
            $("#is_list_off").prop("checked", true);
    });

    $('#is_filter').on('change',function () {
        if ($(this).is(':checked'))
            $("#is_filter_on").prop("checked", true);
        else
            $("#is_filter_off").prop("checked", true);
    });
</script>
@endsection
@endsection