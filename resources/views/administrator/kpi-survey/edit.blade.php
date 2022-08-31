@extends('layouts.administrator')

@section('title', 'KPI Survey')

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
                    <h4 class="page-title">KPI Survey</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">KPI Survey</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form KPI Survey</h3>
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
                                                $status = "Self Reviewed - Waiting for Manager's Review";
                                                break;
                                            case 2:
                                                $status = "Final Reviewed - Waiting for employee's Acknowledgement";
                                                break;
                                            case 3:
                                                $status = "Acknowledged - Employee has acknowledged this KPI";
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
                                                <th width="5%">SPV SCORE</th>
                                                <th width="20%">COMMENT</th>
                                                <th width="10%">FINAL SCORE</th>
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
                                                    <td><input class="form-control input-item input-employee score" type="number" step="0.01" min="{{$period->min_rate}}" max="{{$period->max_rate}}" name="self_score[{{$item->id}}]" value="{{count($item->scoring)>0&&$employee->status!=0?$item->scoring[0]->self_score:""}}"/></td>
                                                    <td><textarea class="form-control input-item input-employee" type="text" name="justification[{{$item->id}}]">{{count($item->scoring)>0&&$employee->status!=0?$item->scoring[0]->justification:""}}</textarea></td>
                                                    <td><input class="form-control input-item score score-manager" data-number="{{$no+1}}" data-weightage="{{$item->weightage}}" type="number" step="0.1" min="{{$period->min_rate}}" max="{{$period->max_rate}}" name="spv_score[{{$item->id}}]" value="{{count($item->scoring)>0&&$employee->status>=2?$item->scoring[0]->supervisor_score:""}}"/></td>
                                                    <td><textarea class="form-control input-item" type="text" name="comment[{{$item->id}}]">{{count($item->scoring)>0&&$employee->status>=2?$item->scoring[0]->comment:""}}</textarea></td>
                                                    <td id="final{{$no+1}}" class="text-center finals text-info text-capitalize">{{count($item->scoring)>0&&$employee->status>=2?($item->weightage*$item->scoring[0]->supervisor_score)/100:""}}</td>

                                                </tr>
                                                <?php $weightage += $item->weightage ?>
                                            @endforeach


                                            </tbody>
                                            <thead>
                                            <tr style="background: white">
                                                <td colspan="5" class="text-center"><b>Total</b></td>
                                                <td class="text-center"><b>{{$weightage}} %</b></td>
                                                <td class="text-center"  colspan="4" ></td>
                                                <td class="text-center"><b id="total"></b></td>

                                            </tr>
                                            </thead>
                                        </table>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    @if($employee->status == 3)
                                        <div class="col-md-10 col-xs-8">
                                            <label>Feedback</label>
                                            <textarea class="form-control" name="feedback" disabled>{{$employee->employee_feedback}}</textarea>
                                        </div>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <br/>
                                <div class="col-md-12">
                                    <a href="{{ route('administrator.kpi-survey.index') }}" class="btn btn-sm btn-default waves-effect waves-light"><i class="fa fa-arrow-left"></i> Back</a>
                                    <br style="clear: both;" />
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
        $('.input-item').attr('disabled',true);
        @if($employee->status>=2)
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
        @endif

        $('textarea').each(function () {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            }).on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>

@endsection
@endsection
