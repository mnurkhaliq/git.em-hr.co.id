<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    @if(get_setting('favicon') != "")
    <link rel="icon" type="image/png" sizes="16x16" href="{{ get_setting('favicon') }}">
    @else
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/clients/empore.png') }}">
    @endif
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('admin-css/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-css/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-css/plugins/bower_components/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-css/plugins/bower_components/morrisjs/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-css/plugins/bower_components/chartist-js/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-css/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-css/plugins/bower_components/calendar/dist/fullcalendar.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin-css/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-css/css/style.css') }}?time=<?=date('His')?>" rel="stylesheet">
    <link href="{{ asset('admin-css/css/font.css') }}?time=<?=date('His')?>" rel="stylesheet">
    <link href="{{ asset('admin-css/css/colors/green.css?v=2') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('admin-css/plugins/bower_components/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <?php 
         $chek_url = @$_SERVER['HTTP_HOST'];
         if (strpos($chek_url, '.local') == false) {
      ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GA_KEY_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ env("GA_KEY_ID") }}');
    </script>
    <?php } ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]> 
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <style type="text/css">
        body {
            font-size: 12px;
        }
        table.table tr th, table.table tr td {
            font-size: 12px;
        }
        .navbar-header {
            background: #eaeaea;
            border-top: 5px solid red;
        }
        .btn-default, .btn-default.disabled {
            background: #e4e7ea !important;
            border: 1px solid #e4e7ea !important;
        }
        @if(get_setting('top_header_color') !="")
            .navbar-header {
                border-top: 5px solid {{ get_setting('top_header_color')  }};
            }
        @else
            .navbar-header {
                border-top: 5px solid #ACCE22;
            }
        @endif

        @if(get_setting('header_color') !="")
            .navbar-header {
                background: {{ get_setting('header_color')  }};
            }
        @else
            .navbar-header {
                background: #0E9A88;
            }
        @endif
        @if(get_setting('menu_color') != "")
            #side-menu > li > a.active {
                background :white;
                color: {{ get_setting('menu_color')  }};
                border-bottom : 3px solid  {{ get_setting('menu_color')  }};
                font-weight: {{ get_setting('header_text_weight')  }};
            }
        @else 
            #side-menu > li > a.active {
                background :white;
                color: #0E9A88;
                border-bottom : 3px solid  #0E9A88;
            }
        @endif
        @if(get_setting('table_color') != "")
            tbody td {
                color: {{ get_setting('table_color')  }};
            }
            tbody td div, tbody td span {
                color: #797979;
            }
        @else
            tbody td {
                color: black;
            }
        @endif
        @if(get_setting('web_font') != "")
            body, h1, h2, h3, h4, h5, h6{
                font-family:   {{ get_setting('web_font')  }};
            }
        @else
            body, h1, h2, h3, h4, h5, h6{font-family:unset;}
        @endif

        @if(get_setting('header_text_color') != "")
            #side-menu > li > a {
                color: {{ get_setting('header_text_color')  }};
                border-bottom: 3px solid transparent;
            }

            .footer {
                color: {{ get_setting('header_text_color')  }};
            }
        @else 
            #side-menu > li > a {
                color: black;
                border-bottom: 3px solid transparent;
            }

            .footer {
                color: black;
            }
        @endif

        @if(get_setting('header_text_weight') != "")
            #side-menu > li > a {
                font-weight: {{ get_setting('header_text_weight')  }};
            }

            .footer {
                font-weight: {{ get_setting('header_text_weight')  }};
            }
        @endif
        @if(get_setting('table_weight') != "")
            tbody td {
                font-weight: {{ get_setting('table_weight')  }};
            }
            tbody td div, tbody td span {
                font-weight: 300;
            }
        @endif
    </style>
</head>
<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="{{ route('administrator.dashboard') }}">
                        @if(get_setting('logo') != "")
                        <span class="hidden-xs">
                            <img src="{{ get_setting('logo') }}" style="height: 40px;" class="light-logo">
                        </span>
                        @endif
                    </a>
                </div>
                <!-- /Logo -->
                <!-- Search input and Toggle icon -->
                <ul class="nav navbar-top-links navbar-left">
                    <li><a href="javascript:void(0)" class="open-close waves-effect waves-light visible-xs"><i class="ti-close ti-menu"></i></a></li>
                    <!-- .Megamenu -->
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="javascript:void(0)"> 
                            @if(!empty(\Auth::user()->foto))
                                <img src="{{ asset('storage/foto/'. \Auth::user()->foto) }}" alt="user-img" width="36" class="img-circle" />
                            @elseif(empty(\Auth::user()->foto) && \Auth::user()->jenis_kelamin == 'Female')
                                <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 36px;" class="img-circle">
                            @elseif(empty(\Auth::user()->foto) && \Auth::user()->jenis_kelamin == 'Male')
                                <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 36px;" class="img-circle">
                            @else
                                <img src="{{ asset('admin-css/images/user.png') }}" alt="user-img" width="36" class="img-circle" />
                            @endif
                            <b style="color:black;" class="hidden-xs">{{ Auth::user()->name }}</b><span class="caret"></span> </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img">
                                        @if(!empty(\Auth::user()->foto))
                                            <img src="{{ asset('storage/foto/'. \Auth::user()->foto) }}" alt="user-img" width="36" class="img-circle" />
                                        @elseif(empty(\Auth::user()->foto) && \Auth::user()->jenis_kelamin == 'Female')
                                            <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 50px;" class="img-circle">
                                        @elseif(empty(\Auth::user()->foto) && \Auth::user()->jenis_kelamin == 'Male')
                                            <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 50px;" class="img-circle">
                                        @else
                                            <img src="{{ asset('admin-css/images/user.png') }}" alt="user-img" width="36" class="img-circle" />
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        <h4 style="color:black;">{{ Auth::user()->name }}</h4>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.profile') }}">@lang('menu.profile')</a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                @include('layouts.nav')
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        @yield('content')

    <script type="text/javascript">
         
    </script>

    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('admin-css/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('admin-css/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{{ asset('admin-css/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
    <!--slimscroll JavaScript -->
    <script src="{{ asset('admin-css/js/jquery.slimscroll.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('admin-css/js/waves.js') }}"></script>
    <!--Counter js -->
    <script src="{{ asset('admin-css/plugins/bower_components/waypoints/lib/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('admin-css/plugins/bower_components/counterup/jquery.counterup.min.js') }}"></script>
    <!--Morris JavaScript -->
    <script src="{{ asset('admin-css/plugins/bower_components/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('admin-css/plugins/bower_components/morrisjs/morris.js') }}"></script>
    <!-- chartist chart -->
    <script src="{{ asset('admin-css/plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <!-- Calendar JavaScript -->
    <script src="{{ asset('admin-css/plugins/bower_components/moment/moment.js') }}"></script>
    <script src="{{ asset('admin-css/plugins/bower_components/calendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('admin-css/plugins/bower_components/calendar/dist/cal-init.js') }}"></script>
    <script src="{{ asset('admin-css/plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>
    <script src="{{ asset('js/jquery.priceformat.min.js') }}"></script>
    
    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('admin-css/js/custom.js') }}"></script>
    <script src="{{ asset('admin-css/js/dashboard1.js') }}?time=<?=date('His')?>"></script>
    <!-- Custom tab JavaScript -->
    <script src="{{ asset('admin-css/js/cbpFWTabs.js') }}"></script>
    <script src="{{ asset('js/bootbox.min.js') }}"></script>

    <!-- start - This is for export functionality only -->
    <script src="{{ asset('admin-css/plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <link rel="stylesheet" href="{{ asset('admin-css/plugins/bower_components/jquery-ui/jquery-ui.css') }}">
    <script src="{{ asset('admin-css/plugins/bower_components/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/general.js?v='. date('His')) }}"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $( document ).on( 'focus', ':input', function(){
                $( this ).attr( 'autocomplete', 'off' );
            });
        });
    </script>

@yield('js')

@yield('footer-script')

@include('layouts.alert')

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
</body>
</html>