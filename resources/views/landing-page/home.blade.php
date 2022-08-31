@extends('landing-page.landing-page')

@section('content')

@if(count($data_promo) != 0)
<div class="container" style="margin-top: 3%;">
    <div class="row">
        <div id="carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($data_promo as $index => $promo )
                    @if($index == 0)
                    <div class="carousel-item active">
                        @if ( Config::get('app.locale') == 'id')
                        <a href="{{ route('promo', ['id','title'=>str_slug($promo->name),'id'=>$promo->id]) }}">    
                           <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
                        </a>
                        @elseif ( Config::get('app.locale') == 'en')
                        <a href="{{ route('promo', ['en','title'=>str_slug($promo->name),'id'=>$promo->id]) }}">
                            <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image_en&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
                        </a>
                        @else
                        <a href="{{ route('promo', ['id','title'=>str_slug($promo->name),'id'=>$promo->id]) }}">    
                           <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
                        </a>
                        @endif
                    </div>
                    @else
                    <div class="carousel-item">
                        @if ( Config::get('app.locale') == 'id')
                        <a href="{{ route('promo', ['id','title'=>str_slug($promo->name),'id'=>$promo->id]) }}">    
                            <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
                        </a>
                        @elseif ( Config::get('app.locale') == 'en')
                        <a href="{{ route('promo', ['en','title'=>str_slug($promo->name),'id'=>$promo->id]) }}">
                            <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image_en&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
                        </a>
                        @else
                        <a href="{{ route('promo', ['id','title'=>str_slug($promo->name),'id'=>$promo->id]) }}">    
                           <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
                        </a>
                        @endif
                    </div>
                    @endif
                @endforeach
           
            </div>
                 @if(count($data_promo) > 1)
                    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"
                            style="background-image: url('{{ asset('landing-page/2019-05-28/left_arrow.png') }}')"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"
                            style="background-image: url('{{ asset('landing-page/2019-05-28/right_arrow.png') }}')"></span>
                        <span class="sr-only">Next</span>
                    </a>
                @endif
            </div>
        </div>
    
    </div>
  
</div>
@endif

<div class="container">
<div class="row">
    <div class="col-12 p-0">
            @if(count($data_promo) > 1)
                @if ( Config::get('app.locale') == 'id')
                    <a href="{{ url('id','promo_list') }}" class="btn pull-right btn-info btn_all_promo">{{ __("landingpage.home.btn_detail_promo") }}</a>
                @elseif ( Config::get('app.locale') == 'en')
                    <a href="{{ url('en','promo_list') }}" class="btn pull-right btn-info btn_all_promo">{{ __("landingpage.home.btn_detail_promo") }}</a>
                @endif  
            @endif
    </div>
   </div>
</div>

<div class="container" style="overflow: hidden;">

    <div class="row">
        <div class="col-lg-7 mt-5 pt-2">
            <div class="col-md-12">
                <p>
                <h2 style="font-size: 250%; font-weight: 600;" class="eht_title">
                    {{ __("landingpage.home.text_app_system") }}<br>
                    {{ __("landingpage.home.text_the_best") }}
                </h2>
                </p>
            </div>
            <div class="col-md-12">
                <p style="font-size: 19px;">

                    {{ __("landingpage.home.subtext_choosing") }} <br>
                    {{ __("landingpage.home.subtext_now_comes") }}
                </p>
                <br />
                <p>
                    <a style="color: white; text-decoration: none !important;" href="{{ env('URL_CRM','https://crm.em-hr.co.id/').'web/signup' }}" class="btn btn_trial_1">
                        {{ __("landingpage.home.button_create") }}
                    </a>
                    <!-- <button class="btn_trial_1" onclick="form_free_trial()">Create Member</button> -->
                <div class="or" style="width: 252px;height: 25px;">
                    <h3 style="color: #0e9a88; text-align:center;font-size: 15px;">{{ __("landingpage.home.text_or") }}</h3>
                </div>
                <a style="color: white; text-decoration: none !important;" href="{{ env('URL_CRM','https://crm.em-hr.co.id/').'web/login' }}" class="btn btn_login_2">
                    {{ __("landingpage.home.button_login") }}
                </a>
                </p>
            </div>
        </div>
        <div class="col-lg-5 d-flex flex-row justify-content-center">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner carousel-inner-home">
                    <div class="carousel-item active">
                        <br />
                        <img src="{{ asset('landing-page/2019-05-28/play.png') }}" class="img-fluid mx-auto d-block imgplay" data-toggle="modal" data-target="#exampleModalCenter" />
                        <img src="{{ asset('landing-page/2019-05-28/slide1.png') }}" alt="Slide 1" class="img-fluid mx-auto d-block w-100" />
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('landing-page/2019-05-28/slide2.png') }}" alt="Slide 2" class="img-fluid mx-auto d-block w-100" />
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('landing-page/2019-05-28/slide3.png') }}" alt="Los Angeles" class="img-fluid mx-auto d-block w-100" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="container" style="margin-top: 5%;">
        <h2 style="font-size: 250%; font-weight: 600;" class=" text-center">
            <span style="color: #0E9A88;">Em-HR </span>{{ __("landingpage.home.text_give_solution") }}
        </h2>
        <br>
        <div style="margin-left: 50px; margin-right: 50px;">
            @foreach($data as $item)
            <div class="article-list text-center" style="margin-bottom: 30px;">
                <h2 style="font-size: 190%; font-weight: 550;">
                    @if ( Config::get('app.locale') == 'id')
                    {{ $item->name }}
                    @else
                    {{ $item->name_en }}
                    @endif
                </h2>
                <span class="badge badge-pill badge-grey pt-1 pb-1 pl-2 pr-2">{{ isset($item->category) ? $item->category->name : '' }}</span>
                <p>
                    @if ( Config::get('app.locale') == 'id')
                    {!! substr(strip_tags($item->subtitle),0, 200) !!}
                    @else
                    {!! substr(strip_tags($item->subtitle_en),0, 200) !!}
                    @endif
                </p>
                @if ( Config::get('app.locale') == 'id')
                <a href="{{ route('detail', ['id','category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}" class="btn btn-info btn_detail">{{ __("landingpage.home.button_more") }}</a>
                @elseif ( Config::get('app.locale') == 'en')
                <a href="{{ route('detail', ['en', 'category'=>str_slug($item->category->name),'title'=>str_slug($item->name_en),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}" class="btn btn-info btn_detail">{{ __("landingpage.home.button_more") }}</a>
                @else
                <a href="{{ route('detail', ['category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}" class="btn btn-info btn_detail">{{ __("landingpage.home.button_more") }}</a>
                @endif

            </div>
            @endforeach
            <div class="text-center" style="margin-top: 9%;">
                <a href="{{ env('URL_CRM','https://crm.em-hr.co.id/').'web/signup' }}" class="btn btn-info btn_try_free">{{ __("landingpage.home.button_free") }}</a>
            </div>
            <br>
            <h4 class="text-center">
                {{ __("landingpage.home.text_or_lower") }}
            </h4>
            <div class="text-center" style="margin-top: 2%;">
                @if ( Config::get('app.locale') == 'id')
                    <a href="{{ url('id','contact') }}" class="btn btn-info btn_negotiable">{{ __("landingpage.home.button_negotiable") }}</a>
                @elseif ( Config::get('app.locale') == 'en')
                    <a href="{{ url('en','contact') }}" class="btn btn-info btn_negotiable">{{ __("landingpage.home.button_negotiable") }}</a>
                 @else
                    <a href="{{ url('contact') }}" class="btn btn-info btn_negotiable">{{ __("landingpage.home.button_negotiable") }}</a>
                @endif
            </div>
        </div>
    </div>


    <div class="container" style="margin-top: 7;">

        <div style="margin-top: 7%;">
            <div class="row" style="padding-bottom: 5%;">
                <div class="col-md-12" style="text-align: center;">
                    <h1 style="font-size: 250%; font-weight: 600;"><b>{{ __("landingpage.home.text_easy_step") }} <span style="color: #0E9A88;">{{ __("landingpage.home.text_emhr_system") }}</span></b></h1>
                </div>
            </div>

            <div class="row" style="padding-left: 70px; padding-right: 70px;">
                <div class="col-md-4">
                    <img style="margin: 0 35%; width: 40%;" alt="Register for free" src="{{ asset('landing-page/2019-05-28/Register for free.png') }}" />

                    <div class="col-md-12" style="padding: 2% 20% 0 30%; text-align: center; font-size: 18px;">
                        <h5>{{ __("landingpage.home.subtext_register") }}</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <img style="margin: 0 35%; width: 40%;" alt="install emhr" src="{{ asset('landing-page/2019-05-28/install emhr.png') }}" />
                    <div class="col-md-12" style="padding: 2% 20% 0 30%; text-align: center; font-size: 18px;">
                        <h5>Install Em-HR</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <img style="margin: 0 35%; width: 40%;" alt="feel the ease" src="{{ asset('landing-page/2019-05-28/feel the ease.png') }}" />
                    <div class="col-md-12" style="padding: 2% 20% 0 30%; text-align: center; font-size: 18px;">
                        <h5>{{ __("landingpage.home.subtext_feel") }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container container_bottom" style="margin-top: 5%;" id="form_register">

        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-5">
                <img class="img-fluid img-handphone" width="420" style="max-width: 100%; height: auto;" alt="hand with phone" src="{{ asset('landing-page/2019-05-28/slide3.png') }}" />
            </div>
            <div class="col-md-7">
                <br><br><br>
                <div class="row">
                    <h4>{{ __("landingpage.home.subtext_get") }} <span style="color: #0E9A88;">Em-HR Mobile</span>
                        {{ __("landingpage.home.subtext_steps") }}
                    </h4>
                    <p>{{ __("landingpage.home.subtext_download") }}
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!--img style="width: 70%; padding: 0 0 0 20%;" src="{{ asset('landing-page/2019-05-28/playstore dan IOS.png') }}"/-->
                        <a href="https://play.google.com/store/apps/details?id=id.co.empore.emhrmobile">
                            <img style="width: 85%; padding: 0 0 0 10%; margin-left: 5%;" src="{{ asset('landing-page/2019-05-28/Google Play.png') }}" />
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="https://apps.apple.com/id/app/em-hr-mobile/id1550924123">
                            <img style="width: 85%; padding: 0 0 0 10%; margin-left: -25%;" src="{{ asset('landing-page/2019-05-28/AppStore.png') }}" />
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        @if ( Config::get('app.locale') == 'id')
                        <a target="_blank" style="margin-right: 10%;" href="{{route('privacy-policy-mobile-id')}}">{{ __("landingpage.home.subtext_privacy") }}</a>
                        @else
                        <a target="_blank" style="margin-right: 10%;" href="{{route('privacy-policy-mobile')}}">{{ __("landingpage.home.subtext_privacy") }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="text-center" style="height: 240px;">
    <br>
    <h3><span>{{ __("landingpage.home.text_client") }}</span></h3>
    <br>
    <div id="demo" class="carousel slide" data-interval="2000" data-ride="carousel">
        <!-- The slideshow -->
        <div class="carousel-inner no-padding">
            <div class="carousel-item active">
                <img class="img-fluid" src="{{asset('images/clients/citius.png')}}" style="width: 100px; height: 100px;">
                <img class="img-fluid" src="{{asset('images/clients/javandra.png')}}" style="width:200px;height:100px;margin-left: 7px;">
                <img class="img-fluid" src="{{asset('images/clients/aaf.png')}}" style="width:200px;height:65px;margin-left: 7px;">
                <img class="img-fluid" src="{{asset('images/clients/arsari.png')}}" style="width:220px;height:100px;margin-left: 7px;">
            </div>
            <div class="carousel-item">
                <img class="img-fluid" src="{{asset('images/clients/nominomi.jpg')}}" style="width:75px;height:75px;">
                <img class="img-fluid" src="{{asset('images/clients/inti-makmur.png')}}" style="width:75px;height:75px;margin-left: 40px;">
                <img class="img-fluid" src="{{asset('images/clients/glek.jpg')}}" style="width:75px;height:75px;margin-left: 40px;">
                <img class="img-fluid" src="{{asset('images/clients/wadah-titian.jpg')}}" style="width:75px;height: 75px;margin-left: 40px;">
                <img class="img-fluid" src="{{asset('images/clients/wadah-yad.png')}}" style="width:200px;height: 110px;margin-left: 20px;">
                <img class="img-fluid" src="{{asset('images/clients/samitra.png')}}" style="width: 90px; height: 90px;">
            </div>
            <div class="carousel-item">
                <img class="img-fluid img-tribakti" src="{{asset('images/clients/tribhakti.png')}}" style="width:25%;height: 80px; margin-right: 15px;">
                <img class="img-fluid img-tribakti" src="{{asset('images/clients/zebra.jpg')}}" style="width:200px; height: 100px; margin-right: 15px;">
                <img class="img-fluid img-tribakti" src="{{asset('images/clients/amalia.jpg')}}" style="width:200px; height: 100px; margin-right: 15px;">
            </div>
        </div>
    </div>
    <br />
    <br />
</div>
@endsection