@extends('layouts.administrator')

@section('title', 'Recruitment Detail')

@section('content')

    <link href="{{ asset('js/recruitment-request/general.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<style>
    table {
        border-collapse: separate;
        border-spacing: 0 0.5em;
    }
    td{
        vertical-align: top;
        white-space: normal;
    }
    .multiline{
        white-space: normal;
    }
    .scroll-horizontal{
        overflow-x: auto;
        width: 100%;
    }
    .scroll-horizontal{
        white-space: nowrap;
    }

</style>
<link href="{{ asset('js/recruitment/general.css') }}" rel="stylesheet">


@php($application = getApplicants($recruitment->id))
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-xs-12">
                <h4 class="page-title">Recruitment Detail</h4>
                <div class="col-xs-11 col-lg-11">
                    <table>
                        <tr>
                            <td>Request Number</td>
                            <td width="30" class="text-center"> : </td>
                            <td>{{$recruitment->request_number}}</td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td width="30" class="text-center"> : </td>
                            <td>{{$recruitment->job_position}}</td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td width="30" class="text-center"> : </td>
                            <td>{{$recruitment->branch->name}}</td>
                        </tr>
                        <tr>
                            <td>Headcount</td>
                            <td width="30" class="text-center"> : </td>
                            <td>{{$recruitment->headcount}}</td>
                        </tr>
                        <tr>
                            <td>Applicants</td>
                            <td width="30" class="text-center"> : </td>
                            <td>{{$application['all']}}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-xs-1 col-lg-1">
                    <a href="{{ route('administrator.recruitment-request.edit', $recruitment->id) }}" class="pull-right"><b>Detail..</b></a>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div id="showSetting">
                    <div class="white-box p-l-1 p-r-1">
                        <ul class="nav nav-tabs" role="tablist">
                            @if($recruitment->internal)
                            <li id="tab_internal" role="presentation" class=""><a href="#internal" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="true"> Internal ({{$application['internal']}})</a></li>
                            @endif
                            @if($recruitment->external)
                            <li id="tab_external" role="presentation" class=""><a href="#external" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"> External ({{$application['external']}})</a></li>
                            @endif
                                <button type="button" class="btn btn-sm btn-info pull-right" onclick="exportData()">Export</button>
                        </ul>

                        <div class="tab-content" style="background-color: #F2F2F2; margin: 0px; padding: 16px;">
                            @if($recruitment->internal)
                            <div role="tabpanel" class="tab-pane fade scroll-horizontal" id="internal">
                                <div class="container-fluid" id="internal_container">
                                    {{--<div class="panel panel-primary kanban-col" style="border-radius: 8px">--}}
                                        {{--<div class="panel-heading text-center" style="border-top-left-radius: 8px;border-top-right-radius: 8px">--}}
                                            {{--SCREENING (2)--}}
                                        {{--</div>--}}
                                        {{--<div class="panel-body" style="padding: 0; margin: 8px">--}}
                                            {{--<div id="SCREENING" class="kanban-centered">--}}
                                                {{--<article class="kanban-entry board-waiting" id="item2" draggable="false">--}}
                                                    {{--<div class="kanban-entry-inner">--}}
                                                        {{--<div class="kanban-label">--}}
                                                            {{--<div class="row">--}}
                                                                {{--<div class="col-xs-10">--}}
                                                                    {{--<table width="100%">--}}
                                                                        {{--<tr>--}}
                                                                            {{--<td><p>Applicant</p></td>--}}
                                                                            {{--<td class="text-center">:</td>--}}
                                                                            {{--<td>Ujang</td>--}}
                                                                        {{--</tr>--}}
                                                                        {{--<tr>--}}
                                                                            {{--<td>Date Apply</td>--}}
                                                                            {{--<td width="20" class="text-center">:</td>--}}
                                                                            {{--<td>20 Desember 2019</td>--}}
                                                                        {{--</tr>--}}
                                                                        {{--<tr>--}}
                                                                            {{--<td>Status</td>--}}
                                                                            {{--<td class="text-center">:</td>--}}
                                                                            {{--<td>Waiting</td>--}}
                                                                        {{--</tr>--}}
                                                                    {{--</table>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="col-xs-2">--}}
                                                                    {{--<i class="fa fa-bars pull-right" style="cursor: pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>--}}
                                                                    {{--<div class="dropdown-menu dropdown-menu-right">--}}
                                                                        {{--<li><a class="dropdown-item" href="#">Edit</a></li>--}}
                                                                        {{--<li><a class="dropdown-item" href="#">Move to board..</a></li>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="col-xs-12">--}}
                                                                    {{--<p style="width: 100%; margin-top: 8px" class="text-center">--}}
                                                                        {{--<a href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">--}}
                                                                            {{--Details..--}}
                                                                        {{--</a>--}}
                                                                    {{--</p>--}}
                                                                    {{--<div class="collapse" id="collapseExample" style="width: 100%;">--}}
                                                                        {{--<table width="100%">--}}
                                                                            {{--<tr>--}}
                                                                                {{--<td><p>Interview Location</p></td>--}}
                                                                                {{--<td width="20" class="text-center">:</td>--}}
                                                                                {{--<td class="multiline">Metropolitan tower lt 13 wawa awaawaw awwaaw</td>--}}
                                                                            {{--</tr>--}}
                                                                            {{--<tr>--}}
                                                                                {{--<td>Date Apply</td>--}}
                                                                                {{--<td width="20" class="text-center">:</td>--}}
                                                                                {{--<td class="multiline">20 Desember 2019</td>--}}
                                                                            {{--</tr>--}}
                                                                        {{--</table>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</article>--}}
                                                {{--<article class="kanban-entry roved" id="item2" draggable="false">--}}
                                                    {{--<div class="kanban-entry-inner">--}}
                                                        {{--<div class="kanban-label">--}}
                                                            {{--<div class="row">--}}
                                                                {{--<div class="col-xs-10">--}}
                                                                    {{--<table width="100%">--}}
                                                                        {{--<tr>--}}
                                                                            {{--<td><p>Applicant</p></td>--}}
                                                                            {{--<td class="text-center">:</td>--}}
                                                                            {{--<td>Baso Ahmad</td>--}}
                                                                        {{--</tr>--}}
                                                                        {{--<tr>--}}
                                                                            {{--<td>Date Apply</td>--}}
                                                                            {{--<td width="20" class="text-center">:</td>--}}
                                                                            {{--<td>20 Desember 2019</td>--}}
                                                                        {{--</tr>--}}
                                                                        {{--<tr>--}}
                                                                            {{--<td>Status</td>--}}
                                                                            {{--<td class="text-center">:</td>--}}
                                                                            {{--<td>Approved</td>--}}
                                                                        {{--</tr>--}}
                                                                    {{--</table>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="col-xs-2">--}}
                                                                    {{--<i class="fa fa-bars pull-right" style="cursor: pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>--}}
                                                                    {{--<div class="dropdown-menu dropdown-menu-right">--}}
                                                                        {{--<li><a class="dropdown-item" href="#">Edit</a></li>--}}
                                                                        {{--<li><a class="dropdown-item" href="#">Move to board..</a></li>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                                {{--<div class="col-xs-12">--}}
                                                                    {{--<p style="width: 100%; margin-top: 8px" class="text-center">--}}
                                                                        {{--<a href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false" aria-controls="collapseExample">--}}
                                                                            {{--Details..--}}
                                                                        {{--</a>--}}
                                                                    {{--</p>--}}
                                                                    {{--<div class="collapse" id="collapseExample2" style="width: 100%;">--}}
                                                                        {{--<table width="100%">--}}
                                                                            {{--<tr>--}}
                                                                                {{--<td colspan="3">--}}
                                                                                    {{--<a href="javascript:void(0);">Download CV</a>--}}
                                                                                {{--</td>--}}
                                                                            {{--</tr>--}}
                                                                            {{--<tr>--}}
                                                                                {{--<td colspan="3">--}}
                                                                                    {{--<a  href="javascript:void(0);" data-toggle="collapse" data-target="#collapseD1" aria-expanded="false" aria-controls="collapseD1">Show Cover Letter</a>--}}
                                                                                    {{--<div class="collapse" id="collapseD1">--}}
                                                                                        {{--<div class="well" style="white-space: normal;">--}}
                                                                                        {{--</div>--}}
                                                                                    {{--</div>--}}
                                                                                {{--</td>--}}
                                                                            {{--</tr>--}}
                                                                        {{--</table>--}}
                                                                    {{--</div>--}}
                                                                {{--</div>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</article>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                            </div>

                                <!--sütun bitiş-->
                            </div>
                            @endif
                            @if($recruitment->external)
                            <div role="tabpanel" class="tab-pane fade" id="external">
                                <div class="container-fluid" id="external_container">

                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>                
        </div>
    </div>

    <div class="modal fade none-border" id="modal-assign">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Assign To</strong></h4>
                </div>
                <div class="modal-body" id="modal-assign-body">
                    <div class="table-responsive">
                        <input type="hidden" id="idShift">
                        <input type="text" class="form-control" id="searchUser">
                        <br />
                        <table class="table" id="tableList">
                            <tr>
                                <th><input type="checkbox" id="checkTopUser"></th>
                                <th>NO</th>
                                <th>NIK</th>
                                <th>NAME</th>
                                <th>POSITION</th>
                                <th>DIVISION</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Application Edit</strong></h4>
                </div>
                <form id="form_edit">
                    <div class="modal-body" id="modal-edit-body">
                        <div class="row" style="border-bottom: 1px solid #c4c4c4; margin: 0 20px 0 20px; padding: 0 0 -50px 0">
                            <div class="form-group col-xs-9">
                                <table class="table-detail-apply" width="100%">
                                    <tr>
                                        <td width="30%">Name</td>
                                        <td width="30"> : </td>
                                        <td id="app_name_edit"></td>
                                    </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td> : </td>
                                        <td id="app_position_edit">Software Engineer - IT</td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td> : </td>
                                        <td id="app_branch_edit">TB Simatupang Office</td>
                                    </tr>
                                    <tr>
                                        <td>Date Request</td>
                                        <td> : </td>
                                        <td id="app_date_edit">2 July 2020</td>
                                    </tr>
                                    <tr>
                                        <td>Phase</td>
                                        <td> : </td>
                                        <td id="app_phase_edit">Screening</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group col-xs-3">
                                <img id="app_photo_edit" src="{{ asset('admin-css/images/user.png') }}" class="img-circle pull-right" width="80px" height="80px" style="margin-top: 8px" alt="Cinque Terre">
                            </div>
                        </div>
                        <div class="col-xs-12" id="edit_form">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-move">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Move Board</strong></h4>
                </div>
                <form id="form_move">
                    <div class="modal-body" id="modal-add-body">
                        <div class="row" style="border-bottom: 1px solid #c4c4c4; margin: 0 20px 0 20px; padding: 0 0 -50px 0">
                            <div class="form-group col-xs-9">
                                <table class="table-detail-apply" width="100%">
                                    <tr>
                                        <td width="30%">Name</td>
                                        <td width="30"> : </td>
                                        <td id="app_name_m"></td>
                                    </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td> : </td>
                                        <td id="app_position_m"></td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td> : </td>
                                        <td id="app_branch_m"></td>
                                    </tr>
                                    <tr>
                                        <td>Date Request</td>
                                        <td> : </td>
                                        <td id="app_date_m"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="form-group col-xs-3">
                                <img id="app_photo" src="{{ asset('admin-css/images/user.png') }}" class="img-circle pull-right" width="80px" height="80px" style="margin-top: 8px" alt="Cinque Terre">
                            </div>
                        </div>

                        <div class="form-group col-xs-12">
                            <br>
                            <div class="row">
                                    @csrf
                                    <input type="hidden" name="application_id" id="application_id">
                                    <div class="col-xs-5">
                                        <div class="form-group col-md-12">
                                            <label>Current Board</label>
                                            <div>
                                                <select class="form-control" id="current_board" style="width: 100%">
                                                    <option value="0">- Pilih Posisi - </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-1 text-center">
                                        <br>
                                        TO
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="form-group col-md-12">
                                            <label>Next Board</label>
                                            <div>
                                                <select name="next_board" class="form-control" id="next_board" style="width: 100%">

                                                </select>
                                            </div>
                                        </div>


                            </div>
                        </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-onboard">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Application Onboard</strong></h4>
                </div>
                <form id="form_onboard">
                    <input type="hidden" id="board_external_id" name="external_application_id">
                    <div class="modal-body" id="modal-edit-body">
                        <div class="col-xs-12" id="onboard_form">
                            <div class="form-group col-md-12">
                                <label>New Employee Name</label>
                                <div>
                                    <input type="text" id="board_name" value="" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Position</label>
                                <div>
                                    <input type="text" id="board_position"  value="" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Branch</label>
                                <div>
                                    <input type="text" id="board_branch" value="" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Onboarding date</label>
                                <div>
                                    <input type="text" id="board_date" value="" class="form-control " disabled>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Facilities</label>
                                <div id="facilities">
                                    {{--<div class="col-md-4">--}}
                                        {{--<input type="checkbox" class="form-check-input" name="facilities[]" value="1"> Card--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4">--}}
                                        {{--<input type="checkbox" class="form-check-input" name="facilities[]" value="2"> Vechicle--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4">--}}
                                        {{--<input type="checkbox" class="form-check-input" name="facilities[]" value="3"> Email--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4">--}}
                                        {{--<input type="checkbox" class="form-check-input" name="facilities[]" value="4"> Workstation--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4">--}}
                                        {{--<input type="checkbox" class="form-check-input" name="facilities[]" value="5"> Printer--}}
                                    {{--</div>--}}
                                    {{--<div class="col-md-4">--}}
                                        {{--<input type="checkbox" class="form-check-input" name="facilities[]" value="6"> Laptop--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('layouts.footer')
</div>

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>

        @if($recruitment->internal)
            $("#tab_internal").addClass("active");
            $("#internal").addClass("active in");
        @else
            $("#tab_external").addClass("active");
            $("#external").addClass("active in");
        @endif

        var interviewers = [];
        @foreach($recruitment->interviewers as $interviewer)
         interviewers.push({'id':'{{$interviewer->user->id}}', 'name':'{{$interviewer->user->name}}' });
        @endforeach
    </script>
    <script>
        getInternalData();
        getExternalData();
        function getInternalData() {
            var id = '{{$recruitment->id}}';
            $.ajax({
                url: "/administrator/recruitment/detail-internal/"+id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    fillBoard(data,1,'internal_container');

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
        function getExternalData() {
            var id = '{{$recruitment->id}}';
            $.ajax({
                url: "/administrator/recruitment/detail-external/"+id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    fillBoard(data,2,'external_container');
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

        function fillBoard(data, type,container_id) {
            var panel_html = '';
            for(var i = 0; i < data.length; i++){
                var applications = data[i].applications;
                panel_html += '<div class="panel panel-primary kanban-col" style="border-radius: 8px">' +
                    '                                        <div class="panel-heading text-center" style="border-top-left-radius: 8px;border-top-right-radius: 8px">' +
                    '                                            '+(i+1)+'. '+data[i].name+' ('+applications.length+')' +
                    '                                        </div>' +
                    '                                        <div class="panel-body" style="padding: 0; margin: 8px">' +
                    '                                            <div id="" class="kanban-centered">';
                for(var j = 0; j < applications.length; j++){
                    var app = applications[j];
                    panel_html += '<article class="kanban-entry board board-'+app.status_name.toLowerCase()+'" id="item'+app.id+'" draggable="false">' +
                        '                                                    <div class="kanban-entry-inner">' +
                        '                                                        <div class="kanban-label">' +
                        '                                                            <div class="row">' +
                        '                                                                <div class="col-xs-10">' +
                        '                                                                    <table width="100%">' +
                        '                                                                        <tr>' +
                        '                                                                            <td><p>Applicant</p></td>' +
                        '                                                                            <td class="text-center">:</td>' +
                        '                                                                            <td>'+app.applicant+'</td>' +
                        '                                                                        </tr>' +
                        '                                                                        <tr>' +
                        '                                                                            <td>Date Apply</td>' +
                        '                                                                            <td width="20" class="text-center">:</td>' +
                        '                                                                            <td>'+app.created_at+'</td>' +
                        '                                                                        </tr>' +
                        '                                                                        <tr>' +
                        '                                                                            <td>Status</td>' +
                        '                                                                            <td class="text-center">:</td>' +
                        '                                                                            <td>'+app.status_name+'</td>' +
                        '                                                                        </tr>' +
                        '                                                                    </table>' +
                        '                                                                </div>';
                    var option = '<li><a class="dropdown-item" onclick="editBoard('+app.history.id+')">Edit</a></li>';
                    if(type==1)
                        option += '<li><a class="dropdown-item" onclick="detailHistoryInternal('+app.internal_app_id+')">Detail history</a></li>';
                    else
                        option += '<li><a class="dropdown-item" onclick="detailHistoryExternal('+app.external_app_id+')">Detail history</a></li>';
                    if(data[i].id == 3 || data[i].id == 8){
                        option += '<li><a class="dropdown-item" onclick="sendEmail('+app.id+',3)">Email Interviewers</a></li>';
                    }
                    if(app.status == 1 && app.next_boards.length > 0){

                        option += '<li><a class="dropdown-item" onclick="moveBoard('+app.id+')">Move to board..</a></li>';
                    }
                    if(data[i].id == 13 && app.status == 1){
                        option += '<li><a class="dropdown-item" onclick="onboard('+app.external_app_id+')">Boarding Status</a></li>';
                    }

                    panel_html += '<div class="col-xs-2">' +
                        '                                                                    <i class="fa fa-bars pull-right" style="cursor: pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>' +
                        '                                                                    <div class="dropdown-menu dropdown-menu-right">' +option+
                        '                                                                    </div>' +
                        '                                                                </div>';

                    panel_html += '<div class="col-xs-12">' +
                        '                                                                    <p style="width: 100%; margin-top: 8px" class="text-center">' +
                        '                                                                        <a class="detail" href="javascript:void(0);" data-toggle="collapse" data-target="#collapseExample'+app.id+'" aria-expanded="false" aria-controls="collapseExample">' +
                        '                                                                            Details..' +
                        '                                                                        </a>' +
                        '                                                                    </p>' +
                        '                                                                    <div class="collapse" id="collapseExample'+app.id+'" style="width: 100%;">'+
                        '                                                                       <table width="100%">';
                    var details = app.details;
                    for (var k = 0; k < details.length; k++){

                        var detail = details[k];
                        if(detail.type == 'text'){
                            panel_html += '<tr>' +
                                '                                                                            <td width="40%"><p>'+detail.title+'</p></td>' +
                                '                                                                            <td width="20" class="text-center">:</td>' +
                                '                                                                            <td>'+detail.data+'</td>' +
                                '                                                                        </tr>'
                        }
                        else if(detail.type == 'url'){
                            panel_html += '<tr>' +
                                '                                                                                <td colspan="3">' +
                                '                                                                                    <a href="'+detail.data+'" target="_blank">Download CV</a>' +
                                '                                                                                </td>' +
                                '                                                                            </tr>';
                        }
                        else if(detail.type == 'collapse'){
                            panel_html += '<tr>' +
                                '                                                                                <td colspan="3">' +
                                '                                                                                    <a  href="javascript:void(0);" data-toggle="collapse" data-target="#collapseD'+app.id+''+k+'" aria-expanded="false" aria-controls="collapseD1">Show Cover Letter</a>' +
                                '                                                                                    <div class="collapse" id="collapseD'+app.id+''+k+'">' +
                                '                                                                                        <div class="well" style="white-space: normal;">' +detail.data+
                                '                                                                                        </div>' +
                                '                                                                                    </div>' +
                                '                                                                                </td>' +
                                '                                                                            </tr>'
                        }
                    }

                    panel_html += '                                                             </table>' +
                        '                                                                    </div>' +
                        '                                                                </div>' +
                        '                                                            </div>' +
                        '                                                        </div>' +
                        '                                                    </div>' +
                        '                                                </article>';

                }


                panel_html +=                                   '</div>' +
                    '                                        </div>' +
                    '                                    </div>';
            }
            $('#'+container_id).html(panel_html);
        }

        function detailHistoryInternal(id) {

            $.ajax({
                url: "/karyawan/recruitment_application/detail-history-internal/"+id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    fillHistory(data);
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
        function detailHistoryExternal(id) {

            $.ajax({
                url: "/administrator/recruitment/detail-history-external/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    fillHistory(data);
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

        function fillHistory(data) {
            $('#app_name').html(data.application.name);
            $('#app_position').html(data.application.position);
            $('#app_branch').html(data.application.branch);
            $('#app_date').html(data.application.date_request);
            $("#app_photo").attr("src",data.application.photo);

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
                        status = 'dot-rejected';
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
        }

        function editBoard(id){
            $.ajax({
                url: "/administrator/recruitment/detail-edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#app_name_edit').html(data.application.name);
                    $('#app_position_edit').html(data.application.position);
                    $('#app_branch_edit').html(data.application.branch);
                    $('#app_date_edit').html(data.application.date_request);
                    $('#app_phase_edit').html(data.application.phase);
                    $("#app_photo_edit").attr("src",data.application.photo);
                    console.log(data);
                    var app_interviewers = [];
                    for(var i = 0; i < data.application.interviewers.length; i++){

                        app_interviewers.push(data.application.interviewers[i].user_id);
                    }
                    var history_html = '<br><input type="hidden" name="history_id" value="'+data.application.history_id+'"/> ' +
                        '                            <div class="form-group col-md-12">' +
                        '                                <label>Status</label>' +
                        '                                <div>' +
                        '                                    <select class="form-control" id="status_edit" name="application_status" style="width: 100%">' +
                        '                                        <option value="0">Waiting</option>' +
                        '                                        <option value="1">Approved</option>' +
                        '                                        <option value="2">Shortlisted</option>' +
                        '                                        <option value="3">Rejected</option>' +
                        '                                        <option value="4">Archived</option>' +
                        '                                    </select>' +
                        '                                </div>' +
                        '                            </div>';


                    if(data.application.current_phase_id == '3' || data.application.current_phase_id == '8'){

                        history_html += '                            <div class="form-group col-md-12">' +
                            '                                <label>Interviewers</label>' +
                            '                                <div>' +
                            '                                    <select class="form-control" id="interviewers" name="interviewers[]" multiple="multiple" style="width: 100%">';
                        for(var j = 0; j < interviewers.length; j++){
                            var selected = app_interviewers.includes(interviewers[j].id)?'selected':'';
                            history_html += '<option value="'+interviewers[j].id+'" '+selected+'>'+interviewers[j].name+'</option>';
                        }
                        history_html += '                                    </select>' +
                            '                                </div>' +
                            '                            </div>';
                    }
                    for(var i = 0; i < data.application.details.length; i++){
                        var detail = data.application.details[i];
                        
                        history_html += '                            <div class="form-group col-md-12">' +
                            '                                <label>'+detail.title+'</label>' +
                            '                                <div>';
                        if(detail.type == 'text'){
                            history_html+='<input type="text" name="'+detail.name+'" value="'+detail.data+'" class="form-control">';
                        }
                        if(detail.type == 'textarea'){
                            history_html+='<textarea name="'+detail.name+'" class="form-control">'+detail.data+'</textarea>';
                        }
                        else if(detail.type == 'date'){
                            history_html+='<input type="text" name="'+detail.name+'" value="'+detail.data+'" class="form-control datepicker">';
                        }
                        else if(detail.type == 'datetime'){
                            history_html+='<input type="text" name="'+detail.name+'" value="'+detail.data+'" class="form-control datetimepicker">';
                        }

                        history_html += '                                </div>' +
                            '                            </div>';
                    }
                    $('#edit_form').html(history_html);
                    $('.datepicker').datepicker({
                        dateFormat: 'yy-mm-dd'
                    });
                    $('#interviewers').select2();

                    var date = [];
                    $( ".datetimepicker" ).each(function( index ) {
                       date.push($( this ).val());
                    });
                    $('.datetimepicker').datetimepicker({
                        format: 'YYYY-MM-DD HH:mm:SS'
                    });
                    $( ".datetimepicker" ).each(function( index ) {
                        $(this).data("DateTimePicker").date(new Date(date[index]));
                    });


                    $('#status_edit').val(data.application.status);
                    $("#modal-edit").modal('show');
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

        // $(function () {
        //     var kanbanCol = $('.panel-body');
        //     kanbanCol.css('max-height', (window.innerHeight - 150) + 'px');
        //
        //     var kanbanColCount = parseInt(kanbanCol.length);
        //     $('.container-fluid').css('min-width', (kanbanColCount * 350) + 'px');
        //
        //     draggableInit();
        //
        //     // $('.panel-heading').click(function() {
        //     //     var $panelBody = $(this).parent().children('.panel-body');
        //     //     $panelBody.slideToggle();
        //     // });
        // });
        //
        // function draggableInit() {
        //     var sourceId;
        //
        //     $('[draggable=true]').bind('dragstart', function (event) {
        //         sourceId = $(this).parent().attr('id');
        //         event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
        //     });
        //
        //     $('.panel-body').bind('dragover', function (event) {
        //         event.preventDefault();
        //     });
        //
        //     $('.panel-body').bind('drop', function (event) {
        //         var children = $(this).children();
        //         var targetId = children.attr('id');
        //
        //         if (sourceId != targetId) {
        //             var elementId = event.originalEvent.dataTransfer.getData("text/plain");
        //
        //             $('#processing-modal').modal('toggle'); //before post
        //
        //
        //             // Post data
        //             setTimeout(function () {
        //                 var element = document.getElementById(elementId);
        //                 children.prepend(element);
        //                 $('#processing-modal').modal('toggle'); // after post
        //             }, 1000);
        //
        //         }
        //
        //         event.preventDefault();
        //     });
        // }



        $('.dropdown-submenu a.test').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });

        function moveBoard(id) {

            $.ajax({
                url: "/administrator/recruitment/detail-move/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('#app_name_m').html(data.application.name);
                    $('#app_position_m').html(data.application.position);
                    $('#app_branch_m').html(data.application.branch);
                    $('#app_date_m').html(data.application.date_request);
                    $("#app_photo_m").attr("src", data.application.photo);
                    $("#current_board").html("<option>"+data.application.current_phase+"</option>");
                    $('#application_id').val(data.application.id);
                    $('#modal-move').modal('show');

                    var next_board_html='';
                    for(var i = 0; i < data.next_boards.length; i++){
                        next_board_html += "<option value='"+data.next_boards[i].id+"'>"+data.next_boards[i].name+"</option>";
                    }
                    $("#next_board").html(next_board_html);
                }

            });
        }

        function sendEmail(id, phase_id){
            $.ajax({
                url: "{{route('recruitment.email-interviewer')}}",
                type: "POST",
                data: {'id': id, 'phase_id': phase_id,'_token':'{{csrf_token()}}'},
                dataType: "JSON",
                success: function (data) {
                    if (data.status == 'success') {
                        swal("Success!", data.message, "success");
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                }
            });
        }
        function onboard(id){
            $('#board_external_id').val(id);
            $.ajax({
                url: "/administrator/recruitment/detail-onboard/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    console.log(data);
                    $('#board_name').val(data.application.name);
                    $('#board_position').val(data.application.position);
                    $('#board_branch').val(data.application.branch);
                    $('#board_date').val(data.application.onboard_date);
                    $('#modal-onboard').modal('show');
                    $('#facilities').html('');
                    for(var i = 0; i < data.application.facilities.length; i++){
                        var facility = data.application.facilities[i];
                        var checked = facility.employee_facility_id == null? '':'checked';

                        $('#facilities').append('<div class="col-md-4">' +
                            '                                        <input type="checkbox" class="form-check-input" name="facilities[]" value="'+facility.id+'" '+checked+'> '+facility.name+
                            '                                    </div>');
                    }
                }

            });


        }
        $('#form_move').on('submit',function () {
            var form = $('#form_move')[0];
            var formData = new FormData(form);
            $.ajax({
                url: "{{route('recruitment.move')}}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == 'success') {
                        swal("Success!", data.message, "success");
                        $('#form_move')[0].reset();
                        $('#modal-move').modal('hide');
                        if(data.data == '1')
                            getInternalData();
                        else
                            getExternalData();
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                    console.log(data);
                }
            });
            return false;
        });
        $('#form_edit').on('submit',function () {
            var form = $('#form_edit')[0];
            var formData = new FormData(form);
            formData.append('_token',"{{csrf_token()}}");
            $.ajax({
                url: "{{route('recruitment.update-board')}}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if (data.status == 'success') {
                        swal("Success!", data.message, "success");
                        $('#form_edit')[0].reset();
                        $('#modal-edit').modal('hide');
                        if(data.data == '1')
                            getInternalData();
                        else
                            getExternalData();
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                    console.log(data);
                }
            });
            return false;
        });
        $('#form_onboard').on('submit',function () {
            var form = $('#form_onboard')[0];
            var formData = new FormData(form);
            formData.append('_token',"{{csrf_token()}}");
            $.ajax({
                url: "{{route('recruitment.update-onboard')}}",
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if (data.status == 'success') {
                        swal("Success!", data.message, "success");
                        $('#form_onboard')[0].reset();
                        $('#modal-onboard').modal('hide');
                        getExternalData();
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                    console.log(data);
                }
            });
            return false;
        });
        function exportData() {
            window.location.href = "{{route('recruitment.download',$recruitment->id)}}";
        }
    </script>
@endsection
@endsection
