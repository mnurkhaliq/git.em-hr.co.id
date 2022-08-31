@extends('layouts.karyawan')

@section('title', 'Profile')

@section('sidebar')

@endsection

@section('content')


    <style>
        .timeline {
            position: relative;
            max-width: 100%;
            margin: 0 auto;
        }

        /* The actual timeline (the vertical ruler) */
        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: #eaeaea;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }

        /* contain around content */
        .contain {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
        }

        /* The circles on the timeline */
        .contain::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -12px;
            background-color: white;
            border: 4px solid #FF9F55;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        /* Place the contain to the left */
        .left {
            left: 0;
        }

        .left-future {
            left: 0;
        }

        .left-current {
            left: 0;
        }

        .left-future::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -12px;
            background-color: white;
            border: 4px solid green;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .left-current::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -12px;
            background-color: #FF9F55;
            border: 4px solid #FF9F55;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        /* Place the contain to the right */
        .right {
            left: 50%;
        }

        .right-future {
            left: 50%;
        }

        .right-current {
            left: 50%;
        }

        .right-future::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -12px;
            background-color: white;
            border: 4px solid green;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        .right-current::after {
            content: '';
            position: absolute;
            width: 25px;
            height: 25px;
            right: -12px;
            background-color: #FF9F55;
            border: 4px solid #FF9F55;
            top: 15px;
            border-radius: 50%;
            z-index: 1;
        }

        /* Add arrows to the left contain (pointing right) */
        .left::before {
            content: " ";
            height: 0;
            position: absolute;
            top: 22px;
            width: 0;
            z-index: 1;
            right: 30px;
            border: medium solid white;
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent #eaeaea;
        }

        .left-future::before {
            content: " ";
            height: 0;
            position: absolute;
            top: 22px;
            width: 0;
            z-index: 1;
            right: 30px;
            border: medium solid white;
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent #eaeaea;
        }

        .left-current::before {
            content: " ";
            height: 0;
            position: absolute;
            top: 22px;
            width: 0;
            z-index: 1;
            right: 30px;
            border: medium solid white;
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent #eaeaea;
        }

        /* Add arrows to the right contain (pointing left) */
        .right::before {
            content: " ";
            height: 0;
            position: absolute;
            top: 22px;
            width: 0;
            z-index: 1;
            left: 30px;
            border: medium solid white;
            border-width: 10px 10px 10px 0;
            border-color: transparent #eaeaea transparent transparent;
        }

        .right-future::before {
            content: " ";
            height: 0;
            position: absolute;
            top: 22px;
            width: 0;
            z-index: 1;
            left: 30px;
            border: medium solid white;
            border-width: 10px 10px 10px 0;
            border-color: transparent #eaeaea transparent transparent;
        }

        .right-current::before {
            content: " ";
            height: 0;
            position: absolute;
            top: 22px;
            width: 0;
            z-index: 1;
            left: 30px;
            border: medium solid white;
            border-width: 10px 10px 10px 0;
            border-color: transparent #eaeaea transparent transparent;
        }

        /* Fix the circle for containers on the right side */
        .right::after {
            left: -12px;
        }

        .right-future::after {
            left: -12px;
        }

        .right-current::after {
            left: -12px;
        }

        /* The actual content */
        .content {
            padding: 20px 30px;
            background-color: #eaeaea;
            position: relative;
            border-radius: 6px;
        }

        /* Media queries - Responsive timeline on screens less than 600px wide */
        @media screen and (max-width: 600px) {
            /* Place the timelime to the left */
            .timeline::after {
                left: 31px;
            }

            /* Full-width containers */
            .contain {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            /* Make sure that all arrows are pointing leftwards */
            .contain::before {
                left: 60px;
                border: medium solid white;
                border-width: 10px 10px 10px 0;
                border-color: transparent white transparent transparent;
            }

            /* Make sure all circles are at the same spot */
            .left::after, .right::after {
                left: 15px;
            }

            /* Make all right containers behave like the left ones */
            .right {
                left: 0%;
            }
        }
    </style>
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">HOME</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Home</a></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-lg-3">
                <div class="panel">
                    <div class="p-30">
                        <div class="row">
                            <div class="col-xs-4 col-sm-5 p-0">
                                @if(empty(\Auth::user()->foto) && empty(Auth::user()->jenis_kelamin))
                                <img src="{{ asset('admin-css/images/user.png') }}" alt="varun" class="img-circle img-responsive">
                                @elseif(empty(\Auth::user()->foto) && Auth::user()->jenis_kelamin == 'Female')
                                <img src="{{ asset('images/Birthday_Female_Icon.png') }}" width="120px;" height="120px;" class="img-circle img-responsive">
                                @elseif(empty(\Auth::user()->foto) && Auth::user()->jenis_kelamin == 'Male')
                                <img src="{{ asset('images/Birthday_Male_Icon.png') }}" width="120px;" height="120px;" class="img-circle img-responsive">
                                @else
                                <img src="{{ asset('storage/foto/'. Auth::user()->foto) }}" alt="varun" class="img-circle img-responsive">
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-7">
                                <h2 class="m-b-0">{{ Auth::user()->name }}</h2>
                                <h4>{{ empore_jabatan(Auth::user()->id) }}</h4>
                                <a class="btn btn-info btn-xs" id="change_password">Change Password <i class="fa fa-key"></i></a>
                                @if(Auth::user()->last_change_password !== null) 
                                    <p>Last Update :  {{ date('d F Y H:i', strtotime(Auth::user()->last_change_password)) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-20 text-center">
                        <table class="table table-hover">
                            <tr>
                                <th>NIK</th>
                                <th> : {{ Auth::user()->nik }}</th>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <th> : {{ Auth::user()->email }}</th>
                            </tr>
                            <tr>
                                <th>Place of birth</th>
                                <th> : {{ Auth::user()->tempat_lahir }}</th>
                            </tr>
                            <tr>
                                <th>Date of birth</th>
                                <th> : {{ Auth::user()->tanggal_lahir }}</th>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <th> : {{ Auth::user()->jenis_kelamin }}</th>
                            </tr>
                            <tr>
                                <th>Religion</th>
                                <th> : {{ Auth::user()->agama }}</th>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-9 p-0">
                <div class="white-box">
                    <div class="panel">
                         <ul class="nav customtab nav-tabs" role="tablist">
                            <li role="presentation" class="{{ !$tab ? 'active' : '' }}"><a href="#dependent" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Dependent</span></a></li>
                            <li role="presentation" class=""><a href="#education" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Education</span></a></li>
                            <li role="presentation" class=""><a href="#certification" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Training</span></a></li>
                            <li role="presentation" class=""><a href="#department" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Branch and Position</span></a></li>
                             @if(checkModule(26))
                             <li role="presentation" class=""><a href="#career" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Career</span></a></li>
                             @endif
                             <li role="presentation" class=""><a href="#rekening_bank" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Bank Account</span></a></li>
                            <li role="presentation" class="{{ $tab == 'inventory' ? 'active' : '' }}"><a href="#inventaris" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Facilities</span></a></li>
                            <li role="presentation" class=""><a href="#shift" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Shift</span></a></li>
                            <li role="presentation" class=""><a href="#cuti" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Leave</span></a></li>
                            <li role="presentation" class="{{ $tab == 'attendance' ? 'active' : '' }}"><a href="#absensi" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Attendance</span></a></li>
                            @if(checkModule(28))	
                            <li role="presentation" class=""><a href="#visit" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Visit</span></a></li>	
                            @endif
                        </ul>
                        <div class="tab-content">

                            <!---------------- DEPENDENT TAB ---------------->
                            <div role="tabpanel" class="tab-pane fade {{ !$tab ? 'active in' : '' }}" id="dependent">
                                <h3 class="box-title m-b-0">Dependent</h3>
                                <br />
                                <br />
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Relationship</th>
                                            <th>Contact Number</th>
                                            <th>Place of birth</th>
                                            <th>Date of birth</th>
                                            <th>Date of death</th>
                                            <th>Education level</th>
                                            <th>Occupation</th>
                                        </tr>
                                        </thead>
                                        <tbody class="dependent_table">
                                        @foreach($data->userFamily as $no => $item)
                                            <tr>
                                                <td>{{ $no+1 }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->hubungan }}</td>
                                                <th>{{ $item->contact }}</th>
                                                <td>{{ $item->tempat_lahir }}</td>
                                                <td>{{ $item->tanggal_lahir }}</td>
                                                <td>{{ $item->tanggal_meninggal }}</td>
                                                <td>{{ $item->jenjang_pendidikan }}</td>
                                                <td>{{ $item->pekerjaan }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!---------------- EDUCATION TAB ---------------->
                            <div role="tabpanel" class="tab-pane fade" id="education">
                                <h3 class="box-title m-b-0">Education</h3>
                                <br />
                                <br />
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Education</th>
                                            <th>Year of Start</th>
                                            <th>Year of Graduate</th>
                                            <th>School Name</th>
                                            <th>Major</th>
                                            <th>Grade</th>
                                            <th>City</th>
                                        </tr>
                                        </thead>
                                        <tbody class="education_table">
                                        @foreach($data->userEducation as $no => $item)
                                            <tr>
                                                <td>{{ $no+1 }}</td>
                                                <td>{{ $item->pendidikan }}</td>
                                                <td>{{ $item->tahun_awal }}</td>
                                                <td>{{ $item->tahun_akhir }}</td>
                                                <td>{{ $item->fakultas }}</td>
                                                <td>{{ $item->jurusan }}</td>
                                                <td>{{ $item->nilai }}</td>
                                                <td>{{ $item->kota }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table><br /><br />
                                </div>
                            </div>

                            <!---------------- CERIFICATION TAB ---------------->
                            <div role="tabpanel" class="tab-pane fade" id="certification">
                                <h3 class="box-title m-b-0">Training</h3>
                                <br />
                                <br />
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Organizer</th>
                                            <th>Certificate Number</th>
                                            <th>Score</th>
                                            <th>Description</th>
                                        </tr>
                                        </thead>
                                        <tbody class="certification_table">
                                        @foreach($data->userCertification as $no => $item)
                                            <tr>
                                                <td>{{ $no+1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->organizer }}</td>
                                                <td>{{ $item->certificate_number }}</td>
                                                <td>{{ $item->score }}</td>
                                                <td>{{ $item->description }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table><br /><br />
                                </div>
                            </div>


                            <!---------------- DEPARTMENT TAB ---------------->
                            <div role="tabpanel" class="tab-pane fade" id="department">
                                @if(get_setting('struktur_organisasi') == 3)
                                    <div class="form-group">
                                        <label class="col-md-12">Branch</label>
                                        <div class="col-md-6">
                                            <input type="text" name="branch" class="form-control"  readonly="true" value="{{ isset($data->cabang->name) ? $data->cabang->name : '' }}" />
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="col-md-12">Position</label>
                                        <div class="col-md-6">
                                            <input type="text" name="position" class="form-control"  readonly="true" value="{{ isset($data->structure->position->name) ? $data->structure->position->name : '' }}{{ isset($data->structure->division) ? ' - '.$data->structure->division->name : '' }}{{ isset($data->structure->title) ? ' - '.$data->structure->title->name : '' }}" />
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                @else
                                    <div class="form-group">
                                        <label class="col-md-12">Branch Type</label>
                                        <div class="col-md-6">
                                            <select class="form-control" name="branch_type" readonly="true">
                                                <option value=""> - none - </option>
                                                @foreach(['HO', 'BRANCH'] as $item)
                                                    <option {{ $data->branch_type == $item ? ' selected' : '' }}>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group section-cabang" style="{{ $data->branch_type == "HO" ? 'display:none' : ''  }}">
                                        <label class="col-md-12">Branch</label>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <select class="form-control" name="cabang_id" readonly="true">
                                                <option value="">Choose Branch</option>
                                                @foreach(get_cabang() as $item)
                                                    <option value="{{ $item->id }}" {{ $item->id == $data->cabang_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="clearfix" />
                                    </div>
                                    <br class="clearfix" />
                                    <br>
                                    <div class="col-md-12">
                                        <label><input type="checkbox" name="is_pic_cabang" value="1" {{ $data->is_pic_cabang == 1 ? 'checked' : '' }}> Branch PIC</label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr />
                            </div>

                            <div class="section-ho">
                                <div class="form-group">
                                    <label class="col-md-12">Division</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="division_id" readonly="true">
                                            <option value="">Choose Division</option>
                                            @foreach(get_organisasi_division() as $item)
                                                <option value="{{ $item->id }}" {{ $data->division_id == $item->id ? 'selected' : '' }} >{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Department</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="department_id" readonly="true">
                                            <option value="">Choose Department</option>
                                            @foreach(get_organisasi_department($data->division_id) as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $data->department_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Unit / Section</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="section_id" readonly="true">
                                            <option value="">Choose Section</option>
                                            @foreach(get_organisasi_unit() as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $data->section_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Position</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="organisasi_position" readonly="true">
                                            @foreach(get_organisasi_position($data->section_id) as $item)
                                                <option {{ $item->id == $data->organisasi_position ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Job Rule</label>
                                    <div class="col-md-6">
                                        <input type="text" readonly="true" value="{{ $data->organisasi_job_role }}" name="organisasi_job_role" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                            <!-- Tab Visit  -->
                        <div role="tabpanel" class="tab-pane fade" id="visit" >
                            <input type="hidden" id="idUser" value="{{$data->id}}">
                            <table id="tableVisit" class="data_table_no_pagging table table-background">
                                <thead>
                                    <tr>
                                        <th rowspan="1">No</th>
                                        <th rowspan="1">Visit Category</th>
                                        <th rowspan="1">Date</th>
                                        <th rowspan="1">Day</th>
                                        <th rowspan="1">Timezone</th>
                                        <th rowspan="1">Branch Name / Place Name</th>
                                        <th rowspan="1">Location Name</th>
                                        <th rowspan="1">Activity Name</th>
                                        <th rowspan="1">PIC Name</th>
                                        <th rowspan="1">Visit Point</th>
                                </thead>
                                <tbody>
                                    @foreach($visitlistkaryawan as $no => $item)
                                    <tr>
                                        <td >{{ $no+1 }}</td>
                                        <td >{{ $item->master_category_name}}</td>
                                        <td class="tanggalVisit" id="tanggal{{$no+1}}">
                                            @if(!empty($item->longitude) || !empty($item->latitude) || !empty($item->pic))
                                            <a href="javascript:void(0)" data-title="Visit Detail <?=$item->username?> <?=date('d F Y h:i:s A', strtotime($item->visit_time))?>" data-longitude="<?=$item->longitude?>" data-signature="/<?=$item->signature?>" data-description="<?=$item->description?>" data-visittype="<?= $item->master_visit_type_id ?>" data-isoutbranch="<?= $item->isoutbranch ?>" data-visitid="<?=$item->id?>" data-latitude="<?=$item->latitude?>" data-picname="<?=$item->picname?>" data-time="<?=$item->visit_time?>" data-long-branch="<?=$item->branchlongitude?>" data-lat-branch="<?=$item->branchlatitude?>" data-radius-branch="<?=$item->radius_visit?>" data-activity-name="<?=$item->activityname?>" data-justification="{{$item->justification}}" data-placename="{{$item->placename}}" data-cabang="{{$item->cabangDetail?$item->cabangDetail->name:""}}"  data-location="{{$item->locationname}}" onclick="detail_visit(this)" title="Mobile Visit"> {{ $item->visit_time }}</a>
                                            <i title="Mobile Visit" class="fa fa-location-arrow right" style="font-size: 20px;"></i>
                                            @else
                                            {{ $item->visit_time }}
                                            @endif
                                        </td>
                                        @if($item->timetable == 'Sunday')
                                        <td class="hariAbsen" id="hari{{$no+1}}" style="color:red;">{{ $item->timetable }}</td>
                                        @else
                                        <td class="hariAbsen" id="hari{{$no+1}}">{{ $item->timetable }}</td>
                                        @endif
                                        <td >{{ $item->timezone}}</td>
                                        @if($item->master_visit_type_name == 'Unlock' || ( $item->master_visit_type_name == 'Lock' && $item->isoutbranch == 1 ))
                                        <td>{{ $item->placename}}</td>
                                        @else
                                        <td>{{ $item->cabang_name}}</td>
                                        @endif
                                        <td >{{ $item->locationname}}</td>
                                        <td >{{ $item->activityname}}</td>
                                        <td >{{ $item->picname }}</td>
                                        <td >{{ $item->point }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- END Tab Visit  --> 
                            <div role="tabpanel" class="tab-pane fade {{ $tab == 'inventory' ? 'active in' : '' }}" id="inventaris" style="overflow: auto;">
                                <table class="table table-bordered">
                                     <thead>
                                    <tr>
                                        <th width="70" class="text-center">NO</th>
                                        <th>ASSET NUMBER</th>
                                        <th>ASSET NAME</th>
                                        <th>ASSET TYPE</th>
                                        <th>SERIAL/PLAT NUMBER</th>
                                        <th>PURCHASE/RENTAL DATE</th>
                                        <th>ASSET CONDITION</th>
                                        <th>STATUS ASSET</th>
                                        <th>PIC</th>
                                        <th>HANDOVER DATE</th>
                                        <th>STATUS</th>
                                        {{-- <th>ACTION</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data->assets as $no => $item)
                                        <tr>
                                            <td class="text-center">{{ $no+1 }}</td>   
                                            <td>{{ $item->asset_number }}</td>
                                            <td>{{ $item->asset_name }}</td>
                                            <td>{{ isset($item->asset_type->name) ? $item->asset_type->name : ''  }}</td>
                                            <td>{{ $item->asset_sn }}</td>
                                            <td>{{ format_tanggal($item->purchase_date) }}</td>
                                            <td>{{ $item->asset_condition }}</td>
                                            <td>{{ $item->assign_to }}</td>
                                            <td>{{ isset($item->user->name) ? $item->user->name : '' }}</td>
                                            <td>{{ $item->handover_date != "" ?  format_tanggal($item->handover_date) : '' }}</td>
                                            <td>
                                            @if($item->handover_date === NULL)
                                                <span class="badge badge-warning">Waiting Acceptance</span>
                                            @endif

                                            @if($item->handover_date !== NULL && $item->status==1)
                                                <span class="badge badge-success">Accepted</span>
                                            @endif

                                            @if($item->handover_date !== NULL && $item->status==2)
                                                <label class="badge badge-info">Waiting Returned</label>
                                            @endif

                                            @if($item->handover_date !== NULL && $item->status==3)
                                                <label class="badge badge-danger">Rejected</label>
                                            @endif
                                            </td>
                                            {{-- <td>
                                                @if($item->status == null || $item->status == 0)
                                                <form id="form_asset{{$item->id}}" action="{{route("karyawan.asset.confirm",$item->id)}}" method="POST">
                                                    {{ csrf_field() }}
                                                    <button type="button" class="btn btn-success btn-xs" onclick="acceptAsset({{$item->id}})" style="margin-bottom: 2px;">Accept Asset</button>
                                                </form>
                                                @endif
                                                <button type="button" class="btn btn-primary btn-xs" onclick="addNote({{ $item->id }},'{{ $item->asset_number }}','{{ $item->user_id == $item->user_note_by ? $item->user_note : null }}')">Add/Edit Note</button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                                <br />
                            </div>
                            
                            <div role="tabpanel" class="tab-pane fade" id="shift">
                                <table id="tableShift" class="data_table_no_pagging table table-background">
                                    <thead>
                                        <tr>
                                            <th rowspan="1">No</th>
                                            <th rowspan="1">Change Date</th>
                                            <th rowspan="1">Shift Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shiftScheduleChange as $no => $item)
                                        <tr>
                                            <td >{{ $no+1 }}</td>
                                            <td >{{ $item->change_date}}</td>
                                            <td >{{ $item->shift->name}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div role="tabpanel" class="tab-pane fade {{ $tab == 'attendance' ? 'active in' : '' }}" id="absensi">
                                <h3 class="box-title m-b-0">Attendance</h3>
                                <table id="tableAttendance" class="data_table_no_pagging table table-background">
                                    <thead class="header" >
                                        <tr>
                                            <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">No</th>
                                            <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Date</th>
                                            <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Day</th>
                                            <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Shift</th>
                                            <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Shift</th>
                                            <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Clock</th>
                                            <th colspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Branch</th>
                                            <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Late CLOCK In</th>
                                            <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Early CLOCK Out</th>
                                            <th rowspan="2" style="padding: 3px 5px;vertical-align: middle;text-align:center;">Duration</th>
                                        </tr>
                                        <tr>
                                            <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">In</th>
                                            <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Out</th>
                                            <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">In</th>
                                            <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Out</th>
                                            <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">In</th>
                                            <th style="padding: 3px 5px;vertical-align: middle;text-align:center;">Out</th>
                                        </tr>
                                    </thead>
                                    <tbody class="no-padding-td">
                                        <?php $i = 1; ?>
                                        @foreach($dates as $no => $date)
                                        <tr>
                                            <td>{{$i}}</td>
                                            @if(date('l', strtotime($date)) == 'Sunday')
                                            <td class="tanggalAbsen" style="color:red;">{{$date}}</td>
                                            @else
                                            <td class="tanggalAbsen">{{$date}}</td>
                                            @endif
                                            <td id="hariAbsen{{$date}}" style="color: {{$shiftDay[$no] || !$shiftSchedule['shift'][$no] ? 'blue' : 'black'}}">{{date('l', strtotime($date))}}</td>
                                            <td id="shift{{$date}}">{{$shiftSchedule['shift'][$no] ?: 'No Shift'}}</td>
                                            <td id="shiftIn{{$date}}">{{$shiftSchedule['shift_in'][$no]}}</td>
                                            <td id="shiftOut{{$date}}">{{$shiftSchedule['shift_out'][$no]}}</td>
                                            <td id="clockIn{{$date}}"></td>
                                            <td id="clockOut{{$date}}"></td>
                                            <td id="branchIn{{$date}}"></td>
                                            <td id="branchOut{{$date}}"></td>
                                            <td id="lateIn{{$date}}"></td>
                                            <td id="earlyOut{{$date}}"></td>
                                            <td id="duration{{$date}}"></td>
                                        </tr>
                                        <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                <br />
                            </div>

                            


                            <div role="tabpanel" class="tab-pane fade" id="cuti">
                                <h3 class="box-title m-b-0">Leave / Permit</h3>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Leave / Permit Type</th>
                                            <th>Quota</th>
                                            <th>Leave Taken</th>
                                            <th>Leave Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table_cuti">
                                        @foreach(Auth::user()->cuti as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ isset($item->cuti->jenis_cuti) ? $item->cuti->jenis_cuti : '' }}</td>
                                            <td>{{ $item->kuota }}</td>
                                            <td>{{ $item->cuti_terpakai }}</td>
                                            <td>{{ $item->sisa_cuti }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <br />
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="rekening_bank">
                                <div class="form-group">
                                    <label class="col-md-12">Name of Account</label>
                                    <div class="col-md-6">
                                        <input type="text" name="nama_rekening" class="form-control" readonly="true" value="{{ $data->nama_rekening }}"  />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Account Number</label>
                                    <div class="col-md-6">
                                       <input type="text" name="nomor_rekening" class="form-control"  readonly="true" value="{{ $data->nomor_rekening }}" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Name of Bank</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="bank_id" readonly="true">
                                            <option value="">Pilih Bank</option>
                                            @foreach(get_bank() as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $data->bank_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if(checkModule(26))
                            <div role="tabpanel" class="tab-pane fade" id="career">
                                    <h5>Employment Status : {{$emp_status}}</h5>
                                    @if($emp_status && $emp_status != 'Permanent')
                                    <h6>{{date('F j, Y', strtotime($join_date))}} until {{date('F j, Y', strtotime($end_date))}}</h6>
                                    @endif
                            @if($future!= '')
                                @if($type == 'exist')
                                <button class="btn btn-sm btn-primary" onclick="collapseTimeline()">Toggle Timeline</button>
                                    <div id="view-timeline">
                                        <div class="timeline">
                                            <?php $i = 1; ?>
                                            <div class="contain left-future">
                                                <div class="content join-date">
                                                    <h2>{{$future}}</h2>
                                                    <h5>Future Position</h5>
                                                </div>
                                            </div>
                                            @foreach($career as $dat)
                                            <input type="hidden" value="{{$i}}">
                                            @if($i%2==0)
                                            @if($dat->id == $current)
                                            <div class="contain left-current">
                                            @else
                                            <div class="contain left">
                                            @endif
                                            @else
                                            @if($dat->id == $current)
                                            <div class="contain right-current">
                                            @else
                                            <div class="contain right">
                                            @endif
                                            @endif
                                                <div class="content">
                                                    <h2>{{$dat->position}} ({{$dat->effective_date->diffForHumans()}})</h2>
                                                    <h5>Since {{date_format($dat->effective_date, 'l jS F Y')}}</h5>
                                                    <p>Branch - {{$dat->branch}}</p>
                                                    <p><?php echo htmlspecialchars_decode($dat->job_desc) ?></p>
                                                    <p style="color:#0000ff;font-size:1em;text-align:right">{{$dat->status}}
                                                    @if($dat->status && $dat->status != 'Permanent')
                                                    <br>{{date('F j, Y', strtotime($dat->start))}} - {{date('F j, Y', strtotime($dat->end))}}</p>
                                                    @endif
                                                    </p>
                                                </div>
                                            </div>                                    
                                            <?php $i++; ?>
                                            @endforeach
                                            @if($i%2==0)
                                            <div class="contain left">
                                            @else
                                            <div class="contain right">
                                            @endif
                                                <div class="content join-date">
                                                    <h2>Join Date</h2>
                                                    <h5>{{date('F j, Y', strtotime($join_date))}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">General Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $general ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Personal Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $additional ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$grade}}" />
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Sub Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$sub_grade}}" />
                                        </div>
                                    </div>
                                @else
                                    No data yet.
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">General Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $general ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Personal Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $additional ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$grade}}" />
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Sub Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$sub_grade}}" />
                                        </div>
                                    </div>
                                @endif
                            @else
                                @if($type == 'exist')
                                <button class="btn btn-sm btn-primary" onclick="collapseTimeline()">Toggle Timeline</button>
                                    <div id="view-timeline">
                                        <div class="timeline">
                                            <?php $i = 1; ?>
                                            @foreach($career as $dat)
                                            <input type="hidden" value="{{$i}}">
                                            @if($i%2==0)
                                            @if($dat->id == $current)
                                            <div class="contain right-current">
                                            @else
                                            <div class="contain right">
                                            @endif
                                            @else
                                            @if($dat->id == $current)
                                            <div class="contain left-current">
                                            @else
                                            <div class="contain left">
                                            @endif
                                            @endif
                                                <div class="content">
                                                    <h2>{{$dat->position}} ({{$dat->effective_date->diffForHumans()}})</h2>
                                                    <h5>Since {{date_format($dat->effective_date, 'l jS F Y')}}</h5>
                                                    <p>Branch - {{$dat->branch}}</p>
                                                    <p><?php echo htmlspecialchars_decode($dat->job_desc) ?></p>
                                                    <p style="color:#0000ff;font-size:1em;text-align:right">{{$dat->status}}
                                                    @if($dat->status && $dat->status != 'Permanent')
                                                    <br>{{date('F j, Y', strtotime($dat->start))}} - {{date('F j, Y', strtotime($dat->end))}}</p>
                                                    @endif
                                                    </p>
                                                </div>
                                            </div>                                    
                                            <?php $i++; ?>
                                            @endforeach
                                            @if($i%2==0)
                                            <div class="contain right">
                                            @else
                                            <div class="contain left">
                                            @endif
                                                <div class="content join-date">
                                                    <h2>Join Date</h2>
                                                    <h5>{{date('F j, Y', strtotime($join_date))}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">General Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $general ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Personal Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $additional ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$grade}}" />
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Sub Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$sub_grade}}" />
                                        </div>
                                    </div>
                                @else
                                    No data yet.
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">General Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $general ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Personal Job Description</label>
                                        <div class="col-md-6">
                                            <?php echo $additional ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-md-12">Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$grade}}" />
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Sub Grade</label>
                                        <div class="col-md-6">
                                            <input type="text" name="grade" class="form-control"  readonly="true" value="{{$sub_grade}}" />
                                        </div>
                                    </div>
                                @endif
                            @endif
                            </div>
                            @endif


                        </div>
                    </div>
                    <div class="clearfix"></div><br />
                </div>
            </div>
        </div>

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
</style>


@section('footer-script')

<div id="modal_detail_visit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Visit</h4> </div>
                <div class="modal-body">
                <div><b style="font-size: large">Activity Name : </b>
                <p id="Visit_activity_name"></p>
                   
                <div><b style="font-size: medium">Description : </b>
                <p id="description"></p>
                        </div>
                        <div>
                        <b style="font-size: medium" class="text-center">Location Name : </b>
                        <p id="location_name"></p>
                        <b style="font-size: medium">Visit Location Map</b>
                        </div>
                        <div id="map" style="height: 254px; width: 100%;">
                        </div>
                        <div class="form-group">
                            <br>
                            <label class="col-md-6">Latitude </label>
                            <label class="col-md-6">Longitude </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-latitude" readonly="true">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-longitude" readonly="true">
                            </div>
                            <br>
                        </div>
                        <div id="container_justification">
                            <br>
                            <br>
                            <b style="font-size: medium" id="title_justification">Note : </b>
                            <p id="justification"></p>
                        </div>
                        <div>
                            <b style="font-size: medium">Branch Name / Place Name : </b>
                            <p id="branch_name"></p>
                        </div>
                <form class="form-horizontal frm-modal-inventaris">
                        <div class="form-group text-center">
                            <table class="table table-hover" id="tableListVisitPict">
                            <tr>
                            <th class="text-center">Photo</th>
                            </tr>
                            </table>
                        </div>
                        <div>
                            <b style="font-size: medium">PIC Name : </b>
                            <p id="picname"></p>
                        </div>
                        <b style="font-size: medium">Signature : </b>
                        <div class="col-md-12 signature">
                        </div>
                        
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>

     <div class="modal fade" id="modal_reset_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="exampleModalLabel1">Reset Password !</h4> 
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Password:</label>
                        <input type="password" name="password"class="form-control" placeholder="Password"> 
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Konfirmasi Password:</label>
                        <input type="password" name="confirm"class="form-control" placeholder="Konfirmasi Password"> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="submit_password">Submit Password <i class="fa fa-arrow-right"></i></button>
                </div>
              </form>
            </div>
        </div>
    </div>

    <div id="modal_detail_attendance" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Attendance</h4> </div>
                <div class="modal-body">
                    <form class="form-horizontal frm-modal-inventaris-lainnya">
                        <div class="form-group">
                            <div class="col-md-12 input_pic">
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <b><p style="font-size: large" id="attendance_type"></p></b>
                            <div id="map_attendance" style="height: 254px; width: 100%;">

                            </div>

                        </div>
                        <div id="container_justification">
                            <b style="font-size: medium" id="title_justification">Note : </b>
                            <p id="justification"></p>
                        </div>
                        <div>
                            <b style="font-size: medium">Branch : </b>
                            <p id="branch_name"></p>
                        </div>
                        <div>
                            <b style="font-size: medium">Location : </b>
                            <p id="location_name"></p>
                        </div>
                        <div class="form-group">
                            <label class="col-md-6">Latitude </label>
                            <label class="col-md-6">Longitude </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-latitude" readonly="true">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-longitude" readonly="true">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade none-border" id="modal-note">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Inventory <span id="number_note"></span> Note</strong></h4>
                </div>
                <form action="{{ route('karyawan.update-note') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="id_note" name="id_note">
                    <div class="modal-body" id="modal-add-body">
                        <div class="form-group col-md-12">
                            <label>Note</label>
                            <div>
                                <textarea required id="user_note" name="user_note" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success save-event waves-effect waves-light">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBTctq_RFrwKOd84ZbvJYvU3MEcrLmPNJ8"
    async defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript">

    function addNote(id, number, note) {
        $('#id_note').val(id)
        $('#number_note').html(number)
        $('#user_note').val(note)
        $('#modal-note').modal('show')
    }

    $(document).on('hide.bs.modal', '#modal-note', function () {
        $('#id_note').val('')
        $('#number_note').html('')
        $('#user_note').val('')
    })

    function detail_attendance(el) {
        var img = '<img src="'+ $(el).data('pic') +'" style="width:100%;" />';
        $('#modal_detail_attendance .modal-title').html($(el).data('title'));
        $('#modal_detail_attendance .input_pic').html(img);
        $("#modal_detail_attendance .input-latitude").val($(el).data('lat'));
        $("#modal_detail_attendance .input-longitude").val($(el).data('long'));
        $("#modal_detail_attendance").modal("show");

        if($(el).data('attendance-type')=='remote'){
            $('#modal_detail_attendance #attendance_type').html("Remote Attendance");
        }
        else if($(el).data('attendance-type')=='out_of_office'){
            $('#modal_detail_attendance #attendance_type').html("Out of Office Attendance");
        }
        else{
            $('#modal_detail_attendance #attendance_type').html("Normal Attendance");
        }
        if($(el).data('attendance-type')=='out_of_office') {
            $('#modal_detail_attendance #title_justification').html('Justification : ');
        }
        else{
            $('#modal_detail_attendance #title_justification').html('Note : ');
        }
        $('#modal_detail_attendance #justification').html($(el).data('justification'));
        $('#modal_detail_attendance #location_name').html($(el).data('location'));
        $('#modal_detail_attendance #branch_name').html($(el).data('cabang'));


        // The location of Uluru
        var userLoc = {lat: $(el).data('lat'), lng: $(el).data('long')};
        var icon = "{{asset('images/icon/icon_man.png')}}";
        // The map, centered at Uluru
        setTimeout(function(){
            var map = new google.maps.Map(
                document.getElementById('map_attendance'));
            // The marker, positioned at Uluru
            var userMarker = new google.maps.Marker({position: userLoc, map: map,icon: icon});
            var bounds = new google.maps.LatLngBounds();
            bounds.extend(userMarker.getPosition());
            var padding = 0;

            if($(el).data('lat-office')!="" && $(el).data('long-office')!="") {
                var officeLoc = {lat: $(el).data('lat-office'), lng: $(el).data('long-office')};
                var radius = $(el).data('radius-office');
                var distance = getDistance(userLoc.lat,userLoc.lng,officeLoc.lat,officeLoc.lng);
                var color;
                if(distance > radius){
                    color = "#FF0000";
                    padding = 0;
                }
                else{
                    color = "#7cb342";
                    padding = 100;
                }

                var cityCircle = new google.maps.Circle({
                    strokeColor: color,
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: color,
                    fillOpacity: 0.35,
                    map: map,
                    center: officeLoc,
                    radius: radius
                });
                console.log("City Circle colored : "+color);

                bounds.extend(officeLoc);
            }
            map.fitBounds(bounds,padding);
        }, 1000);
    }
        
    $('#tableVisit').ready(function() {
            var user_id = $('#idUser').val()
            $.ajax({
                url: '{{route("visit.ajax-holiday")}}',
                type: 'GET',
                dataType: 'JSON',
                contentType: 'application/json',
                processData: false,
                success: function(data) {
                    // console.log(data)
                    if (data.message == 'success') {
                        if (data.holidays.length > 0) {
                            $('.tanggalVisit').each(function(i) {
                                console.log(i)
                                var baru = i + 1
                                if ($('#holiday' + baru).val() == 0) {
                                    for (var y = 0; y < data.holidays.length; y++) {
                                        if ($(this).text() == data.holidays[y].tanggal) {
                                            $(this).css('color', 'red')
                                        }
                                    }
                                }
                            })
                        }
                    } else {

                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            })
        })


        $('#tableAttendance').ready(function(){
            var user_id = $('#idUser').val()
            $.ajax({
                url: '{{route("karyawan.ajax-attendance")}}',
                type: 'GET',
                dataType: 'JSON',
                contentType: 'application/json',
                processData: false,
                success: function(data){
                    console.log(data)
                    if(data.message == 'success'){
                        if(data.absensi_item.length > 0){
                            $('.tanggalAbsen').each(function(i){
                                for(var i = 0; i < data.absensi_item.length; i++){
                                    if($(this).text() == data.absensi_item[i].date){
                                        var baru = i+1
                                        var asset = "'upload/attendance/'"
                                        var dateChange = "date('d F Y', "
                                        if(data.absensi_item[i].long || data.absensi_item[i].lat || data.absensi_item[i].pic){
                                            var long_office = 'data-long-office="'+data.absensi_item[i].long_office_in+'"';
                                            var lat_office = 'data-lat-office="'+data.absensi_item[i].lat_office_in+'"';
                                            var radius_office = 'data-radius-office="'+data.absensi_item[i].radius_office_in+'"';
                                            var clock_in = data.absensi_item[i].clock_in;
                                            if(data.absensi_item[i].attendance_type_in == 'remote')
                                                clock_in += ' (R)';
                                            else if(data.absensi_item[i].attendance_type_in == 'out_of_office')
                                                clock_in += ' (O)';
                                            if(data.absensi_item[i].long_office_in == null){
                                                long_office = 'data-long-office'
                                            }
                                            if(data.absensi_item[i].lat_office_in == null){
                                                lat_office = 'data-lat-office'
                                            }
                                            if(data.absensi_item[i].radius_office_in == null){
                                                radius_office = 'data-radius-office'
                                            }
                                            if(data.absensi_item[i].pic.includes('upload/attendance')){
                                                $('#clockIn'+data.absensi_item[i].date_shift).html(
                                                    '<a href="javascript:void(0)" data-pic="{{asset("/")}}'+data.absensi_item[i].pic+'" data-title="Clock In '+data.absensi_item[i].date+' '+clock_in+'" data-long="'+data.absensi_item[i].long+'" data-lat="'+data.absensi_item[i].lat+'" data-time="'+data.absensi_item[i].clock_in+'" '+long_office+' '+lat_office+' '+radius_office+' title="Mobile Attendance" data-long-office="'+data.absensi_item[i].long_office_in+'" data-lat-office="'+data.absensi_item[i].lat_office_in+'" data-radius-office="'+data.absensi_item[i].radius_office_in+'" data-attendance-type="'+data.absensi_item[i].attendance_type_in+'" data-justification="'+data.absensi_item[i].justification_in+'" data-cabang="'+data.absensi_item[i].cabang_in+'" data-location="'+data.absensi_item[i].location_name_in+'" onclick="detail_attendance(this)">'+clock_in+'</a>'+
                                                    '<i title="Web Attendance" class="fa fa-desktop pull-right"></i>'
                                                )
                                            }
                                            else{
                                                $('#clockIn'+data.absensi_item[i].date_shift).html(
                                                    '<a href="javascript:void(0)" data-pic="{{asset("upload/attendance/")}}'+data.absensi_item[i].pic+'" data-title="Clock In '+data.absensi_item[i].date+' '+clock_in+'" data-long="'+data.absensi_item[i].long+'" data-lat="'+data.absensi_item[i].lat+'" data-time="'+data.absensi_item[i].clock_in+'" '+long_office+' '+lat_office+' '+radius_office+' title="Mobile Attendance" data-long-office="'+data.absensi_item[i].long_office_in+'" data-lat-office="'+data.absensi_item[i].lat_office_in+'" data-radius-office="'+data.absensi_item[i].radius_office_in+'" data-attendance-type="'+data.absensi_item[i].attendance_type_in+'" data-justification="'+data.absensi_item[i].justification_in+'" data-cabang="'+data.absensi_item[i].cabang_in+'" data-location="'+data.absensi_item[i].location_name_in+'" onclick="detail_attendance(this)">'+clock_in+'</a>'+
                                                    '<i title="Mobile Attendance" class="fa fa-mobile pull-right"></i>'
                                                )
                                            }
                                        }
                                        else{
                                            $('#clockIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].clock_in)
                                        }
                                        if(data.absensi_item[i].long_out || data.absensi_item[i].lat_out || data.absensi_item[i].pic_out){
                                            var long_office = 'data-long-office="'+data.absensi_item[i].long_office_out+'"';
                                            var lat_office = 'data-lat-office="'+data.absensi_item[i].lat_office_out+'"';
                                            var radius_office = 'data-radius-office="'+data.absensi_item[i].radius_office_out+'"';
                                            var clock_out = data.absensi_item[i].clock_out;
                                            if(data.absensi_item[i].attendance_type_out == 'remote')
                                                clock_out += ' (R)';
                                            else if(data.absensi_item[i].attendance_type_out == 'out_of_office')
                                                clock_out += ' (O)';
                                            if(data.absensi_item[i].date != data.absensi_item[i].date_out)
                                                clock_out += ' (ND)';
                                            if(data.absensi_item[i].long_office_out == null){
                                                long_office = 'data-long-office'
                                            }
                                            if(data.absensi_item[i].lat_office_out == null){
                                                lat_office = 'data-lat-office'
                                            }
                                            if(data.absensi_item[i].radius_office_out == null){
                                                radius_office = 'data-radius-office'
                                            }
                                            if(data.absensi_item[i].pic_out.includes('upload/attendance')){
                                                $('#clockOut'+data.absensi_item[i].date_shift).html(
                                                    '<a href="javascript:void(0)" data-pic="{{asset("/")}}'+data.absensi_item[i].pic_out+'" data-title="Clock Out '+data.absensi_item[i].date_out+' '+clock_out+'" data-long="'+data.absensi_item[i].long_out+'" data-lat="'+data.absensi_item[i].lat_out+'" data-time="'+data.absensi_item[i].clock_out+'" '+long_office+' '+lat_office+' '+radius_office+' title="Mobile Attendance" data-long-office="'+data.absensi_item[i].long_office_out+'" data-lat-office="'+data.absensi_item[i].lat_office_out+'" data-radius-office="'+data.absensi_item[i].radius_office_out+'" data-attendance-type="'+data.absensi_item[i].attendance_type_out+'" data-justification="'+data.absensi_item[i].justification_out+'" data-cabang="'+data.absensi_item[i].cabang_out+'" data-location="'+data.absensi_item[i].location_name_out+'" onclick="detail_attendance(this)">'+clock_out+'</a>'+
                                                    '<i title="Web Attendance"  class="fa fa-desktop pull-right"></i>'
                                                )
                                            }
                                            else{
                                                $('#clockOut'+data.absensi_item[i].date_shift).html(
                                                    '<a href="javascript:void(0)" data-pic="{{asset("upload/attendance/")}}'+data.absensi_item[i].pic_out+'" data-title="Clock Out '+data.absensi_item[i].date_out+' '+clock_out+'" data-long="'+data.absensi_item[i].long_out+'" data-lat="'+data.absensi_item[i].lat_out+'" data-time="'+data.absensi_item[i].clock_out+'" '+long_office+' '+lat_office+' '+radius_office+' title="Mobile Attendance" data-long-office="'+data.absensi_item[i].long_office_out+'" data-lat-office="'+data.absensi_item[i].lat_office_out+'" data-radius-office="'+data.absensi_item[i].radius_office_out+'" data-attendance-type="'+data.absensi_item[i].attendance_type_out+'" data-justification="'+data.absensi_item[i].justification_out+'" data-cabang="'+data.absensi_item[i].cabang_out+'" data-location="'+data.absensi_item[i].location_name_out+'" onclick="detail_attendance(this)">'+clock_out+'</a>'+
                                                    '<i title="Mobile Attendance"  class="fa fa-mobile pull-right"></i>'
                                                )
                                            }
                                        }
                                        else{
                                            $('#clockOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].clock_out)
                                        }
                                        $('#lateIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].late)
                                        $('#earlyOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].early)
                                        $('#duration'+data.absensi_item[i].date_shift).html(data.absensi_item[i].work_time)
                                        $('#branchIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].cabang_in);
                                        $('#branchOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].cabang_out);
                                        $('#shift'+data.absensi_item[i].date_shift).html(data.absensi_item[i].shift_name ? data.absensi_item[i].shift_name : 'No Shift');
                                        $('#shiftIn'+data.absensi_item[i].date_shift).html(data.absensi_item[i].shift_in);
                                        $('#shiftOut'+data.absensi_item[i].date_shift).html(data.absensi_item[i].shift_out);
                                        $('#hariAbsen'+data.absensi_item[i].date_shift).css('color', data.absensi_item[i].shift_detail_id || !data.absensi_item[i].shift_name ? 'blue' : 'black');
                                    }
                                }
                            })
                        }

                        if(data.holidays.length > 0){
                            $('.tanggalAbsen').each(function(i){
                                for(var y = 0; y < data.holidays.length; y++){
                                    if($(this).text() == data.holidays[y].tanggal){
                                        $(this).css('color', 'red')
                                    }
                                }
                            })
                        }
                    }
                    else{
                        
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            })
        })

        function detail_visit(el)
        {
            var idlist = $(el).data('visitid');
            var url = '{{ route("karyawan.visit.pictlist", ":visitid") }}';
            url = url.replace(':visitid', idlist );
            var pathsignature = $(el).data('signature');
            var visittype = $(el).data('visittype');
            var isoutbranch = $(el).data('isoutbranch');
            var img = '<img src="' + pathsignature + '" style="width:100%;" />';
            $('#modal_detail_visit .modal-title').html($(el).data('title'));
            $('.signature').html(img);
            $(".input-latitude").val($(el).data('latitude'));
            $(".input-longitude").val($(el).data('longitude'));
            $("#modal_detail_visit").modal("show");
            $('#idvisit').html($(el).data('visitid'));
            $('#Visit_activity_name').html($(el).data('activity-name'));
            $('#picname').html($(el).data('picname'));
            $('#justification').html($(el).data('justification'));
            $('#location_name').html($(el).data('location'));
            if (visittype==2 || ( visittype==1 && isoutbranch==1))
            {
                $('#branch_name').html($(el).data('placename'));
                
            }
            else
            {
                $('#branch_name').html($(el).data('cabang'));
            }
            
            $('#description').html($(el).data('description'));


            // The location of Uluru
            var userLoc = {lat: $(el).data('latitude'), lng: $(el).data('longitude')};
            var icon = "{{asset('images/icon/icon_man.png')}}";
            // The map, centered at Uluru
            setTimeout(function(){
                var map = new google.maps.Map(
                    document.getElementById('map'));
                // The marker, positioned at Uluru
                var userMarker = new google.maps.Marker({position: userLoc, map: map,icon: icon});
                var bounds = new google.maps.LatLngBounds();
                bounds.extend(userMarker.getPosition());
                var padding = 0;

                if($(el).data('lat-branch')!="" && $(el).data('long-branch')!="") {
                    var branchLoc = {lat: $(el).data('lat-branch'), lng: $(el).data('long-branch')};
                    var radius = $(el).data('radius-branch');
                    var distance = getDistance(userLoc.lat,userLoc.lng,branchLoc.lat,branchLoc.lng);
                    var color;
                    if(distance > radius){
                        color = "#FF0000";
                        padding = 0;
                    }
                    else{
                        color = "#7cb342";
                        padding = 100;
                    }

                    var cityCircle = new google.maps.Circle({
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color,
                        fillOpacity: 0.35,
                        map: map,
                        center: branchLoc,
                        radius: radius
                    });
                    console.log("City Circle colored : "+color);

                    bounds.extend(branchLoc);
                }
                map.fitBounds(bounds,padding);
            }, 1000);
        
        
            $.ajax({       
        url:url,
        type: "GET",
        dataType: "JSON",
        contentType: "application/json",
        processData: false,
        success: function(data){
            if(data.message == 'success'){
                $('#IdVisit').val(idlist)
                $('#tableListVisitPict').find('tr:gt(0)').remove()
                for(var i = 0; i < data.data.length; i++){
                    var num = i+1;     
                    if(data.data[i].visit_list_id == idlist){
                     $('#tableListVisitPict tr:last').after(
                            '<tr>'+
                            '<td width=100% style="text-align: center; vertical-align: middle;"><img src="/'+data.data[i].photo+'" style="width:100%;height:70%;text-align: center;"></img> <br> <br> <p>'+data.data[i].photocaption+'</p> </td>'+'</tr>'
                        )
                    }
                    else{
                        $('#tableListVisitPict tr:last').after(
                            '<tr>'+
                            '<td width=100% style="text-align: center; vertical-align: middle;"><img src="/'+data.data[i].photo+'" style="width:100%;height:70%;text-align: center;"></img> <br> <br> <p>'+data.data[i].photocaption+'</p> </td>'+'</tr>'
                        )
                    }
                }
            }
            else{
                $('#tableListVisitPict').find('tr:gt(0)').remove()
                $('#tableListVisitPict tr:last').after(
                    '<tr>'+
                        '<td colspan="1">No data.</td>'+
                    '</tr>'
                )
                $('#modal_detail_visit .modal-title').html($(el).data('title'));
            }
        }
        }
        )
        }

        function getDistance(lat1,lon1,lat2,lon2) {

var R = 6371000; // Radius of the earth in m
var dLat = deg2rad(lat2-lat1);  // deg2rad below
var dLon = deg2rad(lon2-lon1);
var a =
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
    Math.sin(dLon/2) * Math.sin(dLon/2);
var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
var d = R * c; // Distance in km
return d;
}

function deg2rad(deg) {
return deg * (Math.PI/180)
}

        function collapseTimeline(){
            $("#view-timeline").toggle();
        }

        $("#change_password").click(function(){

            $("#modal_reset_password").modal("show");

        });

        $("#submit_password").click(function(){

            var password    = $("input[name='password']").val();
            var confirm     = $("input[name='confirm']").val();

            if(password == "" || confirm == "")
            {
                bootbox.alert('Password atau Konfirmasi Password harus diisi !');
                return false;
            }

            if(password != confirm)
            {
                bootbox.alert('Password tidak sama');
            }
            else
            {
                 $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.update-first-password') }}',
                    data: {'id' : {{ Auth::user()->id }}, 'password' : password, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {
                        if(data.message == 'error')
                        {
                            alert(data.data);
                        }
                        else
                        {
                            window.location.href = "{{ url('karyawan/profile') }}"
                        }
                    }
                });
            }
        });

        function acceptAsset(assetId) {
            swal({
                title: 'Are you sure?',
                text: 'The asset item will be accepted!',
                buttons: true,
                dangerMode: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    $('#form_asset'+assetId).submit();
                }
            });
        }
    </script>
@endsection

@endsection
