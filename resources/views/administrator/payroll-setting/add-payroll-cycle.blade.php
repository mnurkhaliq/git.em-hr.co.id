@extends('layouts.administrator')

@section('title', 'Form Setting Payroll Cycle')

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
                <h4 class="page-title">Form Setting Payroll Cycle</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting Payroll Cycle</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.payroll-setting.store-payroll-cycle') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Add Setting Payroll Cycle</h3>
                        <hr />
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ csrf_field() }}
                        <input type="hidden" name="key_name" value="payroll_custom" />
                        <div class="form-group">
                            <label class="col-md-12">Label</label>
                            <div class="col-md-6">
                               <input type="text" name="label" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Start Date</label>
                            <div class="col-md-6">
                                <select id="start_date" class="form-control" disabled>
                                    <option selected hidden>Select Start Date</option>
                                    @for($i=1; $i<=31; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                <input type="hidden" name="start_date">
                            </div>
                            <div class="col-md-4" style="color: red" id="cycle_status">
                                *Started from last month's payroll cycle
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">End Date</label>
                            <div class="col-md-6">
                                <select id="end_date" name="end_date" class="form-control" required>
                                    <option value="" selected hidden>Select End Date</option>
                                    @for($i=1; $i<=31; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-sm btn-default waves-effect waves-light m-r-10" onclick="document.getElementById('cancel').submit()"><i class="fa fa-arrow-left"></i> Cancel</button>
                                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save</button>
                                <br style="clear: both;" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>    
            </form>
            <form id="cancel" action="{{ route('administrator.payroll-setting.index') }}" method="GET">
                <input type="hidden" name="tab" value="cyclePayroll" />
            </form>                  
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@section('footer-script')
<script type="text/javascript">
    var start_date;
    var end_date;
    calculate_cycle();

    $('#end_date').on('change',function () {
        end_date = $(this).val();
        if(end_date == 0){
            start_date = 0;
        }
        else if(end_date == 31){
            start_date = 1;
        }else{
            start_date = parseInt(end_date) + 1;
        }
        $('#start_date').val(start_date);
        $("input[name='start_date']").val(start_date);
        calculate_cycle();
    });

    function calculate_cycle() {
        if(start_date > end_date){
            $('#cycle_status').removeClass('hidden');
        }
        else{
            $('#cycle_status').addClass('hidden');
        }
    }
</script>
@endsection

@endsection
