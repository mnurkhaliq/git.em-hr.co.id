@extends('layouts.administrator')

@section('title', 'Attendance Summary')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title" style="overflow: inherit;">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Attendance Summary</h4>
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <form method="POST" action="{{ route('attendance.list') }}" id="filter-form" autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="action" value="view">
                        <input type="hidden" name="reset" value="0">
                        <input type="hidden" name="eksport" value="0">

                        <div class="pull-right">
                            <div class="btn-group m-l-10 m-r-10 pull-right">
                                <a href="javascript:void(0)" aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle">Action
                                    <i class="fa fa-gear"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu">
                                    <li><a href="javascript:void(0)" onclick="reset_filter()"><i class="fa fa-refresh"></i> Reset Filter </a></li>
                                    <li><a href="javascript:void(0)" onclick="eksportAttendance()"><i class="fa fa-download"></i> Export </a></li>
                                </ul>
                            </div>
                            <button id="filter_view" class="btn btn-default btn-sm btn-outline"> <i class="fa fa-search-plus"></i></button>
                        </div>
                        <div class="row">
                            <div class="col-md-10 pull-right" style="padding: 0">
                                <div class="col-md-2 pull-right">
                                    <select name="branch" class="form-control form-control-line" id="branch">
                                        <option value="" selected>- Branch -</option>
                                        @foreach(cabang() as $item)
                                            <option {{ $item->id == \Session::get('branch') ? 'selected' : '' }} value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 pull-right">
                                    <select name="position" class="form-control form-control-line" id="position">
                                        <option value="" selected>- Position -</option>
                                        @foreach(getStructureName() as $item)
                                            <option {{ $item['id'] == \Session::get('position') ? 'selected' : '' }} value="{{$item['id']}}">{{$item['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 pull-right">
                                    <input type="number" name="min" min="0" max="31" class="form-control form-control-line" id="min" placeholder="Minimum Attendances" value="{{ \Session::get('min') }}">
                                </div>
                                <div class="col-md-2 pull-right">
                                    <input required type="text" name="end" class="form-control datepicker form-control-line" id="end" placeholder="End Date" value="{{ \Session::get('end') }}">
                                </div>
                                <div class="col-md-2 pull-right">
                                    <input required type="text" name="start" class="form-control datepicker form-control-line" id="start" placeholder="Start Date" value="{{ \Session::get('start') }}">
                                </div>
                                <div class="col-md-2 pull-right">
                                    <input type="text" name="name" id="nama_nik" class="form-control form-control-line autocomplete-karyawan" placeholder="Nik / Name" value="{{ \Session::get('name')}}">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 p-l-0 p-r-0">
                    <div class="white-box">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Branch</th>
                                <th>Total Attendances</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }} </td>
                                    <td>{{ $item->nik }} </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ isset($item->structure->position->name) ? $item->structure->position->name : '' }}{{ isset($item->structure->division->name) ? ' - '.$item->structure->division->name : '' }}{{ isset($item->structure->title->name) ? ' - '.$item->structure->title->name : '' }}</td>
                                    <td>{{ isset($item->cabang->name) ? $item->cabang->name : '' }}</td>
                                    <td>{{ $item->absensi_item_count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>

    <style>
        table.dataTable tbody th, table.dataTable tbody td {
            padding: 8px 5px;
        }
        .fa.pull-right {
            margin-left: 0.1em;
        }
        table.dataTable thead th, table.dataTable thead td {
            padding: 10px 20px;
        }
    </style>
@section('js')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTctq_RFrwKOd84ZbvJYvU3MEcrLmPNJ8" async defer></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });
        
        function reset_filter()
        {
            $("#filter-form input.form-control, #filter-form select").val("");
            $("input[name='reset']").val(1);
            $("#filter-form").submit();
        }

        function eksportAttendance(){
            $("input[name='eksport']").val(1);
            $("#filter-form").submit();

            $("input[name='eksport']").val(0);
        }
    </script>
    <script>
        $(".autocomplete-karyawan").autocomplete({
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
                $( "input[name='id']" ).val(ui.item.id);
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });
    </script>
@endsection
@endsection
