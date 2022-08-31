@extends('layouts.administrator')

@section('title', 'Payroll Monthly')

@section('content')
@php($bulanArray = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'])
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit; ">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <h4 class="page-title pull-left m-r-10">Payroll @if(!empty(\Session::get('m-year')) && !empty(\Session::get('m-month'))) {{$bulanArray[\Session::get('m-month')]." ".\Session::get('m-year') }} @else Default @endif</h4>
                <form method="POST" action="{{ route('administrator.payroll-monthly.index') }}" id="filter-form" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="reset" value="0">
                    <div class="pull-right" style="padding-left:0;">
                        <button type="button" id="filter_view" class="btn btn-default btn-sm btn-outline"> <i class="fa fa-search-plus"></i></button>
                        <div class="btn-group m-r-5">
                            <button aria-expanded="false" data-toggle="dropdown" class="btn btn-sm btn-info dropdown-toggle waves-effect waves-light" type="button">Action
                                <i class="fa fa-gear"></i>
                            </button>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="javascript:void(0)" onclick="reset_filter()"> <i class="fa fa-refresh"></i> Reset Filter</a></li>
                                <li><a href="{{ route('administrator.payroll-monthly.create') }}"> <i class="fa fa-plus"></i> Create</a></li>
                                <li><a href="#" onclick="submit_filter_download()"><i class="fa fa-download"></i> Export Payroll</a></li>
                                <li><a id="add-import-karyawan"> <i class="fa fa-file"></i> Import</a></li>
                                <li><a href="javascript:void(0)" onclick="submit_sendpayslip()" title="Send Payslip"><i class="fa fa-send-o"></i> Send Payslip</a></li>
                                @if(get_setting('button_lock'))
                                    <li><a href="javascript:void(0)" onclick="submit_lock()" title="Lock Payroll"><i class="fa fa-lock"></i> Lock Payroll</a></li>
                                @endif
                                <li><a href="javascript:void(0)" onclick="submit_downloadpayslip()" title="Download Payslip"><i class="fa fa-download"></i> Export Payslip</a></li>
                                <li><a href="#" onclick="submit_spt()" title="Download E-SPT"><i class="fa fa-download"></i> Export E-SPT</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                <i class="fa fa-eye"></i>
                            </a>
                            <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                <li><a class="toggle-vis" data-column="2" style="color:blue;">Number</a></li> 
                                <li><a class="toggle-vis" data-column="3" style="color:blue;">NIK</a></li> 
                                <li><a class="toggle-vis" data-column="4" style="color:blue;">Name</a></li>
                                <li><a class="toggle-vis" data-column="5" style="color:blue;">Earning</a></li>
                                <li><a class="toggle-vis" data-column="6" style="color:blue;">Deductions</a></li>
                                <li><a class="toggle-vis" data-column="7" style="color:blue;">Take Home Pay</a></li> 
                                <li><a class="toggle-vis" data-column="8" style="color:blue;">Type</a></li>
                                <li><a class="toggle-vis" data-column="9" style="color:blue;">Action</a></li>
                            </ul>
                        </div>
                    </div>
                    {{--<div class="col-md-1 pull-right" style="padding-left:0;">--}}
                        {{--<div class="form-group m-b-0">--}}
                            {{--<select class="form-control form-control-line" name="is_calculate">--}}
                                {{--<option value="">- Status -</option>--}}
                                {{--<option value="0" {{ (\Session::get('is_calculate') == '0') ? 'selected' : '' }}>No Calculated</option>--}}
                                {{--<option value="1" {{ (\Session::get('is_calculate') == '1') ? 'selected' : '' }}>Calculated</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="col-md-1 pull-right" style="padding: 0px;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="employee_resign">
                                <option value="">- Active/Resign - </option>
                                <option {{ (\Session::get('m-employee_resign') == 'Active') ? 'selected' : '' }}>Active</option>
                                <option {{ (\Session::get('m-employee_resign') == 'Resign') ? 'selected' : '' }}>Resign</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 pull-right" style="padding-left:0;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="payroll_type" onclick="filter_change()">
                                <option value="">- Type -</option>
                                <option value="NET" {{ (\Session::get('m-payroll_type') == 'NET') ? 'selected' : '' }}>NET</option>
                                <option value="GROSS" {{ (\Session::get('m-payroll_type') == 'GROSS') ? 'selected' : '' }}>GROSS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 pull-right" style="padding-left:0;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="division_id" onclick="filter_change()">
                                <option value=""> - Division - </option>
                                @foreach($division as $item)
                                <option value="{{ $item->id }}" {{ $item->id== \Session::get('m-division_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 pull-right" style="padding-left:0;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="position_id" onclick="filter_change()">
                                <option value=""> - Position - </option>
                                @foreach($position as $item)
                                <option value="{{ $item->id }}" {{ $item->id== \Session::get('m-position_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 pull-right" style="padding-left:0;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="employee_status" onclick="filter_change()">
                                <option value="">- Employee Status - </option>
                                <option {{ (\Session::get('m-employee_status') == 'Permanent') ? 'selected' : '' }}>Permanent</option>
                                <option {{ (\Session::get('m-employee_status') == 'Contract') ? 'selected' : '' }}>Contract</option>
                                <option {{ (\Session::get('m-employee_status') == 'Internship') ? 'selected' : '' }}>Internship</option>
                                <option {{ (\Session::get('m-employee_status') == 'Outsource') ? 'selected' : '' }}>Outsource</option>
                                <option {{ (\Session::get('m-employee_status') == 'Freelance') ? 'selected' : '' }}>Freelance</option>
                                <option {{ (\Session::get('m-employee_status') == 'Consultant') ? 'selected' : '' }}>Consultant</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-1 pull-right" style="padding-left:0;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="month" onclick="filter_change()">
                                @foreach(month_name() as $key => $item)
                                <option value="{{ $key }}" {{ (\Session::get('m-month') == $key) ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1 pull-right" style="padding-left:0;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="year" onclick="filter_change()">
                                @for($year=2018; $year <= ((Int)date('Y') + 5); $year++))
                                <option {{ (\Session::get('m-year') == $year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group m-b-0">
                            <input type="text" class="form-control form-control-line" name="name" value="{{ \Session::get('m-name') }}" placeholder="Nik / Name" onclick="filter_change()">
                        </div>
                    </div>
                    <input type="hidden" name="action" value="view">
                    <div class="clearfix"></div>
                    <div id="filter-form-user"></div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                 <form method="POST" id="form_table_payroll" action="{{ route('administrator.payroll-monthly.index') }}">
                    <input type="hidden" name="action" value="bukti-potong">
                    {{ csrf_field() }}
                    <div class="table-responsive">
                        <table id="data_table_no_search_with_page_length_check" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="10" style="text-align: left;padding-left:9px;"><input type="checkbox" title="Check All" name="check_all" /></th>
                                    <th width="70" class="text-center">NO</th>
                                    <th>NUMBER</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>EARNINGS</th>
                                    <th>DEDUCTIONS</th>
                                    <th>TAKE HOME PAY</th>
                                    <th>TYPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php ($i = 1)
                            @php ($new=false)
                            @if(isset($data))
                                @foreach($data as $no => $item)
                                    @if(isset($item->user))
                                        {{--@if(\Session::get('m-month') and \Session::get('m-year'))--}}
                                                {{--@php($history = get_payroll_history($item->user_id, \Session::get('m-month'), \Session::get('m-year') ))--}}
                                                {{--@if($history)--}}
                                                    {{--@php($item = $history)--}}
                                                    {{--@php($item->is_calculate = 1)--}}
                                                {{--@endif--}}
                                        {{--@endif--}}
                                        <tr>
                                            <td><input type="checkbox" name="payroll_id[]" class="check_user" data-user_id="{{ $item->user_id }}" value="{{ $item->id }}"></td>
                                            <td><center>{{ $i }}</center></td>
                                            <td>{{ $item->number }}</td>
                                            <td>{{ $item->user->nik }}</td>
                                            <td title="{{$item->user->name}}">{{ str_limit($item->user->name, $limit = 20, $end = '...') }}</td>
                                            <td>{{ format_idr($item->total_earnings) }}</td>
                                            <td>{{ format_idr($item->total_deduction) }}</td>
                                            <td>{{ format_idr($item->thp) }}</td>
                                            <td class="">
                                                {{--@if(\Session::get('m-month') and \Session::get('m-year'))--}}
                                                    {{--@php($history_ = cek_payroll_user_id($item->user_id, \Session::get('m-month'), \Session::get('m-year') ))--}}
                                                    {{--@if(!$history_)--}}
                                                        {{--<label class="btn btn-warning btn-xs btn-circle" title="Not Calculate"><i class="fa fa-close"></i></label>--}}
                                                    {{--@elseif($history_)--}}
                                                        {{--<label class="btn btn-success btn-xs  btn-circle"  title="Calculated"><i class="fa fa-check"></i> </label>--}}
                                                    {{--@endif--}}
                                                {{--@else--}}
                                                    {{--@if($item->is_calculate == 0)--}}
                                                        {{--<label class="btn btn-warning btn-xs btn-circle" title="Not Calculate"><i class="fa fa-close"></i></label>--}}
                                                    {{--@else--}}
                                                        {{--<label class="btn btn-success btn-xs  btn-circle"  title="Calculated"><i class="fa fa-check"></i> </label>--}}
                                                    {{--@endif--}}
                                                {{--@endif--}}
                                                <b style="color: {{$item->payroll_type=='GROSS'?'red':'green'}}">{{$item->payroll_type}}</b>
                                            </td>
                                            <td>
                                                @if(\Session::get('m-month') and \Session::get('m-year'))
                                                    @php($history = get_payroll_history($item->user_id, \Session::get('m-month'), \Session::get('m-year') ))
                                                     <a href="{{ route('administrator.payroll-monthly.detail-history', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> detail</a>
                                                    @if(!$item->is_lock)
                                                        <a href="{{ route('administrator.payroll-monthly.delete-history', $item->id) }}" onclick="return confirm('You would not be able to restore after, Delete this data?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> delete</a>
                                                    @endif
                                                @else
                                                    <a href="{{ route('administrator.payroll-monthly.detail', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> detail</a>
                                                @endif

                                                {{--@if(\Session::get('m-month') and \Session::get('m-year'))--}}
                                                    {{--@php($history_ = cek_payroll_user_id($item->user_id, \Session::get('m-month'), \Session::get('m-year') ))--}}
                                                    {{--@if(!$history_)--}}
                                                        {{--<a href="{{ route('administrator.payroll-monthly.create-by-payroll-id', $item->id) }}?date={{ \Session::get('m-year') }}-{{ \Session::get('m-month') }}-01" class="btn btn-warning btn-xs"><i class="fa fa-plus"></i> Create Payroll </a>--}}
                                                        {{--@php($new = true)--}}
                                                        {{--@php($item->is_lock = 0)--}}
                                                    {{--@endif--}}
                                                {{--@endif--}}
                                                @if($item->is_lock==1)
                                                    <a href="" class="pull-right text-danger" title="Lock Payroll" style="font-size: 25px;"><i class="fa fa-lock"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @php ($i ++)
                                    @endIf
                                @endforeach
                            @endIf
                            </tbody>
                        </table>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>

<!-- modal content education  -->
<div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Import Data Payroll @if(!empty(\Session::get('m-year')) && !empty(\Session::get('m-month'))) {{$bulanArray[\Session::get('m-month')]." ".\Session::get('m-year') }} @else Default @endif</h4> </div>
                    <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.payroll-monthly.temp-import') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">File (xls)</label>
                            <div class="col-md-9">
                                <input type="file" name="file" class="form-control" />
                            </div>
                        </div>
                        <a href="{{ route('administrator.payroll-monthly.download') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <label class="btn btn-info btn-sm" id="btn_import">Import</label>
                    </div>
                </form>
                <div style="text-align: center;display: none;" class="div-proses-upload">
                    <h3>Please wait for the upload process!</h3>
                    <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
                </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<style type="text/css" media="screen">
    .ui-datepicker-year {
        width: 97% !important;
        height: 28px !important;
        border: 1px solid #e9e9e9;
    }
</style>
<script type="text/javascript">
    function submit_lock()
    {
        $("#filter-form input[name='action']").val('lock');
        onChecked();
    }

    function submit_sendpayslip()
    {
        $("#filter-form input[name='action']").val('submitpayslip');
        onChecked()
    }

    function submit_downloadpayslip()
    {
        $("#filter-form input[name='action']").val('downloadpayslip');
        onChecked()
    }

    function submit_filter_download() {
        $("#filter-form input[name='action']").val('download');
        onChecked();
    }


    function submit_filter_bank_download() {
        $("#filter-form input[name='action']").val('downloadBank');
        onChecked();
    }

    function submit_bukti_potong()
    {
        $("#filter-form input[name='action']").val('bukti-potong');
        onChecked();
    }

    function submit_spt()
    {
        $("#filter-form input[name='action']").val('spt');
        onChecked();
    }

    $("#filter_view").click(function(){
        $("#filter-form input[name='action']").val('view');
        $("#filter-form").submit();
    });

    function reset_filter()
    {
        $("#filter-form input.form-control, #filter-form select").val("");
        $("#filter-form input[name='action']").val('');
        $("#filter-form select[name='month']").val({{ date('n') }});
        $("#filter-form select[name='year']").val({{ date('Y') }});
        $("input[name='reset']").val(1);
        $("#filter-form").submit();
    }

    function filter_change() {
        $("#filter-form input[name='action']").val('view');
    }

    $('#dpYears').datepicker( {
        //yearRange: "c-100:c",
        changeMonth: false,
        changeYear: true,
        showButtonPanel: true,
        closeText:'Select',
        currentText: 'This year',
        onClose: function(dateText, inst) {
          var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
          $(this).val($.datepicker.formatDate("yy", new Date(year, 0, 1)));
        },
        beforeShow: function(input, inst){
          if ($(this).val()!='')
          {
            var year = $(this).val();
            //$(this).datepicker('option','defaultDate', new Date(tmpyear, 0, 1));
            $(this).datepicker( {
                changeMonth: false,
                changeYear: true,
                showButtonPanel: true,
                closeText:'Select',
                currentText: 'This year',
                setDate: new Date(year, 0, 1)
            });
          }
        }
      }).focus(function () {
        $(".ui-datepicker-month").hide();
        $(".ui-datepicker-calendar").hide();
        $(".ui-datepicker-current").hide();
        $(".ui-datepicker-prev").hide();
        $(".ui-datepicker-next").hide();
        $("#ui-datepicker-div").position({
          my: "left top",
          at: "left bottom",
          of: $(this)
        });
      }).attr("readonly", false);


    $("input[name='check_all']").click(function () {
        $('input:checkbox').prop('checked', this.checked);
    });

    $('.check_user').click(function () {
        if ($('.check_user:visible:checked').length == $('.check_user:visible').length && $('.check_user:visible').length)
            $("input[name='check_all']").prop('checked', true)
        else
            $("input[name='check_all']").prop('checked', false)
    });

    function onChecked() {
        var count   = $("input[name='payroll_id[]']").filter(':checked');
        var html    = '';

        $(count).each(function(k,v){
            html += '<input type="hidden" name="user_id[]" value="'+ $(v).data('user_id') +'" />';
            html += '<input type="hidden" name="payroll_id[]" value="'+ $(v).val() +'" />';
        });

        $("#filter-form-user").html(html);
        $("#filter-form").submit();
    }

    $("#btn_import").click(function(){

        if($("input[type='file']").val() == "")
        {
            bootbox.alert('File can not be empty');
            return false;
        }

        $("#form-upload").submit();
        $("#form-upload").hide();
        $('.div-proses-upload').show();

    });

    $("#add-import-karyawan").click(function(){
        if($("select[type='year']").val() == ""){
            alert("a");
        }
        $("#modal_import").modal("show");
        $('.div-proses-upload').hide();
        $("#form-upload").show();
    })
</script>

<script type="text/javascript">
    $("#calculate").click(function(){

        bootbox.confirm('Calculate Payroll ?', function(res){

            bootbox.dialog({closeButton: false, message: '<div class="text-center"><h4><i class="fa fa-spin fa-spinner"></i> Please Wait, Calculate Payroll...</h4></div>' })

            setTimeout(function(){
                window.location = '{{ route('administrator.payroll-monthly.calculate') }}';
            }, 2000);
        });

    });

    $( "#from, #to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function( selectedDate ) {
            if(this.id == 'from'){
              var dateMin = $('#from').datepicker("getDate");
              var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate()); // Min Date = Selected + 1d
              var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
              $('#to').datepicker("option","minDate",rMin);
              $('#to').datepicker("option","maxDate",rMax);
            }
        }
    });
    $('a.toggle-vis').on('click', function (e) {
        e.preventDefault();
        e.target.style.color == 'blue' ? $(this).addClass('change-toggle') : $(this).removeClass('change-toggle');
        e.target.style.color = e.target.style.color == 'blue' ? 'red' : 'blue';
            // console.log($(this).attr('href'))
            // $($(this).attr('href')).click(function(e) {
            //     e.stopPropagation();
            // })
            // $($(this).attr('href')).prop("checked", !$($(this).attr('href')).prop("checked"));
            // if((e.target).tagName == 'INPUT') return true; 
            
            // Get the column API object
        var column = data_table_no_search_with_page_length_check.column($(this).attr('data-column'));
    
            // Toggle the visibility
        column.visible(!column.visible());
    });
</script>
@endsection
@endsection