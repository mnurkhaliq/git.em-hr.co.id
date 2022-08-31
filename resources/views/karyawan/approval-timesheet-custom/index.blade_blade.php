@extends('layouts.karyawan')

@section('title', 'Approval Timesheet')

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
                <h4 class="page-title">Manage Approval Timesheet</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Approval Timesheet</li>
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
                                    <<th>TIMESHEET PERIOD</th>
                                    <th>SUBMISSION STATUS</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $no => $item)
                                @if($item->is_approved == NULL)
                                    @if($item->timesheetPeriod->status == 3)
                                        <?php continue;?>
                                    @endif

                                    @if(!cek_level_timesheet_up($item->timesheetPeriod->id))
                                        <?php continue;?>
                                    @endif
                                @endif
                                <tr>
                                    <td class="text-center">{{ $no+1 }}</td>
                                    <td>{{ $item->timesheetPeriod->user->nik }}</td>
                                    <td>{{ $item->timesheetPeriod->user->name }}</td>  
                                    <td>{{ date('d F Y', strtotime($item->start_date)) }} - {{ date('d F Y', strtotime($item->end_date)) }}</td>
                                    <td>
                                        <a onclick="detail_approval_timesheetCustom({{ $item->id }})">
                                        {!! status_timesheet($item->status) !!}
                                        </a>
                                    </td>
                                    <td>
                                        @if($item->is_approved === NULL and $item->status < 2)
                                            <a href="{{ route('karyawan.approval.timesheet-custom.detail', $item->timesheetPeriod->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> process </button></a>
                                        @else
                                            <a href="{{ route('karyawan.approval.timesheet-custom.detail', $item->timesheetPeriod->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail </button></a>
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
