@extends('landing-page.landing-page')

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-7 text-center">
            <h2 class="text-center" style="font-size: 160%; font-weight: 750;">{{ __("landingpage.subscribe.text_subscribe") }}</h2>

        </div>

    </div>
    <div class="ml-5 mr-5 mb-5">
        <p style="font-size: 120%; font-weight: 600;" class="mt-4">{{ __("landingpage.subscribe.text_subscribe_mandiri") }}</p>
        <p>
            {{ __("landingpage.subscribe.subtext_desc_head") }}
        <ol>
            <li>{{ __("landingpage.subscribe.subtext_desc_poin_1_open_website") }}
                <a href="https://www.em-hr.co.id" target="__blank">www.em-hr.co.id</a>
                {{ __("landingpage.subscribe.subtext_desc_poin_1_registration") }}
                <a href="https://crm.em-hr.co.id/web/signup" target="__blank">https://crm.em-hr.co.id/web/signup</a>
            </li>
            <li>{{ __("landingpage.subscribe.subtext_desc_point_2") }}</li>
            <li>{{ __("landingpage.subscribe.subtext_desc_point_3") }}</li>
            <li>{{ __("landingpage.subscribe.subtext_desc_point_4") }}</li>
            <li>{{ __("landingpage.subscribe.subtext_desc_point_5") }}</li>


        </ol>
        </p>
        <p style="font-size: 120%; font-weight: 600;" class="mt-4">{{ __("landingpage.subscribe.text_subscribe_sales") }}</p>
        <p>
            {{ __("landingpage.subscribe.subtext_desc") }}
            @if ( Config::get('app.locale') == 'id')
            <a target="_blank" href="{{ url('id','contact') }}">{{ __("landingpage.home.text_contact_us") }}</a>
            @elseif ( Config::get('app.locale') == 'en')
            <a target="_blank" href="{{ url('en','contact') }}">{{ __("landingpage.home.text_contact_us") }}</a>
            @else
            <a target="_blank" href="{{ url('contact') }}">{{ __("landingpage.home.text_contact_us") }}</a>
            @endif
        </p>
    </div>
</div>

@endsection