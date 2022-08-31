@extends('layouts.administrator')

@section('title', 'General Setting')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">General Setting</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12 text-right">
                {{--<button type="button" class="btn btn-primary" id="changeSave"><i id="iconChange" class="fa fa-mobile"></i> Mobile Setting</button>--}}
                <button type="button" class="btn btn-info" id="buttonSave" onclick="form_setting.submit()"><i class="fa fa-save"></i> Save Setting</button>
            </div>
        </div>
        <div id="webSetting">
            <div class="row">
                <form class="form-horizontal" id="form-setting" name="form_setting" enctype="multipart/form-data" action="{{ route('administrator.setting.save') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-4 p-l-0 p-r-0">
                        <div class="white-box">
                            <div class="form-group">
                                <label class="col-md-12">Company Name</label>
                                <div class="col-md-12">
                                    <input type="text" name="setting[title]" class="form-control" value="{{ get_setting('title') }}">
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label class="col-md-12">Website Description</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="setting[description]" style="height: 150px;">{{ get_setting('description') }}</textarea>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <label class="col-md-12">Footer Description</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="setting[footer_description]">{{ get_setting('footer_description') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                {{--<div class="col-md-6">--}}
                                    {{--<label>Language</label>--}}
                                    {{--<select class="form-control" name="setting[language]">--}}
                                        {{--@foreach(list_language() as $key => $item)--}}
                                        {{--<option {{ $key == get_setting('language') ? 'selected' : '' }} value="{{ $key }}" >{{ $item }}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                <div class="col-md-6">
                                    <label>Timezones <label class="text-danger"> {{ date('d F Y H:i') }}</label></label>
                                    <select class="form-control" name="setting[timezone]">
                                        @foreach(generate_timezone_list() as $key => $item)
                                        <option {{ $key == get_setting('timezone') ? 'selected' : '' }} value="{{ $key }}" >{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">


                                {{--<div class="col-md-6">--}}
                                    {{--<label>@lang('setting.struktur-organisasi')</label>--}}
                                    {{--<select class="form-control" name="setting[struktur_organisasi]">--}}
                                        {{--<option value="1" {{ get_setting('struktur_organisasi') == 1 ? 'selected' : '' }}>Standar</option>--}}
                                        {{--<option value="3" {{ get_setting('struktur_organisasi') == 3 ? 'selected' : '' }}>Custom</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                <div class="col-md-6">
                                    <label>@lang('setting.login-with-captcha')</label>
                                    <select class="form-control" name="setting[login_with_captcha]">
                                        <option value="1" {{ get_setting('login_with_captcha') == 1 ? 'selected' : '' }}>None</option>
                                        <option value="2" {{ get_setting('login_with_captcha') == 2 ? 'selected' : '' }}>Standar</option>
                                        <!-- <option value="3" {{ get_setting('login_with_captcha') == 3 ? 'selected' : '' }}>Google reCaptcha</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 p-r-0">
                        <div class="white-box">
                            <div class="form-group">
                                <label class="col-md-12">Logo</label>
                                <div class="col-md-6">
                                    <input type="file" name="logo" class="form-control" />
                                </div>
                                <div class="col-md-6">
                                    @if(!empty(get_setting('logo')))
                                    <img src="{{ get_setting('logo') }}" style="height: 50px; " />
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Favicon</label>
                                <div class="col-md-6">
                                    <input type="file" name="favicon" class="form-control" />
                                </div>
                                <div class="col-md-6">
                                    @if(!empty(get_setting('favicon')))
                                    <img src="{{ get_setting('favicon') }}" style="height: 15px;" />
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label class="col-md-12">Logo Footer</label>
                                <div class="col-md-6">
                                    <input type="file" name="logo_footer" class="form-control" />
                                </div>
                                <div class="col-md-6">
                                    @if(!empty(get_setting('logo_footer')))
                                    <img src="{{ get_setting('logo_footer') }}" style="height: 50px;" />
                                    @endif
                                </div>
                            </div> --}}
                            {{--<div class="form-group">--}}
                                {{--<label class="col-md-12">Website Status</label>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<select class="form-control" name="setting[app_debug]">--}}
                                        {{--<option value="false" {{ get_setting('app_debug') == 'false' ? 'selected' : ''  }}>Production</option>--}}
                                        {{--<option value="true" {{ get_setting('app_debug') == 'true' ? 'selected' : ''  }}>Development</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                    <div class="col-md-4 p-r-0">
                        <div class="white-box">
                            <div class="form-group">
                                <label class="col-md-4">Top Header Color</label>
                                <label class="col-md-4">Header Color</label>
                                <label class="col-md-4">Menu Color</label>
                                <div class="col-md-4" style="padding-right: 0px;">
                                    <input data-jscolor="{}" type="text" class="form-control top_header_color" style="float: left;" name="setting[top_header_color]" value="{{ empty(get_setting('top_header_color')) ? '#ACCE22' : get_setting('top_header_color') }}">
                                </div>
                                <div class="col-md-4" style="padding-right: 0px;">
                                    <input data-jscolor="{}" type="text" class="form-control header_color" style="float: left;" name="setting[header_color]" value="{{ empty(get_setting('header_color')) ? '#0E9A88' : get_setting('header_color') }}">
                                </div>
                                <div class="col-md-4">                            
                                    <input data-jscolor="{}" type="text" class="form-control menu_color" style="float: left;" name="setting[menu_color]" value="{{ empty(get_setting('menu_color')) ? '#0E9A88' : get_setting('menu_color') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-6">Header Text Color</label>
                                <label class="col-md-6">Header Text Weight</label>
                                <div class="col-md-6">
                                    <input data-jscolor="{}" type="text" class="form-control header_text_color" style="float: left;" name="setting[header_text_color]" value="{{ empty(get_setting('header_text_color')) ? '#000000' : get_setting('header_text_color') }}">
                                </div>
                                <div class="col-md-6">                            
                                    <select class="form-control header_text_weight" name="setting[header_text_weight]">
                                        @for($index = 100; $index <= 800; $index+=50)
                                            <option value="{{ $index }}" {{ get_setting('header_text_weight') == $index || (empty(get_setting('header_text_weight')) && $index == 300) ? 'selected' : '' }}>{{ $index }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-6">Table Font Color</label>
                                <label class="col-md-6">Table Font Weight</label>
                                <div class="col-md-6">                                
                                    <input data-jscolor="{}" type="text" class="form-control table_color" style="float: left;" name="setting[table_color]" value="{{ empty(get_setting('table_color')) ? '#000000' : get_setting('table_color') }}">
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control" name="setting[table_weight]">
                                        @for($index = 100; $index <= 500; $index+=50)
                                            <option value="{{ $index }}" {{ get_setting('table_weight') == $index || (empty(get_setting('table_weight')) && $index == 300) ? 'selected' : '' }}>{{ $index }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Font</label>
                                <div class="col-md-12">
                                    <select class="form-control web_font" id="web_font" name="setting[web_font]">
                                        <option value="Arial" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Arial' ? 'selected' : '' }}> Arial </option>
                                        <option value="auto" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'auto' ? 'selected' : '' }}> Auto </option>
                                        <option value="Brush Script MT" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Brush Script MT' ? 'selected' : '' }}> Brush Script MT </option>
                                        <option value="Copperplate" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Copperplate' ? 'selected' : '' }}> Copperplate </option>
                                        <option value="Courier New" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Courier New' ? 'selected' : '' }}> Courier New </option>
                                        <option value="cursive" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'cursive' ? 'selected' : '' }}> Cursive </option>
                                        <option value="emoji" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'emoji' ? 'selected' : '' }}> Emoji </option>
                                        <option value="fangsong" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'fangsong' ? 'selected' : '' }}> Fangsong </option>
                                        <option value="fantasy" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'fantasy' ? 'selected' : '' }}> Fantasy </option>
                                        <option value="Garamond" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Garamond' ? 'selected' : '' }}> Garamond </option>
                                        <option value="Georgia" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Georgia' ? 'selected' : '' }}> Georgia </option>
                                        <option value="Helvetica" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Helvetica' ? 'selected' : '' }}> Helvetica </option>
                                        <option value="inherit" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'inherit' ? 'selected' : '' }}> Inherit </option>
                                        <option value="Inter" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Inter' ? 'selected' : '' }}> Inter </option>
                                        <option value="Karla" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Karla' ? 'selected' : '' }}> Karla </option>
                                        <option value="Koulen" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Koulen' ? 'selected' : '' }}> Koulen </option>
                                        <option value="Lato" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Lato' ? 'selected' : '' }}> Lato </option>
                                        <option value="Lara" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Lara' ? 'selected' : '' }}> Lara </option>
                                        <option value="Lucida Console" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Lucida Console' ? 'selected' : '' }}> Lucida Console </option>
                                        <option value="Lucida Handwriting" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Lucida Handwriting' ? 'selected' : '' }}> Lucida Handwriting </option>
                                        <option value="Macondo" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Macondo' ? 'selected' : '' }}> Macondo </option>
                                        <option value="Merriweather" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Merriweather' ? 'selected' : '' }}> Merriweather </option>
                                        <option value="Monaco" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Monaco' ? 'selected' : '' }}> Monaco </option>
                                        <option value="Montserrat" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Montserrat' ? 'selected' : '' }}> Montserrat </option>
                                        <option value="Mukta" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Mukta' ? 'selected' : '' }}> Mukta </option>
                                        <option value="Nunito" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Nunito' ? 'selected' : '' }}> Nunito </option>
                                        <option value="Oleo Script Swash Caps" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Oleo Script Swash Caps' ? 'selected' : '' }}> Oleo Script Swash Caps </option>
                                        <option value="Open Sans" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Open Sans' ? 'selected' : '' }}> Open Sans </option>
                                        <option value="Papyrus" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Papyrus' ? 'selected' : '' }}> Papyrus </option>
                                        <option value="Playfair Display" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Playfair Display' ? 'selected' : '' }}> Playfair Display </option>
                                        <option value="Poppins" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Poppins' ? 'selected' : '' }}> Poppins </option>
                                        <option value="Radio Canada" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Radio Canada' ? 'selected' : '' }}> Radio Canada </option>
                                        <option value="revert" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'revert' ? 'selected' : '' }}> Revert </option>
                                        <option value="Roboto" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Roboto' ? 'selected' : '' }}> Roboto </option>
                                        <option value="Rubik" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Rubik' ? 'selected' : '' }}> Rubik </option>
                                        <option value="sans-serif" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'sans-serif' ? 'selected' : '' }}> Sans-serif </option>
                                        <option value="serif" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'serif' ? 'selected' : '' }}> Serif </option>
                                        <option value="Source Sans Pro" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Source Sans Pro' ? 'selected' : '' }}> Source Sans Pro </option>
                                        <option value="system-ui" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'system-ui' ? 'selected' : '' }}> System-ui </option>
                                        <option value="Times New Roman" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Times New Roman' ? 'selected' : '' }}> Times New Roman </option>
                                        <option value="ui-monospace" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'ui-monospace' ? 'selected' : '' }}> Ui-monospace </option>
                                        <option value="ui-rounded" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'ui-rounded' ? 'selected' : '' }}> Ui-rounded </option>
                                        <option value="ui-sans-serif" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'ui-sans-serif' ? 'selected' : '' }}> Ui-sans-serif </option>
                                        <option value="ui-serif" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'ui-serif' ? 'selected' : '' }}> Ui-serif </option>
                                        <option value="Ubuntu" {{get_setting('web_font') == 'Ubuntu' || (empty(get_setting('web_font'))) ? 'selected' : '' }}> Ubuntu </option>
                                        <option value="unset" {{get_setting('web_font') == 'unset' || (empty(get_setting('web_font'))) ? 'selected' : '' }}> Unset </option>
                                        <option value="Verdana" {{ get_setting('web_font') !=  '' && get_setting('web_font') == 'Verdana' ? 'selected' : '' }}> Verdana </option>
                                        <option value="Work Sans" {{get_setting('web_font') == 'Work Sans' || (empty(get_setting('web_font'))) ? 'selected' : '' }}> Work Sans </option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('administrator.setting.rollback') }}" type="button" class="btn text-right" style="background-color: #0E9A88; color:white" data-toggle="tooltip" data-placement="bottom" title="this button to return the display to the default view"><i class="fa fa-back"></i> Set as Default</a>
                            </div>
                        </div>
                    </div>
                </form>                   
            </div>
        </div>
        <div id="mobileSetting" style="display:none">
            <div class="row">
                <form class="form-horizontal" id="form-setting-mobile" enctype="multipart/form-data" name="form_setting_mobile" action="{{ route('administrator.attendance.setting-save') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="white-box">
                    <div class="table-responsive">
                        </br>
                        <div class="col-md-6 p-l-0 p-r-0">
                            <div class="form-group">
                                <label class="col-md-12">Logo</label>
                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="attendance_logo" />
                                </div>
                                <div class="col-md-6">
                                    @if(!empty(get_setting('attendance_logo')))
                                    <img src="{{ get_setting('attendance_logo') }}" style="height: 50px; " />
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name Company</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="setting_mobile[attendance_company]" value="{{ get_setting('attendance_company') }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Notification / News / Memo</label>
                                <div class="col-md-12">
                                    <textarea name="setting_mobile[attendance_news]" class="form-control">{{ get_setting('attendance_news') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
@section('js')
<script src="{{ asset('js/jscolor-2.4.5/jscolor.min.js') }}"></script> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript">

    $('#web_font').select2();

    function changeSave(){
        $('#changeSave').click(function(){
            $('#buttonSave').attr('onclick', 'form_setting_mobile.submit()')
            $('#webSetting').hide()
            $('#mobileSetting').show()
            $('#changeSave').html('<i id="iconChange" class="fa fa-tv"></i> Web Setting')
            $('#changeSave').attr('id', 'changeSaveMobile')
            changeSave()
        })

        $('#changeSaveMobile').click(function(){
            $('#buttonSave').attr('onclick', 'form_setting.submit()')
            $('#mobileSetting').hide()
            $('#webSetting').show()
            $('#changeSaveMobile').html('<i id="iconChange" class="fa fa-mobile"></i> Mobile Setting')
            $('#changeSaveMobile').attr('id', 'changeSave')
            changeSave()
        })
    }

    $(function(){
        changeSave()

        jscolor.presets.default = { // Defaults for all pickers on page
            position: 'bottom',
            // width: 100,
            //height: 100,
            //paletteCols: 8,
            //hideOnPaletteClick: true,
            palette: [
                '#000000', '#7d7d7d', '#870014', '#ec1c23', '#ff7e26',
                '#fef100', '#22b14b', '#00a1e7', '#3f47cc', '#a349a4',
                '#ffffff', '#c3c3c3', '#b87957', '#feaec9', '#ffc80d',
                '#eee3af', '#b5e61d', '#99d9ea', '#7092be', '#c8bfe7',
            ],
        };

        $(".top_header_color").on("change",function(){
            var warna  = $(this).val();
            $('.navbar-header').css("border-top", '5px solid '+ warna );
        });

        $(".header_color").on("change",function(){
            var warna  = $(this).val();

            $('.navbar-header').css({ backgroundColor: warna});
        });

        $(".header_text_color").on("change",function(){
            var warna  = $(this).val();
            $('#side-menu > li > a').css({ color: warna});
        });

        $(".header_text_weight").on("change",function(){
            var weight  = $(this).val();
            $('#side-menu > li > a').css({ fontWeight: weight});
        });

        $(".web_font").on("change",function(){
            var font  = $(this).val();
            $('.fix-header').css({ fontFamily: font});
        });

        $(".menu_color").on("change",function(){
            var warna  = $(this).val();
            $('#side-menu > li > a.active').css("color",  warna);
            $('#side-menu > li > a.active').css("border-bottom", '3px solid '+  warna);
            $('#side-menu > li > a.active > img').css("background-color",  warna);
            $('#side-menu > li > a.active > img').css("width",  '30px');
            $('#side-menu > li > a.active > img').css("height",  '30px');
            $('#side-menu > li > a.active > img').css("-webkit-mask-repeat",  'no repeat');
            $('#side-menu > li > a.active > img').css("-webkit-mask-size",  'contain');
            $('#side-menu > li > a.active > img').css("-webkit-mask-image", ('{{url('/admin-css/icon/ICON_SETTING_GREEN.png')}}'));
        });

        $(".table_color").on("change",function(){
            var warna  = $(this).val();
        });
    })
</script>
@endsection
@endsection
