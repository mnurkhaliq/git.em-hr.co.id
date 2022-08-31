@extends('layouts.administrator')

@section('title', 'Organization Structure')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Organization Structure</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                @if(countStructureOrganization() == 0)
                <a href="javascript:void(0)" onclick="add_structure(null,'')" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> Add Presiden Direktur</a>
                @endif
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Organization Structure</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    <button type="button" onclick="exportData()" class="btn btn-info btn-sm pull-right">Export<i class="fa fa-download"></i></button>
                    <div id="orgChart" style="overflow: scroll;"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>

@section('footer-script')
<div id="modal_structure_organization" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="title_structure_organization"></h4> </div>
                <form method="POST" class="form-horizontal" action="{{ route('administrator.organization-structure-custom.store') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="parent_id" class="form-control" />
                        <div class="form-group">
                            <label class="col-md-3">Position</label>
                            <div class="col-md-9">
                                <select class="form-control" name="organisasi_position_id">
                                        <option value=""> - choose - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Division</label>
                            <div class="col-md-9">
                                <select class="form-control" name="organisasi_division_id">
                                        <option value=""> - choose - </option>
                                        @foreach($division as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Title</label>
                            <div class="col-md-9">
                                <select class="form-control" name="organisasi_title_id">
                                        <option value=""> - choose - </option>
                                        @foreach($title as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="container_structure">
                            <label class="col-md-3">Structure</label>
                            <div class="col-md-9">
                                <select class="form-control" name="structure">
                                    <option value="below">Below</option>
                                    <option value="above">Above</option>
                                </select>
                            </div>
                        </div>
                        @if(checkModuleAdmin(26))
                        <div class="form-group">
                            <label class="col-md-3">Grade</label>
                            <div class="col-md-9">
                                <select class="form-control" name="grade_id">
                                        <option value=""> - choose - </option>
                                        <?php $grade = get_grades(); ?>
                                        @foreach($grade as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-md-3">Job Description</label>
                            <div class="col-md-9">
                            <textarea class="content" name="job_desc" id="job_desc"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Requirement</label>
                            <div class="col-md-9">
                            <textarea class="content" name="requirement" id="requirement"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_structure_organization_edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="title_structure_organization"></h4> </div>
                <form method="POST" class="form-horizontal" action="{{ route('administrator.organization-structure-custom.update') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <input type="hidden" name="parent_id" class="form-control" />
                        <input type="hidden" name="structure_id" id="structure_id">
                        <div class="form-group">
                            <label class="col-md-3">Supervisor</label>
                            <div class="col-md-9">
                                <select class="form-control" name="parent_id" id="parent_id">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Position</label>
                            <div class="col-md-9">
                                <select class="form-control" name="pid" id="pid">
                                        <option value=""> - choose - </option>
                                        @foreach($position as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Division</label>
                            <div class="col-md-9">
                                <select class="form-control" name="did" id="did">
                                        <option value=""> - choose - </option>
                                        @foreach($division as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Title</label>
                            <div class="col-md-9">
                                <select class="form-control" name="tid" id="tid">
                                        <option value=""> - choose - </option>
                                        @foreach($title as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        @if(checkModuleAdmin(26))
                        <div class="form-group">
                            <label class="col-md-3">Grade</label>
                            <div class="col-md-9">
                                <select class="form-control" name="grade_edit" id="grade_edit">
                                        <option value=""> - choose - </option>
                                        <?php $grade = get_grades(); ?>
                                        @foreach($grade as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <label class="col-md-3">Job Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="job_desc_edit" id="job_desc_edit"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Requirement</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="requirement_edit" id="requirement_edit"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.replace( 'job_desc' );
    CKEDITOR.replace( 'requirement' );
    CKEDITOR.replace( 'job_desc_edit' );
    CKEDITOR.replace( 'requirement_edit' );
</script>
<link href="{{ asset('admin-css/js/jquery.orgchart/jquerysctipttop.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-css/js/jquery.orgchart/jquery.orgchart.css') }}?v={{ date('YmdHis') }}" media="all" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin-css/js/jquery.orgchart/jquery.orgchart.js') }}?v={{ date('YmdHis') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    function structureDetail(id){
        $.ajax({
            url: "organization-structure-custom/"+id,
            type: "GET",
            dataType: "JSON",
            contentType: "application/json",
            processData: false,
            success: function (data) {
                if (data.status == 'success') {
                    var parent = data.data.parent;
                    data = data.data;
                    if(parent.length == 0){
                        $('#parent_id').html('<option value=""> NONE </option>');
                    }
                    else {
                        var html_parent = '';
                        for (var i = 0; i < parent.length; i++) {
                            html_parent += '<option value="' + parent[i].id + '">' + parent[i].position + '</option>';
                        }
                        console.log(data);
                        $('#parent_id').html(html_parent);
                        $('#parent_id').val(data.data[0].parent_id);
                    }



                    // alert(data.data[0].grade_id);
                    $('#grade_edit').val(data.data[0].grade_id);
                    $('#structure_id').val(data.data[0].id);
                    $('#tid').val(data.data[0].tid);
                    $('#did').val(data.data[0].did);
                    $('#pid').val(data.data[0].pid);
                    CKEDITOR.instances['job_desc_edit'].setData(data.data[0].description);
                    CKEDITOR.instances['requirement_edit'].setData(data.data[0].requirement);
                    // $('textarea#job_desc_edit').text(data.data[0].description);
                    // $('textarea#requirement_edit').text(data.data[0].requirement);
                    $('#modal_structure_organization_edit').modal('show');
                } else {
                    swal("Failed!", data.status, "error");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });

    }

    function confirm_delete_structure(id, org_chart)
    {
        bootbox.confirm({
            title : "<i class=\"fa fa-warning\"></i> EMPORE SYSTEM",
            message: 'Delete Structure ?',
            closeButton: false,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn btn-sm btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn btn-sm btn-danger'
                }
            },
            callback: function (result) {
              if(result)
              { 
                 $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.structure-custome-delete') }}',
                     dataType: "JSON",
                    data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                    success: function (data) {
                        console.log(data);
                        if (data.status == 'success') {
                            location.reload();
                        } else {
                            swal("Failed!", data.message, "error");
                        }
                         // org_chart.deleteNode(id);
                    }
                });
              }
            }
        });
    }

    function add_structure(id, title)
    {
        $("input[name='parent_id']").val(id);
        if(id == null){
            $('#container_structure').addClass('hidden');
        }else{
            $('#container_structure').removeClass('hidden');
        }
        $("#title_structure_organization").html(title);
        $("#modal_structure_organization").modal("show");
    }

    function edit_inline_structure(obj)
    {
        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.structure-custome-edit') }}',
            data: {'id' : obj.id, 'name' : obj.name, '_token' : $("meta[name='csrf-token']").attr('content')},
            success: function (data) {
            }
        });
    }

    $(function(){
         $.ajax({
            type: 'GET',
            url: '{{ route('ajax.get-stucture-custome') }}',
            dataType: 'json',
            success: function (data) {
                console.log(data);
                org_chart = $('#orgChart').orgChart({
                    data: data,
                    showControls: true,
                    allowEdit: false,
                    newNodeText : 'Add',
                    onAddNode: function(node){ 
                        add_structure(node.data.id, node.data.name);
                    },
                    onDeleteNode: function(node){
                        confirm_delete_structure(node.data.id, org_chart);
                    },
                    onClickNode: function(node){
                        structureDetail(node.data.id);
                    }
                });
               
            }
        })
    });

    function exportData() {
        window.location.href = "{{route('administrator.organization-structure-custom.export')}}";
    }
    </script>
@endsection

@endsection
