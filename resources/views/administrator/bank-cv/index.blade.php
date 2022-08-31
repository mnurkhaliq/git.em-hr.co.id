@extends('layouts.administrator')

@section('title', 'Bank CV')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title" style="overflow: inherit;">
            <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                <h4 class="page-title">Manage Bank CV</h4> 
            </div>
            <div class="col-lg-10 col-sm-9 col-md-9 col-xs-12">
                <form method="POST" action="" id="filter-form">
                    {{ csrf_field() }}
                    <input type="hidden" name="action" value="view">
                    @if(get_setting('layout_bank_cv') != 'grid') 
                    <div class="btn-group pull-right">
                        <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                            <i class="fa fa-eye"></i>
                        </a>
                        <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                            <li><a class="toggle-vis" data-column="1" style="color:blue;">NIK</a></li>                                   
                            <li><a class="toggle-vis" data-column="2" style="color:blue;">Name</a></li>
                            <li><a class="toggle-vis" data-column="3" style="color:blue;">Email</a></li>
                            <li><a class="toggle-vis" data-column="4" style="color:blue;">Phone Number</a></li>
                            <li><a class="toggle-vis" data-column="5" style="color:blue;">Year of Birth</a></li>
                            <li><a class="toggle-vis" data-column="6" style="color:blue;">Skill</a></li>
                            @foreach($column->where('is_list', 1) as $no => $item)
                                <li><a class="toggle-vis" data-column="{{$no}}+7" style="color:blue;">{{($item->name)}}</a></li>
                            @endforeach
                            <li><a class="toggle-vis" data-column="{{count($column->where('is_list', 1))}}+7" style="color:blue;">Test</a></li>
                            <li><a class="toggle-vis" data-column="{{count($column->where('is_list', 1))}}+8" style="color:blue;">Expected Salary</a></li>
                            <li><a class="toggle-vis" data-column="{{count($column->where('is_list', 1))}}+9" style="color:blue;">Updated By</a></li>
                            <li><a class="toggle-vis" data-column="{{count($column->where('is_list', 1))}}+10" style="color:blue;">Action</a></li>
                        </ul>
                    </div>
                    @endif
                    <div class="btn-group m-r-5 pull-right">
                        <a href="javascript:void(0)" title="Layout Table Bank CV Grid / Table" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                            @if(get_setting('layout_bank_cv') == 'grid') 
                                <i class="fa fa-th-large"></i>
                            @else
                                <i class="fa fa-list"></i>
                            @endif
                        </a>
                        <ul role="menu" class="dropdown-menu" style="min-width: 10px;">
                            @if(get_setting('layout_bank_cv') == 'grid') 
                            <li><a href="{{ route('administrator.bank-cv.index') }}?layout_bank_cv=list" class="pull-right" title="Table Data" style="{{ (get_setting('layout_bank_cv') == 'list') ? 'color: grey;' : '' }} padding: 0px 10px;"><i class="fa fa-list"></i></a></li>
                            @else
                            <li><a href="{{ route('administrator.bank-cv.index') }}?layout_bank_cv=grid" class="pull-right" title="Grid Data" style="{{ (get_setting('layout_bank_cv') == 'grid') ? 'color: grey;' : '' }} padding: 0px 10px;"><i class="fa fa-th-large"></i></a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="btn-group m-l-5 m-r-5 pull-right">
                        <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action 
                            <i class="fa fa-gear"></i>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="{{ route('administrator.bank-cv.create') }}"> <i class="fa fa-plus"></i> Add CV</a></li>
                            <li><a href="javascript:void(0)" id="add-import-karyawan"> <i class="fa fa-upload"></i> Import</a></li>
                            <li><a onclick="submit_filter_download()"><i class="fa fa-download"></i> Download </a></li>
                            <li><a href="javascript:void(0)" onclick="reset_filter()"> <i class="fa fa-refresh"></i> Reset Filter</a></li>
                            <!--<li><a id="delete-karyawan"><i class="ti-trash"></i> Delete </a></li>-->
                        </ul>
                    </div>
                    <button id="filter_view" class="btn btn-default btn-sm pull-right btn-outline"> <i class="fa fa-search-plus"></i></button>
                    <div class="col-md-10 pull-right" style="padding: 0px">
                        <div class="col-md-3">
                            <div class="form-group m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{\Session::get('bc-name')}}" placeholder="Name / NIK CV">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-b-0">
                                <input type="text" name="skill" class="form-control form-control-line" value="{{\Session::get('bc-skill')}}" placeholder="Skill">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group m-b-0">
                                <input type="number" name="min_salary" class="form-control form-control-line" value="{{\Session::get('bc-min_salary')}}" placeholder="Maximum Expected Salary">
                            </div>
                        </div>                      
                        <div class="col-md-3">
                            <div class="form-group m-b-0">
                                <input type="number" name="max_salary" class="form-control form-control-line" value="{{\Session::get('bc-max_salary')}}"placeholder="Minimum Expected Salary">
                            </div>
                        </div>
                        @foreach($column->where('is_filter', 1) as $no => $item)
                        <div class="col-md-3">
                            <div class="form-group m-b-0">
                                @if($item->is_dropdown)
                                <select class="form-control form-control-line" name="option[{{ $item->id }}]">
                                    <option value="">- Select {{ ucwords($item->name) }} -</option>
                                    @foreach($item->values as $no => $val)
                                    <option value="{{ $val->id }}" {{ (request() and isset(request()->option[$item->id]) and request()->option[$item->id] == $val->id) || \Session::get('bc-value') == $val->id ? 'selected' : '' }}>{{ $val->value }}</option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" name="option[{{ $item->id }}]" class="form-control form-control-line" value="{{ (request() and isset(request()->option[$item->id])) ? request()->option[$item->id] : '' }}" placeholder="{{ ucwords($item->name) }}">
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
        @if(get_setting('layout_bank_cv') == 'grid')
        @foreach($data as $no => $item)
        <div class="col-md-4 col-sm-4">
            <div class="white-box" style="min-height: 241px;">
                <div class="row">
                    <div class="btn-group m-r-10 pull-right">
                        <a aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle waves-effect waves-light">Action 
                            <span class="caret"></span>
                        </a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="{{ route('administrator.bank-cv.edit', $item->id) }}"><i class="fa fa-search-plus"></i> Detail</a></li>
                            <li>
                                <form action="{{ route('administrator.bank-cv.destroy', $item->id) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}                                               
                                    <a href="javascript:void(0)" onclick="confirm_delete('Delete this data ?', this)"><i class="ti-trash"></i> Delete</a>
                                </form>
                            </li>
                            {{-- <li><a href="{{ route('administrator.bank-cv.print-profile', $item->id) }}" target="_blank"><i class="fa fa-print"></i> Print</a></li> --}}                                        
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-4 text-center">
                        <a href="{{ route('administrator.bank-cv.edit', $item->id) }}">
                            @if(empty($item->photos))
                                @if($item->gender == 'Male' || $item->gender == "")
                                    <img src="{{ asset('images/user-man.png') }}" alt="{{ $item->title }}" class="img-circle img-responsive">
                                @else
                                    <img src="{{ asset('images/user-woman.png') }}" alt="{{ $item->title }}" class="img-circle img-responsive">
                                @endif
                            @else
                                <img src="{{ asset('storage/file-cv-photos/'.$item->photos) }}" alt="{{ $item->name }}" class="img-circle img-responsive">
                            @endif
                        </a><br />
                        <p><strong>{{ $item->nik }}</strong></p>
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3 class="box-title m-b-0">{{ $item->name }}</h3> 
                        <small>
                            {{ $item->born_year ?: '' }}
                            {{ $item->salary ?: '' }}
                        </small>
                        <address>
                            {{ $item->address }}<br />
                            @if(!empty($item->phone_number))
                                <i class="mdi mdi-phone"></i> {{ $item->phone_number }}<br />
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
                <div class="table-responsive">
                    <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <!-- <th></th> -->
                                <th>NO</th>
                                <th>NIK</th>
                                <th>@lang('general.name')</th>
                                <th>EMAIL</th>
                                <!-- <th>ADDRESS</th> -->
                                <th>PHONE NUMBER</th>
                                <th>YEAR OF BIRTH</th>
                                <th>SKILL</th>
                                @foreach($column->where('is_list', 1) as $no => $item)
                                <th>{{ strtoupper($item->name) }}</th>
                                @endforeach
                                <th>EXPECTED SALARY</th>
                                <th>UPDATED BY</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $no => $item)
                            <tr>
                                <td>{{ $no+1 }}</td>
                                <td>
                                    <a href="{{ route('administrator.bank-cv.edit', $item->id) }}">
                                        <b>{{ strtoupper($item->nik) }}</b>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('administrator.bank-cv.edit', $item->id) }}">
                                        <b>{{ strtoupper($item->name) }}</b>
                                    </a>
                                </td>
                                <td>{{ $item->email }}</td>
                                {{-- <td>{{ $item->address }}</td> --}}
                                <td>{{ $item->phone }}</td>
                                <td>{{ $item->born_year }}</td>
                                <td>
                                    @foreach($item->tags as $no => $val)
                                    <button type="button" class="btn-xs btn-primary">{{ $val->tag }}</button>
                                    @endforeach
                                </td>
                                @foreach($column->where('is_list', 1) as $no => $val)
                                @if($val->is_dropdown)
                                @if(isset($item->options->where('bank_cv_option_id', $val->id)->first()->value->value))
                                <td>{{ $item->options->where('bank_cv_option_id', $val->id)->first()->value->value }}</td>
                                @else
                                <td></td>
                                @endif
                                @else
                                @if($item->options->where('bank_cv_option_id', $val->id)->first())
                                <td>{{ $item->options->where('bank_cv_option_id', $val->id)->first()->bank_cv_option_value }}</td>
                                @else
                                <td></td>
                                @endif
                                @endif
                                @endforeach
                                <td>{{ $item->salary }}</td>
                                <td>{{ $item->updatedBy ? $item->updatedBy->nik.' - '.$item->updatedBy->name : 'Applicant' }}</td>
                                <td>
                                    <div class="btn-group m-r-10">
                                        <button aria-expanded="false" data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle waves-effect waves-light" type="button">Action
                                            <span class="caret"></span>
                                        </button>
                                        <ul role="menu" class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('administrator.bank-cv.edit', $item->id) }}"><i class="fa fa-search-plus"></i> Detail</a>
                                            </li>
                                            <li>
                                                <form action="{{ route('administrator.bank-cv.destroy', $item->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}                                               
                                                    <a href="javascript:void(0)" onclick="confirm_delete('Delete this data ?', this)"><i class="ti-trash"></i> Delete</a>
                                                </form>
                                            </li>
                                            {{-- <li><a href="{{ route('administrator.bank-cv.print-profile', $item->id) }}" target="_blank"><i class="fa fa-print"></i> Print</a></li> --}}                                        
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
        @endif
    </div>
</div>
@include('layouts.footer')

<!-- modal import  -->
<div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Import Data</h4> </div>
                    <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.bank-cv.import') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">File (xls)</label>
                            <div class="col-md-9">
                                <input type="file" name="file" class="form-control" />
                            </div>
                        </div>
                        <a href="{{ route('administrator.bank-cv.download') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
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

    // loadData();

    // function loadData(){
    //     $('#mytable').DataTable().destroy();
    //     $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
    //     {
    //         return {
    //             "iStart": oSettings._iDisplayStart,
    //             "iEnd": oSettings.fnDisplayEnd(),
    //             "iLength": oSettings._iDisplayLength,
    //             "iTotal": oSettings.fnRecordsTotal(),
    //             "iFilteredTotal": oSettings.fnRecordsDisplay(),
    //             "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
    //             "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    //         };
    //     };
    //     t = $("#mytable").DataTable({
    //         searching: false,
    //         lengthChange: false,
    //         pageLength: 50,
    //         initComplete: function() {
    //             var api = this.api();
    //             $('#mytable_filter input')
    //                 .off('.DT')
    //                 .on('keyup.DT', function(e) {
    //                     if (e.keyCode == 13) {
    //                         api.search(this.value).draw();
    //                     }
    //                 });
    //         },
    //         oLanguage: {
    //             sProcessing: "loading..."
    //         },
    //         oSearch: { "bSmart": false, "bRegex": true },
    //         processing: true,
    //         serverSide: true,
    //         ajax: {
    //             "url": "{{ route('administrator.bank-cv.table') }}",
    //             "type": "GET",
    //             "data": {
    //                 "end_date": $('input[name="max_salary"]').val(),
    //                 "start_date": $('input[name="min_salary"]').val(),
    //                 "name": $('input[name="name"]').val(),
    //             }
    //         },
    //         columns: [
    //             { "data": "id", "name":"id", "visible": false, "searchable": false },
    //             { "data": "id", "orderable": false, "searchable": false },
    //             { "data": "column_nik", name: 'nik' },
    //             { "data": "column_name", name: 'name' },
    //             { "data": "email" },
    //             { "data": "address" },
    //             { "data": "phone_number" },
    //             { "data": "born_year" },
    //             { "data": "salary" },
    //             { "data": "column_action", "orderable": false, "searchable": false },
    //         ],
    //         order: [[0, 'desc']],
    //         rowCallback: function(row, data, iDisplayIndex) {
    //             var info = this.fnPagingInfo();
    //             var page = info.iPage;
    //             var length = info.iLength;
    //             var index = page * length + (iDisplayIndex + 1);
    //             $('td:eq(0)', row).html(index);
    //         }
    //     });
    // };

    // function reload_table()
    // {
    //     $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
    // }
    
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

    // $("input[name='check_all']").click(function () {    
    //     $('input:checkbox').prop('checked', this.checked);  
    // });

    // $('#delete-karyawan').click(function(){
    //     var employees = [];
    //     $.each($("input[name='checked_id[]']:checked"), function(){            
    //         employees.push($(this).val());
    //     });

    //     if(employees.length < 1){
    //         bootbox.confirm({
    //             title : "<i class=\"fa fa-warning\"></i> EMPORE SYSTEM",
    //             message: "Belum ada data yang dipilih",
    //             closeButton: false,
    //             callback: function (result) {
    //                 if(result)
    //                 { 
                        
    //                 }
    //             }
    //         });
    //     }
    //     var url = "<?php echo route('ajax.get-karyawan-by-id') ?>";
    //     for(i=0; i<employees.length; i++){
    //         var id = employees[i];
    //         bootbox.confirm({
    //             title : "<i class=\"fa fa-warning\"></i> EMPORE SYSTEM",
    //             message: "Delete this data ?",
    //             closeButton: false,
    //             buttons: {
    //                 confirm: {
    //                     label: 'Yes',
    //                     className: 'btn btn-sm btn-success'
    //                 },
    //                 cancel: {
    //                     label: 'No',
    //                     className: 'btn btn-sm btn-danger'
    //                 }
    //             },
    //             callback: function (result) {
    //                 if(result)
    //                 { 
    //                     $.ajax({
    //                         type: 'POST',
    //                         url: '{{ route('ajax.delete-karyawan') }}',
    //                         data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
    //                         dataType: 'json',
    //                         success: function (msg) {
    //                             window.location = "<?php echo route('administrator.bank-cv.index') ?>"; 
    //                         }
    //                     });  
    //                 }
    //             }
    //         });
    //     }
    // });

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
            var column = data_table_no_search.column($(this).attr('data-column'));
    
            // Toggle the visibility
            column.visible(!column.visible());
        });
</script>
@endsection
@endsection