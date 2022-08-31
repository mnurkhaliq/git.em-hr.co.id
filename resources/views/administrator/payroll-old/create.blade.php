@extends('layouts.administrator')

@section('title', 'Payroll')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Employee Payroll </h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-r-10 pull-right" onclick="form_submit()"><i class="fa fa-save"></i> Save Data </button>
            </div>
        </div>
        <div class="row">
            <form class="form-horizontal" id="form-payroll" autocomplete="off" enctype="multipart/form-data" action="{{ route('administrator.payroll.store') }}" method="POST">
               <div class="col-md-4 p-l-0">
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
                        </div>
                        <div class="clearfix"></div>
                        <table class="table table-stripped m-t-20">
                            <tr>
                                <th style="width: 50%;">Email</th>
                                <th>:</th>
                                <th style="width: 50%;" class="td-email"> </th>
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
                        </table>
                    </div>
               </div>
               <div class="col-md-4 p-l-0" style="max-height: 460px; overflow-x: scroll;">
                    <div class="white-box p-t-10 m-b-0">
                        <h3>Earning</h3>
                        <table class="table table-stripped" id="list_earnings">
                            <thead>
                                <tr>
                                    <td style="vertical-align: middle;">Salary</td>
                                    <td><input type="text" class="form-control price_format calculate" name="salary" placeholder="Rp. " /></td> 
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;">Bonus / THR</td>
                                    <td><input type="text" class="form-control price_format calculate" name="bonus" placeholder="Rp. " /></td> 
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
                                    <th class="td-pph21" colspan="2"></th>
                                </tr>
                                <!--/end start custome-->
                                <tr>
                                    <th>Total Earnings </th>
                                    <th class="total_earnings"></th>
                                </tr>
                            </tfoot>
                        </table>
                        <a href="javascript:void(0)" class="btn btn-info btn-xs pull-right" onclick="add_income()"><i class="fa fa-plus"></i></a>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-4 p-l-0 p-r-0" style="overflow-x: scroll;max-height: 460px; ">
                    <div class="white-box p-t-10 m-b-0" style="min-height: 440px;">
                        <h3>Deduction</h3>
                        <table class="table table-stripped" id="list_deductions">
                            <thead>
                                <input type="hidden" name="burden_allow"  class="form-control burden_allow" />
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
                            <tfoot>
                                <tr>
                                    <th>Monthly Income Tax / PPh21</th>
                                    <th class="td-pph21"></th>
                                </tr>
                                <tr>
                                    <th>Total Deduction</th>
                                    <th class="total_deductions"></th>
                                </tr>
                            </tfoot>
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


            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
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

    function form_submit()
    {
        if($("input[name='user_id']").val() == "" || $("input[name='salary']").val() == "")
        {
            _alert("@lang('payroll.message-employee-cannot-empty')");
            return false;
        }

        $("#form-payroll").submit();
    }

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

            $(this).on('input', function(){
                calculate();
            });
        });
    }

    init_calculate();

    var json_earnings = [];
    @foreach(get_earnings() as $item)
        json_earnings[{{ $item->id }}] = ({'id' : {{ $item->id }}, 'title' : '{{ $item->title }}'});
    @endforeach

    var json_deductions = [];
    @foreach(get_deductions() as $item)
        json_deductions[{{ $item->id }}] = ({'id' : {{ $item->id }}, 'title' : '{{ $item->title }}'});
    @endforeach

    function add_income()
    {
        $("select[name='earning[]']").each(function(k,v){
            //json_earnings.splice($(v).val(),1 );
        });

        var el = "<tr>";
            el += '<td>';

            el += '<select class="form-control" name="earning[]">';
            $(json_earnings).each(function(k,v){
                if(v !== null && typeof v === 'object')
                {
                    el += '<option value="'+ v.id +'" data-title="'+ v.title +'">'+ v.title +'</option>';
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
        $("select[name='earning[]']").each(function(k,v){
            //json_earnings.splice($(v).val(),1 );
        });

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

    function remove_item(el)
    {
        var obj = $(el).parent().parent();
     
        $(el).parent().parent().remove();

        calculate();
    }

    var marital_status = ""; 

    function calculate()
    {
        var earnings    = [];
        var deductions  = [];
        var salary      = $("input[name='salary']").val();
        var bonus       = $("input[name='bonus']").val() == "" ? 0 : $("input[name='bonus']").val();

        if(salary == "")
        {
            return false;
        }

        $("input[name='earning_nominal[]']").each(function(index, item){
            earnings.push($(this).val());
        });
        $("input[name='deduction_nominal[]']").each(function(index, item){
            deductions.push($(this).val());
        });

        var sum_earnings = $("input[name='earning_nominal[]']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);

        var sum_deductions = $("input[name='deduction_nominal[]']").toArray().reduce(function(sum,element) {
                            element = element.value;
                            return sum + Number(element.split('.').join(''));
                         }, 0);

        $.ajax({
            url: "{{ route('ajax.get-calculate-payroll') }}",
            method : 'POST',
            data: {
                earnings, 
                deductions,
                salary : salary,
                marital_status : marital_status,
                bonus : bonus,

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
                // end custom
                
                is_create : 1,
                '_token' : $("meta[name='csrf-token']").attr('content')
            },
            success: function( data ) {
                
                var thp = parseInt(data.thp.split('.').join(''));

                $('.td-thp').html(numberWithDot(data.thp));
                $('.td-pph21').html(data.monthly_income_tax);
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
                

                $("input[name='thp']").val(parseInt(data.thp));
                $("input[name='pph21']").val(data.monthly_income_tax);
                //$('.bpjs_ketenagakerjaan_company').val(data.bpjs_ketenagakerjaan);
                //$('.bpjs_kesehatan_company').val(data.bpjs_kesehatan);
                //$('.bpjs_pensiun_company').val(data.bpjs_pensiun);
                $('.bpjs_ketenagakerjaan_employee').val(data.bpjs_ketenagakerjaan2);
                $('.bpjs_kesehatan_employee').val(data.bpjs_kesehatan2);
                $('.bpjs_pensiun_employee').val(data.bpjs_pensiun2);
                 $('.burden_allow').val(data.burden_allow);
                $("input[name='burden_allow']").val(data.burden_allow);
                

                bonus = bonus != 0 ? bonus.split('.').join('') : 0;

                sum_earnings    = parseInt(sum_earnings) + parseInt(salary.split('.').join('')) + parseInt(bonus);
                sum_deductions  = parseInt(data.monthly_income_tax.split(',').join('')) + sum_deductions + parseInt(data.bpjs_ketenagakerjaan2.split(',').join('')) + parseInt(data.bpjs_kesehatan2.split(',').join('')) + parseInt(data.bpjstotalearning.split(',').join('')) + parseInt(data.bpjs_pensiun2.split(',').join(''))

                // start custom
                sum_earnings    = parseInt(sum_earnings) + parseInt(data.monthly_income_tax.split(',').join(''))+ parseInt(data.bpjstotalearning.split(',').join(''));
                // end custom
                
                $("input[name='total_earnings']").val(sum_earnings);
                $("input[name='total_deductions']").val(sum_deductions);
                $(".total_earnings").html(numberWithDot(sum_earnings));
                $(".total_deductions").html(numberWithDot(sum_deductions));
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
                    marital_status = data.data.marital_status;  

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
</script>
@endsection
@endsection