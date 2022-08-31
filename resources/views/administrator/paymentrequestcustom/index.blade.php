@extends('layouts.administrator')

@section('title', 'Payment Request')

@section('sidebar')

@endsection

@section('content')      
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Payment Request</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="col-md-12 pull-right" style="padding:0px;">
                    <form method="POST" action="{{ route('administrator.paymentRequestCustom.index') }}" id="filter-form">
                        {{ csrf_field() }}
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div style="padding-left:0; float: right;">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                        <li><a class="toggle-vis" data-column="1" style="color:blue;">Number</a></li> 
                                        <li><a class="toggle-vis" data-column="2" style="color:blue;">To</a></li> 
                                        <li><a class="toggle-vis" data-column="3" style="color:blue;">From</a></li>
                                        <li><a class="toggle-vis" data-column="4" style="color:blue;">Purpose</a></li>
                                        <li><a class="toggle-vis" data-column="5" style="color:blue;">Payment Method</a></li>
                                        <li><a class="toggle-vis" data-column="6" style="color:blue;">Total Amount (IDR)</a></li>
                                        <li><a class="toggle-vis" data-column="7" style="color:blue;">Status</a></li>
                                        <li><a class="toggle-vis" data-column="8" style="color:blue;">Created</a></li>                                 
                                        <li><a class="toggle-vis" data-column="9" style="color:blue;">Action</a></li>
                                    </ul>
                                </div>
                                <div class="btn-group m-l-4 m-r-4 pull-right" style="padding-left:3px; padding-right:3px;">
                                    <a href="javascript:void(0)" aria-expanded="true" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action 
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
                                        <option value="{{ $item->id }}" {{ $item->id== request()->division_id || Session::get('pr-division_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="position_id">
                                        <option value=""> - Choose Position - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->position_id || Session::get('pr-position_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="employee_status">
                                    <option value="">- Employee Status - </option>
                                    <option {{ (request() and request()->employee_status == 'Permanent') || Session::get('pr-employee_status') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option {{ (request() and request()->employee_status == 'Contract') || Session::get('pr-employee_status') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option {{ (request() and request()->employee_status == 'Internship') || Session::get('pr-employee_status') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    <option {{ (request() and request()->employee_status == 'Outsource') || Session::get('pr-employee_status') == 'Outsource' ? 'selected' : '' }}>Outsource</option>
                                    <option {{ (request() and request()->employee_status == 'Freelance') || Session::get('pr-employee_status') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option {{ (request() and request()->employee_status == 'Consultant') || Session::get('pr-employee_status') == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group  m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || Session::get('pr-name') ? Session::get('pr-name') : '' }}" placeholder="Name/NIK/Number">
                            </div>
                        </div>
                        <input type="hidden" name="action" value="view">
                        <div class="clearfix"></div>
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
                                    <th>NUMBER</th>
                                    <th>TO</th>
                                    <th>FROM</th>
                                    <th>PURPOSE</th>
                                    <!--<th>TRANSACTION TYPE</th>-->
                                    <th>PAYMENT METHOD</th>
                                    <th>TOTAL AMOUNT (IDR)</th>
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
                                        <td>Accounting Department</td>
                                        <td title="{{$item->user->name}}">{{ $item->user->nik }} / {{ str_limit($item->user->name, $limit = 20, $end = '...') }}</td>
                                        <td>{{ str_limit($item->tujuan, $limit = 30, $end = '...') }}</td>
                                        <td>{{ $item->payment_method }}</td>
                                        <td>{{ format_idr(sum_payment_request_price($item->id)) }}</td>
                                        <td>
                                            <a onclick="detail_approval_payment_custom({{ $item->id }})"> 
                                                {!! status_payment_request($item->status) !!}
                                            </a>
                                            <br>
                                            @if($item->status==2 && $item->is_transfer==0 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-warning" style="margin-top: 2px;">Waiting Transfer</span>
                                            @elseif($item->status==2 && $item->is_transfer==1 && $item->payment_method == 'Bank Transfer')
                                                <span class="badge badge-success" style="margin-top: 2px;">Transfered</span>
                                            @endif
                                        </td>
                                        <td>{{ date('d F Y H:i:s', strtotime($item->created_at)) }}</td>
                                        <td>
                                            @if($item->status == 2 && $item->is_transfer == 0)
                                                <a href="{{ route('administrator.paymentRequestCustom.proses', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5">detail</button></a>
                                            @else
                                                <a href="{{ route('administrator.paymentRequestCustom.proses', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-search-plus"></i> detail</a>
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
        $("#btn_pembatalan").click(function(){

            if($("textarea[name='note']").val() == "")
            {
                bootbox.alert('Alasan pembatalan harus diisi ');
                return false;
            }

            $("#form-pembatalan").submit();
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
