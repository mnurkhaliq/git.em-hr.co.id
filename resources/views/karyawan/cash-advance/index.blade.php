@extends('layouts.karyawan')

@section('title', 'Cash Advance')

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
                <h4 class="page-title hidden-xs hidden-sm">Manage Cash Advance</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                @if($data_waiting <= 0 || get_setting('period_ca_pr')!= 'yes')
                <a href="{{ route('karyawan.cash-advance.create') }}" class="btn btn-success btn-sm pull-right m-l-20 waves-effect waves-light"> <i class="fa fa-plus"></i> ADD CASH ADVANCE</a>
                @else
                    <a class="btn btn-success btn-sm pull-right m-l-20 waves-effect waves-light" onclick="bootbox.alert('Sorry you can not apply this transaction before the previous transaction has been completely approved')"> <i class="fa fa-plus"></i> ADD CASH ADVANCE</a>
                @endif
                @if(get_setting('period_ca_pr')== 'yes')
                <a href="javascript:void(0)" id="plafond_modal" class="btn btn-info btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-eye"></i> Plafond</a> 
                @endif
                <ol class="breadcrumb hidden-xs hidden-sm">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Cash Advance</li>
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
                                    <th>CA NUMBER</th>
                                    <th>TO</th>
                                    <th>PURPOSE</th>
                                    <!--<th>TRANSACTION TYPE</th>-->
                                    <th>PAYMENT METHOD</th>
                                    <th>CREATED</th>
                                    <th>CA APPROVED (IDR)</th>
                                    <th>CA SETTLEMENT (IDR)</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                    <th>SETTLEMENT STATUS</th>
                                    <th>ACTION SETTLEMENT</th>
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
                                        <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ format_idr($item->total_amount_approved) }}</td>
                                        <td>{{ format_idr($item->total_amount_claimed) }}</td>
                                        <td>
                                            <a onclick="detail_approval_cashAdvance({{ $item->id }})">
                                                {!! status_payment_request($item->status) !!}
                                            </a>
                                            <br>
                                            @if($item->status==2 && $item->is_transfer==0 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('karyawan.cash-advance.edit', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> detail</a>
                                        </td>
                                        <td>
                                            <a onclick="detail_approval_cashAdvanceClaim({{ $item->id }})">
                                                {!! status_payment_request($item->status_claim) !!}
                                            </a>
                                            <br>
                                            @if($item->status_claim==2 && $item->is_transfer_claim==0 && $item->payment_method == 'Bank Transfer' && $item->total_amount_claimed != $item->total_amount_approved)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status_claim==2 && $item->is_transfer_claim==1 && $item->payment_method == 'Bank Transfer'  && $item->total_amount_claimed != $item->total_amount_approved)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 2 && $item->is_transfer == 1 )
                                                @if(empty($item->status_claim) or $item->status_claim < 1 or $item->status_claim == 3  or $item->status_claim == 4)
                                                    <a href="{{ route('karyawan.cash-advance.claim', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-book"></i> settlement</a>
                                                @else
                                                    @if($item->status_claim == 1 && $item->is_transfer_claim == 0)
                                                    <a href="{{ route('karyawan.cash-advance.claim', $item->id) }}">
                                                    <label class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> settlement  detail</label></a>
                                                    @elseif($item->status_claim >= 1 && $item->is_transfer_claim == 1)
                                                    <a href="{{ route('karyawan.cash-advance.claim', $item->id) }}">
                                                    <label class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> settlement  detail</label></a>
                                                    {{--@elseif($item->status_claim == 2 && $item->is_transfer_claim == 0 && ($item->total_amount_approved > $item->total_amount_claimed))
                                                        <a href="{{ route('karyawan.cash-advance.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a>--}}
                                                    @elseif($item->status_claim >= 1 && $item->is_transfer_claim == 0 )
                                                        <a href="{{ route('karyawan.cash-advance.claim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i>settlement detail</button></a>
                                                    @endif

                                                @endif
                                            @endif
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
                    <h4 class="modal-title" id="myModalLabel">Available Plafond and Period Cash Advance</h4>
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
                                    <td>{{ get_available_plafond_ca($item->type) ?  format_idr(get_available_plafond_ca($item->type)) : ''}}</td>
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
