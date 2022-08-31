@extends('layouts.administrator')

@section('title', 'Setting On Leave')

@section('sidebar')

@endsection

@section('content')

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

<script type="text/javascript">

    jQuery(function($) {
    $("#edit-cuti").click(function() {
        if ($("input[name='kuota']").val() == "" || $("input[name='description']").val() == "") {
            bootbox.alert('Complete all form!');
            return false;

        }
        if ($("#jenis_cuti").val() == "") {
            bootbox.alert('Please Select Leave Type');
            return false;
        }
        if ($("#metodeperhitungan_cuti").val() == "" && $("#jenis_cuti").val() == 'Annual Leave') {
            bootbox.alert('Please Select Calculated Method');
            return false;
        }
        if ($("input[name='cutoffmonth']").val() == "" && $("#jenis_cuti").val() == 'Annual Leave' && ($("#metodeperhitungan_cuti").val() == 4 || $("#metodeperhitungan_cuti").val() == 5)) {
            bootbox.alert('Please Fill Cut Off Month!');
            return false;
        }

        if ($("#jenis_cuti").val() == 'Annual Leave' && $("#iscarryforward").is(":checked") && $("#carryforwardleave").val() == ""  ) {
            bootbox.alert('Please Fill Carry Forward Leave');
            return false;
        }
    });
    });


    jQuery(function($) {
    $("#iscarryforward").click(function() {
        if ($(this).is(":checked")) {
            $("#Divcarryforwardleave").show();
        } else {
            $("#Divcarryforwardleave").hide();
            $("#carryforwardleave").val(null);
        }
    });
    });

    $(document).ready(function() {
    var checkVal = $("#jenis_cuti").val();
    var checkVal2 = $("#metodeperhitungan_cuti").val();
    // if (checkVal == 'Annual Leave' && checkVal2 != "4") {
    if (checkVal == 'Annual Leave') {
        $("#Diviscarryforward").show();
    } else {
        $("#Diviscarryforward").hide();
    }
    });

    $(document).ready(function() {
    var checkVal = $("#metodeperhitungan_cuti").val();
    if (checkVal == 5 || checkVal == 4) {
        $("#Divcutoffmonth").show();
    } else {
        $("#Divcutoffmonth").hide();
    }
    });

    jQuery(function($) {
    $('#metodeperhitungan_cuti').on('change', function() {
        if (this.value == 5 || this.value == 4)
        {
            $("#Divcutoffmonth").show();
        } else {
            $("#Divcutoffmonth").hide();
        }
    });
    });

    jQuery(function($) {
    $('#metodeperhitungan_cuti').on('change', function() {
        if (this.value == 5 || this.value == 4)
        {
            $("#Divcutoffmonth").show();
        } else {
            $("#Divcutoffmonth").hide();
        }
    });
    });



    $(document).ready(function() {
    var checkVal = $("#iscarryforward").val();
    if (checkVal == 1) {
        $("#iscarryforward").prop('checked', true);
        $("#iscarryforward").show();
        $("#Divcarryforwardleave").show();
    }
    });


    jQuery(function($) {
    $('#jenis_cuti').on('change', function() {
        if (this.value == 'Annual Leave') {
            $("#Divmetodeperhitungan_cuti").show();
            $("#Diviscarryforward").show();
        } else {
            $("#Divmetodeperhitungan_cuti").hide();
            $("#Diviscarryforward").hide();
            $("#Divcutoffmonth").hide();
        }
    });
    });

    // jQuery(function($) {
    // $('#metodeperhitungan_cuti').on('change', function() {
    //     if (this.value == 4)
    //     {
    //         $("#Diviscarryforward").hide();
    //     } else {
    //         $("#Diviscarryforward").show();
    //     }
    // });
    // });

    $(function() {
    $("#no-year-datepicker").datepicker({
        dateFormat: "d-m",
        changeMonth: true,
        changeYear: false
    });
    });

</script>

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Form Setting On Leave</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">List Setting On Leave</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.setting-master-cuti.update', $data->id) }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Update Setting On Leave</h3>
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

                        <input type="hidden"  name="_method" value="PUT">

                        {{ csrf_field() }}
                        @if($data->jenis_cuti == 'Annual Leave')
                        <input type="hidden" id="jenis_cuti" name="jenis_cuti" value="{{$data->jenis_cuti}}">
                        @else
                        
                        <div class="form-group">
                            <label class="col-md-12">Type</label>
                            <div class="col-md-6">
                               <select class="form-control" name="jenis_cuti" id="jenis_cuti">
                               <option value="">- Select - </option>
                                    @foreach(['Permit', 'Annual Leave', 'Special Leave'] as $item)
                                    <option {{ $data->jenis_cuti == $item ? 'selected' : '' }}>{{ ($item == 'Special Leave'?"Other ":"").$item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-md-12">Description</label>
                            <div class="col-md-6">
                            @if($data->jenis_cuti == 'Annual Leave')
                                <input disabled type="text" name="description" class="form-control" value="{{ $data->description }}">
                            @else
                               <input type="text" name="description" class="form-control" value="{{ $data->description }}">
                            @endif
                            </div>
                        </div>
                    
                        <div class="form-group" id="Divmetodeperhitungan_cuti"> 
                        <label class="col-md-12">Calculation Method</label>
                            <div class="col-md-6">
                               <select class="form-control form-control" name="metodeperhitungan_cuti" id="metodeperhitungan_cuti" >
                                @foreach($type as $item)
                                        <option value="{{ $item["id"] }}" {{ $item["id"]== $data->master_cuti_type_id ? 'selected' : '' }}>{{ $item["master_cuti_name"] }}</option>
                                        @endforeach
                                </select>
                                </div>
                        </div>
                        <div class="form-group" id="Divcutoffmonth" style="display: none">
                        <label class="col-md-12">CutOff Date:<span class="text-danger">*</span></label>
                        <div class="col-md-6"><input type="text" id="no-year-datepicker" id="cutoffmonth" name="cutoffmonth" value="{{ $data->cutoffmonth }}">  
                        </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Quota (Days)<span class="text-danger">*</span></label>
                            <div class="col-md-6"><input type="text" name="kuota" class="form-control" value="{{$data->kuota}}">
                            </div>
                        </div>

                        <div class="form-group" id="Diviscarryforward" style="display: none">
                            <div class="col-md-12">
                                <input type="checkbox" id="iscarryforward" name="iscarryforward" value="{{$data->iscarryforward}}"/>
                                <label style="margin-left: 5px;">Is Carry Forward</label>
                            </div>
                        </div>

                        <div class="form-group" id="Divcarryforwardleave" style="display: none">
                            <label class="col-md-12">Carry Forward Leave (Days)<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                               <input type="text" id="carryforwardleave" name="carryforwardleave" class="form-control" value="{{ $data->carryforwardleave }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_attachment" name="is_attachment" value="1" {{ $data->is_attachment ? 'checked' : '' }}/>
                                <label style="margin-left: 5px;">Supporting Document Required/Attached</label>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <br />
                        <div class="form-group">
                            <a href="{{ route('administrator.setting-master-cuti.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                            <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="edit-cuti"><i class="fa fa-save"></i> Save</button>
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
@endsection
