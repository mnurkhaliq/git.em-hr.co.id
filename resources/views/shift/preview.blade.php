@extends('layouts.administrator')

@section('title', 'Employee Attendance')

@section('content')        
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Preview Import Shift Schedule Change</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="POST" action="{{ route('shift-schedule.import-all') }}" id="filter-form" autocomplete="off">
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
                                <th>Change Date</th>
                                <th>Shift</th>
                                <th>User NIK</th>
                                <th>User Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $item)
                                <tr> 
                                    <td>{{ $item->change_date }}</td>    
                                    <td>{{ $item->shift_name }}</td>    
                                    <td>{{ $item->user_nik }}</td>
                                    <td>{{ $item->user_name }}</td>
                                    <td>{{ $item->description ?: 'Success' }}</td>   
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