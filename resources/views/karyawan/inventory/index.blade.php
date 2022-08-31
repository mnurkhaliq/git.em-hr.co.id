@extends('layouts.karyawan')

@section('title', 'Facilities')

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
                <h4 class="page-title">HOME</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Home</a></li>
                    <li class="active">Facilities</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 p-0">
                <div class="white-box">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>ASSET NUMBER</th>
                                    <th>ASSET NAME</th>
                                    <th>ASSET TYPE</th>
                                    <th>SERIAL/PLAT NUMBER</th>
                                    <th>PURCHASE/RENTAL DATE</th>
                                    <th>ASSET CONDITION</th>
                                    <th>STATUS ASSET</th>
                                    <th>RECEIVER</th>
                                    <th>HANDOVER DATE</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->assets as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ $item->asset_number }}</td>
                                        <td>{{ $item->asset_name }}</td>
                                        <td>{{ isset($item->asset_type->name) ? $item->asset_type->name : ''  }}</td>
                                        <td>{{ $item->asset_sn }}</td>
                                        <td>{{ format_tanggal($item->purchase_date) }}</td>
                                        <td>{{ $item->asset_condition }}</td>
                                        <td>{{ $item->assign_to }}</td>
                                        <td>{{ isset($item->user->name) ? $item->user->name : '' }}</td>
                                        <td>{{ $item->handover_date != "" ?  format_tanggal($item->handover_date) : '' }}</td>
                                        <td>
                                            @if($item->handover_date === NULL)
                                                <span class="badge badge-warning">Waiting Acceptance</span>
                                            @endif

                                            @if($item->handover_date !== NULL && $item->status==1)
                                                <span class="badge badge-success">Accepted</span>
                                            @endif

                                            @if($item->handover_date !== NULL && $item->status==2)
                                                <label class="badge badge-info">Waiting Returned</label>
                                            @endif

                                            @if($item->handover_date !== NULL && $item->status==3)
                                                <label class="badge badge-danger">Rejected</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == null || $item->status == 0)
                                                <a href="{{ route('karyawan.facilities.show', $item->id) }}" class="btn btn-warning btn-xs" style="margin-bottom: 2px;"> Process Asset</a>
                                            @endif
                                            @if($item->status == 1)
                                                <a href="{{ route('karyawan.facilities.edit', $item->id) }}" class="btn btn-success btn-xs" style="margin-bottom: 2px;"> Return Asset</a>
                                            @endif
                                            @if($item->status != 3)
                                            <button type="button" class="btn btn-primary btn-xs" onclick="addNote({{ $item->id }},'{{ $item->asset_number }}','{{ $item->user_id == $item->user_note_by ? $item->user_note : null }}')">Add/Edit Note</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                       </table>
                    </div>
                    <div class="clearfix"></div><br />
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
</style>


@section('footer-script')
    <div class="modal fade none-border" id="modal-note">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Facilities <span id="number_note"></span> Note</strong></h4>
                </div>
                <form action="{{ route('karyawan.update-note') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="id_note" name="id_note">
                    <div class="modal-body" id="modal-add-body">
                        <div class="form-group col-md-12">
                            <label>Note</label>
                            <div>
                                <textarea required id="user_note" name="user_note" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success save-event waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">

        function addNote(id, number, note) {
            $('#id_note').val(id)
            $('#number_note').html(number)
            $('#user_note').val(note)
            $('#modal-note').modal('show')
        }
    </script>
@endsection

@endsection
