@extends('layouts.administrator')

@section('title', 'Birthday Wording')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Birthday Wording</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="javascript:void(0)" id="form_add_modal" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD</a> 
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Birthday Wording</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <div class="table-responsive">
                        <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>WORD</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ $item->word }}</td>
                                        <td>
                                            <form action="{{ route('administrator.birthday-wording.destroy', $item->id) }}" method="post" style="margin-left: 5px;">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}                                               
                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="confirm_delete('Delete this data ?', this)" class="text-danger"><i class="ti-trash"></i> delete </a>
                                            </form> 
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
                <h4 class="modal-title" id="myModalLabel">Birthday Wording</h4> </div>
                <form method="POST" class="form-horizontal" action="{{ route('administrator.birthday-wording.store') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">Word</label>
                            <div class="col-md-9">
                                <input type="text" name="word" class="form-control" />
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

@section('js')
<script type="text/javascript">
    function edit_modal(el)
    {
        $("#modal_edit_provinsi").modal("show");
        $("#form-modal-edit").attr('action', $(el).data('url'));
        $("#form-modal-edit input[name='nama']").val($(el).data('nama'));
        $("#form-modal-edit select[name='type']").val($(el).data('type'));
    }

    $("#form_add_modal").click(function(){
        $('#modal_provinsi').modal("show");
    });
</script>
@endsection
@endsection