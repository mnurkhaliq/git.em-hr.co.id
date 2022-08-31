@extends('layouts.administrator')

@section('title', 'Internal Memo')

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
                <h4 class="page-title">Form Internal Memo</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Internal Memo</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.internal-memo.store') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Internal Memo</h3>
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
                                <input type="text" required class="form-control" name="title">
                            </div>
                        </div>




                        <div class="form-group">
                            <label class="col-md-12">Content</label>
                            <div class="col-md-12">
                                <textarea class="content" name="content" id="ckeditor"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Status</label>
                            <div class="col-md-12">
                                <select class="form-control" name="status" required>
                                    <option value=""> - none - </option>
                                    <option value="1">Publish</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Thumbnail</label>
                            <div class="col-md-12">
                                <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*">
                                <output id='result_thumbnail' />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Image Detail</label>
                            <div class="col-md-12">
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                <output id='result_image' />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">File (PDF/Image)</label>
                            <div class="col-md-12">
                                <input type="file" id="file" name="file[]" class="form-control" accept="image/*, application/pdf" multiple />
                                <output id='result_file' />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br />
                    
                        <a href="{{ route('administrator.internal-memo.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
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
    @include('layouts.footer')
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

        $("#file").on("change", function() {
            if ($("#file")[0].files.length > 10) {
                $("#file").val('')
                $("#result_file").html("");
                alert("You can select only 10 images and PDF");
            } else if ($("#file")[0].files.length <= 10) {
                var files = $('#file')[0].files;
                var totalSize = 0;
                for (var i = 0; i < files.length; i++) {
                    // calculate total size of all files        
                    totalSize += files[i].size;
                    if(files[i].size > 1000000 && files[i].type.match('image')){
                        console.log(files[i].size)
                        i = files.length;
                        imageToBig();
                    }
                    else if(files[i].size > 5000000 && files[i].type.match('pdf')){
                        console.log(files[i].size)
                        i = files.length;
                        pdfToBig();
                    }
                }
                showFile()
            }
        });

        function imageToBig(){
            $("#file").val('')
            $("#result_file").html("");
            window.alert("Maximal of image size is 1 Mb");
        }

        function pdfToBig(){
            $("#file").val('')
            $("#result_file").html("");
            window.alert("Maximal of PDF size is 5 Mb");
        }

        function showFile(){
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementById("file");
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_file");
                    $("#result_file").html("");
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
            } else {
                console.log("Your browser does not support File API");
            }
        }

        window.onload = function() {
            //Check File API support
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementById("thumbnail");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    var output = document.getElementById("result_thumbnail");
                    $("#result_thumbnail").html("");
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
