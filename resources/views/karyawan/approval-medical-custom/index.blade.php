@extends('layouts.karyawan')

@section('title', 'Approval Medical Reimbursement')

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
                <h4 class="page-title">Manage Medical Reimbursement</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="POST" action="{{ route('karyawan.approval.medical-custom.index') }}" id="filter-form">
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
                                    <option value="{{ $item->id }}" {{ $item->id== request()->division_id || $item->id== \Session::get('amr-division_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            <select class="form-control form-control-line" name="position_id">
                                <option value=""> - Choose Position - </option>
                                    @foreach($position as $item)
                                    <option value="{{ $item->id }}" {{ $item->id== request()->position_id || $item->id== \Session::get('amr-position_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                     <div class="col-md-2" style="padding-left:0; float: right;">
                        <div class="form-group m-b-0">
                            <select class="form-control form-control-line" name="employee_status">
                                <option value="">- Employee Status - </option>
                                <option {{ (request() and request()->employee_status == 'Permanent') || (\Session::get('amr-employee_status') == 'Permanent') ? 'selected' : '' }}>Permanent</option>
                                <option {{ (request() and request()->employee_status == 'Contract') || (\Session::get('amr-employee_status') == 'Contract') ? 'selected' : '' }}>Contract</option>
                                <option {{ (request() and request()->employee_status == 'Internship') || (\Session::get('amr-employee_status') == 'Internship') ? 'selected' : '' }}>Internship</option>
                                <option {{ (request() and request()->employee_status == 'Outsource') || (\Session::get('amr-employee_status') == 'Outsource') ? 'selected' : '' }}>Outsource</option>
                                <option {{ (request() and request()->employee_status == 'Freelance') || (\Session::get('amr-employee_status') == 'Freelance') ? 'selected' : '' }}>Freelance</option>
                                <option {{ (request() and request()->employee_status == 'Consultant') || (\Session::get('amr-employee_status') == 'Consultant') ? 'selected' : '' }}>Consultant</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <div class="form-group  m-b-0">
                            <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || \Session::get('amr-name') ? \Session::get('amr-name') : '' }}" placeholder="Name/NIK/Number">
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
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>NUMBER</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>CLAIM DATE</th>
                                    <th>NOMINAL APPROVED (IDR)</th>
                                    <th>STATUS</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    @if($item->is_approved == NULL)
                                        @if($item->medicalReimbursement->status == 3)
                                            <?php continue;?>
                                        @endif

                                        @if(!cek_level_medical_up($item->medicalReimbursement->id))
                                            <?php continue;?>
                                        @endif
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td> 
                                        <td>{{$item->medicalReimbursement->number}}</td> 
                                        <td>{{ $item->medicalReimbursement->user->nik }}</td>
                                        <td>{{ $item->medicalReimbursement->user->name }}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_pengajuan)) }}</td>
                                        <td>{{$item->medicalReimbursement->form->sum('nominal_approve') != 0 ? format_idr($item->medicalReimbursement->form->sum('nominal_approve')) : ''}}</td>
                                        <td>
                                            <a onclick="detail_approval_medicalCustom({{ $item->id }})">
                                            {!! status_medical($item->status) !!}
                                            </a><br>
                                            @if($item->status==2 && $item->is_transfer==0)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                         <td>
                                            @if($item->is_approved === NULL and $item->status < 2)
                                                <a href="{{ route('karyawan.approval.medical-custom.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> process </button></a>
                                            @elseif($item->status == 2 && $item->is_transfer == 0)
                                                @if(cek_transfer_setting_user())
                                                <a href="{{ route('karyawan.approval.medical-custom.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">Transfer</button></a> 
                                                @else 

                                                <a href="{{ route('karyawan.approval.medical-custom.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-search-plus"></i></button></a>
                                                @endif
                                            @else 
                                                @if(cek_transfer_setting_user())
                                                <a href="{{ route('karyawan.approval.medical-custom.transfer', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail</button></a> 
                                                @else 
                                                <a href="{{ route('karyawan.approval.medical-custom.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail </button></a>
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

</script>
@endsection
@endsection
