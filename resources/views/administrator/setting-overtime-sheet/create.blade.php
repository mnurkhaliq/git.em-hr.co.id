@extends('layouts.administrator')

@section('title', 'Add Overtime Payment Setting')

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
                <h4 class="page-title">Form Overtime Payment Setting</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Overtime Payment Setting</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data"
                action="{{ route('administrator.setting-overtime-sheet.store') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Add Overtime Payment Setting</h3>
                        <hr />

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-12">Name<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Type<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control" name="overtime_payroll_type_id"
                                    id="overtime-payroll-type-id" required>
                                    <option value="" disabled selected hidden> - choose Overtime Payment Type -
                                    </option>
                                    @foreach($overtimePayrollTypes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->description }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="fix-rate" style="display: none;">
                            <label class="col-md-12" id="fix-rate-title"></label>
                            <div class="col-md-6" id="fix-rate-value"></div>
                        </div>

                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12">
                            <a href="{{ route('administrator.setting-overtime-sheet.index') }}"
                                class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i
                                    class="fa fa-arrow-left"></i> Cancel</a>
                            <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i
                                    class="fa fa-save"></i> Save</button>
                            <br style="clear: both;" />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    var earnings = @json($earnings)

    $(document).ready(function ($) {
        getFixRate($("#overtime-payroll-type-id").val())

        $("#overtime-payroll-type-id").change(function () {
            $('#fix-rate-value').html('')
            getFixRate($(this).val())
        })

        function getFixRate(value) {
            if (value) {
                if (value != 1) {
                    $('#fix-rate-title').html('Fix Rate<span class="text-danger">*</span>')

                    $('#fix-rate-value').html('<input type="text" name="fix_rate" class="money form-control" required>')
                    $('.money').mask('000.000.000', {reverse: true})
                } else {
                    $('#fix-rate-title').html('Earnings<span class="text-danger">*</span>')

                    earnings.forEach(function myFunction(value) {
                        $('#fix-rate-value').append(
                            '<div class="col-md-6"><input type="checkbox" name="fix_rate[]" value="' + value[
                                'id'] + '" required> ' + value['title'] + '<br>')
                    })

                    $('input[type="checkbox"]').on('click', function () {
                        if ($('input[type="checkbox"]').is(':checked')) {
                            $('input[type="checkbox"]').prop('required', false);
                        } else {
                            $('input[type="checkbox"]').prop('required', true);
                        }
                    })
                }
                $('#fix-rate').show()
            }
        }
    })
</script>
@endsection