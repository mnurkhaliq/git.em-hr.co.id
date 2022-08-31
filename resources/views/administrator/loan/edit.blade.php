@extends('layouts.administrator')

@section('title', 'Loan')

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
                <h4 class="page-title">Form Loan</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Loan</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#Form" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Form</span></a></li>
                        <li role="presentation" class=""><a href="#Payment" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Payment</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="Form">
                            <h3>Form Loan</h3>
                            <br />
                            <form class="form-horizontal" id="form-loan" enctype="multipart/form-data" action="{{ route('administrator.loan.update', $data->id) }}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                {{ csrf_field() }}
                                <div class="col-md-6" style="padding-left:0;">
                                    <div class="form-group">
                                        <label class="col-md-12">NIK / Employee Name</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->user->nik .' - '. $data->user->name }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Position</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ isset($data->user->structure->position) ? $data->user->structure->position->name:''}}{{ isset($data->user->structure->division) ? ' - '. $data->user->structure->division->name:''}}{{ isset($data->user->structure->title) ? ' - '. $data->user->structure->title->name:''}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Loan Number</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->number }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Name of Account</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->user->nama_rekening }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Account Number</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->user->nomor_rekening }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Name Of Bank</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ isset($data->user->bank) ? $data->user->bank->name : '' }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-left:0;">
                                    <div class="form-group">
                                        <label class="col-md-12">Max Loan Plafond (IDR)</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ format_idr($data->plafond) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Available Loan Plafond (IDR)</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ format_idr($data->available_plafond) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Amount (IDR)</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ format_idr($data->amount) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Total Tenor(Month)</label>
                                        <div class="col-md-12">
                                            <input type="number" class="form-control" value="{{ $data->rate }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Interest(%)</label>
                                        <div class="col-md-12">
                                            <input type="number" class="form-control" value="{{ $data->interest }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Calculated (IDR)</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ format_idr($data->calculated_amount) }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Purpose</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->loan_purpose }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Request Date</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ date('Y-m-d', strtotime($data->created_at)) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Expected Cash Disbursement Date</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ date('Y-m-d', strtotime($data->expected_disbursement_date)) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Actual Cash Disbursement Date</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->disbursement_date ? date('Y-m-d', strtotime($data->disbursement_date)) : '' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Refund Method</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company' }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <br />
                                <div class="col-md-12" style="padding-left:0;">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" checked disabled>
                                                <label class="form-check-label">
                                                    I agree to <span style="cursor: pointer;" onclick="preview_term()"><b><u>Term & Condition</u></b></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php($assets = '<ol>')
                                @if(count($data->asset))
                                <div class="col-md-6 asset" style="padding-left:0;">
                                    <div class="clearfix"></div>
                                    <hr />
                                    <h3>Collateral</h3>
                                    <br />
                                    @foreach($data->asset as $item)
                                    @php($assets .= '<li>' . $item->asset_name . '</li>')
                                    <div class="form-group">
                                        <label class="col-md-12">Photo {{ $item->asset_name }}</label>
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <input type="file" name="photos[{{ $item->id }}]" data-key="key-{{ $item->id }}" class="form-control image" accept="image/*, application/pdf" disabled />
                                            </div>
                                            <div class="col-md-6">
                                                <a onclick="preview_image('{{ $item->id }}')" class="btn btn-default"><i class="fa fa-search-plus"></i> View</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="clearfix"></div>
                                    <br />
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" checked disabled>
                                                <label class="form-check-label">
                                                    I agree to <span style="cursor: pointer;" onclick="preview_collateral()"><b><u>Collateral Receipt</u></b></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @php($assets .= '</ol>')
                                @foreach($data->historyApproval as $key => $item)
                                    <div class="form-group">
                                        <label class="col-md-12">Note Approval {{ $item->setting_approval_level_id }}</label>
                                        <div class="col-md-8">
                                            <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                        </div>
                                    </div>
                                @endforeach
                                @if(!$data->historyApproval->where('is_approved', '!=', 1)->count())
                                <div class="col-md-8" style="padding-left:0;">
                                    <div class="clearfix"></div>
                                    <hr />
                                    <h3>Admin Approval</h3>
                                    <br />
                                    <div class="table-responsive">
                                        <table class="table table-hover manage-u-table">
                                            <thead>
                                                <tr>
                                                    <th width="10%">STEP</th>
                                                    <th width="15%">ACTUAL CASH DISBURSEMENT DATE</th>
                                                    <th width="15%">FIRST DUE DATE</th>
                                                    <th>NOTE APPROVAL</th>
                                                    <th width="1%">REJECT</th>
                                                    <th width="1%">APPROVE</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-content-lembur">
                                                @if($data->asset->count())
                                                <tr>
                                                    <td>Collateral Photo Check</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><input type="text" class="form-control" id="note_1" name="note_1" value="{{ $data->approval_collateral_receipt_note }}" {{ isset($data->approval_collateral_receipt_status) ? 'disabled' : '' }}></td>
                                                    <td>
                                                        <div class="radio reject icheck-danger col-xs-6 p-0">
                                                            <input type="radio" id="danger-1" name="approval_1" value="0" {{ $data->approval_collateral_receipt_status === '0' ? 'checked' : '' }} {{ isset($data->approval_collateral_receipt_status) ? 'disabled' : '' }}>
                                                            <label for="danger-1"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="radio icheck-success col-xs-6 p-0">
                                                            <input type="radio" id="success-1" name="approval_1" value="1" {{ $data->approval_collateral_receipt_status === '1' ? 'checked' : '' }} {{ isset($data->approval_collateral_receipt_status) ? 'disabled' : '' }}>
                                                            <label for="success-1"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Collateral Physical Check</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><input type="text" class="form-control" id="note_2" name="note_2" value="{{ $data->approval_collateral_physical_note }}" {{ isset($data->approval_collateral_receipt_status) && !isset($data->approval_collateral_physical_status) ? '' : 'disabled' }}></td>
                                                    <td>
                                                        <div class="radio reject icheck-danger col-xs-6 p-0">
                                                            <input type="radio" id="danger-2" name="approval_2" value="0" {{ $data->approval_collateral_physical_status === '0' ? 'checked' : '' }} {{ isset($data->approval_collateral_receipt_status) && !isset($data->approval_collateral_physical_status) ? '' : 'disabled' }}>
                                                            <label for="danger-2"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="radio icheck-success col-xs-6 p-0">
                                                            <input type="radio" id="success-2" name="approval_2" value="1" {{ $data->approval_collateral_physical_status === '1' ? 'checked' : '' }} {{ isset($data->approval_collateral_receipt_status) && !isset($data->approval_collateral_physical_status) ? '' : 'disabled' }}>
                                                            <label for="success-2"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td>Loan</td>
                                                    <td><input type="text" class="form-control datepicker date_3" name="disbursement_date" value="{{ $data->disbursement_date }}" {{ (isset($data->approval_physical_receipt_status) || !$data->asset->count()) && !isset($data->approval_loan_status) ? '' : 'disabled' }}></td>
                                                    <td><input type="text" class="form-control datepicker date_3" name="first_due_date" value="{{ $data->first_due_date }}" {{ (isset($data->approval_physical_receipt_status) || !$data->asset->count()) && !isset($data->approval_loan_status) ? '' : 'disabled' }}></td>
                                                    <td><input type="text" class="form-control" id="note_3" name="note_3" value="{{ $data->approval_loan_note }}" {{ (isset($data->approval_physical_receipt_status) || !$data->asset->count()) && !isset($data->approval_loan_status) ? '' : 'disabled' }}></td>
                                                    <td>
                                                        <div class="radio reject icheck-danger col-xs-6 p-0">
                                                            <input type="radio" id="danger-3" name="approval_3" value="0" {{ $data->approval_loan_status === '0' ? 'checked' : '' }} {{ (isset($data->approval_physical_receipt_status) || !$data->asset->count()) && !isset($data->approval_loan_status) ? '' : 'disabled' }}>
                                                            <label for="danger-3"></label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="radio icheck-success col-xs-6 p-0">
                                                            <input type="radio" id="success-3" name="approval_3" value="1" {{ $data->approval_loan_status === '1' ? 'checked' : '' }} {{ (isset($data->approval_physical_receipt_status) || !$data->asset->count()) && !isset($data->approval_loan_status) ? '' : 'disabled' }}>
                                                            <label for="success-3"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                                <input type="hidden" name="approver_assign"/>
                                <input type="hidden" name="photo_assign"/>
                                <div class="clearfix"></div>
                                <br />
                                <div class="col-md-12">
                                    <a href="{{ route('administrator.loan.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                                    @if(!$data->historyApproval->where('is_approved', '!=', 1)->count() && $data->status == 1)
                                    <a class="btn btn-sm btn-primary waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-check"></i> Submit Approval</a>
                                    @endif
                                    <br style="clear: both;" />
                                </div>
                                <div class="clearfix"></div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="Payment">
                            <h3>Payment</h3>
                            <br />
                            <div class="table-responsive">
                                <table id="myTable" class="table display nowrap sticky-header" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Tenor</th>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Refund Method</th>
                                            <th>Submit Date</th>
                                            <th>Payroll</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>

<div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_image" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_assign" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Enter Signature</h4>
            </div>
            <div class="modal-body form-horizontal text center">
                <div id="enterSignature"></div>
                <div class="form-group" style="margin:10px; margin-bottom:0; margin-top:30px;">
                    <label>Or Upload Signature Image File</label>
                    <div>
                        <input type="file" id="uploadSignature" class="form-control" accept="image/*" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning waves-effect btn-sm" onclick="resetCanvas()">Reset</button>
                <button type="button" class="btn btn-danger waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary waves-effect btn-sm">Done</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_term" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 style="font-weight:bold">LOAN AGREEMENT</h3>
                    </div>
                    <div class="col-md-3">
                        Employee
                    </div>
                    <div class="col-md-9">
                        : {{ $data->user->nik .' - '. $data->user->name }}
                    </div>
                    <div class="col-md-3">
                        Position
                    </div>
                    <div class="col-md-9">
                        : {{ isset($data->user->structure->position) ? $data->user->structure->position->name:''}}{{ isset($data->user->structure->division) ? ' - '. $data->user->structure->division->name:''}}{{ isset($data->user->structure->title) ? ' - '. $data->user->structure->title->name:''}}
                    </div>
                    <div class="col-md-3">
                        Loan Number
                    </div>
                    <div class="col-md-9">
                        : {{ $data->number }}
                    </div>
                </div>
                <hr style="height:2px; border-top:1px solid black; border-bottom:1px solid black;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Purpose</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="loan_purpose">{{ $data->loan_purpose }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Refund Method</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="payment_type">{{ $data->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company' }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Amount</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="amount">{{ $data->amount }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Total Tenor(Month)</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="rate">{{ $data->rate }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Actual Cash Disbursement Date</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="disbursement_date">{{ $data->disbursement_date ? date('Y-m-d', strtotime($data->disbursement_date)) : '' }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">First Payment Due Date</label>
                            <div class="col-md-12">
                                <h4 style="margin:0" id="first_due_date">{{ $data->first_due_date ? date('Y-m-d', strtotime($data->first_due_date)) : '' }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 term_date" style="display:none;">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Due Date Monthly</label>
                            <div id="term_date"></div>
                        </div>
                    </div>
                    <div class="col-md-6 term_amount" style="display:none;">
                        <div class="form-group">
                            <label class="col-md-12" style="margin:0">Amount</label>
                            <div id="term_amount"></div>
                        </div>
                    </div>
                </div>
                {!! get_setting('term_condition') !!}
                <br>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div><label>Requestor</label></div>
                        <img class="user_assign" style="height: 80px; width: auto;" src="{{ $data->user_assign ? asset('storage/file-loan-assign/') . '/' . $data->user_assign : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" />
                        <div style="font-weight:bold">{{ $data->user->name }}</div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div><label>Approver</label></div>
                        <img class="approver_assign" style="height: 80px; width: auto;" src="{{ $data->approver_assign ? asset('storage/file-loan-assign/') . '/' . $data->approver_assign : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" />
                        <div class="approver_assign_name" style="font-weight:bold">{{ isset($data->loanApprover) && isset($data->loanApprover->name) ? $data->loanApprover->name : "" }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" onclick="resetCanvas()" data-dismiss="modal">Close</button>
                <button type="button" onclick="$('#danger-3').prop('checked', true).change()" class="btn btn-danger waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="showCanvas('term'); resetCanvas();" class="btn btn-primary waves-effect btn-sm" data-dismiss="modal">I Accept</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_collateral" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h3 style="font-weight:bold">COLLATERAL RECEIPT</h3>
                    </div>
                    <div class="col-md-3">
                        Employee
                    </div>
                    <div class="col-md-9">
                        : {{ $data->user->nik .' - '. $data->user->name }}
                    </div>
                    <div class="col-md-3">
                        Position
                    </div>
                    <div class="col-md-9">
                        : {{ isset($data->user->structure->position) ? $data->user->structure->position->name:''}}{{ isset($data->user->structure->division) ? ' - '. $data->user->structure->division->name:''}}{{ isset($data->user->structure->title) ? ' - '. $data->user->structure->title->name:''}}
                    </div>
                    <div class="col-md-3">
                        Loan Number
                    </div>
                    <div class="col-md-9">
                        : {{ $data->number }}
                    </div>
                </div>
                <hr style="height:2px; border-top:1px solid black; border-bottom:1px solid black;">
                {!! str_replace('$collateral',$assets,get_setting('collateral_receipt')) !!}
                <br>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div><label>Requestor</label></div>
                        <img class="collateral_assign" style="height: 80px; width: auto;" src="{{ $data->collateral_assign ? asset('storage/file-loan-assign/') . '/' . $data->collateral_assign : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" />
                        <div style="font-weight:bold">{{ $data->user->name }}</div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div><label>Approver</label></div>
                        <img class="photo_assign" style="height: 80px; width: auto;" src="{{ $data->photo_assign ? asset('storage/file-loan-assign/') . '/' . $data->photo_assign : 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' }}" />
                        <div class="photo_assign_name" style="font-weight:bold">{{ isset($data->receiptApprover) && isset($data->receiptApprover->name) ? $data->receiptApprover->name : "" }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" onclick="resetCanvas()" data-dismiss="modal">Close</button>
                <button type="button" onclick="$('#danger-1').prop('checked', true).change()" class="btn btn-danger waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" onclick="showCanvas('collateral'); resetCanvas();" class="btn btn-primary waves-effect btn-sm" data-dismiss="modal">I Accept</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

<div class="modal fade none-border" id="modal-action">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><strong>Payment Detail</strong></h4>
            </div>
            <form id="optionForm">
                <input type="hidden" id="loan_payment_id" name="loan_payment_id">
                <div class="modal-body" id="modal-action-body">
                    <div class="form-group col-md-12">
                        <label>Payment Receipt <span class="text-danger">*</span></label>
                        <output id="result_photo" />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Payment Date <span class="text-danger">*</span></label>
                        <div>
                            <input type="text" id="payment_date" name="payment_date" class="form-control datepicker" required />
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Payment Note <span class="text-danger">*</span></label>
                        <div>
                            <textarea id="user_note" name="user_note" class="form-control" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('footer-script')
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.4.0/fabric.min.js'></script>
<link href="https://www.cssscript.com/demo/pure-css-checkbox-radio-button-replacement-bootstrap-icheck/icheck-bootstrap.css" rel="stylesheet" type="text/css">
<style>
    #sheet {
        border:1px solid black;
    }
    .canvas-container {
        margin:0 auto ;
    }
    .table-hover>tbody>tr:hover {
        background-color: white !important;
    }
    .approval-note > td {
        border: 0px !important;
        vertical-align: middle !important;
        padding-top: 0 !important;
    }
    .radio label::after {
        background-color: rgba(255, 255, 255, 0) !important;
    }
    .radio>input[type=radio]:first-child:not(:checked)+label::before, .checkbox>input[type=checkbox]:first-child:not(:checked)+label::before, .radio>input[type=radio]:first-child:not(:checked)+input[type=hidden]+label::before, .checkbox>input[type=checkbox]:first-child:not(:checked)+input[type=hidden]+label::before, .radio>input[type=radio]:first-child:checked+label::before, .checkbox>input[type=checkbox]:first-child:checked+label::before, .radio>input[type=radio]:first-child:checked+input[type=hidden]+label::before, .checkbox>input[type=checkbox]:first-child:checked+input[type=hidden]+label::before {
        position: inherit !important;
    }
    .reject>input[type=radio]:first-child:checked+label::before {
        content: "\e014" !important;
    }
    .radio+.radio, .checkbox+.checkbox {
        margin-top: 0 !important;
    }
    .radio {
        padding: 13% 0!important;
    }
    .radio>input[type=radio]:first-child:not(:checked)+label::before, .radio>input[type=radio]:first-child:not(:checked)+input[type=hidden]+label::before, .radio>input[type=radio]:first-child:checked+label::before, .radio>input[type=radio]:first-child:checked+input[type=hidden]+label::before {
        border-radius: 0 !important;
    }
    .noteError {
        border-color: red;
    }
    .table.dataTable {
        margin-top: 0 !important;
    }
</style>
<script>
    var canvas = null;
    var emptyCanvas = null;

    function showCanvas(type) {
        $('#modal_assign .btn-primary').unbind("click").click(function () {
            if (($('#sheet').length && document.getElementById("sheet").toDataURL() != emptyCanvas) || document.getElementById("uploadSignature").files.length != 0) {
                $('#modal_assign').modal('hide');

                setCanvas(type);
                
                if (type == 'term') {
                    preview_term();
                } else {
                    preview_collateral();
                }
            } else {
                bootbox.alert("Signature can't be blank!");
            }
        });

        $('#modal_assign .btn-danger').unbind("click").click(function () {
            resetCanvas();

            if (type == 'term') {
                $('#danger-3').prop('checked', true).change()
            } else {
                $('#danger-1').prop('checked', true).change()
            }
        });

        $('#modal_assign').modal({backdrop: 'static', keyboard: false}, 'show').css('overflow-y', 'auto');

        setTimeout(function(){
            canvas.calcOffset();
        }, 1000);
    }

    function resetCanvas() {
        $("#uploadSignature").val('');

        $('#modal_assign #enterSignature').html('<canvas id="sheet" width="550px" height="400"></canvas>');

        canvas = new fabric.Canvas("sheet");
        canvas.isDrawingMode = true;
        canvas.freeDrawingBrush.width = 5;
        canvas.freeDrawingBrush.color = "#000000";

        emptyCanvas = document.getElementById("sheet").toDataURL();
    }

    var myTable = null

    $(document).ready(function() {
        initMyTable();
    });

    function initMyTable() {
        $('#myTable').DataTable().destroy();
        myTable = $('#myTable').DataTable({
            ajax: {
                "url": "{{ route('administrator.loan.table', $data->id) }}",
                "type": "GET"
            },
            searching: false,
            paging: false,
            ordering: false,
            lengthChange: false,
            bInfo : false,
            columns: [
                { "data": "tenor" },
                { "data": "due_date" },
                { "data": "column_amount" },
                { "data": "column_payment_type", "name":'loan.payment_type' },
                { "data": "submit_date" },
                { "data": "column_payroll" },
                { "data": "column_status" },
                { "data": "column_action" },
            ],
            initComplete: function (settings, json) {
                if (json.data.length > 0) {
                    $(".term_date, .term_amount").show();
                }
                $.each(json.data, function (key, val) {
                    $("#term_date").append('<div class="col-md-12">' + val.due_date + '</div>');
                    $("#term_amount").append('<div class="col-md-12">' + val.amount + '</div>');
                });
            }
        });

        $('#myTable tbody').on('click', 'button', function () {
            var data = myTable.row($(this).parents('tr')).data()
            if (this.id == 'status')
                optionStatus(data)
            else
                optionAction(data)
        })
    }

    function optionStatus(data) {
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

    $(document).on('hide.bs.modal', '#modal-action', function () {
        $('#result_photo').html('')
        $('#modal-action #payment_date').val('').removeAttr('disabled')
        $('#modal-action #user_note').val('').removeAttr('disabled')
        $('#modal-action #loan_payment_id').val('')
    })

    function optionAction(data) {
        if (data.photo) {
            img = "{{ asset('storage/file-loan-payment/') }}/"+data.photo;
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                div = '<div><embed src="'+img+'" frameborder=\'0\' width=\'100%\' height=\'400px\'></div>';
            } else {
                div = '<div><img src="'+img+'" style=\'width: 100%;\' /></div>';
            }
            $('#result_photo').html(div)
        }
        $('#modal-action #payment_date').val(data.payment_date).attr('disabled', true)
        $('#modal-action #user_note').val(data.user_note).attr('disabled', true)
        $('#modal-action #loan_payment_id').val(data.id)
        $('#modal-action').modal('show');
    }

    showAttachment()

    function showAttachment(){
        div = "";
        @foreach($data->asset as $file)
            @if(!empty($file->photo) && file_exists(public_path('storage/file-loan/').$file->photo))
                img = "{{ asset('storage/file-loan/'. $file->photo) }}";
                var ext = img.split('.').pop().toLowerCase();
                if(ext === 'pdf'){
                    div += '<div id="key-{{ $file->id }}"><embed src="'+img+'" frameborder=\'0\' width=\'100%\' height=\'400px\'></div>';
                } else {
                    div += '<div id="key-{{ $file->id }}"><img src="'+img+'" style=\'width: 100%;\' /></div>';
                }
            @endif
        @endforeach
        $('#result_image').html(div)
    }

    function preview_image(params) {
        $("#key-"+params).siblings().hide();
        $("#key-"+params).show();
        $('#modal_file').modal('show');
    }

    function preview_term(agreement = false) {
        $("#disbursement_date").html($("input[name='disbursement_date']").val());
        $("#first_due_date").html($("input[name='first_due_date']").val());

        if (agreement) {
            $('#modal_term .btn-default').hide();
            $('#modal_term .btn-primary').show();
            $('#modal_term .btn-danger').show();
        } else {
            $('#modal_term .btn-primary').hide();
            $('#modal_term .btn-danger').hide();
            $('#modal_term .btn-default').show();
        }

        $('#modal_term').modal({backdrop: 'static', keyboard: false}, 'show').css('overflow-y', 'auto');
    }
    
    function preview_collateral(agreement = false) {
        if (agreement) {
            $('#modal_collateral .btn-default').hide();
            $('#modal_collateral .btn-primary').show();
            $('#modal_collateral .btn-danger').show();
        } else {
            $('#modal_collateral .btn-primary').hide();
            $('#modal_collateral .btn-danger').hide();
            $('#modal_collateral .btn-default').show();
        }

        $('#modal_collateral').modal({backdrop: 'static', keyboard: false}, 'show').css('overflow-y', 'auto');
    }

    function setCanvas(type) {
        if ($('#sheet').length) {
            if (type == 'term') {
                $(".approver_assign").attr('src', document.getElementById("sheet").toDataURL());
                $("input[name='approver_assign']").val(document.getElementById("sheet").toDataURL());
            } else {
                $(".photo_assign").attr('src', document.getElementById("sheet").toDataURL());
                $("input[name='photo_assign']").val(document.getElementById("sheet").toDataURL());
            }
        } else {
            let reader = new FileReader();
            reader.readAsDataURL(document.getElementById('uploadSignature').files[0]);
            reader.onload = function () {
                if (type == 'term') {
                    $(".approver_assign").attr('src', reader.result);
                    $("input[name='approver_assign']").val(reader.result);
                } else {
                    $(".photo_assign").attr('src', reader.result);
                    $("input[name='photo_assign']").val(reader.result);
                }
            }
        }
    }

    window.onload = function() {
        //Check File API support
        if (window.File && window.FileList && window.FileReader) {
            $(".image").on("change", function(event) {
                var files = event.target.files; //FileList object
                var output = document.getElementById("result_image");
                var key = $(this).attr('data-key');
                $("#"+key).remove();
                if (files.length) {
                    $(this).parent().next().children().show();
                } else {
                    $(this).parent().next().children().hide();
                }
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (!file.type.match('image') && !file.type === 'application/pdf') {
                        $(this).parent().next().children().hide();
                        continue;
                    }
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                        var picFile = event.target;
                        var div = document.createElement("div");
                        div.setAttribute("id", key);
                        if(!file.type.match('image')){
                            div.innerHTML = "<embed src='" + picFile.result + "' frameborder=\'0\' width=\'100%\' height=\'400px\' >";
                        } else {
                            div.innerHTML = "<img src='" + picFile.result + "' style=\'width: 100%;\' />";
                        }
                        output.insertBefore(div, null);
                    });
                    //Read the image
                    picReader.readAsDataURL(file);
                }
            });

            $("#uploadSignature").on("change", function(event) {
                var files = event.target.files; //FileList object
                var output = document.getElementById("enterSignature");
                $("#enterSignature canvas, #enterSignature div").remove();
                if (!files.length) {
                    resetCanvas();
                }
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (!file.type.match('image')) {
                        resetCanvas();
                        bootbox.alert("Can't upload other than image file!");
                        continue;
                    }
                    var picReader = new FileReader();
                    picReader.addEventListener("load", function(event) {
                        var picFile = event.target;
                        var div = document.createElement("div");
                        div.innerHTML = '<img src="' + picFile.result + '" width="550px" height="400" />';
                        output.insertBefore(div, null);
                    });
                    //Read the image
                    picReader.readAsDataURL(file);
                }
            });
        } else {
            console.log("Your browser does not support File API");
        }
    }

    $("input[name='approval_1']").change(function() {
        if($(this).val() == 1) {
            $("input[name='approval_2']").removeAttr('disabled');
            $("input[name='note_2']").removeAttr('disabled');

            $('.photo_assign_name').text("{{ Auth::user()->name }}");
            preview_collateral(true);
        } else {
            $("input[name='approval_2']").attr('disabled', true).removeAttr('checked');
            $("input[name='note_2']").attr('disabled', true).val('');
            $("input[name='approval_3']").attr('disabled', true).removeAttr('checked');
            $("input[name='note_3']").attr('disabled', true).val('');
            $(".date_3").attr('disabled', true).val('');

            $('.photo_assign_name').text("");
            $(".photo_assign").attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            $("input[name='photo_assign']").val("");
        }
    });

    $("input[name='approval_2']").change(function() {
        if($(this).val() == 1) {
            $("input[name='approval_3']").removeAttr('disabled');
            $("input[name='note_3']").removeAttr('disabled');
            $(".date_3").removeAttr('disabled');
        } else {
            $("input[name='approval_3']").attr('disabled', true).removeAttr('checked');
            $("input[name='note_3']").attr('disabled', true).val('');
            $(".date_3").attr('disabled', true).val('');
        }
    });

    $("input[name='approval_3']").change(function() {
        if($(this).val() == 1) {
            $(".date_3").removeAttr('disabled');

            $('.approver_assign_name').text("{{ Auth::user()->name }}");
            preview_term(true);
        } else {
            $(".date_3").attr('disabled', true).val('');

            $('.approver_assign_name').text("");
            $(".approver_assign").attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            $("input[name='approver_assign']").val("");
        }
    });

    $("#btn_submit").click(function(){
        let error = false;

        if($("input[type='radio']").not("[disabled]").is(':checked') == false){
            error = true;
        }

        if($("input[name='approval_1']").is(':checked') && !$("input[name='note_1']").val()){
            $("input[name='note_1']").addClass('noteError');
            error = true;
        } else {
            $("input[name='note_1']").removeClass('noteError');
        }

        if($("input[name='approval_2']").is(':checked') && !$("input[name='note_2']").val()){
            $("input[name='note_2']").addClass('noteError');
            error = true;
        } else {
            $("input[name='note_2']").removeClass('noteError');
        }

        if($("input[name='approval_3']").is(':checked') && !$("input[name='note_3']").val()){
            $("input[name='note_3']").addClass('noteError');
            error = true;
        } else {
            $("input[name='note_3']").removeClass('noteError');
        }

        $(".date_3").each(function() {
            if($("input[name='approval_3']").is(':checked') && $("input[name='approval_3']:checked").val() == 1 && !$(this).val()){
                $(this).addClass('noteError');
                error = true;
            } else {
                $(this).removeClass('noteError');
            }
        });

        if(!error){
            bootbox.confirm('Submit Loan Approval?', function(result){
                if(result)
                {
                    $('#form-loan').submit();
                }
            });
        } else {
            alertNote();
        }
    });

    function alertNote() {
        bootbox.alert({
            message: "Complete the field before submit!",
            size: 'small'
        });
    }
</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
