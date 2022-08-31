@extends('layouts.superadmin')

@section('title', 'Administrator')

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
                <h4 class="page-title">Manage Administrator</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="{{ route('superadmin.admin.create') }}" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD ADMINISTRATOR</a>
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Administrator</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>NIK/USERNAME</th>
                                    <th>NAME</th>
                                    <th>GENDER</th>
                                    <th>TELEPHONE</th>
                                    <th>EMAIL</th>
                                    <th>TIPE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td title="{{$item->name}}">{{ str_limit($item->name, $limit = 30, $end = '...') }}</td>
                                        <td>{{ $item->jenis_kelamin }}</td>
                                        <td>{{ $item->mobile_1 }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{$item->access_id == 1?"Admin":"Karyawan"}}</td>
                                        <td>
                                            <a onclick="changeStatus({{$item->id.",".$item->access_id}})">
                                            @if($item->access_id == 2)
                                                <button class="btn btn-success btn-xs m-r-5">
                                                    <i class='fa fa-arrow-up'></i> Activate
                                                </button>
                                            @else
                                                <button class="btn btn-warning btn-xs m-r-5">
                                                    <i class='fa fa-arrow-down'></i> Inactivate
                                                </button>
                                            </a>
                                            <a href="{{ route('superadmin.admin.edit', $item->id) }}"> 
                                                <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button>
                                            </a>
                                            @endif

                                            <a onclick="changeEnableStatus({{$item->id.",".($item->inactive_date && \Carbon\Carbon::now() >= $item->inactive_date)}})">
                                                    @if($item->inactive_date && \Carbon\Carbon::now() >= $item->inactive_date)
                                                        <!-- <button class="btn btn-default btn-xs m-r-5">
                                                            <i class='fa fa-check-circle'></i> Enable
                                                        </button> -->
                                                    @else
                                                        <button class="btn btn-danger btn-xs m-r-5">
                                                            <i class='fa fa-ban'></i> Disable
                                                        </button>
                                                    @endif
                                            </a>

                                            {{-- <form action="{{ route('superadmin.admin.destroy', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post" style="margin-left: 10px;">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> delete</button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                               
                            </tbody>
                        </table>
                        {{-- <div class="col-m-6 pull-left text-left">Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries</div>
                        <div class="col-md-6 pull-right text-right">{{ $data->appends($_GET)->render() }}</div><div class="clearfix"></div> --}}
                    </div>
                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->

    @include('layouts.footer')
</div>
@endsection
@section("js")
<script src="https://cdn.jsdelivr.net/npm/jquery.redirect@1.1.4/jquery.redirect.min.js"></script>
<script>
        function changeStatus(item_id, access_id) {
            access_id === 1 ? access_id = 2 : access_id = 1;
            var msg = "Are you sure want to change user's status to become ";
            if(access_id===1){
                msg+="Admin?";
            }else{
                msg+="just Employee?";
            }
            if(confirm(msg)) {
                jQuery(function($) {
                    $.redirect("{{ route('superadmin.admin.changeStatus')}}", {
                        'id': item_id,
                        'access_id': access_id,
                        '_token' : '{{csrf_token()}}'
                    });
                });
            }
        }

        function changeEnableStatus(item_id, is_inactive) {
            var msg = "Are you sure want to ";
            if(is_inactive){
                msg+="enable this user?";
            }else{
                msg+="disable this user? Once disabled it can't be undone.";
            }
            if(confirm(msg)) {
                jQuery(function($) {
                    $.redirect("{{ route('superadmin.admin.changeEnableStatus')}}", {
                        'id': item_id,
                        'is_inactive': is_inactive,
                        '_token' : '{{csrf_token()}}'
                    });
                });
            }
        }

</script>
@endsection