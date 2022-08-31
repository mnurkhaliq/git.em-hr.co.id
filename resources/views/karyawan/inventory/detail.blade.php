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
                        <label class="col-md-12">FACILITIES</label>
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
                                        <th rowspan="2">ASSET CONDITION</th>
                                        <th rowspan="2">ACTION</th>
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
                                            @if($data->status == null || $data->status == 0)
                                                <form id="form_asset{{$data->id}}" action="{{route("karyawan.asset.confirm",$data->id)}}" method="POST">
                                                    {{ csrf_field() }}
                                                    <button type="button" id="acceptButton" class="btn btn-success btn-xs" onclick="acceptAsset({{$data->id}})" style="margin-bottom: 2px;" disabled>Accept Asset</button>
                                                </form>
                                                <form id="form_asset_reject{{$data->id}}" action="{{route("karyawan.asset.reject",$data->id)}}" method="POST">
                                                    {{ csrf_field() }}
                                                    <button type="button" class="btn btn-danger btn-xs" onclick="rejectAsset({{$data->id}})" style="margin-bottom: 2px;">Reject Asset</button>
                                                </form>
                                            @endif 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12" style="padding-left:0;">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="term" name="term" value="1" required>
                                        <label class="form-check-label">
                                            I agree to <span style="cursor: pointer;" onclick="preview_term()"><b><u>Term & Agreement</u></b></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br />
                    </div>

                    <div class="form-group">
                        <a href="{{ route('karyawan.facilities.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>

                </div>
            </div>
                                        
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>

<div id="modal_term" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <h2 class="text-center">Term and Agreement of Asset</h2>
                <h5> The assets in good condition, can function properly and can be used properly, then you declare that you are willing to promise to:</h5>
                @if(get_setting('term_and_agreement_asset') == '')
                <p style=" text-align: justify; text-justify: inter-word;"> 1.	Maintain (cleanliness, integrity and security) of the company's assets as mentioned above as much as possible;</p>
                <p style=" text-align: justify; text-justify: inter-word;"> 2.	If there is damage or loss of the asset caused by my carelessness or negligence, bear the cost of repair and or replacement of spare parts or replacement;</p>
                <p style=" text-align: justify; text-justify: inter-word;"> 3.	Use these assets in the right way, only on behalf and purposes of my job duties and responsibilities on {{ get_setting('title') }}/{{ get_setting('title') }} Clients;</p>
                <p style=" text-align: justify; text-justify: inter-word;"> 4.	Only use original software and if there is a need to install software with the knowledge of {{ get_setting('title') }};</p>
                <p style=" text-align: justify; text-justify: inter-word;"> 5.	Return and hand over the assets directly to the unit appointed by the company in intact condition and can still be used properly until my employment relationship ends for any reason or if at any time the company wants me to return the asset.</p>
                @else
                {!! get_setting('term_and_agreement_asset') !!}
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
        function acceptAsset(assetId) {
            swal({
                title: 'Are you sure?',
                text: 'The asset item will be accepted!',
                buttons: true,
                dangerMode: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    $('#form_asset'+assetId).submit();
                }
            });
        }

        function rejectAsset(assetId) {
            swal({
                title: 'Are you sure?',
                text: 'The asset item will be rejected!',
                buttons: true,
                dangerMode: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    $('#form_asset_reject'+assetId).submit();
                }
            });
        }

        function preview_term() {
            $('#modal_term').modal('show');
        }

        $("#term").change(function(){
        if($("#term").is(':checked')){
            $("#acceptButton").removeAttr('disabled')
        }
        else{
            $("#acceptButton").attr('disabled', 'disabled')
        }
    });
   
</script>

@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
