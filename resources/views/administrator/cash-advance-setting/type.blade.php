@extends('layouts.administrator')

@section('title', 'Type Payment Request & Cash Advance')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Type Payment Request & Cash Advance</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Type Payment Request & Cash Advance</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <h4 class="box-title m-b-0">Period</h4><br>
                    <form method="POST" name="form_setting" action="{{ route('administrator.payment-request-type.period.store') }}" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="button" class="btn btn-info btn-sm hidden-sm waves-effect waves-light m-l-20" onclick="form_setting.submit()"> <i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                        <div class="col-md-12 p-l-0">
                            <div class="form-group">
                                <label class="col-md-12">Do you want to activate period for Type of Payment Request and Cash Advance?</label>
                                <div class="col-md-12">
                                    <div class="input-form">
                                        <input type="radio" value="yes" {{ get_setting('period_ca_pr') == 'yes' ? 'checked' : '' }} name="setting[period_ca_pr]"> Yes
                                        <input type="radio" value="no" {{ get_setting('period_ca_pr') == 'no' || get_setting('period_ca_pr') == NULL ? 'checked' : '' }}  name="setting[period_ca_pr]"> No
                                    </div>
                                    @if(get_setting('period_ca_pr') && $period_ca_pr->value=='yes')
                                    <p>will be applied to payment request and cash advance transactions starting from {{date('d F Y H:i:s', strtotime($period_ca_pr->updated_at))}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                    <h3 class="box-title m-b-0">Type</h3>
                    <br>
                    <div class="table-responsive">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="javascript:void(0)" id="form_add_modal" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD</a> 
                            </div>
                        </div>
                        <table class="display nowrap data_table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>Type</th>
                                    <th>Plafond (IDR)</th>
                                    @if(get_setting('period_ca_pr') == 'yes')
                                    <th>Period</th>
                                    @endif
                                    <th>Description</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ $item->type }}</td>
                                        <td>{{ $item->plafond ? format_idr($item->plafond) : '' }}</td>
                                        @if(get_setting('period_ca_pr') == 'yes')
                                        <td>{{ $item->period }}</td>
                                        @endif
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-info btn-xs" style="float: left; margin-right:5px" data-url="{{ route('administrator.payment-request-type.update', $item->id) }}" data-type="{{ $item->type }}" data-is_lock="{{ $item->is_lock }}" data-plafond="{{$item->plafond}}" data-period="{{$item->period}}" data-description="{{$item->description}}" onclick="edit_modal(this)"><i class="fa fa-edit"></i> edit </a>
                                            @if($item->is_lock!=1)
                                            <form action="{{ url('administrator/payment-request-type/'. $item->id) }}" method="post" style="margin-left: 5px;">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}                                               
                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="confirm_delete('Delete this data ?', this)" class="text-danger"><i class="ti-trash"></i> delete </a>
                                            </form> 
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    @include('layouts.footer')
</div>

<div id="modal_provinsi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Type Payment Request & Cash Advance</h4> </div>
                <form method="POST" class="form-horizontal" action="{{ route('administrator.payment-request-type.store') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">Type</label>
                            <div class="col-md-9">
                                <input type="text" name="type" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Plafond</label>
                            <div class="col-md-9">
                                <input type="text" name="plafond" class="form-control price_format"/>
                            </div>
                        </div>
                        @if(get_setting('period_ca_pr') == 'yes')
                        <div class="form-group">
                            <label class="col-md-3">Period</label>
                            <div class="col-md-9">
                                <select name="period" class="form-control">
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-md-3">Description</label>
                            <div class="col-md-9">
                                <input type="text" name="description" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info btn-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_edit_provinsi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Type Payment Request & Cash Advance</h4> </div>
                <form method="POST" class="form-horizontal" id="form-modal-edit" action="">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">Type</label>
                            <div class="col-md-9">
                                <input type="text" name="type" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Plafond</label>
                            <div class="col-md-9">
                                <input type="text" name="plafond" class="form-control price_format"/>
                            </div>
                        </div>
                        @if(get_setting('period_ca_pr') == 'yes')
                        <div class="form-group">
                            <label class="col-md-3">Period</label>
                            <div class="col-md-9">
                                <select name="period" class="form-control">
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-md-3">Description</label>
                            <div class="col-md-9">
                                <input type="text" name="description" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript">
    function edit_modal(el)
    {
        $("#modal_edit_provinsi").modal("show");
        $("#form-modal-edit").attr('action', $(el).data('url'));
        $("#form-modal-edit input[name='type']").val($(el).data('type'));
        $("#form-modal-edit input[name='plafond']").val(numberWithDot($(el).data('plafond')));
        $("#form-modal-edit select[name='period']").val($(el).data('period'));
        $("#form-modal-edit input[name='description']").val($(el).data('description'));

        if($(el).data('is_lock') == 1)
        {
            $("#form-modal-edit input[name='type']").attr("readonly", true);

        } else {
            $("#form-modal-edit input[name='type']").attr("readonly", false);
        }

    }

    $("#form_add_modal").click(function(){
        $('#modal_provinsi').modal("show");
    });
</script>
@endsection
@endsection