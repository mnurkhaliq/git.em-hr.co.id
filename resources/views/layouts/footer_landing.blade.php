<div class="mt-2 footer-landing">
    <img src="{{ asset('landing-page/2019-05-28/line botton.png') }}" style="width: 100%;">
    <br><br>
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-md-4">
                    <h6>{{ __("landingpage.home.text_office") }}</h6>
                    <span>Metropolitan Tower, level13-A</span><br>
                    <span>Jl. R.A Kartini - T.B. Simatupang Kav. 14</span><br>
                    <span>Cilandak, Jakarta Selatan</span><br>
                    <span>Jakarta - 12430</span><br>
                    <span>Phone : +62 21 2955 7450</span><br>
                </div>

                <div class="col-xs-6 col-md-4">
                    <h6>{{ __("landingpage.home.text_company") }}</h6>
                    <ul class="footer-links">
                        <li><a href="https://www.empore.co.id/" target="_blank">{{ __("landingpage.home.text_about_us") }}</a></li>
                        <li>
                            <a target="_blank" href="https://www.empore.co.id/contact-us.php">{{ __("landingpage.home.text_contact_us") }}</a>
                        </li>
                        <li>
                            @if ( Config::get('app.locale') == 'id')
                                <a target="_blank" href="{{ url('id','feature') }}">{{ __("landingpage.home.text_blog") }}</a>
                            @elseif ( Config::get('app.locale') == 'en')
                                <a target="_blank" href="{{ url('en','feature') }}">{{ __("landingpage.home.text_blog") }}</a>
                            @endif
                        </li>
                        <li><a href="https://jobs-empore.em-hr.co.id/jobs.html" target="_blank">{{ __("landingpage.home.text_career_us") }}</a></li>
                    </ul>
                </div>

                <div class="col-xs-6 col-md-4">
                    <h6>{{ __("landingpage.home.text_help") }}</h6>
                    <ul class="footer-links">
                        <li>
                            @if ( Config::get('app.locale') == 'id')
                            <a target="_blank" href="{{ url('id','help_center') }}">{{ __("landingpage.home.text_center_help") }}</a>
                            @elseif ( Config::get('app.locale') == 'en')
                            <a target="_blank" href="{{ url('en','help_center') }}">{{ __("landingpage.home.text_center_help") }}</a>
                            @else
                            <a target="_blank" href="{{ url('help_center') }}">{{ __("landingpage.home.text_center_help") }}</a>
                            @endif
                        </li>
                        <li>
                            @if ( Config::get('app.locale') == 'id')
                            <a target="_blank" href="{{ url('id','contact') }}">{{ __("landingpage.home.text_contact_sales") }}</a>
                            @elseif ( Config::get('app.locale') == 'en')
                            <a target="_blank" href="{{ url('en','contact') }}">{{ __("landingpage.home.text_contact_sales") }}</a>
                            @else
                            <a target="_blank" href="{{ url('contact') }}">{{ __("landingpage.home.text_contact_sales") }}</a>
                            @endif
                        </li>
                        <li>
                            @if ( Config::get('app.locale') == 'id')
                            <a target="_blank" href="{{ url('id','how_to_subscribe') }}">{{ __("landingpage.home.subtext_howto_subscribe") }}</a>
                            @elseif ( Config::get('app.locale') == 'en')
                            <a target="_blank" href="{{ url('en','how_to_subscribe') }}">{{ __("landingpage.home.subtext_howto_subscribe") }}</a>
                            @else
                            <a target="_blank" href="{{ url('how_to_subscribe') }}">{{ __("landingpage.home.subtext_howto_subscribe") }}</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row mr-3">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <p class="copyright-text">Copyright &copy; 2022 Em-HR by
                        <a href="https://www.empore.co.id">PT Empore Hezer Tama</a>
                    </p>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="social-icons">

                        <li><a class="instagram" target="_blank" href="https://www.instagram.com/emhr.apps/"><i class="fa fa-instagram"></i></a></li>
                        <li><a class="facebook" target="_blank" href="https://www.facebook.com/profile.php?id=100077046252227"><i class="fa fa-facebook"></i></a></li>
                        <li><a class="linkedin" target="_blank" href="https://www.linkedin.com/in/em-hr-hris-software-sistem-hrd-57629722b/"><i class="fa fa-linkedin"></i></a></li>
                        <li><a class="youtube" target="_blank" href="https://www.youtube.com/watch?v=y8h1fB7lSIQ"><i class="fa fa-youtube"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
<style>
    
</style>