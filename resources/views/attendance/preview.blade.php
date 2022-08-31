@extends('layouts.administrator')

@section('title', 'Employee Attendance')

@section('content')        
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Preview Import Attendance</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="POST" action="{{ route('attendance.import-all') }}" id="filter-form" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="pull-right">
                        <button type="submit" class="btn btn-info btn-sm"> <i class="fa fa-plus"></i> Import All</button>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <table class="data_table_no_pagging table table-background">
                        <thead>
                            <tr>
                                <th rowspan="2">NIK</th>
                                <th rowspan="2">Name</th>
                                <th rowspan="2">Date</th>
                                <th rowspan="2">Day</th>
                                <th colspan="2" style="text-align: center;">Clock</th>
                                <th rowspan="2">Late CLOCK In</th>
                                <th rowspan="2">Early CLOCK Out</th>
                                <th rowspan="2">Duration</th>
                            </tr>
                            <tr>
                                <th>In</th>   
                                <th>Out</th>   
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $item)
                                @if(!isset($item->user->nik) || empty($item->date))
                                <?php continue; ?>
                                @endif
                                <tr> 
                                    <td>{{ $item->user->nik }} </td>    
                                    <td>{{ $item->user->name }}</td>    
                                    <td>{{ $item->date }}</td>    
                                    <td>{{ $item->timetable }}</td>    
                                    <td>{{ $item->clock_in }}</td>
                                    <td>{{ $item->clock_out }}</td>
                                    <td>{{ $item->late }}</td>   
                                    <td>{{ $item->early }}</td>    
                                    <td>{{ $item->work_time }}</td>    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> 
        </div>
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>

@endsection