@extends('landing-page.landing-page')

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-7 text-center">
            <h2 style="font-size: 160%; font-weight: 750;">{{ __("landingpage.contact.text_contact_us") }}</h2>
            <p class="mt-4">{{ __("landingpage.contact.subtext_desc_contact") }}</p>
        </div>
        <div class="col-7">
            @foreach($data as $item)
            <div class="row">
                <div class="col-md-2 p-0 m-0">
                    <div class="imgAbt pull-right float-right mt-1">
                        <img src="{{ env('URL_CRM').'web/image?model=res.partner&field=image_1920&id='.$item->id }}" class="rounded-circle" alt="Sales Image" width="70" height="70">
                    </div>
                </div>
                <div class="col-md-10">
                    <span style="font-size: 130%; font-weight: 700;">{{ $item->name }}</span><br>
                    <span style="font-weight: bold;">{{ $item->phone }}</span><br>
                    <span>Email : {{ $item->email }}</span>
                    <a href="https://api.whatsapp.com/send?phone={{ $item->mobile }}&text=Hallo {{ $item->name }} saya ingin menanyakan tentang Em-HR" target="_blank" style="text-decoration: none; color: inherit;">
                        <div class="row">
                            <hr class="ml-3" style="border-color:#ACCE22; opacity:0.5; height: 2px; width: 400px;">
                            <span class="mt-1">{{ __("landingpage.contact.subtext_live_chat") }}</span>
                            <img class="ml-1 mt-1" src="{{asset('images/whatsapp.png')}}" class="rounded-circle" alt="Sales Image" width="25" height="25">
                        </div>
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

@endsection