@extends('layouts.karyawan')

@section('title', 'Recruitment Request')

@section('sidebar')

@endsection

@section('content')
    <link href="{{ asset('js/recruitment-request/general.css') }}" rel="stylesheet">

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Dashboard</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Approval Recruitment Request</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Manage Approval Recruitment Request</h3>
                    <br />
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center">NO</th>
                                    <th width="10%">REQUEST NUMBER</th>
                                    <th width="10%">POSITION</th>
                                    <th width="10%">BRANCH</th>
                                    <th width="5%">HEADCOUNT</th>
                                    <th width="10%">REQUESTOR</th>
                                    <th width="10%">REQUEST DATE</th>
                                    <th width="10%">TARGET</th>
                                    <th width="5%">STATUS</th>
                                    <th  width="5%">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $no => $item)
                                @if($item->is_approved == NULL)
                                    @if($item->recruitmentRequest->approval_user == '0')
                                        <?php continue;?>
                                    @endif

                                    @if(!cek_level_recruitment_up($item->recruitmentRequest->id))
                                        <?php continue;?>
                                    @endif
                                @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->request_number }}</td>
                                        <td>{{ $item->job_position }}</td>
                                        <td>{{ $item->recruitmentRequest->branch->name }}</td>
                                        <td>{{ $item->headcount }}</td>
                                        <td>{{ $item->recruitmentRequest->requestor->name }}</td>
                                        <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                        <td>
                                            @php($details = $item->recruitmentRequest->details)
                                            @if(count($details) == 2)
                                                EXTERNAL {{ $details[0]->status_post == 1 ? "(POSTED)" : "(UNPOSTED)" }}
                                                <br>
                                                INTERNAL {{ $details[1]->status_post == 1 ? "(POSTED)" : "(UNPOSTED)" }}
                                            @elseif(count($details) == 1 && $details[0]->recruitment_type_id == 1)
                                                INTERNAL {{ $details[0]->status_post == 1 ? "(POSTED)" : "(UNPOSTED)" }}
                                            @elseif(count($details) == 1 && $details[0]->recruitment_type_id == 2)
                                                EXTERNAL {{ $details[0]->status_post == 1 ? "(POSTED)" : "(UNPOSTED)" }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->recruitmentRequest->approval_hr == '0' || $item->recruitmentRequest->approval_user == '0')
                                                <label class='btn btn-danger btn-xs' onclick="detail({{$item->recruitmentRequest->id}})">Rejected</label>
                                            @elseif($item->recruitmentRequest->approval_hr == '1' && $item->recruitmentRequest->approval_user == '1')
                                                <label class='btn btn-success btn-xs' onclick="detail({{$item->recruitmentRequest->id}})">Approved</label>
                                            @elseif($item->recruitmentRequest->approval_hr == null)
                                                <label class='btn btn-warning btn-xs' onclick="detail({{$item->recruitmentRequest->id}})">Waiting for HR</label>
                                            @else
                                                <label class='btn btn-warning btn-xs' onclick="detail({{$item->recruitmentRequest->id}})">Waiting for User</label>
                                            @endif


                                        </td>
                                        <td>
                                            @if($item->approval_user == null)
                                                <a href="{{ route('karyawan.approval.recruitment-request.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> Process</button></a>
                                            @else
                                                <a href="{{ route('karyawan.approval.recruitment-request.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> Detail</button></a>
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>

    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Recruitment Request Approval</strong></h4>
                </div>
                <form id="form">
                    <div class="modal-body" id="modal-add-body">
                        <div class="form-group col-xs-12">
                            <table class="table-history" width="100%">
                                <tr>
                                    <td width="30%">Request Number</td>
                                    <td width="30"> : </td>
                                    <td id="approval_request_number"></td>
                                </tr>
                                <tr>
                                    <td>Position</td>
                                    <td> : </td>
                                    <td id="approval_position"></td>
                                </tr>
                                <tr>
                                    <td>Branch</td>
                                    <td> : </td>
                                    <td id="approval_branch"></td>
                                </tr>
                                <tr>
                                    <td>Date Request</td>
                                    <td> : </td>
                                    <td id="approval_date_request"></td>
                                </tr>
                            </table>

                        </div>
                        <hr/>
                        <div class="form-group col-xs-12" id="approval">
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
@section('js')
    <script>var url = "{{route('ajax.get-recruitment-request-approval')}}"</script>
    <script src="{{ asset('js/recruitment-request/general.js') }}"></script>
@endsection
@endsection
