@extends('layouts.karyawan')

@section('title', 'Recruitment Application')

@section('sidebar')

@endsection

@section('content')
    <link href="{{ asset('js/recruitment-request/general.css') }}" rel="stylesheet">
    <style>

    </style>
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Recruitment Application</h4> </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    {{--<a href="{{ route('Manager.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>--}}
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Recruitment Application</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">
                        <div class="table-responsive">
                            <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="30" class="text-center">NO</th>
                                    <th width="30%">POSITION</th>
                                    <th>BRANCH</th>
                                    <th>APPLICATION DATE</th>
                                    <th>CURRENT PHASE</th>
                                    <th width="10%">STATUS</th>
                                    <th width="10%">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- ============================================================== -->
        </div>
        <!-- BEGIN MODAL -->
        <div  class="modal fade none-border" id="modal-detail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Application History</strong></h4>
                    </div>
                    <form id="form">
                        <div class="modal-body" id="modal-add-body">
                            <div class="row" style="border-bottom: 1px solid #c4c4c4; margin: 0 20px 0 20px; padding: 0 0 -50px 0">
                                <div class="form-group col-xs-9">
                                    <table class="table-detail-apply" width="100%">
                                        <tr>
                                            <td width="30%">Name</td>
                                            <td width="30"> : </td>
                                            <td id="app_name">Baso Ahmad Muflih</td>
                                        </tr>
                                        <tr>
                                            <td>Position</td>
                                            <td> : </td>
                                            <td id="app_position">Software Engineer - IT</td>
                                        </tr>
                                        <tr>
                                            <td>Branch</td>
                                            <td> : </td>
                                            <td id="app_branch">TB Simatupang Office</td>
                                        </tr>
                                        <tr>
                                            <td>Date Request</td>
                                            <td> : </td>
                                            <td id="app_date">2 July 2020</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group col-xs-3">
                                    <img id="app_photo" src="{{ asset('admin-css/images/user.png') }}" class="img-circle pull-right" width="80px" height="80px" style="margin-top: 8px" alt="Cinque Terre">
                                </div>
                            </div>
                            <div class="form-group col-xs-12" id="histories">

                                {{--<div class="row" style="padding: 0px;">--}}
                                    {{--<div class="col-xs-12">--}}
                                        {{--<div class="col-xs-1">--}}
                                            {{--<div class="dot-approved">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-11">--}}
                                            {{--<b>Technical Exam</b>--}}
                                            {{--<span class="pull-right">Last Edited : 20/02/2019</span>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-12 status-detail" style="margin-top: -4px">--}}
                                            {{--<table class="table-status" width="100%">--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Status</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>Approved</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td colspan="3"><b>Details</b></td>--}}
                                                {{--</tr>--}}
                                            {{--</table>--}}
                                            {{--<table class="table-status" style="margin-top: -6px" width="100%">--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Test Schedule</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>8 July 2019 10:00</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Test Result</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>He is good b dassad dsa ass ad asdas dsad sad  asd sadas d asda sds ad asdas d das asd asdda s asd asd ads ds as as dsa assad</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Remark</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>90</td>--}}
                                                {{--</tr>--}}
                                            {{--</table>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="row" style="padding: 0px;">--}}
                                    {{--<div class="col-xs-12">--}}
                                        {{--<div class="col-xs-1">--}}
                                            {{--<div class="dot-rejected">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-11">--}}
                                            {{--<b>Interview HR & User</b>--}}
                                            {{--<span class="pull-right">Last Edited : 20/02/2019</span>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-xs-12 status-detail" style="margin-top: -4px">--}}
                                            {{--<table class="table-status" width="100%">--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Status</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>Rejected</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td colspan="3"><b>Details</b></td>--}}
                                                {{--</tr>--}}
                                            {{--</table>--}}
                                            {{--<table class="table-status" style="margin-top: -6px" width="100%">--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Interview Schedule</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>8 July 2019 10:00</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Interview Location</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>Jl TB Simatupang</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Interview Result</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>Not really good</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td width="25%">Remark</td>--}}
                                                    {{--<td width="20"> : </td>--}}
                                                    {{--<td>50</td>--}}
                                                {{--</tr>--}}
                                            {{--</table>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
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
        loadData();
        function loadData(){
            $('#mytable').DataTable().destroy();
            $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
            {
                return {
                    "iStart": oSettings._iDisplayStart,
                    "iEnd": oSettings.fnDisplayEnd(),
                    "iLength": oSettings._iDisplayLength,
                    "iTotal": oSettings.fnRecordsTotal(),
                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                    "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                    "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };
            t = $("#mytable").DataTable({
                initComplete: function() {
                    var api = this.api();
                    $('#mytable_filter input')
                        .off('.DT')
                        .on('keyup.DT', function(e) {
                            if (e.keyCode == 13) {
                                api.search(this.value).draw();
                            }
                        });
                },
                oLanguage: {
                    sProcessing: "loading..."
                },
                oSearch: { "bSmart": false, "bRegex": true },
                processing: true,
                serverSide: true,
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {"url": "{{ route('ajax.table.recruitment_application') }}", "type": "GET"},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "job_position"},
                    { "data": "branch","name":"c.name"},
                    { "data": "application_date", searchable:false},
                    { "data": "current_phase","name":"rp.name"},
                    { "data": "status","name":"rs.status",
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            if (data == 0) {
                                label = 'btn btn-warning btn-xs';
                                st = 'Waiting';
                            } else if (data == 1)  {
                                label = 'btn btn-success btn-xs';
                                st = 'Approved';
                            }
                            else if (data == 2)  {
                                label = 'btn btn-default btn-xs';
                                st = 'Shortlisted';
                            }
                            else if (data == 3)  {
                                label = 'btn btn-danger btn-xs';
                                st = 'Rejected';
                            }
                            data = "<label  class='"+label+ "'> " + st + "</label ><br>";
                            return data;
                        }
                    },
                    { "data": 'action', searchable: false,
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            var id = row['id'];
                            data = "<button type='button' onclick='detail("+id+")' class='btn btn-xs btn-info'><i class='fa fa-search-plus'></i> Detail</button ><br>";
                            return data;
                        }
                    }
                ],
                order: [[1, 'asc']],
                rowCallback: function(row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                }
            });
        };
        function detail(id) {
            $.ajax({
                url: "/karyawan/recruitment_application/detail-history-internal/"+id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#app_name').html(data.application.name);
                    $('#app_position').html(data.application.position);
                    $('#app_branch').html(data.application.branch);
                    $('#app_date').html(data.application.date_request);
                    $("#app_photo").attr("src",data.application.photo);
                    console.log(data);
                    var history_html = '';
                    for(var i = 0; i < data.histories.length; i++){
                        var history = data.histories[i];
                        switch (history.status) {
                            case '0' :
                                status = 'dot-waiting';
                                break;
                            case '1' :
                                status = 'dot-approved';
                                break;
                            case '2' :
                                status = 'dot-shortlisted';
                                break;
                            case '3' :
                                status = 'dot-rejected';
                                break;
                            default:
                                status = '';
                        }

                        history_html += '<div class="row" style="padding: 0px;">' +
                        '                                    <div class="col-xs-12">' +
                        '                                        <div class="col-xs-1">' +
                        '                                            <div class="'+status+'">' +
                        '                                            </div>' +
                        '                                        </div>' +
                        '                                        <div class="col-xs-11">' +
                        '                                            <b>'+history.phase+'</b>' +
                        '                                            <span class="pull-right">Last Edited : '+history.last_edit+'</span>' +
                        '                                        </div>' +
                        '                                        <div class="col-xs-12 status-detail" style="margin-top: -4px">' +
                        '                                            <table class="table-status" width="100%">' +
                        '                                                <tr>' +
                        '                                                    <td width="25%">Status</td>' +
                        '                                                    <td width="20"> : </td>' +
                        '                                                    <td>'+history.status_name+'</td>' +
                        '                                                </tr>' +
                        '                                                <tr>' +
                        '                                                    <td colspan="3"><b>Details</b></td>' +
                        '                                                </tr>' +
                        '                                            </table>' +
                        '                                            <table class="table-status" style="margin-top: -6px" width="100%">';
                        for(var j = 0; j < history.details.length; j++){
                            var detail = history.details[j];
                            if(detail.type == 'text'){
                                history_html+= '<tr>' +
                                    '                                                    <td width="25%">'+detail.title+'</td>' +
                                    '                                                    <td width="20"> : </td>' +
                                    '                                                    <td>'+detail.data+'</td>' +
                                    '                                                </tr>'
                            }
                            else if(detail.type == 'url'){
                                history_html+= '<tr>' +
                                    '                                                    <td colspan="3"><a href="'+detail.data+'" target="_blank">'+detail.title+'</a></td>' +
                                    '                                                </tr>'
                            }
                            else if(detail.type == 'collapse'){
                                history_html+= '<tr>' +
                                    '                                                    <td colspan="3">' +
                                    '                                                        <a href="#" data-toggle="collapse" data-target="#collapse'+i+''+j+'" aria-expanded="false" aria-controls="collapse'+i+''+j+'">'+detail.title+'</a>' +
                                    '                                                        <div class="collapse" id="collapse'+i+''+j+'">' +
                                    '                                                            <div class="well">' +
                                    '                                                                '+detail.data+
                                    '                                                            </div>' +
                                    '                                                        </div>' +
                                    '                                                    </td>' +
                                    '                                                </tr>'
                            }
                        }
                        history_html += '                               </table>' +
                            '                                        </div>' +
                            '                                    </div>' +
                            '                                </div>'
                    }
                    $('#histories').html(history_html);
                    $("#modal-detail").modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    $("#btn_apply").attr("disabled", false);
                }
            });
            return false;

        }
        function reload_table()
        {
            $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
        }
    </script>
@endsection
@endsection
