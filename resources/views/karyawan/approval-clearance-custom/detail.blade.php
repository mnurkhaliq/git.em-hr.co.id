@extends('layouts.karyawan')

@section('title', 'Exit Clearance Form')

@section('sidebar')

@endsection

@section('content')
<style>
    #table_clearance thead tr th{
        vertical-align: middle;
        text-align: center;
    }
    #table_clearance tbody tr td{
        vertical-align: middle;
    }
</style>
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Exit Clearance</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Exit Clearance </li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" autocomplete="off" enctype="multipart/form-data" action="{{ route('karyawan.approval.clearance-custom.proses') }}" method="POST" id="exit_clearance_form">
                <div class="col-md-12">
                    <div class="white-box">
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
                        <input type="hidden" name="id" value="{{$id}}">
                        <div class="form-group">
                            <label class="col-md-12">FACILITIES RETURN</label>
                            <div class="col-md-12">
                                <table class="table table-bordered" id="table_clearance">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" width="70">NO</th>
                                            <th rowspan="2">ASSET NUMBER</th>
                                            <th rowspan="2">ASSET NAME</th>
                                            <th rowspan="2">ASSET TYPE</th>
                                            <th rowspan="2">SERIAL/PLAT NUMBER</th>
                                            <th rowspan="2">PURCHASE/RENTAL DATE</th>
                                            <th rowspan="2">ASSET OWNERSHIP</th>
                                            <th colspan="2">ASSET CONDITION</th>
                                            <th rowspan="2">HANDOVER DATE</th>
                                            <th rowspan="2">APPROVAL CHECKED</th>
                                            <th rowspan="2">NOTE USER</th>
                                            <th rowspan="2">NOTE APPROVAL</th>
                                        </tr>
                                        <tr>
                                            <th  width="8%">HANDED OVER</th>
                                            <th  width="10%">RETURNED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($no = 0)
                                        @php($hasApproved = 1)
                                        @foreach($data as $k => $item)
                                        @php($no++)
                                        <tr class="oninput">
                                            <td class="text-center">{{ $no }}</td>   
                                            <td>{{ $item->asset->asset_number }}</td>
                                            <td>{{ $item->asset->asset_name }}</td>
                                            <td>{{ isset($item->asset->asset_type->name) ? $item->asset->asset_type->name : ''  }}</td>
                                            <td>{{ $item->asset->asset_sn }}</td>
                                            <td>{{ format_tanggal($item->asset->purchase_date) }}</td>
                                            <td>{{ $item->asset->status_mobil }}</td>
                                            <td>{{ $item->asset->asset_condition }}</td>
                                            <td>
                                                <select name="asset_condition[{{$no}}]" class="form-control" {{ $item->approval_check == 1 || $item->asset->asset_type->pic_department != $type->nama_approval || $exit->status_clearance == 2 ? 'disabled' : '' }}>
                                                    <option selected disabled>- Asset Condition -</option>
                                                    @foreach($asset_conditions as $condition)
                                                        <option value="{{$condition}}" {{$condition == $item->asset_condition?"selected":""}}>{{$condition}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $item->asset->handover_date != "" ?  format_tanggal($item->asset->handover_date) : '' }}</td>
                                            
                                            @if($item->asset->asset_type->pic_department == $type->nama_approval)
                                                <input type="hidden" name="asset[{{$no}}]" value="{{ $item->id }}" />
                                                <input type="hidden" name="status" value="accept">
                                                <td style="text-align: center;">
                                                    <input type="checkbox" value="1" {{ $item->approval_check == 1 ? 'checked disabled' : ($exit->status_clearance == 2 ? 'disabled' : 'required') }} name="approval_check[{{$no}}]">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control catatan" value="{{ $item->catatan_user }}" disabled/>
                                                </td>
                                                <td>
                                                    <input type="text" name="catatan[{{$no}}]" class="form-control catatan" value="{{ $item->catatan }}" {{ $item->approval_check == 1 || $exit->status_clearance == 2 ? 'disabled' : 'required' }} />
                                                </td>
                                                @if(!$item->approval_check && $exit->status_clearance == 0)
                                                    @php($hasApproved = 0)
                                                @endif
                                            @else
                                                <td style="text-align: center;">
                                                    <input type="checkbox" value="1" disabled {{ $item->approval_check == 1 ? 'checked' : '' }} name="approval_check[{{$no}}]">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control catatan" value="{{ $item->catatan_user }}" disabled/>
                                                </td>
                                                <td>
                                                    <input type="text" name="catatan[{{$no}}]" class="form-control catatan" value="{{ $item->catatan }}" disabled/>
                                                </td>
                                            @endif
                                        
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="{{ route('karyawan.approval.clearance-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                                @if($hasApproved == 0)
                                <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit_form"><i class="fa fa-check"></i> Approve</a>
                                <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_tolak"><i class="fa fa-close"></i> Reject</a>
                                @endif
                                
                            </div>
                        </div>

                    </div>
                </div>     
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

<script type="text/javascript">

    function form_validate() {
        var validate = true;
        $('.oninput input').each(function(){
            if($(this).val() == null || $(this).val() == "" && ($(this).prop('required')))
            {
                $(this).parent().addClass('has-error');
                validate = false;
            }
        });

        $('.oninput input:checkbox').each(function(){
            if(!this.checked && ($(this).prop('required'))){
                $(this).parent().addClass('has-error');
                validate = false;
            }
        });

        $('.oninput select').each(function(){

            if($(this).val() == null || $(this).val() == "" && ($(this).prop('required')))
            {
                $(this).parent().addClass('has-error');
                validate = false;
            }
        });
        return validate;
    }

    $('#submit_form').click(function(){
        $('.oninput').find('td').removeClass("has-error");

        var validate = form_validate();
        if(!validate){
            window.alert("Form not completed. Please check and resubmit");
        }
        else{
            bootbox.confirm("Do you want to update this form ?", function(result){
                if(result)
                {
                    $("#exit_clearance_form").submit()
                }
            });
        }

    });

    $('#btn_tolak').click(function(){
        bootbox.confirm("Do you want to reject this form ?", function(result){
            $("input[name='status']").val('reject');
            if(result)
            {
                $("#exit_clearance_form").submit()
            }
        });
    });

    jQuery('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
    });
</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
