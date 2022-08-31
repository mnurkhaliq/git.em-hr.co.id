<!DOCTYPE html>
<html>

<head>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="{{ asset('css/landingpage.css?v=24') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/fontawesome.min.css" integrity="sha384-jLKHWM3JRmfMU0A5x5AkjWkw/EYfGUAGagvnfryNV3F9VqM98XiIH7VBGVoxVSc7" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/landingpage.js') }}"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GA_KEY_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', "{{ env('GA_KEY_ID') }}");
    </script>

    <link rel="stylesheet"
        href="https://rawcdn.githack.com/rafaelbotazini/floating-whatsapp/3d18b26d5c7d430a1ab0b664f8ca6b69014aed68/floating-wpp.min.css">
    <!--Floating WhatsApp javascript-->
    <script type="text/javascript"
        src="https://rawcdn.githack.com/rafaelbotazini/floating-whatsapp/3d18b26d5c7d430a1ab0b664f8ca6b69014aed68/floating-wpp.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GA_KEY_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env("GA_KEY_ID") }}');
    </script>

    <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}" />

    @if(get_setting('favicon') != "")
    <link rel="icon" type="image/png" sizes="16x16" href="{{ get_setting('favicon') }}">
    @else
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/clients/empore.png') }}">
    @endif

    <title>EM-HR - HRIS Application System The Best and Complete</title>
    <meta name="description" content="Get a reliable partner with deep experiences and expertise in software development and Human Resource Management, along with all supporting technology"/>
    <meta name="keywords" content="HRIS Application System, HRM, HRIS, Aplikasi HR, HRD, Payroll, Sistem Payroll, 
        Payroll System, Resource Management, Sistem HRD, Sistem HR, Attendance, Mobile Attendance, Absensi Online,
        Absensi, Aplikasi Kehadiran, Timesheet Management, Aplikasi Penggajian, Software Penggajian, Aplikasi Absensi,
        Penggajian Karyawan, Aplikasi Gaji, Gaji, Aplikasi Kunjungan, Aplikasi Tracking, Software Tracking, Penggajian" />
    <meta property="og:image" content="https://www.talenta.co/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2020/09/shutterstock_1544686769-1-1-e1607517860511.jpg.webp">

    <style type="text/css">
    html {
        height: 100%;
    }

    body {
        font-family: "Trebuchet MS", Helvetica, sans-serif;
        background: url('{{ asset('landing-page/2019-05-28/Background2.png') }}');
        background-size: cover;
        position: relative;
        min-height: 100%;
        padding-bottom: 450px;
    }

    .bg-1 {
        background: url('{{ asset('landing-page/2019-05-28/Background1.png') }}');
        background-size: contain;
    }


    .form form {
        background: white;
    }

    .btn_trial_1 {
        background: url('{{ asset('landing-page/2019-05-28/button trial now.png') }}');
        background-size: cover;
        border: 0;
        width: 252px;
        height: 45px;
        color: white;
        font-size: 20px;
        cursor: pointer;
    }

    .btn_trial_1:focus{
        box-shadow: none!important;
    }

    .btn_trial_2 {
        background: url('{{ asset('landing-page/2019-05-28/button trial now.png') }}');
        background-size: cover;
        border: 0;
        width: 303px;
        height: 54px;
        color: white;
        font-size: 20px;
        cursor: pointer;

    }

    .btn_login_2 {
        background: url('{{ asset('landing-page/2019-05-28/button trial now.png') }}');
        background-size: cover;
        border: 0;
        width: 252px;
        height: 45px;
        color: white;
        font-size: 20px;
        cursor: pointer;
    }

    .btn_login_2:focus{
        box-shadow: none!important;
    }

    .btn_trial_3 {
        background: url('{{ asset('landing-page/2019-05-28/button trial now.png') }}');
        background-size: cover;
        border: 0;
        width: 303px;
        height: 54px;
        color: white;
        font-size: 20px;
    }

    .bg-form-title {
        background: url('{{ asset('landing-page/2019-05-28/button trial start register.png') }}');
        background-size: cover;
        border: 0;
        width: 100%;
        height: 54px;
        color: white;
        font-size: 20px;
    }

    .section-1 {
        margin-top: 15%;
    }

    .btn_login {
        color: white !important;
        background: #0E9A88;
        width: 150px;
        border-radius: 7px;
        height: 40px;
        margin-right: 25px;
        border: 0;
        font-size: 18px;
        align-items: center;

        /*position:fixed;
            right:0px;
            z-index=99*/
    }

    .btn_detail {
        color: white !important;
        background: #0E9A88;
        width: 150px;
        border-radius: 7px;
        height: 40px;
        border: 0;
        font-size: 18px;
        align-items: center;

        /*position:fixed;
            right:0px;
            z-index=99*/
    }

    .btn_promo {
        color: white !important;
        background: #0E9A88;
        width: 230px;
        border-radius: 7px;
        height: 40px;
        border: 0;
        font-size: 18px;
        align-items: center;

        /*position:fixed;
            right:0px;
            z-index=99*/
    }

    .btn_all_promo {
        color: white !important;
        background: #000;
        width: 170px;
        border-radius: 7px;
        height: 30px;
        border: 0;
        font-size: 15px;
        padding-top: 3px;
        padding-bottom: 0px;
        align-items: center;
        margin-top: 10px;

        /*position:fixed;
            right:0px;
            z-index=99*/
    }

    .btn_try_free {
        color: black !important;
        background: #ACCE22;
        width: 600px;
        border-radius: 7px;
        height: 60px;
        border: 0;
        font-size: 32px;
        align-items: center;
        font-weight: bold;

        /*position:fixed;
            right:0px;
            z-index=99*/
    }
    
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }

    #back2Top {
        width: 50px;
        line-height: 50px;
        overflow: hidden;
        z-index: 2;
        display: none;
        cursor: pointer;
        -moz-transform: rotate(270deg);
        -webkit-transform: rotate(270deg);
        -o-transform: rotate(270deg);
        -ms-transform: rotate(270deg);
        transform: rotate(270deg);
        position: fixed;
        bottom: 300px;
        right: 30px;
        background-color: #DDD;
        color: #555;
        text-align: center;
        font-size: 40px;
        text-decoration: none;
    }

    #back2Top:hover {
        background-color: #0e9a88;
        color: #acce22;
    }
    </style>
</head>

<body>
    <!--whatsapp-->
    <div id="WAButton" style="z-index:1; bottom: 20px;"></div>
    <div>
        <div class="bg-2">

            @include('layouts.nav_landing') 

            @yield('content')

            @include('layouts.footer_landing') 
        </div>
        <div class="modal fade none-border" id="modal-code">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>{{ __("landingpage.login.subtext_company_code") }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <form id="form">
                        <div class="modal-body" id="modal-add-body">
                            <div class="form-group col-md-12">

                                <label style="color: red;" id="error" class="hidden"></label>
                                <div>
                                    <input type="text" id="code" name="code" class="form-control"
                                        placeholder="{{ __('landingpage.login.subtext_enter_company') }}" autocomplete="off">
                                </div>
                                <label id="register">{{ __("landingpage.login.subtext_register_company") }} <a
                                        href="{{ env('URL_CRM','https://testcrm.em-hr.co.id/').'web/signup' }}" onmouseover=""
                                        style="color:deepskyblue;cursor: pointer;">{{ __("landingpage.login.subtext_click_here") }}</a></label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit"
                                class="btn btn-success save-event waves-effect waves-light">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Youtube -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe width="100%" id="yt-player" height="320px"
                                src="https://www.youtube.com/embed/y8h1fB7lSIQ" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                            <!-- <iframe id="yt-player" src="https://www.youtube.com/embed/y8h1fB7lSIQ"> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
        $(function() {
            $('#WAButton').floatingWhatsApp({
                phone: '+6281225561122',
                //WhatsApp Business phone number International format-
                //Get it with Toky at https://toky.co/en/features/whatsapp.
                headerTitle: '{{ __("landingpage.home.text_title_with_us") }}', //Popup Title
                popupMessage: '{{ __("landingpage.home.text_can_help") }}', //Popup Message
                showPopup: true, //Enables popup display
                buttonImage: '<img src="https://rawcdn.githack.com/rafaelbotazini/floating-whatsapp/3d18b26d5c7d430a1ab0b664f8ca6b69014aed68/whatsapp.svg" />', //Button Image
                //headerColor: 'crimson', //Custom header color
                //backgroundColor: 'crimson', //Custom background button color
                position: "right",
                size: '88px'
            });
        });

        function form_free_trial() {
            $('html, body').animate({
                scrollTop: $(".container_bottom").offset().top
            }, 1000);
        }

        @if(Session::has('message-success'))
        alert("{{ Session::get('message-success') }}");
        @endif

        $('#nama').on('input', function() {
            $('#nama2').val($('#nama').val());
        });
        $('#jabatan').change(function() {
            $('#jabatan2').val($('#jabatan').val());
        });
        $('#email').on('input', function() {
            $('#email2').val($('#email').val());
        });
        $('#nama_perusahaan').on('input', function() {
            $('#nama_perusahaan').val($('#nama_perusahaan').val());
        });
        $('#bidang_usaha').on('change', function() {
            $('#bidang_usaha2').val($('#bidang_usaha').val());
        });
        $('#handphone').on('input', function() {
            $('#handphone2').val($('#handphone').val());
        });


        function submitFormPricelist() {
            if ($('#nama2').val() != '' || $('#jabatan2').val() != '' || $('#email2').val() != '' || $(
                    '#nama_perusahaan2')
                .val() != '' || $('#bidang_usaha2').val() != '' || $('#handphone2').val() != '') {
                $('#form-price-list').submit();
            } else {
                bootbox.confirm({
                    title: "<i class=\"fa fa-warning\"></i> EMPORE SYSTEM",
                    message: "Field tidak boleh kosong",
                    closeButton: false,
                    buttons: {

                    },
                    callback: function(result) {
                        if (result) {

                        }
                    }
                });
            }
        }

        function openModal() {
            $('#form')[0].reset();
            $('#error').addClass('hidden');
            $("#modal-code").modal('show');
        }
        $('#form').on('submit', function() {
            var code = $('#code').val();
            $.ajax({
                url: "{{route('check-code')}}",
                type: "POST",
                data: {
                    'code': code,
                    '_token': "{{csrf_token()}}"
                },
                dataType: "JSON",
                success: function(data) {
                    if (data.status == 'success') {
                        $('#error').addClass('hidden');
                        window.location.href = "{{url('/')}}/" + code;
                    } else {
                        $('#error').html(data.message);
                        $('#error').removeClass('hidden');
                    }
                    console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
            return false;
        });

        function scrollToRegister() {
            $('#modal-code').modal('hide');
            form_free_trial();
        }
        $(function() {
            setTimeout(function() {
                var hash = window.location.hash;

                //The result of x will be:

                if (hash == '#register') {
                    form_free_trial();
                }
                @if(count($errors) > 0)
                form_free_trial();
                @endif
            }, 200);
        });

        /*Scroll to top when arrow up clicked BEGIN*/
        $(window).scroll(function() {
            var height = $(window).scrollTop();
            if (height > 100) {
                $('#back2Top').fadeIn();
                $('#register2Top').fadeIn();
            } else {
                $('#back2Top').fadeOut();
                $('#register2Top').fadeOut();
            }
        });
        $(document).ready(function() {
            $("#back2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
                return false;
            });
            $("#register2Top").click(function(event) {
                event.preventDefault();
                $("html, body").animate({
                    scrollTop: 0
                }, "slow");
                return false;
            });

        });
        /*Scroll to top when arrow up clicked END*/

        $(document).ready(function() {
            $('#exampleModalCenter').on('hidden.bs.modal', function() {
                var $this = $(this).find('iframe'),
                    tempSrc = $this.attr('src');
                $this.attr('src', "");
                $this.attr('src', tempSrc);
            });
        });

        </script>
        <a id="back2Top" title="Back to top" href="#">&#10148;</a>
</body>

</html>