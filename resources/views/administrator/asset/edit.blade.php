@extends('layouts.administrator')

@section('title', 'List of Asset')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Form List of Asset</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">List of Asset</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.asset.update', $data->id) }}" method="POST">
                <div class="col-md-12 p-l-0 p-r-0">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="white-box">
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
                        <div class="form-group">
                            <label class="col-md-12">Asset Number</label>
                            <div class="col-md-6">
                               <input type="text" readonly="true" class="form-control" value="{{ $data->asset_number }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Asset Name</label>
                            <div class="col-md-6">
                               <input type="text" name="asset_name" class="form-control" value="{{ $data->asset_name }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Asset Type</label>
                            <div class="col-md-6">
                                <select name="asset_type_id" id="asset_type_id" class="form-control" required>
                                    <option value=""> - none - </option>
                                    @foreach($asset_type as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $data->asset_type_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group asset-sn">
                            <label class="col-md-12">Serial / Plat Number</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="asset_sn" value="{{ $data->asset_sn }}" required/>
                            </div>
                        </div>

                        <div class="form-group asset-sn">
                            <label class="col-md-12">Specification</label>
                            <div class="col-md-6">
                            <textarea class="spesifikasi" name="spesifikasi" id="ckeditor">{!! isset($data->spesifikasi) ? $data->spesifikasi : ''!!}</textarea>
                            </div>
                        </div>

                        <div class="form-group" style="display: none;" id="div_pic_id">
                            <label class="col-md-12">PIC Name</label>
                            <div class="col-md-6">
                                <select name="pic_id" id="pic_id" class="form-control" required>
                                    <option>- none -</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group asset-ow">
                            <label class="col-md-12">Asset Ownership</label>
                            <div class="col-md-6">
                                <select class="form-control" name="status_mobil" required>
                                        <option value="">- none -</option>
                                        <option {{ $data->status_mobil == 'Rental' ? 'selected' : '' }}>Rental</option>
                                        <option {{ $data->status_mobil == 'Company Inventory' ? 'selected' : '' }}>Company Inventory</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Purchase Date / Rental Date</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control datepicker" name="purchase_date" value="{{ $data->purchase_date }}" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Remark</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="remark" value="{{ $data->remark }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Asset Condition</label>
                            <div class="col-md-6">
                                <select class="form-control" name="asset_condition" required>
                                    <option value=""> - none - </option>
                                    <option value="Good" {{ $data->asset_condition =='Good' ? 'selected' : '' }}>Good</option>
                                    <option value="Malfunction" {{ $data->asset_condition =='Malfunction' ? 'selected' : '' }}>Malfunction</option>
                                    <option value="Lost" {{ $data->asset_condition =='Lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="col-md-12">Status</label>
                            <div class="col-md-6">
                                <select class="form-control" name="assign_to" required>
                                    <option value=""> - none - </option>
                                    <option {{ $data->assign_to =='Assign To Employee' ? 'selected' : '' }}>Assign To Employee</option>
                                    <option {{ $data->assign_to =='Office Inventory/Idle' ? 'selected' : '' }}>Office Inventory/Idle</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Employee/User Name </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control autocomplete-karyawan" value="{{ $data->user->nik .' - '. $data->user->name }}" {{($data->status==0 && $data->handover_date == NULL)  || $data->status==2 ?  'disabled' : 'required'}}>
                                <input type="hidden" name="user_id" class="form-control" value="{{ $data->user_id }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Admin Note</label>
                            <div class="col-md-6">
                                <textarea class="form-control" name="admin_note">{{ $data->admin_note }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">User Note</label>
                            <div class="col-md-6">
                                <input class="form-control" disabled value="{{ $data->user_note_by ? $data->userNoteBy->nik .' - '. $data->userNoteBy->name : '' }}" style="margin-bottom: 10px;" />
                                <textarea class="form-control" disabled>{{ $data->user_note }}</textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <hr />
                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="{{ route('administrator.asset.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Update Data</button>
                                <br style="clear: both;" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>    
            </form>                    
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
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    
<script type="text/javascript">
    CKEDITOR.replace( 'ckeditor' );
    $("select[name='asset_type_id']").on('change', function(){
        var val = $("select[name='asset_type_id'] option:selected").text();

        if(val == 'Mobil')
        {
            $('.asset-mobil').slideDown("slow");
            $(".asset-sn").hide();
        }
        else
        {
            $('.asset-mobil').slideUp("slow");
            $(".asset-sn").show();
        }
    });
    jQuery('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
    });

    if($("#asset_type_id").val() != null){
        id = $("#asset_type_id").val()
        $("#div_pic_id").show();
        getPIC(id)
    }

    function getPIC(id){
        $.ajax({
            url: "{{ route('ajax.get-pic-asset') }}",
            method : 'POST',
            data: {
                'id': id,'_token' : $("meta[name='csrf-token']").attr('content')
            },
            success: function(data) {
                $('#pic_id').html(`
                    <option value="" selected disabled>--Select PIC--</option>
                `);    
                data.forEach(data => {    
                    if('{{$data->pic_id}}' == data.id){
                        $('#pic_id').append(`
                            <option value="${data.id}" selected>`+data.name+`</option>
                        `);
                    }
                    else{
                    $('#pic_id').append(`
                        <option value="${data.id}" >`+data.name+`</option>
                    `);
                    }
                });
            },
            error:function(XMLHttpRequest, textStatus, errorThrown) { 
                alert("Status: " + textStatus); alert("Error: " + errorThrown); 
            }    
        });
    }

    $("#asset_type_id").change(function(){
        $("#div_pic_id").show();
        getPIC($(this).val());
    });

    $(".autocomplete-karyawan" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
              url: "{{ route('ajax.get-karyawan-asset') }}",
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
</script>
@endsection
@endsection
