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
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.medical-custom.store') }}" id="form-medical" method="POST"  autocomplete="off">
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
                                <label class="col-md-12">Claim Date</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ date('Y-m-d') }}" name="tanggal_pengajuan" readonly="true" />
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                        </div>
                        <div class="col-md-6">

                            <br />
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
                        </div>

                        <div class="clearfix"></div>
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
                                      <th></th>
                                  </tr>
                              </thead>
                              <tbody class="table-claim">
                                <tr class="oninput">
                                    <td>1</td>
                                    <input type="hidden" name="status" value="1">
                                    <td><input type="date" class="form-control input" required name="tanggal_kwitansi[]" /></td>
                                    <td>
                                        <select name="user_family_id[]" class="form-control input" onchange="select_hubungan(this)" required>
                                            <option value="">Choose Relationship</option>
                                            <option value="0" data-nama="{{ \Auth::user()->name }}">My Self</option>
                                            @foreach(Auth::user()->userFamily as $item)
                                            <option value="{{ $item->id }}" data-nama="{{ $item->nama }}">{{ $item->hubungan }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" readonly="true" class="form-control nama_hubungan input" /></td>
                                    <td>
                                        <select name="medical_type_id[]" class="form-control input" required>
                                            <option  selected disabled> - choose Medical Type - </option>
                                        @foreach($type as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->medical_type_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="no_kwitansi[]" required/></td>
                                    <td><input type="text" class="form-control price_format nominal input" name="jumlah[]" required /></td>
                                    <td>
                                        <input type="file" class="form-control file_bukti input" name="file_bukti_transaksi[]" id="medical_1"  accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" required />
                                        <div id="default_medical_1"></div><div id="preview_medical_1" style="display: none"></div>
                                    </td>
                                    <td id="showhide"><a class="btn btn-danger btn-xs" onclick="hapus_item(this)"><i class="fa fa-trash"></i></a></td>
                                </tr>

                              </tbody>
                              <tfoot>
                                  <tr>
                                      <th colspan="6" style="text-align: right;">TOTAL : </th>
                                      <th class="th-total"></th>
                                  </tr>
                              </tfoot>
                          </table>
                          <span class="btn btn-info btn-xs pull-right" id="add">Add</span>

                        </div>

                     
                        <div class="clearfix"></div>
                        <br />
                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="{{ route('karyawan.medical-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Submit Medical Reimbursement</a>
                                <a class="btn btn-sm btn-primary waves-effect waves-light m-r-10" id="btn_draft"><i class="fa fa-save"></i> Save Draft</a>
                                <br style="clear: both;" />
                            </div>
                        </div>
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

@section('footer-script')
{{--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
{{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
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

            if($(this).val() == "")
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
                        '<td><input type="file" class="form-control file_bukti input" name="file_bukti_transaksi[]" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" id="medical_'+(no+1)+'" required />'+
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
    // $("#add").show();
    // validate_form = true;
    // $('.oninput .input').each(function(){
    //
    //     if($(this).val() == "")
    //     {
    //         $("#add").hide();
    //         validate_form = false;
    //     }
    // });

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
    if(confirm("Delete this item?"))
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

</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
