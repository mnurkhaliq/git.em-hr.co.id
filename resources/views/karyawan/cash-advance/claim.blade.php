@extends('layouts.karyawan')

@section('title', 'Cash Advance')

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
                <h4 class="page-title">Form Cash Advance</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Cash Advance</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            @if($data->payment_method == 'Bank Transfer' && $data->status_claim == 2 && $data->is_transfer_claim == 0 )
            <form method="POST" enctype="multipart/form-data" id="form-claim-cash-advance-transfer" action="{{ route('karyawan.cash-advance.prosesTransfer', $data->id) }}">
            @else 
            <form class="form-horizontal" enctype="multipart/form-data" id="form-claim-cash-advance" action="{{ route('karyawan.cash-advance.prosesclaim', $data->id) }}" method="POST">
            @endif
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Data Cash Advance</h3>
                        <br />
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

                        {{ csrf_field() }}
                        <input type="hidden" name="status_claim" value="1">
                        <div class="col-md-6" style="padding-left:0;">
                            <div class="form-group">
                                <label class="col-md-12">CA Number</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" readonly="true" value="{{ $data->number }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">From</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name  }}" readonly="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">To : Accounting Department</label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Purpose</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="tujuan" readonly="true">{{ $data->tujuan }}</textarea>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-md-12">Payment Method</label>
                                <div class="col-md-12">
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" {{ $data->payment_method == 'Cash' ? 'checked="true"' : '' }} value="Cash" disabled/> Cash</label> &nbsp;&nbsp;
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" {{ $data->payment_method == 'Bank Transfer' ? 'checked="true"' : '' }} value="Bank Transfer" disabled/> Bank Transfer</label>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Name of Account</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ isset(Auth::user()->nama_rekening) ? Auth::user()->nama_rekening : '' }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Account Number</label>
                                <div class="col-md-12">
                                    <input type="number" class="form-control" readonly="true" value="{{ isset(Auth::user()->nomor_rekening) ? Auth::user()->nomor_rekening : '' }}" />
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name Of Bank</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ isset(Auth::user()->bank) ? Auth::user()->bank->name : '' }}" />
                                </div>
                            </div>
                            @if($data->payment_method == 'Bank Transfer' && $data->status==2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-12">Proccess</label>
                                    <div class="col-md-12">
                                        <label style="font-weight: normal;"><input class="form-check-input" type="checkbox" {{$data->is_transfer==1 ? 'checked disabled' : ''}} id="is_transfer" name="is_transfer" value="1" disabled>  Has been Proccessed</label> &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" id="disbursement_div">
                                <div class="form-group">
                                    <label class="col-md-12">Disbursement</label>
                                    <div class="col-md-12">
                                        <label style="font-weight: normal;"><input type="radio" id="disbursement_id" name="disbursement" {{ $data->disbursement == 'Transfer' ? 'checked="true"' : '' }} value="Transfer" disabled/> Transfer</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" id="disbursement_id_next" name="disbursement" {{ $data->disbursement == 'Next Payroll' ? 'checked="true"' : '' }} value="Next Payroll" disabled/> Next Payroll</label>
                                    </div>
                                </div>
                            </div>
                            @if($data->transfer_proof == NULL && $data->disbursement == 'Transfer')
                            <div class="col-md-6" id="transfer_proof_div">
                                <div class="form-group">
                                    <label class="col-md-12">Transfer Proof</label>
                                    <div class="col-md-8">
                                        <input type="file" id="transfer_proof_by_admin" name="transfer_proof_by_admin" {{$data->is_transfer==1 ? 'disabled' : ''}} class="form-control " accept="image/*, application/pdf"/>
                                    </div>
                                    <div class="col-md-4">
                                        <a onclick="preview()" class="btn btn-default preview" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                    </div>
                                </div>
                            </div>
                            @elseif($data->transfer_proof != NULL)
                            <a onclick="show_proof('{{ $data->transfer_proof }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                            @endif
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        
                        <div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TYPE</th>
                                        <th>{{get_setting('period_ca_pr') == 'yes' ? 'AVAILABLE ' : '' }} PLAFOND (IDR)</th>
                                        <th>DESCRIPTION</th>
                                        {{--<th>QUANTITY</th>--}}
                                        <th>AMOUNT (IDR)</th>
                                        <th>AMOUNT APPROVED (IDR)</th>
                                        <th>NOTE</th>
                                        <th>ACTUAL AMOUNT (IDR)</th>
                                        <th>RECEIPT TRANSACTION</th>
                                        <th>AMOUNT CLAIMED (IDR)</th>
                                        <th>NOTE CLAIMED</th>
                                        
                                    </tr>
                                </thead>
                                <tbody class="table-content-lembur">
                                    @php($total_cost=0)
                                    @php($total_amount=0)
                                    @php($total_amount_approved=0)
                                    @php($total_amount_claimed=0)
                                    @php($total_actual_amount=0)
                                    @foreach($form as $key => $item)
                                    @php($total_amount +=$item->amount)
                                    @php($total_amount_approved +=$item->nominal_approved)
                                    @php($total_amount_claimed +=$item->nominal_claimed)
                                    @php($total_actual_amount +=$item->actual_amount)
                                    <tr class="oninput">
                                        <input type="hidden" name="cash_advance_form_id[]" class="form-control"  value="{{ $item->id }}" readonly="true">
                                        <td>{{ ($key+1) }}</td>
                                        <td class="type_form">{{ $item->type_form }} @if($item->bensin) <a class="btn btn-info btn-xs" data-tanggal="{{$item->bensin->tanggal}}" data-odo_start="{{$item->bensin->odo_start}}" data-odo_end="{{$item->bensin->odo_end}}" data-liter="{{$item->bensin->liter}}" data-cost="{{$item->bensin->cost}}" onclick="info_bensin(this)"><i class="fa fa-info"></i></a>  @endif</td>
                                        @if(get_setting('period_ca_pr')== 'yes' && $item->plafond != $item->sisa_plafond)
                                        <td class="sisa_plafond_value">
                                            @if(isset($item->nominal_claimed))
                                            @php($sisa_plafond = $item->sisa_plafond+$item->nominal_claimed)
                                            {{ $item->sisa_plafond != null ? format_idr($item->sisa_plafond+$item->nominal_claimed) : '' }}
                                            @elseif(isset($item->actual_amount))
                                            @php($sisa_plafond = $item->sisa_plafond+$item->actual_amount)
                                            {{ $item->sisa_plafond != null ? format_idr($item->sisa_plafond+$item->actual_amount) : '' }}
                                            @elseif(isset($item->nominal_approved))
                                            @php($sisa_plafond = $item->sisa_plafond+$item->nominal_approved)
                                            {{ $item->sisa_plafond != null ? format_idr($item->sisa_plafond+$item->nominal_approved) : '' }}
                                            @endif
                                        </td>
                                        @else
                                        @php($sisa_plafond = $item->sisa_plafond)
                                        <td>{{ $item->sisa_plafond != null ? format_idr($item->sisa_plafond) : '' }}</td>
                                        @endif
                                        <input type="hidden" class="input_sisa_plafond" name="sisa_plafond[]" max="{{$item->sisa_plafond}}"/>
                                        <td>{{ $item->description }}</td>
                                        {{--<td>{{ $item->quantity }}</td>--}}
                                        <td>{{ format_idr($item->amount) }}</td>
                                        <td>{{ format_idr($item->nominal_approved) }}</td>
                                        <td>{{ $item->note }}</td>
                                        <td>
                                            <input type="text" name="actual_amount[]" class="form-control price_format actual_amount" max="{{$sisa_plafond}}" onchange="cek_actual_amount(this)" value="{{ $item->actual_amount }}" {{$item->actual_amount == NULL || $data->status_claim== 3 || $data->status_claim== 4 ? 'required' : 'readonly=true'  }}/>
                                        </td>
                                        @if($data->status_claim < 1 || $data->status_claim == "" || $data->status_claim == 3 || $data->status_claim == 4)
                                        <td>
                                            <input type="file" name="file_struk[]" class="form-control input" id="cash_advance_{{$item->id}}"  accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" {{$item->file_struk ? '' : 'required'}}>
                                            <div id="default_cash_advance_{{$item->id}}">
                                            @if(!empty($item->file_struk) && $data->status_claim == 4 || $data->status_claim == 3) 
                                                <a onclick="show_image('{{ $item->file_struk }}')" class="btn btn-default btn-xs" id="view_cash_advance_{{$item->id}}"><i class="fa fa-search-plus"></i>View </a>
                                            @endif
                                            </div><div id="preview_cash_advance_{{$item->id}}" class="preview_cash_advance_{{$item->id}}" style="display: none"></div>
                                        </td>
                                        @else
                                        <td>
                                            @if(!empty($item->file_struk)) 
                                                <a onclick="show_image('{{ $item->file_struk }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                            @endif
                                        </td>
                                        @endif
                                        <td>
                                            <input type="text" name="amount_claimed[]" class="form-control price_format nominal_claim" value="{{ $item->nominal_claimed }}" readonly="true"/>
                                        </td>
                                        <td>{{ $item->note_claimed }}</td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background: #eee;">
                                        <th colspan="4" style="text-align: right;">Total</th>
                                        <th>{{ format_idr($total_amount) }}</th>
                                        <th>{{ format_idr($total_amount_approved) }}</th>
                                        <th></th>
                                        <th class="actual_amount_sum" style="font-size: 14px;" colspan="2">{{ $total_actual_amount != 0 ?  format_idr($total_actual_amount) : '' }}</th>
                                        <th colspan="2">{{ format_idr($total_amount_claimed) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if($total_amount_claimed > 0)
                        <div class="col-md-6 table-total" style="padding-left:0;">
                            <table class="table table-hover">
                                <tr>
                                    <th>Total Actual Amount (IDR)</th>
                                    <th style="text-align: left; width: 30%;">
                                        <input type="hidden" value="{{$total_actual_amount}}" id="total_actual_bill">
                                        {{ format_idr($total_actual_amount) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>Cash Advance Value Approved (IDR)</th>
                                    <input type="hidden" value="{{$total_amount_approved}}" id="total_amount_approved_id">
                                    <th style="text-align: left; width: 30%;">{{ format_idr($total_amount_approved) }}</th>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 table-total" style="padding-right:0;">
                            <table class="table table-hover">
                                <tr>
                                    <th>Total Claimed (IDR)</th>
                                    <th style="text-align: left; width: 30%;">
                                        <input type="hidden" value="{{$total_amount_claimed}}" id="total_amount_claimed_id">
                                        {{ format_idr($total_amount_claimed) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th id="title_disetujui"></th>
                                    <th style="text-align: left; width: 30%;" id="total_reimbursement_disetujui">
                                        
                                    </th>
                                </tr>
                            </table>
                        </div>
                        @endif
                        <div class="clearfix"></div>
                        <br />
                            @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-6">Note Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Note Claim Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note_claim" value="{{ $item->note_claim }}">
                                </div>
                            </div>
                            @endforeach

                            <div class="clearfix"></div>
                            <br />
                            <div class="form-group">
                                @if($data->payment_method == 'Bank Transfer' && $data->status_claim==2 && ($total_amount_claimed != $total_amount_approved))
                                    <div class="col-md-3">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" {{$data->is_transfer_claim==1 ? 'checked disabled' : ''}} {{$total_amount_claimed < $total_amount_approved ? 'required' : ''}} id="is_transfer_claim" name="is_transfer_claim" value="1" >
                                                <label class="form-check-label">
                                                    Has been Proccessed
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @if($data->disbursement_claim != NULL)
                                    <div class="col-md-3" id="disbursement_claim_div">
                                        <div class="form-group">
                                            <label class="col-md-12">Disbursement</label>
                                            <div class="col-md-12">
                                                <label style="font-weight: normal;"><input type="radio" id="disbursement_claim_id_next" name="disbursement_claim" {{ $data->disbursement_claim == 'Next Payroll' ? 'checked="true"' : '' }} {{ $data->disbursement_claim != null ? 'disabled' : '' }} value="Next Payroll"/> Next Payroll</label>&nbsp;&nbsp;
                                                <label style="font-weight: normal;"><input type="radio" id="disbursement_claim_id" name="disbursement_claim" {{ $data->disbursement_claim == 'Transfer' ? 'checked="true"' : '' }} {{ $data->disbursement_claim != null ? 'disabled' : '' }} value="Transfer"/> Transfer</label> 
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($data->transfer_proof_claim == NULL && $data->disbursement_claim == 'Transfer')
                                    <div class="col-md-6" id="transfer_proof_claim_div">
                                        <div class="form-group">
                                            <label class="col-md-12">Transfer Proof</label>
                                            <div class="col-md-8">
                                                <input type="file" id="transfer_proof_claim_by_admin" name="transfer_proof_claim_by_admin" {{$data->is_transfer_claim==1 ? 'disabled' : ''}} {{$total_amount_claimed > $total_amount_approved ? '' : ''}} class="form-control " accept="image/*, application/pdf"/>
                                            </div>
                                            <div class="col-md-4">
                                                <a onclick="preview()" class="btn btn-default preview" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($data->transfer_proof_claim != NULL)
                                    <a onclick="show_proof('{{ $data->transfer_proof_claim }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                    @endif
                                @endif
                            </div>

                        <a href="{{ route('karyawan.cash-advance.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        
                        @if($data->status_claim < 1 || $data->status_claim == "" || $data->status_claim == 3 || $data->status_claim == '4')
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Send Cash Advance Claim</a>
                        <a class="btn btn-sm btn-primary waves-effect waves-light m-r-10" id="btn_draft"><i class="fa fa-save"></i> Save Draft</a>
                        @endif

                        {{-- @if($data->payment_method == 'Bank Transfer' && $data->status_claim == 2 && $data->is_transfer_claim == 0 && ($total_amount_claimed < $total_amount_approved))
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_save"><i class="fa fa-check"></i> Save</a>
                        @endif --}}
                        <br style="clear: both;" />
                        <div class="clearfix"></div>
                    </div>
                </div>  
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body form-horizontal">
                    <div id="modalcontent">

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

    <div id="modal_file_proof" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body form-horizontal">
                    <output id="result_modal_file"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modal_bensin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Data Gasoline</h4> </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form_modal_bensin">
                        <div class="form-group">
                            <label class="col-md-12">Date of purchase of gasoline</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal_tanggal_struk_bensin" disabled/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Odometer (KM)</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_from" placeholder="From Odo Meter" disabled/>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_to" placeholder="To Odo Meter" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Liter</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control modal_liter" disabled/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Cost (IDR)</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control modal_cost" disabled/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" id="btn_cancel_bensin" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-sm" id="add_modal_bensin">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<script type="text/javascript">

    $(".actual_amount").on('input', function(){
        calculate_amountApprove();
    });

    var calculate_amountApprove  = function(){
        var total = 0;
        $('.actual_amount').each(function(){
            if($(this).val() != ""){
                var value = $(this).val();
                total += parseInt(value.split('.').join(''));
            }
        });

        $('.actual_amount_sum').html(numberWithComma(total).replace(/,/g, "."));
    }

    function info_bensin(el){
        $('.modal_tanggal_struk_bensin').val($(el).data('tanggal'));
        $('.modal_odo_from').val($(el).data('odo_start'));
        $('.modal_odo_to').val($(el).data('odo_end'));
        $('.modal_liter').val($(el).data('liter'));
        $('.modal_cost').val($(el).data('cost'));
        $('#modal_bensin').modal('show');
    }

    $("#btn_draft").click(function(){
        $('.oninput').find('td').removeAttr('required');
        const input = $('.oninput').find('td').removeAttr('required')
        input.required = false
        bootbox.confirm('Save as Draft?', function(res){
            if(res)
            {
                $("input[name='status_claim']").val(4);
                $("#form-claim-cash-advance").submit();
            }
        });
    });

    $("#btn_submit").click(function(){
        $('.oninput').find('td').removeClass("has-error");
        var jumlah = $('.table-hover tr').length;

        if(jumlah <= 0)
        {
            bootbox.alert('Form not completed. Please check and resubmit.');
            validate = false;
            return;
        }
        var validate = form_validate();
        console.log(validate)
        if(!validate){
            bootbox.alert('Form not completed. Please check and resubmit.');
            return false;
        }
        else{
            bootbox.confirm('Do you want to submit Cash Advance Settlement?', function(result){
                if(result)
                {
                    $('#form-claim-cash-advance').submit();
                }
            });
        }
    });

    function form_validate() {
        var validate = true;
        $('.oninput input').each(function(){

            if($(this).val() == "" && ($(this).prop('required')))
            {
                $(this).parent().addClass('has-error');
                validate = false;
            }
        });
        return validate;
    }
</script>
<script type="text/javascript">
    function show_image(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/cash-advance/file-struk/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/cash-advance/file-struk/')}}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        }
        else{
            alert("Filetype is not supported!");
        }
        {{--bootbox.alert('<img src="{{ asset('storage/file-struk/') }}/'+ img +'" style = \'width: 100%;\' />');--}}
    }

    var actual = $('#total_actual_bill').val();
    var approved = $('#total_amount_approved_id').val();
    var total = $('#total_amount_claimed_id').val();
    
    if(total >= 0){
        if(parseInt(approved) > parseInt(total)){
            $('#title_disetujui').html("Total Payment by Employee (IDR)");
            var disetujui = parseInt(approved) - parseInt(total);
            $('#total_reimbursement_disetujui').html(numberWithComma(disetujui).replace(/,/g, "."));
        }
        else{
            $('#title_disetujui').html("Total Payment by Company (IDR)");
            var disetujui = parseInt(approved) - parseInt(total);
            if(disetujui < 0) {
                disetujui = disetujui * -1;
            }
            $('#total_reimbursement_disetujui').html(numberWithComma(disetujui).replace(/,/g, "."));
        }
    }

    function show_proof(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/cash-advance/transfer-proof/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/cash-advance/transfer-proof/')}}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        }
        else{
            alert("Filetype is not supported!");
        }

    }
    $("#btn_save").click(function(){
        if(!$("#is_transfer_claim").is(':checked')){
            window.alert("Please checked the field Already Transfered");
            // $("#is_transfer").addClass('noteError');
        }
        else{
            // $("#is_transfer").removeClass('noteError');
            bootbox.confirm('Do you want to send this transfer proof claim?', function(result){
                // $("input[name='status']").val(1);
                if(result)
                {
                    $('#form-claim-cash-advance-transfer').submit();
                }
            });
        }
    });

    function preview()
    {
        $('#modal_file_proof').modal('show');
    }

    $("#transfer_proof_claim_by_admin").on("change", function() {
        var files = $('#transfer_proof_claim_by_admin')[0].files;
        if(files[0].size > 1000000 && files[0].type.match('image')){
            $("#transfer_proof_claim_by_admin").val('')
            $(".preview").hide();
            imageToBig();
        }
        else if(files[0].size > 5000000 && files[0].type.match('pdf')){
            $("#transfer_proof_claim_by_admin").val('')
            $(".preview").hide();
            pdfToBig();
        }
        showFile()
    });

    function imageToBig(){
        window.alert("Maximal of image size is 1 Mb");
    }

    function pdfToBig(){
        window.alert("Maximal of PDF size is 5 Mb");
    }

    function showFile(){
        if (window.File && window.FileList && window.FileReader) {
            var files = event.target.files; //FileList object
            var output = document.getElementById("result_modal_file");
            $("#result_modal_file").html("");
            if (files.length) {
                $(".preview").show();
            } else {
                $(".preview").hide();
            }
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    //Only pics
                    if (!file.type.match('image') && !file.type === 'application/pdf')
                        continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                        var picFile = event.target;
                        var div = document.createElement("div");
                        if(!file.type.match('image')){
                            $("#result_modal_file").html("<embed src='" + picFile.result + "' frameborder='0' width='100%' height='400px'>");
                        } else {
                            div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                        }
                        output.insertBefore(div, null);
                    });
                    //Read the image
                    picReader.readAsDataURL(file);
                }
        } else {
            console.log("Your browser does not support File API");
        }
    }

    function initImage() {
        if (window.File && window.FileList && window.FileReader) {
            var filesInput = document.getElementsByClassName("input");
            for (var i = 0; i < filesInput.length; i++) {
                filesInput[i].replaceWith(filesInput[i].cloneNode(true));
                filesInput[i].addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var id = event.target.id;
                    var output = document.getElementById("preview_"+id);
                    $("#preview_" + id).html("");
                    if (files.length) {
                        $("#default_" + id).hide();
                        $("#preview_" + id).show();
                    } else {
                        $("#preview_" + id).hide();
                        $("#default_" + id).show();
                    }
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics
                        if (!file.type.match('image') && !file.type === 'application/pdf')
                            continue;
                        else if(file.size > 1000000){
                            $('#'+id).val('')
                            $("#preview_" + id).hide();
                            window.alert("Maximal of file size is 1 Mb");
                        }
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            div.innerHTML = '<label onclick="show_img(\'' + picFile.result + '\')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>';
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });
            }
        } else {
            console.log("Your browser does not support File API");
        }
    }

    function show_img(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if (ext === 'pdf' || img.match('pdf')) {
            bootbox.alert({
                message : '<embed src="'+ img +'" frameborder="0" width="100%" height="600px">',
                size: 'large' 
            });
        } else if(images.includes(ext) || img.match('image')) {
            bootbox.alert({
                message : '<img src="'+ img +'" style="width: 100%;" />',
                size: 'large' 
            });
        } else {
            alert("Filetype is not supported!");
        }
    }

    initImage();

    function cek_amount_plafond(sisa_plafond, type, index){
        var total = 0;
        // console.log(type)
        $('.type_form').each(function(index2){
            if($(this).text() == type){
                amount = $(this).closest("tr").find('.actual_amount').val().split('.').join('')
                sisa = parseInt(sisa_plafond) - parseInt(amount)
                if(amount && $(this).closest("tr").index() >= index){
                    total = parseInt(total) + parseInt(amount)
                }
                // console.log(sisa_plafond, amount, total, sisa_plafond-total, sisa_plafond-amount);
                if($(this).closest("tr").index() > index && sisa_plafond > 0){
                    $(this).closest("tr").find('.sisa_plafond_value').text(numberWithDot(sisa_plafond-(total-amount)))
                    $(this).closest("tr").find('.actual_amount').attr("max",sisa_plafond-(total-amount))
                    $(this).closest("tr").find('.input_sisa_plafond').val((sisa_plafond-(total-amount)))
                    if(sisa_plafond-(total-amount) < amount){
                        $(this).closest("tr").find('.actual_amount').val(numberWithDot(sisa_plafond-(total-amount)))
                        $(this).closest("tr").find('.input_sisa_plafond').val((sisa_plafond-(total-amount)))
                        if(sisa_plafond-(total-amount) <= 0){
                            $(this).closest("tr").find('.sisa_plafond_value').text(numberWithDot(0))
                            $(this).closest("tr").find('.actual_amount').attr("max",0)
                            $(this).closest("tr").find('.actual_amount').val(numberWithDot(0))
                            $(this).closest("tr").find('.input_sisa_plafond').val(0)
                        }
                    }
                } 
            }
        });
    }

    function cek_actual_amount(el){
        value = $(el).val().split('.').join('')
        index = $(el).closest("tr").index();
        if('{{get_setting("period_ca_pr")}}'=='yes'){
            if(parseInt($(el).attr('max')) <= parseInt(value)){
                $(el).val(numberWithDot($(el).attr('max')))
                // $(el).parent().parent().find('.sisa_plafond_value').val(0);
            }
            else if(parseInt($(el).attr('max')) > parseInt(value)){
                sisa = parseInt($(el).attr('max')) - parseInt(value)
                // $(el).parent().parent().find('.sisa_plafond_value').val(numberWithDot(sisa));
            }
            cek_amount_plafond(parseInt($(el).parent().parent().find('.sisa_plafond_value').text().split('.').join('')), $(el).parent().parent().find('.type_form').text(),$(el).closest("tr").index())
        }
        calculate_amountApprove();
    }
</script>

@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
