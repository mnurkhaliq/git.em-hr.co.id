@extends('layouts.administrator')

@section('title', 'Edit Loan Plafond Setting')

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
                <h4 class="page-title">Setting Loan Plafond</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Loan Plafond</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.loan-setting.update-plafond', $data->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Edit Plafond</h3>
                        <hr />
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-12">Position <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control" name="organisasi_position_id" required>
                                        <option value=""> - choose Position - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}" {{ $data->organisasi_position_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Type <span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <select class="form-control" name="type" required>
                                        <option value=""> - choose Type - </option>
                                        @foreach((checkModule(13) ? [1 => 'Payroll Default Salary', 2 => 'Custom'] : [2 => 'Custom']) as $index => $item)
                                        <option value="{{ $index }}" {{ $data->type == $index ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Plafond (IDR)<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" step="any" name="plafond" class="form-control price_format" value="{{ $data->plafond }}" required>
                            </div>
                            <div class="col-md-6" id="plafond_notice" style="color: red; {{ $data->type == 1 ? '' : 'display: none;' }}" >
                                *Will be used as plafond if employee does not have Payroll Default
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
            <form id="cancel" action="{{ route('administrator.loan-setting.index') }}" method="GET">
                <input type="hidden" name="tab" value="plafond" />
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
@section('footer-script')
<script type="text/javascript">
    $('select[name="type"]').change(function() {
        if ($(this).val() == 1) {
            $("#plafond_notice").show();
        } else {
            $("#plafond_notice").hide();
        }
    })  
</script>
@endsection

@endsection