@extends('layouts.administrator')

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
            <form class="form-horizontal" id="form-training">
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

                            <li role="presentation" class=""><a href="#pesawat" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs"> Trip</span></a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="kegiatan">
                                <h4>Activity Form</h4>
                                <hr />
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">From</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ $data->user->nik .' / '. $data->user->name  }}" readonly="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">BT Number</label>
                                        <div class="col-md-12">
                                            <input type="text" readonly="true" class="form-control" value="{{ isset($data->number)? $data->number:'' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Business Trip Type</label>
                                        <div class="col-md-12">
                                            <input type="text" readonly="true" class="form-control" value="{{ isset($data->training_type)? $data->training_type->name:'' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Location</label>
                                        <div class="col-md-12">
                                            <label style="font-weight: normal;margin-right: 10px;">
                                                <input type="radio" disabled name="lokasi_kegiatan" value="Dalam Negeri" {{ $data->lokasi_kegiatan == 'Dalam Negeri'  ? 'checked' : '' }}> Local
                                            </label>

                                            <label style="font-weight: normal;">
                                                <input type="radio" disabled name="lokasi_kegiatan" value="Luar Negeri" {{ $data->lokasi_kegiatan == 'Luar Negeri' ? 'checked' : '' }}> Abroad
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-12">Name of Account</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" readonly="true" value="{{ isset($data->user->nama_rekening) ? $data->user->nama_rekening : '' }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Account Number</label>
                                        <div class="col-md-12">
                                            <input type="number" class="form-control" readonly="true" value="{{ isset($data->user->nomor_rekening) ? $data->user->nomor_rekening : '' }}" />
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Name Of Bank</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" readonly="true" value="{{ isset($data->user->bank) ? $data->user->bank->name : '' }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Destination</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" readonly name="tempat_tujuan" value="{{ $data->tempat_tujuan }}" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Topic</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control" readonly name="topik_kegiatan">{{ $data->topik_kegiatan }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Activity Date</label>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_start" class="form-control datepicker" placeholder="From Date" readonly value="{{ $data->tanggal_kegiatan_start}}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_end" class="form-control datepicker" placeholder="To Date" readonly value="{{ $data->tanggal_kegiatan_end  }}">
                                        </div>
                                    </div>
                                    <hr />
                                    <h4><b>Cash Advance Request</b></h4>
                                    <div class="col-md-12" style="border: 1px solid #eee; padding: 15px">
                                        <div class="form-group">
                                            <label class="col-md-12">Cash Advance Value (IDR)</label>
                                            <div class="col-md-6">
                                                <input type="text" readonly class="form-control price_format" name="pengambilan_uang_muka" value="{{ $data->pengambilan_uang_muka != null ? format_idr($data->pengambilan_uang_muka) : '' }}" />
                                            </div>
                                        </div>
                                        @if($data->tanggal_pengajuan)
                                        <div class="form-group">
                                            <label class="col-md-6">Request Date</label>
                                            <label class="col-md-6">Settlement Date</label>
                                            <div class="col-md-6">
                                                <input type="text" readonly class="form-control datepicker" value="{{ $data->tanggal_pengajuan }}" name="tanggal_pengajuan" />
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" readonly class="form-control datepicker" name="tanggal_penyelesaian" value="{{ date('Y-m-d', strtotime($data->tanggal_pengajuan .' +'.(get_setting('settlement_duration') ?: 10).' day')) }}" />
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <hr />
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pesawat">
                                <div class="col-md-12">
                                    <h4>Form Booking</h4>
                                    <hr />

                                    <div class="form-group">
                                        <label class="col-md-12">Choose Trip Type</label>
                                        <div class="col-md-6">
                                            <label style="font-weight: normal;">
                                                <input type="radio" disabled readonly="true" {{ $data->tipe_perjalanan == 'Tidak Ada' ? 'checked' : '' }} name="tipe_perjalanan" value="Tidak Ada"> No Trip
                                            </label> &nbsp;&nbsp;
                                            <label style="font-weight: normal;">
                                                <input type="radio" disabled readonly="true" {{ $data->tipe_perjalanan == 'Sekali Jalan' ? 'checked' : '' }} name="tipe_perjalanan" value="Sekali Jalan"> One Way
                                            </label> &nbsp;&nbsp;
                                            <label style="font-weight: normal;">
                                                <input type="radio" disabled readonly="true" {{ $data->tipe_perjalanan == 'Pulang Pergi' ? 'checked' : '' }} name="tipe_perjalanan" value="Pulang Pergi"> Round Trip
                                            </label>
                                        </div>
                                    </div>

                                    @if($data->tipe_perjalanan != 'Tidak Ada')
                                        <div id="trip_container">
                                            <div id="depart_trip">
                                                <h4>Departure</h4>
                                                <div class="form-group">
                                                    <label class="col-md-12">Choose Transportation</label>
                                                    <div class="col-md-6">
                                                        <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_berangkat" {{ $data->transportasi_berangkat == 'Pesawat' ? 'checked' : '' }} value="Pesawat" checked> Plane </label> &nbsp;&nbsp;
                                                        <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_berangkat" {{ $data->transportasi_berangkat == 'Kapal' ? 'checked' : '' }} value="Kapal"> Ship </label> &nbsp;&nbsp;&nbsp;
                                                        <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_berangkat" {{ $data->transportasi_berangkat == 'Kereta' ? 'checked' : '' }} value="Kereta"> Train </label> &nbsp;
                                                        <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_berangkat" {{ $data->transportasi_berangkat == 'Lainnya' ? 'checked' : '' }} value="Lainnya"> Other </label> &nbsp;
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label class="col-md-12">Date  / Time</label>
                                                    <div class="col-md-4">
                                                        <input type="text" placeholder="Departure Date" value="{{ $data->tanggal_berangkat }}" name="tanggal_berangkat" class="form-control" disabled>
                                                    </div>
                                                    <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control time_picker" placeholder="Time"  value="{{ date("H:i",strtotime($data->waktu_berangkat))}}" name="waktu_berangkat" disabled/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3">From Airport/Seaport/Station</label>
                                                    <label class="col-md-3">To Airport/Seaport/Station</label>
                                                    <div class="clearfix"></div>
                                                    <div class="col-md-3">
                                                        <input type="text" name="rute_dari_berangkat" class="form-control" value="{{ $data->rute_dari_berangkat }}" placeholder="From" disabled>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" name="rute_tujuan_berangkat" class="form-control" value="{{ $data->rute_tujuan_berangkat }}" placeholder="To" disabled="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3">Class</label>
                                                    <label class="col-md-9">Airline/Train/Ship</label>
                                                    <div class="col-md-3">
                                                        <label style="font-weight: normal;">
                                                            <input type="radio" disabled name="tipe_kelas_berangkat" value="Ekonomi" {{ $data->tipe_kelas_berangkat == 'Ekonomi' ? 'checked' : '' }} /> Economy
                                                        </label>
                                                        <label style="font-weight: normal;">
                                                            <input type="radio" disabled name="tipe_kelas_berangkat" value="Bisnis"  {{ $data->tipe_kelas_berangkat == 'Bisnis' ? 'checked' : '' }}/> Business
                                                        </label>
                                                        <label style="font-weight: normal;">
                                                            <input type="radio" disabled name="tipe_kelas_berangkat" value="Executive" {{ $data->tipe_kelas_berangkat == 'Executive' ? 'checked' : '' }} /> Executive
                                                        </label>
                                                        <label style="font-weight: normal;">
                                                            <input type="radio" disabled name="tipe_kelas_berangkat" value="First Class"  {{ $data->tipe_kelas_berangkat == 'First Class' ? 'checked' : '' }}/> First Class
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" readonly class="form-control" name="nama_transportasi_berangkat" value="{{ $data->nama_transportasi_berangkat }}" />
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            @if($data->tipe_perjalanan == 'Pulang Pergi')
                                                <div id="return_trip">
                                                    <h4>Return</h4>
                                                    <div class="form-group">
                                                        <label class="col-md-12">Choose Transportation</label>
                                                        <div class="col-md-6">
                                                            <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_pulang" {{ $data->transportasi_pulang == 'Pesawat' ? 'checked' : '' }} value="Pesawat" checked> Plane </label> &nbsp;&nbsp;
                                                            <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_pulang" {{ $data->transportasi_pulang == 'Kapal' ? 'checked' : '' }} value="Kapal"> Ship </label> &nbsp;&nbsp;&nbsp;
                                                            <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_pulang" {{ $data->transportasi_pulang == 'Kereta' ? 'checked' : '' }} value="Kereta"> Train </label> &nbsp;
                                                            <label style="font-weight: normal;"><input type="radio" disabled name="transportasi_pulang" {{ $data->transportasi_pulang == 'Lainnya' ? 'checked' : '' }} value="Lainnya"> Other </label> &nbsp;
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="col-md-12">Date  / Time</label>
                                                        <div class="col-md-4">
                                                            <input type="text" placeholder="Departure Date" value="{{ $data->tanggal_pulang }}" name="tanggal_pulang" class="form-control" disabled>
                                                        </div>
                                                        <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control time_picker" placeholder="Time"  value="{{ date("H:i",strtotime($data->waktu_pulang))}}" name="waktu_pulang" disabled/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3">From Airport/Seaport/Station</label>
                                                        <label class="col-md-3">To Airport/Seaport/Station</label>
                                                        <div class="clearfix"></div>
                                                        <div class="col-md-3">
                                                            <input type="text" name="rute_dari_pulang" class="form-control" value="{{ $data->rute_dari_pulang }}" placeholder="From" disabled>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" name="rute_tujuan_pulang" class="form-control" value="{{ $data->rute_tujuan_pulang }}" placeholder="To" disabled="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-3">Class</label>
                                                        <label class="col-md-9">Airline/Train/Ship</label>
                                                        <div class="col-md-3">
                                                            <label style="font-weight: normal;">
                                                                <input type="radio" disabled name="tipe_kelas_pulang" value="Ekonomi" {{ $data->tipe_kelas_pulang == 'Ekonomi' ? 'checked' : '' }} /> Economy
                                                            </label>
                                                            <label style="font-weight: normal;">
                                                                <input type="radio" disabled name="tipe_kelas_pulang" value="Bisnis"  {{ $data->tipe_kelas_pulang == 'Bisnis' ? 'checked' : '' }}/> Business
                                                            </label>
                                                            <label style="font-weight: normal;">
                                                                <input type="radio" disabled name="tipe_kelas_pulang" value="Executive" {{ $data->tipe_kelas_pulang == 'Executive' ? 'checked' : '' }} /> Executive
                                                            </label>
                                                            <label style="font-weight: normal;">
                                                                <input type="radio" disabled name="tipe_kelas_pulang" value="First Class"  {{ $data->tipe_kelas_pulang == 'First Class' ? 'checked' : '' }}/> First Class
                                                            </label>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" readonly class="form-control" name="nama_transportasi_pulang" value="{{ $data->nama_transportasi_pulang }}" />
                                                        </div>
                                                    </div>
                                                    <hr>
                                                </div>
                                            @endif

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
                                                            <td>{{ $data->user->name .' / '. $data->user->nik }}</td>
                                                            <td>{{ $data->user->ktp_number }}</td>
                                                            <td>{{ $data->user->passport_number }}</td>
                                                            <td>{{ $data->user->jenis_kelamin }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-12">Business Trip Partner</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" value="{{ $data->pergi_bersama }}" readonly="true" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-12">Note</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" value="{{ $data->note }}" readonly="true" />
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                            </div>
                        </div>

                        @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-12">Note Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                </div>
                            </div>
                        @endforeach
                        @if($data->status==2 && $data->pengambilan_uang_muka > 0)
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
                            <a onclick="show_image('{{ $data->transfer_proof }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                            @endif
                        @endif
                        <div class="clearfix"></div>
                        <br />

                        <input type="hidden" name="status" value="0" />
                        <input type="hidden" name="id" value="{{ $data->id }}">

                        <div class="col-md-12">
                            <a href="{{ route('administrator.trainingCustom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
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
<style type="text/css">
    .custome_table tr th {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
</style>

@section('footer-script')
<script type="text/javascript">

    $("#btn_approved").click(function(){
        bootbox.confirm('Approve Perjalanan Dinas Karyawan ?', function(result){

            $("input[name='status']").val(1);
            if(result)
            {
                $('#form-training').submit();
            }

        });
    });

    $("#btn_tolak").click(function(){
        bootbox.confirm('Tolak Perjalanan Dinas Karyawan ?', function(result){

            if(result)
            {
                $('#form-training').submit();
            }

        });
    });

    function show_image(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/training-custom/transfer-proof/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/training-custom/transfer-proof/')}}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        }
        else{
            alert("Filetype is not supported!");
        }
    }

</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
@endsection
