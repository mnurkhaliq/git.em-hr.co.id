@extends('layouts.karyawan')

@section('title', 'Business Trip')

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
                <h4 class="page-title hidden-xs hidden-sm">Manage Business Trip</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="{{ route('karyawan.training-custom.create') }}" class="btn btn-success btn-sm pull-right m-l-20  widthaves-effect waves-light"> <i class="fa fa-plus"></i> ADD BUSINESS TRIP</a>
                <ol class="breadcrumb hidden-xs hidden-sm">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Business Trip</li>
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
                                    <th>BT NUMBER</th>
                                    <th>ACTIVITY TYPE</th>
                                    <th>ACTIVITY DATE</th>
                                    <th>CASH ADVANCE (IDR)</th>
                                    <th>TOTAL APPROVED (IDR)</th>
                                    <th>CREATED</th>
                                    <th>BT APPROVAL</th>
                                    <th>ACTION</th>
                                    <th>BT CLAIM</th>
                                    <th>ACTUAL BILL REPORT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td> {{ $item->number != null ? $item->number : ''  }}</td>
                                        <td>{{ isset($item->training_type)? $item->training_type->name:''}}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_kegiatan_start)) }} - {{ date('d F Y', strtotime($item->tanggal_kegiatan_end)) }}</td>
                                        <td>{{ format_idr($item->pengambilan_uang_muka) }}</td>
                                        <td>{{ $item->status_actual_bill==2 ? format_idr($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) : '' }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a onclick="detail_approval_trainingCustom({{ $item->id }})"> 
                                            {!! status_cuti($item->status) !!}
                                            </a>
                                            <br>
                                            @if($item->status==2 && $item->is_transfer==0 )
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1 && $item->pengambilan_uang_muka > 0)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('karyawan.training-custom.edit', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> detail</a>
                                        </td>
                                        <td>
                                            <a href="javascript:;" onclick="detail_approval_trainingClaimCustom({{ $item->id }})"> 
                                                {!! status_cuti($item->status_actual_bill) !!}
                                            </a>
                                            <br>
                                            @php( $total_reimbursement_disetujui = $item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui - $item->pengambilan_uang_muka )
                                            @if($item->status_actual_bill==2 && $item->is_transfer_claim==0  && $total_reimbursement_disetujui != 0)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status_actual_bill==2 && $item->is_transfer_claim==1   && $total_reimbursement_disetujui != 0)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 2 && $item->is_transfer == 1)
                                                @if(empty($item->status_actual_bill) or $item->status_actual_bill < 1 or $item->status_actual_bill == 3 or $item->status_actual_bill == 4)
                                                    <a href="{{ route('karyawan.training-custom.claim', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-book"></i> claim</a>
                                                @else
                                                @if($item->status_actual_bill == 1 && $item->is_transfer_claim == 0)
                                                <a href="{{ route('karyawan.training-custom.claim', $item->id) }}">
                                                <label class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> claimed detail</label></a>
                                                @elseif($item->status_actual_bill >= 1 && $item->is_transfer_claim == 1)
                                                <a href="{{ route('karyawan.training-custom.claim', $item->id) }}">
                                                <label class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> claimed detail</label></a>
                                                {{--@elseif($item->status_actual_bill == 2 && $item->is_transfer_claim == 0 && ($total_reimbursement_disetujui < 0))
                                                    <a href="{{ route('karyawan.training-custom.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a>--}}
                                                @elseif($item->status_actual_bill >= 1 && $item->is_transfer_claim == 0 )
                                                    <a href="{{ route('karyawan.training-custom.claim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i>claimed detail</button></a>
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
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
@endsection

@endsection
