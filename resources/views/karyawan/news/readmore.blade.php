@extends('layouts.karyawan')

@section('title', $title)

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
                <h4 class="page-title">Dashboard</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">{{ $title }}</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="white-box">
                    <h2 class="box-title" style="margin-bottom:0;">{{ $data->title }}</h2>
                    <p><small>{{ $data->created_at }}</small></p>
                    <hr /> 
                    
                    @if(!empty($data->image) && file_exists(public_path('storage/'.$section.'/').$data->image))
                        @if($data->images != null && count($data->images) < 2)
                        <p style="text-align: center;">
                            <img style="max-width: 100%; max-height:500px;" src="{{ asset('storage/'.$section.'/'. $data->image) }}" />
                        </p>
                        @elseif($data->images != null && count($data->images) >= 2)
                        <p style="text-align: center;">
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    @foreach($data->images as $key => $image)
                                    <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
                                    @endforeach
                                </ol>
                            
                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    @foreach($data->images as $key => $image)
                                    <div class="item {{ $key == 0 ? 'active' : '' }}">
                                        <img style="max-width: 100%; max-height:500px;" src="{{ asset('storage/'.$section.'/'. $image->image) }}" />
                                    </div>
                                    @endforeach
                                </div>
                            
                                <!-- Left and right controls -->
                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </p>
                        @else
                        <p style="text-align: center;">
                            <img style="max-width: 100%; max-height:500px;" src="{{ asset('storage/'.$section.'/'. $data->image) }}" />
                        </p>
                        @endif
                    @elseif(empty($data->image) && $section == 'news')
                        @if($data->images != null && count($data->images) > 1)
                        <p style="text-align: center;">
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    @foreach($data->images as $key => $image)
                                    <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
                                    @endforeach
                                </ol>
                            
                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    @foreach($data->images as $key => $image)
                                    <div class="item {{ $key == 0 ? 'active' : '' }}">
                                        <img style="max-width: 100%; max-height:500px;" src="{{ asset('storage/'.$section.'/'. $image->image) }}" />
                                    </div>
                                    @endforeach
                                </div>
                            
                                <!-- Left and right controls -->
                                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </p>
                        @elseif($data->images != null && count($data->images) == 1)
                        @foreach($data->images as $key => $image)
                        <p style="text-align: center;">
                            <img style="max-width: 100%; max-height:500px;" src="{{ asset('storage/'.$section.'/'. $image->image) }}" />
                        </p>
                        @endforeach
                        @else(!empty($data->thumbnail) && file_exists(public_path('storage/'.$section.'/').$data->thumbnail))
                        <p style="text-align: center;">
                            <img style="max-width: 100%; max-height:500px;" src="{{ asset('storage/'.$section.'/'. $data->thumbnail) }}" />
                        </p>
                        @endif
                    @elseif(!empty($data->thumbnail) && file_exists(public_path('storage/'.$section.'/').$data->thumbnail))
                        <p style="text-align: center;">
                            <img style="max-width: 100%; max-height:500px;" src="{{ asset('storage/'.$section.'/'. $data->thumbnail) }}" />
                        </p>
                    @endif
                    
                    {!! $data->content !!}

                    @if(isset($data->files) && count($data->files) > 1)
                        @foreach($data->files as $key => $file)
                            @if(!empty($file->file) && file_exists(public_path('storage/'.$section.'/').$file->file))
                                <img onclick="show_image('{{ $file->file }}')" style="margin: 20px 20px 0 0;" src="{{ asset('images/'.(explode('.', $file->file)[count(explode('.', $file->file)) - 1] == 'pdf' ? 'pdf' : 'image').'.png') }}" />
                            @endif
                        @endforeach
                    @elseif(isset($data->file))
                        @if(!empty($data->file) && file_exists(public_path('storage/'.$section.'/').$data->file))
                            <img onclick="show_image('{{ $data->file }}')" style="margin: 20px 20px 0 0;" src="{{ asset('images/'.(explode('.', $data->file)[count(explode('.', $data->file)) - 1] == 'pdf' ? 'pdf' : 'image').'.png') }}" />
                        @endif
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <div class="row">
                    @php($total = 0)
                    @foreach($news_list_right as $item)
                        @if($item->id != $data->id)
                            @if(++$total < 8)
                            <div class="col-lg-12 col-sm-12 col-xs-12">
                                <div class="white-box" style="padding: 7px 4px 7px 8px;margin-bottom:8px;">
                                    <div class="col-md-4" style="padding:0;">
                                        @if(!empty($item->thumbnail) && file_exists(public_path('storage/'.$section.'/').$item->thumbnail))
                                        <a href="{{ route('karyawan.'.$section.'.readmore', $item->id) }}">
                                            <img src="{{ asset('storage/'.$section.'/'. $item->thumbnail) }}" style="width: 100%; height:90px; margin-top: 2px;" />
                                        </a>
                                        @endif
                                    </div>
                                    <div class="col-md-8" style="padding-right:0;">
                                        <h4 style="padding-bottom:0; margin-bottom:0;padding-top:0;margin-top:0;"><a href="{{ route('karyawan.'.$section.'.readmore', $item->id) }}">{!! $item->title !!}</a></h4>
                                        <p style="margin-bottom:0;"><small>{{ date('d F Y H:i', strtotime($item->created_at)) }}</small></p>
                                        <p>
                                            {!! substr(strip_tags($item->content),0, 40) !!}
                                        </p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            @endif
                        @endif
                    @endforeach
                    </div>
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
<div id="modal_file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <div id="modalcontent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
    .modal-dialog {
        width: 1000px;
    }
</style>
<script>
    function show_image(img) {
        var images = ['png','gif','jpg','jpeg'];
        var ext = img.split('.').pop().toLowerCase();
        if(ext === 'pdf') {
            $('#modalcontent').html('<embed src="{{ asset('storage/'.$section.'/') }}/'+ img +'" frameborder="0" width="100%" height="700px">');
            $('#modal_file').modal('show');
        } else if(images.includes(ext)) {
            $('#modalcontent').html('<img src="{{ asset('storage/'.$section.'/') }}/'+ img +'" style = \'width: 100%;\' />');
            $('#modal_file').modal('show');
        } else {
            alert("Filetype is not supported!");
        }
    }
</script>
@endsection
