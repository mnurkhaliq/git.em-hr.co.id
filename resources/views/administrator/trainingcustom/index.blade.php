@extends('layouts.administrator')

@section('title', 'Business Trip')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Business Trip</h4> 
            </div>
            <div class="col-lg-10 col-sm-8 col-md-8 col-xs-12">
                <div class="col-md-12 pull-right" style="padding:0px;">
                    <form method="POST" action="{{ route('administrator.trainingCustom.index') }}" id="filter-form">
                        {{ csrf_field() }}
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div style="padding-left:0; float: right;">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                        <li><a class="toggle-vis" data-column="1" style="color:blue;">BT Number</a></li> 
                                        <li><a class="toggle-vis" data-column="2" style="color:blue;">NIK</a></li> 
                                        <li><a class="toggle-vis" data-column="3" style="color:blue;">Name</a></li>
                                        <li><a class="toggle-vis" data-column="4" style="color:blue;">Position</a></li>
                                        <li><a class="toggle-vis" data-column="5" style="color:blue;">Activity Type</a></li>
                                        <li><a class="toggle-vis" data-column="6" style="color:blue;">Activity Date</a></li> 
                                        <li><a class="toggle-vis" data-column="7" style="color:blue;">Cash Advance (IDR)</a></li>
                                        <li><a class="toggle-vis" data-column="8" style="color:blue;">Total Approved (IDR)</a></li> 
                                        <li><a class="toggle-vis" data-column="9" style="color:blue;">Created</a></li>
                                        <li><a class="toggle-vis" data-column="10" style="color:blue;">BT Approval</a></li>
                                        <li><a class="toggle-vis" data-column="11" style="color:blue;">Action</a></li> 
                                        <li><a class="toggle-vis" data-column="12" style="color:blue;">BT Claim</a></li> 
                                        <li><a class="toggle-vis" data-column="13" style="color:blue;">Actual Bill Report</a></li>
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
                                        <option value="{{ $item->id }}" {{ $item->id== request()->division_id || Session::get('bt-division_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="position_id">
                                    <option value=""> - Choose Position - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->position_id || Session::get('bt-position_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1  pull-right" style="padding: 0px; margin:0px;">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="bt_claim">
                                    <option value="">BT Claim</option>
                                    <option value="1" {{ (request() and request()->bt_claim == '1') || Session::get('bt-bt_claim') == '1' ? 'selected' : '' }}>Waiting</option>
                                    <option value="2" {{ (request() and request()->bt_claim == '2') || Session::get('bt-bt_claim') == '2' ? 'selected' : '' }}>Approved</option>
                                    <option value="3" {{ (request() and request()->bt_claim == '3') || Session::get('bt-bt_claim') == '3' ? 'selected' : '' }}>Rejected</option>
                                    <option value="4" {{ (request() and request()->bt_claim == '4') || Session::get('bt-bt_claim') == '4' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-1  pull-right" style="padding: 0px; margin:0px;">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="bt_approval">
                                    <option value="">BT Approval</option>
                                    <option value="1" {{ (request() and request()->bt_approval == '1') || Session::get('bt-bt_approval') == '1' ? 'selected' : '' }}>Waiting</option>
                                    <option value="2" {{ (request() and request()->bt_approval == '2') || Session::get('bt-bt_approval') == '2' ? 'selected' : '' }}>Approved</option>
                                    <option value="3" {{ (request() and request()->bt_approval == '3') || Session::get('bt-bt_approval') == '3' ? 'selected' : '' }}>Rejected</option>
                                    <option value="4" {{ (request() and request()->bt_approval == '4') || Session::get('bt-bt_approval') == '4' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="employee_status">
                                    <option value="">- Employee Status - </option>
                                    <option {{ (request() and request()->employee_status == 'Permanent') || Session::get('bt-employee_status') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option {{ (request() and request()->employee_status == 'Contract') || Session::get('bt-employee_status') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option {{ (request() and request()->employee_status == 'Internship') || Session::get('bt-employee_status') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    <option {{ (request() and request()->employee_status == 'Outsource') || Session::get('bt-employee_status') == 'Outsource' ? 'selected' : '' }}>Outsource</option>
                                    <option {{ (request() and request()->employee_status == 'Freelance') || Session::get('bt-employee_status') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option {{ (request() and request()->employee_status == 'Consultant') || Session::get('bt-employee_status') == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || Session::get('bt-name') ? Session::get('bt-name')  : '' }}" placeholder="Name/NIK/BT Number">
                            </div>
                        </div>
                        <input type="hidden" name="action" value="view">
                    </form>
                </div>
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
                                    <th>POSITION</th>
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
                                    @if(isset($item->user->name)) 
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ $item->number != null ? $item->number : ''  }}</td>
                                        <td>{{ $item->user->nik }}</td>
                                        <td title="{{$item->user->name}}">{{ str_limit($item->user->name, $limit = 20, $end = '...') }}</td>
                                        <td>{{ $item->user->structure ? $item->user->structure->position->name.($item->user->structure->division ? ' - '.$item->user->structure->division->name : '').($item->user->structure->title ? ' - '.$item->user->structure->title->name : '') : ""}}</td>
                                        <td>{{isset($item->training_type)? $item->training_type->name:''}}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_kegiatan_start)) }} - {{ date('d F Y', strtotime($item->tanggal_kegiatan_end)) }}</td>
                                        <td>{{ format_idr($item->pengambilan_uang_muka) }}</td>
                                        <td>{{ $item->status_actual_bill==2 ? format_idr($item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui) : '' }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a onclick="detail_approval_training_custom({{ $item->id }})">
                                            {!! status_cuti($item->status) !!}
                                            </a>
                                            <br>
                                            @if($item->status==2 && $item->is_transfer==0)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1 && $item->pengambilan_uang_muka > 0)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('administrator.trainingCustom.proses', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> detail </a>
                                        </td>
                                        <td>
                                            <a href="javascript:;" onclick="detail_approval_trainingClaim_custom({{ $item->id }})"> 
                                                {!! status_cuti($item->status_actual_bill) !!}
                                            </a>
                                            <br>
                                            @php( $total_reimbursement_disetujui = $item->sub_total_1_disetujui + $item->sub_total_2_disetujui + $item->sub_total_3_disetujui + $item->sub_total_4_disetujui - $item->pengambilan_uang_muka )
                                            @if($item->status_actual_bill==2 && $item->is_transfer_claim==0  && $total_reimbursement_disetujui != 0)
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status_actual_bill==2 && $item->is_transfer_claim==1   && $total_reimbursement_disetujui != 0)
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 2 and $item->status_actual_bill >= 1) 
                                                <a href="{{ route('administrator.trainingCustom.claim', $item->id) }}" class="btn btn-info btn-xs"> <i class="fa fa-search-plus"></i> claimed detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
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

        $("#proses_pembatalan").click(function(){

            var alasan = $("#alasan_pembatalan").val();

            if(alasan == "")
            {
                bootbox.alert('Reason of cancellation must filled!');
            }
            else
            {
                $("#form-pembatalan").submit();
            }
        });

        function batalkan_pengajuan(id)
        {   
            $('.id-pembatalan').val(id);

            $("#modal_pembatalan").modal('show');
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
