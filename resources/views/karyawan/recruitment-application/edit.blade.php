@extends('layouts.karyawan')

@section('title', 'KPI Evaluation')

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
                <h4 class="page-title">KPI Evaluation</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">KPI Evaluation</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Form KPI Evaluation</h3>
                    <hr />
                    <form id="form">
                    <div class="form-group col-md-12" style="padding: 0">
                        <div class="col-md-3" style="padding: 0">
                            <label class="col-md-12">Start Date</label>
                            <div class="col-md-12">
                                <input type="text" name="start_date" class="form-control datepicker" value="{{ date("Y/m/d", strtotime($period->start_date)) }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3" style="padding: 0">
                            <label class="col-md-12">End Date</label>
                            <div class="col-md-12">
                                <input type="text" name="end_date" class="form-control datepicker" value="{{ date("Y/m/d", strtotime($period->end_date))}}" disabled>
                            </div>
                        </div>
                    </div>
                        <div class="form-group col-md-12" style="padding: 0">
                            <div class="col-md-3" style="padding: 0">
                                <label class="col-md-12">Name</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{$employee->user->nik." - ".$employee->user->name}}" disabled>
                                </div>
                            </div>
                        </div>
                    <div class="form-group col-md-12" style="padding: 0">
                        <div class="col-md-3" style="padding: 0">
                            <label class="col-md-12">Position</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control"  value="{{$position->position}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12" style="padding: 0">
                        <div class="col-md-3" style="padding: 0">
                            <label class="col-md-12">Status</label>
                            <div class="col-md-12">
                                <?php
                                    $status = "";
                                    switch ($employee->status){
                                        case 0:
                                            $status = "Draft - Not Published to Manager yet";
                                            break;
                                        case 1:
                                            $status = "Self Reviewed - Waiting for Manager Review";
                                            break;
                                        case 2:
                                            $status = "Final Reviewed - Waiting for your Acknowledgement";
                                            break;
                                        case 3:
                                            $status = "Acknowledged - You have acknowledged this KPI";
                                            break;
                                    }
                                ?>
                                <input type="text" class="form-control"  value="{{$status}}" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">KPI Form Evaluation</label>
                        <div class="col-md-12">
                            <div class="table-responsive col-md-12" style="padding: 0">

                                    <table id="mytable" class="table table-striped table-bordered display nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr CLASS="text-center">
                                                <th width="3%" class="text-center">NO</th>
                                                <th width="10%">MODUL</th>
                                                <th width="10%">KPI ITEM</th>
                                                <th width="5%">MIN RATE</th>
                                                <th width="5%">MAX RATE</th>
                                                <th width="5%">WEIGHTAGE</th>
                                                <th width="5%">SELF SCORE</th>
                                                <th width="20%">JUSTIFICATION</th>
                                                @if($employee->status > 1)
                                                <th width="5%">SPV SCORE</th>
                                                <th width="20%">COMMENT</th>
                                                <th width="10%">FINAL SCORE</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody id="data_item">
                                                <?php $weightage = 0; ?>
                                                @foreach($items as $no => $item)
                                                    <tr>
                                                    <?php $module = getSettingModuleByID($period->settings,$item->kpi_setting_scoring_id); ?>
                                                    <td class="text-center">{{$no+1}}</td>
                                                    <td>@if($module){{$module->name}}@endif</td>
                                                    <td>{{$item->name}}</td>
                                                    <td class="text-center">{{$period->min_rate}}</td>
                                                    <td class="text-center">{{$period->max_rate}}</td>
                                                    <td class="text-center">{{$item->weightage}}%</td>
                                                    <td><input class="form-control input-item score" type="number" min="{{$period->min_rate}}" max="{{$period->max_rate}}" name="self_score[{{$item->id}}]" value="{{count($item->scoring)>0?$item->scoring[0]->self_score:""}}"/></td>
                                                    <td><textarea class="form-control input-item" type="text" name="justification[{{$item->id}}]">{{count($item->scoring)>0?$item->scoring[0]->justification:""}}</textarea></td>
                                                    @if($employee->status > 1)
                                                    <td><input class="form-control input-item score" type="number" min="{{$period->min_rate}}" max="{{$period->max_rate}}" name="spv_score[{{$item->id}}]" value="{{count($item->scoring)>0?$item->scoring[0]->supervisor_score:""}}"/></td>
                                                    <td><textarea class="form-control input-item" type="text" name="comment[{{$item->id}}]">{{count($item->scoring)>0?$item->scoring[0]->comment:""}}</textarea></td>
                                                    <td class="text-center finals text-info text-capitalize">{{count($item->scoring)>0&&$employee->status!=0?($item->weightage*$item->scoring[0]->supervisor_score)/100:""}}</td>
                                                    @endif
                                                    </tr>
                                                    <?php $weightage += $item->weightage ?>
                                                @endforeach


                                        </tbody>
                                        <thead>
                                        <tr style="background: white">
                                            <td colspan="5" class="text-center"><b>Total</b></td>
                                            <td class="text-center"><b>{{$weightage}} %</b></td>
                                            @if($employee->status <= 1)
                                                <td class="text-center"  colspan="2" ></td>
                                            @else
                                                <td class="text-center"  colspan="4" ></td>
                                                <td class="text-center" id="total"></td>
                                            @endif
                                        </tr>
                                        </thead>
                                    </table>

                            </div>
                        </div>
                    </div>

                    <div class="row">


                            @if($employee->status == 0)
                            <div class="col-md-12">
                                <div class="col-md-12 pull-right">
                                    <button type="button" class="btn btn-success pull-right btn_save" onclick="submitItem(1)">Submit</button>
                                    <button type="button" class="btn btn-warning pull-right btn_save m-r-15" onclick="submitItem(0)">Save draft</button>
                                </div>
                            @elseif($employee->status == 2 || $employee->status == 3)
                            <div class="col-md-12" style="display: flex;align-items: center;">
                                <div class="col-md-10 col-xs-8">
                                    <label>Feedback</label>
                                    <textarea class="form-control" name="feedback" {{$employee->status==3?"disabled":""}}>{{$employee->employee_feedback}}</textarea>
                                </div>
                                @if($employee->status == 2)
                                <div class="col-md-2 col-xs-4">
                                    <button type="button" class="btn btn-success pull-right" style="width: 100%" onclick="submitItem(3)">Acknowledge</button>
                                </div>
                                @endif
                            @endif
                    </div>
                    </div>
                    </form>
                </div>
            </div>                        
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
   @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        @if($employee->status != 0)
            $('.input-item').attr('disabled',true);
        @endif

        $(".score").keydown(function () {
            // Save old value.
            if (!$(this).val() || (parseInt($(this).val()) <= parseInt($(this).attr('max')) && parseInt($(this).val()) >= 0))
                $(this).data("old", $(this).val());
        });
        $('.score').on('keyup', function () {
            if (!(!$(this).val() || (parseInt($(this).val()) <= $(this).attr('max') && parseInt($(this).val()) >= 0))) {
                $(this).val($(this).data("old"));
                return;
            }
            updateTotal();
        });
        // $('.score').on('change', function () {
        //     updateTotal();
        // });
        updateTotal();
        function updateTotal() {
            var total = 0;
            $(".finals").each(function () {
                if ($(this).html() != "") {
                    total += parseFloat($(this).html());
                }
            });
            $('#total').html(total.toFixed(2));
        }
        function submitItem(status){
            if(status==1) {
                swal({
                    title: 'Are you sure?',
                    text: 'The KPI self scoring will be saved and you will not be able to change it later!',
                    buttons: true,
                    dangerMode: true,
                }).then((willSubmit) => {
                    if (willSubmit) {
                        submitEval(status)
                    }
                });
            }
            else{
                submitEval(status);
            }
        }
        function submitEval(status) {
            var form = $('#form')[0]; // You need to use standart javascript object here
            var formData = new FormData(form);
            formData.append('kpi_employee_id',{{$employee->id}});
            formData.append('status',status);
            formData.append('_token',"{{csrf_token()}}");

            $(".btn_save").attr("disabled", true);
            $.ajax({
                url: "{{route('karyawan.performance-evaluation.store')}}",
                type: "POST",
                data:formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == 'success') {
                        swal("Success!", data.message, "success");
                        if(status==1 || status==3){
                            setTimeout(window.location.href = window.location.href, 1000);
                        }
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                    console.log(data);
                    $(".btn_save").attr("disabled", false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    $(".btn_save").attr("disabled", false);
                }
            });
        }
    </script>
@endsection
@endsection
