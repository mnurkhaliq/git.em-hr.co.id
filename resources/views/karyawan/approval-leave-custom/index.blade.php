@extends('layouts.karyawan')

@section('title', 'Approval Employee Leave')

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
                <h4 class="page-title">Manage Approval Employee Leave</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Approval Employee Leave</li>
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
                                    <th>NAME</th>
                                    <th>DATE OF LEAVE</th>
                                    <th>LEAVE TYPE</th>
                                    <th>LEAVE DURATION</th>
                                    <th>PURPOSE</th>
                                    <th>CREATED</th>
                                    <th>STATUS</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $no => $item)
                                @if($item->is_approved == NULL)
                                    @if($item->cutiKaryawan->status == 3)
                                        <?php continue;?>
                                    @endif

                                    @if(!cek_level_leave_up($item->cutiKaryawan->id, true))
                                        <?php continue;?>
                                    @endif
                                @endif
                                <tr>
                                    <td class="text-center">{{ $no+1 }}</td>
                                    <td>
                                        {{$item->cutiKaryawan->karyawan->nik}}</td>
                                    <td>{{$item->cutiKaryawan->karyawan->name}}</td>
                                    <td>{{ date('d M Y', strtotime($item->tanggal_cuti_start)) }} - {{ date('d M Y', strtotime($item->tanggal_cuti_end)) }}</td>
                                    <td>{{ isset($item->cutiKaryawan->cuti) ? $item->cutiKaryawan->cuti->description : '' }}</td>
                                    <td>{{ $item->total_cuti }} Day/s</td>
                                    <td>{{ str_limit($item->keperluan, $limit = 30, $end = '...') }}</td>
                                    <td>{{ $item->cutiKaryawan->created_at }}</td>
                                    <td><a onclick="detail_approval_leaveCustom({{ $item->id }})">
                                            {!! status_cuti($item->status) !!}
                                            </a>
                                    </td>
                                    <td>
                                        @if(cek_level_leave_up($item->cutiKaryawan->id) AND $item->is_approved == NULL AND ($item->status < 2 OR $item->status == 6))
                                            <a href="{{ route('karyawan.approval.leave-custom.detail', $item->cutiKaryawan->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> process </button></a>
                                        @else
                                            <a href="{{ route('karyawan.approval.leave-custom.detail', $item->cutiKaryawan->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail </button></a>
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
