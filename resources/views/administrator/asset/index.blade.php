@extends('layouts.administrator')

@section('title', 'Facilities')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-12">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Manage List of Asset</h4> 
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <a href="{{ route('administrator.asset.create') }}" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light m-b-0"> <i class="fa fa-plus"></i> ADD LIST OF ASSET</a>
                    <a href="{{ route('administrator.asset-tracking.index') }}" class="btn btn-info btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light m-b-0"> <i class="fa fa-history"></i> ASSET TRACKING</a>
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">List of Asset</li>
                    </ol>
                </div>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <form method="GET" id="filter-form">
                        <div class="col-md-2">
                            <input type="text" id="asset_number" class="form-control form-control-line " placeholder="Asset Number" value="{{($asset_number) ? $asset_number : ''}}" name="asset_number">
                        </div>
                        <div class="col-md-1" style="padding-left: 0;">
                            <select name="asset_type_id" class="form-control">
                                <option value="">Type</option>
                                @foreach(asset_type() as $i)
                                <option value="{{ $i->id }}" {{$asset_type_id == $i->id?"selected":""}}>{{ $i->name }}</option>
                                @endforeach
                            </select> 
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="serial_number" class="form-control form-control-line" placeholder="Serial Number" value="{{($serial_number) ? $serial_number : ''}}" name="serial_number">
                        </div>
                        <div class="col-md-2">
                            <select name="asset_condition" class="form-control">
                                <option value="">-Asset Condition-</option>
                                <option value="Good" {{$asset_condition == "Good"?"selected":""}} >Good</option>
                                <option value="Malfunction" {{$asset_condition == "Malfunction"?"selected":""}}>Malfunction</option>
                                <option value="Lost" {{$asset_condition == "Lost"?"selected":""}}>Lost</option>
                            </select>  
                        </div>
                        <div class="col-md-2" style="padding-left: 0;">
                            <select name="assign_to" class="form-control">
                                <option value="">-Assign To-</option>
                                <option value="Assign To Employee" {{$assign_to == "Assign To Employee"?"selected":""}} >Employee</option>
                                <option value="Office Facility" {{$assign_to == "Office Facility"?"selected":""}}>Office Facility</option>
                                <option value="Office Inventory/idle" {{$assign_to == "Office Inventory/idle"?"selected":""}}>Office Inventory/idle</option>
                            </select>  
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="name" class="form-control form-control-line autocomplete-karyawan" placeholder="Nik / Name" value="{{ isset($user)&&$user?$user->nik." - ".$user->name:''}}">
                            <input type="hidden" name="user_id">
                        </div>
                        <div class="col-md-1" style="padding:0;">
                            <button type="submit" class="btn btn-sm btn-info m-r-1" title="filter"><i class="fa fa-search-plus"></i></button>
                            <a href="javascript:void(0)" class="btn btn-sm btn-info m-r-1" onclick="reset_filter()" title="reset filter"> <i class="fa fa-refresh"></i></a>
                            <div class="btn-group pull-right">
                                <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                    <li><a class="toggle-vis" data-column="1" style="color:blue;">Asset Number</a></li> 
                                    <li><a class="toggle-vis" data-column="2" style="color:blue;">Asset Name</a></li> 
                                    <li><a class="toggle-vis" data-column="3" style="color:blue;">Asset Type</a></li>
                                    <li><a class="toggle-vis" data-column="4" style="color:blue;">PIC</a></li>
                                    <li><a class="toggle-vis" data-column="5" style="color:blue;">Serial/Plat Number</a></li>
                                    <li><a class="toggle-vis" data-column="6" style="color:blue;">Purchase/Rental Date</a></li> 
                                    <li><a class="toggle-vis" data-column="7" style="color:blue;">Asset Condition</a></li>
                                    <li><a class="toggle-vis" data-column="8" style="color:blue;">Status Asset</a></li>
                                    <li><a class="toggle-vis" data-column="9" style="color:blue;">From</a></li>
                                    <li><a class="toggle-vis" data-column="10" style="color:blue;">To</a></li> 
                                    <li><a class="toggle-vis" data-column="12" style="color:blue;">Handover Date</a></li> 
                                    <li><a class="toggle-vis" data-column="13" style="color:blue;">Status</a></li> 
                                    <li><a class="toggle-vis" data-column="14" style="color:blue;">Action</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                    </form>
                    <hr style="margin-top:0;margin-bottom:6px;" />
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">No</th>
                                    <th>ASSET NUMBER</th>
                                    <th>ASSET NAME</th>
                                    <th>ASSET TYPE</th>
                                    <th>PIC</th>
                                    <th>SERIAL/PLAT NUMBER</th>
                                    <th>PURCHASE/RENTAL DATE</th>
                                    <th>ASSET CONDITION</th>
                                    <th>STATUS ASSET</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th></th>
                                    <th>HANDOVER DATE</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ $item->asset_number }}</td>
                                        <td>{{ $item->asset_name }}</td>
                                        <td>{{ isset($item->asset_type->name) ? $item->asset_type->name : ''  }}</td>
                                        <td>@if($item->pic_id != null)
                                            {{ isset($item->pic->name) ? str_limit($item->pic->name, $limit = 20, $end = '...') : '' }}
                                            @else
                                            {{ isset($item->asset_type->pic_department) ? $item->asset_type->pic_department : '' }}
                                            @endif
                                        </td>
                                        <td>{{ $item->asset_sn }}</td>
                                        <td>{{ format_tanggal($item->purchase_date) }}</td>
                                        <td>{{ $item->asset_condition }}</td>
                                        <td>{{ $item->assign_to }}</td>
                                        @if(count($data[$no]['history']) > 1)
                                        <td>{{ $data[$no]['history'][1]['user']['name'] }}
                                        </td>
                                        <td>{{ $data[$no]['history'][0]['user']['name'] }}
                                        </td>
                                        @else 
                                        <td>
                                            @if($item->pic_id != null)
                                            {{ isset($item->pic->name) ?  str_limit($item->pic->name, $limit = 20, $end = '...')  : '' }}
                                            @else
                                            {{ isset($item->asset_type->pic_department) ? $item->asset_type->pic_department : '' }}
                                            @endif
                                        </td>
                                        <td>{{ isset($item->user->name) ? str_limit($item->user->name, $limit = 20, $end = '...')  : '' }}
                                        </td>
                                        @endif
                                        <td>
                                            @if(isset($item->user->non_active_date) && date('Y-m-d') > $item->user->non_active_date )
                                                <span class="badge badge-danger" style="text-align: center;" title="{{$item->user->non_active_date}}">R</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->handover_date != "" ?  format_tanggal($item->handover_date) : '' }}</td>
                                        <td>
                                            @if($item->handover_date === NULL)
                                                <span class="badge badge-warning">Waiting Acceptance</span>
                                            @endif

                                            @if($item->handover_date !== NULL && $item->status == 1)
                                                <span class="badge badge-success">Accepted</span>
                                            @endif     
                                            
                                            @if($item->handover_date !== NULL && $item->status == 2)
                                                <span class="badge badge-info">Waiting Returned</span>
                                            @endif     
                                            
                                            @if($item->handover_date !== NULL && $item->status == 3)
                                                <span class="badge badge-danger">Rejected by {{isset($item->user->name) ? $item->user->name : ''}}</span>
                                            @endif       
                                        </td>
                                        <td>
                                            <a href="{{ route('administrator.asset.edit', $item->id) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> edit</button></a>
                                            <a href="{{ route('administrator.asset.show', $item->id) }}"> <button class="btn btn-primary btn-xs m-r-5">History</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>                
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>
    @section('js')
    <script>
        function reset_filter()
        {
            $("#filter-form input.form-control, #filter-form select").val("");
            $("#filter-form input[name='action']").val('');
            $("input[name='reset']").val(1);
            $("#filter-form").submit();
        }

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
                $( "input[name='user_id']" ).val(ui.item.id);
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
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
            var column = data_table_no_search.column($(this).attr('data-column'));
    
            // Toggle the visibility
            column.visible(!column.visible());
        });
    </script>
    @endsection
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@endsection
