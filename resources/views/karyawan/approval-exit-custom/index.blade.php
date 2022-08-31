@extends('layouts.karyawan')

@section('title', 'Approval Exit Interview')

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
                <h4 class="page-title">Manage Approval Exit Interview</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Exit Interview</li>
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
                                    <th>POSITION</th>
                                    <th>EXIT DATE</th>
                                    <th>REASON FOR LEAVING</th>
                                    <th>STATUS</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($no = 1)
                                @foreach($data as $item)
                                    @if($item->is_approved == NULL)
                                        @if($item->exitInterview->status == 3)
                                            <?php continue;?>
                                        @endif

                                        @if(!cek_level_exit_up($item->exitInterview->id))
                                            <?php continue;?>
                                        @endif
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>    
                                        <td>{{ $item->exitInterview->user->nik }}</td>
                                        <td>{{ $item->exitInterview->user->name }}</td>
                                        <td>{{ isset($item->exitInterview->user->structure->position) ? $item->exitInterview->user->structure->position->name : '' }}</td>
                                        <td>{{ $item->exitInterview->resign_date }}</td>
                                        <td>
                                            @if($item->exitInterview->exit_interview_reason == "")
                                                {{ str_limit($item->exitInterview->other_reason, $limit = 30, $end = '...') }}
                                            @else
                                                {!! str_limit($item->exitInterview->exitInterviewReason->label, $limit = 30, $end = '...') !!}
                                            @endif
                                        </td>
                                        <td>
                                            <a onclick="detail_approval_exitCustom({{ $item->exitInterview->id }})">
                                                {!! status_exit_interview($item->exitInterview->status) !!}
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->is_approved === NULL and $item->status < 2)
                                                <a href="{{ route('karyawan.approval.exit-custom.detail', $item->exit_interview_id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> process </button></a>
                                            @else
                                                <a href="{{ route('karyawan.approval.exit-custom.detail', $item->exit_interview_id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail </button></a>
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
