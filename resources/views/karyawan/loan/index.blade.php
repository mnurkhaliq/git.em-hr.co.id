@extends('layouts.karyawan')

@section('title', 'Loan')

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
                <h4 class="page-title hidden-xs hidden-sm">Manage Loan</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="{{ route('karyawan.loan.create') }}" class="btn btn-success btn-sm pull-right m-l-20 waves-effect waves-light"> <i class="fa fa-plus"></i> ADD LOAN</a>
                <ol class="breadcrumb hidden-xs hidden-sm">
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
                                    <th>REQUEST DATE</th>
                                    <th>CALCULATED AMOUNT (IDR)</th>
                                    <th>STATUS</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td> 
                                        <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                        <td>{{ format_idr($item->calculated_amount) }}</td>
                                        <td>
                                            <a onclick="detail_approval_loanCustom({{ $item->id }})"> 
                                            {!! status_loan($item->status) !!}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('karyawan.loan.edit', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
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
