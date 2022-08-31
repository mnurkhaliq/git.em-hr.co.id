@extends('layouts.administrator')

@section('title', 'Edit Bank CV Option Setting')

@section('content')

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Form Bank CV Option Setting</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Bank CV Option Setting</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Edit Bank CV Option Setting</h3>
                    <hr />
                    <form class="form-horizontal" enctype="multipart/form-data"
                        action="{{ route('administrator.setting-bank-cv.update', $bankCvOption->id) }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">

                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-md-12">Name<span class="text-danger">*</span></label>
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" required value="{{ $bankCvOption->name }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_dropdown" class="switch-input" {{ $bankCvOption->is_dropdown ? 'checked' : '' }}/>
                                <label style="margin-left: 5px;">Dropdown</label>
                                <input type="radio" name="is_dropdown" id="is_dropdown_on" value="1" {{ $bankCvOption->is_dropdown ? 'checked' : '' }} hidden>
                                <input type="radio" name="is_dropdown" id="is_dropdown_off" value="0" {{ $bankCvOption->is_dropdown ? '' : 'checked' }} hidden>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_date" class="switch-input" {{ $bankCvOption->is_date ? 'checked' : '' }}/>
                                <label style="margin-left: 5px;">Show Updated Date</label>
                                <input type="radio" name="is_date" id="is_date_on" value="1" {{ $bankCvOption->is_date ? 'checked' : '' }} hidden>
                                <input type="radio" name="is_date" id="is_date_off" value="0" {{ $bankCvOption->is_date ? '' : 'checked' }} hidden>
                            </div>
                        </div>
                        <div class="form-group" id="date_name" {{ $bankCvOption->is_date ? '' : 'style=display:none' }}>
                            <label class="col-md-12">Updated Date Field Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="date_name" value="{{ $bankCvOption->date_name }}" {{ $bankCvOption->is_date ? 'required' : '' }} />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_list" class="switch-input" {{ $bankCvOption->is_list ? 'checked' : '' }}/>
                                <label style="margin-left: 5px;">Show in List</label>
                                <input type="radio" name="is_list" id="is_list_on" value="1" {{ $bankCvOption->is_list ? 'checked' : '' }} hidden>
                                <input type="radio" name="is_list" id="is_list_off" value="0" {{ $bankCvOption->is_list ? '' : 'checked' }} hidden>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="checkbox" id="is_filter" class="switch-input" {{ $bankCvOption->is_filter ? 'checked' : '' }}/>
                                <label style="margin-left: 5px;">Show as Filter</label>
                                <input type="radio" name="is_filter" id="is_filter_on" value="1" {{ $bankCvOption->is_filter ? 'checked' : '' }} hidden>
                                <input type="radio" name="is_filter" id="is_filter_off" value="0" {{ $bankCvOption->is_filter ? '' : 'checked' }} hidden>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-default waves-effect waves-light m-r-10" onclick="document.getElementById('cancel').submit()"><i class="fa fa-arrow-left"></i> Cancel</button>
                            <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save</button>
                            <br style="clear: both;" />
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
            <form id="cancel" action="{{ route('administrator.setting-bank-cv.index') }}" method="GET"></form>
            <div class="col-md-12 tableOption" {{ $bankCvOption->is_dropdown ? '' : 'style=display:none' }}>
                <div class="white-box">
                    <h3 class="box-title m-b-0">Option Values</h3>
                    <hr />
                    <div class="table-responsive">
                        <div class="form-group col-md-1 pull-right">
                            <button class="btn btn-sm btn-info pull-right" type="button" id="addOption" autocomplete="off">Add Value</button>
                        </div>
                        <table class="display nowrap dataTable no-footer optionTable" role="grid" aria-describedby="data_table_no_search_info"></table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
        <div  class="modal fade none-border" id="modal-add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Add New Option Values</strong></h4>
                    </div>
                    <form id="optionForm">
                        <input type="hidden" id="value_id" name="value_id">
                        <input type="hidden" id="bank_cv_option_id" name="bank_cv_option_id" value="{{ $bankCvOption->id }}">
                        <div class="modal-body" id="modal-add-body">
                            <div class="form-group col-md-12">
                                <label>Value</label>
                                <div>
                                    <input required autocomplete="off" type="text" id="value" name="value" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success save-event waves-effect waves-light">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $('#is_dropdown').on('change',function () {
        if ($(this).is(':checked')) {
            $("#is_dropdown_on").prop("checked", true);
            $(".tableOption").show();
        }
        else {
            $("#is_dropdown_off").prop("checked", true);
            $(".tableOption").hide();
        }
    });

    $('#is_date').on('change',function () {
        if ($(this).is(':checked')) {
            $("#is_date_on").prop("checked", true);
            $("#date_name").show();
            $("#date_name input").attr("required", true);
        }
        else {
            $("#is_date_off").prop("checked", true);
            $("#date_name").hide();
            $("#date_name input").val('').removeAttr("required");
        }
    });

    $('#is_list').on('change',function () {
        if ($(this).is(':checked'))
            $("#is_list_on").prop("checked", true);
        else
            $("#is_list_off").prop("checked", true);
    });

    $('#is_filter').on('change',function () {
        if ($(this).is(':checked'))
            $("#is_filter_on").prop("checked", true);
        else
            $("#is_filter_off").prop("checked", true);
    });

    var optionTable = null

    $(document).ready(function() {
        initOptionTable();
    });

    function initOptionTable() {
        optionTable = $('.optionTable').DataTable( {
            ajax: "{{ URL::to('administrator/bank-cv-option-values') }}/" + $('#modal-add #bank_cv_option_id').val(),
            order: [
                [0, "asc"]
            ],
            columnDefs: [{
                "targets": [0],
                "searchable": false,
                "orderable": false,
                "visible": true
            }],
            columns: [
                {
                    data: 'id',
                    title: 'No',
                    width: 1,
                    className: 'id'
                },
                {
                    data: 'value',
                    title: 'Value'
                },
                {
                    data: null,
                    title: 'Action',
                    render: function () {
                        return '<button id="edit" type="button" class="btn btn-info btn-xs m-r-5"><i class="fa fa-edit"></i> Edit</button>'+
                            '<button id="delete" type="button" class="btn btn-danger btn-xs m-r-10"><i class="ti-trash"></i> Delete</button>'
                    }
                },
            ],
        });

        optionTable.on('order.dt search.dt', function () {
            optionTable.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        $('.optionTable tbody').on('click', 'button', function () {
            var data = optionTable.row($(this).parents('tr')).data()
            if (this.id == 'delete')
                optionDelete(data)
            else
                optionEdit(data)
        })
    }

    $(document).on('hide.bs.modal', '#modal-add', function () {
        $('#modal-add .modal-title strong').html('Add New Option Values')
        $('#modal-add #value_id').val('')
        $('#modal-add #value').val('')
    })

    $('#addOption').click(function () {
        $('#modal-add').modal('show');
    })

    function optionDelete(data) {
        swal({
            title: 'Are you sure?',
            text: "Once deleted, you will not be able to recover this data!",
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if (result) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'DELETE',
                    url: "{{ URL::to('administrator/bank-cv-option-values') }}/" + data.id,
                    success: function(response){
                        optionTable.ajax.reload()
                        swal(response.type, response.title, response.type)
                    }
                })
            }
        })
    }

    function optionEdit(data) {
        $('#modal-add .modal-title strong').html('Edit Option Values')
        $('#modal-add #value_id').val(data.id)
        $('#modal-add #value').val(data.value)
        $('#modal-add').modal('show');
    }

    $("#optionForm").submit(function(e) {
        e.preventDefault();
        optionSubmit();
    });

    function optionSubmit() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: !$('#modal-add #value_id').val() ? 'POST' : 'PATCH',
            url: !$('#modal-add #value_id').val() ? "{{ URL::to('administrator/bank-cv-option-values') }}" : "{{ URL::to('administrator/bank-cv-option-values') }}/" + $('#modal-add #value_id').val(),
            data: {
                bank_cv_option_id: $('#modal-add #bank_cv_option_id').val(),
                value: $('#modal-add #value').val()
            },
            success: function(response){
                $('#modal-add').modal('hide');
                optionTable.ajax.reload()
                swal(response.type, response.title, response.type)
            }
        })
    }
</script>
@endsection
@endsection