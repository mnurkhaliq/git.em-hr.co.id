@extends('layouts.karyawan')

@section('title', 'Overtime Sheet')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Form Overtime Sheet</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Overtime Sheet</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.overtime-custom.prosesclaim') }}" id="form-overtime" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Data Overtime Sheet</h3>
                        <br />
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
                        <?php
                        $readonly = ''; 
                        if($data->status_claim >= 1)
                        {
                            $readonly = ' readonly="true"'; 
                        }
                        else{
                            $readonly = 'required';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-md-12">NIK / Employee Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $data->user->nik .' - '. $data->user->name  }}" readonly="true" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Position</label>
                            <div class="col-md-6">
                                <input type="text" readonly="true" class="form-control jabatan" value="{{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}">
                            </div>
                        </div>
                        <hr />
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                                <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%" border="1">
                                <thead>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="3" style="text-align: center;">PRE</th>
                                        <th colspan="3" style="text-align: center;">PRE APPROVED</th>
                                        <th colspan="2" style="text-align: center;">ATTENDANCE</th>
                                        <th colspan="3" style="text-align: center;">CLAIM</th>
                                        <th colspan="3" style="text-align: center;">CLAIM APPROVED</th>
                                        <th>OT APPROVED</th>
                                        <th>EARNING</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>NO</th>
                                        <th>DATE</th>
                                        <th>DESCRIPTION</th>
                                        <th>START</th>
                                        <th>END</th>
                                        <th>OT (HOURS)</th>
                                        <th>START</th>
                                        <th>END</th>
                                        <th>OT (HOURS)</th>
                                        <th>IN </th>
                                        <th>OUT </th>
                                        <th>START</th>
                                        <th>END </th>
                                        <th>OT (HOURS)</th>
                                        <th>START</th>
                                        <th>END</th>
                                        <th>OT(HOURS)</th>
                                        <th>CALCULATED</th>
                                        <th>MEAL ALLOWANCE</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="table-content-lembur">
                                    @foreach($data->overtime_form as $no => $item)
                                    <tr>
                                        <input type="hidden" name="id_overtime_form[]" class="form-control"  value="{{ $item->id }}" readonly="true">
                                        <td>{{ $no+1 }}</td>
                                        <td><input type="text" style="width: 125px" readonly="true" value="{{ $item->tanggal }}" name="tanggal[]" class="form-control"></td>
                                        <td><input type="text" style="width: 150px" readonly="true" name="description[]" class="form-control" value="{{ $item->description }}"></td>
                                        <td><input type="text" style="width: 70px" readonly="true" name="awal[]" class="form-control" value="{{ $item->awal }}" /></td>
                                        <td><input type="text" style="width: 70px" readonly="true" name="akhir[]" class="form-control" value="{{ $item->akhir }}" /></td>
                                        <td><input type="text" readonly="true" name="total_lembur[]" class="form-control" value="{{ $item->total_lembur }}" /></td>
                                        <td><input type="text" style="width: 70px" readonly="true" name="pre_awal_approved[]" class="form-control" value="{{ $item->pre_awal_approved }}" /></td>
                                        <td><input type="text" style="width: 70px" readonly="true" name="pre_akhir_approved[]" class="form-control" value="{{ $item->pre_akhir_approved }}" /></td>
                                        <td><input type="text" readonly="true" name="pre_total_approved[]" class="form-control" value="{{ $item->pre_total_approved }}" /></td>
                                        @php($in = overtime_absensi($item->tanggal,$data->user_id))
                                        <td><input type="text" style="width: 70px" readonly="true" class="form-control" value="{{ isset($in) ? $in->clock_in :''}}" /></td>
                                        <td><input type="text" style="width: 70px" readonly="true" class="form-control" value="{{ isset($in) ? $in->clock_out :''}}" /></td>
                                        <td><input type="text" name="awal_claim[]" style="width: 70px"  {{$readonly}} class="form-control time-picker awal_claim input" value="{{ $item->awal_claim }}" /></td>
                                        <td><input type="text" name="akhir_claim[]" style="width: 70px" {{$readonly}}  class="form-control time-picker akhir_claim input" value="{{ $item->akhir_claim }}" /></td>
                                        <td><input type="text" id="total_lembur_claim[]" name="total_lembur_claim[]" class="form-control total_lembur_claim" readonly="true" value="{{ $item->total_lembur_claim }}" /></td>
                                        <td><input type="text" style="width: 70px" readonly="true" name="awal_approved[]" class="form-control" value="{{ $item->awal_approved }}" /></td>
                                        <td><input type="text" style="width: 70px" readonly="true" name="akhir_approved[]" class="form-control" value="{{ $item->akhir_approved }}" /></td>
                                        <td><input type="text" readonly="true" name="total_lembur_approved[]" class="form-control" value="{{ $item->total_lembur_approved }}" /></td>
                                        <td><input type="text" readonly="true" name="overtime_calculate[]" class="form-control" value="{{ $item->overtime_calculate }}" /></td>
                                        <td><input type="text" readonly="true" name="meal_allowance[]" class="money form-control" value="{{ $item->meal_allowance }}" /></td>
                                        <td>
                                            @if($data->status_claim < 1 or $data->status_claim == "")
                                            <a class="btn btn-danger btn-xs" onclick="cancel_(this)"><i class="fa fa-trash"></i>Cancel</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                             @if($data->status_claim < 1 or $data->status_claim == "")
                            <a class="btn btn-info btn-xs pull-right" id="add"><i class="fa fa-plus"></i> Add</a>
                            @endif
                        </div>
                        
                        <div class="clearfix"></div>
                        <br />
                        @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-12">Note Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note_claim }}">
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden" name="status" value="0" />
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12" style="padding-left: 0;">
                        <a href="{{ route('karyawan.overtime-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>

                        @if($data->status_claim < 1 or $data->status_claim == "")
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Send Overtime Claim</a>
                        @endif
                        <br style="clear: both;" />
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>    
            </form>                    
        </div>
    </div>
    @include('layouts.footer')
</div>
@section('footer-script')
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript">
    function cancel_(el)
    {
        var result = "00:00";
        $(el).parent().parent().find('.awal_claim').val(result);
        $(el).parent().parent().find('.akhir_claim').val(result);
        $(el).parent().parent().find('.total_lembur_claim').val(result);
        //$(el).parent().parent().find('.awal_claim').setAttribute('value','My default value');
    }
    function hapus_(el)
    {
        $(el).parent().parent().remove();
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.money').mask('000.000.000', {reverse: true});
    });

    $("#btn_submit").click(function(){
        var total = $('.table-content-lembur tr').length;
        if(total == 0) return false;
        var validation = validate_form();
        if(!validation)
        {
            bootbox.alert('Form must be completed!');
            return false;
        }
        bootbox.confirm('Do you want to submit Overtime Claim?', function(result){
            if(result)
            {
                $('form.form-horizontal').submit();
            }
        });
    });

    function validate_form()
    {
        var validate = true;

        $('.input').each(function(){

            if($(this).val() == "")
            {
                $(this).parent().addClass('has-error');
                console.log('cek');
                validate = false;
            }
        });
        return validate;
    }
    
    sum_total_claim();

    // Clock pickers
    $('.time-picker').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });

    function sum_total_claim()
    {
        $("input.awal_claim, input.akhir_claim").each(function(){

            $(this).on('change', function(){

                var start = $(this).parent().parent().find('.awal_claim').val(),
                    end = $(this).parent().parent().find('.akhir_claim').val();

                if(start =="" || end == "") { return false; } 
                
                start = start.split(":");
                end = end.split(":");
                var startDate = new Date(0, 0, 0, start[0], start[1], 0);
                var endDate = new Date(0, 0, 0, end[0], end[1], 0);
                var diff = endDate.getTime() - startDate.getTime();
                var hours = Math.floor(diff / 1000 / 60 / 60);
                diff -= hours * 1000 * 60 * 60;
                var minutes = Math.floor(diff / 1000 / 60);

                // If using time pickers with 24 hours format, add the below line get exact hours
                if (hours < 0)
                    hours = hours + 24;

                var result =  (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;

                $(this).parent().parent().find('.total_lembur_claim').val(result);
           
            });
        });
    }

    function reInitDate() {
        var disabledDates = [];
        $("input[name='tanggal[]']").each(function(){
            if($(this).val() != "")
            {
                disabledDates.push($(this).val());
            }
        });
            
        jQuery('.scopeDatePicker').datepicker("destroy").datepicker({
            dateFormat: 'yy-mm-dd',
            beforeShowDay: function(date){
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [ disabledDates.indexOf(string) == -1 ]
            }
        });
    }

    function handlerDate() {
        reInitDate()
        $("input[name='tanggal[]']:last").change(function(){
            reInitDate()

            if($(this).val() != "")
            {
                var el_in=$(this);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.get-in-out-overtime-custom') }}',
                    data: {'date' : $(this).val() ,'user_id':{{\Auth::user()->id}},'_token' : $("meta[name='csrf-token']").attr('content') },
                    dataType: 'json',
                    success: function (data) { 
                            el_in.parent().parent().find('.clock_in').val(data.data.clock_in);
                            el_in.parent().parent().find('.clock_out').val(data.data.clock_out);
                    }
                });
            }
        })
    }

    $("#add").click(function(){
        var no = $('.table-content-lembur tr').length;
        var html = '<tr>';
            html += '<td>'+ (no+1) +'</td>';
            html += '<input type="hidden" name="id_overtime_form[]" class="form-control"  value="" readonly="true">';
            html += '<td><input type="text" name="tanggal[]" class="form-control scopeDatePicker date_overtime"></td>';
            html += '<td><input type="text" name="description[]" class="form-control description"></td>';
            html += '<td><input type="text" name="awal[]" readonly = true class="form-control awal" /></td>';
            html += '<td><input type="text" name="akhir[]" readonly = true class="form-control akhir" /></td>';
            html += '<td><input type="text" name="total_lembur[]" readonly = true class="form-control total_lembur" readonly="true" /></td>';
            html += '<td><input type="text" name="pre_awal_approved[]" style="width: 70px" readonly="true" class="form-control" /></td>';
            html += '<td><input type="text" name="pre_akhir_approved[]" style="width: 70px" readonly="true" class="form-control" /></td>';
            html += '<td><input type="text" name="pre_total_approved[]" readonly="true" class="form-control" /></td>';
            html += '<td><input type="text" style="width: 70px" readonly="true" class="form-control clock_in" value="#" /></td>';
            html += '<td><input type="text" style="width: 70px" readonly="true" class="form-control clock_out" value="#" /></td>';
            html += '<td><input type="text" name="awal_claim[]" style="width: 70px" class="form-control time-picker awal_claim"/></td>';
            html += '<td><input type="text" name="akhir_claim[]" style="width: 70px" class="form-control time-picker akhir_claim "/></td>';
            html += '<td><input type="text" id="total_lembur_claim[]" name="total_lembur_claim[]" class="form-control total_lembur_claim" readonly="true" /></td>';
            html += '<td><input type="text" style="width: 70px" readonly="true" name="awal_approved[]" class="form-control"/></td>';
            html += '<td><input type="text" style="width: 70px" readonly="true" name="akhir_approved[]" class="form-control"/></td>'; 
            html += '<td><input type="text" readonly="true" name="total_lembur_approved[]" class="form-control"/></td>';
            html += '<td><input type="text" readonly="true" name="overtime_calculate[]" class="form-control" /></td>';
            html += '<td><input type="text" readonly="true" name="meal_allowance[]" class="money form-control" /></td>';
            html += '<td><a class="btn btn-danger btn-xs" onclick="hapus_(this)"><i class="fa fa-trash"></i></a></td>';
            html += '</tr>';

        $('.table-content-lembur').append(html);

        $('.time-picker').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            'default': 'now'
        });

        handlerDate()

        sum_total_claim();
    });
  

</script>
@endsection
@endsection