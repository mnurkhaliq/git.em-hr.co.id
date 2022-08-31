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
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Employee Payroll <?php
                        $month = null;
                        $year  = null;
                        $bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
                        if(!empty($month) && !empty($year)) {
                            echo $bulanArray[$month]." ".$year;
                        }
                        ?> </h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-right" onclick="form_submit()" id="btn_submit"><i class="fa fa-save"></i> Save Data </button>
                </div>
            </div>
            <div class="row">
                <form class="form-horizontal" id="form-payroll" autocomplete="off" enctype="multipart/form-data" action="{{ route('administrator.payroll.store') }}" method="POST">
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
                                        <input type="text" value="" class="form-control autocomplete-karyawan" placeholder="Select Employee..">
                                        <input type="hidden" name="user_id">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Tipe</label>
                                    <div class="col-md-12">
                                        <select class="form-control" id="payroll_type" name="payroll_type">
                                            <option value="NET">NET</option>
                                            <option VALUE="GROSS">GROSS</option>
                                        </select>
                                    </div>
                                </div>
                                @if(empty($month) && empty($year))
                                    <div class="form-group">
                                        <label class="col-md-12">Payslip Password</label>
                                        <div class="col-md-12">
                                            <input type="password" autocomplete="new-password" class="form-control" id="pdf_password" name="pdf_password">
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
                                    <th style="width: 65%;" class="td-email"> </th>
                                </tr>
                                <tr>
                                    <th>Telepon</th>
                                    <th>:</th>
                                    <th class="td-telepon"></th>
                                </tr>
                                <tr>
                                    <th>Take Home Pay</th>
                                    <th>:</th>
                                    <th class="td-thp"></th>
                                </tr>
                                <tr class="tr-umr" style="display: none;">
                                    <th>UMR</th>
                                    <th>:</th>
                                    <th class="td-umr"></th>
                                </tr>
                                <tr class="tr-payroll_cycle" style="display: none;">
                                    <th>Payroll Cycle</th>
                                    <th>:</th>
                                    <th class="td-payroll_cycle"></th>
                                </tr>
                                <tr class="tr-attendance_cycle" style="display: none;">
                                    <th>Attendance Cycle</th>
                                    <th>:</th>
                                    <th class="td-attendance_cycle"></th>
                                </tr>
                                @if(!empty($month) && !empty($year))
                                <tr>
                                    <th>Attendance</th>
                                    <th>:</th>
                                    <th>
                                        <?php $cycle = get_payroll_cycle();?>
                                        @if($cycle!=null)
                                            <a class="attendance" href="#" onclick="getAttendance()">0</a>
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
                                            <a class="overtime" href="#" onclick="getOvertime()">0</a>
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
                            <table class="table table-stripped">
                                <thead>
                                <tr>
                                    <td style="vertical-align: middle;">Salary</td>
                                    <td><input type="text" class="form-control price_format calculate" name="salary" placeholder="Rp. " /></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Bonus</td>
                                    <td><input type="text" class="form-control price_format calculate" name="bonus" placeholder="Rp. " /></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">THR</td>
                                    <td><input type="text" class="form-control price_format calculate" name="thr" placeholder="Rp. " /></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Overtime</td>
                                    <td><input type="text" class="form-control price_format calculate" name="overtime" placeholder="Rp. " /></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Kecelakaan Kerja (JKK) (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_jkk_company"  class="form-control bpjs_jkk_company" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Kematian (JKM) (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_jkm_company"  class="form-control bpjs_jkm_company" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Hari Tua (JHT) (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_jht_company"  class="form-control bpjs_jht_company" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Pensiun (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_pensiun_company"  class="form-control bpjs_pensiun_company" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Kesehatan (Company)</td>
                                    <td>
                                        <input type="text" name="bpjs_kesehatan_company"  class="form-control bpjs_kesehatan_company" />
                                    </td>
                                </tr>
                                </thead>
                                <tfoot>
                                <!-- start custom-->
                                <tr>
                                    <th>Monthly Income Tax / PPh21 (ditanggung perusahaan)</th>
                                    <th class="td-pph21 pph_earnings" colspan="2"></th>
                                </tr>
                                <!--/end start custome-->
                                <tr>
                                    <th>Total Earnings </th>
                                    <th class="total_earnings"></th>
                                </tr>
                                </tfoot>
                                <tbody id="list_earnings"></tbody>
                                <thead id="list_business_trips"></thead>
                                <thead id="list_cash_advances"></thead>
                            </table>
                            <a href="javascript:void(0)" class="btn btn-info btn-xs pull-right" onclick="add_income()"><i class="fa fa-plus"></i></a>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="col-md-4 p-l-0 p-r-0 targets" style="overflow-x: scroll;">
                        <div class="white-box p-t-10 m-b-0 inner">
                            <h3>Deduction</h3>
                            <table class="table table-stripped">
                                <thead>
                                <input type="hidden" name="burden_allow"  class="form-control burden_allow" />
                                <input type="hidden" name="yearly_income_tax"  class="form-control yearly_income_tax" />
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Jaminan Hari Tua (JHT) (Employee)</td>
                                    <td>
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" name="bpjs_ketenagakerjaan_employee"  class="form-control bpjs_ketenagakerjaan_employee" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Kesehatan (Employee)</td>
                                    <td>
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" name="bpjs_kesehatan_employee" class="form-control bpjs_kesehatan_employee" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">BPJS Pensiun (Employee)</td>
                                    <td>
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" name="bpjs_pensiun_employee" class="form-control bpjs_pensiun_employee" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Total BPJS (Company)</td>
                                    <td>
                                        <div class="col-md-12 p-r-0 p-l-0">
                                            <input type="text" readonly="true" name="bpjstotalearning" class="form-control bpjstotalearning" />
                                        </div>
                                    </td>
                                </tr>
                                </thead>
                                <thead id="list_loans"></thead>
                                <thead id="list_business_trips_deduct"></thead>
                                <thead id="list_cash_advances_deduct"></thead>
                                <tfoot>
                                <tr>
                                    <th>Monthly Income Tax / PPh21</th>
                                    <th class="td-pph21 pph_deductions"></th>
                                </tr>
                                <tr>
                                    <th>Total Deduction</th>
                                    <th class="total_deductions"></th>
                                </tr>
                                </tfoot>
                                <tbody id="list_deductions"></tbody>
                            </table>
                            <a href="javascript:void(0)" class="btn btn-info btn-xs pull-right" onclick="add_deduction()"><i class="fa fa-plus"></i></a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <input type="hidden" name="bpjs_jkk_company" />
                    <input type="hidden" name="bpjs_jkm_company" />
                    <input type="hidden" name="bpjs_jht_company" />
                    <input type="hidden" name="bpjs_pensiun_company" />
                    <input type="hidden" name="bpjs_kesehatan_company" />
                    <input type="hidden" name="bpjstotalearning" />

                    <input type="hidden" name="bpjs_ketenagakerjaan2" />
                    <input type="hidden" name="bpjs_kesehatan2" />
                    <input type="hidden" name="bpjs_pensiun2" />
                    <input type="hidden" name="total_deductions" />
                    <input type="hidden" name="total_earnings" />
                    <input type="hidden" name="thp" />
                    <input type="hidden" name="pph21" />
                    <input type="hidden" name="burden_allow" />
                    <input type="hidden" name="yearly_income_tax" />
                    @if(!empty($month) && !empty($year))
                        <input type="hidden" name="month" value="{{$month}}" />
                        <input type="hidden" name="year" value="{{$year}}" />
                    @endif
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
        var var_edit_bpjs_ketenagakerjaan_employee       = 0;
        var var_edit_bpjs_kesehatan_employee             = 0;
        var var_edit_bpjs_pensiun_employee               = 0;
        var var_edit_bpjs_jkk_company                    = 0;
        var var_edit_bpjs_jkm_company                    = 0;
        var var_edit_bpjs_jht_company                    = 0;
        var var_edit_bpjs_pensiun_company                = 0;
        var var_edit_bpjs_kesehatan_company              = 0;
        var payroll_type                                 = 'NET';
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

        function form_submit()
        {
            // if($("input[name='user_id']").val() == "" || $("input[name='salary']").val() == "")
            if($("input[name='user_id']").val() == "")
            {
                _alert("@lang('payroll.message-employee-cannot-empty')");
                return false;
            }
            if(is_calculating){
                _alert("Payroll is being calculated, wait a second!");
                return false;
            }
            var c_earning = check_earning();
            if(c_earning !== true){
                _alert("Earning "+json_earnings[c_earning].title.toUpperCase()+" can't be more than one item!");
                return false;
            }
            var c_deduction = check_deduction();
            if(c_deduction !== true){
                _alert("Deduction "+json_deductions[c_deduction].title.toUpperCase()+" can't be more than one item!");
                return false;
            }



            $('#btn_submit').attr('disabled',true);
            $("#form-payroll").submit();
        }

        function check_earning(){
            var earning_arr = [];
            var response = true;
            $("select[name='earning[]']").each(function() {
                var earning_id = $(this).val();
                if(earning_arr.includes(earning_id)){
                    response = earning_id;
                    return false;
                }
                else
                    earning_arr.push(earning_id);
            });
            return response;
        }
        function check_deduction(){
            var deduction_arr = [];
            var response = true;
            $("select[name='deduction[]']").each(function() {
                var deduction_id = $(this).val();
                if(deduction_arr.includes(deduction_id)){
                    response = deduction_id;
                    return false;
                }
                else
                    deduction_arr.push(deduction_id);
            });
            return response;
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
            json_deductions[{{ $item->id }}] = ({'id' : {{ $item->id }}, 'title' : '{{ $item->title }}', 'taxable' : '{{ $item->taxable }}'});
        @endforeach

        function add_income(earning = null, prorate = 1)
        {

            var el = "<tr class='earnings'>";
            el += '<td>';

            el += '<select class="form-control" name="earning[]">';
            var payroll_earning_id = null;
            var status;
            $(json_earnings).each(function(k,v){
                if(v !== null && typeof v === 'object')
                {
                    if(v.taxable == 1)
                        status="Taxable"
                    else
                        status="Untaxable";
                    if(earning==null || earning.payroll_earning_id != v.id) {
                        el += '<option value="' + v.id + '" data-title="' + v.title + '">' + v.title +' ('+status+')</option>';
                    }
                    else{
                        el += '<option value="' + v.id + '" data-title="' + v.title + '" selected>' + v.title +' ('+status+')</option>';
                        payroll_earning_id = v.id;
                    }
                }
            });
            el += '</select>';
            el +='</td>';
            if(earning==null || earning.payroll_earning_id != payroll_earning_id) {
                el += '<td><input type="text" name="earning_nominal[]" class="form-control calculate price_format" placeholder="Rp. " /></td>';
            }
            else{
                el += '<td><input type="text" name="earning_nominal[]" class="form-control calculate price_format" placeholder="Rp. " value="'+Math.round(earning.nominal.split('.').join('')*prorate)+'"/></td>';
            }
            el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
            el += "</tr>";

            $("#list_earnings").append(el);
            init_calculate();
            price_format();

            $("select[name='earning[]']").each(function(){
                $(this).off();
                $(this).on('change',function () {
                    calculate();
                })
            });

        }

        function add_deduction(deduction = null)
        {

            var el = "<tr class='deductions'>";
            el += '<td>';

            el += '<select class="form-control" name="deduction[]">';
            var payroll_deduction_id = null;
            var status;
            $(json_deductions).each(function(k,v){
                if(v !== null && typeof v === 'object') {
                    if(v.taxable == 1)
                        status="Taxable"
                    else
                        status="Untaxable";
                    if (deduction == null || deduction.payroll_deduction_id != v.id) {
                        el += '<option value="' + v.id + '" data-title="' + v.title + '">' + v.title +' ('+status+')</option>';
                    } else {
                        el += '<option value="' + v.id + '" data-title="' + v.title + '" selected>' + v.title +' ('+status+')</option>';
                        payroll_deduction_id = v.id;
                    }
                }
            });
            el += '</select>';

            el +='</td>';
            if(deduction==null || deduction.payroll_deduction_id != payroll_deduction_id) {
                el += '<td><input type="text" name="deduction_nominal[]" class="form-control calculate price_format" placeholder="Rp. " /></td>';
            }
            else{
                el += '<td><input type="text" name="deduction_nominal[]" class="form-control calculate price_format" placeholder="Rp. " value="'+deduction.nominal+'"/></td>';
            }
            el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
            el += "</tr>";

            $("#list_deductions").append(el);

            init_calculate();
            price_format();

            $("select[name='deduction[]']").each(function(){
                $(this).off();
                $(this).on('change',function () {
                    calculate();
                })
            });
        }

        function add_loan(loan_payment = null)
        {
            var el = "<tr class='loan_payments'>";

            el += '<td style="vertical-align: middle;">' + (loan_payment.loan.number ? loan_payment.loan.number : '') + ' Tenor ' + loan_payment.tenor + ' (Untaxable)</td>';

            el += '<td><input type="text" class="form-control price_format" value="' + loan_payment.amount + '" disabled /><input type="hidden" name="loan_payment[' + loan_payment.id + ']" class="calculate price_format" value="' + loan_payment.amount + '"/></td>';
            
            el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
            
            el += "</tr>";

            $("#list_loans").append(el);

            init_calculate();
            price_format();

            $("input[name^='loan_payment']").each(function(){
                $(this).off();
                $(this).on('change',function () {
                    calculate();
                })
            });
        }

        function add_business_trip(business_trip = null)
        {
            var total = parseInt(business_trip.sub_total_1_disetujui) + parseInt(business_trip.sub_total_2_disetujui) + parseInt(business_trip.sub_total_3_disetujui) + parseInt(business_trip.sub_total_4_disetujui);
            var sisa = parseInt(business_trip.pengambilan_uang_muka) - parseInt(total);
            
            if(parseInt(sisa) > 0){
                var el = "<tr class='business_trip_payments_deduc'>";
                el += '<td style="vertical-align: middle;">' + (business_trip.number ? business_trip.number : '') + ' (Untaxable)</td>';
                el += '<td><input type="text" class="form-control price_format" value="' + sisa + '" disabled /><input type="hidden" name="training_deduc[' + business_trip.id + ']" class="calculate price_format" value="' + sisa + '"/></td>';
                el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
                el += "</tr>";

                $("#list_business_trips_deduct").append(el);

                init_calculate();
                price_format();

                $("input[name^='training_deduc']").each(function(){
                    $(this).off();
                    $(this).on('change',function () {
                        calculate();
                    })
                });
            }
            if(parseInt(sisa) < 0){
                var el2 = "<tr class='business_trip_payments'>";
                el2 += '<td style="vertical-align: middle;">' + (business_trip.number ? business_trip.number : '') + ' (Untaxable)</td>';
                el2 += '<td><input type="text" class="form-control price_format" value="' + sisa + '" disabled /><input type="hidden" name="business_trip[' + business_trip.id + ']" class="calculate price_format" value="' + (-1 * sisa) + '"/></td>';
                el2 += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
                el2 += "</tr>";

                // console.log(el2)
                $("#list_business_trips").append(el2);

                init_calculate();
                price_format();

                $("input[name^='business_trip']").each(function(){
                    $(this).off();
                    $(this).on('change',function () {
                        calculate();
                    })
                });
            }
        }

        function add_cash_advance(cash_advance = null)
        {
           var sisa = parseInt(cash_advance.total_amount_approved) - parseInt(cash_advance.total_amount_claimed)
            
            if(sisa < 0){
                var el = "<tr class='cash_advance_payments'>";
                el += '<td style="vertical-align: middle;">' + (cash_advance.number ? cash_advance.number : '') + ' (Untaxable)</td>';
                el += '<td><input type="text" class="form-control price_format" value="' + sisa + '" disabled /><input type="hidden" name="cash_advance[' + cash_advance.id + ']" class="calculate price_format" value="' + (-1 * sisa) + '"/></td>';
                el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
                el += "</tr>";

                $("#list_cash_advances").append(el);

                init_calculate();
                price_format();

                $("input[name^='cash_advance']").each(function(){
                    $(this).off();
                    $(this).on('change',function () {
                        calculate();
                    })
                });
            }
            if(sisa > 0){
                var el = "<tr class='cash_advance_payments_deduc'>";
                el += '<td style="vertical-align: middle;">' + (cash_advance.number ? cash_advance.number : '') + ' (Untaxable)</td>';
                el += '<td><input type="text" class="form-control price_format" value="' + sisa + '" disabled /><input type="hidden" name="ca_deduc[' + cash_advance.id + ']" class="calculate price_format" value="' + sisa + '"/></td>';
                el += '<td style="vertical-align: middle"><a href="javascript:void(0)" onclick="remove_item(this)"><i class="fa fa-trash text-danger" style="font-size: 15px;"></i></a></td>';
                el += "</tr>";

                $("#list_cash_advances_deduct").append(el);
                init_calculate();
                price_format();

                $("input[name^='ca_deduc']").each(function(){
                    $(this).off();
                    $(this).on('change',function () {
                        calculate();
                    })
                });
            }
        }

        function remove_item(el)
        {
            var obj = $(el).parent().parent();

            $(el).parent().parent().remove();

            calculate();
        }

        var payroll_marital_status = "";
        var payroll_jenis_kelamin  = "";

        function calculate(prorate = false)
        {
            var earnings    = [];
            var earning_items    = [];
            var deduction_items  = [];
            var deductions  = [];
            var loan_payments    = [];
            var business_trip_payments    = [];
            var business_trip_payments_deduc    = [];
            var cash_advance_payments    = [];
            var cash_advance_payments_deduc    = [];
            var salary      = $("input[name='salary']").val();
            var bonus       = $("input[name='bonus']").val() == "" ? 0 : $("input[name='bonus']").val();
            var thr         = $("input[name='thr']").val() == "" ? 0 : $("input[name='thr']").val();
            var overtime    = $("input[name='overtime']").val() == "" ? 0 : $("input[name='overtime']").val();

            // if(salary == "")
            // {
            //     return false;
            // }
            $("select[name='earning[]']").each(function() {
                earning_items.push($(this).val());
            });
            $("select[name='deduction[]']").each(function() {
                deduction_items.push($(this).val());
            });
            $("input[name='earning_nominal[]']").each(function(index, item){
                earnings.push($(this).val());
            });
            $("input[name='deduction_nominal[]']").each(function(index, item){
                deductions.push($(this).val());
            });
            $("input[name^='loan_payment']").each(function(index, item){
                loan_payments.push($(this).val());
            });
            $("input[name^='business_trip']").each(function(index, item){
                business_trip_payments.push($(this).val());
            });
            $("input[name^='training_deduc']").each(function(index, item){
                business_trip_payments_deduc.push($(this).val());
            });
            console.log(business_trip_payments)
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
            sum_earnings += $("input[name^='business_trip']").toArray().reduce(function(sum,element) {
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
            sum_deductions += $("input[name^='training_deduc']").toArray().reduce(function(sum,element) {
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
                    payroll_marital_status : payroll_marital_status,
                    payroll_jenis_kelamin : payroll_jenis_kelamin,
                    bonus : bonus,
                    thr : thr,
                    overtime : overtime,
                    user_id : $("input[name='user_id']").val(),

                    // start custom
                    bpjs_ketenagakerjaan_employee: $('.bpjs_ketenagakerjaan_employee').val(),
                    bpjs_kesehatan_employee: $('.bpjs_kesehatan_employee').val(),
                    bpjs_pensiun_employee: $('.bpjs_pensiun_employee').val(),
                    edit_bpjs_ketenagakerjaan_employee : prorate ? 0 : var_edit_bpjs_ketenagakerjaan_employee,
                    edit_bpjs_kesehatan_employee : prorate ? 0 : var_edit_bpjs_kesehatan_employee,
                    edit_edit_bpjs_pensiun_employee : prorate ? 0 : var_edit_bpjs_pensiun_employee,

                    bpjs_jkk_company: $('.bpjs_jkk_company').val(),
                    bpjs_jkm_company: $('.bpjs_jkm_company').val(),
                    bpjs_jht_company: $('.bpjs_jht_company').val(),
                    bpjs_pensiun_company: $('.bpjs_pensiun_company').val(),
                    bpjs_kesehatan_company: $('.bpjs_kesehatan_company').val(),
                    edit_bpjs_jkk_company : prorate ? 0 : var_edit_bpjs_jkk_company,
                    edit_bpjs_jkm_company : prorate ? 0 : var_edit_bpjs_jkm_company,
                    edit_bpjs_jht_company : prorate ? 0 : var_edit_bpjs_jht_company,
                    edit_bpjs_pensiun_company : prorate ? 0 : var_edit_bpjs_pensiun_company,
                    edit_bpjs_kesehatan_company : prorate ? 0 : var_edit_bpjs_kesehatan_company,
                    payroll_type : payroll_type,
                    // end custom

                    is_create : 1,
                    '_token' : $("meta[name='csrf-token']").attr('content')
                },
                success: function( data ) {
                    var thp  = data.thp.split(',').join('');
                    $('.td-thp').html(numberWithDot(thp));
                    //var thp = parseInt(data.thp.split('.').join(''));
                    //$('.td-thp').html(numberWithDot(data.thp));

                    @if(empty($month) || empty($year))
                        if(data.umr_label){
                            $('.td-umr').html(data.umr_label+' ('+numberWithDot(data.umr_value)+')');
                            $('.tr-umr').show();
                        } else {
                            $('.tr-umr').hide();
                        }
                    @endif

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


                    $("input[name='thp']").val(thp);
                    $("input[name='pph21']").val(data.monthly_income_tax);
                    //$('.bpjs_ketenagakerjaan_company').val(data.bpjs_ketenagakerjaan);
                    //$('.bpjs_kesehatan_company').val(data.bpjs_kesehatan);
                    //$('.bpjs_pensiun_company').val(data.bpjs_pensiun);
                    $('.bpjs_ketenagakerjaan_employee').val(data.bpjs_ketenagakerjaan2);
                    $('.bpjs_kesehatan_employee').val(data.bpjs_kesehatan2);
                    $('.bpjs_pensiun_employee').val(data.bpjs_pensiun2);
                    $('.burden_allow').val(data.burden_allow);
                    $("input[name='burden_allow']").val(data.burden_allow);
                    $('.yearly_income_tax').val(data.yearly_income_tax);
                    $("input[name='yearly_income_tax']").val(data.yearly_income_tax);



                    bonus = bonus != 0 ? bonus.split('.').join('') : 0;
                    thr = thr != 0 ? thr.split('.').join('') : 0;
                    overtime = overtime != 0 ? overtime.split('.').join('') : 0;

                    sum_earnings    = parseInt(sum_earnings) + parseInt(salary.split('.').join('')) + parseInt(bonus) + parseInt(thr) + parseInt(overtime);
                    sum_deductions  = parseInt(data.monthly_income_tax.split(',').join('')) + sum_deductions + parseInt(data.bpjs_ketenagakerjaan2.split(',').join('')) + parseInt(data.bpjs_kesehatan2.split(',').join('')) + parseInt(data.bpjstotalearning.split(',').join('')) + parseInt(data.bpjs_pensiun2.split(',').join(''))

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
                    is_calculating = false;
                    price_format();

                    matchHeight();
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
                        'name': request.term,'_token' : $("meta[name='csrf-token']").attr('content'){{(!empty($month) && !empty($year))?",month:$month,year:$year":""}}
                    },
                    success: function( data ) {

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

                        @php($attendance = get_payroll_cycle('attendance'))
                        if(data.data.attendance_cycle != null) {
                            $('.td-attendance_cycle').html(data.data.attendance_cycle.label + ' (' + data.data.attendance_cycle.start_date + ' - ' + data.data.attendance_cycle.end_date + ') ');
                            $('.tr-attendance_cycle').show();
                        } else if("{{ $attendance->start_date }}") {
                            $('.td-attendance_cycle').html("{{ $attendance->label ?: 'Default' }}" + ' (' + "{{ $attendance->start_date }}" + ' - ' + "{{ $attendance->end_date }}" + ') ');
                            $('.tr-attendance_cycle').show();
                        } else {
                            $('.tr-attendance_cycle').hide();
                        }
                        
                        @php($payroll = get_payroll_cycle('payroll'))
                        if(data.data.payroll_cycle != null) {
                            $('.td-payroll_cycle').html(data.data.payroll_cycle.label + ' (' + data.data.payroll_cycle.start_date + ' - ' + data.data.payroll_cycle.end_date + ') ');
                            $('.tr-payroll_cycle').show();
                        } else if("{{ $payroll->start_date }}") {
                            $('.td-payroll_cycle').html("{{ $payroll->label ?: 'Default' }}" + ' (' + "{{ $payroll->start_date }}" + ' - ' + "{{ $payroll->end_date }}" + ') ');
                            $('.tr-payroll_cycle').show();
                        } else {   
                            $('.tr-payroll_cycle').hide();
                        }

                        @if(!empty($month) && !empty($year))
                            $.ajax({
                                type: 'GET',
                                url: '{{ route('ajax.payroll.default') }}',
                                data: {'user_id' : ui.item.id, '_token' : $("meta[name='csrf-token']").attr('content')},
                                dataType: 'json',
                                success: function (data) {
                                    console.log(data)
                                    let prorate = data.prorate;
                                    let loan_payments = data.loan_payment;
                                    let business_trips = data.business_trip_payment;
                                    let cash_advances = data.cash_advance_payment
                                    data = data.payroll;
                                    if(data!=null && !jQuery.isEmptyObject(data)){
                                        $("input[name='salary']").val(Math.round(data.salary.split('.').join('')*prorate));
                                        $("input[name='bonus']").val(data.bonus);
                                        $("input[name='thr']").val(data.thr);
                                        $("input[name='overtime']").val(data.overtime);
                                        if(data.umr_label){
                                            $('.td-umr').html(data.umr_label+' ('+numberWithDot(data.umr_value)+')');
                                            $('.tr-umr').show();
                                        } else {
                                            $('.tr-umr').hide();
                                        }
                                        if(typeof data.payroll_type  !== "undefined") {
                                            $('#payroll_type').val(data.payroll_type);
                                            payroll_type = data.payroll_type;
                                        }

                                        $('.earnings').remove();
                                        $('.deductions').remove();
                                        $('.loan_payments').remove();
                                        $('.business_trip_payments').remove();
                                        $('.business_trip_payments_deduc').remove();
                                        $('.cash_advance_payments').remove();
                                        $('.cash_advance_payments_deduc').remove();
                                        if(data.payroll_earnings_employee!= null && data.payroll_earnings_employee.length > 0){

                                            earnings = data.payroll_earnings_employee;
                                            for(var i = 0; i < earnings.length; i++){
                                                add_income(earnings[i], prorate);
                                            }
                                        }
                                        if(data.payroll_deductions_employee!= null && data.payroll_deductions_employee.length > 0){

                                            deductions = data.payroll_deductions_employee;
                                            for(var i = 0; i < deductions.length; i++){
                                                add_deduction(deductions[i]);
                                            }
                                        }
                                        if(loan_payments != null && loan_payments.length > 0){

                                            for(var i = 0; i < loan_payments.length; i++){
                                                add_loan(loan_payments[i]);
                                            }
                                        }
                                        if(business_trips != null && business_trips.length > 0){
                                            for(var i = 0; i < business_trips.length; i++){
                                                add_business_trip(business_trips[i]);
                                            }
                                        }
                                        if(cash_advances != null && cash_advances.length > 0){
                                            for(var i = 0; i < cash_advances.length; i++){
                                                add_cash_advance(cash_advances[i]);
                                            }
                                        }
                                        $("input[name='bpjs_jkk_company']").val(data.bpjs_jkk_company);
                                        $("input[name='bpjs_jkm_company']").val(data.bpjs_jkm_company);
                                        $("input[name='bpjs_jht_company']").val(data.bpjs_jht_company);
                                        $("input[name='bpjs_pensiun_company']").val(data.bpjs_pensiun_company);
                                        $("input[name='bpjs_kesehatan_company']").val(data.bpjs_kesehatan_company);
                                        $("input[name='bpjs_ketenagakerjaan_employee']").val(data.bpjs_ketenagakerjaan_employee);
                                        $("input[name='bpjs_kesehatan_employee']").val(data.bpjs_kesehatan_employee);
                                        $("input[name='bpjs_pensiun_employee']").val(data.bpjs_pensiun_employee);

                                        var_edit_bpjs_ketenagakerjaan_employee       = 1;
                                        var_edit_bpjs_kesehatan_employee             = 1;
                                        var_edit_bpjs_pensiun_employee               = 1;
                                        var_edit_bpjs_jkk_company                    = 1;
                                        var_edit_bpjs_jkm_company                    = 1;
                                        var_edit_bpjs_jht_company                    = 1;
                                        var_edit_bpjs_pensiun_company                = 1;
                                        var_edit_bpjs_kesehatan_company              = 1;
                                        if(prorate == 1)
                                            calculate();
                                        else
                                            calculate(true);
                                        price_format();

                                        @if(!empty($month) && !empty($year))
                                            $.ajax({
                                                type: 'GET',
                                                url: '{{ route('ajax.payroll.attendance') }}',
                                                data: {'user_id' : ui.item.id,'month':"{{$month}}",'year':'{{$year}}', '_token' : $("meta[name='csrf-token']").attr('content')},
                                                dataType: 'json',
                                                success: function (data) {
                                                    console.log(data);
                                                    if(data !=null && !jQuery.isEmptyObject(data)) {
                                                        attendance_data = data;
                                                        $('.attendance').html(attendance_data.attendance.length);
                                                    }
                                                }
                                            });

                                            $.ajax({
                                                type: 'GET',
                                                url: '{{ route('ajax.payroll.overtime') }}',
                                                data: {'user_id' : ui.item.id,'month':"{{$month}}",'year':'{{$year}}', '_token' : $("meta[name='csrf-token']").attr('content')},
                                                dataType: 'json',
                                                success: function (data) {
                                                    console.log(data);
                                                    if(data !=null && !jQuery.isEmptyObject(data)) {
                                                        overtime_data = data;
                                                        total = 0
                                                        overtime_data.overtime.forEach(function(value) {
                                                            total += parseInt(value.payroll_calculate ? value.payroll_calculate : 0) + parseInt(value.meal_allowance ? value.meal_allowance : 0)
                                                        });
                                                        if (total) $("input[name='overtime']").val(money(total));
                                                        $('.overtime').html(overtime_data.overtime.length);
                                                    }
                                                }
                                            });
                                        @endif
                                    }
                                }
                            });
                        @else
                            calculate();
                        @endif
                    }
                });
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });
        @if(!empty($month) && !empty($year))
            function getAttendance() {
                var user_id = $("input[name='user_id']").val();
                if(user_id==''){
                    alert('Select user first');
                }
                else{

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
                            clock_in += ' (ND)';
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
                    $('#modal-attendance').modal('show');
                }
            }
            function getOvertime() {
                var user_id = $("input[name='user_id']").val();
                if(user_id==''){
                    alert('Select user first');
                }
                else{

                    $('#modal-overtime #modal_title').html("User Overtime "+$('.autocomplete-karyawan').val());
                    var row="";
                    var total=0;
                    for(var i = 0; i < overtime_data.overtime.length; i++){

                        var date        = overtime_data.overtime[i].tanggal!=null?overtime_data.overtime[i].tanggal:'';
                        var approval    = overtime_data.overtime[i].updated_at!=null?overtime_data.overtime[i].updated_at:'';
                        var earning     = overtime_data.overtime[i].payroll_calculate!=null?overtime_data.overtime[i].payroll_calculate:0;
                        var meal        = overtime_data.overtime[i].meal_allowance!=null?overtime_data.overtime[i].meal_allowance:0;
                        var rowTotal    = parseInt(earning) + parseInt(meal);

                        row += '<tr>'+
                            '<td>'+(i+1)+'</td>'+
                            '<td>'+date+'</td>'+
                            '<td>'+approval+'</td>'+
                            '<td>'+money(earning)+'</td>'+
                            '<td>'+money(meal)+'</td>'+
                            '<td>'+money(rowTotal)+'</td>'+
                            '</tr>';
                    }
                    $('#data-overtime').html(row);
                    $('#modal-overtime #start_date').html(overtime_data.start_date);
                    $('#modal-overtime #end_date').html(overtime_data.end_date);
                    $('#modal-overtime').modal('show');
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
