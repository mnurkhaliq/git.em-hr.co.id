@extends('landing-page.landing-page')

@section('content')

<div class="container" style="margin-top: 3%;">
    <div class="row" style="padding-left: 40px; padding-right: 40px;">
        <div class="d-flex vh-100 justify-content-center " style="width: 1199px; height: 300px; background: url('{{ asset('landing-page/2019-05-28/bg_gradient.png') }}')">
            <div class="justify-content-center align-self-center">
                <div class="col-12 justify-content-center">
                    <h1 class="text-center" style="font-size: 240%; font-weight: 800; color: white;">
                        {{ __("landingpage.promo.text_get_promo") }} <br>
                    </h1>
                    <h1 class="text-center" style="font-size: 150%; font-weight: 300; color: white;">
                        {{ __("landingpage.promo.text_get_bonus") }} <br>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-left: 40px; padding-right: 40px; margin-top: 3%;">
        @foreach($promos as $item)
        <div class="col-xs-6 col-sm-6 col-md-6 padding-0">
        <div class="card cardproduct shadow border-0 mb-3">
            @if ( Config::get('app.locale') == 'id')
                <img style="height: 150px; border-radius: 13px;" class="card-img d-block w-100" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$item->id }}"> 
            @elseif ( Config::get('app.locale') == 'en')
                <img style="height: 150px; border-radius: 13px;" class="card-img d-block w-100" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image_en&id='.$item->id }}">
            @else
                <img style="height: 150px; border-radius: 13px;" class="card-img d-block w-100" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$item->id }}"> 
            @endif
                <div class="card-body pt-2">
                    @if ( Config::get('app.locale') == 'id')
                        <h2 style="font-size: 110%; font-weight: 800;">{{ $item->name }}</h2>
                    @elseif ( Config::get('app.locale') == 'en')
                        <h2 style="font-size: 110%; font-weight: 800;">{{ $item->name_en }}</h2>
                    @else
                        <h2 style="font-size: 110%; font-weight: 800;">{{ $item->name }}</h2>
                    @endif
                    <span style="font-size: 15px;">{{ __("landingpage.promo.text_periode_promo") }}</span><br>
                    <b>
                        @if(empty($item->use_start_date) and empty($item->use_end_date)) 
                            {{ __("landingpage.promo.text_periode_periode") }}
                        @else
                            @if(date('F', strtotime($item->use_start_date)) == date('F', strtotime($item->use_end_date)))
                                @if(date('Y', strtotime($item->use_start_date)) == date('Y', strtotime($item->use_end_date)))                            
                                    {{ date('d', strtotime($item->use_start_date)) }}
                                    - 
                                    {{ date('d', strtotime($item->use_end_date)) }}
                                    {{ date('F', strtotime($item->use_start_date)) }}
                                    {{ date('Y', strtotime($item->use_start_date)) }}
                                @else
                                    {{ date('d', strtotime($item->use_start_date)) }}
                                    {{ date('F', strtotime($item->use_start_date)) }}
                                    {{ date('Y', strtotime($item->use_start_date)) }}

                                    @if(!empty($item->use_end_date))
                                    - 
                                    {{ date('d', strtotime($item->use_end_date)) }}
                                    {{ date('F', strtotime($item->use_end_date)) }}     
                                    {{ date('Y', strtotime($item->use_end_date)) }}
                                    @endif
                                @endif
                           
                            @else
                                @if(!empty($item->use_start_date))
                                    {{ date('d', strtotime($item->use_start_date)) }}
                                    {{ date('F', strtotime($item->use_start_date)) }}
                                @endif
                                @if(date('Y', strtotime($item->use_start_date)) == date('Y', strtotime($item->use_end_date)))                            
                                - 
                                {{ date('d', strtotime($item->use_end_date)) }}
                                {{ date('F', strtotime($item->use_end_date)) }}
                                {{ date('Y', strtotime($item->use_end_date)) }}
                                @else
                                    @if(!empty($item->use_start_date))
                                        {{ date('Y', strtotime($item->use_start_date)) }}
                                    @endif
                                    @if(!empty($item->use_end_date))
                                        @if(!empty($item->use_start_date))
                                        - 
                                        @endif
                                    {{ date('d', strtotime($item->use_end_date)) }}
                                    {{ date('F', strtotime($item->use_end_date)) }}     
                                    {{ date('Y', strtotime($item->use_end_date)) }}
                                    @endif
                                
                                
                                @endif
                            
                            @endif
                        @endif
                        
                    </b>
                    <br>
                    <span style="font-size: 15px;">{{ __("landingpage.promo.text_code_promo") }}</span><br>
                    <b>
                        @if(empty($item->code)) 
                            {{ __("landingpage.promo.text_value_code_promo") }}
                        @else
                            {{ $item->code }}
                        @endif
                    </b><br><br>
                    <div class="text-center">
                        @if ( Config::get('app.locale') == 'id')
                            <a href="{{ route('promo', ['id','title'=>str_slug($item->name),'id'=>$item->id]) }}" class="btn btn-info btn_promo">{{ __("landingpage.promo.btn_detail_promo") }}</a>
                        @elseif ( Config::get('app.locale') == 'en')
                            <a href="{{ route('promo', ['en','title'=>str_slug($item->name_en),'id'=>$item->id]) }}" class="btn btn-info btn_promo">{{ __("landingpage.promo.btn_detail_promo") }}</a>
                        @else
                            <a href="{{ route('promo', ['id','title'=>str_slug($item->name),'id'=>$item->id]) }}" class="btn btn-info btn_promo">{{ __("landingpage.promo.btn_detail_promo") }}</a>
                        @endif
                    </div>
                </div>
            </div>
    
        </div>
        @endforeach
    </div>
</div>

@endsection