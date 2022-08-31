@extends('landing-page.landing-page')

@section('content')
<div class="container">

    <div class="row" style="margin-top: 5%; margin-bottom: 3%;">
        <div class="col-md-12" style="text-align: center;">
            <h2 style="font-size: 250%; font-weight: 600;">{{ __("landingpage.feature.text_can_you_get") }}</h2>
            <h2 style="font-size: 250%; font-weight: 600; color: #0E9A88;">{{ __("landingpage.feature.text_in_system") }}</h2>
        </div>
    </div>

    <div class="row" style="padding-left: 40px; padding-right: 40px;">
        <div class="col-md-12">
            <div class="row">
                @foreach($data as $item)
                <div class="col-xs-3 col-sm-3 col-md-3 padding-0">
                    <div class="card cardproduct shadow border-0 mb-3" style="height: 340px;">
                        <div class="card-body text-center pl-2 pr-2">
                            @if ( Config::get('app.locale') == 'id')
                            <a style="color: inherit; text-decoration: none;" href="{{ route('feature_detail', ['id','category'=>str_slug($item->name),'id_feature'=>$item->id]) }}">
                                <img src="{{ env('URL_CRM').'web/image?model=product.template&field=image_1920&id='.$item->id }}" alt="" class="img-fluid mb-3 img_features" style="width: 130px;" />
                                <h4 class="card-title font-weight-bold">
                                    {{ $item->name }}
                                </h4>
                                <p class="card-text" style="display: -webkit-box; max-width: 210px; -webkit-line-clamp:5; -webkit-box-orient: vertical; overflow: hidden;">
                                    @if ( Config::get('app.locale') == 'id')
                                    {{ $item->description_sale_id }}
                                    @else
                                    {{ $item->description_sale }}
                                    @endif
                                </p>
                            </a>
                            @elseif ( Config::get('app.locale') == 'en')
                            <a style="color: inherit; text-decoration: none;" href="{{ route('feature_detail', ['en','category'=>str_slug($item->name),'id_feature'=>$item->id]) }}">
                                <img src="{{ env('URL_CRM').'web/image?model=product.template&field=image_1920&id='.$item->id }}" alt="" class="img-fluid mb-3 img_features" style="width: 130px;" />
                                <h4 class="card-title font-weight-bold">
                                    {{ $item->name }}
                                </h4>
                                <p class="card-text" style="display: -webkit-box; max-width: 210px; -webkit-line-clamp: 5; -webkit-box-orient: vertical; overflow: hidden;">
                                    @if ( Config::get('app.locale') == 'id')
                                    {{ $item->description_sale_id }}
                                    @else
                                    {{ $item->description_sale }}
                                    @endif
                                </p>
                            </a>
                            @else
                            <a style="color: inherit; text-decoration: none;" href="{{ route('feature_detail', ['category'=>str_slug($item->name),'id_feature'=>$item->id]) }}">
                                <img src="{{ env('URL_CRM').'web/image?model=product.template&field=image_1920&id='.$item->id }}" alt="" class="img-fluid mb-3 img_features" style="width: 130px;" />
                                <h4 class="card-title font-weight-bold">
                                    {{ $item->name }}
                                </h4>
                                <p class="card-text">
                                    @if ( Config::get('app.locale') == 'id')
                                    {{ $item->description_sale_id }}
                                    @else
                                    {{ $item->description_sale }}
                                    @endif
                                </p>
                            </a>
                            @endif

                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
    </div>

    <div class="container text-center mt-5 pt-4" style="padding-left: 50px; padding-right: 50px;">
        <div class="row">
            <div class="col-md-12" style="text-align: center; margin-bottom: 20px;">
                <h1 style="font-size: 250%; font-weight: 600;">{{ __("landingpage.feature.text_why_use") }} <span style="color: #0E9A88;">{{ __("landingpage.feature.text_system") }}</span></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <img style="margin: 0 25%; width: 30%;" src="{{ asset('landing-page/2019-05-28/simple-and-easy.png') }}" />

                <div class="col-md-12" style="padding: 5% 15% 5% 5%; text-align: center; font-size: 18px;">
                    <h3>{{ __("landingpage.feature.subtext_simple") }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <img style="margin: 0 30%;  width: 40%;" src="{{ asset('landing-page/2019-05-28/Complete Features.png') }}" />

                <div class="col-md-12" style="padding: 5% 10% 5% 10%; text-align: center; font-size: 18px;">
                    <h3>{{ __("landingpage.feature.subtext_completes") }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <img style="margin: 0 30%;  width: 53%;" src="{{ asset('landing-page/2019-05-28/Affordable Prices.png') }}" />

                <div class="col-md-12" style="padding: 5% 10% 5% 20%; text-align: center; font-size: 18px;">
                    <h3>{{ __("landingpage.feature.subtext_affordable") }}</h3>
                </div>
            </div>
        </div>
        <br>
        <br>
        <a href="{{ env('URL_CRM','https://crm.em-hr.co.id/').'web/signup' }}" class="btn btn-info btn_try_free">{{ __("landingpage.feature.button_free") }}</a>
    </div>

    <div class="container text-center mt-5 pt-3 mb-5" style="padding-left: 50px; padding-right: 50px;">
        @foreach($data_blog as $item)
        <div class="list_feature mb-5">
            <div class="row">
                <div class="col-md-12" style="text-align: center; margin-bottom: 10px;">
                    <h1 style="font-size: 220%; font-weight: 800;">
                        @if ( Config::get('app.locale') == 'id')
                        {{ $item->name }}
                        @else
                        {{ $item->name_en }}
                        @endif
                    </h1>
                    <span class="badge badge-pill badge-grey pt-1 pb-1 pl-2 pr-2">{{ isset($item->category) ? $item->category->name : '' }}</span>
                </div>
            </div>
            <img class="img-responsive" style="width: 80%; height: 220px;" src="{{ env('URL_CRM').'web/image?model=emhr.blog&field=image&id='.$item->id }}">
            <p class="mt-3">
                @if ( Config::get('app.locale') == 'id')
                    {!! substr(strip_tags($item->subtitle),0, 200) !!}
                @else
                    {!! substr(strip_tags($item->subtitle_en),0, 200) !!}
                @endif
            </p>
            @if ( Config::get('app.locale') == 'id')
            <a href="{{ route('detail', ['id', 'category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}" class="btn btn-info btn_detail">{{ __("landingpage.feature.button_more_detail") }}</a>
            @elseif ( Config::get('app.locale') == 'en')
            <a href="{{ route('detail', ['en', 'category'=>str_slug($item->category->name),'title'=>str_slug($item->name_en),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}" class="btn btn-info btn_detail">{{ __("landingpage.feature.button_more_detail") }}</a>
            @else
            <a href="{{ route('detail', ['category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}" class="btn btn-info btn_detail">{{ __("landingpage.feature.button_more_detail") }}</a>
            @endif
        </div>
        @endforeach
    </div>

    <div class="row mb-5" style="padding-left: 40px; padding-right: 40px;">
        <h3 style="font-size: 180%; font-weight: 800;">{{ __("landingpage.feature.subtext_all_article") }}</h3>
        <div class="col-md-12">
            <div class="row">
                @foreach($data_blog_all as $item)
                <div class="col-xs-3 col-sm-3 col-md-3 padding-0  mt-3">
                    <div class="card card-blog-feature shadow border-0" style="height: 340px;">
                        <div class="card-body p-0">
                            @if (Config::get('app.locale') == 'id')
                            <a style="color: inherit; text-decoration: none;" href="{{ route('detail', ['id', 'category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}">
                                <img style="width: 100%; height: 48%;" src="{{ env('URL_CRM').'web/image?model=emhr.blog&field=image_detail&id='.$item->id }}">
                                <div class="p-2">
                                    <span style="font-weight:bold; font-size: 16px; display: -webkit-box; max-width: 210px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        @if ( Config::get('app.locale') == 'id')
                                        {!! $item->name !!}
                                        @else
                                        {!! $item->name_en !!}
                                        @endif
                                    </span>
                                    <span class="mt-1" style="font-size: 13px; display: -webkit-box; max-width: 210px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        @if ( Config::get('app.locale') == 'id')
                                        {!! substr(strip_tags($item->body),0, 200) !!}
                                        @else
                                        {!! substr(strip_tags($item->body_en),0, 200) !!}
                                        @endif
                                    </span>
                                    <span class="badge badge-pill badge-grey mt-2 pt-1 pb-1 pl-2 pr-2" style="display: -webkit-box; max-width: 180px; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">{{ isset($item->category) ? $item->category->name : '' }}</span>
                                </div>
                            </a>
                            @elseif ( Config::get('app.locale') == 'en')
                            <a style="color: inherit; text-decoration: none;" href="{{ route('detail', ['en', 'category'=>str_slug($item->category->name),'title'=>str_slug($item->name_en),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}">
                                <img style="width: 100%; height: 48%;" src="{{ env('URL_CRM').'web/image?model=emhr.blog&field=image_detail&id='.$item->id }}">
                                <div class="p-2">
                                    <span style="font-weight:bold; font-size: 16px; display: -webkit-box; max-width: 210px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        @if ( Config::get('app.locale') == 'id')
                                        {!! $item->name !!}
                                        @else
                                        {!! $item->name_en !!}
                                        @endif
                                    </span>
                                    <span class="mt-1" style="font-size: 13px; display: -webkit-box; max-width: 210px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        @if ( Config::get('app.locale') == 'id')
                                        {!! substr(strip_tags($item->body),0, 200) !!}
                                        @else
                                        {!! substr(strip_tags($item->body_en),0, 200) !!}
                                        @endif
                                    </span>
                                    <span class="badge badge-pill badge-grey mt-1 pt-1 pb-1 pl-2 pr-2" style="display: -webkit-box; max-width: 180px; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">{{ isset($item->category) ? $item->category->name : '' }}</span>
                                </div>
                            </a>
                            @else
                            <a style="color: inherit; text-decoration: none;" href="{{ route('detail', ['category'=>str_slug($item->category->name),'title'=>str_slug($item->name),'id'=>$item->id, 'id_feature'=>$item->category->id]) }}">
                                <img style="width: 100%; height: 48%;" src="{{ env('URL_CRM').'web/image?model=emhr.blog&field=image_detail&id='.$item->id }}">
                                <div class="pl-2 pt-2 pr-2">
                                    <span style="font-weight:bold; font-size: 16px; display: -webkit-box; max-width: 210px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        @if ( Config::get('app.locale') == 'id')
                                        {!! $item->name !!}
                                        @else
                                        {!! $item->name_en !!}
                                        @endif
                                    </span>
                                    <span class="mt-1" style="font-size: 13px; display: -webkit-box; max-width: 210px; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        @if ( Config::get('app.locale') == 'id')
                                        {!! substr(strip_tags($item->body),0, 200) !!}
                                        @else
                                        {!! substr(strip_tags($item->body_en),0, 200) !!}
                                        @endif
                                    </span>
                                    <span class="badge badge-pill badge-grey mt-1 pt-1 pb-1 pl-2 pr-2" style="display: -webkit-box; max-width: 180px; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">{{ isset($item->category) ? $item->category->name : '' }}</span>
                                </div>
                            </a>
                            @endif


                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection