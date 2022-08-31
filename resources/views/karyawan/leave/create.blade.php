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
            <form class="form-horizontal" id="form-cuti" enctype="multipart/form-data" action="{{ route('karyawan.leave.store') }}" method="POST" autocomplete="off">
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
                                    <input type="text" readonly="true" class="form-control jabatan" 
                                    value="{{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Leave Type</label>
                                    <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control type_cuti" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Leave Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="jenis_cuti" id="jenis_cuti" required>
                                        <option value="">Choose Leave Name</option>
                                        @foreach(list_user_cuti() as $item)
                                        <option value="{{ $item->id }}" data-attachment="{{ $item->is_attachment ? true : false }}" data-kuota="{{ get_kuota_cuti($item->id, \Auth::user()->id ) }}" data-cutiterpakai="{{ get_cuti_terpakai($item->id, \Auth::user()->id) }}" data-sisacuti="{{ get_cuti_user($item->id, \Auth::user()->id, 'sisa_cuti') }}" data-type="{{$item->jenis_cuti}}" >{{ $item->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="jam_datang_terlambat" style="display: none;" class="form-control jam_datang_terlambat" placeholder="Coming">
                                </div>
                                <div class="col-md-2"> 
                                    <input type="text" name="jam_pulang_cepat" style="display: none;" class="form-control jam_pulang_cepat" placeholder="Leave">
                                </div>
                                <div class="col-md-2">
                                    <label class="btn btn-info history_cuti timeHistory" id="history_cuti" style="height: 35px; width: 100px; display: none;"><i class="fa fa-history"></i> History</label>
                                </div>
                            </div>
                            
                            <div class="form-group" id="TypeLeave" style="display: none;"> 
                                <label class="col-md-6">Leave Quota</label>
                                <label class="col-md-2">Leave Taken</label>
                                <label class="col-md-2">Leave Balance</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control kuota_cuti" name="temp_kuota" readonly="true" />
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control cuti_terpakai" name="temp_cuti_terpakai" readonly="true"  />
                                </div>
                                <div class="col-md-2">
                                    <input type="text" readonly="true" class="form-control sisa_cuti" name="temp_sisa_cuti">
                                </div>
                                <div class="col-md-2">
                                    <label class="btn btn-info history_cuti" id="history_cuti" style="height: 35px; width: 100px"><i class="fa fa-history"></i> History</label>
                                </div>
                            </div>

                            <div class="form-group" id="is_attachment" style="display: none;">
                                <label class="col-md-12">Supporting Document/Attachment</label>
                                <div class="col-md-6">
                                    <input type="file" id="attachment" name="attachment" class="form-control" accept="image/x-png,image/gif,image/jpg,image/jpeg,application/pdf" />
                                </div>
                                <div class="col-md-2">
                                    <a onclick="show_image()" class="btn btn-info" style="height: 35px; width: 100%"><i class="fa fa-search-plus"></i>View</a>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Date of Leave</label>
                                <div class="col-md-4">
                                    <input type="text" name="tanggal_cuti_start" class="form-control" id="from" placeholder="Start Date" />
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="tanggal_cuti_end" class="form-control" id="to" placeholder="End Date">
                                </div>
                                <div class="col-md-4">
                                    <h3 class="btn btn-info total_hari_cuti" style="margin-top:0; height: 35px;">0 Day/s</h3>
                                    <h3 class="btn btn-warning btn_hari_libur" style="margin-top:0; height: 35px;">Public Holiday</h3>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Purpose</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="keperluan"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Backup Person</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control autcomplete-backup">
                                    <input type="hidden" name="backup_user_id" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Position</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control jabatan_backup">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Mobile Number</label>
                                <label class="col-md-6">Email</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control no_handphone">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control email">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('karyawan.leave.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit_form"><i class="fa fa-save"></i> Submit Form Leave/Permit</a>
                            <br style="clear: both;" />
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>  
                <input type="hidden" name="total_cuti" />
                <div id="leave_list" hidden></div>  
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
                <h4 class="modal-title" id="modalHistoryLabel">Leave History</h4> </div>
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
                        <tr class="data-cuti data-cuti-{{$item->jenis_cuti}}">
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

<div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <img id="modalcontent" style="max-width: 100%; max-height: 500px;">
                <embed id="modalcontentpdf" width="100%" height="500">
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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script type="text/javascript">
    var list_anggota = [];
    var list_atasan = [];

    @foreach(get_backup_cuti() as $item)
        list_anggota.push({id : {{ $item->id }}, value : "{{ $item->nik .' - '. $item->name }}" });
    @endforeach
    console.log(list_anggota);

    @foreach(empore_get_atasan_langsung() as $item)
        list_atasan.push({id : {{ $item->id }}, value : '{{ $item->nik .' - '. $item->name.' - '. empore_jabatan($item->id) }}',  });
    @endforeach
</script>
<script type="text/javascript">

    function show_image() {
        $('#modalcontent').removeAttr('src');
        $('#modalcontentpdf').removeAttr('src');

        let array = ['png','gif','jpg','jpeg','pdf'];
        let ext = $('#attachment').val().split('.').pop().toLowerCase();
        if(array.includes(ext)) {
            var reader = new FileReader();
            reader.onload = function(e) {
                if(ext == 'pdf')
                    $('#modalcontentpdf').attr('src', e.target.result);
                else
                    $('#modalcontent').attr('src', e.target.result);
            }
            reader.readAsDataURL(document.getElementById('attachment').files[0]);
            $('#modal_file').modal('show');
        } else {
            alert("Filetype is not supported!");
        }
    }

    $(".autcomplete-atasan" ).autocomplete({
        source: list_atasan,
        minLength:0,
        select: function( event, ui ) {
            $( "input[name='atasan_user_id']" ).val(ui.item.id);
            
            var id = ui.item.id;

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    $('.jabatan_atasan').val(data.data.jabatan);
                    $('.department_atasan').val(data.data.department_name);
                    $('.no_handphone_atasan').val(data.data.mobile_1);
                    $('.email_atasan').val(data.data.email);
                }
            });
        }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });

    $(".autcomplete-backup" ).autocomplete({
        source: list_anggota,
        minLength:0, 
        select: function( event, ui ) {
            $( "input[name='backup_user_id']" ).val(ui.item.id);
            
            var id = ui.item.id;

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    $('.jabatan_backup').val(data.data.position);
                    $('.no_handphone').val(data.data.mobile_1);
                    $('.email').val(data.data.email);
                }
            });
          }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });
    
    $(".btn_hari_libur").click(function(){

        $("#modal_hari_libur").modal('show');

    });

    $("input[name='tanggal_cuti_end'], input[name='tanggal_cuti_start']").on('change', function(){
        calculateCuti();
    });
    $("input[name='tanggal_cuti_end'], input[name='tanggal_cuti_start']").on('input', function(){
        calculateCuti();
    });

    function getMonth(date) {
      var month = date.getMonth() + 1;
      return month < 10 ? '0' + month : '' + month;
    } 

    function getDay(date) {
      var month = date.getDate() + 1;
      return month < 10 ? '0' + month : '' + month;
    }  


    var total_hari = 0;
    function calculateCuti() {        
        if($("input[name='tanggal_cuti_start']").val() == "" || $("input[name='tanggal_cuti_end']").val() == "") {
            return false;
        }

        let start_date = new Date($("input[name='tanggal_cuti_start']").val());
        let end_date = new Date($("input[name='tanggal_cuti_end']").val());
        let start_date_loop = new Date(start_date);
        let total_libur = 0;

        let loopMonth = '';
        let loopDay = '';
        let loopYear = '';
        let loopDate = '';
        let loopHoliday = '';

        $("#leave_list").html('')
        let index = 0;
        while(start_date_loop <= end_date){
            loopMonth = '' + (start_date_loop.getMonth() + 1)
            loopDay = '' + start_date_loop.getDate()
            loopYear = start_date_loop.getFullYear()

            if (loopMonth.length < 2) 
                loopMonth = '0' + loopMonth;
            if (loopDay.length < 2) 
                loopDay = '0' + loopDay;

            loopDate = [loopYear, loopMonth, loopDay].join('-');
            loopHoliday = hari_libur.filter(i => loopDate == i.date);

            if (loopHoliday.length > 0) {
                total_libur++;
                $("#leave_list").append('<input name="day_list['+(index)+'][type]" value="'+(loopHoliday[0]['classname'])+'">');
                $("#leave_list").append('<input name="day_list['+(index)+'][desc]" value="'+(loopHoliday[0]['title'])+'">');
            } else {
                $("#leave_list").append('<input name="day_list['+(index)+'][type]" value="1">');
                $("#leave_list").append('<input name="day_list['+(index)+'][desc]" value="Leave/permit day">');
            }
            $("#leave_list").append('<input name="day_list['+(index++)+'][date]" value="'+loopDate+'">');

            start_date_loop = new Date(start_date_loop.setDate(start_date_loop.getDate() + 1)); //date increase by 1
        }
        
        total_hari  = Math.floor((end_date.getTime() - start_date.getTime()) / (1000 * 3600 * 24)) + 1 - total_libur;

        $('.total_hari_cuti').html(total_hari+" Day/s"); 
        $("input[name='total_cuti']").val(total_hari);
    }

    function calcBusinessDays(dDate1, dDate2) // input given as Date objects
    { 
        var iWeeks, iDateDiff, iAdjust = 0;
        if (dDate2 < dDate1) return -1; // error code if dates transposed
        var iWeekday1 = dDate1.getDay(); // day of week
        var iWeekday2 = dDate2.getDay();
        iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1; // change Sunday from 0 to 7
        iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
        if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1; // adjustment if both days on weekend
        iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1; // only count weekdays
        iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;
     
        // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
        iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)
     
        if (iWeekday1 <= iWeekday2) {
          iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
        } else {
          iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
        }
     
        iDateDiff -= iAdjust // take into account both days on weekend
     
        return (iDateDiff + 1); // add 1 because dates are inclusive
    }

    $(".history_cuti").click(function(){
        var jenis_cuti = $('#jenis_cuti').val();
        var leaveName = $('#jenis_cuti option:selected').text();
        $('.data-cuti').addClass('hidden');
        $('.data-cuti-'+jenis_cuti).removeClass('hidden');

        $('#modalHistoryLabel').html(leaveName+' History');
        $("#modal_history_cuti").modal('show');

    });

    $("select[name='jenis_cuti']").on('change', function(){

        var el = $(this).find(":selected");
        if(el.data('attachment')) {
            $('#is_attachment').show()
        } else {
            $('#is_attachment').hide()
            $('#attachment').val('')
        }

        if(el.data('type') =='Special Leave' || el.data('type') =='Annual Leave') {   
            document.getElementById('TypeLeave').style.display = "block";
            time_picker();
            $('.jam_pulang_cepat').val('').hide();
            $('.timeHistory').hide();
            $('.jam_datang_terlambat').val('').hide();

            $('.kuota_cuti').val( el.data('kuota') );
            $('.cuti_terpakai').val( el.data('cutiterpakai') );
            $('.sisa_cuti').val( el.data('sisacuti') );
            $('.type_cuti').val( el.data('type') );
        } else if(el.data('type') =='Permit') {
            document.getElementById('TypeLeave').style.display = "none";
            time_picker();
            $('.jam_pulang_cepat').show();
            $('.timeHistory').show();
            $('.jam_datang_terlambat').show();

            $('.kuota_cuti').val('0');
            $('.cuti_terpakai').val('0');
            $('.sisa_cuti').val('0');
            $('.type_cuti').val( el.data('type') );
        } else if(el.data('type') =='Attendance') {
            document.getElementById('TypeLeave').style.display = "none";
            time_picker();
            $('.jam_pulang_cepat').show();
            $('.timeHistory').show();
            $('.jam_datang_terlambat').show();
            
            $('.kuota_cuti').val('0');
            $('.cuti_terpakai').val('0');
            $('.sisa_cuti').val('0');
            $('.type_cuti').val( el.data('type') );
        }
    });

    $("#btn_submit_form").click(function(){
        $("div").removeClass("has-error");
        if($("input[name='backup_user_id']").val() == "" || $("textarea[name='keperluan']").val() == "" ||  $("input[name='tanggal_cuti_start']").val() == "" || $("input[name='tanggal_cuti_end']").val() == "" || ($("input[name='attachment']").val() == "" && $('#is_attachment').is(":visible")) )
        {
            if($("input[name='backup_user_id']").val() == "")
                $("input[name='backup_user_id']").parent().addClass('has-error');
            if($("textarea[name='keperluan']").val() == "")
                $("textarea[name='keperluan']").parent().addClass('has-error');
            if($("input[name='tanggal_cuti_start']").val() == "")
                $("input[name='tanggal_cuti_start']").parent().addClass('has-error');
            if($("input[name='tanggal_cuti_end']").val() == "")
                $("input[name='tanggal_cuti_end']").parent().addClass('has-error');
            if($("input[name='attachment']").val() == "" && $('#is_attachment').is(":visible"))
                $("input[name='attachment']").parent().addClass('has-error');

            bootbox.alert("Leave data is incomplete !");
            return false;
        }

        if($("input[name='tanggal_cuti_start']").val() < minDate || hari_libur.filter(i => $("input[name='tanggal_cuti_start']").val() == i.date).length || $("input[name='tanggal_cuti_end']").val() > maxDate || hari_libur.filter(i => $("input[name='tanggal_cuti_end']").val() == i.date).length) {
            if($("input[name='tanggal_cuti_start']").val() < minDate  || hari_libur.filter(i => $("input[name='tanggal_cuti_start']").val() == i.date).length)
                $("input[name='tanggal_cuti_start']").parent().addClass('has-error');
            if($("input[name='tanggal_cuti_end']").val() > maxDate  || hari_libur.filter(i => $("input[name='tanggal_cuti_end']").val() == i.date).length)
                $("input[name='tanggal_cuti_end']").parent().addClass('has-error');

            bootbox.alert("Invalid date");
            return false;
        }

        if ($("#jenis_cuti").val() === "") {
            $("#jenis_cuti").parent().addClass('has-error');
            bootbox.alert("Leave Type is incomplete !");
            return false;
        }

        if(total_hari == 0)
        {
            bootbox.alert('Your total leave is wrong, Please check the date of submission !');

            return false;
        }

        if($("input[name='atasan_user_id']").val() == "")
        {

            bootbox.alert('Superior names are not found in your list department !');
            return false;
        }

        bootbox.confirm('Submit Form Leave/Permit?', function(result){
            if(result) {
                $("#form-cuti").submit();
            }
        });
    });

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        minDate: 1
    });
    
    var hari_libur = []
    var maxDate = null
    var minDate = null
    function getShiftSchedule() {
        $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-leave-calendar') }}',
            success: function (data) {
                hari_libur = data.event_dates
                maxDate = data.end_date
                minDate = data.start_date
                $( "#from, #to" ).datepicker({
                    // defaultDate: "+1w",
                    dateFormat:"yy-mm-dd",
                    maxDate: maxDate,
                    minDate: minDate,
                    changeMonth: true,
                    numberOfMonths: 2,
                    beforeShowDay: function(date){
                        let loopDate = null
                        let loopMonth = '' + (date.getMonth() + 1)
                        let loopDay = '' + date.getDate()
                        let loopYear = date.getFullYear()

                        if (loopMonth.length < 2) 
                            loopMonth = '0' + loopMonth;
                        if (loopDay.length < 2) 
                            loopDay = '0' + loopDay;

                        loopDate = [loopYear, loopMonth, loopDay].join('-');

                        return [hari_libur.filter(i => loopDate == i.date).length == 0]
                    },
                    onSelect: function(selectedDate) {
                        if(this.id == 'from'){
                            let dateMin = $('#from').datepicker("getDate");
                            let rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate()); // Min Date = Selected
                            $('#to').datepicker("option", "minDate", rMin);
                        } else {
                            let dateMin = $('#to').datepicker("getDate");
                            let rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate()); // Max Date = Selected
                            $('#from').datepicker("option", "maxDate", rMax);
                        }

                        calculateCuti();
                    }
                });
            }
        });
    }
    getShiftSchedule();

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
                $('.no_handphone').val(data.data.mobile_1);
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
                $('.cuti_terpakai').val(data.data.cuti_yang_terpakai == "" ? 0 : data.data.cuti_yang_terpakai);

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
            html += '<td><select name="employee_id" class="form-control"><option value="">Choose Employee</option></select></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Choose SPV</option></select></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Choose Manager</option></select></td>';
            html += '</tr>';

        $('.table-content-lembur').append(html);

    }); 

    function time_picker()
    {
        // Clock pickers
        $('.jam_pulang_cepat').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            'default': 'now'
        });

        $('.jam_datang_terlambat').clockpicker({
            placement: 'bottom',
            align: 'left',
            autoclose: true,
            'default': 'now'
        });
    }

</script>


@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
