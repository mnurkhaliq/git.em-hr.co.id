@extends('layouts.administrator')

@section('title', 'Product Information')

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
                <h4 class="page-title">Form Product Information</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Product Information</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.product.update', $data->id) }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Product Information</h3>
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
                        <div class="form-group">
                            <label class="col-md-12">Title</label>
                            <div class="col-md-12">
                                <input type="text" required class="form-control" name="title" value="{{ $data->title }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Content</label>
                            <div class="col-md-12">
                                <textarea class="content" name="content" id="ckeditor">{{ $data->content }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Status</label>
                            <div class="col-md-12">
                                <select class="form-control" name="status" required>
                                    <option value=""> - none - </option>
                                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Publish</option>
                                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Thumbnail</label>
                            <div class="col-md-12">
                                <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*">
                                <output id='default_thumbnail'>
                                @if(!empty($data->thumbnail) && file_exists(public_path('storage/product/').$data->thumbnail))
                                    <div><img src="{{ asset('storage/product/'. $data->thumbnail) }}" /></div>
                                @endif
                                </output>
                                <output id='result_thumbnail' style="display: none" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Image Detail</label>
                            <div class="col-md-12">
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                <output id='default_image'>
                                @if(!empty($data->image) && file_exists(public_path('storage/product/').$data->image))
                                    <div><img src="{{ asset('storage/product/'. $data->image) }}" /></div>
                                @endif
                                </output>
                                <output id='result_image' style="display: none" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">File (PDF/Image)</label>
                            <div class="col-md-12">
                                <input type="file" id="file" name="file" class="form-control" accept="image/*, application/pdf" />
                                <output id='default_file'></output>
                                <output id='result_file' style="display: none" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                    
                        <a href="{{ route('administrator.product.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        <button type="submit"  class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Save Data</button>
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
    @extends('layouts.footer')
</div>
@section('footer-script')
    <style>
        .form-group img, .form-group embed {
            width: 30% !important;
            padding-bottom: 7px;
        }
        .form-group embed {
            height: 100%;
        }
    </style>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'ckeditor' );

        showAttachment()
        
        function showAttachment(){
            @if(!empty($data->file) && file_exists(public_path('storage/product/').$data->file))
                img = "{{ asset('storage/product/'. $data->file) }}";
                var ext = img.split('.').pop().toLowerCase();
                if(ext === 'pdf'){
                    $('#default_file').html('<div><embed src="'+img+'" ></div>');
                } else {
                    $('#default_file').html('<div><img src="'+img+'" /></div>');
                }
            @endif
        }

        window.onload = function() {
            //Check File API support
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementById("thumbnail");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_thumbnail");
                    $("#result_thumbnail").html("");
                    if (files.length) {
                        $("#default_thumbnail").hide();
                        $("#result_thumbnail").show();
                    } else {
                        $("#result_thumbnail").hide();
                        $("#default_thumbnail").show();
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
                            div.innerHTML = "<img src='" + picFile.result + "' />";
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
                        $("#default_image").hide();
                        $("#result_image").show();
                    } else {
                        $("#result_image").hide();
                        $("#default_image").show();
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
                            div.innerHTML = "<img src='" + picFile.result + "' />";
                            output.insertBefore(div, null);
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });

                var filesInput = document.getElementById("file");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_file");
                    $("#result_file").html("");
                    if (files.length) {
                        $("#default_file").hide();
                        $("#result_file").show();
                    } else {
                        $("#result_file").hide();
                        $("#default_file").show();
                    }
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics
                        if (!file.type.match('image') && !file.type === 'application/pdf')
                            continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            var div = document.createElement("div");
                            if(!file.type.match('image')){
                                div.innerHTML = "<embed src='" + picFile.result + "' >";
                            } else {
                                div.innerHTML = "<img src='" + picFile.result + "' />";
                            }
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
    </script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
