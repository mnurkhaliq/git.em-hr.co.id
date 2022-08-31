@extends('landing-page.landing-page')

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-7 text-center">
            <h2 class="text-center" style="font-size: 160%; font-weight: 750;">{{ __("landingpage.help_center.text_helpcenter") }}</h2>

        </div>

    </div>
    <div class="ml-5 mr-5 mb-5">
        <span style="font-size: 120%; font-weight: 600;" class="mt-4">{{ __("landingpage.help_center.text_helpcenter") }}</span>
        <br>
        <span>
            {{ __("landingpage.help_center.subtext_desc_helpcenter") }}
        </span>
        <br><br>
        <span style="font-size: 120%; font-weight: 600;" class="mt-4">{{ __("landingpage.help_center.subtext_service_time") }}</span>
        <br>
        <span>
            {{ __("landingpage.help_center.subtext_monday") }}
        </span>
        <br>
        <span>
            {{ __("landingpage.help_center.subtext_saturday") }}
        </span>
    </div>
    <div class="row justify-content-center mt-5">
        <div class="col-7 text-center">
            <span style="font-size: 120%; font-weight: 600;" class="mt-4">{{ __("landingpage.help_center.text_we_help") }}</span>
        </div>
    </div>
    <div id="accordion" class="ml-5 mr-5 mt-3 mb-5">
        @foreach($data as $item)
            <div class="card">
                <div class="card-header" id="heading{{ $item->id }}">
                    <h5 class="mb-0">
                        <button class="btn btn-link" style="color: inherit; font-weight: bold; font-size: 17px;" data-toggle="collapse" data-target="#collapse{{ $item->id }}" aria-expanded="true" aria-controls="collapse{{ $item->id }}">
                            @if ( Config::get('app.locale') == 'id')
                                {!! $item->question !!}
                            @else
                                {!! $item->question_en !!}
                            @endif
                        </button>
                    </h5>
                </div>

                <div id="collapse{{ $item->id }}" class="collapse show" aria-labelledby="heading{{ $item->id }}" data-parent="#accordion">
                    <div class="card-body" style="padding-left: 34px;">
                            @if ( Config::get('app.locale') == 'id')
                                {!! $item->answer !!}
                            @else
                                {!! $item->answer_en !!}
                            @endif
                    </div>
                </div>
            </div>
        @endforeach
        <!-- <div class="card">
            <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Apa itu Em-HR ?
                    </button>
                </h5>
            </div>

            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ex earum numquam reiciendis? Itaque, aut vero magni beatae eveniet nihil optio dignissimos, sapiente nisi consectetur deleniti ut. Magnam vel in minus.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Apa itu Em-HR ?
                    </button>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam officia quaerat quo quasi provident! Voluptatibus cupiditate et ipsum similique earum id autem est placeat porro dolorem iusto, necessitatibus quae facilis.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Apa itu Em-HR ?
                    </button>
                </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Recusandae beatae eaque necessitatibus natus suscipit porro commodi iure nam tempora. Repellendus recusandae pariatur maiores vel quos quo dolorem delectus consequuntur nam?
                </div>
            </div>
        </div> -->
    </div>
</div>

@endsection