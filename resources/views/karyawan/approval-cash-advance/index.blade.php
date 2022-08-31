@extends('layouts.karyawan')

@section('title', 'Approval Cash Advance')

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
                <h4 class="page-title">Dashboard</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="POST" action="{{ route('karyawan.approval.cash-advance.index') }}" id="filter-form">
                    {{ csrf_field() }}
                    <div style="padding-left:0; float: right;">
                        <button type="button" onclick="reset_filter()" class="btn btn-default btn-sm pull-right btn-outline" title="reset filter"><i class="fa fa-refresh"></i></button>
                        <button type="button" id="filter_view" class="btn btn-default btn-sm pull-right btn-outline m-r-5" title="filter"><i class="fa fa-search-plus"></i></button>
                    </div>
                   <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select class="form-control form-control-line" name="division_id">
                                <option value=""> - Choose Division - </option>
                                    @foreach($division as $item)
                                    <option value="{{ $item->id }}" {{ $item->id== request()->division_id || $item->id== \Session::get('aca-division_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select class="form-control form-control-line" name="position_id">
                                <option value=""> - Choose Position - </option>
                                    @foreach($position as $item)
                                    <option value="{{ $item->id }}" {{ $item->id== request()->position_id || $item->id== \Session::get('aca-position_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="col-md-2" style="padding-left:0; float: right;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="employee_status">
                                <option value="">- Employee Status - </option>
                                <option {{ (request() and request()->employee_status == 'Permanent') || (\Session::get('aca-employee_status') == 'Permanent') ? 'selected' : '' }}>Permanent</option>
                                <option {{ (request() and request()->employee_status == 'Contract') || (\Session::get('aca-employee_status') == 'Contract') ? 'selected' : '' }}>Contract</option>
                                <option {{ (request() and request()->employee_status == 'Internship') || (\Session::get('aca-employee_status') == 'Internship') ? 'selected' : '' }}>Internship</option>
                                <option {{ (request() and request()->employee_status == 'Outsource') || (\Session::get('aca-employee_status') == 'Outsource') ? 'selected' : '' }}>Outsource</option>
                                <option {{ (request() and request()->employee_status == 'Freelance') || (\Session::get('aca-employee_status') == 'Freelance') ? 'selected' : '' }}>Freelance</option>
                                <option {{ (request() and request()->employee_status == 'Consultant') || (\Session::get('aca-employee_status') == 'Consultant') ? 'selected' : '' }}>Consultant</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <div class="form-group  m-b-0">
                            <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || \Session::get('aca-name') ? \Session::get('aca-name') : '' }}" placeholder="Name/NIK/Number">
                        </div>
                    </div>
                    <input type="hidden" name="action" value="view">
                </form>
            </div>
            <!-- /.col-lg-12 -->
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
                                @if($data != null)
                                @forelse($data as $no => $item)
                                    @if($item->is_approved == NULL)
                                        @if($item->cashAdvance->status == 3)
                                            <?php continue;?>
                                        @endif
                                        @if(!cek_level_cash_advance_up($item->cashAdvance->id))
                                            <?php continue;?>
                                        @endif

                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>    
                                        <td>{{$item->number}}</td> 
                                        <td>{{ $item->cashAdvance->user->nik }}</td>
                                        <td>{{ $item->cashAdvance->user->name }}</td>
                                        <td>Accounting Department</td>
                                        <td>{{ str_limit($item->tujuan, $limit = 30, $end = '...') }}</td>
                                        <!--<td>{{ $item->transaction_type }}</td>-->
                                        <td>{{ $item->payment_method }}</td>
                                        <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                        <td>{{ $item->cashAdvance->cash_advance_form->sum('nominal_approved') != 0 ?  format_idr($item->cashAdvance->cash_advance_form->sum('nominal_approved')) : ''}}</td>
                                        <td>{{ $item->cashAdvance->cash_advance_form->sum('nominal_claimed') != 0 ? format_idr($item->cashAdvance->cash_advance_form->sum('nominal_claimed')) : ''}}</td>
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
                                            @if($item->is_approved === NULL and $item->status < 2)
                                                <a href="{{ route('karyawan.approval.cash-advance.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">process <i class="fa fa-arrow-right"></i></button></a>
                                            @elseif($item->status == 2 && $item->is_transfer == 0)
                                                @if(cek_transfer_setting_user())
                                                <a href="{{ route('karyawan.approval.cash-advance.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a> 
                                                @else 
                                                <a href="{{ route('karyawan.approval.cash-advance.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-search-plus"></i></button></a>
                                                @endif
                                            @else
                                                @if(cek_transfer_setting_user())
                                                <a href="{{ route('karyawan.approval.cash-advance.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail</button></a> 
                                                @else 
                                                <a href="{{ route('karyawan.approval.cash-advance.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-search-plus"></i></button></a>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status_claim != 4)
                                            <a href="javascript:;" onclick="detail_approval_cashAdvanceClaim({{ $item->id }})"> 
                                                {!! status_cash_advance($item->status_claim) !!}
                                            </a>
                                            @endif
                                            <br>
                                            @if($item->status_claim==2 && $item->is_transfer_claim==0 && $item->payment_method == 'Bank Transfer' && $item->cashAdvance->cash_advance_form->sum('nominal_claimed') != $item->cashAdvance->cash_advance_form->sum('nominal_approved'))
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status_claim==2 && $item->is_transfer_claim==1 && $item->payment_method == 'Bank Transfer'  && $item->cashAdvance->cash_advance_form->sum('nominal_claimed') != $item->cashAdvance->cash_advance_form->sum('nominal_approved'))
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 2)
                                                @if($item->is_approved_claim === NULL and $item->status_claim == 1)
                                                    @if(cek_level_cash_advance_up($item->cashAdvance->id))
                                                    <a href="{{ route('karyawan.approval.cash-advance.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-arrow-right"></i> process settlement </a>
                                                    @endif
                                                @elseif($item->is_approved_claim === NULL and $item->status_claim == 1 && $item->is_transfer_claim == 0)
                                                    @if(cek_level_cash_advance_up($item->cashAdvance->id))
                                                    <a href="{{ route('karyawan.approval.cash-advance.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-arrow-right"></i> process settlement </a>
                                                    @endif
                                                @elseif($item->is_approved_claim != NULL and $item->status_claim >= 1 && $item->is_transfer_claim == 1)
                                                @if(cek_transfer_setting_user())
                                                <a href="{{ route('karyawan.approval.cash-advance.transferClaim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">settlement detail</button></a> 
                                                @else 
                                                <a href="{{ route('karyawan.approval.cash-advance.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> settlement  detail</a>
                                                @endif
                                                @elseif($item->is_approved_claim != NULL and $item->status_claim >= 1 && $item->is_transfer_claim == 0)
                                                    @if(cek_transfer_setting_user() && ($item->cashAdvance->cash_advance_form->sum('nominal_claimed') != $item->cashAdvance->cash_advance_form->sum('nominal_approved')) and $item->status_claim == 2)
                                                    <a href="{{ route('karyawan.approval.cash-advance.transferClaim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a>
                                                    @else 
                                                    @if(cek_transfer_setting_user())
                                                    <a href="{{ route('karyawan.approval.cash-advance.transferClaim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">settlement detail</button></a> 
                                                    @else 
                                                    <a href="{{ route('karyawan.approval.cash-advance.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> settlement  detail</a>
                                                    @endif
                                                    @endif
                                                @endif
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="10">No data available in table</td>   
                                </tr>
                                @endforelse
                                @else 
                                <tr>
                                    <td class="text-center" colspan="10">No data available in table</td>    
                                </tr>
                                @endif
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

</script>
@endsection
@endsection
