@extends('layouts.administrator')

@section('title', 'Dashboard')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row m-t-10">
            <div class="col-sm-12">
                <div class="col-sm-12">
                    <div class="row white-box">
                        <div class="form-group" autocomplete="off">
                            <label class="col-md-12">Filter Date</label>
                            <div class="col-md-2">
                                <input type="text" id="filter_start" name="filter_start" class="form-control datepicker" id="from" placeholder="Start Date" autocomplete="off" />
                            </div>
                            <div class="col-md-2">
                                <input type="text" id="filter_end"  name="filter_end" class="form-control datepicker" id="to" placeholder="End Date" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <select id="filter_position" name="filter_position" class="form-control">
                                    <option value="" selected>- Position -</option>
                                    @foreach($position as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="filter_division" name="filter_division" class="form-control">
                                    <option value="" selected>- Division -</option>
                                    @foreach($division as $item)
                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select id="filter_branch" name="filter_branch" class="form-control">
                                    <option value="" selected>- Branch -</option>
                                    @foreach($cabang as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div id="filter-dashboard" class="btn btn-danger"><i class="fa fa-search" style="font-size: 20px"></i> Submit</div>
                            </div>
                        </div> 
                    </div>
                </div>
                
                <div class="white-box m-b-2">
                    <div class="box-title pull-left" style="text-transform: inherit;"><i class="fa fa-user m-r-5"></i> Employees Attendance</div>
                    <button class="btn btn-info pull-right datepicker"><i class="fa fa-table m-r-5"></i> {{ date('d F Y') }} </button>
                    <div class="clearfix"></div>

                    <div class="row row-in">
                        <div class="col-lg-2 col-sm-6 padding-bottom-10">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-info" style="font-size: 18px !important;padding-top: 18px;background: blue;">
                                        {{ total_karyawan() }}
                                    </span>
                                </li>
                                <li class="col-middle">
                                    <h4>Total Employee</h4>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 padding-bottom-10">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-success" style="font-size: 18px !important;padding-top: 18px;">
                                        {{ employee('active') }}
                                    </span>
                                </li>
                                <li class="col-middle">
                                    <h4>Present</h4>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 padding-bottom-10">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-danger" style="font-size: 18px !important;padding-top: 18px;">
                                        {{ total_karyawan() - employee('active') }}
                                    </span>
                                </li>
                                <li class="col-middle">
                                    <h4>Absent</h4>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 padding-bottom-10">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-warning" style="font-size: 18px !important;padding-top: 18px;">
                                        {{ employee('late-comers') }}
                                    </span>
                                </li>
                                <li class="col-middle">
                                    <h4>Late Comers</h4>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 padding-bottom-10">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-primary" style="font-size: 18px !important;padding-top: 18px;">
                                        {{ employee('on-leave') }}
                                    </span>
                                </li>
                                <li class="col-middle">
                                    <h4>Leave</h4>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-2 col-sm-6 padding-bottom-10">
                            <ul class="col-in">
                                <li>
                                    <span class="circle circle-md bg-default" style="font-size: 18px !important;padding-top: 18px;background: grey;">
                                        {{ employee_exit_this_month() }}
                                    </span>
                                </li>
                                <li class="col-middle">
                                    <h4>Exit This Month</h4>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div id="chart-1-parent" class="white-box" style="margin-bottom:25px;">
                    <div class="box-title pull-left" style="text-transform: inherit;"><i class="mdi mdi-chart-areaspline m-r-5"></i> Monthly Recruitments</div>
                    <!--button id="filter-monthly-join-resign" class="btn btn-xs btn-danger pull-right datepicker"><i class="mdi mdi-filter" style="font-size: 12px"></i> </button-->
                    <div class="clearfix"></div>
                    <canvas id="chart-1" style="height: 220px"></canvas>
                </div>
            </div>
            <div class="col-sm-6">
                <div id="chart-2-parent" class="white-box" style="margin-bottom:25px;">
                    <div class="box-title pull-left" style="text-transform: inherit;"><i class="mdi mdi-chart-line m-r-5"></i> Attrition Rate</div>
                    <!--button id="filter-attrition" class="btn btn-xs btn-info pull-right datepicker"><i class="mdi mdi-filter" style="font-size: 12px"></i></button-->
                    <div class="clearfix"></div>
                    <canvas id="chart-2" style="height: 220px"></canvas>
                </div>
            </div>         
            <div class="col-sm-6">
                <div class="white-box" style="margin-bottom:25px;">
                    <div class="box-title" style="text-transform: inherit;"><i class="mdi mdi-chart-pie m-r-5"></i> Employee Division</div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-sm-5">
                            <div id="pie-chart-1" style="height:300px;"></div>
                        </div>
                        <div class="col-sm-7 no-padding-left">
                            <div class="col-sm-12" style="margin-bottom: 20px;">
                                <h2 id="total-headcount" class="m-b-0 font-medium"></h2>
                                <h5 class="text-muted m-t-0">Total Division</h5>
                            </div>
                            <div id="color-division"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="white-box" style="margin-bottom:25px;">
                    <div class="box-title" style="text-transform: inherit;"><i class="mdi mdi-chart-arc m-r-5"></i> Employee Status</div>
                    <div class="row" style="padding-top: 20px;">
                        <div class="col-sm-5">
                            <div id="pie-chart-2" style="height:300px;"></div>
                        </div>
                        <div class="col-sm-7 no-padding-left">
                            <div class="col-sm-12" style="margin-bottom: 20px;">
                                <h2 id="total-status" class="m-b-0 font-medium"></h2>
                                <h5 class="text-muted m-t-0">Total Status</h5>
                            </div>
                            <div id="color-status"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6" style="margin-bottom:10px;">
                <div id="calendar2"></div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>

<!-- BEGIN MODAL -->
<div class="modal fade none-border" id="add-event">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>Add Note</strong></h4>
            </div>
            <div class="modal-body" id="add-event-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" id="tanggal" name="tanggal" class="form-control" placeholder="Tanggal" />
                            <input type="text" id="judul" name="judul" class="form-control" placeholder="Judul" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea id="catatan"  name="catatan" class="form-control" placeholder="Catatan"></textarea>
                        </div>
                    </div>
                    <br>
                    
                </div>
                
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button id="submit-note" class="btn btn-success save-event waves-effect waves-light">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
    .ct-series.ct-series-b .ct-area { fill: green; }
    .ct-series.ct-series-b .ct-line { stroke: green; }
    .ct-series.ct-series-b .ct-point { stroke: green; }
    .no-padding {
        padding: 0px !important;
    }
    .no-padding-left {
        padding-left: 0px !important;
    }
</style>
<link href="{{ asset('admin-css/plugins/bower_components/css-chart/css-chart.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/chartist-js/dist/chartist.min.js') }}"></script>
<link href="{{ asset('admin-css/plugins/bower_components/calendar/dist/fullcalendar.css') }}" rel="stylesheet" />
@section('js')
<script src="{{ asset('admin-css/plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('admin-css/plugins/bower_components/calendar/dist/cal-init.js') }} "></script>

<script type="text/javascript">
    


    $(document).ready(function () {
        var tahun = "<?php echo date('Y'); ?>";
        var filter_start = "<?php echo date('Y-')."01-01"; ?>";
        var filter_end = "<?php echo date('Y-')."12-31"; ?>";

        dataDashboard(filter_start, filter_end);
        // getUserActive();
        calendarDashboard();
        headcountDepartment();
        employeeStatus();
    });

    $('#submit-note').click(function(){
        var tanggal = $('#tanggal').val();
        var judul = $('#judul').val();
        var catatan = $('#catatan').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.store-note') }}',
            data: {'tanggal' : tanggal, 'judul' : judul, 'catatan' : catatan, '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (ret) {
                calendarDashboard();
                $("#add-event").modal("hide");
                window.location = "<?php echo route('administrator.dashboard'); ?>";
            }
        });
    });


    $('#filter-dashboard').click(function(){
        var filter_start = $('#filter_start').val();
        var filter_end = $('#filter_end').val();
        if(filter_start != '' && filter_end == ''){
            alert("End date cannot be empty if start date is filled");
        } else if(filter_start == '' && filter_end != ''){
            alert("Start date cannot be empty if end date is filled");
        } else if(filter_start != '' && filter_end != ''){
            if(filter_end < filter_start){
                alert("Date can't be backdate");
            } else {
                dataDashboard(filter_start, filter_end);
                headcountDepartment();
                employeeStatus();
            }
        } else {
            filter_start = "<?php echo date('Y-')."01-01"; ?>";
            filter_end = "<?php echo date('Y-')."12-31"; ?>";
            dataDashboard(filter_start, filter_end);
            headcountDepartment();
            employeeStatus();
        }
    });


    function dataDashboard(filter_start, filter_end){
        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.get-data-dashboard') }}',
            data: {
                'filter_start' : filter_start,
                'filter_end' : filter_end,
                'filter_position' : $('#filter_position').val(),
                'filter_division' : $('#filter_division').val(),
                'filter_branch' : $('#filter_branch').val(),
                '_token' : $("meta[name='csrf-token']").attr('content')
            },
            dataType: 'json',
            success: function (msg) {
                var hasil = JSON.parse(msg);
                var bulan_label = hasil['bulan_val'];
                var employee_active = hasil['employee_active'];
                var employee_resign = hasil['employee_resign'];
                var employee_join = hasil['employee_join'];
                var employee_end_contract = hasil['employee_end_contract'];
                var attrition = hasil['attrition'];

                var total = [];
                employee_resign.forEach((item, index) => {
                    total[index] = item + employee_join[index] + employee_end_contract[index]
                });

                filterMonthlyJoinResign(bulan_label, employee_active, employee_resign, employee_end_contract, employee_join, total);
                filterAttrition(bulan_label, attrition);

            }
        });
    }

    function filterMonthlyJoinResign(bulan_label, employee_active, employee_resign, employee_end_contract, employee_join, total){
        
        $('#chart-1').remove();
        
        $('#chart-1-parent').append('<canvas id="chart-1" style="height: 220px"></canvas>');

        var ctx = document.getElementById("chart-1").getContext("2d");

        Chart.register(ChartDataLabels);

        var data = {
            labels: bulan_label,
            datasets: [
                {
                    label: "Active Employee",
                    backgroundColor: "blue",
                    borderColor: "blue",
                    data: employee_active,
                    type: 'line',
                    datalabels: {
                        anchor: 'start', // remove this line to get label in middle of the bar
                        align: 'start',
                        labels: {
                            value: {
                                color: 'black'
                            }
                        }
                    }
                },
                {
                    label: "Monthly resignees",
                    backgroundColor: "#f05b4f",
                    data: employee_resign,
                    minBarLength: 10,
                    datalabels: {
                        // anchor: 'end', // remove this line to get label in middle of the bar
                        align: 'end',
                        labels: {
                            value: {
                                color: 'black'
                            }
                        }
                    }
                },
                {
                    label: "Monthly joinees",
                    backgroundColor: "green",
                    data: employee_join,
                    minBarLength: 10,
                    datalabels: {
                        // anchor: 'end', // remove this line to get label in middle of the bar
                        align: 'end',
                        labels: {
                            value: {
                                color: 'black'
                            }
                        }
                    }
                },
                {
                    label: "Monthly end contracts",
                    backgroundColor: "#f4c63d",
                    data: employee_end_contract,
                    minBarLength: 10,
                    datalabels: {
                        // anchor: 'end', // remove this line to get label in middle of the bar
                        align: 'end',
                        labels: {
                            value: {
                                color: 'black'
                            }
                        }
                    }
                }
            ]
        };

        var myBarChart = new Chart(ctx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: data,
            options: {
                barValueSpacing: 20,
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0
                        }
                    }]
                }
            }
        });
        
        // console.log(employee_join);
        // new Chartist.Line('#chart-1', {
        //     high: 100,
        //     labels: bulan_label,
        //     series: [
        //         employee_resign,
        //         employee_join,
        //         employee_end_contract,
        //         // total
        //     ],
        //     showlabel: true
        // }, {
        //     top: 0,
        //     low: 0,
        //     showPoint: true,
        //     height: 210,
        //     fullWidth: true,
        // /*    plugins: [
        //         Chartist.plugins.tooltip()
        //     ],  */
        //     axisY: {
        //         labelInterpolationFnc: function (value) {
        //             return (value / 1);
        //         }
        //     },
        //     showArea: true
        // });
    }
    
    
  
    function filterAttrition(bulan_label, attrition){
        
        $('#chart-2').remove();
        
        $('#chart-2-parent').append('<canvas id="chart-2" style="height: 220px"></canvas>');
 
        var ctx = document.getElementById("chart-2").getContext("2d");

        Chart.register(ChartDataLabels);

        var data = {
            labels: bulan_label,
            datasets: [
                {
                    label: "Attrition Rate",
                    backgroundColor: "#f05b4f",
                    borderColor: "#f05b4f",
                    data: attrition,
                    type: 'line'
                }
            ]
        };

        var myBarChart = new Chart(ctx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: data,
            options: {
                plugins: {
                    datalabels: {
                        anchor: 'end', // remove this line to get label in middle of the bar
                        align: 'end',
                        formatter: (val) => (`${val}%`),
                        labels: {
                            value: {
                                color: 'black'
                            }
                        }
                    }
                },
                barValueSpacing: 20,
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0
                        }
                    }]
                }
            }
        });

        // new Chartist.Line('#chart-2', {
        //     high: 100,
        //     labels: bulan_label,
        //     series: [
        //         attrition
        //     ]
        // }, {
        //     top: 0,
        //     low: 1,
        //     showPoint: true,
        //     height: 210,
        //     fullWidth: true,
        // /*    plugins: [
        //         Chartist.plugins.tooltip()
        //     ],  */
        //     axisY: {
        //         labelInterpolationFnc: function (value) {
        //             return (value / 1) + '%';
        //         }
        //     },
        //     showArea: true
        // });
    }

    function calendarDashboard(){
        $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-libur-nasional') }}',
            dataType: 'json',
            success: function (msg) {
                var result = JSON.parse(msg);
                var startdate = result['tanggal'];
                var enddate = result['tanggal'];
                var title = result['keterangan'];
                
                var events = [];
                var coolor = [];
                for(var i = 0; i < startdate.length; i++) 
                {
                    events.push( {
                            title: title[i], 
                            start: startdate[i], 
                            end: enddate[i]
                    });
                }
                coolor.push('#7bcef3');

                $.ajax({
                    type: 'GET',
                    url: '{{ route('ajax.get-note') }}',
                    dataType: 'json',
                    success: function (resultnote) {
                        var resultnote = JSON.parse(resultnote);
                        var startdatenote = resultnote['tanggal'];
                        var enddatenote = resultnote['tanggal'];
                        var titlenote = resultnote['keterangan'];
                        var color = ['#ff7676', '#2cabe3', '#53e69d', '#7bcef3', '#ff63f7', '#fbfcb0', '#ffca60', '#60fff1', '#847bfc', '#ff9696', '#2e7a3c', '#87197c'];
                    
                        
                        for(var j = 0; j < startdatenote.length; j++) 
                        {
                            events.push({
                                title: titlenote[j], 
                                start: startdatenote[j], 
                                end: startdatenote[j]
                            });
                        }
                        coolor.push('#2cabe3');

                        $('#calendar2').fullCalendar({
                            dayClick: function(date, allDay, jsEvent, view) {
                            /*  if (allDay) {
                                    $('#calendar2')
                                        .fullCalendar('changeView', 'basicDay')
                                        .fullCalendar('gotoDate',
                                            date.getFullYear(), date.getMonth(), date.getDate());
                                }   */

                                var check = $(this).find('i.checkbox');
                                check.toggleClass('marked');
                            //  $(this).css('background-color', '#4f92ff');
                                $("#add-event").modal("show");
                                
                                var tanggalnote = date.format('YYYY-MM-D');
                                $.ajax({
                                    type: 'POST',
                                    url: '{{ route('ajax.get-detail-note') }}',
                                    data: {'tanggal' : tanggalnote, '_token' : $("meta[name='csrf-token']").attr('content')},
                                    dataType: 'json',
                                    success: function (detailnote) {
                                        var resultdetailnote = JSON.parse(detailnote);
                                        
                                        $("#judul").val(resultdetailnote['judul']);
                                        $("#catatan").val(resultdetailnote['catatan']);
                                        $("#tanggal").val(date.format('YYYY-MM-D'));
                                        
                                    }
                                });
                            },

                            dayRender: function(date, cell) {
                                var check = document.createElement('i');
                                check.classList.add('checkbox');
                                cell.append(check);
                                $('.fc-sat, .fc-sun').css('background-color', '#e6eaf2');
                            },
                                       
                            events: events,
                            eventColor: coolor[0],
                            height: 410
                        });
                    }
                });
            }
        });
    }

    function getUserActive(){
        $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-user-active') }}',
            dataType: 'json',
            success: function (msg) {
                $('#user-active').html(msg);
                console.log(msg);
            }
        });
        // var cekUser = setTimeout("getUserActive()", 5000);
    }

    function headcountDepartment(){
        $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-headcount-department') }}',
            dataType: 'json',
            data: {
                'filter_position' : $('#filter_position').val(),
                'filter_division' : $('#filter_division').val(),
                'filter_branch' : $('#filter_branch').val(),
                '_token' : $("meta[name='csrf-token']").attr('content')
            },
            success: function (response) {
                let namedivision = response.namedivision;
                let jumlahperdivisi = response.jumlahperdivisi;
                let total_headcount = 0;

                $('#color-division').html('');

                data = [];
                allDiv = '';
                for(var i=0; i<namedivision.length; i++){
                    total_headcount += jumlahperdivisi[i];
                    data.push( {
                        label: namedivision[i],
                        value: jumlahperdivisi[i]
                    })
                    var colors =  ['#ff7676', '#2cabe3', '#53e69d', '#7bcef3', '#ff63f7', '#fbfcb0', '#ffca60', '#60fff1', '#847bfc', '#ff9696', '#2e7a3c', '#87197c'];
                    var div1 = '<div class="row" style="padding-left: 30px"><div class="col-md-6 no-padding"><span class="btn-xs col-md-1 no-padding" style="cursor: default; background: ' + colors[i] +' ">&nbsp;</span><p class="col-md-11" style="padding-left: 10px;"> ' + namedivision[i] +'</p></div>';
                    var div2 = '<div class="col-md-6 no-padding"><span class="btn-xs col-md-1 no-padding" style="cursor: default; background: ' + colors[i] +' ">&nbsp;</span><p class="col-md-11" style="padding-left: 10px;"> ' + namedivision[i] +'</p></div></div>';
                    var number = i % 2;
                    if(number == '0'){
                        allDiv += div1;
                    }else if(number == Math.round(number)){
                        allDiv += div2;
                    }else{
                        console.log("error");
                    }
                }
                $('#color-division').append(allDiv);
                $("#total-headcount").html(total_headcount);
                
                $("#pie-chart-1").empty();
                Morris.Donut({
                    element: 'pie-chart-1',
                    data: data,
                    resize: true,
                    colors: ['#ff7676', '#2cabe3', '#53e69d', '#7bcef3', '#ff63f7', '#fbfcb0', '#ffca60', '#60fff1', '#847bfc', '#ff9696', '#2e7a3c', '#87197c']
                });
            }
        });
        
    }

    function employeeStatus(){
        $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-employee-status') }}',
            dataType: 'json',
            data: {
                'filter_position' : $('#filter_position').val(),
                'filter_division' : $('#filter_division').val(),
                'filter_branch' : $('#filter_branch').val(),
                '_token' : $("meta[name='csrf-token']").attr('content')
            },
            success: function (response) {
                let name = response.data;
                let total_status = 0;

                $('#color-status').html('');

                data = [];
                allStatus = '';
                $.each(name, function(index, value) {
                    total_status += parseInt(value);
                    data.push( {
                        label: index,
                        value: value
                    })
                    var colors =  ['#ff7676', '#2cabe3', '#53e69d', '#7bcef3', '#ff63f7', '#fbfcb0', '#ffca60', '#60fff1', '#847bfc', '#ff9696', '#2e7a3c', '#87197c'];
                    var div1 = '<div class="row" style="padding-left: 30px"><div class="col-md-6 no-padding"><span class="btn-xs col-md-1 no-padding" style="cursor: default; background: ' + colors[data.length - 1] +' ">&nbsp;</span><p class="col-md-11" style="padding-left: 10px;"> ' + index +'</p></div>';
                    var div2 = '<div class="col-md-6 no-padding"><span class="btn-xs col-md-1 no-padding" style="cursor: default; background: ' + colors[data.length - 1] +' ">&nbsp;</span><p class="col-md-11" style="padding-left: 10px;"> ' + index +'</p></div></div>';
                    var number = (data.length - 1) % 2;
                    if(number == '0'){
                        allStatus += div1;
                    }else if(number == Math.round(number)){
                        allStatus += div2;
                    }else{
                        console.log("error");
                    }
                });
                $('#color-status').append(allStatus);
                $("#total-status").html(total_status);
                
                $("#pie-chart-2").empty();
                Morris.Donut({
                    element: 'pie-chart-2',
                    data: data,
                    resize: true,
                    colors: ['#ff7676', '#2cabe3', '#53e69d', '#7bcef3', '#ff63f7', '#fbfcb0', '#ffca60', '#60fff1', '#847bfc', '#ff9696', '#2e7a3c', '#87197c']
                });
            }
        });
        
    }
    
</script>
@endsection
@endsection
