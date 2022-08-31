@extends('landing-page.landing-page')

@section('content')

<div class="container">

    <!-- {{ Session::put('isHaveUser', false); }} -->

    <div class="row mt-5 mb-4 pl-3 align-items-center">
        <div class="card" style="width: 100px; border-radius: 10px;">
            <div class="card-body p-0">
                <img src="{{ env('URL_CRM').'web/image?model=product.template&field=image_1920&id='.$data_feature->id }}" width="100%" />
            </div>
        </div>
       <div class="col">
       <h3 style="font-size: 180%; font-weight: 800;" class="mb-0">
            {{ $data_feature->name }}
        </h3>
        <span>
            @if ( Config::get('app.locale') == 'id')
            {{ $data_feature->description_sale_id }}
            @else
            {{ $data_feature->description_sale }}
            @endif
        </span>
       </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <h2 style="font-size: 240%; font-weight: 800;">
                @if ( Config::get('app.locale') == 'id')
                {{ $data->name }}
                @else
                {{ $data->name_en }}
                @endif
            </h2>
            <p>
                {{ date('l, d F Y', strtotime($data->create_date)) }}
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mr-0 pr-0">
            <div class="image">
                <img style="width: 100%; height: 394px;" src="{{ env('URL_CRM').'web/image?model=emhr.blog&field=image_detail&id='.$data->id }}" alt="" class="img-fluid mx-auto d-block">
            </div>
            <br>
            <span>
                @if ( Config::get('app.locale') == 'id')
                {!! $data->body !!}
                @else
                {!! $data->body_en !!}
                @endif
            </span>
            <div class="sharebuttons btn-group btn-group-lg showtext inline">
                <a href="https://wa.me/?text={{ Request::url(); }}" class="btn btn-whatsapp" style="padding-top: 5px;" target="_blank"><img class="p-0 m-0" src="{{ asset('images/wa.png') }}" width="18" alt="..." /></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url(); }}" class="btn btn-facebook" style="padding-left: 19px; padding-right: 19px;" target="_blank"><i class="fa fa-facebook"></i></a>
                @if ( Config::get('app.locale') == 'id')
                    <a href="https://t.me/share/url?url={{ Request::url(); }}&text={{ $data->name; }}" class="btn btn-telegram" style="padding-top: 5px;" target="_blank"><img class="p-0 m-0" src="{{ asset('images/telegram.png') }}" width="20" alt="..." /></a>
                @elseif( Config::get('app.locale') == 'en')
                    <a href="https://t.me/share/url?url={{ Request::url(); }}&text={{ $data->name_en; }}" class="btn btn-telegram" style="padding-top: 5px;" target="_blank"><img class="p-0 m-0" src="{{ asset('images/telegram.png') }}" width="20" alt="..." /></a>
                @else
                    <a href="https://t.me/share/url?url={{ Request::url(); }}&text={{ $data->name; }}" class="btn btn-telegram" style="padding-top: 5px;" target="_blank"><img class="p-0 m-0" src="{{ asset('images/telegram.png') }}" width="20" alt="..." /></a>
                @endif
                @if ( Config::get('app.locale') == 'id')
                    <a href="https://www.linkedin.com/sharing/share-offsite?mini=true&url={{ Request::url(); }}/&title={{ $data->name }}&summary=" target="_blank" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
                @elseif( Config::get('app.locale') == 'en')
                    <a href="https://www.linkedin.com/sharing/share-offsite?mini=true&url={{ Request::url(); }}/&title={{ $data->name_en }}&summary=" target="_blank" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
                @else
                    <a href="https://www.linkedin.com/sharing/share-offsite?mini=true&url={{ Request::url(); }}/&title={{ $data->name }}&summary=" target="_blank" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
                @endif
                @if ( Config::get('app.locale') == 'id')
                    <a href="https://twitter.com/intent/tweet?text={{ $data->name }}&url={{ Request::url(); }}" class="btn btn-twitter" target="_blank"><i class="fa fa-twitter"></i></a>
                @elseif( Config::get('app.locale') == 'en')
                    <a href="https://twitter.com/intent/tweet?text={{ $data->name_en }}&url={{ Request::url(); }}" class="btn btn-twitter" target="_blank"><i class="fa fa-twitter"></i></a>
                @else
                    <a href="https://twitter.com/intent/tweet?text={{ $data->name }}&url={{ Request::url(); }}" class="btn btn-twitter" target="_blank"><i class="fa fa-twitter"></i></a>
                @endif
            </div>
            <section class="mt-3 mb-5">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" class="get_session" value="{{ session('user.isSessionUser') }}" />
                        <input type="hidden" class="get_session_id" value="{{ session('user.id_user') }}" />
                        <input type="hidden" class="get_session_name" value="{{ session('user.name') }}" />
                        <input type="hidden" class="get_session_email" value="{{ session('user.email') }}" />
                        <input type="hidden" class="get_session_phone" value="{{ session('user.phone') }}" />
                        <input type="hidden" class="get_session_position" value="{{ session('user.position') }}" />


                        <!-- Comment form-->
                        <h5 style="font-size: 120%; font-weight: 730;">
                            {{ __("landingpage.detail.subtext_comment") }}
                        </h5>
                        <form>
                            <input type="hidden" name="blog_id" value="{{ $data->id }}" />
                            <textarea style="resize: none;" name="name" required class="form-control" rows="3" placeholder="{{ __('landingpage.detail.subbtext_typing_comment') }}"></textarea>
                            <br>
                            <input type="submit" class="btn btn-submit-comment btn-info float-right" value="{{ __('landingpage.detail.button_add_commentar') }}" />
                        </form>
                        <!-- Registration Form -->
                        <div class="registration">
                            <h5 style="font-size: 120%; font-weight: 700;">
                                Login atau register terlebih dahulu
                            </h5>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link pt-1 pb-1 active" id="pills-login-tab" data-toggle="pill" href="#pills-login" role="tab" aria-controls="pills-login" aria-selected="true">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link pt-1 pb-1" id="pills-google-tab" href="{{ url('google') }}" role="tab" aria-controls="pills-google" aria-selected="false">Login with Google</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link pt-1 pb-1" id="pills-register-tab" data-toggle="pill" href="#pills-register" role="tab" aria-controls="pills-register" aria-selected="false">Register</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane mt-3 fade show active" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab">
                                    <form>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input type="email" class="form-control email_login" id="email" name="email" placeholder="Email">
                                            </div>
                                        </div>
                                        <input type="submit" class="btn submit_data_login btn-sm btn-info" value="Submit" />
                                    </form>
                                </div>
                                <div class="tab-pane mt-3 fade" id="pills-google" role="tabpanel" aria-labelledby="pills-google-tab">
                                    <a href="{{ url('google') }}" class="btn btn-primary">
                                        Login with Google
                                    </a>
                                </div>
                                <div class="tab-pane fade mt-3" id="pills-register" role="tabpanel" aria-labelledby="pills-register-tab">
                                    <form>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input required type="text" class="form-control fullname" id="fullname" name="fullname" placeholder="Nama Lengkap">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <input required type="email" class="form-control email_regis" id="email_regis" name="email_regis" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <input required type="number" class="form-control phone" id="phone" name="phone" placeholder="Nomor Hp">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <select name="position" class="custom-select position">
                                                    <option value="Owner">Owner</option>
                                                    <option value="HRD / Finance">HRD / Finance</option>
                                                    <option value="IT">IT</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="submit" class="btn submit_data btn-sm btn-info" value="Submit" />
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Registration Form -->
                        <!-- Comment with nested comments-->
                        <br><br>
                        <div class="row ml-2 mt-2">
                            <h5 class="mb-3" style="font-size: 120%; font-weight: 730;">
                                {!! $comment_count !!} {{ __("landingpage.detail.subtext_comment") }}
                            </h5>
                            <div class="col user_session">
                                <span class="pull-right" style="font-weight: bold;">{{ session('user.name') }}</span>
                                <div class="flex-shrink-0 pull-right mr-1"><img width="25" class="rounded-circle" src="{{ asset('images/avatar.png') }}" alt="..." /></div>
                            </div>
                        </div>
                        @include('landing-page.comments', ['comments' => $data_comments, 'blog_id' => $data->id])
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4 pl-0 ml-0 pr-0">
            <div class="col-lg-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-body p-0 m-0 pl-3 pt-3 pr-2">
                        <h5 style="font-size: 120%; font-weight: 730;" class="text-center">
                            {{ __("landingpage.detail.subtext_new_article") }}
                        </h5>
                        @if(count($datas) < 1)
                            @include('landing-page.empty_layout', ['isFrom' => 'detail','title' => 'Data Artikel Kosong', 'desc' => 'Opps, artikel terbaru kosong nih. Tunggu aja yaa kami akan membuat artikel terbaru buat kamu.'])
                        @else
                            @foreach($datas as $item)
                                <div class="row">
                                    <div class="col-md-4 pl-2 pr-0">
                                        <img src="{{ env('URL_CRM').'web/image?model=emhr.blog&field=image_detail&id='.$item->id }}" style="width: 100%; margin-top: 2px; height: 65px;" />
                                    </div>
                                    <div class="col-md-8 pl-2">
                                        <h5 class="m-0 p-0" style="font-size: 15px; display: -webkit-box; max-width: 170px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            @if ( Config::get('app.locale') == 'id')
                                            <a href="{{ route('detail', ['id','category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}">
                                                @if ( Config::get('app.locale') == 'id')
                                                {!! $item->name !!}
                                                @else
                                                {!! $item->name_en !!}
                                                @endif
                                            </a>
                                            @elseif ( Config::get('app.locale') == 'en')
                                            <a href="{{ route('detail', ['en','category'=>str_slug($item->category->name),'title'=>str_slug($item->name_en),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}">
                                                @if ( Config::get('app.locale') == 'id')
                                                {!! $item->name !!}
                                                @else
                                                {!! $item->name_en !!}
                                                @endif
                                            </a>
                                            @else
                                            <a href="{{ route('detail', ['category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}">
                                                @if ( Config::get('app.locale') == 'id')
                                                {!! $item->name !!}
                                                @else
                                                {!! $item->name_en !!}
                                                @endif
                                            </a>
                                            @endif
                                        </h5>
                                        <span class="mt-1" style="font-size: 13px; display: -webkit-box; max-width: 210px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            @if ( Config::get('app.locale') == 'id')
                                            {!! substr(strip_tags($item->body),0, 200) !!}
                                            @else
                                            {!! substr(strip_tags($item->body_en),0, 200) !!}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col mt-1 mb-1">
                                        <span class="badge badge-pill badge-grey pt-1 pb-1 pl-2 pr-2">{{ isset($item->category) ? $item->category->name : '' }}</span>
                                    </div>
                                </div>
                                <hr style="margin-top: 2px;" />
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(".submit_data").click(function(e) {

        e.preventDefault();

        var fullname = $(".fullname").val();
        var email_regis = $(".email_regis").val();
        var phone = $(".phone").val();
        var position = $(".position").val();

        if (!$.trim(fullname) || !$.trim(email_regis) || !$.trim(phone) || !$.trim(position)) {
            alert('{{ __("landingpage.detail.subtext_required_data") }}');
        } else if (!isEmail(email_regis)) {
            alert('{{ __("landingpage.detail.subtext_invalid_email") }}');
        } else {
            console.log(fullname, email_regis, phone, position);
            $.ajax({
                method: 'POST',
                url: "{{ route('comment.saveUser') }}",
                data: {
                    name: fullname,
                    email: email_regis,
                    phone: phone,
                    position: position,
                    _token: "{{csrf_token()}}",
                },
                success: function(message) {
                    console.log(message);
                    console.log(message.message);
                    if (message.status == "failed") {
                        alert(message.message)
                    } else {
                        location.reload(true);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }


    });

    $(".submit_data_login").click(function(e) {

        e.preventDefault();

        var email_login = $(".email_login").val();

        if (!$.trim(email_login)) {
            alert('{{ __("landingpage.detail.subtext_required_email") }}');
        } else if (!isEmail(email_login)) {
            alert('{{ __("landingpage.detail.subtext_invalid_email") }}');
        } else {
            $.ajax({
                method: 'POST',
                url: "{{ route('comment.loginUser') }}",
                data: {
                    email: email_login,
                    _token: "{{csrf_token()}}",
                },
                success: function(data) {
                    if (data) {
                        location.reload(true);
                    } else {
                        alert('{{ __("landingpage.detail.subtext_not_have_account") }}')
                    }
                },
                error: function(error) {
                    alert('{{ __("landingpage.detail.subtext_not_have_account") }}')
                }
            });
        }

    });

    var isSession = $(".get_session").val();
    var isSessionId = $(".get_session_id").val();
    var isSessionName = $(".get_session_name").val();
    var isSessionEmail = $(".get_session_email").val();
    var isSessionPhone = $(".get_session_phone").val();
    var isSessionPosition = $(".get_session_position").val();
    if (isSession == 1) {
        $(".btn-submit-comment").show(200);
        $(".registration").hide(200);
        $(".user_session").show(200);
        $(".form-reply").show(200);
    } else {
        $(".btn-submit-comment").hide(200);
        $(".registration").show(200);
        $(".user_session").hide(200);
        $(".form-reply").hide(200);
    }

    $(".btn-submit-comment").click(function(e) {

        e.preventDefault();

        var name = $("textarea[name=name]").val();
        var blog_id = $("input[name=blog_id]").val();

        if (!$.trim(name)) {
            alert('{{ __("landingpage.detail.subtext_required_comment") }}');
        } else {
            $.ajax({
                method: 'POST',
                url: "{{ route('comment.add') }}",
                data: {
                    name: name,
                    blog_id: blog_id,
                    is_publish: true,
                    user_id_comment: isSessionId,
                    user_name_comment: isSessionName,
                    user_email_comment: isSessionEmail,
                    user_phone_comment: isSessionPhone,
                    user_position_comment: isSessionPosition,
                    _token: "{{csrf_token()}}",
                },
                success: function(data, comment) {
                    if (data) {
                        location.reload(true);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

    });

    $(".btn-reply-comment").click(function(e) {

        e.preventDefault();

        let form = $(this).closest('.child_comment');
        let name_reply = form.find("input[name=name_reply]").val();
        let parent_id = form.find("input[name=parent_id]").val();

        if (!$.trim(name_reply)) {
            alert('{{ __("landingpage.detail.subtext_required_comment") }}');
        } else {
            $.ajax({
                method: 'POST',
                url: "{{ route('reply.add') }}",
                data: {
                    name: name_reply,
                    parent_id: parent_id,
                    user_id_comment: isSessionId,
                    user_name_comment: isSessionName,
                    user_email_comment: isSessionEmail,
                    user_phone_comment: isSessionPhone,
                    user_position_comment: isSessionPosition,
                    is_publish: true,
                    _token: "{{csrf_token()}}",
                },
                success: function(data) {
                    console.log(data);
                    if (data) {
                        location.reload(true);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

    });

    // let form = $(this).closest('.child_comment');
    // let text_reply = form.find("a[class=text_reply]");

    // $(".text_reply").click(function(e) {
    //     if ($(".form-reply").is(":visible")) {
    //         $(".form-reply").hide();
    //     } else {
    //         $(".form-reply").show();
    //     }
    //     //don't follow the link (optional, seen as the link is just an anchor)
    //     return false;
    // });

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
</script>

@endsection