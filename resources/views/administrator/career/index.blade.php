@extends('layouts.administrator')

@section('title', 'Career')

@section('sidebar')

@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title" style="overflow: inherit;">
                <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Career</h4> </div>
                <div class="col-lg-10 col-sm-8 col-md-8 col-xs-12">
                {{--<a href="{{ route('administrator.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>--}}
                    <div class="col-md-2 pull-right">    
                        <div style="padding-left:0; float: right;">
                            <div class="btn-group pull-right">
                                <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                    <li><a class="toggle-vis" data-column="1" style="color:blue;">NIK</a></li>                                   
                                    <li><a class="toggle-vis" data-column="2" style="color:blue;">Name</a></li>
                                    <li><a class="toggle-vis" data-column="3" style="color:blue;">Branch</a></li>
                                    <li><a class="toggle-vis" data-column="4" style="color:blue;">Position</a></li>
                                    <li><a class="toggle-vis" data-column="5" style="color:blue;">Status</a></li>
                                    <li><a class="toggle-vis" data-column="6" style="color:blue;">Action</a></li>
                                </ul>
                            </div>
                            <div class="btn-group m-l-10 m-r-10 pull-right">
                                <a href="javascript:void(0)" aria-expanded="true" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action 
                                    <i class="fa fa-gear"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="javascript:void(0)" onclick="importData()"><i class="fa fa-upload"></i> Import Excel</a></li>
                                    <li><a href="javascript:void(0)" onclick="submit_filter_download()"><i class="fa fa-download"></i> Export Career</a></li>
                                    <li><a href="javascript:void(0)" onclick="reset_filter()"> <i class="fa fa-refresh"></i> Reset Filter</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 pull-right" style="padding: 0px">
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="division" id="division">
                                    <option value="0">- Select Division - </option>
                                    <?php $divisions = get_divisions()?>
                                    @foreach($divisions as $division)
                                        <option {{ $division->id == \Session::get('c_division_id') ? 'selected' : '' }}  value="{{$division->id}}">{{$division->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="position" id="position">
                                    <option value="0">- Select Position - </option>
                                    <?php $positions = get_positions()?>
                                    @foreach($positions as $position)
                                        <option {{ $position->id == \Session::get('c_position_id') ? 'selected' : '' }}  value="{{$position->id}}">{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="branch" id="branch">
                                    <?php $branches = get_branches()?>
                                    <option value="0">- Select Branch -</option>
                                    @foreach($branches as $branch)
                                        <option {{ $branch->id == \Session::get('c_branch_id') ? 'selected' : '' }} value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="employee_resign" id="employee_resign">
                                    <option value="">- Active/Resign - </option>
                                    <option {{\Session::get('c_employee_resign') == 'Active' ? 'selected' : '' }} >Active</option>
                                    <option {{\Session::get('c_employee_resign') == 'Resign' ? 'selected' : '' }} >Resign</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="emp_status" id="emp_status">
                                    <option value="">- Select Status - </option>
                                    <option value="Permanent" {{\Session::get('c_status') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option value="Contract" {{\Session::get('c_status') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="Internship" {{\Session::get('c_status') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    <option value="Outsource" {{\Session::get('c_status') == 'Outsource' ? 'selected' : '' }}>Outsource</option>
                                    <option value="Freelance" {{\Session::get('c_status') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="Consultant" {{\Session::get('c_status') == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <input type="text" class="form-control form-control-line" value="{{\Session::get('c_name')}}" name="emp_name" id="emp_name" placeholder="Name/NIK Employee">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">

                        <div class="table-responsive">
                            <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="5%" class="text-center">NO</th>
                                    <th width="15%">NIK</th>
                                    <th width="10%">NAME</th>
                                    <th width="20%">BRANCH</th>
                                    <th width="20%">POSITION</th>
                                    <th width="20%">STATUS</th>
                                    <th  width="10%">ACTION</th>
                                </tr>
                                </thead>
                                <tbody id="data_item">

                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- ============================================================== -->
        </div>
    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Add Employee</strong></h4>
                </div>
                <form id="form">
                <div class="modal-body" id="modal-add-body">
                        <div class="form-group col-md-12">
                            <label>Employee</label>
                            <div>
                                <input type="text" name="employee" class="form-control employee" id="employee2" placeholder="Select Employee">
                                <input type="hidden" id="user_id2" name="user_id">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Select Position</label>
                            <div>
                                <select name="position" CLASS="form-control" id="position2">
                                    <option value="0">- Pilih Posisi - </option>
                                    <?php $positions = getAllPositions()?>
                                    @foreach($positions as $position)
                                        <option value="{{$position->id}}">{{$position->position}}</option>
                                    @endforeach
                                </select>
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
    <div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Import Data</h4> </div>
                <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.career.import') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">File (xls)</label>
                            <div class="col-md-9">
                                <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="file" class="form-control" />
                            </div>
                        </div>
                        <a href="{{ asset('storage/sample/Sample Import Career.xlsx') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
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
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <script>
        var branch_id = $('#branch').val(), empty="", position_id=$('#position').val(), division_id=$('#division').val(), status=$('#emp_status').val(), employee_resign=$('#employee_resign').val(), name=$('#emp_name').val();
        var t;
        function reset_filter()
        {
            $('#branch').val(0)
            $("#position").val(0)
            $("#division").val(0)
            $("#emp_status").val('')
            $("#employee_resign").val("")
            $("#emp_name").val("")
            
            branch_id = 0;
            position_id = 0;
            division_id = 0;
            status = '';
            employee_resign = '';
            name = '';
            loadData();
        }

        $('#branch').on('change',function () {
            branch_id = $(this).val();
            loadData();
        });
        $('#position').on('change',function () {
            position_id = $(this).val();
            loadData();
        });
        $('#division').on('change',function () {
            division_id = $(this).val();
            loadData();
        });
        $('#emp_status').on('change',function () {
            status = $(this).val();
            loadData();
        });
        $('#employee_resign').on('change',function () {
            employee_resign = $(this).val();
            loadData();
        });
        $('#emp_name').on('change',function () {
            name = $(this).val();
            loadData();
        });
        console.log(status)
        // $('#employee').on('input change',function () {
        //     if($.trim($(this).val())==""){
        //         $('#user_id').val("0").trigger('change');
        //     }

        // });
        // $('#user_id').on('change',function () {
        //     id_user = $(this).val();
        //     loadData();
        // });
        loadData();
        function loadData(){
            // if(id_period==0){
            //     empty = "Select period first!";
            // }
            // else{
                empty = "No data available in this table";
            // }
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
                    sProcessing: "loading...",
                    sEmptyTable: empty
                },
                oSearch: { "bSmart": false, "bRegex": true },
                processing: true,
                serverSide: true,
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {"url": "{{ route('ajax.table.career.admin') }}", "type": "GET","data":{"branch_id":branch_id,"position_id":position_id,"division_id":division_id,"status":status,"employee_resign":employee_resign,"name":name}},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "nik","name":"users.nik"},
                    { "data": "name","name":"users.name"},
                    { "data": "branch", "name":"c.name"},
                    { "data": "position"},
                    { "data": "organisasi_status","name":"users.organisasi_status"},
                    { "data": 'action', "orderable": false, "searchable": false}
                ],
                columnDefs:[

                    
                    ],
                order: [[1, 'asc']],
                rowCallback: function(row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                }
            });
        };
        function addEmployee() {
            if(id_period=='0'){
                swal("Failed!", "Select period first!", "error");
            }else{
                $('#modal-add').modal('show');
            }
        }

        function reload_table()
        {
            $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
        }

        $("#employee" ).autocomplete({
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
                $("#user_id").val(ui.item.id).trigger('change');;
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });
        $("#employee2" ).autocomplete({
            minLength:0,
            limit: 25,
            appendTo : '#modal-add-body',
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
                $("#user_id2").val(ui.item.id);
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });
        $('#form').on('submit',function () {
           var user_id = $("#user_id2").val();
            var position = $("#position2").val();
           if(user_id == '' || $.trim($('#employee').val()) == ''){
               alert('Employee should not be empty');
           }
           else if(position==0){
               alert('Position should not be empty');
           }
           else{
               var form = $('#form')[0]; // You need to use standart javascript object here
               var formData = new FormData(form);
               formData.append('user_id',user_id);
               formData.append('kpi_period_id',id_period);
               formData.append('structure_organization_custom_id',position);
               formData.append('_token',"{{csrf_token()}}");
               $.ajax({
                   url: "{{route('kpi-survey.add-employee')}}",
                   type: "POST",
                   data:formData,
                   dataType: "JSON",
                   contentType: false,
                   processData: false,
                   success: function (data) {
                       if (data.status == 'success') {
                           swal("Success!", data.message, "success");
                           $('#form')[0].reset();
                           $('#modal-add').modal('hide');
                           reload_table();
                       } else {
                           swal("Failed!", data.message, "error");
                       }
                       console.log(data);
                   },
                   error: function (jqXHR, textStatus, errorThrown) {
                       console.log(jqXHR);
                       console.log(textStatus);
                       console.log(errorThrown);
                   }
               });
           }
           return false;
        });
        function submit_filter_download() {
            window.location.href = "{{route('career.download')}}?branch_id="+branch_id+"&position_id="+position_id+"&division_id="+division_id+"&status="+status+"&employee_resign="+employee_resign+"&name="+name;
        }
        function importData(){
            $("#modal_import").modal("show");
            $('.div-proses-upload').hide();
            $("#form-upload").show();
        }

        $("#btn_import").click(function(){
            $("#form-upload").submit();
            $("#form-upload").hide();
            $('.div-proses-upload').show();
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
