@extends('layouts.administrator')

@section('title', 'Setting Bank CV')

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
                <h4 class="page-title">Setting Bank CV</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting Bank CV</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                    <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#BankCvOption" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span><span class="hidden-xs">Option</span></a></li>
                        <li role="presentation" class=""><a href="#BankCvSkill" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span><span class="hidden-xs">Skill</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="BankCvOption">
                            <h3 class="box-title m-b-0">Option Setting</h3>
                            <a href="{{ route('administrator.setting-bank-cv.create') }}"
                                class="btn btn-success btn-sm  hidden-sm waves-effect waves-light"> <i
                                    class="fa fa-plus"></i> Add Option</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table id="data_table_no_pagging" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bankCvOptions as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <a href="{{ route('administrator.setting-bank-cv.edit', $item->id) }}" style="float: left; margin-right:10px"><button class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit</button></a>
                                                <form action="{{ route('administrator.setting-bank-cv.destroy', $item->id) }}" onsubmit="return confirm('Delete this data?')" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-danger btn-xs m-r-5"><i class="ti-trash"></i> delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade in" id="BankCvSkill">
                            <h3 class="box-title m-b-0">Skill Setting</h3>
                            <button type="button" id="addSkill" class="btn btn-success btn-sm hidden-sm waves-effect waves-light"><i class="fa fa-plus"></i> Add Skill</button>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="display nowrap dataTable no-footer skillTable" role="grid" aria-describedby="data_table_no_search_info"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <div  class="modal fade none-border" id="modal-add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"><strong>Add New Skill</strong></h4>
                    </div>
                    <form id="skillForm">
                        <div class="modal-body" id="modal-add-body">
                            <div class="form-group col-md-12">
                                <label>Skill</label>
                                <div>
                                    <input required autocomplete="off" type="text" id="tag" name="tag" class="form-control">
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
@section('footer-script')
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<style>
    .skillTable {
        margin-top: 0 !important;
    }
</style>
<script>
    var skillTable = null

    $(document).ready(function() {
        initSkill();
    });

    function initSkill() {
        skillTable = $('.skillTable').DataTable( {
            ajax: "{{ URL::to('administrator/bank-cv-skill-index') }}",
            paging: false,
            searching: false,
            bInfo: false,
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
                    data: 'tag',
                    title: 'Skill'
                },
                {
                    data: null,
                    title: 'Action',
                    render: function () {
                        return '<button id="delete" type="button" class="btn btn-danger btn-xs m-r-10"><i class="ti-trash"></i> Delete</button>'
                    }
                },
            ],
        });

        skillTable.on('order.dt search.dt', function () {
            skillTable.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        $('.skillTable tbody').on('click', 'button', function () {
            var data = skillTable.row($(this).parents('tr')).data()
            skillDelete(data)
        })
    }

    $(document).on('hide.bs.modal', '#modal-add', function () {
        $('#modal-add .modal-title strong').html('Add New Skill')
        $('#modal-add #tag').val('')
    })

    $('#addSkill').click(function () {
        $('#modal-add').modal('show');
    })

    function skillDelete(data) {
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
                    method: 'POST',
                    url: "{{ URL::to('administrator/bank-cv-skill-destroy') }}",
                    data: {
                        tag: data.tag
                    },
                    success: function(response){
                        skillTable.ajax.reload()
                        swal(response.type, response.title, response.type)
                    }
                })
            }
        })
    }

    $("#skillForm").submit(function(e) {
        e.preventDefault();
        skillSubmit();
    });

    function skillSubmit() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "{{ URL::to('administrator/bank-cv-skill-store') }}",
            data: {
                tag: $('#modal-add #tag').val()
            },
            success: function(response){
                $('#modal-add').modal('hide');
                skillTable.ajax.reload()
                swal(response.type, response.title, response.type)
            }
        })
    }
</script>
@endsection
@endsection