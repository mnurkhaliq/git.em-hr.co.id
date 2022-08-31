@extends('layouts.karyawan')

@section('title', 'Facilities Form')

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
                <h4 class="page-title">Facilities</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Facilities </li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" autocomplete="off" enctype="multipart/form-data" action="{{ route('karyawan.approval.facilities.proses') }}" method="POST" id="facilities_form">
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
                        <input type="hidden" name="id" value="{{$data->id}}">
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
                                            <th rowspan="2">SPECIFICATION</th>
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
                                        <tr>
                                            <td class="text-center">1</td>   
                                            <td>{{ $data->asset->asset_number }}</td>
                                            <td>{{ $data->asset->asset_name }}</td>
                                            <td>{{ isset($data->asset->asset_type->name) ? $data->asset->asset_type->name : ''  }}</td>
                                            <td>{{ $data->asset->asset_sn }}</td>
                                            <td>{!! isset($data->asset->spesifikasi) ? $data->asset->spesifikasi : '' !!}</td>
                                            <td>{{ format_tanggal($data->asset->purchase_date) }}</td>
                                            <td>{{ $data->asset->status_mobil }}</td>
                                            <td>{{ $data->asset->asset_condition }}</td>
                                            <td>
                                                <select name="asset_condition_return" class="form-control">
                                                    <option selected disabled>- Asset Condition -</option>
                                                    @foreach($asset_conditions as $condition)
                                                        <option value="{{$condition}}" {{ $hasApproved != NULL ? 'disabled' : '' }} {{$condition == $data->asset_condition_return?"selected":""}}>{{$condition}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $data->date_return != "" ?  format_tanggal($data->date_return) : '' }}</td>
                                            
                                            @if($data->asset->asset_type->pic_department)
                                                <input type="hidden" name="asset_tracking" value="{{ $data->id }}" />
                                                <td style="text-align: center;">
                                                <input type="checkbox" value="1"  {{ $hasApproved != NULL ? 'checked disabled' : 'required' }} name="approval_check">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control catatan" value="{{ $data->note_return }}" disabled/>
                                                </td>
                                                <td>
                                                    <input type="text" name="note" class="form-control catatan" value="{{ $hasApproved != NULL ? $hasApproved->note : '' }}" {{ $hasApproved != NULL ? 'readonly' : 'required' }} />
                                                </td>
                                            @else
                                                <td style="text-align: center;">
                                                    <input type="checkbox" value="1" disabled {{ $hasApproved != NULL ? 'checked' : '' }} name="approval_check">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control catatan" value="{{ $data->note_return }}" disabled/>
                                                </td>
                                                <td>
                                                    <input type="text" name="catatan" class="form-control catatan"  readonly/>
                                                </td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="{{ route('karyawan.approval.facilities.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                                @if($hasApproved == NULL)
                                <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit_form"><i class="fa fa-check"></i> Approve</a>
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
    $('#submit_form').click(function(){
        if(!$("[name='approval_check']").is(':checked')){
        window.alert("Please checked the field Approval Check");
        }
        else if($("[name='approval_check']").is(':checked') && $("[name='note']").val()==''){
            window.alert("Note can not be empty!");
        }
        else{
            bootbox.confirm("Do you want to update this form ?", function(result){
                if(result)
                {
                    $("#facilities_form").submit()
                }
            });
        }
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
