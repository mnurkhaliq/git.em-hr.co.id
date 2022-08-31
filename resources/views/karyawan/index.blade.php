@extends('layouts.karyawan')

@section('title', 'Dashboard')

@section('sidebar')

@endsection

@section('content')
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">HOME</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <form method="GET" action="" style="float: right; width: 40%;">
                    <div class="form-group">
                        <i class="fa fa-search-plus" style="float: left;font-size: 20px;margin-top: 9px;margin-right: 12px;"></i>
                        <input type="text" name="keyword-karyawan" class="form-control autocomplete-karyawan" style="float:left;width: 80%;margin-right: 5px;" placeholder="Search Employee Here">
                    </div>
                </form>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">
            
            <div class="col-lg-12 col-sm-12 col-md-12" id="content_search_karyawan"></div>
            @if($absensiData['shift'] != null || $absensiData['shift'] == null)
            <div class="col-lg-8 col-sm-8 col-md-8" style="padding: 0px">
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 20px; background: #0E9A88;"> 
                        <div class="panel-body" style="background: #0E9A88; border:1px solid #0E9A88;">
                            @if($absensiData['absensi'] == null)
                            <div class="col-md-7"> 
                                <span><h2 style="margin-bottom:0;color:white; font-weight: 500; font-size: 18px;">You can clock in now <a href="{{route('karyawan.detail.clock-in')}}" class="btn" style="color: white; font-size:18px; background:#cbe653;"> CLOCK IN </a></h2></span>
                            </div>
                            @elseif($absensiData['absensi'] != null && $absensiData['absensi']['clock_out'] == null)
                            <div class="col-md-7"> 
                                <h2 style="margin-bottom:0;color:white; font-weight: 500; font-size: 18px;">You  already did clock in today <a href="{{route('karyawan.detail.clock-out')}}" class="btn " style="color: white; background:#bd332b; font-size:18px;"> CLOCK OUT </a></h2>
                            </div>
                            @else
                            <div class="col-md-7"> 
                                <h2 style="margin-bottom:0;color:white; font-weight: 500; font-size: 18px;">You  already did clock in today <a href="{{route('karyawan.detail.clock-in')}}" class="btn" style="color: white; font-size:18px; background:#cbe653;"> ADD CLOCK IN </a></h2>
                            </div>
                            @endif
                            <div class="col-md-5"> 
                                <h2 style="margin-bottom:0;color:white; font-weight: 500; font-size: 18px;">Your attendance history, click <a href="{{route('karyawan.profile', ['tab' => 'attendance'])}}" class="btn btn-xs btn-warning" style="color: white"> here </a></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else 
            <div class="col-lg-8 col-sm-8 col-md-8" style="padding: 0px">
                <div class="col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 20px; background: #0E9A88;"> 
                        <div class="panel-body" style="background: #0E9A88; border:1px solid #0E9A88;">
                            <h2 style="margin-bottom:0;color:white; font-weight: 500; font-size: 18px;">You don't have any shift! Please contact Admin.</h2>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-lg-4 col-sm-4 col-md-4" style="padding: 0px">
                {{-- <div class="col-md-12">
                    <div id="my-holiday"></div>
                </div> --}}
                @forelse($ulang_tahun as $i)
                @if(count($ulang_tahun) == 1)
                <div class="col-md-12">
                    <div class="col-md-6"  style="padding: 0px">
                        <div class="panel panel-warning" style="margin-bottom: 20px;background: #999c9b">
                            <div class="panel-body">
                                <div class="col-md-6" style="padding: 0px;">
                                    <img src="{{ asset('images/Birthday_Cake_Only.png') }}" style="width: 75px;float: center;" class="img-responsive"/> 
                                </div>
                                <div class="col-md-6" style="padding: 0px">
                                    <p style="margin:0;color:white; font-weight: 490; font-size: 10px;" class="text-center">HAPPY BIRTHDAY</p>
                                    <p style="margin:0;color: #f0a6cc; font-weight: 490; font-size: 10px;" class="text-center"> TODAY</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"  style="padding: 0px">
                        <div class="panel panel-warning" style="margin-bottom: 20px;background: #fefe9f">
                            <div class="panel-body">
                                <div class="col-md-12" style="padding: 0px">
                                    <div class="col-md-4" style="padding: 0px">
                                        @if(empty($i->foto))
                                            @if($i->jenis_kelamin == 'Female')
                                                <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 50px;float: left;" class="img-responsive">
                                            @elseif($i->jenis_kelamin == 'Male')
                                                <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 50px;float: left;" class="img-responsive">
                                            @else 
                                            <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" class="img-responsive">
                                            @endif
                                        @else
                                            <img src="{{ asset('storage/foto/'. $i->foto) }}" style="width: 60px;float: left;" class="img-responsive">
                                        @endif
                                    </div>
                                    <div class="col-md-8" style="padding: 0px">
                                        <p style="margin-bottom:0;color: #4d9a00;font-weight: 490; font-size: 10px;"><strong>{{ $i->nik }} - {{strtok($i->name, " ")}}</strong></p>
                                        <p style="margin-top:0px;margin-bottom: 0px;font-size: 8px;">{{ isset($i->cabang->name) ? $i->cabang->name : '' }}</p>
                                        <p style="margin-top:0px;margin-bottom: 0px;font-size: 8px;">{{ isset($i->structure->position) ? $i->structure->position->name:''}}{{ isset($i->structure->division) ? ' - '. $i->structure->division->name:''}}{{ isset($i->structure->title) ? ' - '. $i->structure->title->name:'' }}</p>
                                        @if($i->id == auth()->user()->id)
                                        <p style="text-align: right;margin-top:0px;margin-bottom: 0px; padding:0px; color: #4d9a00; font-size: 7px;"><a href="{{ route('karyawan.notification.more', ['tab' => 'myBirthday']) }}" style="color:black;">View Detail >>>> </a></p>
                                        @else
                                        <p style="text-align: right;margin-top:0px;margin-bottom: 0px; padding:0px; color: #4d9a00; font-size: 7px;"><a href="{{ route('karyawan.notification.more', ['tab' => 'otherBirthday']) }}" style="color:black;">View Detail >>>> </a></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else 
                <div class="col-lg-12 col-sm-12 col-md-12 slideshow-container">
                    <div class="mySlides fadeSlides" style="padding: 0px;">
                        <div class="col-md-6"  style="padding: 0px">
                            <div class="panel panel-warning" style="margin-bottom: 20px;background: #999c9b">
                                <div class="panel-body">
                                    <div class="col-md-6" style="padding: 0px;">
                                        <img src="{{ asset('images/Birthday_Cake_Only.png') }}" style="width: 75px;float: center;" class="img-responsive"/> 
                                    </div>
                                    <div class="col-md-6" style="padding: 0px">
                                        <p style="margin:0;color:white; font-weight: 490; font-size: 10px;" class="text-center">HAPPY BIRTHDAY</p>
                                        <p style="margin:0;color: #f0a6cc; font-weight: 490; font-size: 10px;" class="text-center"> TODAY</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"  style="padding: 0px">
                            <div class="panel panel-warning" style="margin-bottom: 20px;background: #fefe9f">
                                <div class="panel-body">
                                    <div class="col-md-12" style="padding: 0px">
                                        <div class="col-md-4" style="padding: 0px">
                                            @if(empty($i->foto))
                                                @if($i->jenis_kelamin == 'Female')
                                                    <img src="{{ asset('images/Birthday_Female_Icon.png') }}" style="width: 50px;float: left;" class="img-responsive">
                                                @elseif($i->jenis_kelamin == 'Male')
                                                    <img src="{{ asset('images/Birthday_Male_Icon.png') }}" style="width: 50px;float: left;" class="img-responsive">
                                                @else 
                                                <img src="{{ asset('images/user.png') }}" style="width: 60px;float: left;" class="img-responsive">
                                                @endif
                                            @else
                                                <img src="{{ asset('storage/foto/'. $i->foto) }}" style="width: 60px;float: left;" class="img-responsive">
                                            @endif
                                        </div>
                                        <div class="col-md-8" style="padding: 0px">
                                            <p style="margin-bottom:0;color: #4d9a00;font-weight: 490; font-size: 10px;"><strong>{{ $i->nik }} - {{strtok($i->name, " ")}}</strong></p>
                                            <p style="margin-top:0px;margin-bottom: 0px;font-size: 8px;">{{ isset($i->cabang->name) ? $i->cabang->name : '' }}</p>
                                            <p style="margin-top:0px;margin-bottom: 0px;font-size: 8px;">{{ isset($i->structure->position) ? $i->structure->position->name:''}}{{ isset($i->structure->division) ? ' - '. $i->structure->division->name:''}}{{ isset($i->structure->title) ? ' - '. $i->structure->title->name:'' }}</p>
                                            @if($i->id == auth()->user()->id)
                                            <p style="text-align: right;margin-top:0px;margin-bottom: 0px; padding:0px; color: #4d9a00; font-size: 7px;"><a href="{{ route('karyawan.notification.more', ['tab' => 'myBirthday']) }}" style="color:black;">View Detail >>>> </a></p>
                                            @else
                                            <p style="text-align: right;margin-top:0px;margin-bottom: 0px; padding:0px; color: #4d9a00; font-size: 7px;"><a href="{{ route('karyawan.notification.more', ['tab' => 'otherBirthday']) }}" style="color:black;">View Detail >>>> </a></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="prev_ultah" onclick="plusSlides(-1)">❮</a>
                        <a class="next_ultah" onclick="plusSlides(1)">❯</a>
                    </div>
                </div>
                @endif
                @empty 
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <div class="panel panel-warning" style="margin-bottom: 20px; background: #999c9b;">
                        <div class="panel-body">
                            <div class="col-md-3" style="padding: 0px">
                                <img src="{{ asset('images/Birthday_Cake_Only.png') }}" style="width: 75px;float: center;" class="img-responsive"/> 
                            </div>
                            <div class="col-md-9" style="padding: 0px">
                                <p style="margin:0;color:white; font-weight: 490; font-size: 17px;" class="text-center">NO ONE'S BIRTHDAY</p>
                                <p style="margin:0;color: #0E9A88; font-weight: 490; font-size: 17px;" class="text-center"> TODAY</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="col-lg-8 col-sm-8 col-md-8" style="padding: 0px">
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="panel panel-themecolor" style="margin-bottom: 20px">
                        <div class="panel-heading" style="background: #bd332b; border:1px solid #bd332b;"><i class="fa fa-list-alt"></i> News List</div>
                        <div class="panel-body">
                            @foreach($news->take(2) as $item)
                                <div class="col-md-4" style="padding:0;">
                                    @if(!empty($item->thumbnail) && file_exists(public_path('storage/news/').$item->thumbnail))
                                    <a href="{{ route('karyawan.news.readmore', $item->id) }}">
                                        <img src="{{ asset('storage/news/'. $item->thumbnail) }}" style="width: 100%; height:80px;" />
                                    </a>
                                    @endif
                                </div>
                                <div class="col-md-8" style="padding-right:0;">
                                    <a href="{{ route('karyawan.news.readmore', $item->id) }}">
                                        <h4 style="padding-bottom:0; margin-bottom:0;padding-top:0;margin-top:0;color:#bd332b">{!! substr(strip_tags($item->title),0, 20) !!}</h4>
                                    </a>
                                    <p style="margin-bottom:0;"><small>{{ date('d F Y H:i', strtotime($item->created_at)) }}</small></p>
                                    <p>{!! substr(strip_tags($item->content),0, 40) !!}</p>
                                </div>
                                <div class="clearfix"></div>
                                <hr style="margin-top:10px; margin-bottom:10px;" />
                            @endforeach
                            <br />
                            <a href="{{ route('karyawan.news.more') }}" class="btn btn-rounded btn-danger btn-block p-10" style="color: white;"><i class="fa fa-list"></i> More</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="panel panel-themecolor" style="margin-bottom: 20px">
                        <div class="panel-heading" style="background: #2cabe3; border:1px solid #2cabe3;"><i class="fa fa-info-circle"></i> Internal Memo</div>
                        <div class="panel-body">
                            @foreach($internal_memo->take(2) as $item)
                                <div class="col-md-4" style="padding:0;">
                                    @if(!empty($item->thumbnail) && file_exists(public_path('storage/internal-memo/').$item->thumbnail))
                                    <a href="{{ route('karyawan.internal-memo.readmore', $item->id) }}">
                                        <img src="{{ asset('storage/internal-memo/'. $item->thumbnail) }}" style="width: 100%; height:80px;" />
                                    </a>
                                    @endif
                                </div>
                                <div class="col-md-8" style="padding-right:0; {{ !empty($item->file) && file_exists(public_path('storage/internal-memo/').$item->file) ? 'padding-right: 25px;' : '' }}">
                                    <a href="{{ route('karyawan.internal-memo.readmore', $item->id) }}">
                                        <h4 style="padding-bottom:0; margin-bottom:0;padding-top:0;margin-top:0;color:#2cabe3;">{!! substr(strip_tags($item->title),0, 20) !!}</h4>
                                    </a>
                                    <p style="margin-bottom:0;"><small>{{ date('d F Y H:i', strtotime($item->created_at)) }}</small></p>
                                    <p>{!! substr(strip_tags($item->content),0, 40) !!}</p>
                                    @if(!empty($item->file) && file_exists(public_path('storage/internal-memo/').$item->file))
                                    <p style="position: absolute;top: 0;right: 0; font-size: 20px;color: #2cabe3;">
                                        <i class="fa fa-regular fa-file"></i>
                                    </p>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <hr style="margin-top:10px; margin-bottom:10px;" />
                            @endforeach
                            <br />
                            <a href="{{ route('karyawan.internal-memo.more') }}" class="btn btn-rounded btn-info btn-block p-10" style="color: white;"><i class="fa fa-info-circle"></i> More</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4 col-sm-4 col-md-4" style="padding: 0px">
                <div class="col-md-12">
                    <div class="panel panel-themecolor" style="margin-bottom: 20px">
                        <div class="panel-heading" style="background: white; border-bottom:1px solid #0E9A88; color:#0E9A88;margin-bottom: 0px"><i class="fa fa-bell"></i> Notifications</div>
                        <div class="panel-body">
                            <h4 style="margin: 0px;">New (<span id="new-notif">0</span>) {{count($ulang_tahun) > 0 ? 'Birthday ('.count($ulang_tahun).')' : '' }}</h4>
                            <div id="notification">
                                <h5>There is no new notification yet</h5>
                            </div>
                            <br />
                            <div class="col-md-12">
                            <h6 style="text-align: right; color:black;"><a href="{{ route('karyawan.notification.more') }}" style="color:black;">View More >>>> </a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-sm-8 col-md-8" style="padding: 0px">
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="panel panel-themecolor" style="margin-bottom: 20px">
                        <div class="panel-heading" style="background: #53e69d; border:1px solid #53e69d;"><i class="fa fa-gavel"></i> Product Information</div>
                        <div class="panel-body">
                            @foreach($product->take(2) as $item)
                                <div class="col-md-4" style="padding:0;">
                                    @if(!empty($item->thumbnail) && file_exists(public_path('storage/product/').$item->thumbnail))
                                    <a href="{{ route('karyawan.product.readmore', $item->id) }}">
                                        <img src="{{ asset('storage/product/'. $item->thumbnail) }}" style="width: 100%;" />
                                    </a>
                                    @endif
                                </div>
                                <div class="col-md-8" style="padding-right:0; {{ !empty($item->file) && file_exists(public_path('storage/product/').$item->file) ? 'padding-right: 25px;' : '' }}">
                                    <a href="{{ route('karyawan.product.readmore', $item->id) }}"><h4 style="padding-bottom:0; margin-bottom:0;padding-top:0;margin-top:0;color:#53e69d;">{!! $item->title !!}</h4></a>
                                    <p style="margin-bottom:0;"><small>{{ date('d F Y H:i', strtotime($item->created_at)) }}</small></p>
                                    <p>{!! substr(strip_tags($item->content),0, 50) !!}</p>
                                    @if(!empty($item->file) && file_exists(public_path('storage/product/').$item->file))
                                    <p style="position: absolute;top: 0;right: 0; font-size: 20px;color: #53e69d;">
                                        <i class="fa fa-regular fa-file"></i>
                                    </p>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                                <hr style="margin-top:10px; margin-bottom:10px;" />
                            @endforeach
                            <br />
                            <a href="{{ route('karyawan.internal-memo.more') }}" class="btn btn-rounded btn-success btn-block p-10" style="color: white;"><i class="fa fa-info-circle"></i> More</a>
                        </div>
                    </div>
                </div>

                @if(checkModule(27))
                <div class="col-lg-6 col-sm-6 col-md-6">
                    <div class="panel panel-warning" style="margin-bottom: 20px">
                        <div class="panel-heading"><i class="fa fa-user-plus"></i> Internal Recruitment</div>
                        <div class="panel-body">
                            @foreach($internal_vacancy->take(2) as $vacancy)
                            <div class="col-md-12" style="padding:0;">
                                <a href="{{route('karyawan.internal-recruitment.detail',$vacancy->recruitment_id)}}"><h4 style="margin-top: 0px; margin-bottom: 5px; color: #ffa421">{{ $vacancy->job_position }}</h4></a>
                                <p style="margin-bottom: 0px;">Published on: {{ date('d F Y',strtotime($vacancy->posting_date)) }}</p>
                                @if($vacancy->last_posted_date)
                                <p style="margin-bottom: 0px;">Expired on: {{ date('d F Y',strtotime($vacancy->last_posted_date)) }}</p>
                                @endif
                                <p style="margin-bottom: 10px;"></p>
                                @if($vacancy->show_salary_range == '1')
                                <p style="margin-bottom: 0px"><i class="fa fa-dollar"></i> IDR {{format_idr($vacancy->min_salary)." - ".format_idr($vacancy->max_salary)}}</p>
                                @endif
                                <p style="margin-bottom: 0px"><i class="fa fa-map-marker"></i> {{$vacancy->branch}}</p>
                            </div>
                            <div class="clearfix"></div>
                            <hr style="margin-top:10px; margin-bottom:10px;" />
                            @endforeach
                            <br />
                            <a href="{{ route('karyawan.internal-recruitment.more') }}" class="btn btn-rounded btn-warning btn-block p-10" style="color: white;"><i class="fa fa-user-plus"></i> More </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4 col-sm-4 col-md-4" style="padding: 0px">
                <div class="col-md-12">
                    <div id="my-calendar"></div>
                </div>
            </div>
            <div class="clearfix"></div>

            <!-- <div class="col-md-4">
                <div class="white-box">
                    <h2 class="box-title">Ulang Tahun</h2>
                    <hr />
                    @foreach($ulang_tahun as $i)
                        <div class="col-md-6">
                            <div class="col-md-3">
                                @if(empty($i->foto))
                                    <img src="{{ asset('admin-css/images/user.png') }}" alt="varun" class="img-circle img-responsive">
                                @else
                                    <img src="{{ asset('storage/foto/'. Auth::user()->foto) }}" alt="varun" class="img-circle img-responsive">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <p style="margin-bottom:0;color: #4d9a00;"><strong>{{ $i->name }}</strong></p>
                                <p style="margin-top:5px;margin-bottom: 10px;">{{ $i->nik }}</p>
                                <h5>{{ isset($i->cabang->name) ? $i->cabang->name : '' }}</h5>
                                <h5>{{ isset($i->organisasi_job_role) ? $i->organisasi_job_role : '' }}</h5>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    @endforeach
                    
                    <div class="clearfix"></div>
                </div>
            </div> -->
        </div>
        <!-- /.container-fluid -->
        @include('layouts.footer')
    </div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
    .hp {
        width: 130px;
        /* position: absolute; */
        bottom: 38px;
        left: 153px;
    }
    @media (min-width: 1600px) {
        .birthday {
            width: 50%;
        }
        .birthday .panel-body {
            padding: 15px;
        }
        .hp {
            width: 78px;
        }
    }
    .type-4 {
        background-color: #992ce3;
        color: black;
    }
    .type-3 {
        background-color: #FA2601;
        color: black;
    }
    .type-2 {
        background-color: #FA8A00;
        color: black;
    }
    .type-1 {
        background-color: #2cabe3;
        color: black;
    }
    .day {
        height: 40px;
        vertical-align: middle;
        font-size: medium;
        font-weight: bold;
    }
    .calendar-month-header td {
        vertical-align: middle !important;
    }
    .zabuto_calendar {
        padding: 10px !important;
        background-color: white;
    }
    #my-holiday, #my-holiday div {
        background-color: white;
        color: #FA2601;
        font-weight: bold;
    }
    #my-holiday div:last-child, #my-holiday div:nth-last-child(2) {
        padding-bottom: 10px;
    }
    
    .mySlides {display: none}
    img {vertical-align: middle;}

    /* Slideshow container */
    .slideshow-container {
        position: relative;
        /* margin: auto; */
    }

    /* Next & previous buttons */
    .prev_ultah, .next_ultah {
    cursor: pointer;
    position: absolute;
    top: 50%;
    width: auto;
    padding: 16px;
    margin-top: -22px;
    color: white;
    font-weight: bold;
    font-size: 16px;
    transition: 0.6s ease;
    border-radius: 0 3px 3px 0;
    user-select: none;
    }
    
    .prev_ultah {
    left:0;
    border-radius: 3px 0 0 3px;
    }

    /* Position the "next button" to the right */
    .next_ultah {
    right: 0;
    border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev_ultah:hover, .next_ultah:hover {
    background-color: rgba(0,0,0,0.8);
    }


    /* Fading animation */
    .fadeSlides {
    animation-name: fade;
    animation-duration: 10000s;
    }

    @keyframes fadeSlides {
    from {opacity: 1} 
    to {opacity: 1}
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {
    .prev_ultah, .next_ultah,.text {font-size: 11px}
    }
</style>
@section('footer-script')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Example style -->
<link rel="stylesheet" type="text/css" href="https://zabuto.com/assets/css/style.css">
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/examples/style.css">

<!-- Zabuto Calendar -->
<script src="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://www.zabuto.com/dev/calendar/zabuto_calendar.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>

<script type="text/javascript">
    firebase.initializeApp({
        databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
    });
    fbDatabase = firebase.database().ref("{{ env('SERVER') . '/' . strtolower(session('company_url')) . '/' . \Auth::user()->id }}")

    fbDatabase.orderByChild("read").equalTo(0).on('value', (snapshot) => {
        $('#new-notif').html(snapshot.val() ? Object.keys(snapshot.val()).length : 0);
    });

    fbDatabase.orderByChild("read").equalTo(0).limitToLast(4).on('value', (snapshot) => {
        if (snapshot.val()) {
            let notification = '';
            snapshot.forEach((childSnapshot) => {
                notification = '<a onclick="readURL(\'{{ url("") }}'+childSnapshot.val().link+'\', \''+childSnapshot.key+'\', '+(moment(childSnapshot.val().time).format('DD-MM-YYYY') != moment().format('DD-MM-YYYY') && childSnapshot.val().type.includes("birthday"))+')" href="javascript:;">'+
                        '<div class="col-md-9">'+
                            '<h5>'+childSnapshot.val().notif+'</h5>'+
                        '</div>'+
                        '<div class="col-md-3 text-right">'+
                            '<h5 '+(childSnapshot.val().read ? '' : 'style="color: #bd332b"')+'>'+convert(childSnapshot.val().time)+'</h5>'+
                        '</div>'+
                        '<div class="col-md-12">'+
                            '<p style="margin: 0px; color:'+(childSnapshot.val().read ? 'black' : '#bd332b')+';">'+childSnapshot.val().text+'</p>'+
                            '<hr style="margin: 0px; border-color:#e9cda5;">'+
                        '</div>'+
                    '</a>' + notification;
            });
            $('#notification').html(notification);
        } else {
            $('#notification').html('<h5>There is no new notification yet</h5>');
        }
    });

    function readURL(url, key, idle = false) {
        fbDatabase.child(key).update({'/read': 1});

        if (!idle) {
            window.open(url, '_blank');
        }
    }

    function convert(time) {
        if (moment.duration(moment().diff(moment(time))).asMinutes() < 1) {
            return 'just now';
        } else if (moment.duration(moment().diff(moment(time))).asMinutes() < 2) {
            return 'a minute ago';
        } else if (moment.duration(moment().diff(moment(time))).asMinutes() < 60) {
            return Math.floor(moment.duration(moment().diff(moment(time))).asMinutes()) + ' minutes ago';
        } else if (moment.duration(moment().diff(moment(time))).asHours() < 2) {
            return 'an hour ago';
        } else if (moment.duration(moment().diff(moment(time))).asHours() < 24) {
            return Math.floor(moment.duration(moment().diff(moment(time))).asHours()) + ' hours ago';
        } else if (moment.duration(moment().diff(moment(time))).asHours() < 48) {
            return 'yesterday';
        } else {
            return moment(time).format('DD-MM-YYYY')
        }
    }

    $(".autocomplete-karyawan" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
                url: "{{ route('ajax.get-karyawan') }}",
                method : 'POST',
                data: {
                    'name': request.term,'_token' : $("meta[name='csrf-token']").attr('content')
                },
                success: function( data ) {
                    response( data );
                }
            });
        },
        select: function( event, ui ) {
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : ui.item.id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    data = data.data;

                    var el = '<div class="panel panel-themecolor" style="position:relative;"><div class="panel-body"><i class="ti-close" onclick="tutup_ini(this)" style=" position: absolute;right: 36px;top: 18px;color: red;cursor:pointer;"></i><div class="table-responsive">';
                        el += '<table class="table table-striped">';
                        el += '<thead><tr>';
                        el += '<th>NIK</th>';
                        el += '<th>NAMA</th>';
                        el += '<th>TELEPON</th>';
                        el += '<th>EMAIL</th>';
                        el += '<th>EXT</th>';
                        el += '<th>JOB RULE</th>';
                        el += '</tr></thead>';

                        el += '<tbody><tr>';
                        el += '<td>'+ data.nik +'</td>';
                        el += '<td>'+ data.name +'</td>';
                        el += '<td>'+ (data.telepon == null ? '' : data.telepon ) +'</td>';
                        el += '<td>'+ (data.email == null ? '' : data.email) +'</td>';
                        el += '<td>'+ (data.ext ==null ? '' : data.ext) +'</td>';
                        el += '<td>'+ data.position +'</td>';
                        el += '</tr></tbody>';
                        el += '</table>';
                        el += '</div></div></div>'

                        $("#content_search_karyawan").prepend(el);

                    setTimeout(function(){
                        $(".autocomplete-karyawan").val(" ");

                        $(".autocomplete-karyawan").triggerHandler("focus");

                    }, 500);
                }
            });

            $(".autocomplete-karyawan" ).val("");
        }
    }).on('focus', function () {
        $(this).autocomplete("search", "");
    });

    function tutup_ini(el) {
        $(el).parent().parent().hide("slow");
    }

    $(document).ready(function() {
        var now = moment().startOf('month')
        function getList() {
            $('#my-holiday').html('')
            $.ajax({
                type: 'GET',
                url: '{{ route('ajax.get-karyawan-calendar') }}',
                data: {
                    'year' : now.format('YYYY'),
                    'month' : now.format('M')
                },
                dataType: 'json',
                success: function (data) {
                    $('#my-holiday').html('')
                    $(data).each(function(key, value) {
                        if(value.classname == 'type-3')
                            $('#my-holiday').append('<div class="col-md-2">'+moment(value.date).format('D MMM')+'</div><div class="col-md-10">'+value.title+'</div>');
                    })
                }
            });
        }

        getList()
        $("#my-calendar").zabuto_calendar({
            ajax: {
                url: '{{ route("ajax.get-karyawan-calendar") }}',
            },
            today: true,
            show_previous: true,
            show_next: true,
            legend: [
                {type: "block", label: "Leave/permit day", classname: 'type-1'},
                {type: "block", label: "Shift off day", classname: 'type-2'},
                {type: "block", label: "Holiday", classname: 'type-3'},
                {type: "block", label: "Other Leave", classname: 'type-4'},
            ],
            action_nav: function () {
                if(this.id.split('-').pop() == 'next')
                    now = now.add(1, 'M')
                else if(this.id.split('-').pop() == 'prev')
                    now = now.subtract(1, 'M')
                getList()
            }
        });
    });

    let slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        if (n > slides.length) {slideIndex = 1}    
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";  
        }

        slides[slideIndex-1].style.display = "block";  
    }
</script>
@endsection

@endsection
