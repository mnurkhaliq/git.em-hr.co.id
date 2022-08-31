@extends('layouts.karyawan')

@section('title', 'Leave / Permit Employee')

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
                <h4 class="page-title">Form Leave / Permit Employee</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Leave / Permit Employee</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form-cuti" enctype="multipart/form-data" action="{{ route('karyawan.leave.update', $data->id) }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form Leave</h3>
                        <hr />
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
                        {!! method_field('PUT') !!}
                        
                        <div class="col-md-6" style="padding-left: 0;">
                            <div class="form-group">
                                <label class="col-md-6">NIK / Employee Name</label>
                                <label class="col-md-6">Mobile Number</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name }}" readonly="true">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ Auth::user()->mobile_1 }}" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Position</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control jabatan" value="{{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Leave Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $data->cuti->description }}" readonly="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Leave Type</label>
                                <label class="col-md-2">Late Coming</label>
                                <label class="col-md-2">Early Leave</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $data->cuti->jenis_cuti }}" readonly="true">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" value="{{ $data->jam_datang_terlambat ? date_format(date_create($data->jam_datang_terlambat), 'H:i') : '' }}" readonly="true">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" value="{{ $data->jam_pulang_cepat ? date_format(date_create($data->jam_pulang_cepat), 'H:i') : '' }}" readonly="true">
                                </div>
                                <div class="col-md-6" id="section_jenis_izin" style="display: none;">
                                    <input type="text" class="form-control" name="jenis_izin" placeholder="Jenis Izin" />
                                </div>
                            </div>
                            <div class="form-group"> 
                                <label class="col-md-6">Leave Quota</label>
                                <label class="col-md-2">Leave Taken</label>
                                <label class="col-md-2">Leave Balance</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control kuota_cuti"  value="{{ $data->temp_kuota }}" readonly="true" />
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control cuti_terpakai" readonly="true" value="{{ $data->temp_cuti_terpakai == "" ? 0 : $data->temp_cuti_terpakai }}"  />
                                </div>
                                <div class="col-md-2">
                                    <input type="text" readonly="true" class="form-control sisa_cuti" value="{{ $data->temp_sisa_cuti }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="btn btn-info" id="history_cuti" style="height: 35px; width: 100px"><i class="fa fa-history"></i> History</label>
                                </div>
                            </div>
                            @if($data->attachment)
                            <div class="form-group"> 
                                <label class="col-md-12">Supporting Document/Attachment</label>
                                <div class="col-md-12">
                                    <div id="previewAttachment"></div>
                                </div>
                            </div>
                            @endif
                            @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-12">Note {{$item->is_withdrawal ? 'Withdrawal ' : ''}}Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                </div>
                            </div>
                            @endforeach

                            <div class="clearfix"></div>
                            <br />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Date of Leave</label>
                                <div class="col-md-4">
                                    <input type="text" name="tanggal_cuti_start" readonly="true" value="{{ $data->tanggal_cuti_start }}" class="form-control" placeholder="Start Date" />
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="tanggal_cuti_end"  readonly="true" value="{{ $data->tanggal_cuti_end }}" class="form-control" placeholder="End Date">
                                </div>
                                <div class="col-md-4">
                                    <h3 class="btn btn-info total_hari_cuti" style="margin-top:0; height: 35px;">{{ $data->total_cuti }} Day/s</h3>
                                    <h3 class="btn btn-warning btn_hari_libur" style="margin-top:0; height: 35px;">Public Holiday</h3>
                                </div>
                            </div>
                            <div class="form-group" id="my-calendar-group" style="display: none;">
                                <div class="col-md-12">
                                    <div id="my-calendar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Purpose</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="keperluan" readonly="true">{{ $data->keperluan }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Backup Person</label>
                                <div class="col-md-12">
                                    <input type="text" readonly="true" class="form-control" value="{{  $data->backup_karyawan->nik }} / {{  $data->backup_karyawan->name }}">
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-md-12">Position</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control" value="{{ isset($data->backup_karyawan->structure->position) ? $data->backup_karyawan->structure->position->name:''}}{{ isset($data->backup_karyawan->structure->division) ? ' - '. $data->backup_karyawan->structure->division->name:''}}{{ isset($data->backup_karyawan->structure->title) ? ' - '. $data->backup_karyawan->structure->title->name:''}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Mobile Number</label>
                                <label class="col-md-6">Email</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control no_handphone" value="{{ $data->backup_karyawan->mobile_1 }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control email" value="{{ $data->backup_karyawan->email }}">
                                </div>
                            </div>
                            <input type="hidden" name="status" value="5" />
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('karyawan.leave.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            @if($data->status < 2)
                            <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_cancel"><i class="fa fa-close"></i> Cancel</a>
                            @elseif($data->status == 2)
                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_withdraw"><i class="fa fa-save"></i> Withdraw</a>
                            @endif
                            <br style="clear: both;" />
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
    @include('layouts.footer')
</div>

<!-- sample modal content -->
<div id="modal_history_cuti" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Leave History</h4> </div>
                <div class="modal-body">
                   <div class="form-horizontal">
                    <table class="table tabl-hover">
                       <thead>
                           <tr>
                               <th width="50">NO</th>
                               <th>DATE OF LEAVE</th>
                               <th>LEAVE TYPE</th>
                               <th>LEAVE DURATION</th>
                               <th>PURPOSE</th>
                               <th>STATUS</th>
                           </tr>
                       </thead> 
                       <tbody>
                        @foreach(list_cuti_user(Auth::user()->id) as $no => $item)
                        <tr>
                           <td>{{ $no + 1 }}</td>
                           <td>{{ $item->tanggal_cuti_start }} - {{ $item->tanggal_cuti_end }}</td>
                           <td>{{ $item->cuti->description }}</td>
                           <td>{{ $item->total_cuti }}</td>
                           <!--<td>{{ lama_hari($item->tanggal_cuti_start, $item->tanggal_cuti_end) }}</td>-->
                           <td>{{ $item->keperluan }}</td>
                           <td>
                                @if($item->status == 3)
                                    Rejected
                                @elseif($item->status == 4)
                                    Cancelled
                                @elseif($item->status == 1)
                                    Waiting Approval
                                @elseif($item->status == 2)
                                    Approved
                                @endif
                           </td>
                        </tr>
                        @endforeach
                        </tbody>
                       </table>
                   </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- sample modal content -->
<div id="modal_hari_libur" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">PUBLIC HOLIDAY</h4> </div>
                <div class="modal-body">
                   <div class="form-horizontal">
                    <table class="table tabl-hover">
                       <thead>
                           <tr>
                               <th width="50">NO</th>
                               <th>DATE</th>
                               <th>DESCRIPTION</th>
                           </tr>
                       </thead> 
                       <tbody>
                        @foreach(list_hari_libur() as $no => $item)
                        <tr>
                           <td>{{ $no + 1 }}</td>
                           <td>{{ date('d F Y', strtotime($item->tanggal)) }}</td>
                           <td>{{ $item->keterangan }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                       </table>
                   </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')

<style>
    .type-4 {
        background-color: #992ce3;
        color: black;
    }
    .type-3 {
        background-color: #FA2601;
        color: black;
    }
    .type-2 {
        background-color: #FA8A00;
        color: black;
    }
    .type-1 {
        background-color: #2cabe3;
        color: black;
    }
    .day {
        height: 40px;
        vertical-align: middle;
        font-size: medium;
        font-weight: bold;
    }
    .calendar-month-header td {
        vertical-align: middle !important;
    }
</style>

<!-- Example style -->
<link rel="stylesheet" type="text/css" href="https://zabuto.com/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/examples/style.css">

<!-- Zabuto Calendar -->
<script src="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.css">

<link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">

    $("#btn_cancel").click(function(){
        bootbox.confirm('Cancel the leave form ?', function(result){

            $("input[name='status']").val(5);
            if(result)
            {
                $('#form-cuti').submit();
            }

        });
    });

    $("#btn_withdraw").click(function(){
        bootbox.confirm('Withdraw the leave form ?', function(result){

            $("input[name='status']").val(6);
            if(result)
            {
                $('#form-cuti').submit();
            }

        });
    });

    $(".btn_hari_libur").click(function(){

        $("#modal_hari_libur").modal('show');

    });

    $("input[name='tanggal_cuti_end'], input[name='tanggal_cuti_start']").on('change', function(){
        
        var oneDay      = 24*60*60*1000; // hours*minutes*seconds*milliseconds
        var start_date  = $("input[name='tanggal_cuti_start']").val();
        var end_date    = $("input[name='tanggal_cuti_end']").val();
        
        if(start_date == "" || end_date == "")
        {
            return false;
        }


        if(start_date == end_date)
        {
            $('.total_hari_cuti').html('1 Hari');            
        }
        else
        {
            var star_date   = new Date(start_date);
            var end_date    = new Date(end_date);

            $('.total_hari_cuti').html((Math.round(Math.round((end_date.getTime() - star_date.getTime()) / (oneDay))) + 1) +" Hari" );
        }
    });

    $("#history_cuti").click(function(){

        $("#modal_history_cuti").modal('show');

    });

    $("select[name='jenis_cuti']").on('change', function(){

        var el = $(this).find(":selected");
    
        if($(this).val() != 'Izin')
        {   
            $('.kuota_cuti').val( el.data('kuota') );
        }else{
            $('.kuota_cuti').val('0');
        }

    });

    $("#btn_submit_form").click(function(){
        bootbox.confirm('Submit Form Cuti ?', function(result){
            if(result)
            {
                $("#form-cuti").submit();
            }
        });
    });

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
    
    $("select[name='backup_user_id']").on('change', function(){

        var id = $(this).val();

        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.get-karyawan-by-id') }}',
            data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (data) {

                $('.jabatan_backup').val(data.data.organisasi_job_role);
                $('.department_backup').val(data.data.department_name);
                $('.no_handphone').val(data.data.telepon);
                $('.email').val(data.data.email);
            }
        });

    });

    $("select[name='user_id']").on('change', function(){

        var id = $(this).val();

        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.get-karyawan-by-id') }}',
            data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (data) {

                $('.hak_cuti').val(data.data.hak_cuti);
                $('.jabatan').val(data.data.nama_jabatan);
                $('.department').val(data.data.department_name);
                $('.cuti_terpakai').val(data.data.cuti_yang_terpakai);

                $("select[name='backup_user_id'] option[value="+ id +"]").remove();
            }
        });
    });


    $("#add").click(function(){

        var no = $('.table-content-lembur tr').length;

        var html = '<tr>';
            html += '<td>'+ (no+1) +'</td>';
            html += '<td><textarea name="description[]" class="form-control"></textarea></td>';
            html += '<td><input type="text" name="awal[]" class="form-control" /></td>';
            html += '<td><input type="text" name="akhir[]" class="form-control" /></td>';
            html += '<td><input type="text" name="total_lembur[]" class="form-control"  /></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Pilih Employee</option></select></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Pilih SPV</option></select></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Pilih Manager</option></select></td>';
            html += '</tr>';

        $('.table-content-lembur').append(html);

    });

    function showAttachment(){
        img = "{{ asset($data->attachment) }}";
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#previewAttachment').html('<embed src="'+img+'" style="width: 100%; height: 400px;">');
        } else {
            $('#previewAttachment').html('<img src="'+img+'" style="max-width: 100%; max-height: 400px;" />');
        }
    }

    $(document).ready(function() {
        showAttachment();

        $.ajax({
            type: 'GET',
            url: '{{ route("karyawan.leave.get-list-day", "$data->id") }}',
            success: function (data) {
                if (data.length) {
                    $("#my-calendar").zabuto_calendar({
                        today: true,
                        month: data[0]['date'].split("-")[1],
                        year: data[0]['date'].split("-")[0],
                        show_previous: 0,
                        show_next: Math.abs(((new Date(data[data.length-1]['date']).getYear() - new Date(data[0]['date']).getYear())*12) - (new Date(data[data.length-1]['date']).getMonth() - new Date(data[0]['date']).getMonth())),
                        legend: [
                            {type: "block", label: "Leave/permit day", classname: 'type-1'},
                            {type: "block", label: "Shift off day", classname: 'type-2'},
                            {type: "block", label: "Holiday", classname: 'type-3'},
                            {type: "block", label: "Other Leave", classname: 'type-4'},
                        ],
                        data: data
                    });
                    $('#my-calendar-group').show()
                }
            }
        });
    });
</script>


@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
