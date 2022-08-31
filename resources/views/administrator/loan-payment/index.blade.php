@extends('layouts.administrator')

@section('title', 'Loan Payment')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Loan Payment</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="col-md-12 pull-right" style="padding:0px;">
                    <form method="POST" action="{{ route('administrator.loan-payment.index') }}" id="filter-form">
                        {{ csrf_field() }}
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div style="padding-left:0; float: right;">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                        <li><a class="toggle-vis" data-column="1" style="color:blue;">Loan Number</a></li> 
                                        <li><a class="toggle-vis" data-column="2" style="color:blue;">Employee</a></li> 
                                        <li><a class="toggle-vis" data-column="3" style="color:blue;">Position</a></li>
                                        <li><a class="toggle-vis" data-column="4" style="color:blue;">Tenor </a></li>
                                        <li><a class="toggle-vis" data-column="5" style="color:blue;">Due Date</a></li>
                                        <li><a class="toggle-vis" data-column="6" style="color:blue;">Amount (IDR)</a></li> 
                                        <li><a class="toggle-vis" data-column="7" style="color:blue;">Refund Method</a></li>
                                        <li><a class="toggle-vis" data-column="8" style="color:blue;">Submit Date</a></li> 
                                        <li><a class="toggle-vis" data-column="9" style="color:blue;">Payroll</a></li> 
                                        <li><a class="toggle-vis" data-column="10" style="color:blue;">Status</a></li>
                                        <li><a class="toggle-vis" data-column="11" style="color:blue;">Action </a></li>
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
                                        <option value="{{ $item->id }}" {{ $item->id== request()->division_id || Session::get('lp-division_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="position_id">
                                    <option value=""> - Choose Position - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->position_id || Session::get('lp-position_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || Session::get('lp-name') ? Session::get('lp-name') : '' }}" placeholder="Name / NIK Employee">
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="number" class="form-control form-control-line" value="{{ (request() and request()->number) || Session::get('lp-number') ? Session::get('lp-number') : '' }}" placeholder="Loan Number">
                            </div>
                        </div>
                        <input type="hidden" name="action" value="view">
                    </form>
                </div>
            </div>
            <!-- /.col-lg-12 -->
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
                                    <th>LOAN NUMBER</th>
                                    <th>EMPLOYEE</th>
                                    <th>POSITION</th>
                                    <th>TENOR</th>
                                    <th>DUE DATE</th>
                                    <th>AMOUNT (IDR)</th>
                                    <th>REFUND METHOD</th>
                                    <th>SUBMIT DATE</th>
                                    <th>PAYROLL</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->loan->number }}</td>
                                        <td title="{{$item->loan->user->name}}">{{ $item->loan->user->nik }} - {{ str_limit($item->loan->user->name, $limit = 20, $end = '...') }}</td>
                                        <td>{{ $item->loan->user->structure ? $item->loan->user->structure->position->name.($item->loan->user->structure->division ? ' - '.$item->loan->user->structure->division->name : '').($item->loan->user->structure->title ? ' - '.$item->loan->user->structure->title->name : '') : "" }}</td>
                                        <td>{{ $item->tenor }}</td>
                                        <td>{{ date('Y-m-d', strtotime($item->due_date)) }}</td>
                                        <td>{{ format_idr($item->amount) }}</td>
                                        <td>{{ $item->payment_type == 1 ? 'Deduct Salary' : ($item->payment_type == 2 ? 'Transfer to Company' : '') }}</td>
                                        <td>{{ $item->submit_date ? date('Y-m-d', strtotime($item->submit_date)) : '' }}</td>
                                        <td>{{ $item->payrollHistory ? date('Y-m', strtotime($item->payrollHistory->created_at)) : '' }}</td>
                                        <td>
                                            <a onclick="detail_status({{ $item }})"> 
                                                {!! !$item->status ? '<button id="status" type="button" class="btn btn-default btn-xs">Not Yet Paid</button>' : ($item->status == 1 ? '<button id="status" type="button" class="btn btn-warning btn-xs">Waiting Approval</button>' : ($item->status == 2 ? '<button id="status" type="button" class="btn btn-success btn-xs">Approved</button>' : ($item->status == 3 ? '<button id="status" type="button" class="btn btn-danger btn-xs">Rejected</button>' : ($item->status == 4 ? '<button id="status" type="button" class="btn btn-warning btn-xs">Waiting Lock Payroll</button>' : '<button id="status" type="button" class="btn btn-success btn-xs">Payroll Locked</button>')))) !!}
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->status == 1)
                                                <a href="{{ route('administrator.loan-payment.proses', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> process</button></a>
                                            @else
                                                <a href="{{ route('administrator.loan-payment.proses', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
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

<div class="modal fade none-border" id="modal-status">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Payment Approval</h4>
            </div>
            <div class="modal-body" id="modal_content_history_approval">
                <div class="panel-body" style="padding: 0 15px;">
                    <div class="steamline">
                        <div class="sl-item"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

    function detail_status(data) {
        if (!data.status) {
            $('.sl-item').html('<div class="sl-left bg-default"><i class="fa fa-ban" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<p>-</p>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 1) {
            $('.sl-item').html('<div class="sl-left bg-warning"><i class="fa fa-info" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">LOAN HR</a>'+
                    '</div>'+
                    '<div>'+
                        @foreach(getAdminByModule(33) as $val)
                        '<p>{{ $val->name }}</p>'+
                        @endforeach
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 2) {
            $('.sl-item').html('<div class="sl-left bg-success"><i class="fa fa-check" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">LOAN HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<div>'+data.approver.name+'</div>'+
                        data.approval_date+
                        '<p>'+data.approval_note+'</p>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 3) {
            $('.sl-item').html('<div class="sl-left bg-danger"><i class="fa fa-close" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">LOAN HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<div>'+data.approver.name+'</div>'+
                        data.approval_date+
                        '<p>'+data.approval_note+'</p>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 4) {
            $('.sl-item').html('<div class="sl-left bg-warning"><i class="fa fa-info" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">PAYROLL HR</a>'+
                    '</div>'+
                    '<div>'+
                        @foreach(getAdminByModule(13) as $val)
                        '<p>{{ $val->name }}</p>'+
                        @endforeach
                        '<br>'+
                    '</div>'+
                '</div>')
        } else if (data.status == 5) {
            $('.sl-item').html('<div class="sl-left bg-success"><i class="fa fa-check" style="line-height: 40px !important;"></i></div>'+
                '<div class="sl-right">'+
                    '<div>'+
                        '<a href="#">PAYROLL HR</a>'+
                    '</div>'+
                    '<div>'+
                        '<div>'+ (data.approver != null ? data.approver.name : 'AUTO LOCK SYSTEM') +'</div>'+
                        '<br>'+
                    '</div>'+
                '</div>')
        }

        $('#modal-status').modal('show');
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