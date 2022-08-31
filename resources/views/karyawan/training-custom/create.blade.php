@extends('layouts.karyawan')

@section('title', 'Business Trip')

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
                <h4 class="page-title"></h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Business Trip</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" onsubmit="return confirm('Submit Business Trip / Training ?');" enctype="multipart/form-data" action="{{ route('karyawan.training-custom.store') }}" method="POST" autocomplete="off">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form Business Trip</h3>
                        <hr />
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
                        
                        <ul class="nav customtab nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#kegiatan" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Activity</span></a></li>

                            <li role="presentation"><a href="#pesawat" aria-controls="messages" class="" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Trip</span></a></li>
                            
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="kegiatan">
                                <h4><b>Activity Form</b></h4>
                                <hr />
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Business Trip Type</label>
                                        <div class="col-md-12">
                                            <select name="training_type_id" required class="form-control input">
                                                <option value="">Choose Business Trip Type</option>@foreach($trainingType as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Location</label>
                                        <div class="col-md-12">
                                            <label style="font-weight: normal;margin-right: 10px;"><input type="radio" id="lokasi_kegiatan" name="lokasi_kegiatan" value="Dalam Negeri" checked> Local</label>

                                            <label style="font-weight: normal;"><input type="radio" id="lokasi_kegiatan" name="lokasi_kegiatan" value="Luar Negeri"> Abroad</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Destination</label>
                                        <div class="col-md-12">
                                            <select class="form-control" id="tempat_tujuan" name="tempat_tujuan">
                                                <option value=""> --Select Destination--</option>
                                                @foreach($district as $item)
                                                <option value="{{$item->nama}}">{{$item->nama}}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control" name="tempat_tujuan_aboard" id="tempat_tujuan_aboard" style="display: none;"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Topic</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control input" required name="topik_kegiatan"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Date</label>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_start" id="from" required class="form-control input" placeholder="From Date">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_end" id="to" required class="form-control input" placeholder="To Date">
                                        </div>
                                    </div>
                                    <hr />
                                    <h4><b>Cash Advance Request</b></h4>
                                    <div class="col-md-12" style="border: 1px solid #eee; padding: 15px">
                                        <div class="form-group">
                                            <label class="col-md-12">Cash Advance Value (IDR)</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control price_format pengambilan_uang_muka" name="pengambilan_uang_muka"/>
                                            </div>
                                        </div>
                                        <div class="form-group tanggal_uang_muka" style="display: none;">
                                            <label class="col-md-6">Request Date</label>
                                            <label class="col-md-6">Settlement Date</label>
                                            <div class="col-md-6 ">
                                                <input type="text" class="form-control" id="from_tanggal_pengajuan" name="tanggal_pengajuan" />
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="to_tanggal_pengajuan" name="tanggal_penyelesaian" disabled/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <hr />
                                    
                                    <div class="form-group">
                                        <a href="{{ route('karyawan.training-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                        <a href="#" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false" class="btn btn-info btn-sm" onclick="next()">NEXT</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pesawat">
                                <h4>Form Booking</h4>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Choose Trip Type</label>
                                    <div class="col-md-6">
                                        <label style="font-weight: normal;"><input type="radio" name="tipe_perjalanan" value="Tidak Ada"> No Trip</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" name="tipe_perjalanan" value="Sekali Jalan" checked> One Way</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" name="tipe_perjalanan" value="Pulang Pergi"> Round Trip</label>
                                    </div>
                                </div>
                                <div id="trip_container">
                                    <div id="depart_trip">
                                        <h4>Departure</h4>
                                        <div class="form-group">
                                            <label class="col-md-12">Choose Transportation</label>
                                            <div class="col-md-6">
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_berangkat" value="Pesawat" checked> Plane </label> &nbsp;&nbsp;
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_berangkat" value="Kapal"> Ship </label> &nbsp;&nbsp;&nbsp;
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_berangkat" value="Kereta"> Train </label> &nbsp;
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_berangkat" value="Lainnya"> Other </label> &nbsp;
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Date  / Time</label>
                                            <div class="col-md-4">
                                                <input type="text" placeholder="Departure Date" id="from_tanggal_berangkat" name="tanggal_berangkat" class="form-control">
                                            </div>
                                            <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control time_picker" placeholder="Time" name="waktu_berangkat" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3">From Airport/Seaport/Station</label>
                                            <label class="col-md-3">To Airport/Seaport/Station</label>
                                            <div class="clearfix"></div>
                                            <div class="col-md-3">
                                                <input type="text" name="rute_dari_berangkat" class="form-control" id="rute_dari_berangkat" placeholder="From">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="rute_tujuan_berangkat" class="form-control" id="rute_tujuan_berangkat" placeholder="To">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3">Class</label>
                                            <label class="col-md-9">Airline/Train/Ship</label>
                                            <div class="col-md-3">
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_berangkat" value="Ekonomi" /> Economy </label>
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_berangkat" value="Bisnis" /> Business </label>
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_berangkat" value="Executive" /> Executive </label>
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_berangkat" value="First Class" /> First Class </label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" name="nama_transportasi_berangkat" />
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <div id="return_trip" class="hidden">

                                        <h4>Return</h4>
                                        <div class="form-group">
                                            <label class="col-md-12">Choose Transportation</label>
                                            <div class="col-md-6">
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_pulang" value="Pesawat" checked> Plane </label> &nbsp;&nbsp;
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_pulang" value="Kapal"> Ship </label> &nbsp;&nbsp;&nbsp;
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_pulang" value="Kereta"> Train </label> &nbsp;
                                                <label style="font-weight: normal;"><input type="radio" name="transportasi_pulang" value="Lainnya"> Other </label> &nbsp;
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4"><input type="text" placeholder="Return Date" id="to_tanggal_pulang" name="tanggal_pulang" class="form-control">
                                            </div>
                                            <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control time_picker" placeholder="Time" name="waktu_pulang" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3">From Airport/Seaport/Station</label>
                                            <label class="col-md-3">To Airport/Seaport/Station</label>
                                            <div class="clearfix"></div>
                                            <div class="col-md-3">
                                                <input type="text" name="rute_dari_pulang" class="form-control" id="rute_dari_pulang" placeholder="From">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="rute_tujuan_pulang" class="form-control" id="rute_tujuan_pulang" placeholder="To">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3">Class</label>
                                            <label class="col-md-9">Airline/Train/Ship</label>
                                            <div class="col-md-3">
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_pulang" value="Ekonomi" /> Economy </label>
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_pulang" value="Bisnis" /> Business </label>
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_pulang" value="Executive" /> Executive </label>
                                                <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_pulang" value="First Class" /> First Class </label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" name="nama_transportasi_pulang" />
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Passenger Information</label>
                                        <div class="col-md-6">
                                            <table class="table table-bordered custome_table">
                                                <thead>
                                                    <tr>
                                                        <th>NIK</th>
                                                        <th>KTP Number</th>
                                                        <th>Passport Number</th>
                                                        <th>Gender</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-penumpang">
                                                    <tr>
                                                        <td>{{ Auth::user()->name .' / '.Auth::user()->nik }}</td>
                                                        <td>{{ Auth::user()->ktp_number }}</td>
                                                        <td>{{ Auth::user()->passport_number }}</td>
                                                        <td>{{ Auth::user()->jenis_kelamin }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Business Trip Partner</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="pergi_bersama" placeholder="Type here.." />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Note</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="note" placeholder="Type here.." />
                                        </div>
                                    </div>
                                </div>
                        <div class="clearfix"></div>
                        <br/>

                                <div class="col-md-12" style="padding-left: 0;">
                                    <a href="{{ route('karyawan.training-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Submit Activity</button>
                                    {{--<p>* If you don't use the plane, please directly click the submit button</p>--}}
                                    <br style="clear: both;" />
                                </div>
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
<style type="text/css">
    .custome_table tr th {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
    .disabledTab{
        pointer-events: none;
    }
</style>

<!-- sample modal content -->
<div id="modal_penumpang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Data Passenger</h4> </div>
                <div class="modal-body">
                   <div class="form-horizontal">
                       <div class="form-group">
                            <label class="col-md-3">Choose Passenger</label>
                            <div class="col-md-6">
                                <select class="form-control penumpang_id">
                                    <option value="">Choose Passenger </option>
                                    @foreach(get_karyawan() as $item)
                                    <option value="{{ $item->id }}" data-kelamin="{{ $item->jenis_kelamin }}">{{ $item->nik }} / {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                       </div>
                   </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="add_modal_penumpang">Proces Form Business Trip</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div> 
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {
        $('#tempat_tujuan').select2();

        $("input[name$='lokasi_kegiatan']").click(function() {
            var test = $(this).val();
            // if(test == 'Luar Negeri'){
            //     $("#tempat_tujuan").hide();
            //     $('#tempat_tujuan').next(".select2-container").hide();
            //     $("#tempat_tujuan_aboard" ).show();
            //     $('#tempat_tujuan_aboard').addClass('active');
            // }
            // else{
            //     $("#tempat_tujuan_aboard" ).hide();
            //     $("#tempat_tujuan").show();
            //     $('#tempat_tujuan').next(".select2-container").show();
            //     $('#tempat_tujuan').addClass('active');
            // }
        });
    });

    $(".autocomplete-bersama" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
              url: "{{ route('ajax.get-karyawan') }}",
              method : 'POST',
              data: {
                'name': request.term,'_token' : $("meta[name='csrf-token']").attr('content')
              },
              success: function( data ) {
                response( data );
              }
            });
        },
        select: function( event, ui ) {
            $( ".modal_finance_id" ).val(ui.item.id);
        }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });

    price_format();
    $(".pengambilan_uang_muka").on('input', function(){
        if($(this).val() && $(this).val() != 0)
        {
            $(".tanggal_uang_muka").show();
        }
        else
        {
            $(".tanggal_uang_muka").hide("slow");
            $("#from_tanggal_pengajuan").val('');
            $("#to_tanggal_pengajuan").val('');           
        }

    });

    function next() {
        $("a[href='#pesawat']").trigger("click")
    }

    $("a[href='#pesawat']").click(function (e) {
        var validate = validate_form();
        if(!validate) {
            bootbox.alert('Form must be completed!');
            e.stopPropagation();
            return false;
        }
    });

    var list_atasan = [];
    @foreach(empore_get_atasan_langsung() as $item)
        list_atasan.push({id : {{ $item->id }}, value : '{{ $item->nik .' - '. $item->name.' - '. empore_jabatan($item->id) }}',  });
    @endforeach

</script>
<script type="text/javascript">
    
    $( "#from_tanggal_berangkat, #to_tanggal_pulang" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function( selectedDate ) {
            if(this.id == 'from_tanggal_berangkat'){
              var dateMin = $('#from_tanggal_berangkat').datepicker("getDate");
              var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate()); // Min Date = Selected + 1d
              var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
              $('#to_tanggal_pulang').datepicker("option","minDate",rMin);
              $('#to_tanggal_pulang').datepicker("option","maxDate",rMax);                    
            }
        }
    });
    function validate_form()
    {
        var validate = true;

        $('.input').each(function(){

            if($(this).val() == "")
            {
                $(this).parent().addClass('has-error');
                console.log('cek');
                validate = false;
            }
        });

        if($("input[name='pengambilan_uang_muka']").val() && $("input[name='pengambilan_uang_muka']").val() > 0 && $('#from_tanggal_pengajuan').val() == "") {
            $('#from_tanggal_pengajuan').parent().addClass('has-error');
            console.log('cek');
            validate = false;
        }

        return validate;
    }

    var settlement_duration = "{{ get_setting('settlement_duration') ?: 10 }}"

    $( "#from_tanggal_pengajuan, #to_tanggal_pengajuan" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function( selectedDate ) {
            if(this.id == 'from_tanggal_pengajuan'){
              var dateMin = $('#from_tanggal_pengajuan').datepicker("getDate");
              $('#to_tanggal_pengajuan').datepicker("setDate", new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + parseInt(settlement_duration)));                       
            }
        }
    });

    $( "#from, #to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function( selectedDate ) {
            if(this.id == 'from'){
              var dateMin = $('#from').datepicker("getDate");
              var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate()); // Min Date = Selected + 1d
              var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
              $('#to').datepicker("option","minDate",rMin);
              $('#to').datepicker("option","maxDate",rMax);                    
            }
        }
    });

    $(".autcomplete-atasan" ).autocomplete({
        source: list_atasan,
        minLength:0, 
        select: function( event, ui ) {
            $( "input[name='approved_atasan_id']" ).val(ui.item.id);
            
            var id = ui.item.id;

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    $('.jabatan_atasan').val(data.data.jabatan);
                    $('.department_atasan').val(data.data.department_name);
                    $('.no_handphone_atasan').val(data.data.telepon);
                    $('.email_atasan').val(data.data.email);
                }
            });
        }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });

    // Clock pickers
    $('.time_picker').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });

    $("#tempat_tujuan" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-city') }}",
                method:"POST",
                data: {'word' : request.term, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType:"json",
                success:function(data)
                {
                    response(data);
                }
            })
        },
        select: function( event, ui ) {
            $("input[name='tempat_tujuan']").val(ui.item.id)
        },
        showAutocompleteOnFocus: true
    });


    $("input[name='tipe_perjalanan']").each(function(){

        $(this).on('click', function(){
            if($(this).val() == 'Tidak Ada')
            {
                $('#trip_container').addClass('hidden');
            }
            else if($(this).val() == 'Sekali Jalan')
            {
                $('#trip_container').removeClass('hidden');
                $('#depart_trip').removeClass('hidden');
                $('#return_trip').addClass('hidden');
            }
            else
            {
                $('#trip_container').removeClass('hidden');
                $('#depart_trip').removeClass('hidden');
                $('#return_trip').removeClass('hidden');
            }

        });
    });

    $("#rute_dari_berangkat" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
                method:"POST",
                data: {'word' : request.term,'type':$("input[name='transportasi_berangkat']:checked").val(), '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType:"json",
                success:function(data)
                {
                    response(data);
                }
            })
        },
        select: function( event, ui ) {
            
        },
        showAutocompleteOnFocus: true
    });

    $("#rute_ke, #rute_tujuan_berangkat" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
                method:"POST",
                data: {'word' : request.term,'type':$("input[name='transportasi_berangkat']:checked").val(), '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType:"json",
                success:function(data)
                {
                    response(data);
                }
            })
        },
        select: function( event, ui ) {
            $("input[name='pesawat_rute_ke']").val(ui.item.id)
        },
        showAutocompleteOnFocus: true
    });

    $("#rute_dari_pulang" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
                method:"POST",
                data: {'word' : request.term,'type':$("input[name='transportasi_pulang']:checked").val(), '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType:"json",
                success:function(data)
                {
                    response(data);
                }
            })
        },
        select: function( event, ui ) {

        },
        showAutocompleteOnFocus: true
    });

    $("#rute_dari, #rute_tujuan_pulang" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
                method:"POST",
                data: {'word' : request.term,'type':$("input[name='transportasi_pulang']:checked").val(), '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType:"json",
                success:function(data)
                {
                    response(data);
                }
            })
        },
        select: function( event, ui ) {
            $("input[name='pesawat_rute_ke']").val(ui.item.id)
        },
        showAutocompleteOnFocus: true
    });

    $("#add_modal_penumpang").click(function(){

        var html_ = '<tr><td><input type="hidden" name="penumpang_id[]" value="'+ $('.penumpang_id').val() +'" />'+ $('.penumpang_id :selected').text() +'</td><td>'+ $('.penumpang_id :selected').data('kelamin') +'</td></tr>';

        $('.table-penumpang').html(html_);
    });

    $("#table_tambah_penumpang").click(function(){
        $('#modal_penumpang').modal('show');
    });
    function hapus_el(el)
    {
        $(el).parent().parent().remove();
    }

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
@endsection
