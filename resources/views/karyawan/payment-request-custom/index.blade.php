@extends('layouts.karyawan')

@section('title', 'Payment Request')

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
                <h4 class="page-title hidden-xs hidden-sm">Manage Payment Request</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                @if($data_waiting <= 0 || get_setting('period_ca_pr')!= 'yes')
                <a href="{{ route('karyawan.payment-request-custom.create') }}" class="btn btn-success btn-sm pull-right m-l-20 waves-effect waves-light"> <i class="fa fa-plus"></i> ADD PAYMENT REQUEST</a>
                @else
                    <a class="btn btn-success btn-sm pull-right m-l-20 waves-effect waves-light" onclick="bootbox.alert('Sorry you can not apply this transaction before the previous transaction has been completely approved')"> <i class="fa fa-plus"></i> ADD PAYMENT REQUEST</a>
                @endif
                @if(get_setting('period_ca_pr')== 'yes')
                <a href="javascript:void(0)" id="plafond_modal" class="btn btn-info btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-eye"></i> Plafond</a> 
                @endif
                <ol class="breadcrumb hidden-xs hidden-sm">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Payment Request</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>NUMBER</th>
                                    <th>TO</th>
                                    <th>PURPOSE</th>
                                    <!--<th>TRANSACTION TYPE</th>-->
                                    <th>PAYMENT METHOD</th>
                                    <th>NOMINAL APPROVED (IDR)</th>
                                    <th>STATUS</th>
                                    <th>CREATED</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>    
                                        <td>{{$item->number}}</td>
                                        <td>Accounting Department</td>
                                        <td>{{ str_limit($item->tujuan, $limit = 30, $end = '...') }}</td>
                                       <!-- <td>{{ $item->transaction_type }}</td>-->
                                        <td>{{ $item->payment_method }}</td>
                                        <td>{{$item->payment_request_form->sum('nominal_approved') != 0 ? format_idr($item->payment_request_form->sum('nominal_approved')) : ''}}</td>
                                        <td>
                                            <a onclick="detail_approval_paymentRequestCustom({{ $item->id }})">
                                                {!! status_payment_request($item->status) !!}
                                            </a>
                                           <br>
                                           @if($item->status==2 && $item->is_transfer==0 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>{{ date('d F Y H:i:s', strtotime($item->created_at)) }}</td>
                                        <td>
                                            <a href="{{ route('karyawan.payment-request-custom.edit', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>
    <div id="modal_plafond" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Available Plafond and Period Payment Request</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Plafond (IDR)</th>
                                    <th>Period</th>
                                    <th>Available Plafond (IDR)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($type as $item)
                                <tr>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->plafond ? format_idr($item->plafond) : '' }}</td>
                                    @if(get_setting('period_ca_pr') == 'yes')
                                    <td>{{ $item->period }}</td>
                                    @endif
                                    <td>{{ get_available_plafond($item->type) ?  format_idr(get_available_plafond($item->type)) : ''}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('js')
<script type="text/javascript">
    $("#plafond_modal").click(function(){
        $('#modal_plafond').modal("show");
    });
</script>
@endsection
@endsection
