@extends('layouts.administrator')

@section('title', 'Loan Payment')

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
                <h4 class="page-title">Form Loan Payment</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Loan Payment</li>
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

                    <h3>Form Loan Payment</h3>
                    <br />
                    <form class="form-horizontal" id="form-loan" enctype="multipart/form-data" action="{{ route('administrator.loan-payment.update', $data->id) }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="col-md-6" style="padding-left:0;">
                            <div class="form-group">
                                <label class="col-md-12">Employee NIK</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ $data->loan->user->nik }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Employee Name</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ $data->loan->user->name }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Position</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ isset($data->loan->user->structure->position->name) ? $data->loan->user->structure->position->name:''}}{{ isset($data->loan->user->structure->division->name) ? ' - '. $data->loan->user->structure->division->name:''}}{{ isset($data->loan->user->structure->title->name) ? ' - '. $data->loan->user->structure->title->name:''}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Loan Number</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" value="{{ $data->loan->number }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="padding:0;">
                            <hr style="margin-right:15px;">
                            <div class="col-md-6" style="padding-left:0;">
                                <div class="form-group">
                                    <label class="col-md-12">Refund Method</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" value="{{ $data->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company' }}" disabled>
                                    </div>
                                </div>
                                @if(!$data->status || ($data->status && $data->status <= 3))
                                <div class="form-group">
                                    <label class="col-md-6">Payment Date</label>
                                    <label class="col-md-6">Submit Date</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="{{ date('Y-m-d', strtotime($data->payment_date)) }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="{{ date('Y-m-d', strtotime($data->submit_date)) }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Approval Note</label>
                                    <div class="col-md-12">
                                        <textarea name="approval_note" id="approval_note" class="form-control" {{ $data->status != 1 ? 'disabled' : '' }}>{{ $data->approval_note }}</textarea>
                                    </div>
                                </div>
                                @else
                                <div class="form-group">
                                    <label class="col-md-12">Payroll</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" value="{{ date('Y-m', strtotime($data->payrollHistory->created_at)) }}" disabled>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if(!$data->status || ($data->status && $data->status <= 3))
                                <div class="form-group">
                                    <label class="col-md-12">Tenor</label>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" value="{{ $data->tenor }}" disabled>
                                    </div>
                                    <div class="col-md-10">
                                        <a onclick="preview_image()" class="btn btn-default" style="padding: 8px 12px;"><i class="fa fa-search-plus"></i> View Receipt</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Amount (IDR)</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" value="{{ format_idr($data->amount) }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Payment Note</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" disabled>{{ $data->user_note }}</textarea>
                                    </div>
                                </div>
                                @else
                                <div class="form-group">
                                    <label class="col-md-12">Tenor</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" value="{{ $data->tenor }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Amount</label>
                                    <div class="col-md-12">
                                        <input type="number" class="form-control" value="{{ $data->amount }}" disabled>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="status" value="0" />
                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12">
                            <a href="{{ route('administrator.loan-payment.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            @if($data->status == 1)
                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_approved"><i class="fa fa-check"></i> Approve</a>
                            <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_tolak"><i class="fa fa-close"></i> Reject</a>
                            @endif
                            <br style="clear: both;" />
                        </div>
                        <div class="clearfix"></div>
                    </form>
                                        
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

@section('footer-script')
<style>
    .noteError {
        border-color: red;
    }
</style>
<script>
    showAttachment()

    function showAttachment(){
        div = "";
        @if(!empty($data->photo) && file_exists(public_path('storage/file-loan-payment/').$data->photo))
            img = "{{ asset('storage/file-loan-payment/'. $data->photo) }}";
            var ext = img.split('.').pop().toLowerCase();
            if(ext === 'pdf'){
                div += '<div><embed src="'+img+'" frameborder=\'0\' width=\'100%\' height=\'400px\'></div>';
            } else {
                div += '<div><img src="'+img+'" style=\'width: 100%;\' /></div>';
            }
        @endif
        $('#result_image').html(div)
    }

    function preview_image() {
        $('#modal_file').modal('show');
    }

    $("#btn_approved").click(function(){
        if(!$("#approval_note").val()){
            alertNote()
            $("#approval_note").addClass('noteError');
        }
        else{
            $("#approval_note").removeClass('noteError');
            bootbox.confirm('Approve Employee Loan?', function(result){
                $("input[name='status']").val(1);
                if(result)
                {
                    $('#form-loan').submit();
                }
            });
        }
    });

    $("#btn_tolak").click(function(){
        if(!$("#approval_note").val()){
            alertNote()
            $("#approval_note").addClass('noteError');
        }
        else{
            $("#approval_note").removeClass('noteError');
            bootbox.confirm('Reject Employee Loan?', function(result){
                if(result)
                {
                    $('#form-loan').submit();
                }
            });
        }
    });

    function alertNote(){
        bootbox.alert({
            message: "Note Approval can't be empty. Please fill the note!",
            size: 'small'
        });
    }
</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
