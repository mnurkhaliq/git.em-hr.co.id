@extends('layouts.karyawan')

@section('title', 'Payment Request')

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
                <h4 class="page-title">Form Payment Request</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Payment Request</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form_payment" enctype="multipart/form-data" action="{{ route('karyawan.payment-request-custom.update', $data->id) }}" method="POST">
            <input type="hidden" name="_method" value="PUT">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Data Payment Request</h3>
                        <br />
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ csrf_field() }}
                        <input type="hidden" name="status" value="1">
                        <div class="col-md-6" style="padding-left:0;">
                            <div class="form-group">
                                <label class="col-md-12">PR Number</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" readonly="true" value="{{ $data->number }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">From</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name  }}" readonly="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">To : Accounting Department</label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Purpose</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="tujuan" {{$data->status=='4'? '' : 'readonly'}}>{{ $data->tujuan }}</textarea>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-md-12">Payment Method</label>
                                <div class="col-md-12">
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" {{ $data->payment_method == 'Cash' ? 'checked="true"' : '' }} value="Cash" {{$data->status=='4'? '' : 'disabled'}}/> Cash</label> &nbsp;&nbsp;
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" {{ $data->payment_method == 'Bank Transfer' ? 'checked="true"' : '' }} value="Bank Transfer" {{$data->status=='4'? '' : 'disabled'}}/> Bank Transfer</label>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Name of Account</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ isset(Auth::user()->nama_rekening) ? Auth::user()->nama_rekening : '' }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Account Number</label>
                                <div class="col-md-12">
                                    <input type="number" class="form-control" readonly="true" value="{{ isset(Auth::user()->nomor_rekening) ? Auth::user()->nomor_rekening : '' }}" />
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name Of Bank</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ isset(Auth::user()->bank) ? Auth::user()->bank->name : '' }}" />
                                </div>
                            </div>
                            @if($data->payment_method == 'Bank Transfer' && $data->status==2)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="col-md-12">Proccess</label>
                                    <div class="col-md-12">
                                        <label style="font-weight: normal;"><input class="form-check-input" type="checkbox" {{$data->is_transfer==1 ? 'checked disabled' : ''}} id="is_transfer" name="is_transfer" value="1" disabled>  Has been Proccessed</label> &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" id="disbursement_div">
                                <div class="form-group">
                                    <label class="col-md-12">Disbursement</label>
                                    <div class="col-md-12">
                                        <label style="font-weight: normal;"><input type="radio" id="disbursement_id" name="disbursement" {{ $data->disbursement == 'Transfer' ? 'checked="true"' : '' }} value="Transfer" disabled/> Transfer</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" id="disbursement_id_next" name="disbursement" {{ $data->disbursement == 'Next Payroll' ? 'checked="true"' : '' }} value="Next Payroll" disabled/> Next Payroll</label>
                                    </div>
                                </div>
                            </div>
                            @if($data->transfer_proof == NULL && $data->disbursement == 'Transfer')
                            <div class="col-md-6" id="transfer_proof_div">
                                <div class="form-group">
                                    <label class="col-md-12">Transfer Proof</label>
                                    <div class="col-md-8">
                                        <input type="file" id="transfer_proof_by_admin" name="transfer_proof_by_admin" {{$data->is_transfer==1 ? 'disabled' : ''}} class="form-control " accept="image/*, application/pdf"/>
                                    </div>
                                    <div class="col-md-4">
                                        <a onclick="preview()" class="btn btn-default preview" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                    </div>
                                </div>
                            </div>
                            @elseif($data->transfer_proof != NULL)
                            <a onclick="show_proof('{{ $data->transfer_proof }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                            @endif
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        
                        <div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TYPE</th>
                                        <th>{{get_setting('period_ca_pr') == 'yes' ? 'AVAILABLE ' : '' }} PLAFOND (IDR)</th>
                                        <th>DESCRIPTION</th>
                                        {{--<th>QUANTITY</th>--}}
                                        <th>AMOUNT (IDR)</th>
                                        <th>AMOUNT APPROVED (IDR)</th>
                                        <th>RECEIPT TRANSACTION</th>
                                        @if($data->status != 4)
                                        <th>NOTE</th>
                                        @else
                                        <th></th>
                                        @endif
                                    </tr>
                                </thead>
                                @if($data->status != 4)
                                <tbody class="table-content-lembur">
                                    @php($total_cost=0)
                                    @php($total_amount=0)
                                    @php($total_amount_approved=0)
                                    @foreach($form as $key => $item)
                                    @php($total_amount +=$item->amount)
                                    @php($total_amount_approved +=$item->nominal_approved)
                                    <tr>
                                        <td>{{ ($key+1) }}</td>
                                        <td>{{ $item->type_form }} @if($item->bensin) <a class="btn btn-info btn-xs" data-tanggal="{{$item->bensin->tanggal}}" data-odo_start="{{$item->bensin->odo_start}}" data-odo_end="{{$item->bensin->odo_end}}" data-liter="{{$item->bensin->liter}}" data-cost="{{$item->bensin->cost}}" onclick="info_bensin_save(this)"><i class="fa fa-info"></i></a>  @endif</td>
                                        @if(get_setting('period_ca_pr')== 'yes' && $item->plafond != $item->sisa_plafond)
                                        <td>
                                            @if(isset($item->nominal_approved))
                                            {{ isset($item->sisa_plafond) ? format_idr($item->sisa_plafond+$item->nominal_approved) : '' }}
                                            @else
                                            {{ isset($item->sisa_plafond) ? format_idr($item->sisa_plafond+$item->amount) : '' }}
                                            @endif
                                        </td>
                                        @else
                                        <td>{{ isset($item->sisa_plafond) ? format_idr($item->sisa_plafond) : '' }}</td>
                                        @endif
                                        <td>{{ $item->description }}</td>
                                        {{--<td>{{ $item->quantity }}</td>--}}
                                        <td>{{ format_idr($item->amount) }}</td>
                                        <td>{{ $item->nominal_approved ? format_idr($item->nominal_approved) : '' }}</td>
                                        <td>
                                            @if(!empty($item->file_struk)) 
                                                <a onclick="show_image('{{ $item->file_struk }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                            @endif
                                        </td>
                                        <td>{{ $item->note }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background: #eee;">
                                        <th colspan="4" style="text-align: right;">Total</th>
                                        <th>{{ format_idr($total_amount) }}</th>
                                        <th colspan="3">{{ format_idr($total_amount_approved) }}</th>
                                    </tr>
                                </tfoot>
                                @else
                                <tbody class="table-content-lembur">
                                    @foreach($form as $key => $item)
                                    <tr class="oninput">
                                        <td class="nomor">{{ ($key+1) }} <input type="hidden" name="idForm[]" value="{{$item->id}}"></td>
                                        <td>
                                            <div class="col-md-10" style="padding-left:0;">
                                                <select name="type[]" class="form-control type_form input" onchange="select_type_(this)" id="select" required>
                                                    <option value=""> - Select Type - </option>
                                                    @forelse($type as $t)
                                                    <option value="{{$t->type}}"  {{$item->type_form== $t->type ? 'selected' : ''}} data-plafond="{{$t->plafond}}">{{$t->type}}</option>
                                                    @empty
                                                    <option value="Parking" {{$item->type_form=='Parking' ? 'selected' : ''}}>Parking</option>
                                                    <option value="Gasoline" {{$item->type_form=='Gasoline' ? 'selected' : ''}}>Gasoline</option>
                                                    <option value="Toll" {{$item->type_form=='Toll' ? 'selected' : ''}}>Toll</option>
                                                    <option value="Transportation" {{$item->type_form=='Transportation' ? 'selected' : ''}}>Transportation</option>
                                                    <option value="Transport(Overtime)" {{$item->type_form=='Transport(Overtime)' ? 'selected' : ''}}>Transport(Overtime)</option>
                                                    <option value="Others" {{$item->type_form=='Others' ? 'selected' : ''}}>Others</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                            <div class="content_bensin">
                                            @if($item->bensin) <a class="btn btn-info btn-xs" data-tanggal="{{$item->bensin->tanggal}}" data-odo_start="{{$item->bensin->odo_start}}" data-odo_end="{{$item->bensin->odo_end}}" data-liter="{{$item->bensin->liter}}" data-cost="{{$item->bensin->cost}}" onclick="info_bensin_edit(this)"><i class="fa fa-info"></i></a>  @endif
                                            </div>
                                            <div class="content_overtime"></div>
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control plafond_value input price_format" value="{{ $item->plafond }}" name="plafond[]" readonly="true">
                                            <input type="text" class="form-control sisa_plafond_value input price_format" value="{{ $item->sisa_plafond ?  $item->sisa_plafond + $item->amount : '' }}" name="sisa_plafond[]" readonly="true">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input" value="{{ $item->description }}" name="description[]" required>
                                        </td>
                                        {{--<td>
                                            <input type="number" name="quantity[]" value="{{ $item->quantity }}" class="form-control input" required>
                                        </td>--}}
                                        <td>
                                            <input type="text" name="amount[]" value="{{ $item->amount }}" onchange="cek_amount(this)" max="{{$item->plafond}}" class="form-control amount price_format" required>
                                        </td>
                                        <td>
                                            <input type="text" name="amount_approved[]" class="form-control price_format" readonly="true">
                                        </td>
                                        <td>
                                            <input type="file" name="file_struk[]" class="form-control input_file input"  id="payment_request_{{$item->id}}" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" {{ $item->file_struk ? '' : 'required'}}>
                                            <div id="default_payment_request_{{$item->id}}">
                                            @if(!empty($item->file_struk)) 
                                                <a onclick="show_image('{{ $item->file_struk }}')" class="btn btn-default btn-xs"><i class="fa fa-search-plus"></i>View </a>
                                            @endif
                                            </div><div id="preview_payment_request_{{$item->id}}" style="display: none"></div>
                                        </td>
                                        <td id="showhide"><a class="btn btn-xs btn-danger" onclick="delete_item(this);"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr style="background: #eee;">
                                        <th colspan="4" class="text-right" style="font-size: 14px;">Total Claim : </th>
                                        <th class="total_amount" style="font-size: 14px;" colspan="3">0</th>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                            @if($data->status ==4)
                            <a class="btn btn-info btn-xs pull-right" id="add"><i class="fa fa-plus"></i> Add</a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        @foreach($data->historyApproval as $key => $item)
                            <div class="form-group">
                                <label class="col-md-12">Note Approval {{$item->setting_approval_level_id}}</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control note" value="{{ $item->note }}">
                                </div>
                            </div>
                            @endforeach

                            <div class="clearfix"></div>
                            <br />
                    
                        <a href="{{ route('karyawan.payment-request-custom.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        @if($data->status==4)
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit_payment"><i class="fa fa-save"></i> Submit Payment Request</a>
                        <a class="btn btn-sm btn-primary waves-effect waves-light m-r-10" id="btn_draft"><i class="fa fa-save"></i> Save Draft</a>
                        @endif
                        <br style="clear: both;" />
                        <div class="clearfix"></div>
                    </div>
                </div>  
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body form-horizontal">
                    <div id="modalcontent">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modal_bensin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Data Gasoline</h4> </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form_modal_bensin">
                        <div class="form-group">
                            <label class="col-md-12">Date of purchase of gasoline</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker modal_tanggal_struk_bensin" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Odometer (KM)</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_from" placeholder="From Odo Meter" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_to" placeholder="To Odo Meter" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Liter</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control modal_liter" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Cost (IDR)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal_cost price_format" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" id="btn_cancel_bensin" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-sm" id="add_modal_bensin">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modal_bensin_edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Data Gasoline</h4> </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="form_modal_bensin_edit">
                        <div class="form-group">
                            <label class="col-md-12">Date of purchase of gasoline</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker modal_tanggal_struk_bensin_edit" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Odometer (KM)</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_from_edit" placeholder="From Odo Meter" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_to_edit" placeholder="To Odo Meter" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Liter</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control modal_liter_edit" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Cost</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal_cost_edit price_format" {{$data->status=='4' ? '' : 'disabled'}}/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" id="btn_cancel_bensin" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-sm" id="edit_modal_bensin">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.container-fluid -->
    <!-- sample modal content -->
    <div id="modal_overtime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Data Overtime</h4> </div>
                <div class="modal-body">
                    <div class="form-horizontal modal-form-overtime">
                        @if(!data_overtime_user(Auth::user()->id))
                            <p><i>No Data Overtime</i></p>
                        @endif

                        @if(data_overtime_user(Auth::user()->id))
                            <table class="table tabl-hover">
                                <thead>
                                <tr>
                                    <th width="50">NO</th>
                                    <th>DATE</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(data_overtime_user(Auth::user()->id) as $item)
                                    <?php if($item->is_payment_request != ""){ continue; } ?>
                                    <tr>
                                        <td><input type="checkbox" name="overtime_item" value="{{ $item->id }}"></td>
                                        <td>{{ $item->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" id="btn_cancel_overtime" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-info btn-sm" id="add_overtime">Add</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @include('layouts.footer')
</div>
@section('footer-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });

        $("#btn_draft").click(function(){
        $('.oninput').find('td').removeAttr('required');
        const input = $('.oninput').find('td').removeAttr('required')
        input.required = false
        bootbox.confirm('Save as Draft?', function(res){
            if(res)
            {
                $("input[name='status']").val(4);
                $("#form_payment").submit();
            }
        });
        
    });
</script>
<script src="{{ asset('js/payment-request-custom/karyawan.js') }}?v={{ date('ymdhis') }}"></script>
<script type="text/javascript">
    var general_el;
    $("#add").click(function(){
        var no = $('.table-content-lembur tr').length;
        if((no+1) <= 15) {
        var html = '<tr class="oninput">';
            html += '<td class="nomor">'+ (no+1) +'</td>';
            html += '<td><div class="col-md-10" style="padding-left:0;">\
                            <select name="type[]" class="form-control type_form input" onchange="select_type_(this)" required>\
                            <option value=""> - Select Type - </option>'+
                            '@forelse($type as $t)'+
                            '<option value="{{$t->type}}" data-plafond="{{$t->plafond}}">{{$t->type}}</option>'+
                            '@empty @endforelse';

            html += '<div class="content_bensin"></div><div class="content_overtime"></div></td>';
            html += '<td><input type="hidden" class="form-control plafond_value input" name="plafond[]" readonly="true"/><input type="text" class="form-control sisa_plafond_value input price_format" name="sisa_plafond[]" readonly="true"/></td>';
            html += '<td class="description_td"><input type="text" class="form-control input" name="description[]" required></td>';
            // html += '<td><input type="number" name="quantity[]" value="1" class="form-control input" required/></td>';
            //html += '<td><input type="number" name="estimation_cost[]" class="form-control estimation" /></td>';
            html += '<td><input type="text" name="amount[]" class="form-control amount price_format" onchange="cek_amount(this)" required/></td>';
            html += '<td><input type="number" name="amount_approved[]" class="form-control" readonly="true" /></td>';
            html += '<td><input type="file" name="file_struk[]" class="form-control input_file input" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" id="payment_'+no+'" required/>';
            html += '<div id="default_payment_'+no+'"></div><div id="preview_payment_'+no+'" style="display: none"></div>';  
            html += '</td>';
            html += '<td id="showhide"><a class="btn btn-xs btn-danger" onclick="delete_item(this);"><i class="fa fa-trash"></i></a></td>';
            html += '</tr>';

        $('.table-content-lembur').append(html);

        $('.estimation').on('input', function(){

            var total = 0;

            $('.estimation').each(function(){

                if($(this).val() != "")
                {
                    total += parseInt($(this).val());
                }
            });

            $('.total').html('Rp. '+ numberWithComma(total).replace(/,/g, "."));
        });
        price_format();

        $(".amount").on('input', function(){
            calculate_amount();
        });
        show_hide_add();
        cek_button_add();
        calculate_amount();

        initImage()
        }
        else{
            alert('Maximal of items are 15, Please make a new form!')
        }
    });

    function info_bensin_save(el){
        $('.modal_tanggal_struk_bensin').val($(el).data('tanggal'));
        $('.modal_odo_from').val($(el).data('odo_start'));
        $('.modal_odo_to').val($(el).data('odo_end'));
        $('.modal_liter').val($(el).data('liter'));
        $('.modal_cost').val($(el).data('cost'));
        $('#modal_bensin').modal('show');
    }

    function info_bensin_edit(el){
        $('.modal_tanggal_struk_bensin_edit').val($(el).data('tanggal'));
        $('.modal_odo_from_edit').val($(el).data('odo_start'));
        $('.modal_odo_to_edit').val($(el).data('odo_end'));
        $('.modal_liter_edit').val($(el).data('liter'));
        $('.modal_cost_edit').val($(el).data('cost'));
        $('#modal_bensin_edit').modal('show');

        const select = document.getElementById('select');
        general_el = $('#select');
    }

    $("#edit_modal_bensin").click(function(){

    var cost = $('.modal-cost').val();

    general_el.parent().find("input[name='amount[]']").val(cost);

    var tanggal     = $('.modal_tanggal_struk_bensin_edit').val();
    var odo_from    = $('.modal_odo_from_edit').val();
    var odo_to      = $('.modal_odo_to_edit').val();
    var liter       = $('.modal_liter_edit').val();
    var cost        = $('.modal_cost_edit').val();

    var el = '<div class="bensin"><a class="btn btn-info btn-xs" onclick="info_bensin(this)"><i class="fa fa-info"></i></a><input type="hidden" name="bensin[tanggal][]" value="'+ tanggal +'" />';
        el += '<input type="hidden" name="bensin[odo_from][]" value="'+ odo_from +'" />';
        el += '<input type="hidden" name="bensin[odo_to][]" value="'+ odo_to +'" />';
        el += '<input type="hidden" name="bensin[liter][]" value="'+ liter +'" />';
        el += '<input type="hidden" name="bensin[cost][]" value="'+ cost +'" /></div>';

        general_el.parent().parent().find('.content_bensin').html(el);
        general_el.parent().parent().parent().find("input[name='description[]']").val('Bensin');
        general_el.parent().parent().parent().find("input[name='quantity[]']").val(liter);
        general_el.parent().parent().parent().find("input[name='amount[]']").val(cost);

        $("#form_modal_bensin_edit").trigger('reset');
        $("#form_modal_bensin").trigger('reset');
        $("#modal_bensin_edit").modal("hide");

        calculate_amount();
        });
    </script>

<script type="text/javascript">
    function show_image(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/file-struk/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/file-struk/')}}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        }
        else{
            alert("Filetype is not supported!");
        }
        {{--bootbox.alert('<img src="{{ asset('storage/file-struk/') }}/'+ img +'" style = \'width: 100%;\' />');--}}
    }

    function show_proof(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf'){
            $('#modalcontent').html('<embed src="{{ asset('storage/payment-request/transfer-proof/')}}/'+ img +'" frameborder="0" width="100%" height="400px">');
            $('#modal_file').modal('show');
        }
        else if(images.includes(ext)){
            $('#modalcontent').html('<img src="{{ asset('storage/payment-request/transfer-proof/')}}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        }
        else{
            alert("Filetype is not supported!");
        }

    }

    function initImage() {
        if (window.File && window.FileList && window.FileReader) {
            var filesInput = document.getElementsByClassName("input_file");
            for (var i = 0; i < filesInput.length; i++) {
                filesInput[i].replaceWith(filesInput[i].cloneNode(true));
                filesInput[i].addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var id = event.target.id;
                    var output = $("#preview_" + id)[0];
                    $("#preview_" + id).html("");
                    if (files.length) {
                        $("#default_" + id).hide();
                        $("#preview_" + id).show();
                    } else {
                        $("#preview_" + id).hide();
                        $("#default_" + id).show();
                    }
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics
                        if (!file.type.match('image') && !file.type === 'application/pdf')
                            continue;
                        else if(file.size > 1000000){
                            $('#'+id).val('')
                            $("#preview_" + id).hide();
                            window.alert("Maximal of file size is 1 Mb");
                        }
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            div.innerHTML = '<label onclick="show_img(\'' + picFile.result + '\')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>';
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });
            }
        } else {
            console.log("Your browser does not support File API");
        }
    }

    function show_img(img)
    {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if (ext === 'pdf' || img.match('pdf')) {
            bootbox.alert({
                message : '<embed src="'+ img +'" frameborder="0" width="100%" height="600px">',
                size: 'large' 
            });
        } else if(images.includes(ext) || img.match('image')) {
            bootbox.alert({
                message : '<img src="'+ img +'" style="width: 100%;" />',
                size: 'large' 
            });
        } else {
            alert("Filetype is not supported!");
        }
    }

    window.onload = function() {
        initImage();
    }
</script>

@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
