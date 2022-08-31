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
                <h4 class="page-title">Form Medical Reimbursement</h4> </div>
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
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.medical-custom.update', $data->id) }}"  id="form-medical" method="POST">
                <input type="hidden" name="_method" value="PUT">
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
                                    <input type="text" readonly="" class="form-control" value="{{ Auth::user()->nik .' - '. Auth::user()->name }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control jabatan" value="{{ isset(Auth::user()->structure->position) ? Auth::user()->structure->position->name:''}}{{ isset(Auth::user()->structure->division) ? ' - '. Auth::user()->structure->division->name:''}}{{ isset(Auth::user()->structure->title) ? ' - '. Auth::user()->structure->title->name:'' }}">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-6">Claim Date</label>
                                <label class="col-md-6">Name Of Bank</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" name="tanggal_pengajuan" class="form-control" value="{{ $data->tanggal_pengajuan }}" />
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
                                      @if($data->status!=5)
                                      <th>AMOUNT APPROVED (IDR)</th>
                                      <th>NOTE APPROVED</th>
                                      @else
                                      <th></th>
                                      @endif
                                  </tr>
                              </thead>
                              <tbody class="table-claim">
                                @php ($total = 0)
                                @php ($total_disetujui = 0)
                                @foreach($form as $key => $f)
                                <tr class="oninput">
                                    <td>{{ $key+1 }}<input type="hidden" name="idForm[]" value="{{$f->id}}"></td>
                                    <input type="hidden" name="status" value="1">
                                    <td><input type="date" class="form-control input"  {{$data->status == 5 ? 'required' : 'readonly' }} name="tanggal_kwitansi[]" value="{{ $f->tanggal_kwitansi }}"  /></td>
                                    <td>
                                        @if($data->status != 5 && $f->user_family_id == 0)
                                            <input type="text" {{$data->status == 5 ? '' : 'readonly' }} class="form-control" value="My Self">
                                        @elseif($data->status != 5 && $f->user_family_id != 0)
                                            <input type="text" {{$data->status == 5 ? '' : 'readonly' }} class="form-control" value="{{ isset($f->UserFamily->hubungan) ? $f->UserFamily->hubungan : ''  }}">
                                        @else
                                            <select name="user_family_id[]" class="form-control input" onchange="select_hubungan(this)" required>
                                                <option value="">Choose Relationship</option>
                                                <option value="0" {{ "0" == $f->user_family_id ? 'selected' : '' }} data-nama="{{ \Auth::user()->name }}">My Self</option>
                                                @foreach(Auth::user()->userFamily as $item)
                                                <option value="{{ $item->id }}" data-nama="{{ $item->nama }}" {{ $item->id== $f->user_family_id ? 'selected' : '' }} >{{ $item->hubungan }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                    <td>
                                        @if("0" == $f->user_family_id)
                                            <input type="text" {{$data->status == 5 ? 'readonly' : 'readonly' }} class="form-control nama_hubungan input" value="{{ isset($data->user->name) ? $data->user->name : ''  }}" />
                                        @else
                                            <input type="text" {{$data->status == 5 ? 'readonly' : 'readonly' }} class="form-control nama_hubungan input" value="{{ isset($f->UserFamily->nama) ? $f->UserFamily->nama : ''  }}" />
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->status != 5)
                                        <input type="text" {{$data->status == 5 ? '' : 'readonly' }} class="form-control" value="{{ isset($f->medicalType)? $f->medicalType->name:'' }}">
                                        @else
                                            <select name="medical_type_id[]" class="form-control input" required>
                                                <option  selected disabled> - choose Medical Type - </option>
                                            @foreach($type as $item)
                                            <option value="{{ $item->id }}" {{ $item->id== $f->medical_type_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                            </select>
                                        @endif
                                    </td>
                                    <td><input type="text" name="no_kwitansi[]" {{$data->status == 5 ? '' : 'readonly' }} class="form-control" required value="{{ $f->no_kwitansi }}" /></td>
                                    <td><input type="text" name="jumlah[]" class="form-control price_format nominal" required value="{{ $data->status == 5 ? $f->jumlah :  format_idr($f->jumlah) }}" {{$data->status == 5 ? '' : 'readonly' }}/></td>
                                    @if($data->status != 5)
                                    <td><a onclick="show_image('{{ $f->file_bukti_transaksi }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a></td>
                                    @else
                                    <td>
                                        <input type="file" class="form-control file_bukti input" name="file_bukti_transaksi[]" id="medical_{{$f->id}}" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" {{$f->file_bukti_transaksi ? '' : 'required'}}/>
                                        <div id="default_medical_{{$f->id}}">
                                        @if($f->file_bukti_transaksi)
                                        <a onclick="show_image('{{ $f->file_bukti_transaksi }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                        @endif
                                        </div><div id="preview_medical_{{$f->id}}" style="display: none"></div>
                                    </td>
                                    @endif
                                    @if($data->status != 5)
                                    <td><input type="text" readonly="true" class="form-control price_format" value="{{ format_idr($f->nominal_approve) }}"></td>
                                    <td>
                                        <input type="text" name="note_approval[{{ $f->id }}]" readonly class="form-control" value="{{($f->note_approval) }}" >
                                    </td>
                                    @else
                                    <td id="showhide"><a class="btn btn-danger btn-xs" onclick="hapus_item(this)"><i class="fa fa-trash"></i></a></td>
                                    @endif
                                </tr>
                                @php($total += $f->jumlah)
                                @php($total_disetujui += $f->nominal_approve)
                                @endforeach
                              </tbody>
                              <tfoot>
                                  <tr>
                                      <th colspan="6" style="text-align: right;">TOTAL</th>
                                      @if($data->status==5)
                                      <th class="th-total"></th>
                                      @else
                                      <th colspan="2">{{ format_idr($total) }}</th>
                                      <th colspan="2" class="th-total-disetujui">{{ format_idr($total_disetujui) }}</th>
                                      @endif
                                  </tr>
                              </tfoot>
                          </table>  
                        </div>
                        @if($data->status==5)
                        <span class="btn btn-info btn-xs pull-right" id="add">Add</span>
                        @else
                        @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-12">Note Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-8">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                </div>
                            </div>
                        @endforeach
                        @endif
                        <br />
                        <br />
                        <div class="col-md-12">
                            <a href="{{ route('karyawan.medical-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            @if($data->status==5)
                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Submit Medical Reimbursement</a>
                            <a class="btn btn-sm btn-primary waves-effect waves-light m-r-10" id="btn_draft"><i class="fa fa-save"></i> Save Draft</a>
                            @endif
                            <br style="clear: both;" />
                        </div>
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
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>
@section('footer-script')
    <script type="text/javascript">

        validate_form = true;

        show_hide_add();
        cek_button_add();

        jQuery('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        var data_dependent = [];

        $("#btn_draft").click(function(){
            $('.oninput').find('td').removeAttr('required');
            const input = $('.oninput').find('td').removeAttr('required')
            input.required = false
            bootbox.confirm('Save as Draft?', function(res){
                if(res)
                {
                    $("input[name='status']").val(5);
                    $("#form-medical").submit();
                }
            });
            
        });

        $("#btn_submit").click(function(){
            $('.oninput').find('td').removeClass("has-error");
            var jumlah = $('.table-claim tr').length;


            if(jumlah <= 0)
            {
                bootbox.alert('Form not completed. Please check and resubmit.');
                validate = false;
                return;
            }
            var validate = form_validate();
            if(!validate){
                bootbox.alert('Form not completed. Please check and resubmit.');
                return false;
            }
            else{
                bootbox.confirm('Process Form Medical Reimbursement ?', function(result){
                if(result)
                {
                    $("input[name='status']").val(1);
                    $("#form-medical").submit();
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
            $('.oninput select').each(function(){

                if($(this).val() == null || $(this).val() == "")
                {
                    $(this).parent().addClass('has-error');
                    validate = false;
                }
            });
            return validate;
        }

        function select_hubungan(el)
        {
            var nama_hubungan = $(el).find(":selected").data('nama');

            if(nama_hubungan == "") return false;

            $(el).parent().parent().find('.nama_hubungan').val(nama_hubungan);
        }

        $("#add").click(function(){

            var no = $('.table-claim tr').length;
            if((no+1) <= 15) {
            var html =  '<tr class="oninput">'+
                            '<td>'+(no+1)+'</td>'+
                            '<td><input type="date" class="form-control input" required name="tanggal_kwitansi[]" /></td>'+
                            '<td>'+
                                '<select name="user_family_id[]" class="form-control input" onchange="select_hubungan(this)" required>'+
                                    '<option value="">Choose relationship</option><option value="0" data-nama="{{ \Auth::user()->name }}">My Self</option>@foreach(Auth::user()->userFamily as $item)<option value="{{ $item->id }}" data-nama="{{ $item->nama }}">{{ $item->hubungan }}</option>@endforeach'+
                                '</select>'+
                            '</td>'+
                            '<td><input type="text" readonly="true" class="form-control nama_hubungan" /></td>'+
                            '<td>'+
                                '<select name="medical_type_id[]" class="form-control input" required>'+
                                                '<option selected disabled> - choose Medical Type - </option>'+
                                            '@foreach($type as $item)'+
                                            '<option value="{{ $item->id }}" {{ $item->id== request()->medical_type_id ? 'selected' : '' }}>{{ $item->name }}</option> @endforeach'+
                                            '</select>'+
                            '</td> '+
                            '<td><input type="text" class="form-control" name="no_kwitansi[]" required/></td>'+
                            '<td><input type="text" class="form-control price_format nominal input" name="jumlah[]" required /></td>'+
                            '<td><input type="file" class="form-control file_bukti input" name="file_bukti_transaksi[]" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" required />'+
                            '<div id="default_medical_'+(no+1)+'"></div><div id="preview_medical_'+(no+1)+'" style="display: none"></div>'+  
                            '</td>'+
                            '<td id="showhide"><a class="btn btn-danger btn-xs" onclick="hapus_item(this)"><i class="fa fa-trash"></i></a></td>'+
                            '</tr>';

            $('.table-claim').append(html);

            jQuery('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd'
            });

            cek_button_add();
            show_hide_add();
            price_format();

            $(".nominal").on('input', function(){
                var total = 0;
                    $('.nominal').each(function(){
                        if($(this).val() != ""){
                                var value = $(this).val();
                                total += parseInt(value.split('.').join(''));
                            }
                    });
                $('.th-total').html(numberWithComma(total).replace(/,/g, "."));
            });

            initImage()
            }
            else{
                alert('Maximal of items are 15, Please make a new form!')
            }
        });

        function initImage() {
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementsByClassName("file_bukti");
                for (var i = 0; i < filesInput.length; i++) {
                    filesInput[i].replaceWith(filesInput[i].cloneNode(true));
                    filesInput[i].addEventListener("change", function(event) {
                        var files = event.target.files; //FileList object
                        var id = event.target.id;
                        var output = $("#preview_" + id)[0];
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

        window.onload = function() {
            initImage();
        }

        function show_hide_add()
        {

            var total_nominal = 0;
            $(".oninput input[name='jumlah[]']").each(function(){
                if($(this).val() != "")
                {
                    var value = $(this).val();
                    total_nominal += parseInt(value.split('.').join(''));
                    // total_nominal += parseInt($(this).val());
                }
            });

            $('.th-total').html(numberWithComma(total_nominal).replace(/,/g, "."));

        }

        function cek_button_add()
        {
            $('.oninput input').on('keyup',function(){
                show_hide_add();
            });

            $('.oninput input').on('change',function(){
                show_hide_add();
            });

            $('.oninput select').on('change',function(){
                show_hide_add();
            });

            var rowCount = $(".table-claim tr").length;
            if(rowCount == 1) {
                $("#showhide").hide();
            }
            else{
                $("#showhide").show();
            }
        }

        function hapus_item(el)
        {
            if(confirm("Delete this item ?"))
            {
                $(el).parent().parent().remove();
                cek_button_add();
                show_hide_add();
            }

            var rowCount = $(".table-claim tr").length;
            if(rowCount == 1) {
                $("#showhide").hide();
            }
            else{
                $("#showhide").show();
            }
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
