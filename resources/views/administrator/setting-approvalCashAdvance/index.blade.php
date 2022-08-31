@extends('layouts.administrator')

@section('title', 'Setting Approval Cash Advance')

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
                <h4 class="page-title">SETTING APPROVAL CASH ADVANCE</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">SETTING APPROVAL CASH ADVANCE</li>
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
                                    <th width="30" class="text-center">NO</th>
                                    <th>POSITION / DIVISION</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                               
                                    <tr> 
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->structure ? $item->structure->position->name.($item->structure->division ? ' - '.$item->structure->division->name : '').($item->structure->title ? ' - '.$item->structure->title->name : '') : '' }}</td>
                                        <td>@if(isset($item->itemCashAdvance))
                                               <label class="btn btn-success btn-xs  btn-circle"  title="Approval Defined"><i class="fa fa-check"></i> </label>
                                            @else
                                                <label class="btn btn-warning btn-xs btn-circle" title="Approval Undefined"><i class="fa fa-close"></i></label>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('administrator.setting-approvalCashAdvance.indexItem', $item->id) }}"> <button class="btn btn-info btn-xs"><i class="fa fa-arrow-right"></i>Detail</button></a>
                                            @if(isset($item->itemCashAdvance))
                                                <a onclick="detail_setting_approval_cashAdvance_custom({{ $item->id }})">
                                                    <button class="btn btn-success btn-xs"><i class="fa fa-arrow-right"></i>View Approval</button>
                                                </a>
                                            @endif
                                        </td>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>                        
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
   @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection

