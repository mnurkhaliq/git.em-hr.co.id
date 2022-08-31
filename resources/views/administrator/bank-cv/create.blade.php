@extends('layouts.administrator')

@section('title', 'Bank CV')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Bank CV Form</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10 pull-right" onclick="submit()"><i class="fa fa-save"></i> Save CV Data </button>
                <a href="{{ route('administrator.bank-cv.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10 pull-right"><i class="fa fa-arrow-left"></i> Back </a>
            </div>
        </div>
    <div class="row">
        <form class="form-horizontal" id="form-bank-cv" enctype="multipart/form-data" action="{{ route('administrator.bank-cv.store') }}" method="POST">
            <div class="col-md-12 p-l-0 p-r-0">
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
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#biodata" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Personal Information</span></a></li>
                        <li role="presentation" class=""><a href="#option" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Option</span></a></li>                       
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="biodata">
                            {{ csrf_field() }}
                            <div class="col-md-6" style="padding-left: 0">
                                <div class="form-group">
                                    <div class="col-md-12" id="default_photos">
                                        <img src="{{ asset('admin-css/images/user.png') }}" style="width: 200px;" />
                                    </div>
                                    <div class="col-md-12" id="preview_photos" style="display: none">
                                        <output id="result_photos" />
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-info btn-xs" onclick="open_dialog_photo()"><i class="fa fa-upload"></i> Change Photo</button>
                                        <input type="file" id="photos" name="photos" class="form-control" style="display: none;" accept="image/*" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Name <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Gender <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select name="gender" class="form-control">
                                            <option value=""> - Gender - </option>
                                            @foreach(['Male', 'Female'] as $item)
                                                <option {{ old('gender') == $item ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Email <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Phone</label>
                                    <div class="col-md-10">
                                        <input type="number" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Born Year</label>
                                    <div class="col-md-10">
                                        <input type="number" name="born_year" class="form-control" value="{{ old('born_year') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Address</label>
                                    <div class="col-md-10">
                                        <textarea name="address" class="form-control" >{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" style="padding-left: 0">
                                <div class="form-group">
                                    <label class="col-md-12">Input Date</label>
                                    <div class="col-md-10">
                                        <input type="text" name="input_date" class="form-control datepicker2" value="{{ old('input_date', date('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Skill</label>
                                    <div class="col-md-10">
                                        <input type="text" name="skill" class="form-control" value="{{ old('skill') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Expected Salary</label>
                                    <div class="col-md-10">
                                        <input type="number" name="salary" class="form-control" value="{{ old('salary') }}"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Notes</label>
                                    <div class="col-md-10">
                                        <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">File CV</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="image" name="cv" class="form-control" accept="image/*, application/pdf" />
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_image()" class="btn btn-default preview_image" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="option">
                            @foreach(getBankCvOption() as $option)
                            <div class="col-md-6" style="padding-left: 0">
                                <div class="form-group">
                                    <label class="col-md-12">{{ ucwords($option->name) }}</label>
                                    <div class="col-md-10">
                                        @if($option->is_dropdown)
                                        <select name="option[{{ $option->id }}]" class="form-control">
                                            <option value=""> - Select - </option>
                                            @foreach($option->values as $value)
                                                <option value="{{ $value->id }}" {{ isset(old('option')[$option->id]) && old('option')[$option->id] == $value->id ? 'selected' : '' }}>{{ $value->value }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <input type="text" name="option[{{ $option->id }}]" class="form-control" value="{{ isset(old('option')[$option->id]) ? old('option')[$option->id] : '' }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <hr />
                    <br style="clear: both;" />
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.row -->
    <!-- ============================================================== -->
</div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>

<div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_image" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
<style type="text/css">
    .ui-autocomplete{
        z-index: 9999999 !important;
    }
    #map {
        height: 300px; 
        width: 100%;
    }
    .no-padding-td td {
        padding-top:2px !important;
        padding-bottom:2px !important;
    }
    .staff-branch-select, .head-branch-select {
        display: none;
    }
    .swal {
        margin: 10px;
    }
</style>
@section('footer-script')
    <style>
        output {
            padding-top: 0px;
        }
    </style>
    <link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker-employee/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker-employee/bootstrap-datepicker.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.js"></script>
    <script src="https://unpkg.com/@yaireo/tagify"></script>
    <script src="https://unpkg.com/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <style>
        .tags-look .tagify__dropdown__item{
            display: inline-block;
            border-radius: 3px;
            padding: .3em .5em;
            border: 1px solid #CCC;
            background: #F3F3F3;
            margin: .2em;
            font-size: .85em;
            color: black;
            transition: 0s;
        }

        .tags-look .tagify__dropdown__item--active{
            color: black;
        }

        .tags-look .tagify__dropdown__item:hover{
            background: lightyellow;
            border-color: gold;
        }

        .tagify {
            height: 100%;
        }
    </style>
    <script type="text/javascript">
        $.ajax({
            url: "{{ URL::to('administrator/bank-cv/tag') }}",
            success: function(response) {
                populateList(response);
            }
        });

        function populateList(params) {
            tagify = new Tagify(document.querySelector('input[name="skill"]'), {
                whitelist: params,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,           // <- mixumum allowed rendered suggestions
                    classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0,             // <- show suggestions on focus
                    closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
                }
            });
        }

        window.onload = function() {
            //Check File API support
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementById("photos");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_photos");
                    $("#result_photos").html("");
                    if (files.length) {
                        $("#default_photos").hide();
                        $("#preview_photos").show();
                    } else {
                        $("#preview_photos").hide();
                        $("#default_photos").show();
                    }
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics
                        if (!file.type.match('image'))
                            continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            div.innerHTML = "<img src='" + picFile.result + "' style='width: 200px;' />";
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });

                var filesInput = document.getElementById("image");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_image");
                    $("#result_image").html("");
                    if (files.length) {
                        $(".preview_image").show();
                    } else {
                        $(".preview_image").hide();
                    }
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        if (!file.type.match('image')) {
                            src = URL.createObjectURL(event.target.files[0]);
                            $("#result_image").html("<embed src='" + src + "' frameborder='0' width='100%' height='400px'>");
                            continue;
                        }
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            div.innerHTML = "<img src='" + picFile.result + "' style='width: 100%' />";
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });
            } else {
                console.log("Your browser does not support File API");
            }
        }
        
        jQuery('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
        }).on('change', function(){
            $('.datepicker').hide();
        });

        function open_dialog_photo()
        {
            $("input[name='photos']").trigger('click');   
        }

        function submit() {
            $('#form-bank-cv').submit()
        }

        function preview_image()
        {
            $('#modal_file').modal('show');
        }
    </script>
@endsection

@endsection
