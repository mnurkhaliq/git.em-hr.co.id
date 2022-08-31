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
                <form id="filter-form" method="POST" action="">
                    {{ csrf_field() }}
                    <input type="hidden" name="eksport" value="0">

                    <div style="padding-left:0; float: right;">
                        <div class="btn-group m-l-10 m-r-10 pull-right">
                            <button type="button" onclick="eksportTimesheet()" class="btn btn btn-info btn-sm pull-right"><i class="fa fa-download"></i> Export </button>
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
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select class="form-control form-control-line" name="division_id">
                                <option value=""> - Choose Division - </option>
                                @foreach($division as $item)
                                <option value="{{ $item->id }}" {{ $item->id== request()->division_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select class="form-control form-control-line" name="position_id">
                                <option value=""> - Choose Position - </option>
                                @foreach($position as $item)
                                <option value="{{ $item->id }}" {{ $item->id== request()->position_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) ? request()->name : '' }}" placeholder="Name / NIK Employee">
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
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>TIMESHEET PERIOD</th>
                                    <th>APPROVED HOUR</th>
                                    <th>SUBMISSION STATUS</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $no => $item)
                                @php($is_approvable = $item->where('id', $item->id)->whereHas('timesheetPeriodTransaction', function ($query) {
                                    $query->join('timesheet_categories as tc', function ($join) {
                                        $join->on('tc.id', '=', 'timesheet_period_transactions.timesheet_category_id');
                                    })->join('setting_approval_timesheet_transaction_item as satti', function ($join) {
                                        $join->on('tc.id', '=', 'satti.timesheet_category_id');
                                    })->where('status', '=', 1)->where('satti.user_id', '=', \Auth::user()->id);
                                })->first())
                                <tr>
                                    <td class="text-center">{{ $no+1 }}</td>
                                    <td>{{ $item->user->nik }}</td>
                                    <td>{{ $item->user->name }}</td>  
                                    <td>{{ date('d F Y', strtotime($item->start_date)) }} - {{ date('d F Y', strtotime($item->end_date)) }}</td>
                                    <td>{{ round($item->timesheetPeriodTransaction->where('status', 2)->where('approval_id', \Auth::user()->id)->sum('duration'), 2) }} Hour</td>
                                    <td>
                                        <a onclick="detail_approval_timesheetCustom({{ $item->id }})">
                                        {!! status_timesheet($item->status, $is_approvable) !!}
                                        </a>
                                    </td>
                                    <td>
                                        @if($is_approvable)
                                            <a href="{{ route('karyawan.approval.timesheet-custom.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> process </button></a>
                                        @else
                                            <a href="{{ route('karyawan.approval.timesheet-custom.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail </button></a>
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
@section('js')
<script>
    function eksportTimesheet() {
        $("input[name='eksport']").val(1);
        $("#filter-form").submit();

        $("input[name='eksport']").val(0);
    }
</script>
@endsection
@endsection
