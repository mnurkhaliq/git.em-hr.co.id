@extends('layouts.karyawan')

@section('title', 'Approval Business Trip')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Business Trip</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="POST" action="{{ route('karyawan.approval.training-custom.index') }}" id="filter-form">
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
                                    <option value="{{ $item->id }}" {{ $item->id== request()->division_id || $item->id== \Session::get('abt-division_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select class="form-control form-control-line" name="position_id">
                                <option value=""> - Choose Position - </option>
                                    @foreach($position as $item)
                                    <option value="{{ $item->id }}" {{ $item->id== request()->position_id || $item->id== \Session::get('abt-position_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="col-md-2" style="padding-left:0; float: right;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="employee_status">
                                <option value="">- Employee Status - </option>
                                <option {{ (request() and request()->employee_status == 'Permanent') || (\Session::get('abt-employee_status') == 'Permanent') ? 'selected' : '' }}>Permanent</option>
                                <option {{ (request() and request()->employee_status == 'Contract') || (\Session::get('abt-employee_status') == 'Contract') ? 'selected' : '' }}>Contract</option>
                                <option {{ (request() and request()->employee_status == 'Internship') || (\Session::get('abt-employee_status') == 'Internship') ? 'selected' : '' }}>Internship</option>
                                <option {{ (request() and request()->employee_status == 'Outsource') || (\Session::get('abt-employee_status') == 'Outsource') ? 'selected' : '' }}>Outsource</option>
                                <option {{ (request() and request()->employee_status == 'Freelance') || (\Session::get('abt-employee_status') == 'Freelance') ? 'selected' : '' }}>Freelance</option>
                                <option {{ (request() and request()->employee_status == 'Consultant') || (\Session::get('abt-employee_status') == 'Consultant') ? 'selected' : '' }}>Consultant</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <div class="form-group  m-b-0">
                            <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || \Session::get('abt-name') ? \Session::get('abt-name') : '' }}" placeholder="Name/NIK/Number">
                        </div>
                    </div>
                    <input type="hidden" name="action" value="view">
                </form>
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
                                    <th>BT NUMBER</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>DEPARTMENT / POSITION</th>
                                    <th>ACTIVITY TYPE</th>
                                    <th>ACTIVITY DATE</th>
                                    <th>CASH ADVANCE (IDR)</th>
                                    <th>TOTAL APPROVED (IDR)</th>
                                    <th>CREATED</th>
                                    <th>BT APPROVAL</th>
                                    <th>ACTION</th>
                                    <th>BT CLAIM</th>
                                    <th>ACTUAL BILL REPORT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    @if($item->is_approved == NULL)
                                        @if($item->training->status == 3)
                                            <?php continue;?>
                                        @endif
                                        @if(!cek_level_training_up($item->training->id))
                                            <?php continue;?>
                                        @endif
                                    @endif
                                    <?php if(!isset($item->training->user->name)) { continue; } ?>
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>  
                                        <td>{{ $item->number != null ? $item->number : ''  }}</td>
                                        <td>{{ $item->training->user->nik }}</td>
                                        <td>{{ $item->training->user->name }}</a></td>
                                        <td>{{ isset($item->training->user->structure->position) ? $item->training->user->structure->position->name:''}}{{ isset($item->training->user->structure->division) ? ' - '. $item->training->user->structure->division->name:''}}{{ isset($item->training->user->structure->title) ? ' - '. $item->training->user->structure->title->name:''}}</td> 
                                        <td>{{ isset($item->training->training_type)? $item->training->training_type->name:''}}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_kegiatan_start)) }} - {{ date('d F Y', strtotime($item->tanggal_kegiatan_end)) }}</td>
                                        <td>{{ format_idr($item->pengambilan_uang_muka) }}</td>
                                        <td>{{ $item->status_actual_bill==2 ? format_idr($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) : '' }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a onclick="detail_approval_trainingCustom({{ $item->id }})"> 
                                            {!! status_cuti($item->status) !!}
                                            </a>
                                            <br>
                                            @if($item->status==2 && $item->is_transfer==0 )
                                            <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1 && $item->pengambilan_uang_muka > 0)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->is_approved === NULL and $item->status < 2)
                                                <a href="{{ route('karyawan.approval.training-custom.detail', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-arrow-right"></i> process</a>
                                            @elseif($item->status == 2 && $item->is_transfer == 0)
                                                @if(cek_transfer_setting_user())
                                                <a href="{{ route('karyawan.approval.training-custom.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a> 
                                                @else
                                                <a href="{{ route('karyawan.approval.training-custom.detail', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> detail</a>
                                                @endif 
                                            @else
                                                @if(cek_transfer_setting_user())
                                                <a href="{{ route('karyawan.approval.training-custom.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail</button></a> 
                                                @else
                                                <a href="{{ route('karyawan.approval.training-custom.detail', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> detail</a>
                                                @endif 
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status_actual_bill != 4)
                                            <a href="javascript:;" onclick="detail_approval_trainingClaimCustom({{ $item->id }})"> 
                                                {!! status_cuti($item->status_actual_bill) !!}
                                            </a>
                                            @endif
                                            <br>
                                            @php( $total_reimbursement_disetujui = $item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui - $item->pengambilan_uang_muka )
                                            @if($item->status_actual_bill==2 && $item->is_transfer_claim==0  && $total_reimbursement_disetujui != 0)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status_actual_bill==2 && $item->is_transfer_claim==1   && $total_reimbursement_disetujui != 0)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 2)
                                                @if($item->is_approved_claim === NULL and $item->status_actual_bill == 1)
                                                    @if(cek_level_training_up($item->training->id))
                                                    <a href="{{ route('karyawan.approval.training-custom.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-arrow-right"></i> process claim </a>
                                                    @endif
                                                @elseif($item->is_approved_claim === NULL and $item->status_actual_bill == 1 && $item->is_transfer_claim == 0)
                                                    @if(cek_level_training_up($item->training->id))
                                                    <a href="{{ route('karyawan.approval.training-custom.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-arrow-right"></i> process claim </a>
                                                    @endif
                                                @elseif($item->is_approved_claim != NULL and $item->status_actual_bill >= 1 && $item->is_transfer_claim == 1)
                                                    @if(cek_transfer_setting_user())
                                                    <a href="{{ route('karyawan.approval.training-custom.transferClaim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">claimed detail</button></a> 
                                                    @else 
                                                    <a href="{{ route('karyawan.approval.training-custom.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> claimed detail</a>
                                                    @endif
                                                @elseif($item->is_approved_claim != NULL and $item->status_actual_bill >= 1 && $item->is_transfer_claim == 0)
                                                    @if(cek_transfer_setting_user() && $item->status_actual_bill == 2)
                                                    <a href="{{ route('karyawan.approval.training-custom.transferClaim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a>
                                                    @else 
                                                        @if(cek_transfer_setting_user())
                                                        <a href="{{ route('karyawan.approval.training-custom.transferClaim', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">claimed detail</button></a> 
                                                        @else 
                                                        <a href="{{ route('karyawan.approval.training-custom.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> claimed detail</a>
                                                        @endif
                                                    @endif
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
    </div>
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
