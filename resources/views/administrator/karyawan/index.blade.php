@extends('layouts.administrator')

@section('title', 'Employee')

@section('content')
<style>
.tscroll table td:first-child {
  position: sticky;
  left: 0;
  background-color: #fff;
}
.tscroll table td:nth-child(2) {
  position: sticky;
  left: 50px;
  background-color: #fff;
  width:120px;
}
.tscroll table td:nth-child(3) {
  position: sticky;
  left: 135px;
  background-color: #fff;
  width:180px;
}
.tscroll table th:first-child {
  position: sticky;
  left: 0;
  background-color: #fff;
}
.tscroll table th:nth-child(2) {
  position: sticky;
  left: 50px;
  background-color: #fff;
  width:120px;
}
.tscroll table th:nth-child(3) {
  position: sticky;
  left: 135px;
  background-color: #fff;
  width:180px;
}
</style>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <form method="POST" action="{{ route('administrator.karyawan.index') }}" id="filter-form">
                {{ csrf_field() }}
                <input type="hidden" name="action" value="view">
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                    <h4 class="page-title">Manage Employee</h4> 
                </div>
                <div class="col-lg-10 col-sm-9 col-md-9 col-xs-12">
                    <div class="col-md-12 pull-right" style="padding: 0px">
                        <div class="col-md-2 pull-right" style="padding: 0px">
                            @if(get_setting('layout_karyawan') != 'grid') 
                            <div class="btn-group pull-right" style="padding-right: 2px">
                                <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                    <li><a class="toggle-vis" data-column="2" style="color:blue;">NIK</a></li> 
                                    <li><a class="toggle-vis" data-column="3" style="color:blue;">Name</a></li> 
                                    <li><a class="toggle-vis" data-column="4" style="color:blue;">Mobile</a></li>
                                    <li><a class="toggle-vis" data-column="5" style="color:blue;">Email</a></li>
                                    <li><a class="toggle-vis" data-column="6" style="color:blue;">Position</a></li>
                                    <li><a class="toggle-vis" data-column="7" style="color:blue;">Branch</a></li>
                                    <li><a class="toggle-vis" data-column="8" style="color:blue;">Status</a></li>
                                    <li><a class="toggle-vis" data-column="9" style="color:blue;">Join</a></li>
                                    <li><a class="toggle-vis" data-column="10" style="color:blue;">Resign </a></li>
                                    <li><a class="toggle-vis" data-column="11" style="color:blue;">End Contract</a></li>
                                    <li><a class="toggle-vis" data-column="12" style="color:blue;">Action</a></li>
                                </ul>
                            </div>
                            @endif
                            <div class="btn-group pull-right" style="padding-right: 2px">
                                <a href="javascript:void(0)" title="Settings" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                    <i class="fa fa-gear"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu" style="min-width: 10px;">
                                    @if(get_setting('layout_karyawan') == 'grid') 
                                        <li><a href="{{ route('administrator.karyawan.index') }}?layout_karyawan=list"><i class="fa fa-list"></i> List View</a></li>
                                    @else
                                        <li><a href="{{ route('administrator.karyawan.index') }}?layout_karyawan=grid"><i class="fa fa-th-large"></i> Grid View</a></li>
                                    @endif
                                    @if(get_setting('employee_softdeletes') == 2)
                                        <li><a href="{{ route('administrator.karyawan.index') }}?employee_softdeletes=1"><i class="fa fa-user-plus"></i> Active Employee</a></li>
                                        <li><a href="{{ route('administrator.karyawan.index') }}?employee_softdeletes=0"><i class="fa fa-users"></i> All Employee</a></li>
                                    @elseif(get_setting('employee_softdeletes') == 1)
                                        <li><a href="{{ route('administrator.karyawan.index') }}?employee_softdeletes=2"><i class="fa fa-user-times"></i> Resign Employee</a></li>
                                        <li><a href="{{ route('administrator.karyawan.index') }}?employee_softdeletes=0"><i class="fa fa-users"></i> All Employee</a></li>
                                    @else
                                        <li><a href="{{ route('administrator.karyawan.index') }}?employee_softdeletes=1"><i class="fa fa-user-plus"></i> Active Employee</a></li>
                                        <li><a href="{{ route('administrator.karyawan.index') }}?employee_softdeletes=2"><i class="fa fa-user-times"></i> Resign Employee</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="btn-group pull-right" style="padding-right: 2px; padding-left:2px;">
                                <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action</a>
                                <ul role="menu" class="dropdown-menu" style="top:-30px;">
                                    <li><a href="javascript:void(0)" onclick="reset_filter()"> <i class="fa fa-refresh"></i> Reset Filter</a></li>
                                    @if(checkUserLimit())
                                    <li><a href="{{ route('administrator.karyawan.create') }}"> <i class="fa fa-plus"></i> Add Employee</a></li>
                                    @endif
                                    <li><a href="javascript:void(0)" id="add-import-karyawan"> <i class="fa fa-upload"></i> Import</a></li>
                                    <li><a onclick="submit_filter_download()"><i class="fa fa-download"></i> Download </a></li>
                                    @if(checkModuleAdmin(4))
                                    <li><a onclick="submit_filter_download_leave()"><i class="fa fa-download"></i> Download Leave Report </a></li>
                                    @endif
                                    @if(checkModuleAdmin(34))
                                    <li><a onclick="submit_filter_download_contract()"><i class="fa fa-download"></i> Download Contract </a></li>
                                    @endif
                                    <!--<li><a id="delete-karyawan"><i class="ti-trash"></i> Delete </a></li>-->
                                </ul>
                            </div>
                            <button id="filter_view" class="btn btn-default btn-sm pull-right btn-outline"> <i class="fa fa-search-plus"></i></button>
                        </div>

                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="employee_status">
                                    <option value="">- Employee Status - </option>
                                    <option {{ (request() and request()->employee_status == 'Permanent') || Session::get('e-employee_status') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option {{ (request() and request()->employee_status == 'Contract') || Session::get('e-employee_status') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option {{ (request() and request()->employee_status == 'Internship') || Session::get('e-employee_status') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    <option {{ (request() and request()->employee_status == 'Outsource') || Session::get('e-employee_status') == 'Outsource' ? 'selected' : '' }}>Outsource</option>
                                    <option {{ (request() and request()->employee_status == 'Freelance') || Session::get('e-employee_status') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option {{ (request() and request()->employee_status == 'Consultant') || Session::get('e-employee_status') == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="division_id">
                                    <option value=""> - choose Division - </option>
                                    @foreach($division as $item)
                                    <option value="{{ $item->id }}" {{ $item->id== request()->division_id || $item->id== Session::get('e-division_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="position_id">
                                    <option value=""> - choose Position - </option>
                                    @foreach($position as $item)
                                    <option value="{{ $item->id }}" {{ $item->id== request()->position_id || $item->id== Session::get('e-position_id') ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right {{ get_setting('employee_softdeletes') ? 'hidden' : '' }}">
                            <div class="form-group m-b-0">
                                <select class="form-control form-control-line" name="employee_resign">
                                    <option value="">- Active/Resign - </option>
                                    <option {{ (request() && request()->employee_resign == 'Active' || Session::get('e-employee_resign') == 'Active' && !get_setting('employee_softdeletes')) || get_setting('employee_softdeletes') == 1 ? 'selected' : '' }}>Active</option>
                                    <option {{ (request() && request()->employee_resign == 'Resign' || Session::get('e-employee_resign') == 'Resign' && !get_setting('employee_softdeletes')) || get_setting('employee_softdeletes') == 2 ? 'selected' : '' }}>Resign</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || Session::get('e-name') ? Session::get('e-name') : '' }}" placeholder="Name / NIK Employee">
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 pull-right" style="padding: 0px; padding-top: 15px;">
                    
                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="end_date_end_contract" class="form-control datepicker form-control-line" value="{{ (request() and request()->end_date_end_contract) || Session::get('e-end_date_end_contract') ? Session::get('e-end_date_end_contract') : '' }}" placeholder="End Date End Contract">
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="start_date_end_contract" class="form-control datepicker form-control-line" value="{{ (request() and request()->start_date_end_contract) || Session::get('e-start_date_end_contract') ? Session::get('e-start_date_end_contract') : '' }}" placeholder="Start Date End Contract">
                            </div>
                        </div>

                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="end_date_resign" class="form-control datepicker form-control-line" value="{{ (request() and request()->end_date_resign) || Session::get('e-end_date_resign') ? Session::get('e-end_date_resign') : '' }}" placeholder="End Date Resign">
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="start_date_resign" class="form-control datepicker form-control-line" value="{{ (request() and request()->start_date_resign) || Session::get('e-start_date_resign') ? Session::get('e-start_date_resign') : '' }}" placeholder="Start Date Resign">
                            </div>
                        </div>

                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="end_date_join" class="form-control datepicker form-control-line" value="{{ (request() and request()->end_date_join) || Session::get('e-end_date_join') ? Session::get('e-end_date_join') : '' }}" placeholder="End Date Join">
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group m-b-0">
                                <input type="text" name="start_date_join" class="form-control datepicker form-control-line" value="{{ (request() and request()->start_date_join) || Session::get('e-start_date_join') ? Session::get('e-start_date_join') : '' }}" placeholder="Start Date Join">
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        
        @if(get_setting('layout_karyawan') == 'grid')
        @foreach($data as $no => $item)
        <div class="col-md-4 col-sm-4">
            <div class="white-box" style="min-height: 241px;">
                <div class="row">
                    <div class="btn-group m-r-10 pull-right">
                        <a aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle waves-effect waves-light">Action 
                            <span class="caret"></span>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="{{ route('administrator.karyawan.edit', $item->id) }}"><i class="fa fa-search-plus"></i> Detail</a></li>
                            @if(isset($item->non_active_date) && \Carbon\Carbon::parse($item->non_active_date)->isPast() && !$item->is_rejoined)
                                <li><a href="{{ route('administrator.karyawan.rejoin', $item->id) }}"><i class="fa fa-refresh"></i> Rejoin</a></li>
                            @endif
                            {{-- <li>
                                <form action="{{ route('administrator.karyawan.destroy', $item->id) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}                                               
                                    <a href="javascript:void(0)" onclick="confirm_delete('Delete this data ?', this)"><i class="ti-trash"></i> Delete</a>
                                </form>
                            </li> --}}
                            <li><a href="{{ route('administrator.karyawan.print-profile', $item->id) }}" target="_blank"><i class="fa fa-print"></i> Print</a></li>                                        
                            @if(!empty($item->generate_kontrak_file))
                                <li><a href="{{ asset('/storage/file-kontrak/'. $item->id. '/'. $item->generate_kontrak_file) }}" target="_blank"><i class="fa fa-search-plus"></i> View Contract File</a> </li>
                            @endif
                            {{-- @if($item->is_generate_kontrak == "")
                            <li><a onclick="generate_file_document({{ $item->id }})"><i class="fa fa-file"></i> Generate Contract Document</a></li>
                            @endif
                            <li><a onclick="upload_file_dokument({{ $item->id }})"><i class="fa fa-upload"></i> Upload Contract File</a></li> --}}
                            <li><a onclick="confirm_loginas('{{ $item->name }}','{{ route('administrator.karyawan.autologin', $item->id) }}')"><i class="fa fa-key"></i> Autologin</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-4 text-center">
                        <a href="{{ route('administrator.karyawan.edit', $item->id) }}">
                            @if(empty($item->foto))
                                @if($item->jenis_kelamin == 'Male' || $item->jenis_kelamin == "")
                                    <img src="{{ asset('images/Birthday_Male_Icon.png') }}" alt="{{ $item->title }}" class="img-circle img-responsive">
                                @else
                                    <img src="{{ asset('images/Birthday_Female_Icon.png') }}" alt="{{ $item->title }}" class="img-circle img-responsive">
                                @endif
                            @else
                                <img src="{{ asset('storage/foto/'.$item->foto) }}" alt="{{ $item->title }}" class="img-circle img-responsive">
                            @endif
                        </a><br />
                        <p><strong>{{ $item->nik }}</strong></p>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3 class="box-title m-b-0">{{ $item->name }}</h3> 
                        <small>
                        @if(!empty($item->empore_organisasi_staff_id))
                            {{ isset($item->empore_staff->name) ? $item->empore_staff->name : '' }}
                        @endif

                        @if(empty($item->empore_organisasi_staff_id) and !empty($item->empore_organisasi_manager_id))
                            {{ isset($item->empore_manager->name) ? $item->empore_manager->name : '' }}
                        @endif
                        </small>
                        <address>
                            {{ $item->current_address }}<br />
                            @if(!empty($item->telepon))
                                <i class="mdi mdi-phone"></i> {{ $item->telepon }}<br />
                            @endif
                            @if(!empty($item->email))
                                <i class="mdi mdi-email"></i> <a href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                            @endif
                        </address>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-md-12 p-l-0 p-r-0">
            <div class="white-box">
                <div class="table-responsive" style="padding-bottom: 40px;">
                <!-- <div>
                    Hide column: 
                    <a class="toggle-vis" data-column="2">NIK</a> - 
                    <a class="toggle-vis" data-column="3">NAME</a> - 
                    <a class="toggle-vis" data-column="4">MOBILE</a> -
                    <a class="toggle-vis" data-column="5">EMAIL</a> -
                    <a class="toggle-vis" data-column="6">POSITION</a> -
                    <a class="toggle-vis" data-column="7">BRANCH</a> -
                    <a class="toggle-vis" data-column="8">STATUS</a> -
                    <a class="toggle-vis" data-column="9">JOIN</a> -
                    <a class="toggle-vis" data-column="10">RESIGN </a> -
                    <a class="toggle-vis" data-column="11">END CONTRACT</a> -
                    <a class="toggle-vis" data-column="12">ACTION</a>
                </div> -->
                    <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>NO</th>
                                <th>NIK</th>
                                <th>@lang('general.name')</th>
                                <th>MOBILE</th>
                                <th>EMAIL</th>
                                <th>POSITION</th>
                                <th>BRANCH</th>
                                <th>STATUS</th>
                                <th>JOIN</th>
                                <th>RESIGN</th>
                                <th>END CONTRACT</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
        @endif
    </div>
</div>
@include('layouts.footer')

<!-- modal content education  -->
<div id="modal_upload_dokument" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Upload Contract Document</h4> </div>
                <form method="POST" id="form-upload-file-dokument" enctype="multipart/form-data" class="form-horizontal" action="{{ route('administrator.karyawan.upload-dokument-file') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3">File (xls)</label>
                        <div class="col-md-9">
                            <input type="file" name="file" class="form-control" />
                            <input type="hidden" name="user_id">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info btn-sm">Upload File</button>
                </div>
            </form>
            <div style="text-align: center;display: none;" class="div-proses-upload">
                <h3>Please wait for the upload process!</h3>
                <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
            </div>
        </div>
    </div>
</div> 

<!-- modal content education  -->
<div id="modal_generate_dokument" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Generate Contract Document</h4> </div>
                    <form method="POST" id="form-generate-file-dokument" enctype="multipart/form-data" class="form-horizontal" action="{{ route('administrator.karyawan.generate-dokument-file') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-6">Join Date</label>
                            <label class="col-md-6">End Date</label>
                            <div class="col-md-6">
                                <input type="text" name="join_date" class="form-control datepicker">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="end_date" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Status</label>
                            <div class="col-md-9">                                
                                <select class="form-control" name="organisasi_status">
                                    <option value="">- pilih -</option>
                                    <option>Permanent</option>
                                    <option>Contract</option>
                                    <option>Internship</option>
                                    <option>Outsource</option>
                                    <option>Freelance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info btn-sm">Generate File</button>
                    </div>
                </form>
                <div style="text-align: center;display: none;" class="div-proses-upload">
                    <h3>Please wait for the upload process!</h3>
                    <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
                </div>
        </div>
    </div>
</div>

<!-- modal content education  -->
<div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Import Data</h4> </div>
                    <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.karyawan.import') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">File (xls)</label>
                            <div class="col-md-9">
                                <input type="file" name="file" class="form-control" />
                            </div>
                        </div>
                        <a href="{{ asset('storage/sample/Sample-Karyawan.xlsx') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <label class="btn btn-info btn-sm" id="btn_import">Import</label>
                    </div>
                </form>
                <div style="text-align: center;display: none;" class="div-proses-upload">
                    <h3>Uploading !</h3>
                    <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
                </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    loadData();
    var t;
    function loadData(){
        $('#mytable').DataTable().destroy();
        $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };
        t = $("#mytable").DataTable({
            searching: false,
            lengthChange: false,
            pageLength: 50,
            fixedHeader: true,
            fixedColumns:   {
            left: 3,
            },
            scrollCollapse: true,
            scrollX: true,
            initComplete: function() {
                var api = this.api();
                $('#mytable_filter input')
                    .off('.DT')
                    .on('keyup.DT', function(e) {
                        if (e.keyCode == 13) {
                            api.search(this.value).draw();
                        }
                    });
            },
            oLanguage: {
                sProcessing: "loading..."
            },
            oSearch: { "bSmart": false, "bRegex": true },
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ route('administrator.karyawan.table') }}",
                "type": "GET",
                "data": {
                    "end_date_join": $('input[name="end_date_join"]').val(),
                    "start_date_join": $('input[name="start_date_join"]').val(),
                    "end_date_resign": $('input[name="end_date_resign"]').val(),
                    "start_date_resign": $('input[name="start_date_resign"]').val(),
                    "end_date_end_contract": $('input[name="end_date_end_contract"]').val(),
                    "start_date_end_contract": $('input[name="start_date_end_contract"]').val(),
                    "employee_status": $('select[name="employee_status"]').val(),
                    "division_id": $('select[name="division_id"]').val(),
                    "position_id": $('select[name="position_id"]').val(),
                    "employee_resign": $('select[name="employee_resign"]').val(),
                    "name": $('input[name="name"]').val(),
                }
            },
            columns: [
                { "data": "id", "name":"id", "visible": false, "searchable": false },
                { "data": "id", "orderable": false, "searchable": false },
                { "data": "column_nik", "name": 'nik' },
                { "data": "column_name", "name": 'name' },
                { "data": "mobile_1" },
                { "data": "email" },
                { "data": "column_position", "name":'structure.position.name' },
                { "data": "column_cabang", "name":'cabang.name' },
                { "data": "column_status", "name": 'organisasi_status' },
                { "data": "column_join", "name": 'join_date' },
                { "data": "column_resign", "name": 'resign_date' },
                { "data": "end_date_contract" },
                { "data": "column_action", "orderable": false, "searchable": false },
            ],
            order: [[0, 'desc']],
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                var index = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        });
    };
    

    function reload_table()
    {
        $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
    }
    
    $("#filter_view").click(function(){
        $("#filter-form input[name='action']").val('view');
        $("#filter-form").submit();

    });

    function reset_filter()
    {
        $("#filter-form input.form-control, #filter-form select").val("");
        $("#filter-form input[name='action']").val('');
        $("input[name='reset']").val(1);
        $("#filter-form").submit();
    }

    var submit_filter_download = function(){
        $("#filter-form input[name='action']").val('download');
        $("#filter-form").submit();
    }

    var submit_filter_download_leave = function(){
        $("#filter-form input[name='action']").val('download_leave');
        $("#filter-form").submit();
    }

    var submit_filter_download_contract = function(){
        $("#filter-form input[name='action']").val('download_contract');
        $("#filter-form").submit();
    }

    function confirm_loginas(name, url)
    {
        bootbox.confirm("Login as "+ name +" ? ", function(result){

            if(result)
            {
                window.location = url;
            }
        });
    }

    jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });

    function generate_file_document(id)
    {
        $("#modal_generate_dokument").modal("show");

        $("#form-generate-file-dokument input[name='user_id']").val(id);

        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.get-karyawan-by-id') }}',
            data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (data) {

                $("#form-generate-file-dokument input[name='join_date']").val(data.data.join_date);
                $("#form-generate-file-dokument input[name='end_date']").val(data.data.end_date);
                $("#form-generate-file-dokument select[name='organisasi_status']").val(data.data.organisasi_status);
            }
        });
    }

    function upload_file_dokument(id)
    {
        $("#modal_upload_dokument").modal("show");

        $("#form-upload-file-dokument input[name='user_id']").val(id);
    }

    $("#btn_import").click(function(){

        $("#form-upload").submit();
        $("#form-upload").hide();
        $('.div-proses-upload').show();

    });

    $("#add-import-karyawan").click(function(){
        $("#modal_import").modal("show");
        $('.div-proses-upload').hide();
        $("#form-upload").show();
    })

    $("input[name='check_all']").click(function () {    
        $('input:checkbox').prop('checked', this.checked);  
    });

    $('#delete-karyawan').click(function(){
        var employees = [];
        $.each($("input[name='checked_id[]']:checked"), function(){            
            employees.push($(this).val());
        });

        if(employees.length < 1){
            bootbox.confirm({
                title : "<i class=\"fa fa-warning\"></i> EMPORE SYSTEM",
                message: "Belum ada data yang dipilih",
                closeButton: false,
                callback: function (result) {
                    if(result)
                    { 
                        
                    }
                }
            });
        }
        var url = "<?php echo route('ajax.get-karyawan-by-id') ?>";
        for(i=0; i<employees.length; i++){
            var id = employees[i];
            bootbox.confirm({
                title : "<i class=\"fa fa-warning\"></i> EMPORE SYSTEM",
                message: "Delete this data ?",
                closeButton: false,
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn btn-sm btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn btn-sm btn-danger'
                    }
                },
                callback: function (result) {
                    if(result)
                    { 
                        $.ajax({
                            type: 'POST',
                            url: '{{ route('ajax.delete-karyawan') }}',
                            data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                            dataType: 'json',
                            success: function (msg) {
                                window.location = "<?php echo route('administrator.karyawan.index') ?>"; 
                            }
                        });  
                    }
                }
            });
        }


        
    });

    $('a.toggle-vis').on('click', function (e) {
        e.preventDefault();
        e.target.style.color == 'blue' ? $(this).addClass('change-toggle') : $(this).removeClass('change-toggle');
        e.target.style.color = e.target.style.color == 'blue' ? 'red' : 'blue';
    
        // console.log($(this).attr('href'))
        // $($(this).attr('href')).click(function(e) {
        //     e.stopPropagation();
        // })
        // $($(this).attr('href')).prop("checked", !$($(this).attr('href')).prop("checked"));
        // if((e.target).tagName == 'INPUT') return true; 
        
        // Get the column API object
        var column = t.column($(this).attr('data-column'));
 
        // Toggle the visibility
        column.visible(!column.visible());
    });
    
</script>
@endsection
@endsection