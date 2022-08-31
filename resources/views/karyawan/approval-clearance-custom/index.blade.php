@extends('layouts.karyawan')

@section('title', 'Approval Exit Clearance')

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
                <h4 class="page-title">Manage Approval Exit Clearance</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Exit Clearance</li>
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
                                    <th>EXIT DATE</th>
                                    <th>REASON FOR LEAVING</th>
                                    <th>STATUS</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    @if($item == '')
                                        <?php continue;?>
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>    
                                        <td>{{ $item->user->nik }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->resign_date }}</td>
                                        <td>
                                            @if($item->exit_interview_reason == "")
                                                {{ str_limit($item->other_reason, $limit = 30, $end = '...') }}
                                            @else
                                                {!! str_limit($item->exitInterviewReason->label, $limit = 30, $end = '...') !!}
                                            @endif
                                        </td>
                                        <td>
                                            <a onclick="detail_approval_clearanceCustom({{ $item->id }})"> 
                                                @if($item->status_clearance == 0)
                                                <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                @elseif($item->status_clearance == 1)
                                                <label class="btn btn-success btn-xs">Approved</label>
                                                @elseif($item->status_clearance == 2)
                                                <label class="btn btn-danger btn-xs">Rejected</label>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('karyawan.approval.clearance-custom.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> exit clearance </button></a>
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
