@extends('layouts.administrator')

@section('title', 'Approval Cash Advance')

@section('sidebar')

@endsection

@section('content')

  
        
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Business Trip</h4> 
            </div>
            <div class="col-lg-10 col-sm-8 col-md-8 col-xs-12">
                <div class="col-md-12 pull-right" style="padding:0px;">
                    <form method="POST" action="{{ route('administrator.approval.cash-advance.index') }}" id="filter-form">
                        {{ csrf_field() }}
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div style="padding-left:0; float: right;">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                        <li><a class="toggle-vis" data-column="1" style="color:blue;">CA Number</a></li> 
                                        <li><a class="toggle-vis" data-column="2" style="color:blue;">NIK</a></li> 
                                        <li><a class="toggle-vis" data-column="3" style="color:blue;">Name</a></li>
                                        <li><a class="toggle-vis" data-column="4" style="color:blue;">To</a></li>
                                        <li><a class="toggle-vis" data-column="5" style="color:blue;">Purpose</a></li>
                                        <li><a class="toggle-vis" data-column="6" style="color:blue;">Method Payment</a></li> 
                                        <li><a class="toggle-vis" data-column="7" style="color:blue;">Created</a></li>
                                        <li><a class="toggle-vis" data-column="8" style="color:blue;">CA Approved(IDR)</a></li>
                                        <li><a class="toggle-vis" data-column="9" style="color:blue;">CA Settlement (IDR)</a></li>
                                        <li><a class="toggle-vis" data-column="10" style="color:blue;">Status</a></li> 
                                        <li><a class="toggle-vis" data-column="11" style="color:blue;">Action</a></li> 
                                        <li><a class="toggle-vis" data-column="12" style="color:blue;">Settlement Status</a></li> 
                                        <li><a class="toggle-vis" data-column="13" style="color:blue;">Action Settlement</a></li> 
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
                                        <option value="{{ $item->id }}" {{ $item->id== request()->division_id || Session::get('ca-division_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="position_id">
                                    <option value=""> - Choose Position - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->position_id || Session::get('ca-position_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-md-1  pull-right" style="padding: 0px; margin:0px;">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="bt_claim">
                                    <option value="">BT Claim</option>
                                    <option value="1" {{ (request() and request()->bt_claim == '1') || Session::get('ca-bt_claim') == '1' ? 'selected' : '' }}>Waiting</option>
                                    <option value="2" {{ (request() and request()->bt_claim == '2') || Session::get('ca-bt_claim') == '2' ? 'selected' : '' }}>Approved</option>
                                    <option value="3" {{ (request() and request()->bt_claim == '3') || Session::get('ca-bt_claim') == '3' ? 'selected' : '' }}>Rejected</option>
                                    <option value="4" {{ (request() and request()->bt_claim == '4') || Session::get('ca-bt_claim') == '4' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-1  pull-right" style="padding: 0px; margin:0px;">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="bt_approval">
                                    <option value="">BT Approval</option>
                                    <option value="1" {{ (request() and request()->bt_approval == '1') || Session::get('ca-bt_approval') == '1' ? 'selected' : '' }}>Waiting</option>
                                    <option value="2" {{ (request() and request()->bt_approval == '2') || Session::get('ca-bt_approval') == '2' ? 'selected' : '' }}>Approved</option>
                                    <option value="3" {{ (request() and request()->bt_approval == '3') || Session::get('ca-bt_approval') == '3' ? 'selected' : '' }}>Rejected</option>
                                    <option value="4" {{ (request() and request()->bt_approval == '4') || Session::get('ca-bt_approval') == '4' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div> --}}
                        
                        <div class="col-md-3 pull-right">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="employee_status">
                                    <option value="">- Employee Status - </option>
                                    <option {{ (request() and request()->employee_status == 'Permanent') || Session::get('ca-employee_status') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option {{ (request() and request()->employee_status == 'Contract') || Session::get('ca-employee_status') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option {{ (request() and request()->employee_status == 'Internship') || Session::get('ca-employee_status') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    <option {{ (request() and request()->employee_status == 'Outsource') || Session::get('ca-employee_status') == 'Outsource' ? 'selected' : '' }}>Outsource</option>
                                    <option {{ (request() and request()->employee_status == 'Freelance') || Session::get('ca-employee_status') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option {{ (request() and request()->employee_status == 'Consultant') || Session::get('ca-employee_status') == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group  m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || Session::get('ca-name') ? Session::get('ca-name')  : '' }}" placeholder="Name / NIK Employee">
                            </div>
                        </div>
                        <input type="hidden" name="action" value="view">
                    </form>
                </div>
            </div>
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Manage Approval Cash Advance</h3>
                    <br />
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>CA NUMBER</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>TO</th>
                                    <th>PURPOSE</th>
                                    <!--<th>TRANSACION TYPE</th>-->
                                    <th>METHOD PAYMENT</th>
                                    <th>CREATED</th>
                                    <th>CA APPROVED (IDR)</th>
                                    <th>CA SETTLEMENT (IDR)</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                    <th>SETTLEMENT STATUS</th>
                                    <th>ACTION SETTLEMENT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{$item->number}}</td> 
                                        <td>{{ $item->user->nik }}</td>
                                        <td title="{{$item->user->name}}">{{ str_limit($item->user->name, $limit = 20, $end = '...') }}</td>
                                        <td>Accounting Department</td>
                                        <td>{{ str_limit($item->tujuan, $limit = 30, $end = '...') }}</td>
                                        <!--<td>{{ $item->transaction_type }}</td>-->
                                        <td>{{ $item->payment_method }}</td>
                                        <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ $item->cash_advance_form->sum('nominal_approved') != 0 ?  format_idr($item->cash_advance_form->sum('nominal_approved')) : ''}}</td>
                                        <td>{{ $item->cash_advance_form->sum('nominal_claimed') != 0 ? format_idr($item->cash_advance_form->sum('nominal_claimed')) : ''}}</td>
                                        <td>
                                            <a href="javascript:;" onclick="detail_approval_cashAdvance({{ $item->id }})"> 
                                                {!! status_cash_advance($item->status) !!}
                                            </a>
                                            <br>
                                            @if($item->status==2 && $item->is_transfer==0 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status < 2)
                                                <a href="{{ route('administrator.approval.cash-advance.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-arrow-right"></i></button></a>
                                            {{-- @elseif($item->status == 2 && $item->is_transfer == 0)
                                                <a href="{{ route('administrator.approval.cash-advance.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a> --}}
                                            @else
                                                <a href="{{ route('administrator.approval.cash-advance.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-search-plus"></i></button></a>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:;" onclick="detail_approval_cashAdvanceClaim({{ $item->id }})"> 
                                                {!! status_cash_advance($item->status_claim) !!}
                                            </a>
                                            <br>
                                            @if($item->status_claim==2 && $item->is_transfer_claim==0 && $item->payment_method == 'Bank Transfer' && $item->total_amount_claimed != $item->total_amount_approved)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status_claim==2 && $item->is_transfer_claim==1 && $item->payment_method == 'Bank Transfer'  && $item->total_amount_claimed != $item->total_amount_approved)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 2)
                                                @if($item->status_claim == 1)
                                                    <a href="{{ route('administrator.approval.cash-advance.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-arrow-right"></i> process settlement </a>
                                                @elseif($item->status_claim >= 1 && $item->is_transfer_claim == 1)
                                                    <a href="{{ route('administrator.approval.cash-advance.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> settlement detail</a>
                                                {{-- @elseif($item->status_claim == 2 && $item->is_transfer_claim == 0 && ($item->total_amount_claimed > $item->total_amount_approved))
                                                    <a href="{{ route('administrator.approval.cash-advance.claim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a> --}}
                                                @elseif($item->status_claim >= 1 && $item->is_transfer_claim == 0 )
                                                    <a href="{{ route('administrator.approval.cash-advance.claim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i>settlement detail</button></a>
                                                @endif
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
