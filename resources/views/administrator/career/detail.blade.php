@extends('layouts.administrator')

@section('title', 'Career')

@section('sidebar')

@endsection

@section('content')
    <!-- ============================================================== -->
    <!-- Page Content -->
    <!-- ============================================================== -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row bg-title">
                <div class="col-lg-1 col-md-4 col-sm-4 col-xs-12">
                    @if(!empty($user->foto))
                    <img src="{{ asset('storage/foto/'. $user->foto) }}" style="width: 100px;" />
                    @else
                    <img src="{{ asset('admin-css/images/user.png') }}" style="width: 100px;" />
                    @endif
                </div>
                <div class="col-lg-4 col-sm-8 col-md-8 col-xs-12">
                    <h4 class="page-title">Career  history of {{$user->name}} - {{$user->nik}} {{ $user->non_active_date && $user->non_active_date <= \Carbon\Carbon::now() ? '(Non Active)' : ($user->is_exit ? '(Approved Exit Interview)' : '') }}</h4>
                    <h5>Employment Status : {{$data['emp_status']}}</h5>
                    @if($data['emp_status'] && $data['emp_status'] != 'Permanent' && $data['join_date'] != '')
                    <h6>{{date('F j, Y', strtotime($data['join_date']))}} until {{date('F j, Y', strtotime($data['end_date']))}}</h6>
                    @endif
                </div>
                <div class="col-lg-7 col-sm-8 col-md-8 col-xs-12">
{{--                    <a href="{{ route('administrator.setting-performance.create') }}" class="btn btn-sm btn-success pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> ADD SETTING PERIOD</a>--}}
                    <ol class="breadcrumb">
                        <li><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="active">Career</li>
                    </ol>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- .row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="white-box">

                        <div class="table-responsive">
                            <div class="form-group col-md-2">
                                <button type="button" id="view1" onclick="mix()" class="btn btn-primary btn-sm"><i id="icon-view" class="fa fa-clone"></i></button>
                                @if(get_setting('layout_career') == 'timeline')
                                <button type="button" id="view1" onclick="table()" class="btn btn-primary btn-sm"><i id="icon-view" class="fa fa-table"></i></button>
                                @else
                                <button type="button" id="view1" onclick="timeline()" class="btn btn-primary btn-sm"><i id="icon-view" class="fa fa-line-chart"></i></button>
                                @endif
                            </div>
                            @if((!$user->non_active_date || $user->non_active_date > \Carbon\Carbon::now()) && !$user->is_exit)
                            <div class="form-group col-md-1 pull-right">
                                <button class="btn btn-sm btn-info pull-right" type="button" onclick="addHistory()">Add New Data</button>
                            </div>
                            @endif
                            <div class="form-group col-md-3 pull-right">

                                <button type="button" onclick="download()" class="btn btn-info btn-sm pull-right">Export Excel <i class="fa fa-upload"></i></button>
                                <a href="{{ route('career.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10 pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                            </div>
                            <input type="hidden" id="uid" value="{{$id}}">
                        </div>
                        <style>
                            .timeline {
                            position: relative;
                            max-width: 1200px;
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
                            
                            #mytable th {
                                white-space: nowrap;
                            }

                            #mytable td {
                                max-width: 200px;
                                white-space: initial;
                                word-wrap: break-word;
                            }
                        </style>
                        @if($data['future'] != '')
                            @if($data['type'] == 'exist')
                                @if(get_setting('layout_career') == 'timeline')
                                <div id="view-timeline">
                                    <div class="timeline">
                                        <?php $i = 1; ?>
                                        <div class="contain left-future">
                                            <div class="content join-date">
                                                <h2>{{$data['future']}}</h2>
                                                <h5>Future Position</h5>
                                            </div>
                                        </div>
                                        @foreach($data['data'] as $dat)
                                        <input type="hidden" value="{{$i}}">
                                        @if($i%2==0)
                                        @if($dat->id == $data['current'])
                                        <div class="contain left-current">
                                        @else
                                        <div class="contain left">
                                        @endif
                                        @else
                                        @if($dat->id == $data['current'])
                                        <div class="contain right-current">
                                        @else
                                        <div class="contain right">
                                        @endif
                                        @endif
                                            <div class="content">
                                                <h2>{{$dat->position}} ({{$dat->effective_date->diffForHumans()}})</h2>
                                                <h4>Branch - {{$dat->branch}}</h4>
                                                <h5>Since {{date_format($dat->effective_date, 'l jS F Y')}}</h5>
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
                                                <h5>{{date('F j, Y', strtotime($data['join_date_first']))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif(get_setting('layout_career') == 'table')
                                <div id="view-table">
                                    <table id="mytable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="15%">BRANCH</th>
                                            <th width="10%">POSITION</th>
                                            <th width="20%">EFFECTIVE DATE</th>
                                            <th width="20%">STATUS</th>
                                            <th width="20%">START DATE</th>
                                            <th width="20%">END DATE</th>
                                            <th class="action" width="10%">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody id="data_item">

                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div id="view-table">
                                    <table id="mytable" class="display" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="15%">BRANCH</th>
                                            <th width="10%">POSITION</th>
                                            <th width="20%">EFFECTIVE DATE</th>
                                            <th width="20%">STATUS</th>
                                            <th width="20%">START DATE</th>
                                            <th width="20%">END DATE</th>
                                            <th class="action" width="10%">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody id="data_item">

                                        </tbody>
                                    </table>
                                </div><hr>
                                <div id="view-timeline">
                                    <div class="timeline">
                                        <?php $i = 1; ?>
                                        <div class="contain left-future">
                                            <div class="content join-date">
                                                <h2>{{$data['future']}}</h2>
                                                <h5>Future Position</h5>
                                            </div>
                                        </div>
                                        @foreach($data['data'] as $dat)
                                        <input type="hidden" value="{{$i}}">
                                        @if($i%2==0)
                                        @if($dat->id == $data['current'])
                                        <div class="contain left-current">
                                        @else
                                        <div class="contain left">
                                        @endif
                                        @else
                                        @if($dat->id == $data['current'])
                                        <div class="contain right-current">
                                        @else
                                        <div class="contain right">
                                        @endif
                                        @endif
                                            <div class="content">
                                                <h2>{{$dat->position}} ({{$dat->effective_date->diffForHumans()}})</h2>
                                                <h4>Branch - {{$dat->branch}}</h4>
                                                <h5>Since {{date_format($dat->effective_date, 'l jS F Y')}}</h5>
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
                                                <h5>{{date('F j, Y', strtotime($data['join_date_first']))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $i++; ?>
                                </div>
                                @endif
                            @else
                                No data yet.
                            @endif
                        @else
                            @if($data['type'] == 'exist')
                                @if(get_setting('layout_career') == 'timeline')
                                <div id="view-timeline">
                                    <div class="timeline">
                                        <?php $i = 1; ?>
                                        @foreach($data['data'] as $dat)
                                        <input type="hidden" value="{{$i}}">
                                        @if($i%2==0)
                                        @if($dat->id == $data['current'])
                                        <div class="contain right-current">
                                        @else
                                        <div class="contain right">
                                        @endif
                                        @else
                                        @if($dat->id == $data['current'])
                                        <div class="contain left-current">
                                        @else
                                        <div class="contain left">
                                        @endif
                                        @endif
                                            <div class="content">
                                                <h2>{{$dat->position}} ({{$dat->effective_date->diffForHumans()}})</h2>
                                                <h4>Branch - {{$dat->branch}}</h4>
                                                <h5>Since {{date_format($dat->effective_date, 'l jS F Y')}}</h5>
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
                                                <h5>{{date('F j, Y', strtotime($data['join_date_first']))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif(get_setting('layout_career') == 'table')
                                <div id="view-table">
                                    <table id="mytable" class="display" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="15%">BRANCH</th>
                                            <th width="10%">POSITION</th>
                                            <th width="20%">EFFECTIVE DATE</th>
                                            <th width="20%">STATUS</th>
                                            <th width="20%">START DATE</th>
                                            <th width="20%">END DATE</th>
                                            <th class="action" width="10%">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody id="data_item">

                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div id="view-table">
                                    <table id="mytable" class="display" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th width="15%">BRANCH</th>
                                            <th width="10%">POSITION</th>
                                            <th width="20%">EFFECTIVE DATE</th>
                                            <th width="20%">STATUS</th>
                                            <th width="20%">START DATE</th>
                                            <th width="20%">END DATE</th>
                                            <th class="action" width="10%">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody id="data_item">

                                        </tbody>
                                    </table>
                                </div><hr>
                                <div id="view-timeline">
                                    <div class="timeline">
                                        <?php $i = 1; ?>
                                        @foreach($data['data'] as $dat)
                                        <input type="hidden" value="{{$i}}">
                                        @if($i%2==0)
                                        @if($dat->id == $data['current'])
                                        <div class="contain right-current">
                                        @else
                                        <div class="contain right">
                                        @endif
                                        @else
                                        @if($dat->id == $data['current'])
                                        <div class="contain left-current">
                                        @else
                                        <div class="contain left">
                                        @endif
                                        @endif
                                            <div class="content">
                                                <h2>{{$dat->position}} ({{$dat->effective_date->diffForHumans()}})</h2>
                                                <h4>Branch - {{$dat->branch}}</h4>
                                                <h5>Since {{date_format($dat->effective_date, 'l jS F Y')}}</h5>
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
                                                <h5>{{date('F j, Y', strtotime($data['join_date_first']))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @else
                                No data yet.
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- ============================================================== -->
        </div>
    <!-- BEGIN MODAL -->
    <div  class="modal fade none-border" id="modal-add">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Add New Career History</strong></h4>
                </div>
                <form id="form">
                <div class="modal-body" id="modal-add-body">
                <input type="hidden" id="userId" name="user_id" value="{{$id}}">
                        <div class="form-group col-md-12">
                            <label>Select Branch</label>
                            {{--<label>Select Branch</label>--}}
                                <select name="branch" CLASS="form-control" id="branch_id" >
                                    <?php $branches = get_branches()?>
                                    <option value="0">- Select Branch -</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Select Position</label>
                            <div>
                                <select onchange="checkGrade(this)" name="position" CLASS="form-control" id="structure_id" >
                                    <option value="0">- Select Position - </option>
                                    <?php $positions = getAllPositions()?>
                                    @foreach($positions as $position)
                                        <option value="{{$position->id}}">{{$position->position}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="gradeName" style="display:none" class="form-group col-md-12">
                            <label>Grade</label>
                            <div>
                            <input disabled id="gradeNameInp" type="text" class="form-control">
                            </div>
                        </div>
                        <div id="subGradeName" style="display:none" class="form-group col-md-12">
                            <label>Select Sub Grade</label>
                            <div>
                                <select name="subgrade" CLASS="form-control" id="subgrade" >
                                    <option value="0">- Select Sub Grade - </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <div>
                                <select onchange="contract(this)" name="status" CLASS="form-control" id="status" >
                                    <option value="">- Select Status - </option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">Internship</option>
                                    <option value="Outsource">Outsource</option>
                                    <option value="Freelance">Freelance</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:none" id="start" class="form-group col-md-12">
                            <label>Start Date</label>
                            <div>
                                <input autocomplete="off" type="text" id="start_date" name="start_date" class="form-control datepicker">
                            </div>
                        </div>
                        <div style="display:none" id="end" class="form-group col-md-12">
                            <label>End Date</label>
                            <div>
                                <input autocomplete="off" type="text" id="end_date" name="end_date" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Effective Date</label>
                            <div>
                                <input autocomplete="off" type="text" id="eff_date" name="eff_date" class="form-control datepicker">
                            </div>
                        </div>
                        <div style="display:none;" id="general" class="form-group col-md-12">
                            <label>General Job Description</label>
                            <div id="general_job_desc">
                                
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Personal Job Description</label>
                            <div>
                                <textarea id="job_desc" name="job_desc" class="form-control" cols="30" rows="10"></textarea>
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

    <div  class="modal fade none-border" id="modal-edit">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><strong>Edit Data</strong></h4>
                </div>
                <form id="form-edit">
                <div class="modal-body" id="modal-add-body">
                <input type="hidden" id="id_edit" name="id_edit">
                        <div class="form-group col-md-12">
                            <label>Select Branch</label>
                            {{--<label>Select Branch</label>--}}
                                <select name="branch_edit" CLASS="form-control" id="branch_id_edit" >
                                    <?php $branches = get_branches()?>
                                    <option value="0">- Select Branch -</option>
                                    @foreach($branches as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Select Position</label>
                            <div>
                                <select onchange="checkGradeEdit(this)" name="position_edit" CLASS="form-control" id="structure_id_edit" >
                                    <option value="0">- Select Posisi - </option>
                                    <?php $positions = getAllPositions()?>
                                    @foreach($positions as $position)
                                        <option value="{{$position->id}}">{{$position->position}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="gradeNameEdit" style="display:none" class="form-group col-md-12">
                            <label>Grade</label>
                            <div>
                            <input disabled id="gradeNameInpEdit" type="text" class="form-control">
                            </div>
                        </div>
                        <div id="subGradeNameEdit" style="display:none" class="form-group col-md-12">
                            <label>Select Sub Grade</label>
                            <div>
                                <select name="subgradeedit" CLASS="form-control" id="subgradeedit" >
                                    <option value="0">- Select Sub Grade - </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <div>
                                <select onchange="contractEdit(this)" name="status_edit" CLASS="form-control" id="status-edit" >
                                    <option value="">- Select Status - </option>
                                    <option value="Permanent">Permanent</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Internship">Internship</option>
                                    <option value="Outsource">Outsource</option>
                                    <option value="Freelance">Freelance</option>
                                </select>
                            </div>
                        </div>
                        <div style="display:none" id="start-edit" class="form-group col-md-12">
                            <label>Start Date</label>
                            <div>
                                <input autocomplete="off" type="text" id="start_date_edit" name="start_date_edit" class="form-control datepicker">
                            </div>
                        </div>
                        <div style="display:none" id="end-edit" class="form-group col-md-12">
                            <label>End Date</label>
                            <div>
                                <input autocomplete="off" type="text" id="end_date_edit" name="end_date_edit" class="form-control datepicker">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Effective Date</label>
                            <div>
                                <input autocomplete="off" type="text" id="eff_date_edit" name="eff_date_edit" class="form-control datepicker">
                            </div>
                        </div>
                        <div style="display:none;" id="general_edit" class="form-group col-md-12">
                            <label>General Job Description</label>
                            <div id="general_job_desc_edit">
                                
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Personal Job Description</label>
                            <div id="personal_edit">
                                <textarea id="job_desc_edit" name="job_desc_edit" class="form-control" cols="30" rows="10"></textarea>
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


        <!-- /.contain-fluid -->
        @include('layouts.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
@section('js')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace( 'job_desc' );
        CKEDITOR.replace( 'job_desc_edit' );
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <script>
    $(document).ready(function(){
        
        $('#mytable').DataTable();
    });
        // $(document).ready(function(){
        //     $("#view1").click(function(){
        //         var x = $(this).attr('id');
        //         if( x == 'view1'){
                    // $('#view-table').show();
                    // $('#view-timeline').hide();
                    // $(this).attr('id', 'view2');
                    // $('#icon-view').attr('class', 'fa fa-line-chart');
        //         } else{
        //             $('#view-table').hide();
        //             $('#view-timeline').show();
        //             $(this).attr('id', 'view1');
        //             $('#icon-view').attr('class', 'fa fa-table');
        //         }
        //     });
        // });

        function timeline(){
            window.location.href = uid+"?layout_career=timeline";
            $('#view-table').hide();
            $('#view-timeline').show();
            $('#icon-view').attr('class', 'fa fa-table');
        }

        function table(){
            window.location.href = uid+"?layout_career=table";
            $('#view-table').show();
            $('#view-timeline').hide();
            $('#icon-view').attr('class', 'fa fa-line-chart');
        }

        function mix(){
            window.location.href = uid+"?layout_career=mixed";
            $('#view-table').show();
            $('#view-timeline').show();
        }

        function escapeHtml(text){
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }

            return text.replace(/[&<>"']/g, function(m){
                return map[m];
            });
        }

        function contract(select){
            var chosen = select.options[select.selectedIndex];
            if(chosen.value && chosen.value != 'Permanent'){
                // alert('contract');
                $('#start').show();
                $('#end').show();
                $('#start').attr('id', 'start-show');
                $('#end').attr('id', 'end-show');
            }
            else{
                $('#start-show').hide();
                $('#end-show').hide();
                $('#start-show').attr('id', 'start');
                $('#end-show').attr('id', 'end');
            }
        }

        function checkGrade(select){
            var chosen = select.options[select.selectedIndex];
            var id = chosen.value;
            $.ajax({
                url: "{{ route('administrator.grade.checksubgrade') }}",
                type: "GET",
                data: {"id":id},
                dataType: "JSON",
                contentType: "application/json",
                success: function (data) {
                    if (data.message == 'sub grade found') {
                        swal("Attention!", 'Grading is available', "success");
                        $('#gradeNameInp').val(data.grade_name);
                        $('#general_job_desc').html(data.job_desc);
                        $('#gradeName').show();
                        $('#subGradeName').show();
                        $('#general').show();
                        var temp = $('#subgrade');
                        temp.empty();
                        $('#subgrade').append("<option value=''>- Select Sub Grade -</option>");
                        $.each(data.data, function(i, data){
                            $('<option>',{
                                value: data.id,
                                text: data.name
                            }).html(data.name).appendTo('#subgrade');
                        })
                    } else if(data.message == 'only grade found') {
                        swal("Attention!", 'No sub grade for this position', "warning");
                        $('#gradeNameInp').val(data.grade_name);
                        $('#general_job_desc').html(data.job_desc);
                        $('#gradeName').show();
                        $('#subGradeName').show();
                        $('#general').show();
                        var temp = $('#subgrade');
                        temp.empty();
                        $('#subgrade').append("<option value=''>No sub grade available.</option>");
                        $('#subgrade').prop('disabled', true);
                    } else {
                        swal("Attention!", 'No grade for this position', "warning");
                        $('#gradeName').hide();
                        $('#subGradeName').hide();
                        $('#general').hide();
                    }

                    if(data.job_desc == null || data.job_desc == ''){
                        $('#general').hide();
                    }
                    else{
                        $('#general_job_desc').html(data.job_desc);
                        $('#general').show();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }

        function checkGradeEdit(select){
            var chosen = select.options[select.selectedIndex];
            var id = chosen.value;
            $.ajax({
                url: "{{ route('administrator.grade.checksubgrade') }}",
                type: "GET",
                data: {"id":id},
                dataType: "JSON",
                contentType: "application/json",
                success: function (data) {
                    if (data.message == 'sub grade found') {
                        swal("Attention!", 'Grading is available', "success");
                        $('#gradeNameInpEdit').val(data.grade_name);
                        $('#gradeNameEdit').show();
                        $('#subGradeNameEdit').show();
                        var temp = $('#subgradeedit');
                        temp.empty();
                        $('#subgradeedit').append("<option value=''>- Select Sub Grade -</option>");
                        $.each(data.data, function(i, data){
                            $('<option>',{
                                value: data.id,
                                text: data.name
                            }).html(data.name).appendTo('#subgradeedit');
                        })
                    } else if(data.message == 'only grade found') {
                        swal("Attention!", 'No sub grade for this position', "warning");
                        $('#gradeNameInpEdit').val(data.grade_name);
                        $('#general_job_desc_edit').html(data.job_desc);
                        $('#gradeNameEdit').show();
                        $('#subGradeNameEdit').show();
                        var temp = $('#subgradeedit');
                        temp.empty();
                        $('#subgradeedit').append("<option value=''>No sub grade available.</option>");
                        $('#subgradeedit').prop('disabled', true);
                    } else {
                        swal("Attention!", 'No grade for this position', "warning");
                        $('#gradeNameEdit').hide();
                        $('#subGradeNameEdit').hide();
                    }

                    if(data.job_desc == null || data.job_desc == ''){
                        $('#general_edit').hide();
                    }
                    else{
                        $('#general_job_desc_edit').html(data.job_desc);
                        $('#general_edit').show();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        }

        function contractEdit(select){
            var chosen = select.options[select.selectedIndex];
            if(chosen.value && chosen.value != 'Permanent'){
                // alert('contract');
                $('#start-edit').show();
                $('#end-edit').show();
                $('#start-edit').attr('id', 'start-edit-show');
                $('#end-edit').attr('id', 'end-edit-show');
            }
            else{
                $('#start-edit-show').hide();
                $('#end-edit-show').hide();
                $('#start-edit-show').attr('id', 'start-edit');
                $('#end-edit-show').attr('id', 'end-edit');
            }
        }

        // jQuery('.datepicker2').datepicker({
        //     format: 'yyyy-mm-dd',
        // });

        var branch_id = $('#branch').val(), empty="", position_id=$('#position').val(),division_id=$('#division').val(),uid=$('#uid').val();

        $('#branch').on('change',function () {
            branch_id = $(this).val();
            loadData();
        });
        $('#position').on('change',function () {
            position_id = $(this).val();
            loadData();
        });
        $('#division').on('change',function () {
            division_id = $(this).val();
            loadData();
        });
        // $('#employee').on('input change',function () {
        //     if($.trim($(this).val())==""){
        //         $('#user_id').val("0").trigger('change');
        //     }

        // });
        // $('#user_id').on('change',function () {
        //     id_user = $(this).val();
        //     loadData();
        // });
        loadData();
        function loadData(){
            $('#mytable').DataTable().destroy();
            $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
            {
                return {
                    "iStart": oSettings._iDisplayStart,
                    "iEnd": oSettings.fnDisplayEnd(),
                    "iLength": oSettings._iDisplayLength,
                    "iTotal": oSettings.fnRecordsTotal(),
                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                    "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                    "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };
            t = $("#mytable").DataTable({
                initComplete: function() {
                    var api = this.api();
                    $('#mytable_filter input')
                        .off('.DT')
                        .on('keyup.DT', function(e) {
                            if (e.keyCode == 13) {
                                api.search(this.value).draw();
                            }
                        });
                },

                oLanguage: {
                    sProcessing: "loading...",
                    sEmptyTable: empty
                },
                oSearch: { "bSmart": false, "bRegex": true },
                processing: true,
                serverSide: true,
                fixedHeader: true,
                scrollCollapse: true,
                scrollX: true,
                ajax: {"url": "{{ route('ajax.table.career.detail') }}", "type": "GET","data":{"uid":uid}},
                columns: [
                    {
                        "data": "id",
                        "orderable": false,
                        "name":"id"
                    },
                    { "data": "branch", "name": "c.name"},
                    { "data": "position"},
                    {
                        "data": "effective_date",
                        "render": function(data){
                            return moment(data).format("DD-MM-YYYY");
                        }
                    },
                    { "data": "status"},
                    {
                        "data": "start_date",
                        "render": function(data, type, row){
                            if(!row['status'] || row['status'] == 'Permanent'){
                                return "-";
                            }
                            else{
                                return moment(data).format("DD-MM-YYYY");
                            }
                        }
                    },
                    {
                        "data": "end_date",
                        "render": function(data, type, row){
                            if(!row['status'] || row['status'] == 'Permanent'){
                                return "-";
                            }
                            else{
                                return moment(data).format("DD-MM-YYYY");
                            }
                        }
                    },
                    { "data": 'action', "orderable": false, "searchable": false}
                ],
                columnDefs:[
                    // {targets: 4, render: $.fn.dataTable.render.moment('Do MMM YYYYY')},
                    // { width: 200, targets: 5 }
                    ],
                fixedColums: true,
                order: [[1, 'asc']],
                rowCallback: function(row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                }
            });
        };
        function addHistory() {
            $('#modal-add').modal('show');
        }

        function editHistory(id) {
            $.ajax({
                url: "history/"+id,
                type: "GET",
                data:{"id":id},
                dataType: "JSON",
                contentType: "application/json",
                processData: false,
                success: function (data) {
                    if (data.status == 'success') {
                        // $('#form')[0].reset();
                        if(data.data.status && data.data.status != 'Permanent'){
                            $('#start-edit').show();
                            $('#end-edit').show();
                            $('#start-edit').attr('id', 'start-edit-show');
                            $('#end-edit').attr('id', 'end-edit-show');
                            $('#start_date_edit').val(formatDateSlash(data.data.start_date));
                            $('#end_date_edit').val(formatDateSlash(data.data.end_date));
                        }
                        $('#status-edit').val(data.data.status);
                        $('#id_edit').val(data.data.id);
                        $('#branch_id_edit').val(data.data.cabang_id);
                        $('#structure_id_edit').val(data.data.structure_organization_custom_id);
                        CKEDITOR.instances['job_desc_edit'].setData(data.data.job_desc);
                        // var date = new Date(data.data.effective_date);
                        // var strDate = date.getFullYear() +'/'+(date.getMonth()+1)+'/'+date.getDay();
                        $('#eff_date_edit').val(formatDateSlash(data.data.effective_date));
                        $('#modal-add').modal('hide');

                        if(data.job_desc_message == 'found'){
                            $('#general_edit').show();
                            if(data.job_desc != '' || data.job_desc != null){
                                $('#general_job_desc_edit').html('data.job_desc');
                            }
                            else{
                                $('#general_job_desc_edit').html('-');
                            }
                        }
                        else{
                            $('#general_edit').hide();
                        }
                        reload_table();
                    } else {
                        swal("Failed!", data.message, "error");
                    }
                    console.log(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
            $('#modal-edit').modal('show');
        }

        function reload_table()
        {
            $('#mytable').DataTable().ajax.reload(null,false); //reload datatable ajax
        }



        $("#employee" ).autocomplete({
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
                $("#user_id").val(ui.item.id).trigger('change');;
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });
        $("#employee2" ).autocomplete({
            minLength:0,
            limit: 25,
            appendTo : '#modal-add-body',
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
                $("#user_id2").val(ui.item.id);
            }
        }).on('focus', function () {
            $(this).autocomplete("search", "");
        });
        $('#form').on('submit',function () {
            var form = $('#form')[0]; // You need to use standart javascript object here
            var formData = new FormData(form);
            formData.append('jobd', CKEDITOR.instances['job_desc'].getData());
            formData.append('eff_date',formatDate(formData.get('eff_date')));
            formData.append('start_date',formatDate(formData.get('start_date')));
            formData.append('end_date',formatDate(formData.get('end_date')));
            formData.append('_token',"{{csrf_token()}}");
            if($('#start-show').attr('id') == 'start-show' && !formData.get('start_date')){
                    alert('Please set the start date of the contract');
            }
            else if($('#end-show').attr('id') == 'end-show' && !formData.get('end_date')){
                alert('Please set the end date of the contract');
            }
           else if(formData.get('branch') == 0){
               alert('Please select branch');
           }
           else if(formData.get('position') == 0){
               alert('Position should not be empty');
           }
           else if(formData.get('status') == ''){
               alert('Status should not be empty');
           }
           else if(formData.get('eff_date') == ''){
               alert('Effective date should not be empty');
           }
           else if(formData.get('jobd') == ''){
            alert('Job description should not be empty');
           }
           else{
               $.ajax({
                   url: "{{route('career.add-history')}}",
                   type: "POST",
                   data:formData,
                   dataType: "JSON",
                   contentType: false,
                   processData: false,
                   success: function (data) {
                       if (data.status == 'success') {
                           swal("Success!", data.message, "success");
                           $('#form')[0].reset();
                           $('#modal-add').modal('hide');

                           setTimeout(window.location.href = window.location.href, 1000);
                           reload_table();
                       } else {
                           swal("Failed!", data.message, "error");
                       }
                       console.log(data);
                   },
                   error: function (jqXHR, textStatus, errorThrown) {
                       console.log(jqXHR);
                       console.log(textStatus);
                       console.log(errorThrown);
                   }
               });
           }
           return false;
        });

        $('#form-edit').on('submit',function () {
            var form = $('#form-edit')[0]; // You need to use standart javascript object here
            var formData = new FormData(form);
            formData.append('job', CKEDITOR.instances['job_desc_edit'].getData());
            formData.append('eff_date_edit',formatDate(formData.get('eff_date_edit')));
            formData.append('start_date_edit',formatDate(formData.get('start_date_edit')));
            formData.append('end_date_edit',formatDate(formData.get('end_date_edit')));
            formData.append('_token',"{{csrf_token()}}");

            if($('#start-edit-show').attr('id') == 'start-edit-show' && !formData.get('start_date_edit')){
                    alert('Please set the start date of the contract');
            }
            else if($('#end-edit-show').attr('id') == 'end-edit-show' && !formData.get('end_date_edit')){
                alert('Please set the end date of the contract');
            }
            else if(formData.get('branch_edit') == 0){
               alert('Please select branch');
           }
           else if(formData.get('position_edit') == 0){
               alert('Position should not be empty');
           }
           else if(formData.get('eff_date_edit') == ''){
               alert('Effective date should not be empty');
           }
           else if(formData.get('job') == ''){
            alert('Job description should not be empty');
           }
           else{
            //    console.log(formData);
               $.ajax({
                   url: "{{route('administrator.career.update')}}",
                   type: "POST",
                   data:formData,
                   dataType: "JSON",
                   contentType: false,
                   processData: false,
                   success: function (data) {
                       if (data.status == 'success') {
                           swal("Success!", data.message, "success");
                           $('#form-edit')[0].reset();
                           $('#modal-edit').modal('hide');
                           reload_table();
                       } else {
                           swal("Failed!", data.message, "error");
                       }
                       console.log(data);
                   },
                   error: function (jqXHR, textStatus, errorThrown) {
                       console.log(jqXHR);
                       console.log(textStatus);
                       console.log(errorThrown);
                   }
               });
           }
           return false;
        });
        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year, month, day].join('-');
        }
        function formatDateSlash(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

            return [year, month, day].join('/');
        }
        function download() {
            window.location.href = "{{route('career.download.detail')}}?uid="+uid;
        }

        function remove(id) {
            swal({
                title: 'Are you sure?',
                text: "Once deleted, you will not be able to recover this data!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "history/"+id,
                        type: "DELETE",
                        data:{'_token':"{{csrf_token()}}"},
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status == 'success') {
                                swal("Success!", data.message, "success");
                                reload_table();
                            } else {
                                swal("Failed!", data.message, "error");
                            }
                            console.log();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                } else {

                }
            });
        }
    </script>


@endsection
@endsection
