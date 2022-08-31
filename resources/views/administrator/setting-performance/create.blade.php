@extends('layouts.administrator')

@section('title', 'Setting Performance Management')

@section('sidebar')

@endsection

@section('content')

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<style>
    td{
        position:relative;
    }
    /*.input-table{*/
        /*position:absolute;*/
        /*top:5px;*/
        /*right: 5px;*/
        /*left: 5px;*/
        /*bottom: 5px;*/
        /*height:95%;*/
        /*width:90%;*/
    /*}*/
</style>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Form Setting Performance Management Period</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Form Setting Performance Management Period</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.setting-performance.store') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Add Form Setting Performance Management Period</h3>
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
                        <div class="form-group">
                            <div class="col-md-3" style="padding: 0">
                                <label class="col-md-12">Start Date</label>
                                <div class="col-md-12">
                                    <input type="text" name="start_date" class="form-control datepicker" value="{{ old('start_date') }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3"  style="padding: 0">
                                <label class="col-md-12">End Date</label>
                                <div class="col-md-12">
                                    <input type="text" name="end_date" class="form-control datepicker" value="{{ old('end_date') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Weightage Setting</label>
                            <div class="col-md-6">
                                <table id="mytable" class="table table-responsive table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">NO</th>
                                            <th>Module</th>
                                            <th width="20%">MIN RATE</th>
                                            <th width="20%">MAX RATE</th>
                                            <th width="20%">WEIGHTAGE (100%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $modules = get_kpi_modules();?>
                                        @foreach($modules as $no => $item)
                                            <tr>
                                                <td class="text-center">{{$no+1}}</td>
                                                <td>{{$item->name}}</td>
                                                @if($no==0)
                                                    <td rowspan="{{count($modules)+1}}"  style="vertical-align : middle;text-align:center;"><input type="number" name="min_rate" class="form-control input-table" value="{{ old('min_rate',1) }}" disabled></td>
                                                    <td rowspan="{{count($modules)+1}}"  style="vertical-align : middle;text-align:center;"><input type="number" name="max_rate" class="form-control input-table" min="2" max="10" value="{{ old('max_rate',2) }}"></td>
                                                @endif
                                                <td><input type="number" name="weightage[{{$item->id}}]" class="form-control weightage input-table"  min="0" max="100" step="0.01"  value="{{ old('weightage.'.$item->id,0) }}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4">Total</td>
                                            <td class="text-center" id="total">0 %</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Status</label>
                            <div class="col-md-6">
                                <select id="status" class="form-control" name="status" required>
                                    <option value="0">Draft</option>
                                    <option value="1">Publish</option>
                                </select>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-6">
                            <div class="form-group pull-right">
                                <a href="{{ route('administrator.setting-performance.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save</button>
                                <br style="clear: both;" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>    
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
@section('js')
    <script>
        $(document).ready(function() {
            var status = '{{ old('status') }}';

            if(status !== '') {
                $('#status').val(status);
            }
        });
        $(".input-table").keydown(function () {
            // Save old value.
            if (!$(this).val() || (parseFloat($(this).val()) <= parseFloat($(this).attr('max')) && parseFloat($(this).val()) >= 0))
                $(this).data("old", $(this).val());
        });
        $('.input-table').on('keyup',function(){
            if (!(!$(this).val() || (parseFloat($(this).val()) <= $(this).attr('max') && parseFloat($(this).val()) >= 0))){
                $(this).val($(this).data("old"));
                return;
            }

            if($(this).hasClass('weightage')) {
                updateTotal();
            }
        });
        $('.weightage').on('change',function(){
            updateTotal();
        });
        updateTotal();
        function updateTotal() {
            var total_weightage = 0;
            $(".weightage").each(function () {
                if ($(this).val() != "") {
                    total_weightage += parseFloat($(this).val());
                }
            });
            $('#total').html(total_weightage.toFixed(2) + " %");
            if(total_weightage === 100){
                $('#total').css('color','green');
            }
            else{
                $('#total').css('color','red');
            }
        }
    </script>
@endsection
@endsection
