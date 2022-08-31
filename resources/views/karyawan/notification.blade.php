@extends('layouts.karyawan')

@section('title', 'Notification')

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
                <h4 class="page-title">Notifications</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="GET" action="" style="float: right; width: 40%;">
                    <div class="form-group">
                        <i class="fa fa-search-plus" style="float: left;font-size: 20px;margin-top: 9px;margin-right: 12px;"></i>
                        <input type="text" name="keyword-karyawan" class="form-control autocomplete-karyawan" style="float:left;width: 80%;margin-right: 5px;" placeholder="Search Employee Here">
                    </div>
                </form>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            
            <div class="col-lg-12 col-sm-12 col-md-12" id="content_search_karyawan"></div>
            <div class="col-lg-12 col-sm-12 col-md-12" style="padding: 0px">
                <div class="col-md-12">
                    <div class="panel panel-themecolor" style="margin-bottom: 20px">
                        <div class="panel-heading" style="background: white; border-bottom:1px solid #0E9A88; color:#0E9A88;margin-bottom: 0px"><i class="fa fa-bell"></i> Notifications</div>
                        <div class="panel-body">
                            <ul class="nav customtab nav-tabs" role="tablist">
                                <li role="presentation" class="{{ !$tab ? 'active' : '' }}"><a href="#notif" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Notifications</span></a></li>
                                @if(date('m-d') == date('m-d', strtotime(auth()->user()->tanggal_lahir)))
                                <li role="presentation" class="{{ $tab == 'myBirthday' ? 'active' : '' }}"><a href="#myBirthday" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">My Birthday</span></a></li>
                                @endif
                                @if(count($birthday) > 0)
                                <li role="presentation" class="{{ $tab == 'otherBirthday' ? 'active' : '' }}"><a href="#otherBirthday" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Other Birthday</span></a></li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade {{ !$tab ? 'active in' : '' }}" id="notif">
                                    <div id="notification">
                                        <h5>There is no notification yet</h5>
                                    </div>
                                    <br />
                                </div>
                                <div role="tabpanel" class="tab-pane fade {{ $tab == 'myBirthday' ? 'active in' : '' }}" id="myBirthday">
                                    @if(date('m-d') == date('m-d', strtotime(auth()->user()->tanggal_lahir)))
                                    <div class="col-md-1" style="width:auto;">
                                        <h5 ><img src="{{ asset('images/Icon_Trumpet_Birthday.png') }}" width="20px;"></h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>
                                            <p><strong>My Birthday </strong></p>
                                            <p style="color:#0E9A88;"> Hi {{auth()->user()->name}}, Happy Birthday - Wish You All The Best</p>
                                            <p>
                                                @if(cek_user_like(auth()->user()->id))
                                                <a href="{{url('/karyawan/birthday/unlike/'.Auth::user()->id)}}" title="Cancel Like" style="color:black;">
                                                <strong> Like({{count_birthday_like(auth()->user()->id)}}) </strong></a> 
                                                @else
                                                <a href="{{url('/karyawan/birthday/like/'.Auth::user()->id)}}" title="Like" style="color:black;">
                                                Like({{count_birthday_like(auth()->user()->id)}})</a> 
                                                @endif
                                                <span>Comment({{count($comment_birthday)}})</span>
                                            </p>
                                        </h5>
                                        <div class="row">
                                            @if(cek_user_comment(auth()->user()->id))
                                            {{--<div class="col-md-1">
                                                @if(empty(auth()->user()->foto))
                                                    @if(auth()->user()->jenis_kelamin == 'Female')
                                                        <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @elseif(auth()->user()->jenis_kelamin == 'Male')
                                                        <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @else 
                                                    <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                    @endif
                                                @else
                                                    <img src="{{ asset('storage/foto/'. auth()->user()->foto) }}" style="width: 60px;float: left;" >
                                                @endif
                                            </div>
                                            <div class="col-md-11">
                                                <form class="form-horizontal" id="form" method="post" action="{{url('/karyawan/birthday/comment/'.$item->id)}}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
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
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <textarea rows="2" class="form-control mytextarea" name="comment"></textarea>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-t-5">Add Comment </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>--}}
                                            @endif
                                            @if($comment_birthday != null)
                                                @foreach($comment_birthday as $item)
                                                <div class="col-md-1">
                                                @if(empty($item->commentBy->foto))
                                                    @if($item->commentBy->jenis_kelamin == 'Female')
                                                        <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @elseif($item->commentBy->jenis_kelamin == 'Male')
                                                        <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @else 
                                                    <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                    @endif
                                                @else
                                                    <img src="{{ asset('storage/foto/'. $item->commentBy->foto) }}" style="width: 60px;float: left;" >
                                                @endif
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="panel panel-themecolor" style="-moz-border-radius: 12px;
                                                        -webkit-border-radius: 12px;
                                                        -ie-border-radius: 12px;
                                                        -opera-border-radius: 12px;
                                                        -chrome-border-radius: 12px;
                                                        border-radius: 12px; overflow: hidden;">
                                                        <div class="panel-body" style="background: #dedede; border:1px solid #dedede;">
                                                            <p>
                                                                <strong>{{$item->commentBy->name}}</strong><span class="pull-right">{{$item->created_at->diffForHumans()}}</span>
                                                            </p>
                                                            <p style="font-size:10px;">
                                                                {{ isset($item->commentBy->structure->position) ? $item->commentBy->structure->position->name:''}}{{ isset($item->commentBy->structure->division) ? ' - '. $item->commentBy->structure->division->name:''}}{{ isset($item->commentBy->structure->title) ? ' - '. $item->commentBy->structure->title->name.' - ':'' }} {{ isset($item->commentBy->cabang->name) ? $item->commentBy->cabang->name : '' }}
                                                            </p>
                                                            <p class="komentar">{!!$item->comment!!}</p>
                                                        </div>
                                                    </div>
                                                    <p>
                                                        @if(cek_user_comment_like($item->id))
                                                        <a href="{{url('/karyawan/birthday/comment/unlike/'.$item->id)}}" title="Cancel Like" style="color:black;">
                                                        <strong> Like({{count($item->birthdayCommentLike)}}) </strong></a> 
                                                        @else
                                                        <a href="{{url('/karyawan/birthday/comment/like/'.$item->id)}}" title="Like" style="color:black;">
                                                        Like({{count($item->birthdayCommentLike)}})</a> 
                                                        @endif
                                                        <span>Reply({{count($item->children)}})</span>
                                                    </p>
                                                    
                                                    <div class="row">
                                                        @if(cek_user_comment_reply($item->user_id, $item->id))
                                                        <div class="col-md-1">
                                                        @if(empty(auth()->user()->foto))
                                                            @if(auth()->user()->jenis_kelamin == 'Female')
                                                                <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                            @elseif(auth()->user()->jenis_kelamin == 'Male')
                                                                <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                            @else 
                                                                <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                            @endif
                                                        @else
                                                            <img src="{{ asset('storage/foto/'. auth()->user()->foto) }}" style="width: 60px;float: left;" >
                                                        @endif
                                                        </div>
                                                        <div class="col-md-11">
                                                            <form class="form-horizontal" id="form" method="post" action="{{url('/karyawan/birthday/comment/reply/'.$item->id)}}" enctype="multipart/form-data">
                                                            {{ csrf_field() }}
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
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <textarea rows="2" class="form-control mytextarea" name="comment"></textarea>
                                                                        <input type="hidden" name="user_id" value="{{$item->user_id}}">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-t-5">Reply</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        @endif
                                                        @if($item->children != null)
                                                            @foreach($item->children as $item)
                                                            <div class="col-md-1">
                                                            @if(empty($item->commentBy->foto))
                                                                @if($item->commentBy->jenis_kelamin == 'Female')
                                                                    <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                                @elseif($item->commentBy->jenis_kelamin == 'Male')
                                                                    <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                                @else 
                                                                <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                                @endif
                                                            @else
                                                                <img src="{{ asset('storage/foto/'. $item->commentBy->foto) }}" style="width: 60px;float: left;" >
                                                            @endif
                                                            </div>
                                                            <div class="col-md-11">
                                                                <div class="panel panel-themecolor" style="-moz-border-radius: 12px;
                                                                    -webkit-border-radius: 12px;
                                                                    -ie-border-radius: 12px;
                                                                    -opera-border-radius: 12px;
                                                                    -chrome-border-radius: 12px;
                                                                    border-radius: 12px; overflow: hidden;">
                                                                    <div class="panel-body" style="background: #dedede; border:1px solid #dedede;">
                                                                        <p>
                                                                            <strong>{{$item->commentBy->name}}</strong><span class="pull-right">{{$item->created_at->diffForHumans()}}</span>
                                                                        </p>
                                                                        <p style="font-size:10px;">
                                                                            {{ isset($item->commentBy->structure->position) ? $item->commentBy->structure->position->name:''}}{{ isset($item->commentBy->structure->division) ? ' - '. $item->commentBy->structure->division->name:''}}{{ isset($item->commentBy->structure->title) ? ' - '. $item->commentBy->structure->title->name.' - ':'' }} {{ isset($item->commentBy->cabang->name) ? $item->commentBy->cabang->name : '' }}
                                                                        </p>
                                                                        <p class="komentar">{!!$item->comment!!}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <p>{{date('d F Y')}}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <hr style="margin: 0px;">
                                    </div>
                                    @endif
                                    <br />
                                </div>
                                <div role="tabpanel" class="tab-pane fade {{ $tab == 'otherBirthday' ? 'active in' : '' }}" id="otherBirthday">
                                    @forelse($birthday as $item)
                                    <div class="col-md-1" style="width:auto;">
                                        <h5 ><img src="{{ asset('images/Icon_Trumpet_Birthday.png') }}" width="20px;"></h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>
                                            <p><strong>{{$item['name']}} </strong>
                                            <i>{{ isset($item->structure->position) ? $item->structure->position->name:''}}{{ isset($item->structure->division) ? ' - '. $item->structure->division->name:''}}{{ isset($item->structure->title) ? ' - '. $item->structure->title->name.' - ':'' }} {{ isset($item->cabang->name) ? $item->cabang->name : '' }}</i></p>
                                            <p style="color:#0E9A88;"> Hi {{$item['name']}}, Happy Birthday - Wish You All The Best</p>
                                            <p>
                                                @if(cek_user_like($item->id))
                                                <a href="{{url('/karyawan/birthday/unlike/'.$item->id)}}" title="Cancel Like" style="color:black;">
                                                <strong> Like({{count_birthday_like($item->id)}}) </strong></a> 
                                                @else
                                                <a href="{{url('/karyawan/birthday/like/'.$item->id)}}" title="Like" style="color:black;">
                                                Like({{count_birthday_like($item->id)}})</a> 
                                                @endif
                                                <span>Comment({{count($item->birthdayComment)}})</span>
                                            </p>
                                        </h5>
                                        <div class="row">
                                            @if(cek_user_comment($item->id))
                                            <div class="col-md-1">
                                                @if(empty(auth()->user()->foto))
                                                    @if(auth()->user()->jenis_kelamin == 'Female')
                                                        <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @elseif(auth()->user()->jenis_kelamin == 'Male')
                                                        <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @else 
                                                    <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                    @endif
                                                @else
                                                    <img src="{{ asset('storage/foto/'. auth()->user()->foto) }}" style="width: 60px;float: left;" >
                                                @endif
                                            </div>
                                            <div class="col-md-11">
                                                <form class="form-horizontal" id="form" method="post" action="{{url('/karyawan/birthday/comment/'.$item->id)}}" enctype="multipart/form-data">
                                                {{ csrf_field() }}
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
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <textarea rows="2" class="form-control mytextarea" name="comment"></textarea>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-t-5">Add Comment </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            @endif
                                            @if($item->birthdayComment)
                                                @foreach($item->birthdayComment as $item)
                                                <div class="col-md-1">
                                                @if(empty($item->commentBy->foto))
                                                    @if($item->commentBy->jenis_kelamin == 'Female')
                                                        <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @elseif($item->commentBy->jenis_kelamin == 'Male')
                                                        <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                    @else 
                                                    <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                    @endif
                                                @else
                                                    <img src="{{ asset('storage/foto/'. $item->commentBy->foto) }}" style="width: 60px;float: left;" >
                                                @endif
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="panel panel-themecolor" style="-moz-border-radius: 12px;
                                                        -webkit-border-radius: 12px;
                                                        -ie-border-radius: 12px;
                                                        -opera-border-radius: 12px;
                                                        -chrome-border-radius: 12px;
                                                        border-radius: 12px; overflow: hidden;">
                                                        <div class="panel-body" style="background: #dedede; border:1px solid #dedede;">
                                                            <p>
                                                                <strong>{{$item->commentBy->name}}</strong><span class="pull-right">{{$item->created_at->diffForHumans()}}</span>
                                                            </p>
                                                            <p style="font-size:10px;">
                                                                {{ isset($item->commentBy->structure->position) ? $item->commentBy->structure->position->name:''}}{{ isset($item->commentBy->structure->division) ? ' - '. $item->commentBy->structure->division->name:''}}{{ isset($item->commentBy->structure->title) ? ' - '. $item->commentBy->structure->title->name.' - ':'' }} {{ isset($item->commentBy->cabang->name) ? $item->commentBy->cabang->name : '' }}
                                                            </p>
                                                            <p class="komentar">{!!$item->comment!!} </p>
                                                        </div>
                                                    </div>
                                                    <p>
                                                        @if(cek_user_comment_like($item->id))
                                                        <a href="{{url('/karyawan/birthday/comment/unlike/'.$item->id)}}" title="Cancel Like" style="color:black;">
                                                        <strong> Like({{count($item->birthdayCommentLike)}}) </strong></a> 
                                                        @else
                                                        <a href="{{url('/karyawan/birthday/comment/like/'.$item->id)}}" title="Like" style="color:black;">
                                                        Like({{count($item->birthdayCommentLike)}})</a> 
                                                        @endif
                                                        <span>Reply({{count($item->children)}})</span>
                                                    </p>
                                                    <div class="row">
                                                        @if(cek_user_comment_reply($item->user_id, $item->id))
                                                        <div class="col-md-1">
                                                        @if(empty(auth()->user()->foto))
                                                            @if(auth()->user()->jenis_kelamin == 'Female')
                                                                <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                            @elseif(auth()->user()->jenis_kelamin == 'Male')
                                                                <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                            @else 
                                                                <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                            @endif
                                                        @else
                                                            <img src="{{ asset('storage/foto/'. auth()->user()->foto) }}" style="width: 60px;float: left;" >
                                                        @endif
                                                        </div>
                                                        <div class="col-md-11">
                                                            <form class="form-horizontal" id="form" method="post" action="{{url('/karyawan/birthday/comment/reply/'.$item->id)}}" enctype="multipart/form-data">
                                                            {{ csrf_field() }}
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
                                                                <div class="form-group">
                                                                    <div class="col-md-12">
                                                                        <textarea rows="2" class="form-control mytextarea" name="comment"></textarea>
                                                                        <input type="hidden" name="user_id" value="{{$item->user_id}}">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <button type="submit" class="btn btn-sm btn-info waves-effect waves-light m-t-5">Reply</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        @endif
                                                        @if($item->children != null)
                                                            @foreach($item->children as $item)
                                                            <div class="col-md-1">
                                                            @if(empty($item->commentBy->foto))
                                                                @if($item->commentBy->jenis_kelamin == 'Female')
                                                                    <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 60px;float: left;" >
                                                                @elseif($item->commentBy->jenis_kelamin == 'Male')
                                                                    <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 60px;float: left;" >
                                                                @else 
                                                                <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" >
                                                                @endif
                                                            @else
                                                                <img src="{{ asset('storage/foto/'. $item->commentBy->foto) }}" style="width: 60px;float: left;" >
                                                            @endif
                                                            </div>
                                                            <div class="col-md-11">
                                                                <div class="panel panel-themecolor" style="-moz-border-radius: 12px;
                                                                    -webkit-border-radius: 12px;
                                                                    -ie-border-radius: 12px;
                                                                    -opera-border-radius: 12px;
                                                                    -chrome-border-radius: 12px;
                                                                    border-radius: 12px; overflow: hidden;">
                                                                    <div class="panel-body" style="background: #dedede; border:1px solid #dedede;">
                                                                        <p>
                                                                            <strong>{{$item->commentBy->name}}</strong><span class="pull-right">{{$item->created_at->diffForHumans()}}</span>
                                                                        </p>
                                                                        <p style="font-size:10px;">
                                                                            {{ isset($item->commentBy->structure->position) ? $item->commentBy->structure->position->name:''}}{{ isset($item->commentBy->structure->division) ? ' - '. $item->commentBy->structure->division->name:''}}{{ isset($item->commentBy->structure->title) ? ' - '. $item->commentBy->structure->title->name.' - ':'' }} {{ isset($item->commentBy->cabang->name) ? $item->commentBy->cabang->name : '' }}
                                                                        </p>
                                                                        <p class="komentar">{!!$item->comment!!}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <p>{{date('d F Y')}}</p>
                                    </div>
                                    <div class="col-md-12">
                                        <hr style="margin: 0px;">
                                    </div>
                                    @empty
                                    <h5>There is no birthday notification yet</h5>
                                    @endforelse
                                    <br />
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
    .hp {
        width: 130px;
        /* position: absolute; */
        bottom: 38px;
        left: 153px;
    }
    @media (min-width: 1600px) {
        .birthday {
            width: 50%;
        }
        .birthday .panel-body {
            padding: 15px;
        }
        .hp {
            width: 78px;
        }
    }
    .type-4 {
        background-color: #992ce3;
        color: black;
    }
    .type-3 {
        background-color: #FA2601;
        color: black;
    }
    .type-2 {
        background-color: #FA8A00;
        color: black;
    }
    .type-1 {
        background-color: #2cabe3;
        color: black;
    }
    .day {
        height: 40px;
        vertical-align: middle;
        font-size: medium;
        font-weight: bold;
    }
    .calendar-month-header td {
        vertical-align: middle !important;
    }
    .zabuto_calendar {
        padding: 10px !important;
        background-color: white;
    }
    #my-holiday, #my-holiday div {
        background-color: white;
        color: #FA2601;
        font-weight: bold;
    }
    #my-holiday div:last-child, #my-holiday div:nth-last-child(2) {
        padding-bottom: 10px;
    }
    .read {
        color: black !important;
    }
    .read:hover {
        color: #259a97 !important;
    }
</style>
@section('footer-script')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.tiny.cloud/1/3p4k8vysm6fxza79bi3a5d0e57k74yc647h2yfqqr3un6rou/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<!-- Example style -->
<link rel="stylesheet" type="text/css" href="https://zabuto.com/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/examples/style.css">

<!-- Zabuto Calendar -->
<script src="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>

<script type="text/javascript">
    firebase.initializeApp({
        databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
    });
    fbDatabase = firebase.database().ref("{{ env('SERVER') . '/' . strtolower(session('company_url')) . '/' . \Auth::user()->id }}")

    fbDatabase.on('value', (snapshot) => {
        if (snapshot.val()) {
            let notification = '';
            snapshot.forEach((childSnapshot) => {
                notification = '<div class="col-md-10">'+
                        '<a onclick="readURL(\'{{ url("") }}'+childSnapshot.val().link+'\', \''+childSnapshot.key+'\', '+(moment(childSnapshot.val().time).format('DD-MM-YYYY') != moment().format('DD-MM-YYYY') && childSnapshot.val().type.includes("birthday"))+')" href="javascript:;" '+(childSnapshot.val().read ? 'class="read"' : '')+'><h5>'+childSnapshot.val().notif+'</h5>'+
                    '</div>'+
                    '<div class="col-md-2 text-right">'+
                        '<p>'+convert(childSnapshot.val().time)+'</p>'+
                    '</div>'+
                    '<div class="col-md-12">'+
                        '<p>'+childSnapshot.val().text+'</p></a>'+
                        '<hr style="margin: 0px;">'+
                    '</div>' + notification;
            });
            $('#notification').html(notification);
        } else {
            $('#notification').html('<h5>There is no notification yet</h5>');
        }
    });

    function readURL(url, key, idle = false) {
        fbDatabase.child(key).update({'/read': 1});

        if (!idle) {
            window.open(url, '_blank');
        }
    }

    function convert(time) {
        if (moment.duration(moment().diff(moment(time))).asMinutes() < 1) {
            return 'just now';
        } else if (moment.duration(moment().diff(moment(time))).asMinutes() < 2) {
            return 'a minute ago';
        } else if (moment.duration(moment().diff(moment(time))).asMinutes() < 60) {
            return Math.floor(moment.duration(moment().diff(moment(time))).asMinutes()) + ' minutes ago';
        } else if (moment.duration(moment().diff(moment(time))).asHours() < 2) {
            return 'an hour ago';
        } else if (moment.duration(moment().diff(moment(time))).asHours() < 24) {
            return Math.floor(moment.duration(moment().diff(moment(time))).asHours()) + ' hours ago';
        } else if (moment.duration(moment().diff(moment(time))).asHours() < 48) {
            return 'yesterday';
        } else {
            return moment(time).format('DD-MM-YYYY HH:mm:ss')
        }
    }

    $(".autocomplete-karyawan" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
                url: "{{ route('ajax.get-karyawan') }}",
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
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : ui.item.id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    data = data.data;

                    var el = '<div class="panel panel-themecolor" style="position:relative;"><div class="panel-body"><i class="ti-close" onclick="tutup_ini(this)" style=" position: absolute;right: 36px;top: 18px;color: red;cursor:pointer;"></i><div class="table-responsive">';
                        el += '<table class="table table-striped">';
                        el += '<thead><tr>';
                        el += '<th>NIK</th>';
                        el += '<th>NAMA</th>';
                        el += '<th>TELEPON</th>';
                        el += '<th>EMAIL</th>';
                        el += '<th>EXT</th>';
                        el += '<th>JOB RULE</th>';
                        el += '</tr></thead>';

                        el += '<tbody><tr>';
                        el += '<td>'+ data.nik +'</td>';
                        el += '<td>'+ data.name +'</td>';
                        el += '<td>'+ (data.telepon == null ? '' : data.telepon ) +'</td>';
                        el += '<td>'+ (data.email == null ? '' : data.email) +'</td>';
                        el += '<td>'+ (data.ext ==null ? '' : data.ext) +'</td>';
                        el += '<td>'+ data.position +'</td>';
                        el += '</tr></tbody>';
                        el += '</table>';
                        el += '</div></div></div>'

                        $("#content_search_karyawan").prepend(el);

                    setTimeout(function(){
                        $(".autocomplete-karyawan").val(" ");

                        $(".autocomplete-karyawan").triggerHandler("focus");

                    }, 500);
                }
            });

            $(".autocomplete-karyawan" ).val("");
        }
    }).on('focus', function () {
        $(this).autocomplete("search", "");
    });

    function tutup_ini(el) {
        $(el).parent().parent().hide("slow");
    }
    $('.komentar').each(function() { 
        $(this).text(($(this).text().replace(/\\u([0-9A-F]{4})/ig, (_, g) => String.fromCharCode(`0x${g}`))))
    });

    tinymce.init({
        selector: ".mytextarea",
        plugins: "emoticons autoresize",
        toolbar: "emoticons",
        toolbar_location: "bottom",
        menubar: false,
        statusbar: false
    });
        
    
</script>

@endsection

@endsection
