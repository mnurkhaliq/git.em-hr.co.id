@extends('layouts.administrator')

@section('title', 'History of Asset')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">History of Asset</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">History of Asset</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="3"  class="text-center" >Detail Asset</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th> Name </th>
                                        <th> : </th>
                                        <th> {{$data->asset_name}}</th>
                                    </tr>
                                    <tr>
                                        <th> Number </th>
                                        <th> : </th>
                                        <th> {{$data->asset_number}}</th>
                                    </tr>
                                    <tr>
                                        <th> Type </th>
                                        <th> : </th>
                                        <th> {{$data->asset_type->name}}</th>
                                    </tr>
                                    <tr>
                                        <th> Serial Number </th>
                                        <th> : </th>
                                        <th> {{$data->asset_sn}}</th>
                                    </tr>
                                    <tr>
                                        <th> Specification</th>
                                        <th> : </th>
                                        <th> {!!isset($data->spesifikasi) ? $data->spesifikasi : ''!!}</th>
                                    </tr>
                                    <tr>
                                        <th> Purchase Date / Rental Date </th>
                                        <th> : </th>
                                        <th> {{format_tanggal($data->purchase_date)}}</th>
                                    </tr>
                                    <tr>
                                        <th> Condition </th>
                                        <th> : </th>
                                        <th> {{$data->asset_condition}}</th>
                                    </tr>
                                    <tr>
                                        <th> Status</th>
                                        <th> : </th>
                                        <th> {{$data->assign_to}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                        <div class="col-md-8">
                        <div class="table-responsive">
                            <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="70" class="text-center">No</th>
                                        <th>FROM</th>
                                        <th>TO</th>
                                        <th>HANDOVER DATE</th>
                                        <th>ASSET CONDITION</th>
                                        <th>ASSIGN TO</th>
                                        <th>NOTE(FROM)</th>
                                        <th>NOTE(TO)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $no => $item)
                                        <tr>
                                            <td class="text-center">{{ $no+1 }}</td> 
                                            @if($no != 0)
                                                <td>{{isset($history[$no-1]->user->name) ? $history[$no-1]->user->name : ''}}</td>
                                            @else 
                                                @if($item->asset->pic_id != null )
                                                    @if($item->pic_id != null && $item->asset->pic_id != $item->pic_id)
                                                    <td>{{ isset($item->pic->name) ? $item->pic->name : '' }}</td>
                                                    @elseif($item->pic_id != null && $item->asset->pic_id == $item->pic_id)
                                                    <td>{{ isset($item->asset->pic->name) ? $item->asset->pic->name : '' }}</td>
                                                    @else
                                                    <td>{{ isset($item->asset->asset_type->pic_department) ? $item->asset->asset_type->pic_department : '' }}</td>
                                                    @endif
                                                @else
                                                <td>{{ isset($item->asset->asset_type->pic_department) ? $item->asset->asset_type->pic_department : '' }}</td>
                                                @endif
                                            @endif
                                            <td>{{ isset($item->user->name) ? $item->user->name : '' }}</td>
                                            <td>{{ $item->handover_date != "" ?  format_tanggal($item->handover_date) : '' }}</td>  
                                            <td>{{ $item->asset_condition }}</td>
                                            <td>{{ $item->assign_to }}</td>
                                            @if($no != 0)
                                                <td>{{isset($history[$no-1]->note_return) ? $history[$no-1]->note_return : '-'}}</td>
                                            @else 
                                                <td>{{ $item->asset->admin_note != null ? $item->asset->admin_note : '-' }}</td>
                                            @endif
                                            <td class="text-center">{{ $item->note_return != null ? $item->note_return : '-' }}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    @include('layouts.footer')
</div>
<style type="text/css">
    .asset-mobil {
        padding:10px;
        border:1px solid #eee;
        background: #efefef;
        margin-bottom: 20px;
    }
</style>
@section('footer-script')
<script type="text/javascript">
    
</script>
@endsection
@endsection
