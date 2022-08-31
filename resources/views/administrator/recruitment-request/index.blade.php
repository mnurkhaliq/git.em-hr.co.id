@extends('layouts.administrator')

@section('title', 'Recruitment Request')

@section('sidebar')

@endsection

@section('content')
    <link href="{{ asset('js/recruitment-request/general.css') }}" rel="stylesheet">
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title" style="overflow: inherit;">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Recruitment Request</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    {{--<a href="{{ route('Manager.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>--}}
                    <div class="col-md-12 pull-right" style="padding:0px;">
                        <div class="col-md-2 pull-right" style="padding:0px;">
                            <div style="padding-left:0; float: right;">
                                <div class="btn-group pull-right">
                                    <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                        <li><a class="toggle-vis" data-column="1" style="color:blue;">Request Number</a></li> 
                                        <li><a class="toggle-vis" data-column="2" style="color:blue;">Position</a></li> 
                                        <li><a class="toggle-vis" data-column="3" style="color:blue;">Branch</a></li>
                                        <li><a class="toggle-vis" data-column="4" style="color:blue;">Headcount</a></li>
                                        <li><a class="toggle-vis" data-column="5" style="color:blue;">Requestor</a></li>
                                        <li><a class="toggle-vis" data-column="6" style="color:blue;">Recruiter</a></li> 
                                        <li><a class="toggle-vis" data-column="7" style="color:blue;">Request Date</a></li>
                                        <li><a class="toggle-vis" data-column="8" style="color:blue;">Target</a></li>
                                        <li><a class="toggle-vis" data-column="9" style="color:blue;">Status</a></li>
                                        <li><a class="toggle-vis" data-column="10" style="color:blue;">Action</a></li> 
                                    </ul>
                                </div>
                                <div class="btn-group m-l-4 m-r-4 pull-right" style="padding-left:3px; padding-right:3px;">
                                    <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action 
                                        <i class="fa fa-gear"></i>
                                    </a>
                                    <ul role="menu" class="dropdown-menu">
                                        <li><a href="javascript:void(0)" onclick="reset_filter()"> <i class="fa fa-refresh"></i> Reset Filter</a></li>
                                    </ul>
                                </div>
                                <button type="button" id="filter_view" class="btn btn-default btn-sm pull-right btn-outline"><i class="fa fa-search-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="division_id">
                                        <option value=""> - Choose Division - </option>
                                        @foreach($division as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->division_id || Session::get('rr-division_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="position_id">
                                        <option value=""> - Choose Position - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}" {{ $item->id== request()->position_id || Session::get('rr-position_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group  m-b-0">
                                <select class="form-control form-control-line" name="status">
                                    <option value="0">- Recruitment Status -</option>
                                    <option value="1" {{ (request() and request()->status == '1') || Session::get('rr-status') == '1' ? 'selected' : '' }}>Waiting for HR</option>
                                    <option value="2" {{ (request() and request()->status == '2') || Session::get('rr-status') == '2' ? 'selected' : '' }}>Waiting for User</option>
                                    <option value="3" {{ (request() and request()->status == '3') || Session::get('rr-status') == '3' ? 'selected' : '' }}>Approved</option>
                                    <option value="4" {{ (request() and request()->status == '4') || Session::get('rr-status') == '4' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <div class="form-group  m-b-0">
                                <input type="text" name="name" class="form-control form-control-line" value="{{ (request() and request()->name) || Session::get('rr-name') ? Session::get('rr-name') : '' }}" placeholder="Name / NIK Employee">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <div class="col-md-12 p-l-0 p-r-0">
                    <div class="white-box">
                        
                        <div class="table-responsive">
                            <!-- <div class="form-group col-md-2 pull-right">
                                {{--<a class="btn btn-md btn-info pull-right" href="{{route('administrator.recruitment-request.create')}}">Add Request</a>--}}
                            </div> -->
                            <table id="mytable" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th width="2%" class="text-center">NO</th>
                                    <th width="10%">REQUEST NUMBER</th>
                                    <th width="10%">POSITION</th>
                                    <th width="10%">BRANCH</th>
                                    <th width="5%">HEADCOUNT</th>
                                    <th width="10%">REQUESTOR</th>
                                    <th width="10%">RECRUITER</th>
                                    <th width="10%">REQUEST DATE</th>
                                    <th width="10%">TARGET</th>
                                    <th width="5%">STATUS</th>
                                    <th  width="5%">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- ============================================================== -->
        </div>
        <!-- BEGIN MODAL -->
        <div  class="modal fade none-border" id="modal-detail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Recruitment Request Approval</strong></h4>
                    </div>
                    <form id="form">
                        <div class="modal-body" id="modal-add-body">
                            <div class="form-group col-xs-12">
                                <table class="table-history" width="100%">
                                    <tr>
                                        <td width="30%">Request Number</td>
                                        <td width="30"> : </td>
                                        <td id="approval_request_number"></td>
                                    </tr>
                                    <tr>
                                        <td>Position</td>
                                        <td> : </td>
                                        <td id="approval_position"></td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td> : </td>
                                        <td id="approval_branch"></td>
                                    </tr>
                                    <tr>
                                        <td>Date Request</td>
                                        <td> : </td>
                                        <td id="approval_date_request"></td>
                                    </tr>
                                </table>

                            </div>
                            <hr/>
                            <div class="form-group col-xs-12" id="approval">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
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

    <script>var url = "{{route('ajax.get-recruitment-request-approval')}}"</script>
    <script src="{{ asset('js/recruitment-request/general.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        var t;
        $("#filter_view").click(function(){
            loadData();
        });

        function reset_filter()
        {
            $("select[name=status]").val(0)
            $("select[name=division_id]").val('')
            $("select[name=position_id]").val('')
            $("input[name=name]").val('')
            loadData();
        }

        loadData();
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
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {
                    "url": "{{ route('ajax.table.recruitment_request.admin') }}",
                    "type": "GET",
                    'data': {
                        'status': $("select[name=status]").val(),
                        'division_id': $("select[name=division_id]").val(),
                        'position_id': $("select[name=position_id]").val(),
                        'name': $("input[name=name]").val(),
                    }
                },
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "request_number"},
                    { "data": "job_position"},
                    { "data": "branch","name":"c.name"},
                    { "data": "headcount"},
                    { "data": "requestor","name":"ro.name"},
                    { "data": "recruiter","name":"re.name"},
                    { "data": "request_date","searchable":false},
                    { "data": null,"searchable":false,
                        "render": function(data, type, row){
                            if(data.target == '1')
                                return "INTERNAL "+(data.target_post == 1 ? "(POSTED)" : "(UNPOSTED)");
                            else if(data.target == '2')
                                return "EXTERNAL "+(data.target_post == 1 ? "(POSTED)" : "(UNPOSTED)");
                            else
                                return "EXTERNAL "+(data.target_post && data.target_post.split(",")[0] == 1 ? "(POSTED)" : "(UNPOSTED)")+"<br>INTERNAL "+(data.target_post && data.target_post.split(",")[1] == 1 ? "(POSTED)" : "(UNPOSTED)");
                        }
                    },
                    {
                        "data": "status",
                        "searchable":false,
                        "orderable":false,
                        "render": function(data, type, row){
                            // if(row['approval_hr'] == null && row['approval_user'] == null){
                            //     return "Waiting";
                            // }

                            if(row['approval_hr'] == '0' || row['approval_user'] == '0'){
                                label = 'btn btn-danger btn-xs';
                                st = "Rejected";
                            }
                            else if(row['approval_hr'] == '1' && row['approval_user'] == '1'){
                                label = 'btn btn-success btn-xs';
                                st = "Approved";
                            }
                            else if(row['approval_hr'] == null){
                                label = 'btn btn-warning btn-xs';
                                st = 'Waiting for HR';
                            }
                            else{
                                label = 'btn btn-warning btn-xs';
                                st = 'Waiting for User';
                            }
                            var id = row['id'];
                            return "<label  class='"+label+ "' onclick='detail("+id+")'> " + st + "</label ><br>";
                        }
                    },
                    { "data": 'action', "orderable": false, "searchable": false,
                        "render": function(data, type, row){
                            // if(row['approval_hr'] == null && row['approval_user'] == null){
                            //     return "Waiting";
                            // }

                            if(row['approval_hr'] == null){
                                return '<a href="recruitment-request/'+row['id']+'/edit"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-arrow-right"></i> Proses</button></a>';
                            }
                            else{
                                var btn = '<a href="recruitment-request/'+row['id']+'/edit"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> Detail</button></a>';
                                return btn;
                            }
                        }
                    }
                ],
                order: [[1, 'desc']],
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
