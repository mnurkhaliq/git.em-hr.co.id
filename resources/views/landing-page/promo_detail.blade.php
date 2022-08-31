@extends('landing-page.landing-page')

@section('content')

<div class="container" style="margin-top: 3%;">
    <div class="row">
        @if ( Config::get('app.locale') == 'id')
            <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">  
        @elseif ( Config::get('app.locale') == 'en')
            <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image_en&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
        @else
            <img style="width: 1199px; height: 300px;" src="{{ env('URL_CRM').'web/image?model=emhr.promo&field=image&id='.$promo->id }}" class="img-responsive d-block w-100" alt="...">
        @endif
    </div>
    <div class="row" style="margin-top: 2%;">
        <div class="col-12">
            <div class="row">
                <div class="col-7">
                    <h2 style="font-size: 200%; font-weight: 800;">
                        @if (Config::get('app.locale') == 'id')
                            {{ $promo->name }}
                        @elseif (Config::get('app.locale') == 'en')
                            {{ $promo->name_en }}
                        @else
                            {{ $promo->name }}
                        @endif
                    </h2>
                </div>
                <div class="col-5">
                    <div class="float-right">
                    {{ __("landingpage.promo.text_periode_promo") }} : 
                    <b>
                    @if(empty($promo->use_start_date) and empty($promo->use_end_date)) 
                            {{ __("landingpage.promo.text_periode_periode") }}
                        @else
                        @if(date('F', strtotime($promo->use_start_date)) == date('F', strtotime($promo->use_end_date)))
                            @if(date('Y', strtotime($promo->use_start_date)) == date('Y', strtotime($promo->use_end_date)))                            
                                {{ date('d', strtotime($promo->use_start_date)) }}
                                    - 
                                {{ date('d', strtotime($promo->use_end_date)) }}
                                {{ date('F', strtotime($promo->use_start_date)) }}
                                {{ date('Y', strtotime($promo->use_start_date)) }}
                                @else
                                {{ date('d', strtotime($promo->use_start_date)) }}
                                {{ date('F', strtotime($promo->use_start_date)) }}
                                {{ date('Y', strtotime($promo->use_start_date)) }}

                                @if(!empty($promo->use_end_date))
                                    - 
                                {{ date('d', strtotime($promo->use_end_date)) }}
                                {{ date('F', strtotime($promo->use_end_date)) }}     
                                {{ date('Y', strtotime($promo->use_end_date)) }}
                                    @endif
                                @endif
                           
                            @else
                            @if(!empty($promo->use_start_date))
                                {{ date('d', strtotime($promo->use_start_date)) }}
                                {{ date('F', strtotime($promo->use_start_date)) }}
                                @endif
                            @if(date('Y', strtotime($promo->use_start_date)) == date('Y', strtotime($promo->use_end_date)))                            
                                - 
                            {{ date('d', strtotime($promo->use_end_date)) }}
                            {{ date('F', strtotime($promo->use_end_date)) }}
                            {{ date('Y', strtotime($promo->use_end_date)) }}
                                @else
                                @if(!empty($promo->use_start_date))
                                    {{ date('Y', strtotime($promo->use_start_date)) }}
                                    @endif
                                @if(!empty($promo->use_end_date))
                                    @if(!empty($promo->use_start_date))
                                        - 
                                        @endif
                                {{ date('d', strtotime($promo->use_end_date)) }}
                                {{ date('F', strtotime($promo->use_end_date)) }}     
                                {{ date('Y', strtotime($promo->use_end_date)) }}
                                    @endif
                                
                                
                                @endif
                            
                            @endif
                        @endif
                    </b>
                    </div><br>
                    <div class="float-right">
                    {{ __("landingpage.promo.text_code_promo") }} :
                    <b>
                        @if(empty($promo->code)) 
                            {{ __("landingpage.promo.text_value_code_promo") }}
                        @else
                            {{ $promo->code }}
                        @endif
                    </b>
                    </div>

                </div>
            </div>
            <span>
                @if ( Config::get('app.locale') == 'id')
                    {!! $promo->body !!} 
                @elseif (Config::get('app.locale') == 'en')
                    {!! $promo->body_en !!} 
                @else
                    {!! $promo->body !!}
                @endif
            </span>
            <h2 style="font-size: 150%; margin-top: 5px; font-weight: 300;">{{ __("landingpage.promo.text_tnc") }}</h2>
            <span>
                @if ( Config::get('app.locale') == 'id')
                    {!! $promo->tnc !!} 
                @elseif (Config::get('app.locale') == 'en')
                    {!! $promo->tnc_en !!} 
                @else
                    {!! $promo->tnc !!}
                @endif
            </span>
            <br>
            <a href="{{ env('URL_CRM','https://testcrm.em-hr.co.id/').'pricing' }}" class="btn btn-info btn_promo">{{ __("landingpage.promo.text_register_get") }}</a>
            
        </div>
    </div>
</div>

@endsection