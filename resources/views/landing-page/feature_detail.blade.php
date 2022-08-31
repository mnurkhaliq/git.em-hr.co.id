@extends('landing-page.landing-page')

@section('content')

<div class="container">

    <div class="row mt-5" style="padding-left: 40px; padding-right: 40px;">
        <div class="col-md-2 pr-0">
            <div class="card float-right pull-right" style="width: 100px; border-radius: 10px;" >
                <div class="card-body p-0">
                    <img src="{{ env('URL_CRM').'web/image?model=product.template&field=image_1920&id='.$data->id }}" width="100%" />
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <h2 style="font-size: 180%; font-weight: 800;">
                {{ $data->name }}
            </h2>
            <p>
                @if ( Config::get('app.locale') == 'id')
                {{ $data->description_sale_id }}
                @else
                {{ $data->description_sale }}
                @endif
            </p>
        </div>
    </div>

    <div class="container text-center mb-5" style="padding-left: 50px; padding-right: 50px;">
    @if(count($datas) < 1)
        @include('landing-page.empty_layout', ['isFrom' => 'feature','title' => 'Data Artikel Kosong', 'desc' => 'Opps, artikel kosong nih. Tunggu aja yaa kami akan membuat artikel terbaru buat kamu.'])
    @else
        @foreach($datas as $item)
            <div class="list_feature mt-5 mb-5">
                <div class="row">
                    <div class="col-md-12" style="text-align: center; margin-bottom: 10px;">
                        <h1 style="font-size: 220%; font-weight: 800;">
                            @if ( Config::get('app.locale') == 'id')
                            {{ $item->name }}
                            @else
                            {{ $item->name_en }}
                            @endif
                        </h1>
                    </div>
                </div>
                <img style="width: 80%; height: 220px;" src="{{ env('URL_CRM').'web/image?model=emhr.blog&field=image&id='.$item->id }}">
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
        @endif
    </div>


</div>

@endsection