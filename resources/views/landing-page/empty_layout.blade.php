<div class="row">
<div class="col-md-12 text-center">
    @if ($isFrom == 'detail')
        <img src="{{ asset('images/empty.png') }}" class="img-fluid" style="width: 300px;">
        <h3 class="mt-2" style="font-size: 120%; font-weight: 800;" class="mb-0">{{ __("landingpage.detail.subtext_empty_title") }}</h3>
        <p class="mt-2" style="font-size: 13px;">{{ __("landingpage.detail.subtext_empty_desc") }}</p>
    @else
        <img src="{{ asset('images/empty_feature.png') }}" class="img-fluid" style="width: 450px;">
        <h3 class="mt-2" style="font-size: 150%; font-weight: 800;" class="mb-0">{{ __("landingpage.detail.subtext_empty_title") }}</h3>
        <p class="mt-2">{{ __("landingpage.detail.subtext_empty_desc") }}</p>
    @endif
</div>
</div>