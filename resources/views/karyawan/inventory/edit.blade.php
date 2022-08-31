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
            <form class="form-horizontal" autocomplete="off" id="facilities_form"  enctype="multipart/form-data" action="{{ route('karyawan.facilities.update', $data->id) }}" method="POST">
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
                        {!! method_field('PATCH') !!}
                        <div class="form-group">
                            <label class="col-md-12">FACILITIES RETURN</label>
                            <div class="col-md-12">
                                <table class="table table-bordered" id="table_clearance">
                                    <thead>
                                        <tr>
                                            <th width="70" rowspan="2">No</th>
                                            <th rowspan="2">ASSET NUMBER</th>
                                            <th rowspan="2">ASSET NAME</th>
                                            <th rowspan="2">ASSET TYPE</th>
                                            <th rowspan="2">SERIAL/PLAT NUMBER</th>
                                            <th rowspan="2">SPECIFICATION</th>
                                            <th rowspan="2">PURCHASE/RENTAL DATE</th>
                                            <th rowspan="2">ASSET OWNERSHIP</th>
                                            <th colspan="2">ASSET CONDITION</th>
                                            <th rowspan="2">HANDOVER DATE</th>
                                            <th rowspan="2">NOTE EMPLOYEE</th>
                                        </tr>
                                        <tr>
                                            <th  width="8%">HANDED OVER</th>
                                            <th  width="10%">RETURNED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>   
                                            <td>{{ $data->asset_number }}</td>
                                            <td>{{ $data->asset_name }}</td>
                                            <td>{{ isset($data->asset_type->name) ? $data->asset_type->name : ''  }}</td>
                                            <td>{{ $data->asset_sn }}</td>
                                            <td>{!! isset($data->spesifikasi) ? $data->spesifikasi : ''!!}</td>
                                            <td>{{ format_tanggal($data->purchase_date) }}</td>
                                            <td>{{ $data->status_mobil }}</td>
                                            <td>{{ $data->asset_condition }}</td>
                                            <td>
                                                <select name="asset_condition_return" class="form-control" required {{$tracking->is_return==1 ? 'disabled' : ''}}>
                                                    <option selected disabled>- Asset Condition -</option>
                                                    @foreach($asset_conditions as $condition)
                                                        <option value="{{$condition}}" {{$tracking->asset_condition_return==$condition ? 'selected' : ''}}>{{$condition}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $data->handover_date != "" ?  format_tanggal($data->handover_date) : '' }}</td>
                                            
                                            <td>
                                                @if($tracking->is_return==1)
                                                <input type="text"  name="note_return" class="form-control catatan" value="{{$tracking->note_return}}" disabled/>
                                                @else
                                                <input type="text"  name="note_return" class="form-control catatan" required/>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                        </div>

                        <div class="form-group">
                            <a href="{{ route('karyawan.facilities.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            @if($tracking->is_return!=1)
                            <button type="button" class="btn btn-sm btn-success" id="submit_form">Submit Return Asset</button>
                            @endif
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
        if($("[name='asset_condition_return']").val()==null){
            window.alert("Please select condition of asset");
        }
        else if($("[name='asset_condition_return']").val()!=null && $("[name='note_return']").val()==''){
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
</script>

@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
