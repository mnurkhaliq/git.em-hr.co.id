@extends('layouts.administrator')

@section('title', 'KPI Surveys')

@section('sidebar')

@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title" style="overflow: inherit;">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">KPI Surveys</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <div style="padding-left:0; float: right;">
                        <div class="btn-group pull-right">
                            <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                <i class="fa fa-eye"></i>
                            </a>
                            <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                <li><a class="toggle-vis" data-column="1" style="color:blue;">Period</a></li>                                   
                                <li><a class="toggle-vis" data-column="2" style="color:blue;">NIK</a></li>
                                <li><a class="toggle-vis" data-column="3" style="color:blue;">Name</a></li>
                                <li><a class="toggle-vis" data-column="4" style="color:blue;">Position</a></li>
                                <li><a class="toggle-vis" data-column="5" style="color:blue;">Supervisor</a></li>
                                <li><a class="toggle-vis" data-column="6" style="color:blue;">Status</a></li>
                                <li><a class="toggle-vis" data-column="7" style="color:blue;">Final Score</a></li>                               
                                <li><a class="toggle-vis" data-column="8" style="color:blue;">Action</a></li>
                            </ul>
                        </div>
                        <div class="btn-group m-l-10 m-r-10 pull-right">
                            <a href="javascript:void(0)" aria-expanded="true" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action 
                                <i class="fa fa-gear"></i>
                            </a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="javascript:void(0)" onclick="addEmployee()"><i class="fa fa-plus"></i> Add Employee</a></li>
                                <li><a href="javascript:void(0)" onclick="download_all()"><i class="fa fa-download"></i> Download Excel</a></li>
                                <li><a href="javascript:void(0)" onclick="download_detail()" id="download_detail" style="display: none;"><i class="fa fa-download"></i> Download Detail Excel</a></li>
                                <li><a href="javascript:void(0)" onclick="reset_filter()"> <i class="fa fa-refresh"></i> Reset Filter</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <div class="form-group  m-b-0">
                            {{--<label>Select Employee</label>--}}
                            <div>
                                <input type="text" name="employee" class="form-control employee" id="employee" value="{{\Session::get('ks_nik')}} {{\Session::get('ks_nik') ? '-' : ''}} {{\Session::get('ks_name')}}" placeholder="Select Employee">
                                <input type="hidden" id="user_id" name="user_id" value="{{\Session::get('ks_user_id')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            {{--<label>Select Status</label>--}}
                            <select name="status" CLASS="form-control" id="status">
                                <option value="-1">- Select Status -</option>
                                <option value="0" {{ \Session::get('ks_status') == 0 ? 'selected' : '' }}>Draft</option>
                                <option value="1" {{ \Session::get('ks_status') == 1 ? 'selected' : '' }}>Self Reviewed</option>
                                <option value="2" {{ \Session::get('ks_status') == 2 ? 'selected' : '' }}>Final Reviewed</option>
                                <option value="3" {{ \Session::get('ks_status') == 3 ? 'selected' : '' }}>Acknowledged</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="form-group  m-b-0">
                            {{--<label>Select Position</label>--}}
                            <select name="position" CLASS="form-control" id="position">
                                <option value="0">- Pilih Posisi - </option>
                                <?php $positions = getAllPositions()?>
                                @foreach($positions as $position)
                                    <option {{ $position->id == \Session::get('ks_position') ? 'selected' : '' }} value="{{$position->id}}">{{$position->position}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <div class="form-group  m-b-0">
                            {{--<label>Select KPI Period</label>--}}
                            <select name="period" CLASS="form-control" id="period">
                                <?php $periods = get_kpi_periods()?>
                                <option value="0">- Select Period -</option>
                                @foreach($periods as $period)
                                    @if($period->status==1)
                                        <option {{ $period->id == \Session::get('ks_period') ? 'selected' : '' }} value="{{$period->id}}">{{ date("d F Y", strtotime($period->start_date))." - ".date("d F Y", strtotime($period->end_date)) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
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
                                    <th width="15%">PERIOD</th>
                                    <th width="10%">NIK</th>
                                    <th width="20%">NAME</th>
                                    <th width="20%">POSITION</th>
                                    <th width="20%">SUPERVISOR</th>
                                    <th width="10%">STATUS</th>
                                    <th width="10%">FINAL SCORE</th>
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
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <script>
        var id_period = $('#period').val(), empty="", id_position=$('#position').val(),id_status=$('#status').val(), id_user = $('#user_id').val();
        var t;
        function reset_filter()
        {
            $('#employee').val('')
            $("#period").val(0)
            $("#position").val(0)
            $("#status").val(-1)
            $("#user_id").val('0')
            id_period = 0;
            id_position = 0;
            id_status = -1;
            id_user = 0;
            loadData();
        }

        $('#period').on('change',function () {
            id_period = $(this).val();
            loadData();
        });
        $('#position').on('change',function () {
            id_position = $(this).val();
            loadData();
        });
        $('#status').on('change',function () {
            id_status = $(this).val();
            loadData();
        });
        $('#employee').on('input change',function () {
            if($.trim($(this).val())==""){
                $('#user_id').val("0").trigger('change');
            }

        });
        $('#user_id').on('change',function () {
            id_user = $(this).val();
            loadData();
        });
        loadData();
        function loadData(){
            // if(id_period==0){
            //     empty = "Select period first!";
            // }
            // else{
                empty = "No data available in table";
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
                ajax: {"url": "{{ route('ajax.table.kpi_survey.admin') }}", "type": "GET","data":{"id_period":id_period,"id_position":id_position,"id_status":id_status,"id_user":id_user}},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "period", "searchable": false},
                    { "data": "nik","name":"u.nik"},
                    { "data": "name","name":"u.name"},
                    { "data": "position", "searchable": false},
                    { "data": "supervisor","name":"s.name"},
                    { "data": "status"},
                    { "data": "final_score"},
                    { "data": 'action', "orderable": false, "searchable": false}
                ],
                columnDefs:[

                    {
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            if (data == 0) {
                                label = 'badge badge-warning';
                                st = 'DRAFT';
                            } else if (data == 1)  {
                                label = 'badge badge-info';
                                st = 'SELF REVIEWED';
                            }
                            else if (data == 2)  {
                                label = 'badge badge-info';
                                st = 'FINAL REVIEWED';
                            }
                            else if (data == 3)  {
                                label = 'badge badge-success';
                                st = 'ACKNOWLEDGED';
                            }
                            data = "<span  class='"+label+ "'> " + st + "</span ><br>";
                            return data
                        },
                        "targets": 6
                    },
                    {
                        "render": function (data, type, row) {
                            // here you can convert data from base64 to hex and return it
                            if (data == null) {
                                data = '<i>Not yet</i>';
                            }
                            return data
                        },
                        "targets": 7
                    }
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
        function remove(id) {
            swal({
                title: 'Are you sure?',
                text: "Once deleted, you will not be able to recover this user's survey!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "kpi-survey/"+id,
                        type: "DELETE",
                        data:{'_token':"{{csrf_token()}}"},
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status == 'success') {
                                swal("Success!", data.message, "success");
                                reload_table();
                            } else {
                                swal("Failed!", data.message, "error");
                            }
                            console.log();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                } else {

                }
            });
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
           if(user_id == '' || $.trim($('#employee2').val()) == ''){
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
        function download_all() {
            window.location.href = "{{route('kpi-survey.download')}}?id_period="+id_period+"&id_position="+id_position+"&id_status="+id_status+"&id_user="+id_user;
        }
        function download_detail() {
            window.location.href = "{{route('kpi-survey.download-detail')}}?id_period="+id_period+"&id_position="+id_position+"&id_status="+id_status+"&id_user="+id_user;
        }
        $("#period, #position").change(function() { 
            if ($('#period').val() != 0 && $('#position').val() != 0) {
                $('#download_detail').show();
            } else {
                $('#download_detail').hide();
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
