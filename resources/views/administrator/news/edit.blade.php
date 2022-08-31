@extends('layouts.administrator')

@section('title', 'News')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
<style>
    .delete-image {
        float: right;
        margin-left: 375px;
        position: absolute;
        display:block;
        text-decoration: none;
        font: 700 21px/20px sans-serif;
        color: #FFF;
    }
    .dropzone .dz-preview .dz-image {
        height:200px;
        width:200px;
    }
    .dz-image img{
        height:100%;
        width:100%;
        object-fit: contain;
    }
</style>
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Form News</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">News</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-8">
                <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.news.update', $data->id) }}" name="newsform" id="newsform" method="POST">
                    <input type="hidden" name="_method" value="POST">
                    <div>
                        <div class="white-box">

                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="col-md-12">Title</label>
                                <div class="col-md-12">
                                    <input type="text" required class="form-control" id="title" name="title" value="{{ $data->title }}" />
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
                                    <select class="form-control" name="status" id="status" required>
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
                                    @if(!empty($data->thumbnail) && file_exists(public_path('storage/news/').$data->thumbnail))
                                        <div><img src="{{ asset('storage/news/'. $data->thumbnail) }}" /></div>
                                    @endif
                                    </output>
                                    <output id='result_thumbnail' style="display: none" />
                                </div>
                            </div>
                            <input type="hidden" class="news_id" name="news_id" id="news_id" value="{{$data->id}}">
                            <div>
                                <div id="dropzoneDragArea" class="dz-default dz-message dropzone">
                                    <span>Image Detail</span>
                                </div>
                                <br>
                                <!-- <h3>Previous Images</h3>
                                @foreach($data->images as $image)
                                    @if(!empty($image->image) && file_exists(public_path('storage/news/').$image->image))
                                    <div class="img-wrap">
                                        <span class="close" style=" float: right;margin-left: 375px;position: absolute;display:block;" >
                                            <form action="{{ route('administrator.news.file.destroy', $image->id) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}                                               
                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="confirm_delete('Delete this data ?', this)" class="text-danger" title="Delete"><i class="ti-trash"></i></a>
                                            </form> 
                                        </span>
                                        <img src="{{ asset('storage/news/'. $image->image) }}" width="400px;"/>
                                    </div>
                                    @endif
                                @endforeach -->
                            </div>
                            <div class="clearfix"></div>
                            <br />
                        
                            <a href="{{ route('administrator.news.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            <button type="submit"  class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_submit"><i class="fa fa-save"></i> Save</button>
                            <br style="clear: both;" />
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div>
                    <div class="white-box">
                        <h4 class="text-center">Previous Image Detail</h4>
                        @foreach($data->images as $image)
                            @if(!empty($image->image) && file_exists(public_path('storage/news/').$image->image))
                            <div class="img-wrap" style="margin-bottom:10px;">
                                <span class="delete-image">
                                    <form action="{{ route('administrator.news.file.destroy', $image->id) }}" name="deleteForm" id="deleteForm" onsubmit="return confirm('Delete this data?')" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}                                               
                                        <button type="submit" id="deleteImage" class="btn btn-danger btn-xs"  class="text-danger" title="Delete"><i class="ti-trash"></i></button>
                                    </form> 
                                </span>
                                <img src="{{ asset('storage/news/'. $image->image) }}" width="400px;"/>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>                  
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
    </style>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'ckeditor' );

        $("#image").on("change", function() {
            if ($("#image")[0].files.length > 10) {
                $("#image").val('')
                $("#result_image").html("");
                alert("You can select only 10 images");
            } else if ($("#image")[0].files.length <= 10) {
                var files = $('#image')[0].files;
                var totalSize = 0;
                for (var i = 0; i < files.length; i++) {
                    // calculate total size of all files        
                    totalSize += files[i].size;
                    if(files[i].size > 1000000){
                        console.log(files[i].size)
                        i = files.length;
                        imageToBig();
                    }
                }
                showImage()
            }
        });

        function imageToBig(){
            $("#image").val('')
            $("#result_image").html("");
            window.alert("Maximal of image size is 1Mb");
        }

        function showImage(){
            if (window.File && window.FileList && window.FileReader) {
                var filesInput = document.getElementById("image");
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
            } else {
                console.log("Your browser does not support File API");
            }
        }
    </script>
    <script>
        Dropzone.autoDiscover = false;
        // Dropzone.options.newsform = false;
        let token = "{{ csrf_token() }}"
        var max_file = parseInt(10)-parseInt({{count($data->images)}})
        $(function() {
            var myDropzone = new Dropzone("div#dropzoneDragArea", {
                paramName: "file",
                url: "{{ route('administrator.news.store_file') }}",
                // previewsContainer: 'div.dropzone-previews',
                maxFilesize: 1, // MB
                addRemoveLinks: true,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                thumbnailWidth: null,
                thumbnailHeight: null,
                params: {
                    _token: token
                },
                // The setting up of the dropzone
                init: function() {
                    this.on("error", function (file, message) {
                        alert(message);
                    }); 
                    var myDropzone = this;
                    //form submission code goes here
                    $("form[name='newsform']").submit(function(event) {
                        //Make sure that the form isn't actully being sent.
                        $("#btn_submit").css('background', 'gray');
                        $("#btn_submit").html('<i class="fa fa-spinner fa-spin"></i>Saving...');
                        event.preventDefault();
                        URL = $("#newsform").attr('action');
                        // formData = $('#newsform').serialize();
                        var formData = new FormData();
                        formData.append('title', $('#title').val());
                        formData.append('content',  CKEDITOR.instances.ckeditor.getData());
                        formData.append('status', $('#status').val());
                        formData.append('thumbnail', $('input[name=thumbnail]')[0].files[0])
                        $.ajax({
                            type: 'POST',
                            url: URL,
                            headers: {'X-CSRF-TOKEN': token},
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(result){
                                if(result.status == "success"){
                                    // fetch the useid
                                    var news_id = result.news_id;
                                    $("#news_id").val(news_id); // inseting news_id into hidden input field
                                    //process the queue
                                    if (myDropzone.getQueuedFiles().length === 0 && myDropzone.getRejectedFiles().length == 0) {
                                        alert("News edited successfully!!");
                                        window.location.href= "/administrator/news";
                                    }
                                    else if(myDropzone.getRejectedFiles().length > 0){
                                        alert('Please remove file has been rejected!')
                                    }
                                    else{
                                        myDropzone.processQueue();
                                    }
                                }else{
                                    alert(result.message)
                                    $("#submit-all").css('background', '#53e69d');
                                    $("#submit-all").html("Save");
                                    // console.log("error");
                                }
                            }
                        });
                    });
                    this.on('sending', function(file, xhr, formData){
                        let news_id = document.getElementById('news_id').value;
                        formData.append('news_id', news_id);
                    });
                    this.on("thumbnail", function(file, dataUrl) {
                        $('.dz-image').last().find('img').attr({width: '100%', height: '100%'});
                    }),
                    this.on("success", function (file, response) {
                        $('.dz-image img').css({"width":"100%", "height":"100%"});
                    });
                    this.on("queuecomplete", function () {
                        // console.log(myDropzone.getRejectedFiles().length)
                        if(myDropzone.getRejectedFiles().length == 0){
                            alert("News edited successfully!!");
                            window.location.href= "/administrator/news";
                        }
                    });
                }
            });
        });

        $("form[name='deleteForm']").submit(function(event) {
            $("#deleteImage").html('<i class="fa fa-spinner fa-spin"></i>');
        });
    </script>
@endsection
@endsection
