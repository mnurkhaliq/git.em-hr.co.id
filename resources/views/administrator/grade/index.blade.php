@extends('layouts.administrator')

@section('title', 'Grade')

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
                <h4 class="page-title">Manage Grade</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="{{ route('administrator.grade.create') }}" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD GRADE</a>
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Grade</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>GRADE NAME</th>
                                    <th>SALARY RANGE (IDR)</th>
                                    <th>SUB GRADE</th>
                                    <th>SALARY RANGE (IDR)</th>
                                    <th>BENEFIT(s)</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                <?php $sub = call_sub_grade($item->id); ?>
                                @if(count($sub) == 0)
                                    <tr>
                                        <td style="vertical-align:middle;" class="text-center">{{ $no+1 }}</td>
                                        <td style="vertical-align:middle;">{{$item->name}}</td>
                                        <td style="vertical-align:middle;">{{ number_format(explode(' - ', $item->salary_range)[0]) }} - {{ number_format(explode(' - ', $item->salary_range)[1]) }}</td>
                                        <td style="vertical-align:middle;">Not available.</td>
                                        <td style="vertical-align:middle;">Not available.</td>
                                        <td style="vertical-align:middle;">
                                            <?php
                                            if($item->benefit == '' || $item->benefit == null){
                                                echo '-';
                                            }
                                            else{
                                                echo htmlspecialchars_decode($item->benefit);
                                            }
                                            ?>
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <a href="{{ route('administrator.grade.edit', $item->id) }}" style="float: left; margin-right:5px"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> Edit</button></a>
                                            <form action="{{ route('administrator.grade.destroy', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post" style="margin-left: 5px;">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}                                               
                                                <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($sub as $key => $val)
                                        <tr>
                                            @if($key == 0)
                                                <td style="vertical-align:middle;" class="text-center" rowspan="{{count($sub)}}">{{$no+1}}</td>
                                                <td style="vertical-align:middle;" rowspan="{{count($sub)}}">{{$item->name}}</td>
                                                <td style="vertical-align:middle;" rowspan="{{count($sub)}}">{{ number_format(explode(' - ', $item->salary_range)[0]) }} - {{ number_format(explode(' - ', $item->salary_range)[1]) }}</td>
                                            @endif
                                            <td style="vertical-align:middle;">{{$val->name}}</td>
                                            <td style="vertical-align:middle;">{{ number_format(explode(' - ', $val->salary_range)[0]) }} - {{ number_format(explode(' - ', $val->salary_range)[1]) }}</td>
                                            @if($key == 0)
                                            <td style="vertical-align:middle;" rowspan="{{count($sub)}}">
                                                <?php
                                                if($item->benefit == '' || $item->benefit == null){
                                                    echo '-';
                                                }
                                                else{
                                                    echo htmlspecialchars_decode($item->benefit);
                                                }
                                                ?>
                                            </td>
                                            <td style="vertical-align:middle;" rowspan="{{count($sub)}}">
                                                <a href="{{ route('administrator.grade.edit', $item->id) }}" style="float: left; margin-right:5px"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> Edit</button></a>
                                                <form action="{{ route('administrator.grade.destroy', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post" style="margin-left: 5px;">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}                                               
                                                    <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> Delete</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
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
