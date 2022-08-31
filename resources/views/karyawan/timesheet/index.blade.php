@extends('layouts.karyawan')

@section('title', 'Timesheet')

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
                <h4 class="page-title hidden-xs hidden-sm">Manage Timesheet</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="POST" action="{{ route('karyawan.timesheet.search') }}">
                    {{ csrf_field() }}
                    <div style="padding-left:0; float: right;">
                        <div class="btn-group m-l-10 m-r-10 pull-right">
                            <a href="{{ route('karyawan.timesheet.create') }}" class="btn btn-success btn-sm pull-right m-l-20 waves-effect waves-light" onclick=""> <i class="fa fa-plus"></i> ADD TIMESHEET</a>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm pull-right btn-outline"><i class="fa fa-search-plus"></i></button>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <input type="text" name="end_date" class="form-control  datepicker" value="{{ (request() and request()->end_date) ? request()->end_date : '' }}" placeholder="End Date">
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <input type="text" name="start_date" class="form-control  datepicker" value="{{ (request() and request()->start_date) ? request()->start_date : '' }}" placeholder="Start Date">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
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
                                    <th>TIMESHEET PERIOD</th>
                                    <th>SUBMISSION STATUS</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ date('d F Y', strtotime($item->start_date)) }} - {{ date('d F Y', strtotime($item->end_date)) }}</td>
                                        <td>
                                            <a onclick="detail_approval_timesheetCustom({{ $item->id }})">
                                            {!! status_timesheet($item->status) !!}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('karyawan.timesheet.edit', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
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

<!-- sample modal content -->
<div id="modal_history_approval" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">History Approval</h4> </div>
                <div class="modal-body" id="modal_content_history_approval"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection