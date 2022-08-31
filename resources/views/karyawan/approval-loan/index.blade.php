@extends('layouts.karyawan')

@section('title', 'Approval Loan')

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
                <h4 class="page-title">Manage Loan</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Loan</li>
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
                                    <th>NIK</th>
                                    <th>EMPLOYEE NAME</th>
                                    <th>POSITION</th>
                                    <th>REQUEST DATE</th>
                                    <th>CALCULATED AMOUNT (IDR)</th>
                                    <th>STATUS</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    @if($item->is_approved == NULL)
                                        @if($item->loan->status == 3)
                                            <?php continue;?>
                                        @endif

                                        @if(!cek_level_loan_up($item->loan->id))
                                            <?php continue;?>
                                        @endif
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->loan->user->nik }}</td>
                                        <td>{{ $item->loan->user->name }}</td>
                                        <td>{{ $item->loan->user->structure ? $item->loan->user->structure->position->name.($item->loan->user->structure->division ? ' - '.$item->loan->user->structure->division->name : '').($item->loan->user->structure->title ? ' - '.$item->loan->user->structure->title->name : '') : "" }}</td>
                                        <td>{{ date('Y-m-d', strtotime($item->loan->created_at)) }}</td>
                                        <td>{{ format_idr($item->calculated_amount) }}</td>
                                        <td>
                                            <a onclick="detail_approval_loanCustom({{ $item->id }})">
                                            {!! status_loan($item->status) !!}
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->is_approved === NULL and $item->status < 2)
                                                <a href="{{ route('karyawan.approval-loan.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> process</button></a>
                                            @else
                                                <a href="{{ route('karyawan.approval-loan.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
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
@endsection
