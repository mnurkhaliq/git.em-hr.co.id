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
                <h4 class="page-title">Process Cash Advance</h4> </div>
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
            <form class="form-horizontal" id="form-cash-advance" enctype="multipart/form-data" action="{{ route('karyawan.approval.cash-advance.prosesClaim', $data->id) }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        {{ csrf_field() }}
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
                                    <input type="text" class="form-control" readonly="true" value="{{ $data->user->nik }} / {{ $data->user->name }}" />
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
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" value="Cash" {{ $data->payment_method == 'Cash' ? 'checked' : '' }} disabled/> Cash</label> &nbsp;&nbsp;
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" value="Bank Transfer" {{ $data->payment_method == 'Bank Transfer' ? 'checked' : '' }}  disabled/> Bank Transfer</label>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Name of Account</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ $data->user->nama_rekening }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Account Number</label>
                                <div class="col-md-12">
                                    <input type="number" class="form-control" readonly="true" value="{{ $data->user->nomor_rekening }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name Of Bank</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{isset($data->user->bank->name)?$data->user->bank->name:""}}" />
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
                                    @php($total_actual_amount=0)
                                    @php($total_amount_claimed=0)
                                    @foreach($data->cash_advance_form as $key => $item)
                                    @php($total_amount_approved +=$item->nominal_approved)
                                    @php($total_amount_claimed +=$item->nominal_claimed)
                                    @php($total_amount +=$item->amount)
                                    @php($total_actual_amount +=$item->actual_amount)
                                    <tr>
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
                                        <input type="hidden" class="input_sisa_plafond" name="sisa_plafond[{{ $item->id }}]" max="{{$item->sisa_plafond}}"/>
                                        <td>{{ $item->description }}</td>
                                        {{--<td>{{ $item->quantity }}</td>--}}
                                        <td>{{ format_idr($item->amount) }}</td>
                                        <td>{{ format_idr($item->nominal_approved) }}</td>
                                        <td>
                                            <textarea name="note[{{ $item->id }}]" {{$history->is_approved ? 'readonly="true"' : '' }} placeholder="Note" class="form-control">{{ $item->note }}</textarea>
                                        </td>
                                        <td>{{ format_idr($item->actual_amount) }}</td>
                                        <td>
                                            @if(!empty($item->file_struk)) 
                                                <a onclick="show_image('{{ $item->file_struk }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($item->nominal_claimed))
                                                <input type="text" name="nominal_claimed[{{ $item->id }}]" {{$history->is_approved_claim ? 'readonly="true"' : '' }} onchange="cek_amount_claim(this)" max="{{$sisa_plafond}}" class="form-control price_format nominal_claim" value="{{ $item->nominal_claimed }}" placeholder="Nominal Claimed"/>
                                            @else
                                                <input type="text" name="nominal_claimed[{{ $item->id }}]" {{$history->is_approved_claim ? 'readonly="true"' : '' }} onchange="cek_amount_claim(this)" max="{{$sisa_plafond}}" class="form-control price_format nominal_claim" value="{{ $item->actual_amount }}" placeholder="Nominal Claimed"/>
                                            @endif
                                        </td>
                                        <td>
                                            <textarea name="note_claimed[{{ $item->id }}]" {{$history->is_approved_claim ? 'readonly="true"' : '' }} placeholder="Note Claimed" class="form-control">{{ $item->note_claimed }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background: #eee;">
                                        <th colspan="4" style="text-align: right;">Total</th>
                                        <th>{{ format_idr($total_amount) }}</th>
                                        <th>{{ format_idr($total_amount_approved) }}</th>
                                        <th></th>
                                        <th colspan="2">{{ format_idr($total_actual_amount) }}</th>
                                        <th class="total_amountclaim" colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
                                    <th style="text-align: left; width: 30%;" class="total_amountclaim">
                                        
                                    </th>
                                </tr>
                                <tr>
                                    <th id="title_disetujui"></th>
                                    <th style="text-align: left; width: 30%;" id="total_reimbursement_disetujui">

                                    </th>
                                </tr>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="text" readonly="true" class="form-control note" value="{{ $history->note }}">
                            </div>
                            @if($history->note_claim != NULL && $history->is_approved_claim == 1)
                            <div class="col-md-6">
                                <input type="text" readonly="true" class="form-control note_claim" value="{{ $history->note_claim }}">
                            </div>
                            @else
                            <div class="col-md-6">
                                 <textarea class="form-control note_claim" id="noteApproval" name="note_claim" placeholder="Note Claim Approval ">{{$history->note_claim != null ? $history->note_claim : ''}}</textarea>
                            </div>
                             @endif
                        </div>
                        <div class="form-group">
                            @if($data->payment_method == 'Bank Transfer' && $data->status_claim==2 && ($total_amount_claimed != $total_amount_approved))
                                <div class="col-md-3">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" {{$data->is_transfer_claim==1 ? 'checked disabled' : ''}} {{$total_amount_claimed < $total_amount_approved ? 'required disabled' : 'disabled'}} id="is_transfer_claim" name="is_transfer_claim" value="1" >
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
                                            <input type="file" id="transfer_proof_claim_by_admin" name="transfer_proof_claim_by_admin" {{$data->is_transfer_claim==1 ? 'disabled' : 'disabled'}} {{$total_amount_claimed > $total_amount_approved ? 'disabled' : 'disabled'}} class="form-control " accept="image/*, application/pdf"/>
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
                        <div class="clearfix"></div>
                        <br />
                        <input type="hidden" name="status" value="0" />
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <a href="{{ route('karyawan.approval.cash-advance.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        @if($history->is_approved_claim === NULL and $data->status_claim < 2)
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_approved"><i class="fa fa-check"></i> Approve</a>
                        <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_tolak"><i class="fa fa-close"></i> Reject</a>
                        @endif

                        <br style="clear: both;" />
                        <div class="clearfix"></div>
                    </div>
                </div>    
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>

<div id="modal_other" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Approve by Condition</h4> </div>
                <div class="modal-body form-horizontal">
                    <div class="form-group">
                        <label class="col-md-12">Amount Approved</label>
                        <div class="col-md-12">
                            <input type="number" class="form-control modal_nominal" />
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <label class="col-md-12">Note</label>
                        <div class="col-md-12">
                            <textarea class="form-control modal_catatan"></textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect btn-sm" id="btn_modal_oke">Oke</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

@section('footer-script')
<script type="text/javascript">

    function info_bensin(el){
        $('.modal_tanggal_struk_bensin').val($(el).data('tanggal'));
        $('.modal_odo_from').val($(el).data('odo_start'));
        $('.modal_odo_to').val($(el).data('odo_end'));
        $('.modal_liter').val($(el).data('liter'));
        $('.modal_cost').val($(el).data('cost'));
        $('#modal_bensin').modal('show');
    }
    $(document).ready(function () {
        calculate_amountApprove();
    });
    
        
    var global_el;
    $(".nominal_claim").on('input', function(){
        if($(this).val() < 0) {
            var val = $(this).val() * -1
            $(this).val(val)
        }
        calculate_amountApprove();
    });

    var calculate_amountApprove  = function(){
        var total = 0;
        $('.nominal_claim').each(function(){
            if($(this).val() != ""){
                var value = $(this).val();
                total += parseInt(value.split('.').join(''));
            }
        });

        $('.total_amountclaim').html(numberWithComma(total).replace(/,/g, "."));

        var actual = $('#total_actual_bill').val();
        var approved = $('#total_amount_approved_id').val();
        
        if(approved > total){
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
        // if(approved < actual){
        //     if(total <= actual){
        //         $('#title_disetujui').html("Total Payment by Company");
        //         var disetujui = parseInt(approved) - parseInt(total);
        //         $('#total_reimbursement_disetujui').html(numberWithComma(disetujui).replace(/,/g, "."));
        //     }
        // }
    }


    $("#btn_approve_all").click(function(){

        $('.item_payment').each(function(){
           var nominal_old = $(this).find('.nominal_old').val();

           $(this).html('<p>Nominal disetujui : '+ numberWithComma(nominal_old).replace(/,/g, ".") +'</p>');
           $(this).parent().find('.nominal_claim').val(nominal_old);
        });

        $("input[name='approve_all']").val(1);

        $(this).remove();
    });

    $("#btn_modal_oke").click(function(){

        var nominal = $('.modal_nominal').val();
        var catatan = $('.modal_catatan').val();

        if(nominal == "") { bootbox.alert('Nominal harus diisi !'); return false;}
        if(catatan == "") { bootbox.alert('Catatan harus diisi !'); return false;}

        var html = '<p>Nominal : '+ numberWithComman(nominal).replace(/,/g, ".") +'</p>';
            html += '<p>Catatan : '+ catatan +'</p>';

        $(global_el).parent().html(html);
        $(global_el).parent().parent().find('.nominal_claim').val(nominal);
        $(global_el).parent().parent().find('.note').val(catatan);
    });

    function other_(el)
    {
        global_el = el;
        $("#modal_other").modal('show');
    }
    
    function oke_()
    {

    }
    
    $("#btn_approved").click(function(){
        if(!$("#noteApproval").val()){
            alertNote()
            $("#noteApproval").addClass('noteError');
        }
        else{
            $("#noteApproval").removeClass('noteError');
            bootbox.confirm('Approve Employee Cash Advance ?', function(result){
                $("input[name='status']").val(1);
                if(result)
                {
                    $('#form-cash-advance').submit();
                }
            });
        }
    });

    $("#btn_tolak").click(function(){
        if(!$("#noteApproval").val()){
            alertNote()
            $("#noteApproval").addClass('noteError');
        }
        else{
            $("#noteApproval").removeClass('noteError');
            bootbox.confirm('Reject Employee Cash Advance ?', function(result){
                if(result)
                {
                    $('#form-cash-advance').submit();
                }
            });
        }
    });
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
        {{--bootbox.alert('<img src="{{ asset('storage/file-struk/')}}/'+ img +'" style = \'width: 100%;\' />');      --}}

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

    function cek_amount_plafond(sisa_plafond, type, index){
        var total = 0;
        // console.log(type)
        $('.type_form').each(function(index2){
            if($(this).text() == type){
                amount = $(this).closest("tr").find('.nominal_claim').val().split('.').join('')
                sisa = parseInt(sisa_plafond) - parseInt(amount)
                if(amount && $(this).closest("tr").index() >= index){
                    total = parseInt(total) + parseInt(amount)
                }
                // console.log(sisa_plafond, amount, total, sisa_plafond-total, sisa_plafond-amount);
                if($(this).closest("tr").index() > index && sisa_plafond > 0){
                    $(this).closest("tr").find('.sisa_plafond_value').text(numberWithDot(sisa_plafond-(total-amount)))
                    $(this).closest("tr").find('.nominal_claim').attr("max",sisa_plafond-(total-amount))
                    $(this).closest("tr").find('.input_sisa_plafond').val((sisa_plafond-(total-amount)))
                    if(sisa_plafond-(total-amount) < amount){
                        $(this).closest("tr").find('.nominal_claim').val(numberWithDot(sisa_plafond-(total-amount)))
                        $(this).closest("tr").find('.input_sisa_plafond').val((sisa_plafond-(total-amount)))
                        if(sisa_plafond-(total-amount) <= 0){
                            $(this).closest("tr").find('.sisa_plafond_value').text(numberWithDot(0))
                            $(this).closest("tr").find('.nominal_claim').attr("max",0)
                            $(this).closest("tr").find('.nominal_claim').val(numberWithDot(0))
                            $(this).closest("tr").find('.input_sisa_plafond').val(0)
                        }
                    }
                } 
            }
        });
    }

    function cek_amount_claim(el){
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
