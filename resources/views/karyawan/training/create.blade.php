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
            <form class="form-horizontal" onsubmit="return confirm('Submit Pengajuan Perjalanan Dinas / Training ?');" enctype="multipart/form-data" action="{{ route('karyawan.training.store') }}" method="POST" autocomplete="off">
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

                            <li role="presentation" class=""><a href="#pesawat" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Trip</span></a></li>
                            
                           <!--  <li role="presentation" class=""><a href="#biaya" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Perhitungan Biaya</span></a></li> -->
                            
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade" id="biaya">
                                <h3>Actual Bill</h3>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="background: #eee;">1. Transportation</th>
                                            <th style="background: #eee;">Nominal</th>
                                            <th style="background: #eee;">Nominal Approved</th>
                                            <th style="background: #eee;">Receipt Transaction</th>
                                            <th style="background: #eee;">Note</th>
                                        </tr>
                                        <tr>
                                            <td>Ticket (Train/Airlines/Ship,etc)</td>
                                            <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_ticket" ></td>
                                            <td><input placeholder="IDR"  type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Taxi</td>
                                            <td><input placeholder="IDR" type="number" class="form-control" name="transportasi_taxi" ></td>
                                            <td><input placeholder="IDR" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gasoline</td>
                                            <td><input placeholder="IDR"  type="number" class="form-control" name="transportasi_gasoline" ></td>
                                            <td><input placeholder="IDR" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>Tol</td>
                                            <td><input placeholder="IDR" type="number" class="form-control" name="transportasi_tol" ></td>
                                            <td><input placeholder="IDR" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Parking</td>
                                            <td><input placeholder="IDR" type="number" class="form-control" name="transportasi_parkir" ></td>
                                            <td><input placeholder="IDR" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sub Total</th>
                                            <th class="total_transport"></th>
                                        </tr>
                                    </tbody>
                            </table>
                            <table class="table table-bordered">
                                <tbody>
                                     <tr>
                                            <th style="background: #eee;">2. Hotel and Meal Allowance</th>
                                            <th style="background: #eee;">Plafond</th>
                                            <th style="background: #eee;">Nominal / days</th>
                                            <th style="background: #eee;">Amount (days)</th>
                                            <th style="background: #eee;">Total Request</th>
                                            <th style="background: #eee;">Nominal Approved</th>
                                            <th style="background: #eee;">Receipt Transaction</th>
                                            <th style="background: #eee;">Note</th>

                                        </tr> 
                                        @php ($item = plafond_perjalanan_dinas( isset(\Auth::user()->organisasiposition->name) ? \Auth::user()->organisasiposition->name : ''  ))
                                        <tr>
                                            <td>Hotel</td>
                                            <td>IDR {{ number_format($item->hotel) }}</td>
                                            <td><input type="number" class="form-control" placeholder="IDR " ></td>
                                            <td><input type="number" class="form-control" placeholder="AMOUNT" ></td>
                                            <td></td>
                                            <td><input type="text" readonly="true" class="form-control"></td>
                                            <td><input type="file" class="form-control"></td>
                                            <td><input type="text" class="form-control" placeholder="Note"></td>
                                        </tr>

                                        <tr>
                                            <td>Meal Allowance</td>
                                            <td>IDR  {{ number_format($item->tunjangan_makanan) }}</td>
                                            <td><input type="number" class="form-control" placeholder="IDR " ></td>
                                            <td><input type="number" class="form-control" placeholder="AMOUNT" ></td>
                                            <td></td>
                                            <td><input type="text" readonly="true" class="form-control"></td>
                                            <td><input type="file" class="form-control"></td>
                                            <td><input type="text" class="form-control" placeholder="Note"></td>
                                        </tr>
                                        <tr>
                                            <td>Daily Allowance</td>
                                            <td>IDR {{ number_format($item->hotel) }}</td>
                                            <td><input type="number" class="form-control" placeholder="Rp. " ></td>
                                            <td><input type="number" class="form-control" placeholder="AMOUNT" ></td>
                                            <td></td>
                                            <td><input type="text" readonly="true" class="form-control"></td>
                                            <td><input type="file" class="form-control"></td>
                                            <td><input type="text" class="form-control" placeholder="Note"></td>
                                        </tr>
                                        <tr>
                                            <td>Plane</td>
                                            <td>{{ $item->pesawat }}</td>
                                            <td><input type="number" class="form-control" placeholder="IDR " ></td>
                                            <td><input type="number" class="form-control" placeholder="AMOUNT" ></td>
                                            <td></td>
                                            <td><input type="text" readonly="true" class="form-control"></td>
                                            <td><input type="file" class="form-control"></td>
                                            <td><input type="text" class="form-control" placeholder="Catatan"></td>
                                        </tr>
                                        <tr>
                                            <th>Sub Total</th>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                        <tr>
                                            <th colspan="2" style="background: #eee;">3. Others </th>
                                            <th style="background: #eee;">Nominal </th>
                                            <th style="background: #eee;">Nominal Approved </th>
                                            <th style="background: #eee;">Receipt Transaction </th>
                                            <th style="background: #eee;">Note </th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="text" name="biaya_lainnya_label[]" class="form-control" placeholder="Other Expense" />
                                            </td>
                                            <td>
                                                <input type="text" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Nominal" />
                                            </td>
                                            <td>
                                                <input type="text" name="biaya_lainnya_nominal[]" readonly="true" class="form-control" placeholder="IDR " />
                                            </td>
                                            <td>
                                                <input type="file" placeholder="IDR" />
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Note" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="text" name="biaya_lainnya_label[]" class="form-control" placeholder="Other Expense" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="IDR " />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true" placeholder="IDR " />
                                            </td>
                                            <td>
                                                <input type="file" class="form-control" />
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Note" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="6">Sub Total</th>
                                        </tr>
                                        <tr>
                                            <th colspan="6">Total Actual Bill</th>
                                        </t>
                                        <tr>
                                            <th colspan="6">Cash Advance</th>
                                        </tr>
                                        <tr>
                                            <th colspan="6">Total</th>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade in active" id="kegiatan">
                                <h4><b>Activity Form</b></h4>
                                <hr />
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-6">Business Trip Type</label>
                                        <label class="col-md-6 select-cabang" style="display: none;">Branch Location</label>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <select name="jenis_training" required class="form-control">
                                                <option value="">Choose Business Trip Type</option>
                                                @foreach(jenis_perjalanan_dinas() as $item)
                                                <option>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 select-cabang" style="display: none;">
                                            <select class="form-control" name="cabang_id">
                                                <option value="">Choose Branch Location</option>
                                                @foreach(get_cabang() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name  }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 select-others" style="display: none;">
                                            <input type="text" class="form-control" name="others" placeholder="Others Business Trip">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Location</label>
                                        <div class="col-md-12">
                                            <label style="font-weight: normal;margin-right: 10px;"><input type="radio" name="lokasi_kegiatan" value="Dalam Negeri"> Local</label>

                                            <label style="font-weight: normal;"><input type="radio" name="lokasi_kegiatan" value="Luar Negeri"> Abroad</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Destination</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" required name="tempat_tujuan" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Topic</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control" required name="topik_kegiatan"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Date</label>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_start" id="from" required class="form-control" placeholder="From Date">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_end" id="to" required class="form-control" placeholder="To Date">
                                        </div>
                                    </div>
                                    <hr />
                                    <h4><b>Advance Request</b></h4>
                                    <div class="col-md-12" style="border: 1px solid #eee; padding: 15px">
                                        <div class="form-group">
                                            <label class="col-md-12">Cash Advance Value (IDR)</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="pengambilan_uang_muka" placeholder="IDR " />
                                            </div>
                                        </div>
                                        <div class="form-group tanggal_uang_muka" style="display: none;">
                                            <label class="col-md-6">Request Date</label>
                                            <label class="col-md-6">Settlement Date</label>
                                            <div class="col-md-6 ">
                                                <input type="text" class="form-control" id="from_tanggal_pengajuan" name="tanggal_pengajuan" />
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="to_tanggal_pengajuan" name="tanggal_penyelesaian" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <hr />

                                    <h4><b>Approval</b></h4>
                                    <div class="col-md-12" style="border: 1px solid #eee; padding: 15px">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control autcomplete-atasan" placeholder="Select Superior  / Supervisor">
                                                <input type="hidden" name="approved_atasan_id" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-12">Position</label>
                                            <div class="col-md-6">
                                                <input type="text" readonly="true" class="form-control jabatan_atasan">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-6">Phone Number</label>
                                            <label class="col-md-6">Email</label>
                                            <div class="col-md-6">
                                                <input type="text" readonly="true" class="form-control no_handphone_atasan">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" readonly="true" class="form-control email_atasan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <a href="{{ route('administrator.training.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                        <a href="#pesawat" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false" class="btn btn-info btn-sm next_">NEXT</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pesawat">
                                <h4>Form Booking</h4>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Choose Route</label>
                                    <div class="col-md-6">
                                        <label style="font-weight: normal;"><input type="radio" name="tipe_perjalanan" value="Sekali Jalan"> One Way</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" name="tipe_perjalanan" value="Pulang Pergi"> Round Trip</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Date  / Time</label>
                                    <div class="col-md-2">
                                        <input type="text" placeholder="Departure Date" id="from_tanggal_berangkat" name="tanggal_berangkat" class="form-control">
                                    </div>
                                    <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                    <div class="col-md-1">
                                        <input type="text" class="form-control time_picker" placeholder="Time" name="waktu_berangkat" />
                                    </div>
                                    <div style="float: left; width: 5px;padding-top:10px;"> - </div>

                                    <div class="col-md-2"><input type="text" readonly="true" placeholder="Return Date" id="to_tanggal_pulang" name="tanggal_pulang" class="form-control">
                                    </div>
                                    <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                     <div class="col-md-1">
                                        <input type="text" class="form-control time_picker" readonly="true"  placeholder="Time" name="waktu_pulang" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">From Airport</label>
                                    <label class="col-md-3">To Airport</label>
                                    <div class="clearfix"></div>
                                    <div class="col-md-3">
                                        <input type="text" name="rute_dari_berangkat" class="form-control" id="rute_dari_berangkat" placeholder="From">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="rute_tujuan_berangkat" class="form-control" id="rute_tujuan_berangkat" placeholder="To">
                                    </div>
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
                                        <!-- <a class="btn btn-info btn-xs" id="table_tambah_penumpang"><i class="fa fa-plus"></i> Tambah Penumpang</a> -->

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">Class</label>
                                    <label class="col-md-10">Airline</label>
                                    <div class="col-md-2">
                                        <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_berangkat" value="Ekonomi" /> Economy </label>
                                        <label style="font-weight: normal;"><input type="radio" name="tipe_kelas_berangkat" value="Bisnis" /> Business </label>
                                    </div>
                                    <div class="col-md-6"> 
                                        <input type="text" class="form-control" name="nama_transportasi_berangkat" />
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

                                <div class="col-md-12" style="padding-left: 0;">
                                    <a href="{{ route('karyawan.training.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Submit Activity</button>
                                    <p>* If you don't use the plane, please directly click the submit button</p>
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
<script type="text/javascript">

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

    $('.next_').click(function(){

        $("a[href='#pesawat']").parent().addClass('active');        

        $("a[href='#kegiatan']").parent().removeClass('active');
    });

    $("input[name='pengambilan_uang_muka']").on('input', function(){

        if($(this).val() != "")
        {
            $(".tanggal_uang_muka").show("slow");
        }
        else
        {
            $(".tanggal_uang_muka").hide("slow");
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

    $( "#from_tanggal_pengajuan, #to_tanggal_pengajuan" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function( selectedDate ) {
            if(this.id == 'from_tanggal_pengajuan'){
              var dateMin = $('#from_tanggal_pengajuan').datepicker("getDate");
              var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate()); // Min Date = Selected + 1d
              var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
              $('#to_tanggal_pengajuan').datepicker("option","minDate",rMin);
              $('#to_tanggal_pengajuan').datepicker("option","maxDate",rMax);                    
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

    $("select[name='jenis_training']").on('change', function(){
        if($(this).val() == 'Branch Visit')
        {
            $('.select-cabang').show("slow");
        }
        else
        {
            $('.select-cabang').hide("slow");
        }

        if($(this).val() == 'Others')
        {
            $('.select-others').show("slow");
        }
        else
        {
            $('.select-others').hide("slow");
        }
    });
    
    $("input[name='tipe_perjalanan']").each(function(){

        $(this).on('click', function(){
            
            if($(this).val() == 'Sekali Jalan')
            {   
                $("input[name='tanggal_pulang']").attr('readonly', true);
                $("input[name='waktu_pulang']").attr('readonly', true);
            }
            else
            {
                $("input[name='tanggal_pulang']").removeAttr('readonly');
                $("input[name='waktu_pulang']").removeAttr('readonly');
            }

        });
    });

    $("#rute_dari_berangkat" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
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
            
        },
        showAutocompleteOnFocus: true
    });

    $("#rute_ke, #rute_tujuan_berangkat" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
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

    $("#btn_plus_biaya_lainnya").click(function(){

        var el = '<tr><td><input type="text" name="biaya_lainnya_label[]" class="form-control" placeholder="Biaya Lainnya" /></td><td><input type="text" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Nominal" /><a class="btn btn-xs btn-danger" onclick="hapus_el(this)"><i class="fa fa-trash"></i></a></td></tr>';

        $('.append_lain_lain').append(el);
    })

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
