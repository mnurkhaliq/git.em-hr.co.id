@extends('layouts.administrator')

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
            {{--<form class="form-horizontal" autocomplete="off" enctype="multipart/form-data" action="{{ route('administrator.exit-custom.prosesclearance') }}" method="POST" id="exit_interview_form">--}}
            <form class="form-horizontal" autocomplete="off" enctype="multipart/form-data" action="" method="POST" id="exit_interview_form">
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
                                            <th rowspan="2">PURCHASE/RENTAL DATE</th>
                                            <th rowspan="2">ASSET OWNERSHIP</th>
                                            <th colspan="2">ASSET CONDITION</th>
                                            <th rowspan="2">HANDOVER DATE</th>
                                            <th rowspan="2">EMPLOYEE CHECKED</th>
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
                                        @foreach($data as $k => $item)
                                        @php($no++)
                                        <tr>
                                            <input type="hidden" name="asset[{{$no}}]" value="{{ $item->id }}" />
                                            <td class="text-center">{{ $no }}</td>   
                                            <td>{{ $item->asset->asset_number }}</td>
                                            <td>{{ $item->asset->asset_name }}</td>
                                            <td>{{ isset($item->asset->asset_type->name) ? $item->asset->asset_type->name : ''  }}</td>
                                            <td>{{ $item->asset->asset_sn }}</td>
                                            <td>{{ format_tanggal($item->asset->purchase_date) }}</td>
                                            <td>{{ $item->asset->status_mobil }}</td>
                                            <td>{{ $item->asset->asset_condition }}</td>
                                            <td>
                                                <select name="asset_condition[{{$no}}]" class="form-control" disabled>
                                                    <option selected disabled>- Asset Condition -</option>
                                                    @foreach($asset_conditions as $condition)
                                                        <option value="{{$condition}}" {{$condition == $item->asset_condition?"selected":""}}>{{$condition}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $item->asset->handover_date != "" ?  format_tanggal($item->asset->handover_date) : '' }}</td>
                                            <td style="text-align: center;">
                                                 @if($item->user_check == 1)
                                                <label class="bt btn-success btn-xs"><i class="fa fa-check"></i> </label>
                                                @else
                                                <label class="bt btn-danger btn-xs"><i class="fa fa-close"></i> </label>
                                                @endif 
                                            </td>
                                            <td style="text-align: center;">
                                                @if($item->approval_check == 1)
                                                <label class="bt btn-success btn-xs"><i class="fa fa-check"></i> </label>
                                                @else
                                                <label class="bt btn-danger btn-xs"><i class="fa fa-close"></i> </label>
                                                @endif  
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="catatan_user[{{$no}}]" class="form-control catatan" value="{{ $item->catatan_user }}" />
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="catatan[{{$no}}]" class="form-control catatan" value="{{ $item->catatan }}" />
                                            </td>
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
                                <a href="{{ route('administrator.exitCustom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
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

    $("input[name='exit_interview_reason']").click(function(){

        if($(this).val() == 1)
        {
            $('.perusahaan_lain').show("slow");
        }
        else if($(this).val() == 'other')
        {
            $("textarea[name='other_reason']").show();
        }
        else
        {
            $('.perusahaan_lain').hide("slow");
            $("textarea[name='other_reason']").hide();
        }
    });
</script>
<script type="text/javascript">

    var list_atasan = [];

    @foreach(empore_get_atasan_langsung() as $item)
        list_atasan.push({id : {{ $item->id }}, value : '{{ $item->nik .' - '. $item->name.' - '. empore_jabatan($item->id) }}',  });
    @endforeach
</script>
<script type="text/javascript">

    $('#submit_form').click(function(){

        bootbox.confirm("Do you want to submit this form ?", function(result){
            if(result)
            {
                $("#exit_interview_form").submit()
            }
        });

    });

    jQuery('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
    });

    $('.next_exit_clearance').click(function(){

        $("a[href='#clearance']").parent().addClass('active');        

        $("a[href='#interview']").parent().removeClass('active');
    });

</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
