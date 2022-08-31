@extends('layouts.karyawan')

@section('title', 'Clock In')

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
                <h4 class="page-title">HOME</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="GET" action="" style="float: right; width: 40%;">
                    <div class="form-group">
                        <i class="fa fa-search-plus" style="float: left;font-size: 20px;margin-top: 9px;margin-right: 12px;"></i>
                        <input type="text" name="keyword-karyawan" class="form-control autocomplete-karyawan" style="float:left;width: 80%;margin-right: 5px;" placeholder="Search Employee Here">
                    </div>
                </form>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            
            <div class="col-lg-12 col-sm-12 col-md-12" id="content_search_karyawan"></div>
            <div class="col-lg-8 col-sm-8 col-md-8" style="padding: 0px">
                <div class="col-md-12">
                    <div class="panel panel-warning" style="background: #0E9A88;"> 
                        <div class="panel-body" style="background: #0E9A88; border:1px solid #0E9A88;">
                            <h1 style="margin-bottom:10px; color: white; font-weight: 400;" class="text-center">{{Auth::user()->name}}</h1>
                            <h2 style="margin-top:0px; color: white; font-weight: 400;" class="text-center">{{$settings['title']}}</h2>
                            <h5 style="margin-top:0px; color: white; font-weight: 400;" class="text-center">Branch Time {{$timezone}} | {{date('l, d F Y', strtotime($date_shift))}} </h5>
                            <h1 style="margin-bottom:0px; color: white; font-weight: 400;" id="MyClockDisplay" class="clock text-center" onload="startTime()"></h1>
                            <h4 style="margin-top:0px; color: white; font-weight: 400;" class="text-center"> {{ $absensi == null ? 'You can clock in now' : 'You  already did clock in today' }} </h4>
                            @if($absensi== null)
                                <h2 style="margin-bottom:20px;margin-top:20px;color:white; font-weight: 500; font-size: 20px;" class="text-center"><a href="{{route('karyawan.clock-in')}}" class="btn " style="color: white;  background:#cbe653;"> CLOCK IN </a></h2>
                            @else 
                            <div class="col-md-4">
                                <p style="margin-bottom:0; color:white; font-weight: 400;" class="text-center">Clock In</p>
                                <h2 style="margin-top:0; color:white; font-weight: 400;" class="text-center">{{$absensi['clock_in']}}</h2>
                            </div>
                            <div class="col-md-4">
                                <h2 style="margin-bottom:0;color:white; font-weight: 500; font-size: 20px;" class="text-center"><a href="{{route('karyawan.clock-in')}}" class="btn" style="color: white;  background:#cbe653;"> ADD CLOCK IN </a></h2>
                            </div>
                            <div class="col-md-4">
                                <p style="margin-bottom:0; color:white; font-weight: 400;" class="text-center">Clock Out</p>
                                <h2 style="margin-top:0; color:white; font-weight: 400;" class="text-center">{{$absensi['clock_out']}}</h2>
                            </div>
                            @endif
                        </div>
                        <div class="panel-body" style="background: #bdbbbb; border:1px solid #bdbbbb;">
                            <h4 style="color: black; font-weight: 400;" class="text-center">{{$settings['attendance_news']}} </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-4 col-md-4" style="padding: 0px">
                <div class="col-md-12">
                   <div class="panel panel-warning">
                       <div class="panel-body" style="padding-bottom:0px;">
                           <div class="col-md-12">
                               <h2 class="text-center" style="color: black; font-weight: 400;  margin-bottom:0px;">Attendance Statistic</h2>
                           </div>
                           <div class="col-md-5" style="margin-left:20px; margin-bottom:10px; background: #bdbbbb;">
                               <h1 style="color: #0E9A88; font-weight: 400;" class="text-center" id="total_attendance">{{$statistic['total_attendance']}}</h1>
                               <p style="color: black; font-weight: 400;"  class="text-center">Attendance</p>
                           </div>
                           <div class="col-md-5" style="margin-left:20px; margin-bottom:10px; background: #bdbbbb;">
                               <h1 style="color: #0E9A88; font-weight: 400;" class="text-center" id="total_work_hour">{{$statistic['total_work_hour']}}</h1>
                               <p style="color: black; font-weight: 400;"  class="text-center">Total Work Hour</p>
                           </div>
                           <div class="col-md-5" style="margin-left:20px; margin-bottom:10px; background: #bdbbbb;">
                               <h1 style="color: red; font-weight: 400;" class="text-center" id="total_late">{{$statistic['total_late']}}</h1>
                               <p style="color: black; font-weight: 400;"  class="text-center">Late Clock In</p>
                           </div>
                           <div class="col-md-5" style="margin-left:20px; margin-bottom:10px; background: #bdbbbb;">
                               <h1 style="color: red; font-weight: 400;" class="text-center" id="total_forget">{{$statistic['total_forget']}}</h1>
                               <p style="color: black; font-weight: 400;"  class="text-center">Forget to Clock Out</p>
                           </div>
                           <div class="col-md-12" style="margin-top: 10px;">
                                <div class="col-md-6">
                                   <div class="form-group">
                                       <label class="col-md-12">Start Date</label>
                                       <div class="col-md-12" style="margin: 0px; padding:0px;">
                                           <input type="date" name="start_date" id="start_date" class="form-control" value="{{date('Y-m-d', strtotime('-30 days'))}}">
                                       </div>
                                   </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">End Date</label>
                                        <div class="col-md-12" style="margin: 0px; padding:0px;">
                                            <input type="date" name="end_date" id="end_date" class="form-control" max="<?= date('Y-m-d'); ?>" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                           </div>
                        </div>
                        <div class="panel-body" style="margin: 0px; padding:0px;">
                            <hr style="padding: 0px; border: 0.5px solid #bdbbbb; ">
                            <h4 class="text-center" style="color: black; font-weight: 400;">Your attendance history, click <a href="{{route('karyawan.profile', ['tab' => 'attendance'])}}" class="btn btn-sm btn-warning" style="color: white; padding-top:0px;"> here </a></h4>
                        </div>
                   </div>
               </div>
           </div>

            <div class="clearfix"></div>
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
</div>
<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
    .hp {
        width: 130px;
        /* position: absolute; */
        bottom: 38px;
        left: 153px;
    }
    @media (min-width: 1600px) {
        .birthday {
            width: 50%;
        }
        .birthday .panel-body {
            padding: 15px;
        }
        .hp {
            width: 78px;
        }
    }
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
    .zabuto_calendar {
        padding: 10px !important;
        background-color: white;
    }
    #my-holiday, #my-holiday div {
        background-color: white;
        color: #FA2601;
        font-weight: bold;
    }
    #my-holiday div:last-child, #my-holiday div:nth-last-child(2) {
        padding-bottom: 10px;
    }
</style>
@section('footer-script')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Example style -->
<link rel="stylesheet" type="text/css" href="https://zabuto.com/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/examples/style.css">

<!-- Zabuto Calendar -->
<script src="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script type="text/javascript">
    $(".autocomplete-karyawan" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
                url: "{{ route('ajax.get-karyawan') }}",
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
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : ui.item.id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    data = data.data;

                    var el = '<div class="panel panel-themecolor" style="position:relative;"><div class="panel-body"><i class="ti-close" onclick="tutup_ini(this)" style=" position: absolute;right: 36px;top: 18px;color: red;cursor:pointer;"></i><div class="table-responsive">';
                        el += '<table class="table table-striped">';
                        el += '<thead><tr>';
                        el += '<th>NIK</th>';
                        el += '<th>NAMA</th>';
                        el += '<th>TELEPON</th>';
                        el += '<th>EMAIL</th>';
                        el += '<th>EXT</th>';
                        el += '<th>JOB RULE</th>';
                        el += '</tr></thead>';

                        el += '<tbody><tr>';
                        el += '<td>'+ data.nik +'</td>';
                        el += '<td>'+ data.name +'</td>';
                        el += '<td>'+ (data.telepon == null ? '' : data.telepon ) +'</td>';
                        el += '<td>'+ (data.email == null ? '' : data.email) +'</td>';
                        el += '<td>'+ (data.ext ==null ? '' : data.ext) +'</td>';
                        el += '<td>'+ data.position +'</td>';
                        el += '</tr></tbody>';
                        el += '</table>';
                        el += '</div></div></div>'

                        $("#content_search_karyawan").prepend(el);

                    setTimeout(function(){
                        $(".autocomplete-karyawan").val(" ");

                        $(".autocomplete-karyawan").triggerHandler("focus");

                    }, 500);
                }
            });

            $(".autocomplete-karyawan" ).val("");
        }
    }).on('focus', function () {
        $(this).autocomplete("search", "");
    });

    function tutup_ini(el) {
        $(el).parent().parent().hide("slow");
    }

    function startTime() {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('MyClockDisplay').innerHTML =  h + ":" + m + ":" + s;
        $("#time").val(h + ":" + m + ":" + s)
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }

    startTime();

    $("#start_date").on("change",function(){
        var selected = $(this).val();
        var date = new Date(selected);
        date.setDate(date.getDate() + 30);

        if(date >= new Date()){
            date = new Date()
        }
        // console.log(date)
        var dd = date.getDate();
        var mm = date.getMonth()+1;
        var yyyy = date.getFullYear();

        if(dd<10){
            dd='0'+dd
        } 
        if(mm<10){
            mm='0'+mm
        } 
        date = yyyy+'-'+mm+'-'+dd;

        document.getElementById("end_date").setAttribute("max", date)
        $("#end_date").val('');
    });

    $("#end_date").on("change",function(){
        var start_date = $("#start_date").val();
        if(start_date == null){
            alert('Please select start date first!')
        }
        var end_date = $(this).val();

        getStatistic(start_date, end_date)
    });

    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    if(start_date != null && end_date != null){
        getStatistic(start_date, end_date)
    }
    function getStatistic(start_date, end_date){
        $.ajax({
            url: '{{ route('karyawan.ajax-get-statistic') }}',
            type: "get", //send it through get method
            data: { 
                start_date: start_date, 
                end_date: end_date
            },
            success: function(response) {
                $('#total_attendance').text(response.total_attendance);
                $('#total_late').text(response.total_late);
                $('#total_early').text(response.total_early);
                $('#total_forget').text(response.total_forget);
                $('#total_work_hour').text(response.total_work_hour);
            },
            error: function(xhr) {
                //Do Something to handle error
            }
        });
    }

</script>
@endsection

@endsection
