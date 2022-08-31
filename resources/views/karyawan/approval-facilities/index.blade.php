@extends('layouts.karyawan')

@section('title', 'Approval Facilities')

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
                <h4 class="page-title">Manage Approval Facilities</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Facilities</li>
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
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>ASSET NUMBER</th>
                                    <th>ASSET NAME</th>
                                    <th>STATUS</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data != null)
                                @forelse($data as $no => $item)
                                    @if($item == '')
                                        <?php continue;?>
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>    
                                        <td>{{ $item->user->nik }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->asset->asset_number }}</td>
                                        <td>{{ $item->asset->asset_name }}</td>
                                        <td>
                                            <a onclick="detail_approval_facilities({{ $item->id }})"> 
                                                @if($item->status_return == 0)
                                                <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                @else
                                                <label class="btn btn-success btn-xs">Approved</label>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('karyawan.approval.facilities.detail', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> Process </button></a>
                                            <a href="{{ route('karyawan.approval.facilities.history', $item->asset->id) }}"> <button class="btn btn-primary btn-xs m-r-5"> History </button></a>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td class="text-center" colspan="7">No data available in table</td>    
                                </tr>
                                @endforelse
                                @else 
                                <tr>
                                    <td class="text-center" colspan="7">No data available in table</td>    
                                </tr>
                                @endif
                                
                            </tbody>
                        </table>
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
