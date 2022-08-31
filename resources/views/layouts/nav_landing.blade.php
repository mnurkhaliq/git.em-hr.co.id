<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    @if ( Config::get('app.locale') == 'id')
    <a class="navbar-brand" href="{{ url('/id') }}">
        <img src="{{ asset('landing-page/2019-05-28/icon_emhr.svg') }}" class="img-fluid" width="160" style="max-width: 100%; height: auto;" alt="EMHR System">
    </a>
    @elseif ( Config::get('app.locale') == 'en')
    <a class="navbar-brand" href="{{ url('/en') }}">
        <img src="{{ asset('landing-page/2019-05-28/icon_emhr.svg') }}" class="img-fluid" width="160" style="max-width: 100%; height: auto;" alt="EMHR System">
    </a>
    @else
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('landing-page/2019-05-28/icon_emhr.svg') }}" class="img-fluid" width="160" style="max-width: 100%; height: auto;" alt="EMHR System">
    </a>
    @endif

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav mr-auto">
            @if ( Config::get('app.locale') == 'id')
            <li class="nav-item {{ Request::is('id') || Request::is('/') ? 'active' : '' }}">
                <a class="nav-link nav-link-home font-weight-bold" href="{{ url('/id') }}">{{ __("landingpage.home.text_home") }}</a>
            </li>
            @elseif ( Config::get('app.locale') == 'en')
            <li class="nav-item {{ Request::is('en') ? 'active' : '' }}">
                <a class="nav-link nav-link-home font-weight-bold" href="{{ url('/en') }}">{{ __("landingpage.home.text_home") }}</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="{{ env('URL_CRM','https://testcrm.em-hr.co.id/').'demo_trial' }}">{{ __("landingpage.home.text_demo_trial") }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" href="{{ env('URL_CRM','https://testcrm.em-hr.co.id/').'pricing' }}">{{ __("landingpage.home.text_pricing") }}</a>
            </li>
            @if ( Config::get('app.locale') == 'id')
            <li class="nav-item {{ Request::is('id/feature') || Request::is('feature') ? 'active' : '' }}">
                <a class="nav-link nav-link-feature font-weight-bold" href="{{ url('id','feature') }}">{{ __("landingpage.home.text_blog") }}</a>
            </li>
            @elseif ( Config::get('app.locale') == 'en')
            <li class="nav-item {{ Request::is('en/feature') ? 'active' : '' }}">
                <a class="nav-link nav-link-feature font-weight-bold" href="{{ url('en','feature') }}">{{ __("landingpage.home.text_blog") }}</a>
            </li>
            @endif
        </ul>

    </div>

    <div class="row align-items-center">
    <a onclick="openModal()" class="btn btn-info float-right btn_login">Login Em-HR</a>

        @if(Request::path() == "id/feature" || Request::path() == "en/feature" || Request::path() == "feature")
        <a href="{{ url('en','feature') }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ url('id','feature') }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @elseif(Request::path() == "id/contact" || Request::path() == "en/contact" || Request::path() == "contact")
        <a href="{{ url('en','contact') }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ url('id','contact') }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @elseif(Request::path() == "id/how_to_subscribe" || Request::path() == "en/how_to_subscribe" || Request::path() == "how_to_subscribe")
        <a href="{{ url('en','how_to_subscribe') }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ url('id','how_to_subscribe') }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @elseif(Request::path() == "id/help_center" || Request::path() == "en/help_center" || Request::path() == "help_center")
        <a href="{{ url('en','help_center') }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ url('id','help_center') }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @elseif(strpos(Request::url(), '/article'))
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/en/{{ strstr(Request::url(), 'article', false) }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/id/{{ strstr(Request::url(), 'article', false) }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @elseif(strpos(Request::url(), '/feature'))
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/en/{{ strstr(Request::url(), 'feature', false) }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/id/{{ strstr(Request::url(), 'feature', false) }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @elseif(strpos(Request::url(), '/promo'))
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/en/{{ strstr(Request::url(), 'promo', false) }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/id/{{ strstr(Request::url(), 'promo', false) }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @elseif(strpos(Request::url(), '/promo_list'))
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/en/{{ strstr(Request::url(), 'promo_list', false) }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ env('APP_URL', 'https://em-hr.co.id') }}/id/{{ strstr(Request::url(), 'promo_list', false) }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @else
        <a href="{{ url('en/') }}" style="margin-right: 7px;">
            <img src="{{ asset('images/english.png') }}" width="27"></a>
        </a>
        <a href="{{ url('id/') }}">
            <img src="{{ asset('images/indonesia.png') }}" width="27"></a>
        </a>
        @endif
    </div>

</nav>