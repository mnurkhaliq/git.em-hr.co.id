@extends('layouts.administrator')

@section('title', 'Setting On Leave')

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
                <h4 class="page-title">Setting On Leave</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting On Leave</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#type"
                                aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span
                                    class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Leave Type</span></a></li>
                        <li role="presentation" class=""><a href="#range"
                                aria-controls="home" role="tab" data-toggle="tab" aria-expanded="false"><span
                                    class="visible-xs"><i class="ti-home"></i></span> <span class="hidden-xs">Submission Range</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="type">
                            <h3 class="box-title m-b-0">Setting Leave Type</h3>
                            <a href="{{ route('administrator.setting-master-cuti.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="30" class="text-center">NO</th>
                                            <th>DESCRIPTION</th>
                                            <th>LEAVE TYPE</th>
                                            <th>LEAVE CALCULATION METHOD</th>
                                            <th>IS CARRY FORWARD</th>
                                            <th>CARRY FORWARD LEAVE</th>
                                            <th>IS ATTACHMENT</th>
                                            <th>QUOTA</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $no => $item)
                                            <tr>
                                                <td class="text-center">{{ $no+1 }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->jenis_cuti }}</td>
                                                @if($item->master_cuti_type_id)
                                                <td>{{ $item->cutiname->master_cuti_name}}</td>
                                                @else
                                                <td></td> 
                                                @endif
                                                
                                                <td><script type="text/javascript">
                                                if( {{ $item->iscarryforward }} == false ) {
                                                document.write("x");
                                                    } else {
                                                        document.write("✔");
                                                    }
                                                </script></td>
                                                <td>{{ $item->carryforwardleave }}</td>
                                                <td><script type="text/javascript">
                                                    if( {{ $item->is_attachment }} == false ) {
                                                    document.write("x");
                                                        } else {
                                                            document.write("✔");
                                                        }
                                                    </script></td>
                                                <td>{{ $item->kuota }}</td>
                                                <td>
                                                    <a href="{{ route('administrator.setting-master-cuti.edit', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>
                                                    @if($item->jenis_cuti == 'Annual Leave')

                                                    @else
                                                    <a href="{{ route('administrator.setting-master-cuti.delete', $item->id) }}" onclick="return confirm('Delete this data?')"> <button class="btn btn-danger btn-xs m-r-5"><i class="fa fa-trash"></i> delete</button></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="range">
                            <div class="col-lg-12 col-sm-8 col-md-8 col-xs-12">
                                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-left" onclick="submitRange()"><i class="fa fa-save"></i> Save Setting </button>
                            </div>
                            <br><br><br><br>
                            <h3 class="box-title m-b-0">MAXIMUM PERIOD (MONTH) LEAVE SUBMISSION</h3>
                            <form method="POST" id="form-setting-range" action="{{ route('administrator.setting-master-cuti.store-range') }}" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    <br>
                                    <div class="col-md-12 p-l-0">
                                        <div class="form-group">
                                            <label class="col-md-2">Forward (Month)</label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <input type="number" class="form-control" id="max_leave_range" name="max_leave_range" value="{{ $range['max_leave_range'] ?: 2 }}" required />
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label class="col-md-2">Backward (Month)</label>
                                            <div class="col-md-2">
                                                <div class="input-form">
                                                    <input type="number" class="form-control" id="min_leave_range" name="min_leave_range" value="{{ $range['min_leave_range'] ?: 2 }}" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>                        
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
   @include('layouts.footer')
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script>
    function submitRange() {
        if (!$('#max_leave_range').val() || !$('#min_leave_range').val())
            swal("Complete the form", "", "error")
        else
            $('#form-setting-range').submit()
    }
</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
