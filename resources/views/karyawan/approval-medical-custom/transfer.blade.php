@extends('layouts.karyawan')

@section('title', 'Medical Reimbursement')

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
                <h4 class="page-title">Data Medical Reimbursement</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Medical Reimbursement</li>
                </ol>
            </div> 
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form_transfer" enctype="multipart/form-data" action="{{ route('karyawan.approval.medical-custom.prosesTransfer', $data->id) }}" method="POST">
            <div class="col-md-12">
                    <div class="white-box">
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
                        
                        <div class="col-md-6" style="padding-left: 0;">
                            <div class="form-group">
                                <label class="col-md-12">Medical Reimbursement Number</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ $data->number }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">NIK / Employee Name</label>
                                <label class="col-md-6">Position</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" readonly="true" value="{{ $data->user->nik .' - '. $data->user->name }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control" value="{{ isset($data->user->structure->position) ? $data->user->structure->position->name:''}}{{ isset($data->user->structure->division) ? ' - '. $data->user->structure->division->name:'' }}{{ isset($data->user->structure->title) ? ' - '. $data->user->structure->title->name:'' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Claim Date</label>
                                <label class="col-md-6">Name Of Bank</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control" value="{{ $data->tanggal_pengajuan }}" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" readonly="true" value="{{ isset($data->user->bank->name) ? $data->user->bank->name : '' }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Name of Account</label>
                                <label class="col-md-6">Account Number</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" readonly="true" value="{{  isset($data->user->nama_rekening) ? $data->user->nama_rekening : ''}}" />
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" readonly="true" value="{{ isset($data->user->nomor_rekening) ? $data->user->nomor_rekening : '' }}" />
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Outpatient</td>
                                        <td>Original slip, Diagnosa, Copy of Prescription, Copy of MRI (if available)</td>
                                    </tr>
                                    <tr>
                                        <td>Inpatient</td>
                                        <td>Original slip, Diagnosa, Copy of Prescription, Copy of MRI (if available)</td>
                                    </tr>
                                    <tr>
                                        <td>Maternity</td>
                                        <td>Original slip, Certificate of Birth</td>
                                    </tr>
                                    <tr>
                                        <td>Eyeglasses</td>
                                        <td>Original slip, Ophthalmologists check up (for the first time)</td>
                                    </tr>
                                </tbody>
                            </table>
                            @if($data->status==2)
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
                            <a onclick="show_proof('{{ $data->transfer_proof }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                            @endif
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div>
                          <table class="table table-hover">
                              <thead>
                                  <tr>
                                      <th>NO</th>
                                      <th>RECEIPT DATE</th>
                                      <th>RELATIONSHIP</th>
                                      <th>PATIENT NAME</th>
                                      <th>CLAIM TYPE</th>
                                      <th>RECEIPT NO/ KWITANSI NO</th>
                                      <th>AMOUNT (IDR)</th>
                                      <th>FILE</th>
                                      <th>AMOUNT APPROVED (IDR)</th>
                                      <th>NOTE APPROVED</th>
                                  </tr>
                              </thead>
                              <tbody class="table-claim">
                                @php ($total = 0)
                                @php ($total_disetujui = 0)
                                @foreach($data->form as $key => $f)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td><input type="text" class="form-control datepicker"  readonly="true" name="tanggal_kwitansi[]" value="{{ $f->tanggal_kwitansi }}"  /></td>
                                     <td>
                                        @if(0 == $f->user_family_id)
                                            <input type="text" readonly="true" class="form-control" value="Employee">
                                        @else
                                            <input type="text" readonly="true" class="form-control" value="{{ isset($f->UserFamily->hubungan) ? $f->UserFamily->hubungan : ''  }}">
                                        @endif
                                    </td>
                                    <td>
                                        @if(0 == $f->user_family_id)
                                            <input type="text" readonly="true" class="form-control" value="{{ isset($data->user->name) ? $data->user->name : ''  }}" />
                                        @else
                                            <input type="text" readonly="true" class="form-control" value="{{ isset($f->UserFamily->nama) ? $f->UserFamily->nama : ''  }}" />
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" readonly="true" class="form-control" value="{{ isset($f->medicalType)? $f->medicalType->name:'' }}">
                                    </td>
                                    <td><input type="text" readonly="true" class="form-control" required value="{{ $f->no_kwitansi }}" /></td>
                                    <td><input type="text" class="form-control" required value="{{ format_idr($f->jumlah) }}" readonly /></td>
                                    <td><a onclick="show_image('{{ $f->file_bukti_transaksi }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View</a></td>
                                    <td>{{ format_idr($f->nominal_approve) }}</td>
                                    <td>
                                        <input type="text" name="note_approval[{{ $f->id }}]" readonly class="form-control" value="{{($f->note_approval) }}" >
                                    </td>
                                </tr>
                                @php($total += $f->jumlah)
                                @php($total_disetujui += $f->nominal_approve)
                                @endforeach
                              </tbody>
                              <tfoot>
                                  <tr>
                                      <th colspan="6" style="text-align: right;">TOTAL</th>
                                      <th colspan="2">{{ format_idr($total) }}</th>
                                      <th colspan="2">{{ format_idr($total_disetujui) }}</th>
                                  </tr>
                              </tfoot>
                            </table>
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
                        </div>

                        <br />

                        <a href="{{ route('karyawan.approval.medical-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        @if($data->is_transfer == 0 && $data->status==2)
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
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<script type="text/javascript">
    
    $("#add").click(function(){

        var no = $('.table-content-lembur tr').length;

        var html = '<tr>';
            html += '<td>'+ (no+1) +'</td>';
            html += '<td><textarea name="description[]" class="form-control"></textarea></td>';
            html += '<td><input type="number" name="quantity[]" class="form-control" /></td>';
            html += '<td><input type="number" name="estimation_cost[]" class="form-control" /></td>';
            html += '<td><input type="number" name="amount[]" class="form-control"  /></td>';
            html += '</tr>';

        $('.table-content-lembur').append(html);

    });

</script>
<script type="text/javascript">
    
    $(document).ready(function () {
        calculate_amountApprove();
    });

    var calculate_amountApprove  = function(){
    var total_nominal = 0;
        $(".input_nominal_approve").each(function(){
            if($(this).val() != "")
            {
                var value = $(this).val();
                total_nominal += parseInt(value.split('.').join(''));            
            }
        });
       $('.th-total-disetujui').html('Rp '+numberWithComma(total_nominal).replace(/,/g, "."));
    }
    
    function show_image(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/file-medical/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/file-medical/')}}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        }
        else{
            alert("Filetype is not supported!");
        }
        {{--bootbox.alert('<img src="{{ asset('storage/file-medical/') }}/'+ img +'" style = \'width: 100%;\' />');--}}
    }


    $(".input_nominal_approve").on('input', function(){
        var total_nominal = 0;
        $(".input_nominal_approve").each(function(){
            if($(this).val() != "")
            {
                var value = $(this).val();
                total_nominal += parseInt(value.split('.').join(''));            
            }
        });
        $('.th-total-disetujui').html('Rp '+numberWithComma(total_nominal).replace(/,/g, "."));
    });

    $("#btn_approved").click(function(){
        if(!$("#noteApproval").val()){
            alertNote()
            $("#noteApproval").addClass('noteError');
        }
        else{
            $("#noteApproval").removeClass('noteError');
            bootbox.confirm('Approve Employee Medical Reimbursement?', function(result){
                $("input[name='status']").val(1);
                if(result)
                {
                    $('#form-medical').submit();
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
            bootbox.confirm('Reject Employee Medical Reimbursement?', function(result){
                if(result)
                {
                    $('#form-medical').submit();
                }
            });
        }
    });

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
            window.alert("Please checked the field Already Transfered");
        }
        else{
            bootbox.confirm('Do you want to send this transfer proof?', function(result){
                if(result)
                {
                    $('#form_transfer').submit();
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

    function show_proof(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/medical/transfer-proof/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/medical/transfer-proof/')}}/'+ img +'" style = \'width: 100%;\' />');
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
