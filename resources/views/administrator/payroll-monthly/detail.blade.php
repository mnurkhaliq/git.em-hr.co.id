@extends('layouts.administrator')

@section('title', 'Payroll')

@section('content')
    <style>
        .field-icon {
            float: right;
            margin-right: 9px;
            margin-top: -24px;
            position: relative;
            z-index: 2;
        }
    </style>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12" >
                <h4 class="page-title">Employee Payroll <?php
                    $month = \Session::get('m-month');
                    $year  = \Session::get('m-year');
                    $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                    if(!empty($month) && !empty($year)) {
                        echo $bulanArray[$month]." ".$year;
                        $monthly = true;
                    }
                    else{
                        echo "Default";
                        $monthly = false;
                    }
                    ?></h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                @if(isset($create_by_payroll_id))
                    @php($is_lock = false);
                @else
                    @php($is_lock = ($data->is_lock == 1 ? true : false));
                @endif

                @if(!$is_lock)
                    @if(isset($update_history))
                        @if(get_setting('button_lock'))
                            <button type="submit" class="btn btn-sm btn-danger waves-effect waves-light m-r-10 pull-right" onclick="form_finalized()"><i class="fa fa-lock"></i> Finalized </button>
                        @endif
                    @endif
                    <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-right" onclick="form_submit()"><i class="fa fa-save"></i> Save Data </button>
                @endif
                <a href="{{ route('administrator.payroll-monthly.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10 pull-right"><i class="fa fa-arrow-left"></i> Back </a>
            </div>
        </div>
        <div class="row">
            <form class="form-horizontal" id="form-payroll" autocomplete="off" enctype="multipart/form-data" action="{{ route('administrator.payroll-monthly.update', $data->id) }}" method="POST">
               @if(isset($create_by_payroll_id))
               <input type="hidden" name="create_by_payroll_id" value="1">
               <input type="hidden" name="date" value="{{ isset($_GET['date']) ? $_GET['date'] : '' }}">
               @endif
    
               @if(isset($update_history))
               <input type="hidden" name="update_history" value="1">
               @endif
               <input type="hidden" name="is_lock" value="0" />
               <div class="col-md-4 p-l-0 source">
                    <div class="white-box" style="min-height: 440px;">
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
                        <div class="col-md-4">
                            <img src="{{ asset('images/user-man.png') }}" class="img-circle img-responsive td-foto">
                        </div>
                        <div class="col-md-8 m-t-30">
                            <div class="form-group">
                                <label class="col-md-12">NIK / Name</label>
                                <div class="col-md-12">
                                   <input type="text" class="form-control autocomplete-karyawan" value="{{ $data->user->nik }} - {{ $data->user->name }}" placeholder="Select Employee.." readonly disabled>
                                   <input type="hidden" name="user_id" value="{{ $data->user_id }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">P Number</label>
                                <div class="col-md-12">
                                   <input type="text" class="form-control" value="{{ $data->number }}" readonly disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Tipe</label>
                                <div class="col-md-12">
                                    <select class="form-control" id="payroll_type" name="payroll_type" {{ $is_lock ? 'disabled' : '' }} >
                                        <option value="NET" {{$data->payroll_type=="NET"?"SELECTED":""}}>NET</option>
                                        <option VALUE="GROSS" {{$data->payroll_type=="GROSS"?"SELECTED":""}}>GROSS</option>
                                    </select>
                                </div>
                            </div>
                            @if(empty($month) && empty($year))
                                <div class="form-group">
                                    <label class="col-md-12">Payslip Password</label>
                                    <div class="col-md-12">
                                        <input type="password" autocomplete="new-password" class="form-control" id="pdf_password" name="pdf_password" value="{{$data->pdf_password}}">
                                        <span class="field-icon toggle-password fa fa-fw fa-eye"></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <table class="table table-stripped m-t-20">
                            <tr>
                                <th style="width: 35%;">Email</th>
                                <th>:</th>
                                <th style="width: 65%;" class="td-email">{{ $data->user->email }} </th>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <th>:</th>
                                <th class="td-telepon">{{ $data->user->telepon }}</th>
                            </tr>
                            <tr>
                                <th>Take Home Pay</th>
                                <th>:</th>
                                <th class="td-thp">{{ number_format($data->thp) }}</th>
                            </tr>
                            @if($data->umr_label)
                            <tr>
                                <th>UMR</th>
                                <th>:</th>
                                <th class="td-umr">{{ $data->umr_label . ' (' . number_format($data->umr_value) . ')' }}</th>
                            </tr>
                            @endif
                            @if($data->payroll_cycle_label)
                            <tr>
                                <th>Payroll Cycle</th>
                                <th>:</th>
                                <th class="td-payroll_cycle">{{ $data->payroll_cycle_label . ' (' . get_cycle($data->payroll_cycle_start, $data->payroll_cycle_end, $month, $year) . ')' }}</th>
                            </tr>
                            @endif
                            @if($data->attendance_cycle_label)
                            <tr>
                                <th>Attendance Cycle</th>
                                <th>:</th>
                                <th class="td-attendance_cycle">{{ $data->attendance_cycle_label . ' (' . get_cycle($data->attendance_cycle_start, $data->attendance_cycle_end, $month, $year) . ')' }}</th>
                            </tr>
                            @endif
                            @if(!empty($month) && !empty($year))
                            <tr>
                                <th>Attendance</th>
                                <th>:</th>
                                <th>
                                    <?php $cycle = get_payroll_cycle();?>
                                    @if($cycle!=null)
                                        <a class="attendance" data-toggle="modal" data-target="#modal-attendance"></a>
                                    @else
                                        <a href="{{route('administrator.payroll-setting.index')}}" target="_blank" style="color: red">Payroll Cycle is not defined yet</a>
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>Overtime</th>
                                <th>:</th>
                                <th>
                                    @if($cycle!=null)
                                        <a class="overtime" data-toggle="modal" data-target="#modal-overtime"></a>
                                    @else
                                        <a href="{{route('administrator.payroll-setting.index')}}" target="_blank" style="color: red">Payroll Cycle is not defined yet</a>
                                    @endif
                                </th>
                            </tr>
                            @endif
                        </table>
                    </div>
               </div>
               <div class="col-md-4 p-l-0 targets" style="overflow-x: scroll;">
                    <div class="white-box p-t-10 m-b-0 inner">
                        <h3>Earning</h3>
                        <table class="table table-stripped" id="list_earnings">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;">Salary</td>
                                    <td><input type="text" class="form-control price_format calculate" {{ $is_lock ? 'disabled' : '' }} name="salary" placeholder="Rp. " value="{{ number_format($data->salary) }}" /></td> 
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Bonus</td>
                                    <td><input type="text" class="form-control price_format calculate" {{ $is_lock ? 'disabled' : '' }} name="bonus" value="{{ $data->bonus }}" placeholder="Rp. " /></td> 
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">THR</td>
                                    <td><input type="text" class="form-control price_format calculate" {{ $is_lock ? 'disabled' : '' }} name="thr" value="{{ $data->thr }}" placeholder="Rp. " /></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Overtime</td>
                                    <td><input type="text" class="form-control price_format calculate" {{ $is_lock ? 'disabled' : '' }} name="overtime" value="{{ $data->overtime }}" placeholder="Rp. " /></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Kecelakaan Kerja (JKK) (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_jkk_company" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_jkk_company) }}"  class="form-control bpjs_jkk_company" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Kematian (JKM) (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_jkm_company" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_jkm_company) }}"  class="form-control bpjs_jkm_company" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Hari Tua (JHT) (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_jht_company" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_jht_company) }}"  class="form-control bpjs_jht_company" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Pensiun (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_pensiun_company" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_pensiun_company) }}"  class="form-control bpjs_pensiun_company" />
                                    </td>
                                </tr>

                                <tr>
                                    <td style="vertical-align: middle;">BPJS Kesehatan (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_kesehatan_company" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_kesehatan_company) }}"  class="form-control bpjs_kesehatan_company" />
                                    </td>
                                </tr>
                                @foreach(get_earnings() as $item)
                                   @php($earning = ($monthly)?getEarningEmployeeDataHistory($item->id, $data->id):getEarningEmployee($item->id, $data->id))

                                    @if($earning)
                                        @php($earning->payrollEarnings->taxable==1?$taxable='Taxable':$taxable="Untaxable")
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $earning->payrollEarnings->title." ($taxable)" }}</td>
                                            <td>
                                                <input type="hidden" name="earning[]" {{ $is_lock ? 'disabled' : '' }} value="{{ $earning->payrollEarnings->id }}" /> 
                                                <input type="text" class="form-control calculate price_format" {{ $is_lock ? 'disabled' : '' }} name="earning_nominal[]" value="{{ number_format($earning->nominal) }}" />
                                            </td>
                                        </tr>
                                    @else
                                        @php($item->taxable==1?$taxable='Taxable':$taxable="Untaxable")
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $item->title." ($taxable)" }}</td>
                                            <td>
                                                <input type="hidden" name="earning[]" value="{{ $item->id }}" /> 
                                                <input type="text" class="form-control calculate price_format" {{ $is_lock ? 'disabled' : '' }} name="earning_nominal[]" value="{{ number_format($item->nominal) }}" />
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                @if(isset($data->businessTrips))
                                    @foreach($data->businessTrips as $item)
                                        @if($item->pengambilan_uang_muka - ($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) < 0)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ ($item->number ?: '') . ' (Untaxable)' }}</td>
                                            <td>
                                                <input type="text" class="form-control price_format" name="business_trip_earn[{{ $item->id }}]" value="{{ ($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) -$item->pengambilan_uang_muka  }}" disabled />
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @if(isset($data->cashAdvances))
                                    @foreach($data->cashAdvances as $item)
                                        @if(getCashAdvancePaymentDetail($item->id) < 0)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ ($item->number ?: '') . ' (Untaxable)' }}</td>
                                            <td>
                                                <input type="text" class="form-control price_format" name="cash_advance[{{ $item->id }}]" value="{{ getCashAdvancePaymentDetail($item->id) }}" disabled />
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </thead>
                            <tfoot>
                                <!-- start custom-->
                                <tr>
                                    <th>Monthly Income Tax / PPh21 (ditanggung perusahaan)</th>
                                    <th class="td-pph21 pph_earnings" colspan="2">{{ ($data->payroll_type=="GROSS")?"0":number_format($data->pph21) }}</th>
                                </tr>
                                <!--/end start custome-->
                                <tr>
                                    <th>Total Earnings </th>
                                    <th class="total_earnings">{{ number_format($data->total_earnings) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        {{--<a href="javascript:void(0)" class="btn btn-info btn-xs pull-right" onclick="add_income()"><i class="fa fa-plus"></i></a>--}}
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-4 p-l-0 p-r-0 targets" style="overflow-x: scroll;">
                    <div class="white-box p-t-10 m-b-0 inner">
                        <h3>Deduction</h3>
                        <table class="table table-stripped" id="list_deductions">
                            <thead>
                                <input type="hidden" name="burden_allow"  value="{{ number_format($data->burden_allow) }}" class="form-control burden_allow" />
                                <input type="hidden" name="yearly_income_tax"  value="{{ number_format($data->yearly_income_tax) }}" class="form-control yearly_income_tax" />
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Hari Tua (JHT) (Employee)</td>
                                    <td colspan="2">
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" name="bpjs_ketenagakerjaan_employee" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_ketenagakerjaan_employee) }}"  class="form-control bpjs_ketenagakerjaan_employee" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Kesehatan (Employee)</td>
                                    <td colspan="2">
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" name="bpjs_kesehatan_employee" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_kesehatan_employee) }}"  class="form-control bpjs_kesehatan_employee" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Pensiun (Employee)</td>
                                    <td colspan="2">
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" name="bpjs_pensiun_employee" {{ $is_lock ? 'disabled' : '' }} value="{{ number_format($data->bpjs_pensiun_employee) }}" class="form-control bpjs_pensiun_employee" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Total BPJS (Company)</td>
                                    <td colspan="2">
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" name="bpjstotalearning" readonly="true" value="{{ number_format($data->bpjstotalearning) }}" class="form-control bpjstotalearning" />
                                        </div>
                                    </td>
                                </tr>
                                @if(isset($data->loanPayments))
                                    @foreach($data->loanPayments as $item)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ ($item->loan->number ?: '') . ' Tenor ' . $item->tenor . ' (Untaxable)' }}</td>
                                            <td>
                                                <input type="text" class="form-control price_format" name="loan_payment[{{ $item->id }}]" value="{{ $item->amount }}" disabled />
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(isset($data->businessTrips))
                                    @foreach($data->businessTrips as $item)
                                        @if($item->pengambilan_uang_muka - ($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) > 0)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ ($item->number ?: '') . ' (Untaxable)' }}</td>
                                            <td>
                                                <input type="text" class="form-control price_format" name="business_trip_deduc[{{ $item->id }}]" value="{{ $item->pengambilan_uang_muka - ($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) }}" disabled />
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @if(isset($data->cashAdvances))
                                    @foreach($data->cashAdvances as $item)
                                        @if(getCashAdvancePaymentDetail($item->id) > 0)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ ($item->number ?: '') . ' (Untaxable)' }}</td>
                                            <td>
                                                <input type="text" class="form-control price_format" name="ca_deduc[{{ $item->id }}]" value="{{ getCashAdvancePaymentDetail($item->id) }}" disabled />
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @foreach(get_deductions() as $item)
                                    @php($deduction = ($monthly)?getDeductionEmployeeDataHistory($item->id, $data->id):getDeductionEmployee($item->id, $data->id))
                                    @if($deduction)
                                        @php($deduction->payrollDeductions->taxable==1?$taxable='Taxable':$taxable="Untaxable")
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $deduction->payrollDeductions->title." ($taxable)" }}</td>
                                            <td>
                                                <input type="hidden" name="deduction[]" {{ $is_lock ? 'disabled' : '' }} value="{{ $deduction->payrollDeductions->id }}" /> 
                                                <input type="text" class="form-control calculate price_format" {{ $is_lock ? 'disabled' : '' }} name="deduction_nominal[]" value="{{ $deduction->nominal }}" />
                                            </td>
                                        </tr>
                                    @else
                                        @php($item->taxable==1?$taxable='Taxable':$taxable="Untaxable")
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $item->title." ($taxable)" }}</td>
                                            <td>
                                                <input type="hidden" name="deduction[]" {{ $is_lock ? 'disabled' : '' }} value="{{ $item->id }}" /> 
                                                <input type="text" class="form-control calculate price_format" {{ $is_lock ? 'disabled' : '' }} name="deduction_nominal[]" value="{{ number_format($item->nominal) }}" />
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Monthly Income Tax / PPh21</th>
                                    <th class="td-pph21 pph_deductions" colspan="2">{{ number_format($data->pph21) }}</th>
                                </tr>
                                <tr>
                                    <th>Total Deduction</th>
                                    <th class="total_deductions">{{ number_format($data->total_deduction) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        <!--<a href="javascript:void(0)" class="btn btn-info btn-xs pull-right" onclick="add_deduction()"><i class="fa fa-plus"></i></a>-->
                        <div class="clearfix"></div>
                    </div>
                </div>
                <input type="hidden" name="bpjs_jkk_company" value="{{ $data->bpjs_jkk_company }}"/>
                <input type="hidden" name="bpjs_jkm_company" value="{{ $data->bpjs_jkm_company }}"/>
                <input type="hidden" name="bpjs_jht_company" value="{{ $data->bpjs_jht_company }}"/>
                <input type="hidden" name="bpjs_pensiun_company" value="{{ $data->bpjs_pensiun_company }}"/>
                <input type="hidden" name="bpjs_kesehatan_company" value="{{ $data->bpjs_kesehatan_company }}"/>
                <input type="hidden" name="bpjstotalearning" value="{{ $data->bpjstotalearning }}"/>

                <input type="hidden" name="bpjs_ketenagakerjaan2" value="{{ $data->bpjs_ketenagakerjaan2 }}" />
                <input type="hidden" name="bpjs_kesehatan2" value="{{ $data->bpjs_kesehatan2 }}" />
                <input type="hidden" name="bpjs_pensiun2" value="{{ $data->bpjs_pensiun2 }}" />
                <input type="hidden" name="total_deductions" value="{{ $data->total_deduction }}" />
                <input type="hidden" name="total_earnings" value="{{ $data->total_earnings }}" />
                <input type="hidden" name="thp" value="{{ $data->thp }}" />
                <input type="hidden" name="pph21" value="{{ $data->pph21 }}" />
                <input type="hidden" name="burden_allow" value="{{ $data->burden_allow }}" />
                 <input type="hidden" name="yearly_income_tax" value="{{ $data->yearly_income_tax }}" />
               @if(!empty($month) && !empty($year))
                   <input type="hidden" name="month" value="{{$month}}" />
                   <input type="hidden" name="year" value="{{$year}}" />
               @endif
                
                <input type="hidden" name="_method" value="PUT">
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <div  class="modal fade none-border" id="modal-attendance">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong id="modal_title">User Attendance</strong></h4>
                </div>
                <form id="form">
                    <div class="modal-body" id="modal-attendance-body">
                        <div class="col-md-6">
                            <h4>Start Date : <span id="start_date"></span></h4>
                        </div>
                        <div class="col-md-6">
                            <h4>End Date : <span id="end_date"></span></h4>
                        </div>
                        <div class="form-group col-md-12">
                            <table class="data_table_no_pagging table table-background">
                                <thead class="header" >
                                <tr>
                                    <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">No</th>
                                    <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Date</th>
                                    <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Day</th>
                                    <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Clock</th>
                                    <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Late</th>
                                    <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Early</th>
                                    <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Duration</th>
                                </tr>
                                <tr>
                                    <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">In</th>
                                    <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Out</th>
                                </tr>
                                </thead>
                                <tbody class="no-padding-td" id="data-attendance">



                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default save-event waves-effect waves-light" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div  class="modal fade none-border" id="modal-overtime">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong id="modal_title">User Overtime</strong></h4>
                </div>
                <form id="form">
                    <div class="modal-body" id="modal-overtime-body">
                        <div class="col-md-6">
                            <h4>Start Date : <span id="start_date"></span></h4>
                        </div>
                        <div class="col-md-6">
                            <h4>End Date : <span id="end_date"></span></h4>
                        </div>
                        <div class="form-group col-md-12">
                            <table class="data_table_no_pagging table table-background">
                                <thead class="header" >
                                <tr>
                                    <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">No</th>
                                    <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Date</th>
                                    <th colspan="3" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Earning</th>
                                </tr>
                                <tr>
                                    <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Overtime</th>
                                    <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Claim Approval</th>
                                    <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Overtime</th>
                                    <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Meal Allowance</th>
                                    <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Total</th>
                                </tr>
                                </thead>
                                <tbody class="no-padding-td" id="data-overtime">



                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default save-event waves-effect waves-light" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<script type="text/javascript">
    var var_edit_bpjs_ketenagakerjaan_employee       = 1;
    var var_edit_bpjs_kesehatan_employee             = 1;
    var var_edit_bpjs_pensiun_employee               = 1;
    var var_edit_bpjs_jkk_company                    = 1;
    var var_edit_bpjs_jkm_company                    = 1;
    var var_edit_bpjs_jht_company                    = 1;
    var var_edit_bpjs_pensiun_company                = 1;
    var var_edit_bpjs_kesehatan_company              = 1;
    var payroll_type                                 = $('#payroll_type').val();
    var is_calculating                               = false;
    var attendance_data                              = null;
    var overtime_data                                = null;

    function matchHeight() {
        var targetHeight = $('.source').height();
        $('.targets').css('height', targetHeight - 7);
        $('.inner').css('min-height', targetHeight - 7);
    }

    matchHeight();

    function money(bilangan) {
        var	number_string = bilangan.toString(),
            sisa 	= number_string.length % 3,
            rupiah 	= number_string.substr(0, sisa),
            ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return rupiah;
    }

    function form_finalized()
    {
        if(is_calculating){
            _alert("Payroll is being calculated, wait a second!");
            return false;
        }
        $("input[name='is_lock']").val(1);
        $("#form-payroll").submit();
    }

    function form_submit(msg = "")
    {
        // if($("input[name='user_id']").val() == "" || $("input[name='salary']").val() == "")
        if($("input[name='user_id']").val() == "")
        {
            _alert("@lang('payroll.message-employee-cannot-empty')");
            return false;
        }

        if(msg != "")
        {
            _confirm_submit(msg, $("#form-payroll"));   
        }

        if(is_calculating){
            _alert("Payroll is being calculated, wait a second!");
            return false;
        }

        else
        {
            $("#form-payroll").submit();            
        }
    }

    $("#payroll_type").on('change', function () {
        payroll_type = $(this).val();
        calculate();
    });

    // start custom
    $("input[name='bpjs_ketenagakerjaan_employee']").on('input', function(){
        var_edit_bpjs_ketenagakerjaan_employee = 1;
        calculate();
    }); 
    $("input[name='bpjs_kesehatan_employee']").on('input', function(){
        var_edit_bpjs_kesehatan_employee = 1;
        calculate();
    });
    $("input[name='bpjs_pensiun_employee']").on('input', function(){
        var_edit_bpjs_pensiun_employee = 1;
        calculate();
    });

    $("input[name='bpjs_jkk_company']").on('input', function(){
        var_edit_bpjs_jkk_company = 1;
        calculate();
    });
    $("input[name='bpjs_jkm_company']").on('input', function(){
        var_edit_bpjs_jkm_company = 1;
        calculate();
    });
    $("input[name='bpjs_jht_company']").on('input', function(){
        var_edit_bpjs_jht_company = 1;
        calculate();
    });
    $("input[name='bpjs_pensiun_company']").on('input', function(){
        var_edit_bpjs_pensiun_company = 1;
        calculate();
    });
    $("input[name='bpjs_kesehatan_company']").on('input', function(){
        var_edit_bpjs_kesehatan_company = 1;
        calculate();
    });
    
    // end custom

    function init_calculate()
    {   
        $('.calculate').each(function(){
            $(this).off();

            $(this).on('input change', function(){
                @if(empty($month) && empty($year))
                    if($(this).attr('name')=='salary'){
                        var_edit_bpjs_ketenagakerjaan_employee       = 0;
                        var_edit_bpjs_kesehatan_employee             = 0;
                        var_edit_bpjs_pensiun_employee               = 0;
                        var_edit_bpjs_jkk_company                    = 0;
                        var_edit_bpjs_jkm_company                    = 0;
                        var_edit_bpjs_jht_company                    = 0;
                        var_edit_bpjs_pensiun_company                = 0;
                        var_edit_bpjs_kesehatan_company              = 0;
                    }
                @endif
                calculate();
            });
        });
    }

    init_calculate();

    var json_earnings = [];
    @foreach(get_earnings() as $item)
        json_earnings[{{ $item->id }}] = ({'id' : {{ $item->id }}, 'title' : '{{ $item->title }}', 'taxable' : '{{ $item->taxable }}'});
    @endforeach

    var json_deductions = [];
    @foreach(get_deductions() as $item)
        json_deductions[{{ $item->id }}] = ({'id' : {{ $item->id }}, 'title' : '{{ $item->title }}'});
    @endforeach

    function add_income()
    {
        var el = "<tr>";
            el += '<td>';

            el += '<select class="form-control" name="earning[]">';
            var status;
            $(json_earnings).each(function(k,v){
                if(v !== null && typeof v === 'object')
                {
                    if(v.taxable == 1)
                        status = 'Taxable';
                    else
                        status = 'Untaxable';
                    el += '<option value="'+ v.id +'" data-title="'+ v.title +'">'+ v.title +'('+status+')</option>';
                }
            });
            el += '</select>';

            el +='</td>';
            el += '<td><input type="text" name="earning_nominal[]" class="form-control calculate price_format" placeholder="Rp. " /></td>';
            el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
            el += "</tr>";

        $("#list_earnings").append(el);
        
        init_calculate();
        price_format();
    }

    function add_deduction()
    {
        var el = "<tr>";
            el += '<td>';

            el += '<select class="form-control" name="deduction[]">';
            $(json_deductions).each(function(k,v){
                if(v !== null && typeof v === 'object')
                {
                    el += '<option value="'+ v.id +'" data-title="'+ v.title +'">'+ v.title +'</option>';
                }
            });
            el += '</select>';

            el +='</td>';
            el += '<td><input type="text" name="deduction_nominal[]" class="form-control calculate price_format" placeholder="Rp. " /></td>';
            el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
            el += "</tr>";

        $("#list_deductions").append(el);

        init_calculate();
        price_format();
    }

    function remove_item(el, submit=false)
    {
        var obj = $(el).parent().parent();
        
        $(el).parent().parent().remove();

        calculate();
    }

    var payroll_marital_status = "<?= (!empty($month) && !empty($year) && $year > \Carbon\Carbon::now()->format('Y')) ? $data->user->marital_status : $data->user->payroll_marital_status ?>";
    var payroll_jenis_kelamin = "<?= (!empty($month) && !empty($year) && $year > \Carbon\Carbon::now()->format('Y')) ? $data->user->jenis_kelamin : $data->user->payroll_jenis_kelamin ?>";

    function calculate()
    {
        var earnings         = [];
        var earning_items    = [];
        var deduction_items  = [];
        var deductions       = [];
        var loan_payments    = [];
        var business_trip_payments    = [];
        var business_trip_payments_deduc    = [];
        var cash_advance_payments    = [];
        var cash_advance_payments_deduc    = [];
        var salary           = $("input[name='salary']").val();
        var bonus            = $("input[name='bonus']").val() == "" ? 0 : $("input[name='bonus']").val();
        var thr              = $("input[name='thr']").val() == "" ? 0 : $("input[name='thr']").val();
        var overtime         = $("input[name='overtime']").val() == "" ? 0 : $("input[name='overtime']").val();

        $("input[name='earning_nominal[]']").each(function(index, item){
            earnings.push($(this).val());
        });
        $("input[name='deduction_nominal[]']").each(function(index, item){
            deductions.push($(this).val());
        });
        $("input[name='earning[]']").each(function() {
            earning_items.push($(this).val());
        });
        $("input[name='deduction[]']").each(function() {
            deduction_items.push($(this).val());
        });
        $("input[name^='loan_payment']").each(function(index, item){
            loan_payments.push($(this).val());
        });
        $("input[name^='business_trip_earn']").each(function(index, item){
            business_trip_payments.push($(this).val());
        });
        $("input[name^='business_trip_deduc']").each(function(index, item){
            business_trip_payments_deduc.push($(this).val());
        });
        $("input[name^='cash_advance']").each(function(index, item){
            cash_advance_payments.push($(this).val());
        });
        $("input[name^='ca_deduc']").each(function(index, item){
            cash_advance_payments_deduc.push($(this).val());
        });

        var sum_earnings = $("input[name='earning_nominal[]']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);
        sum_earnings += $("input[name^='business_trip_earn']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);
        sum_earnings += $("input[name^='cash_advance']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);
        var sum_deductions = $("input[name='deduction_nominal[]']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);
        sum_deductions += $("input[name^='loan_payment']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);
        sum_deductions += $("input[name^='business_trip_deduc']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);
        sum_deductions += $("input[name^='ca_deduc']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);
        is_calculating = true;
        $.ajax({
            url: "{{ route('ajax.get-calculate-payroll') }}",
            method : 'POST',
            data: {
                earning_items,
                earnings,
                deduction_items,
                deductions,
                loan_payments,
                business_trip_payments,
                business_trip_payments_deduc,
                cash_advance_payments,
                cash_advance_payments_deduc,
                salary : salary,
                bonus : bonus,
                thr : thr,
                overtime : overtime,
                payroll_marital_status : payroll_marital_status,
                payroll_jenis_kelamin : payroll_jenis_kelamin,
                user_id : $("input[name='user_id']").val(),
                
                // start custom
                bpjs_ketenagakerjaan_employee: $('.bpjs_ketenagakerjaan_employee').val(),
                bpjs_kesehatan_employee: $('.bpjs_kesehatan_employee').val(),
                bpjs_pensiun_employee: $('.bpjs_pensiun_employee').val(),
                edit_bpjs_ketenagakerjaan_employee : var_edit_bpjs_ketenagakerjaan_employee,
                edit_bpjs_kesehatan_employee : var_edit_bpjs_kesehatan_employee,
                edit_edit_bpjs_pensiun_employee : var_edit_bpjs_pensiun_employee,

                bpjs_jkk_company: $('.bpjs_jkk_company').val(),
                bpjs_jkm_company: $('.bpjs_jkm_company').val(),
                bpjs_jht_company: $('.bpjs_jht_company').val(),
                bpjs_pensiun_company: $('.bpjs_pensiun_company').val(),
                bpjs_kesehatan_company: $('.bpjs_kesehatan_company').val(),
                edit_bpjs_jkk_company : var_edit_bpjs_jkk_company,
                edit_bpjs_jkm_company : var_edit_bpjs_jkm_company,
                edit_bpjs_jht_company : var_edit_bpjs_jht_company,
                edit_bpjs_pensiun_company : var_edit_bpjs_pensiun_company,
                edit_bpjs_kesehatan_company : var_edit_bpjs_kesehatan_company,
                payroll_type : payroll_type,
                // end custom
                
                '_token' : $("meta[name='csrf-token']").attr('content')
            },
            success: function( data ) {
                var thp  = data.thp.split(',').join('');
                $('.td-thp').html(numberWithDot(thp));
                //var thp = parseInt(data.thp.split('.').join(''));
                //$('.td-thp').html(numberWithDot(data.thp));

                if(payroll_type=='NET') {
                    $('.td-pph21').html(data.monthly_income_tax);
                }else{
                    $('.td-pph21.pph_deductions').html(data.monthly_income_tax);
                    $('.td-pph21.pph_earnings').html('0');
                }
                //$("input[name='bpjs_ketenagakerjaan']").val(data.bpjs_ketenagakerjaan);
                $("input[name='bpjs_ketenagakerjaan2']").val(data.bpjs_ketenagakerjaan2);
                //$("input[name='bpjs_kesehatan']").val(data.bpjs_kesehatan);
                $("input[name='bpjs_kesehatan2']").val(data.bpjs_kesehatan2);
                //$("input[name='bpjs_pensiun']").val(data.bpjs_pensiun);
                $("input[name='bpjs_pensiun2']").val(data.bpjs_pensiun2);

                $("input[name='bpjs_jkk_company']").val(data.bpjs_jkk_company);
                $("input[name='bpjs_jkm_company']").val(data.bpjs_jkm_company);
                $("input[name='bpjs_jht_company']").val(data.bpjs_jht_company);
                $("input[name='bpjs_pensiun_company']").val(data.bpjs_pensiun_company);
                $("input[name='bpjs_kesehatan_company']").val(data.bpjs_kesehatan_company);
                $("input[name='bpjstotalearning']").val(data.bpjstotalearning);
                
                $("input[name='thp']").val(parseInt(thp));
                //$("input[name='thp']").val(parseInt(data.thp));
                $("input[name='pph21']").val(data.monthly_income_tax);
                $('.bpjs_ketenagakerjaan_employee').val(data.bpjs_ketenagakerjaan2);
                $('.bpjs_kesehatan_employee').val(data.bpjs_kesehatan2);
                $('.bpjs_pensiun_employee').val(data.bpjs_pensiun2);
                $("input[name='burden_allow']").val(data.burden_allow);
                $('.burden_allow').val(data.burden_allow);
                $("input[name='yearly_income_tax']").val(data.yearly_income_tax);
                $('.yearly_income_tax').val(data.yearly_income_tax);
                
                bonus = bonus != 0 ? bonus.split('.').join('') : 0;
                thr   = thr != 0 ? thr.split('.').join('') : 0;
                overtime   = overtime != 0 ? overtime.split('.').join('') : 0;

                sum_earnings    = sum_earnings + parseInt(salary.split('.').join('')) + parseInt(bonus) + parseInt(thr) + parseInt(overtime);
                sum_deductions  = parseInt(data.monthly_income_tax.split(',').join('')) + sum_deductions + parseInt(data.bpjs_ketenagakerjaan2.split(',').join('')) + parseInt(data.bpjs_kesehatan2.split(',').join('')) + parseInt(data.bpjstotalearning.split(',').join('')) + parseInt(data.bpjs_pensiun2.split(',').join(''));
                
                // start custom
                if(payroll_type == 'NET')
                    sum_earnings    = parseInt(sum_earnings) + parseInt(data.monthly_income_tax.split(',').join(''))+ parseInt(data.bpjstotalearning.split(',').join(''));
                else
                    sum_earnings    = parseInt(sum_earnings) + parseInt(data.bpjstotalearning.split(',').join(''));
                // end custom
                 
                $("input[name='total_earnings']").val(sum_earnings);
                $("input[name='total_deductions']").val(sum_deductions);
                $(".total_earnings").html(numberWithDot(sum_earnings));
                $(".total_deductions").html(numberWithDot(sum_deductions));
                
                //var_edit_bpjs_ketenagakerjaan_employee  = 0;
                //var_edit_bpjs_kesehatan_employee        = 0;
                //var_edit_bpjs_pensiun_employee          = 0;

                var_edit_bpjs_ketenagakerjaan_employee       = 1;
                var_edit_bpjs_kesehatan_employee             = 1;
                var_edit_bpjs_pensiun_employee               = 1;
                var_edit_bpjs_jkk_company                    = 1;
                var_edit_bpjs_jkm_company                    = 1;
                var_edit_bpjs_jht_company                    = 1;
                var_edit_bpjs_pensiun_company                = 1;
                var_edit_bpjs_kesehatan_company              = 1;
                is_calculating = false;
                price_format();
            }
        })
    }

    $(".autocomplete-karyawan" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
              url: "{{ route('ajax.get-karyawan-payroll') }}",
              method : 'POST',
              data: {
                'name': request.term,'_token' : $("meta[name='csrf-token']").attr('content')
              },
              success: function( data ) {
                  console.log(data);
                response( data );
              }
            });
        },
        select: function( event, ui ) {
            $("input[name='user_id']").val(ui.item.id);

             $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : ui.item.id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {
                    @if(!empty($month) && !empty($year) && $year > \Carbon\Carbon::now()->format('Y'))
                        payroll_marital_status = data.data.marital_status;
                        payroll_jenis_kelamin = data.data.jenis_kelamin;
                    @else
                        payroll_marital_status = data.data.payroll_marital_status;
                        payroll_jenis_kelamin = data.data.payroll_jenis_kelamin;
                    @endif

                    $('.td-foto').attr('src', data.data.foto);
                    if(data.data.email != null)
                    {
                        $('.td-email').html(data.data.email);
                    }
                    else
                    {
                        $('.td-email').html("");   
                    }
                    if(data.data.telepon != null)
                    {
                        $('.td-telepon').html(data.data.telepon);                        
                    }
                    else
                    {
                        $('.td-telepon').html('');                        
                    }
                }
            });
        }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });

    @if(!empty($month) && !empty($year))
        getAttendanceAndOvertime();
        function getAttendanceAndOvertime() {
            var user_id = $("input[name='user_id']").val();
            if(user_id==''){
                alert('Select user first');
            }
            else{
                $.ajax({
                    type: 'GET',
                    url: '{{ route('ajax.payroll.attendance') }}',
                    data: {'user_id' : user_id,'month':"{{$month}}",'year':'{{$year}}','id':'{{$data->id}}', '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {
                        attendance_data = data;
                        $('.attendance').html(attendance_data.attendance.length);
                        $('#modal_title').html("User Attendance "+$('.autocomplete-karyawan').val());
                        var row="";
                        for(var i = 0; i < attendance_data.attendance.length; i++){

                            var date        = attendance_data.attendance[i].date!=null?attendance_data.attendance[i].date:'';
                            var day         = attendance_data.attendance[i].timetable!=null?attendance_data.attendance[i].timetable:'';
                            var clock_in    = attendance_data.attendance[i].clock_in!=null?attendance_data.attendance[i].clock_in:'';
                            var clock_out   = attendance_data.attendance[i].clock_out!=null?attendance_data.attendance[i].clock_out:'';
                            var late        = attendance_data.attendance[i].late!=null?attendance_data.attendance[i].late:'';
                            var early       = attendance_data.attendance[i].early!=null?attendance_data.attendance[i].early:'';
                            var duration    = attendance_data.attendance[i].work_time!=null?attendance_data.attendance[i].work_time:'';

                            if(attendance_data.attendance[i].attendance_type_in == 'remote')
                                clock_in += " (R)";
                            else if(attendance_data.attendance[i].attendance_type_in == 'out_of_office')
                                clock_in += " (O)";
                            if(attendance_data.attendance[i].attendance_type_out == 'remote')
                                clock_out += " (R)";
                            else if(attendance_data.attendance[i].attendance_type_out == 'out_of_office')
                                clock_out += " (O)";
                            if(attendance_data.attendance[i].date != attendance_data.attendance[i].date_out)
                                clock_out += ' (ND)';

                            row += '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+date+'</td>'+
                                '<td>'+day+'</td>'+
                                '<td>'+clock_in+'</td>'+
                                '<td>'+clock_out+'</td>'+
                                '<td>'+late+'</td>'+
                                '<td>'+early+'</td>'+
                                '<td>'+duration+'</td>'+
                                '</tr>';
                        }
                        $('#data-attendance').html(row);
                        $('#start_date').html(attendance_data.start_date);
                        $('#end_date').html(attendance_data.end_date);
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: '{{ route('ajax.payroll.overtime') }}',
                    data: {'user_id' : user_id,'month':"{{$month}}",'year':'{{$year}}','id':'{{$data->id}}', '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {
                        overtime_data = data;
                        $('.overtime').html(overtime_data.overtime.length);
                        $('#modal-overtime #modal_title').html("User Overtime "+$('.autocomplete-karyawan').val());
                        var row="";
                        var total=0;
                        for(var i = 0; i < overtime_data.overtime.length; i++){

                            var date        = overtime_data.overtime[i].tanggal!=null?overtime_data.overtime[i].tanggal:'';
                            var approval    = overtime_data.overtime[i].updated_at!=null?overtime_data.overtime[i].updated_at:'';
                            var earning     = overtime_data.overtime[i].payroll_calculate!=null?overtime_data.overtime[i].payroll_calculate:0;
                            var meal        = overtime_data.overtime[i].meal_allowance!=null?overtime_data.overtime[i].meal_allowance:0;
                            var rowTotal    = parseInt(earning) + parseInt(meal);

                            total += parseInt(rowTotal)

                            row += '<tr>'+
                                '<td>'+(i+1)+'</td>'+
                                '<td>'+date+'</td>'+
                                '<td>'+approval+'</td>'+
                                '<td>'+money(earning)+'</td>'+
                                '<td>'+money(meal)+'</td>'+
                                '<td>'+money(rowTotal)+'</td>'+
                                '</tr>';
                        }
                        @if(!$is_lock)
                            if (total) $("input[name='overtime']").val(money(total));
                        @endif
                        $('#data-overtime').html(row);
                        $('#modal-overtime #start_date').html(overtime_data.start_date);
                        $('#modal-overtime #end_date').html(overtime_data.end_date);
                    }
                });
            }
        }
    @endif
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");

        var input = $("#pdf_password");

        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
@endsection
@endsection