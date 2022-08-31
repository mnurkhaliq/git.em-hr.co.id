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
                <h4 class="page-title">Process Transfer Cash Advance</h4> </div>
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
            <form class="form-horizontal" id="form-payment-request" enctype="multipart/form-data" action="{{ route('karyawan.approval.cash-advance.prosesTransfer', $data->id) }}" method="POST">
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
                                        <label style="font-weight: normal;"><input class="form-check-input" type="checkbox" {{$data->is_transfer==1 ? 'checked disabled' : ''}} id="is_transfer" name="is_transfer" value="1" >  Has been Proccessed</label> &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" id="disbursement_div" {{ $data->disbursement == NULL ? 'style=display:none;' : '' }}>
                                <div class="form-group">
                                    <label class="col-md-12">Disbursement</label>
                                    <div class="col-md-12">
                                        <label style="font-weight: normal;"><input type="radio" id="disbursement_id" name="disbursement" {{ $data->disbursement == 'Transfer' ? 'checked="true"' : '' }} {{ $data->disbursement != null ? 'disabled' : '' }} value="Transfer" /> Transfer</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" id="disbursement_id_next" name="disbursement" {{ $data->disbursement == 'Next Payroll' ? 'checked="true"' : '' }} {{ $data->disbursement != null ? 'disabled' : '' }} value="Next Payroll" /> Next Payroll</label>
                                    </div>
                                </div>
                            </div>
                            @if($data->transfer_proof == NULL)
                            <div class="col-md-6" id="transfer_proof_div" {{ $data->transfer_proof == NULL ? 'style=display:none;' : '' }}>
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
                            @else
                            <a onclick="show_image('{{ $data->transfer_proof }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
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
                                        {{-- <th>RECEIPT TRANSACTION</th> --}}
                                        <th>NOTE</th>
                                    </tr>
                                </thead>
                                <tbody class="table-content-lembur">
                                    @php($total_cost=0)
                                    @php($total_amount=0)
                                    @php($total_amount_approved=0)
                                    @foreach($data->cash_advance_form as $key => $item)
                                    @php($total_amount_approved +=$item->nominal_approved)
                                    @php($total_amount +=$item->amount)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{ $item->type_form }} @if($item->bensin) <a class="btn btn-info btn-xs" data-tanggal="{{$item->bensin->tanggal}}" data-odo_start="{{$item->bensin->odo_start}}" data-odo_end="{{$item->bensin->odo_end}}" data-liter="{{$item->bensin->liter}}" data-cost="{{$item->bensin->cost}}" onclick="info_bensin(this)"><i class="fa fa-info"></i></a>  @endif</td>
                                        @if(get_setting('period_ca_pr')== 'yes' && $item->plafond != $item->sisa_plafond)
                                        <td>
                                        @if(isset($item->nominal_approved))
                                            {{ isset($item->sisa_plafond) ? format_idr($item->sisa_plafond+$item->nominal_approved) : '' }}
                                            @else
                                            {{ isset($item->sisa_plafond) ? format_idr($item->sisa_plafond+$item->amount) : '' }}
                                            @endif
                                        </td>
                                        @else
                                        <td>{{ isset($item->sisa_plafond) ? format_idr($item->sisa_plafond) : '' }}</td>
                                        @endif
                                        <td>{{ $item->description }}</td>
                                        {{--<td>{{ $item->quantity }}</td>--}}
                                        <td>{{ format_idr($item->amount) }}</td>
                                        <td>
                                            @if($item->nominal_approved != NULL)
                                                <input type="text" name="nominal_approve[{{ $item->id }}]" readonly="true" class="form-control price_format nominal_approve" value="{{ $item->nominal_approved }}" placeholder="Nominal Approve"/>
                                            @endif
                                            @if($item->nominal_approved == NULL)
                                                <input type="text" name="nominal_approve[{{ $item->id }}]" readonly="true" class="form-control price_format nominal_approve" value="{{ $item->amount }}" placeholder="Nominal Approve"/>
                                            @endif
                                        </td>
                                        {{-- <td>
                                            @if(!empty($item->file_struk)) 
                                                <a onclick="show_image('{{ $item->file_struk }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                            @endif
                                        </td> --}}
                                        <td>
                                            <textarea name="note[{{ $item->id }}]"  readonly="true" placeholder="Note" class="form-control">{{ $item->note }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background: #eee;">
                                        <th colspan="4" style="text-align: right;">Total</th>
                                        <th>{{ format_idr($total_amount) }}</th>
                                        <th class="total_amountapprove" colspan="3">{{ format_idr($total_amount_approved) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-12">Note Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                </div>
                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                        <br />
                        <input type="hidden" name="status" value="0" />
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <a href="{{ route('karyawan.approval.cash-advance.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        @if($data->payment_method == 'Bank Transfer' && $data->is_transfer == 0 && $data->status==2)
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_save"><i class="fa fa-check"></i> Save</a>
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
    $(".nominal_approve").on('input', function(){
      calculate_amountApprove();
    });

    var calculate_amountApprove  = function(){
    var total = 0;
    $('.nominal_approve').each(function(){
        if($(this).val() != ""){
            var value = $(this).val();
            total += parseInt(value.split('.').join(''));
        }
    });

    $('.total_amountapprove').html(numberWithComma(total).replace(/,/g, "."));
    }


    $("#btn_approve_all").click(function(){

        $('.item_payment').each(function(){
           var nominal_old = $(this).find('.nominal_old').val();

           $(this).html('<p>Nominal disetujui : '+ numberWithComma(nominal_old).replace(/,/g, ".") +'</p>');
           $(this).parent().find('.nominal_approve').val(nominal_old);
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
        $(global_el).parent().parent().find('.nominal_approve').val(nominal);
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

    $('#is_transfer').change(function(){
        if($("#is_transfer").is(':checked')){
            $("#disbursement_div").show();
        }
        else if(!$("#is_transfer").is(':checked')){
            $("#disbursement_div").hide();
        }
    })

    $('#disbursement_id').change(function(){
        console.log($("#disbursement_id").val())
        if($("#disbursement_id").val()=='Transfer'){ 
            $("#transfer_proof_div").show();
        }
    })

    $('#disbursement_id_next').change(function(){
        if($("#disbursement_id_next").val()=='Next Payroll'){ 
            $("#transfer_proof_div").hide();
        }
    })

    $("#btn_save").click(function(){
        if(!$("#is_transfer").is(':checked')){
            window.alert("Please checked the field Has been proccessed");
        }
        else if($("#is_transfer").is(':checked') && !$("[name='disbursement']").is(':checked')){
            window.alert("Please checked the field transfer or next payroll");
        }
        else{
            bootbox.confirm('Do you want to send this transfer proof?', function(result){
                // $("input[name='status']").val(1);
                if(result)
                {
                    $('#form-payment-request').submit();
                }
            });
        }
    });

    function preview()
    {
        $('#modal_file_proof').modal('show');
    }

    $("#transfer_proof_by_admin").on("change", function() {
        var files = $('#transfer_proof_by_admin')[0].files;
        if(files[0].size > 1000000 && files[0].type.match('image')){
            console.log(files[0].size)
            $("#transfer_proof_by_admin").val('')
            $(".preview").hide();
            imageToBig();
        }
        else if(files[0].size > 5000000 && files[0].type.match('pdf')){
            console.log(files[0].size)
            $("#transfer_proof_by_admin").val('')
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
</script>
<script type="text/javascript">
    function show_image(img)
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
</script>

@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
