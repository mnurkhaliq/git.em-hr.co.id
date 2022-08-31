@extends('layouts.administrator')

@section('title', 'Facilities Tracking')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-12">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Manage List of Asset Tracking</h4> 
                </div>
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Asset Tracking</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <form method="GET" id="filter-form">
                        <div class="col-md-2">
                            <input type="text" id="asset_number" class="form-control form-control-line " placeholder="Asset Number"value="{{($asset_number) ? $asset_number : ''}}" name="asset_number">
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
                            <button type="submit" class="btn btn-sm btn-info m-r-2" title="filter"><i class="fa fa-search-plus"></i></button>
                            <a href="javascript:void(0)" class="btn btn-sm btn-info m-r-2" onclick="reset_filter()" title="reset filter"> <i class="fa fa-refresh"></i></a>
                            <div class="btn-group pull-right">
                                <a href="javascript:void(0)" title="Show Hide Column" aria-expanded="false" data-toggle="dropdown" class="btn btn-default btn-sm btn-outline dropdown-toggle">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <ul role="menu" class="dropdown-menu" style="min-width: 10px; color: blue;">
                                    <li><a class="toggle-vis" data-column="1" style="color:blue;">Asset Number</a></li> 
                                    <li><a class="toggle-vis" data-column="2" style="color:blue;">Asset NAME</a></li> 
                                    <li><a class="toggle-vis" data-column="3" style="color:blue;">Asset TYPE</a></li>
                                    <li><a class="toggle-vis" data-column="4" style="color:blue;">Serial/Plat Number</a></li>
                                    <li><a class="toggle-vis" data-column="5" style="color:blue;">Purchase/Rental Date</a></li> 
                                    <li><a class="toggle-vis" data-column="6" style="color:blue;">Asset Condition</a></li>
                                    <li><a class="toggle-vis" data-column="7" style="color:blue;">Assign TO</a></li>
                                    <li><a class="toggle-vis" data-column="8" style="color:blue;">Employee</a></li>
                                    <li><a class="toggle-vis" data-column="9" style="color:blue;">Handover Date</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                    </form>
                    
                    <div class="table-responsive">
                        <table id="data_table_no_search" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">No</th>
                                    <th>ASSET NUMBER</th>
                                    <th>ASSET NAME</th>
                                    <th>ASSET TYPE</th>
                                    <th>SERIAL/PLAT NUMBER</th>
                                    <th>PURCHASE/RENTAL DATE</th>
                                    <th>ASSET CONDITION</th>
                                    <th>ASSIGN TO</th>
                                    <th>EMPLOYEE</th>
                                    <th>HANDOVER DATE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    @if(!isset($item->asset->asset_number))
                                        {{ $item->delete() }}
                                        <?php continue; ?>
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ $item->asset->asset_number }}</td>
                                        <td>{{ $item->asset->asset_name }}</td>
                                        <td>{{ isset($item->asset_type->name) ? $item->asset_type->name : ''  }}</td>
                                        <td>{{ $item->asset->asset_sn }}</td>
                                        <td>{{ format_tanggal($item->purchase_date) }}</td>
                                        <td>{{ $item->asset_condition }}</td>
                                        <td>{{ $item->assign_to }}</td>
                                        <td>{{ isset($item->user->name) ? str_limit($item->user->name, $limit = 20, $end = '...') : '' }}</td>
                                        <td>{{ $item->handover_date != "" ?  format_tanggal($item->handover_date) : '' }}</td>
                                       
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
