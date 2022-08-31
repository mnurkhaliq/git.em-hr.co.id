@extends('layouts.administrator')

@section('title', 'Medical Reimbursement')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Medical Reimbursement</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="col-md-12 pull-right" style="padding:0px;">
                    <form method="POST" action="{{ route('administrator.medicalCustom.index') }}" id="filter-form">
                        {{ csrf_field() }}
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div style="padding-left:0; float: right;">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                        <li><a class="toggle-vis" data-column="1" style="color:blue;">Number</a></li> 
                                        <li><a class="toggle-vis" data-column="2" style="color:blue;">NIK</a></li> 
                                        <li><a class="toggle-vis" data-column="3" style="color:blue;">Name</a></li>
                                        <li><a class="toggle-vis" data-column="4" style="color:blue;">Position</a></li>
                                        <li><a class="toggle-vis" data-column="5" style="color:blue;">Claim Date</a></li>
                                        <li><a class="toggle-vis" data-column="6" style="color:blue;">Nominal Approved (IDR)</a></li> 
                                        <li><a class="toggle-vis" data-column="7" style="color:blue;">Status</a></li>
                                        <li><a class="toggle-vis" data-column="8" style="color:blue;">Created</a></li>
                                        <li><a class="toggle-vis" data-column="9" style="color:blue;">Action</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group m-l-4 m-r-4 pull-right" style="padding-left:3px; padding-right:3px;">
                                    <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action 
                                        <i class="fa fa-gear"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="javascript:void(0)" onclick="reset_filter()"> <i class="fa fa-refresh"></i> Reset Filter</a></li>
                                        <li><a href="javascript:void(0)" onclick="submit_filter_download()"><i class="fa fa-download"></i> Download Excel</a></li>
                                    </ul>
                                </div>
                                <button type="button" id="filter_view" class="btn btn-default btn-sm pull-right btn-outline"><i class="fa fa-search-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="division_id">
                                    <option value=""> - Choose Division - </option>
                                        @foreach($division as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->division_id || Session::get('mr-division_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="position_id">
                                    <option value=""> - Choose Position - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->position_id || Session::get('mr-position_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="employee_status">
                                <option value="">- Employee Status - </option>
                                    <option {{ (request() and request()->employee_status == 'Permanent') || Session::get('mr-employee_status') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option {{ (request() and request()->employee_status == 'Contract') || Session::get('mr-employee_status') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option {{ (request() and request()->employee_status == 'Internship') || Session::get('mr-employee_status') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    <option {{ (request() and request()->employee_status == 'Outsource') || Session::get('mr-employee_status') == 'Outsource' ? 'selected' : '' }}>Outsource</option>
                                    <option {{ (request() and request()->employee_status == 'Freelance') || Session::get('mr-employee_status') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option {{ (request() and request()->employee_status == 'Consultant') || Session::get('mr-employee_status') == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group  m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || Session::get('mr-name') ? Session::get('mr-name') : '' }}" placeholder="Name/NIK/Number">
                            </div>
                        </div>
                        <input type="hidden" name="action" value="view">
                    </form>
                </div>
            <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>NUMBER</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>POSITION</th>
                                    <th>CLAIM DATE</th>
                                    <th>NOMINAL APPROVED (IDR)</th>
                                    <th>STATUS</th>
                                    <th>CREATED</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <?php if(!isset($item->user->name)) { continue; } ?>
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{$item->number}}</td> 
                                        <td>{{ $item->user->nik }}</td>
                                        <td title="{{$item->user->name}}" >{{str_limit($item->user->name, $limit = 20, $end = '...') }}</td>
                                        <td>{{ $item->user->structure ? $item->user->structure->position->name.($item->user->structure->division ? ' - '.$item->user->structure->division->name : '').($item->user->structure->title ? ' - '.$item->user->structure->title->name : '') : ""}}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_pengajuan)) }}</td>
                                        <td>{{$item->form->sum('nominal_approve') != 0 ? format_idr($item->form->sum('nominal_approve')) : ''}}</td>
                                        <td>
                                            <a onclick="detail_approval_medical_custom({{ $item->id }})"> 
                                            {!! status_medical($item->status) !!}
                                            </a><br>
                                            @if($item->status==2 && $item->is_transfer==0)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a href="{{ route('administrator.medicalCustom.proses', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
                                            
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
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<script type="text/javascript">
    $("#filter_view").click(function(){
        $("#filter-form input[name='action']").val('view');
        $("#filter-form").submit();

    });

    function reset_filter()
    {
        $("#filter-form input.form-control, #filter-form select").val("");
        $("#filter-form input[name='action']").val('');
        $("input[name='reset']").val(1);
        $("#filter-form").submit();
    }

    var submit_filter_download = function(){
        $("#filter-form input[name='action']").val('download');
        $("#filter-form").submit();
    }
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
        var column = data_table_no_search.column($(this).attr('data-column'));
    
            // Toggle the visibility
        column.visible(!column.visible());
    });
</script>
@endsection
@endsection