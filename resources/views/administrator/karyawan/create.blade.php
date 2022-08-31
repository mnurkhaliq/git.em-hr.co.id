@extends('layouts.administrator')

@section('title', 'Karyawan')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Employee Form</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10 pull-right" onclick="submit()"><i class="fa fa-save"></i> Save Employee Data </button>
            </div>
        </div>
    <div class="row">
        <form class="form-horizontal" enctype="multipart/form-data" id="form-karyawan" action="{{ route('administrator.karyawan.store') }}" method="POST">
            <div class="col-md-12 p-l-0 p-r-0">
                <div class="white-box">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                            </ul>
                        </div>
                    @endif
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#biodata" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Personal Information</span></a></li>

                        <li role="presentation" class=""><a href="#dependent" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Dependent</span></a></li>
                        
                        <li role="presentation" class=""><a href="#education" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Education</span></a></li>
                        
                        <li role="presentation" class=""><a href="#certification" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Training</span></a></li>

                        <li role="presentation" class=""><a href="#department" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Branch and Position</span></a></li>

                        <li role="presentation" class=""><a href="#rekening_bank" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Bank Account</span></a></li>

                        <li role="presentation" class=""><a href="#inventaris" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Facilities</span></a></li>

                        <li role="presentation" class=""><a href="#shift" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Shift</span></a></li>

                        @if(checkModuleAdmin(4))
                        <li role="presentation" class=""><a href="#cuti" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Leave</span></a></li>
                        @endif
                        
                        @if(checkModuleAdmin(28))
                        <li role="presentation" class=""><a href="#VisitAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Visit</span></a></li>
                        @endif

                        @if(checkModuleAdmin(7))
                        <li role="presentation" class=""><a href="#OvertimeAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Overtime</span></a></li>
                        @endif

                        @if(checkModuleAdmin(13))
                        <li role="presentation" class=""><a href="#PayrollAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Payroll</span></a></li>
                        @endif

                        @if(checkModuleAdmin(27))
                        <li role="presentation" class=""><a href="#RecruitmentAssign" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Recruitment</span></a></li>
                        @endif

                        @if(checkModuleAdmin(34))
                        <li role="presentation" class=""><a href="#contract" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Contract</span></a></li>
                        @endif

                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade" id="cuti">
                            <h3>Leave</h3>
                            <a class="btn btn-info btn-xs" id="add_cuti"><i class="fa fa-plus"></i> Add</a>
                            <div class="clearfix"></div>
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Leave Name</th>
                                            <th>Quota</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table_cuti"></tbody>
                                </table>
                                <br />
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="inventaris">
                            <h3>Mobil</h3>
                            <a class="btn btn-info btn-xs" id="add_inventaris_mobil"><i class="fa fa-plus"></i> Tambah</a>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tipe Mobil</th>
                                        <th>Tahun</th>
                                        <th>No Polisi</th>
                                        <th>Status Mobil</th>
                                    </tr>
                                </thead>
                                <tbody class="table_mobil"></tbody>
                            </table>
                            <br />
                            <h3>Lainnya</h3>
                            <a class="btn btn-info btn-xs" id="add_inventaris_lainnya"><i class="fa fa-plus"></i> Tambah</a>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Inventaris</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="table_inventaris_lainnya"></tbody>
                            </table><br />
                        </div> 
                        <div role="tabpanel" class="tab-pane fade" id="rekening_bank">
                            <div class="form-group">
                                <label class="col-md-12">Name of Account</label>
                                <div class="col-md-6">
                                    <input type="text" name="nama_rekening" class="form-control" value="{{ old('nama_rekening') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Account Number</label>
                                <div class="col-md-6">
                                   <input type="text" name="nomor_rekening" class="form-control" value="{{ old('nomor_rekening') }}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Name of Bank</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="bank_id">
                                        <option value="">Choose Bank</option>
                                        @foreach(get_bank() as $item)
                                        <option value="{{ $item->id }}" {{ old('bank_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>                    
                        <div role="tabpanel" class="tab-pane fade" id="department">
                            @if(get_setting('struktur_organisasi') == 3)
                                <div class="form-group">
                                    <label class="col-md-12">Branch <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="branch_id" id="branch_id">
                                        <option value=""> - choose - </option>
                                        @foreach(cabang() as $item)
                                        <option value="{{ $item["id"] }}" {{ old('branch_id') == $item["id"] ? 'selected' : '' }}>{{ $item["name"] }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Position <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="structure_organization_custom_id">
                                            <option value=""> Choose </option>
                                            @foreach($structure as $item)
                                            <option value="{{ $item["id"]}}" {{ old('structure_organization_custom_id') == $item["id"] ? 'selected' : '' }}>{{ $item["name"] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Project</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="custom_project_id">
                                            <option value=""> Choose </option>
                                            @foreach($project as $item)
                                            <option value="{{ $item["id"] }}" {{ old('custom_project_id') == $item["id"] ? 'selected' : '' }}>{{ $item["name"] }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label class="col-md-12">Office Type</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="branch_type">
                                            <option value="">Choose Office Type</option>
                                            @foreach(['HO', 'BRANCH'] as $item)
                                            <option {{ old('branch_type') == $item ? ' selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                 <div class="form-group section-cabang" style="{{ old('branch_type') == "HO" ? 'display:none' : ''  }}">
                                    <label class="col-md-3">Branch</label>
                                    <div class="clearfix"></div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="cabang_id">
                                            <option value="">Choose Branch</option>
                                            @foreach(get_cabang() as $item)
                                            <option value="{{ $item->id }}" {{ old('cabang_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                    <div class="clearfix" /></div>
                                    <br class="clearfix" />
                                    <div class="col-md-12">
                                        <label><input type="checkbox" name="is_pic_cabang" value="1"> Branch PIC</label>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr />
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Director</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="empore_organisasi_direktur">
                                            <option value=""> Choose </option>
                                            @foreach(empore_list_direktur() as $item)
                                            <option value="{{ $item->id }}" {{ old('empore_organisasi_direktur') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Manager</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="empore_organisasi_manager_id">
                                            <option value=""> Choose </option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Staff</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="empore_organisasi_staff_id">
                                            <option value=""> Choose </option>
                                        </select> 
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Tab Visit  -->
                        <div role="tabpanel" class="tab-pane fade" id="VisitAssign" >
                            <div class="form-group">
                                <label class="col-md-12">Visit Type</label>
                                <div class="col-md-6">
                                    <select class="form-control " id="DivVisitType" name="master_visit_type_id">
                                        <option value=""> - VisitType - </option>
                                        @foreach($VisitTypeList as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == request()->master_visit_type_id ? 'selected' : '' }}>{{ $item->master_visit_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="DivBranch" name="DivBranch" style="display: none">
                                <label class="col-md-12">Branch</label>
                                <div class="col-md-6">
                                    @foreach(cabangvisit() as $item)
                                    <input type="checkbox" name="userbranchs[]" value="{{$item->id}}"> {{$item->name}}</input>
                                    <br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Visit Activity Category Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="master_category_visit_id">
                                        <option value=""> - choose Visit Activity Category Name - </option>
                                        @foreach($CategoryVisitList as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == request()->master_category_visit_id ? 'selected' : '' }}>{{ $item->master_category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Tab Overtime  -->
                        <div role="tabpanel" class="tab-pane fade" id="OvertimeAssign" >
                            <div class="form-group">
                                <label class="col-md-12">Overtime Entitlement</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="overtime_entitle">
                                        <option value="1" {{ old('overtime_entitle') ? 'selected' : '' }}>Entitle Overtime</option>
                                        <option value="" {{ !old('overtime_entitle') ? 'selected' : '' }}>Not Entitle Overtime</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Overtime Payment Setting</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="overtime_payroll_id">
                                        <option value="" hidden selected> - Select setting - </option>
                                        @foreach($OvertimePayroll as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('overtime_payroll_id') ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END Tab Overtime  -->
                        <!-- Tab Payroll  -->
                        <div role="tabpanel" class="tab-pane fade" id="PayrollAssign" >
                            <div class="form-group">
                                <label class="col-md-12">UMR Setting</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="payroll_umr_id">
                                        <option value=""> - Select setting - </option>
                                        @foreach($PayrollUMR as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('payroll_umr_id') ? 'selected' : '' }}>{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">PTKP <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select class="form-control" name="ptkp" id="ptkp">
                                        <option value="">- PTKP -</option>
                                        <option value="TK-0" {{ old('ptkp') == "TK-0" ? 'selected' : '' }}>TK-0</option>
                                        <option value="K-0" {{ old('ptkp') == "K-0" ? 'selected' : '' }}>K-0</option>
                                        <option value="K-1" {{ old('ptkp') == "K-1" ? 'selected' : '' }}>K-1</option>
                                        <option value="K-2" {{ old('ptkp') == "K-2" ? 'selected' : '' }}>K-2</option>
                                        <option value="K-3" {{ old('ptkp') == "K-3" ? 'selected' : '' }}>K-3</option>
                                    </select>
                                </div>
                                <div class="col-md-4" style="color: red" id="cycle_status">
                                    *Will be updated every January 1
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Payroll Cycle</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="payroll_cycle_id">
                                        <option value=""> - Select setting - </option>
                                        @foreach($PayrollCycle as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('payroll_cycle_id') ? 'selected' : '' }}>{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Attendance Cycle</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="attendance_cycle_id">
                                        <option value=""> - Select setting - </option>
                                        @foreach($AttendanceCycle as $item)
                                        <option value="{{ $item['id'] }}" {{ $item['id'] == old('attendance_cycle_id') ? 'selected' : '' }}>{{ $item['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END Tab Payroll  -->
                        <!-- Tab Recruitment  -->
                        <div role="tabpanel" class="tab-pane fade" id="RecruitmentAssign" >
                            <div class="form-group">
                                <label class="col-md-12">Recruitment Entitlement</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="recruitment_entitle">
                                        <option value="1" {{ old('recruitment_entitle') ? 'selected' : '' }}>Entitle Recruitment</option>
                                        <option value="" {{ !old('recruitment_entitle') ? 'selected' : '' }}>Not Entitle Recruitment</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END Tab Recruitment  -->

                        <div role="tabpanel" class="tab-pane fade active in" id="biodata">
                            {{ csrf_field() }}
                            <div class="col-md-6" style="padding-left: 0">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <img src="{{ asset('admin-css/images/user.png') }}" id="result_change_photo" style="width: 200px;" />
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-info btn-xs" onclick="open_dialog_photo()"><i class="fa fa-upload"></i> Change Photo</button>
                                        <input type="file" id="change_photo" name="foto" class="form-control" style="display: none;" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Name <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="name" style="text-transform: uppercase"  class="form-control " value="{{ old('name') }}"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Employee Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="employee_number" class="form-control " value="{{ old('employee_number')}}"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Absensi Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="absensi_number" class="form-control " value="{{ old('absensi_number')}}"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">NIK <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="nik" value="{{ old('nik')}}" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Ext</label>
                                    <div class="col-md-10">
                                        <input type="text" name="ext" value="{{ old('ext') }}" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Place of Birth</label>
                                    <div class="col-md-10">
                                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir')}}" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Date of Birth(yyyy-mm-dd) <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" name="tanggal_lahir" value="{{ old('tanggal_lahir')}}" class="form-control datepicker2" required> </div>

                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Marital Status <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="marital_status" id="marital_status">
                                            <option value="">- Marital Status -</option>
                                            <option value="Bujangan/Wanita" {{ old('marital_status') == "Bujangan/Wanita" ? 'selected' : '' }}>Single</option>
                                            <option value="Menikah" {{ old('marital_status') == "Menikah" ? 'selected' : '' }}>Married</option>
                                            <option value="Menikah Anak 1" {{ old('marital_status') == "Menikah Anak 1" ? 'selected' : '' }}>Married with 1 Child</option>
                                            <option value="Menikah Anak 2" {{ old('marital_status') == "Menikah Anak 2" ? 'selected' : '' }}>Married with 2 Child</option>
                                            <option value="Menikah Anak 3" {{ old('marital_status') == "Menikah Anak 3" ? 'selected' : '' }}>Married with 3 Child</option>
                                        </select>
                                    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Gender <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                            <option value=""> - Gender - </option>
                                            @foreach(['Male', 'Female'] as $item)
                                                <option {{ old('jenis_kelamin') == $item ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Blood Type</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control " name="blood_type" value="{{ old('blood_type') }}" /> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Email</label>
                                    <div class="col-md-10">
                                        <input type="email" value="{{ old('email') }}" class="form-control " name="email" id="example-email"> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Password <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="password" autocomplete="new-password" value="{{ old('password') }}" name="password" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="password" autocomplete="new-password" value="{{ old('confirm') }}" name="confirm" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Join Date(yyyy-mm-dd) <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" id="join_date" name="join_date" value="{{ old('join_date') }}" class="form-control  datepicker2" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group contract_container {{old('organisasi_status') && old('organisasi_status') != 'Permanent' ? '' : 'hidden'}}">
                                    <label class="col-md-12">Last Work/Login Date(yyyy-mm-dd)</label>
                                    <div class="col-md-10">
                                        <input type="text" name="inactive_date" class="form-control  datepicker2" value="{{ old('inactive_date') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Employee Status <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control " name="organisasi_status" id="organisasi_status">
                                            <option value="">- Select - </option>
                                            @foreach(['Permanent', 'Contract', 'Internship', 'Outsource', 'Freelance', 'Consultant'] as $item)
                                            <option {{ old('organisasi_status') == $item ? 'selected' : '' }}>{{ $item }}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                                <div class="form-group row contract_container {{old('organisasi_status') && old('organisasi_status') != 'Permanent' ? '' : 'hidden'}}">
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status Contract</label>
                                        <select class="form-control" name="status_contract">
                                            <option value="">- Select - </option>
                                            @foreach(['Sent', 'Returned'] as $item)
                                            <option {{ old('status_contract') == $item ? 'selected' : '' }} value="{{$item}}">Contract {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Start Date(yyyy-mm-dd)</label>
                                        <input type="text" name="start_date_contract" class="form-control  datepicker2" value="{{ old('start_date_contract') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>End Date(yyyy-mm-dd)</label>
                                        <input type="text" name="end_date_contract" class="form-control  datepicker2" value="{{ old('end_date_contract') }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Foreigners <input type="checkbox" name="foreigners_status" id="foreigners_status" class="" value="1" {{ old('foreigners_status') == 1 ? 'checked' : '' }}></label>
                                    <div class="col-md-10">
                                        <select class="form-control {{ old('foreigners_status') == 1 ? '' : 'hidden' }}" name="payroll_country_id">
                                            <option value="">- Select Country - </option>
                                            @foreach($payrollCountry as $item)
                                                <option {{ old('payroll_country_id') == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="padding-left: 0">
                                <div class="form-group">
                                    <label class="col-md-12">NPWP Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="npwp_number" value="{{ old('npwp_number') }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">BPJS Employment Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="bpjs_number" value="{{ old('bpjs_number') }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">BPJS Health Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="jamsostek_number" value="{{ old('jamsostek_number') }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">ID Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="ktp_number" value="{{ old('ktp_number') }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Passport Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">KK Number</label>
                                    <div class="col-md-10">
                                        <input type="text" name="kk_number" value="{{ old('kk_number') }}" class="form-control ">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Telephone</label>
                                    <div class="col-md-10">
                                        <input type="number" value="{{ old('telepon') }}" name="telepon" class="form-control "> </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-md-12">Mobile 1</label>
                                    <div class="col-md-10">
                                        <input type="number" name="mobile_1" value="{{ old('mobile_1') }}" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Mobile 2</label>
                                    <div class="col-md-10">
                                        <input type="number" name="mobile_2" value="{{ old('mobile_2') }}" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Emergency Contact Name</label>
                                    <div class="col-md-10">
                                        <input type="text" name="emergency_name" value="{{ old('emergency_name') }}" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Emergency Contact Relationship</label>
                                    <div class="col-md-10">
                                        <input type="text" name="emergency_relationship" value="{{ old('emergency_relationship') }}" class="form-control "> </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Emergency Contact Number</label>
                                    <div class="col-md-10">
                                        <input type="number" name="emergency_contact" value="{{ old('emergency_contact') }}" class="form-control "> </div>
                                </div>
                               <div class="form-group">
                                    <label class="col-md-12">Religion</label>
                                    <div class="col-md-10">
                                        <select class="form-control " name="agama">
                                            <option value=""> - Religion - </option>
                                            @foreach(agama() as $item)
                                                <option value="{{ $item }}" {{ old('agama') == $item ? 'selected' : '' }}> {{ $item }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Current Address</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control " name="current_address">{{ old('current_address') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">ID Addres</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control " name="id_address">{{ old('id_address') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">ID Picture</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_ktp" name="foto_ktp" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_ktp()" class="btn btn-default preview_ktp" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Family Card</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_kk" name="foto_kk" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_kk()" class="btn btn-default preview_kk" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Driver's license</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_sim" name="foto_sim" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_sim()" class="btn btn-default preview_sim" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12">Curriculum Vitae</label>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <input type="file" id="foto_cv" name="foto_cv" class="form-control " accept="image/*, application/pdf"/>
                                        </div>
                                        <div class="col-md-6">
                                            <a onclick="preview_cv()" class="btn btn-default preview_cv" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="dependent">
                            <h3 class="box-title m-b-0">Dependent</h3> <a class="btn btn-info btn-sm" id="btn_modal_dependent"><i class="fa fa-plus"></i> Add</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Relationship</th>
                                            <th>Contact Number</th>
                                            <th>Place of birth</th>
                                            <th>Date of birth</th>
                                            <th>Date of death</th>
                                            <th>Education Level</th>
                                            <th>Occupation</th>
                                            <th>Dependent</th>
                                        </tr>
                                    </thead>
                                    <tbody class="dependent_table"></tbody>
                                </table><br /><br />
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="education">
                            <h3 class="box-title m-b-0">Education</h3> <a class="btn btn-info btn-sm" id="btn_modal_education"><i class="fa fa-plus"></i> Add</a>
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
                                    <tbody class="education_table"></tbody>
                                </table><br /><br />
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="certification">
                            <h3 class="box-title m-b-0">Training</h3> <a class="btn btn-info btn-sm" id="btn_modal_certification"><i class="fa fa-plus"></i> Add</a>
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
                                            <th>Certificate Photo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="certification_table"></tbody>
                                    <div id="certificate-photo-container" style="display: none">
                                    </div>
                                </table><br /><br />
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="contract">
                            <h3 class="box-title m-b-0">Contract</h3> <a class="btn btn-info btn-sm" id="btn_modal_contract"><i class="fa fa-plus"></i> Add</a>
                            <br />
                            <br />
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Contract Number</th>
                                            <th>Contract Type</th>
                                            <th>Contract Start Date</th>
                                            <th>Contract End Date</th>
                                            <th>Contract Sent</th>
                                            <th>Contract Return</th>
                                            <th>Contract File</th>
                                        </tr>
                                    </thead>
                                    <tbody class="contract_table">
                                    </tbody>
                                    <div id="contract-photo-container" style="display: none">
                                    </div>
                                </table><br /><br />
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="shift">
                            <form class="form-control">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <select id="optShift" name="shift_id" class="form-control">
                                            <option value=""> - Select Shift - </option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    {{--
                    <a href="{{ route('administrator.karyawan.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                    <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Save Employee Data</button>
                    --}}
                    <br style="clear: both;" />
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>                    
    </div>
</div>
    @include('layouts.footer')
</div>

<!-- modal content dependent  -->
<div id="modal_dependent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Dependent</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-dependent">
                        <div class="form-group">
                            <label class="col-md-12">Name</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-nama">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Relationship</label>
                            <div class="col-md-12">
                                <select class="form-control modal-hubungan">
                                    <option value="">Choose Relationship</option>
                                    <!-- <option>Spouse</option> -->
                                    <option value="Suami">Husband</option>
                                    <option value="Istri">Wife</option>
                                    <option value="Ayah Kandung">Father</option>
                                    <option value="Ibu Kandung">Mother</option>
                                    <option value="Anak 1">First Child</option>
                                    <option value="Anak 2">Second Child</option>
                                    <option value="Anak 3">Third Child</option>
                                    <option value="Anak 4">Fourth Child</option>
                                    <option value="Anak 5">Fifth Child</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Contact Number</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-contact">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Place of birth</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-tempat_lahir">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Date of birth(yyyy-mm-dd)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker2 modal-tanggal_lahir">
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-12">Date of death(yyyy-mm-dd)</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker2 modal-tanggal_meninggal">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Education Level</label>
                            <div class="col-md-12">
                                <select class="form-control modal-jenjang_pendidikan">
                                    <option value="">Choose Education Level</option>
                                    <option value="TK">TK</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA / SMK">SMA / SMK</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Occupation</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-pekerjaan" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Dependent</label>
                            <div class="col-md-12">
                                <select class="form-control modal-tertanggung">
                                    <option>Yes</option>
                                    <option>No</option>
                                </select>
                            </div>
                        </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_dependent">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content education  -->
<div id="modal_education" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Education</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-education">
                        <div class="form-group">
                            <label class="col-md-3">Education</label>
                            <div class="col-md-9">
                                <select class="form-control modal-pendidikan">
                                    <option value="">Choose Education</option>
                                    <option>SD</option>
                                    <option>SMP</option>
                                    <option>SMA/SMK</option>
                                    <option>D1</option>
                                    <option>D2</option>
                                    <option>D3</option>
                                    <option>S1</option>
                                    <option>S2</option>
                                    <option>S3</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">School Name/University</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-fakultas" name="modal-fakultas" id="modal-fakultas"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Year of Start</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control modal-tahun_awal" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Year of Graduate</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control modal-tahun_akhir" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Major</label>
                            <div class="col-md-9">
                                <select class="form-control modal-jurusan">
                                    <option value="">Choose Major</option>
                                    @foreach(get_program_studi() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Grade</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-nilai" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">City</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-kota" name="modal-kota" id="modal-kota"/>
                            </div>
                        </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_education">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content certification  -->
<div id="modal_certification" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Training</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-certification">
                        <div class="form-group">
                            <label class="col-md-3">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-name" name="modal-name" id="modal-name"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Date(yyyy-mm-dd)</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datepicker2 modal-date" name="modal-date" id="modal-date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Organizer</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-organizer" name="modal-organizer" id="modal-organizer"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Certificate Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-certificate_number" name="modal-certificate_number" id="modal-certificate_number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Score</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-score" name="modal-score" id="modal-score"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control modal-description" name="modal-description" id="modal-description"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Certificate Photo</label>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <input type="file" id="modal-certificate_photo" name="modal-certificate_photo" class="form-control modal-certificate_photo" accept="image/*, application/pdf" style="display: none;"/>
                                    <label for="modal-certificate_photo" id="file-label" style="border: 1px solid #ccc;
                                    display: inline-block;
                                    padding: 6px 12px;
                                    cursor: pointer;">Choose File...</label>
                                </div>
                                <div class="col-md-6">
                                    <a onclick="preview_certificate()" class="btn btn-default preview_certificate" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                </div>
                                {{-- <output id='result_certicate'/> --}}
                            </div>
                        </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_certification">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content education  -->
<div id="modal_inventaris_mobil" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Tambah Inventaris Mobil</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-inventaris">
                        <div class="form-group">
                            <label class="col-md-12">Tipe Mobil</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-tipe_mobil">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Tahun</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-tahun">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="col-md-12">No Polisi</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-no_polisi">
                            </div>
                       </div>
                       <div class="form-group">
                            <label class="col-md-12">Status Mobil</label>
                            <div class="col-md-12">
                                <select class="form-control modal-status_mobil">
                                    <option value="">- none -</option>
                                    <option>Rental</option>
                                    <option>Perusahaan</option>
                                </select>
                            </div>
                       </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_inventaris_mobil">Tambah</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content education  -->
<div id="modal_inventaris_lainnya" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Tambah Inventaris Lainnya</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-inventaris">
                        <div class="form-group">
                            <label class="col-md-12">Jenis Inventaris</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control modal-inventaris-jenis">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-md-12">
                                <textarea class="form-control modal-inventaris-description"></textarea>
                            </div>
                       </div>
                      
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_inventaris_lainnya">Tambah</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content education  -->
<div id="modal_cuti" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Leave / Permit</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-cuti">
                        <div class="form-group">
                            <label class="col-md-12">Leave Name <span class="text-danger">*</span></label>
                            <div class="col-md-12">
                                <select class="form-control modal-jenis_cuti" id="jenis_cuti" name="jenis_cuti">
                                    <!-- @foreach(get_master_cuti() as $item)
                                    <option value="{{ $item->id }}" data-id="{{$item->id}}" >{{ $item->description }}</option>
                                    @endforeach -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Quota</label>
                            
                            <div class="col-md-12">
                                <input type="text" readonly="true" id="kuota" class="form-control modal-kuota">
                            </div>
                       </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_cuti">Add</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_cv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_cv"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_sim" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_sim"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_kk" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_kk"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_ktp" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_ktp"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_certificate" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_certificate"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- modal content contract  -->
<div id="modal_contract" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Add Contract</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal frm-modal-contract" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-md-3">Contract Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control modal-number" name="modal-number" id="modal-number"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Contract Type</label>
                            <div class="col-md-9">
                                <select id="modal-contract_type" class="form-control modal-contract_type" name="modal-contract_type">
                                    <option value="" disabled selected>--Contract Type--</option>
                                    <option value="Main Contract">Main Contract</option>
                                    <option value="Amendment">Amendment</option>
                                    <option value="SKB">SKB</option>
                                    <option value="Parklaking">Parklaking</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="form_contract_start_date">
                            <label class="col-md-3">Contract Start Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-contract_start_date" name="modal-contract_start_date" id="modal-contract_start_date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Contract End Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-contract_end_date" name="modal-contract_end_date" id="modal-contract_end_date"/>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label class="col-md-3">Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-date_contract" name="modal-date_contract" id="modal-date_contract"/>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label class="col-md-3">Contract Sent</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-contract_sent" name="modal-contract_sent" id="modal-contract_sent"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Contract Return</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control modal-return_contract" name="modal-return_contract" id="modal-return_contract"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Contract File</label>
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <input type="file" id="modal-file_contract" name="modal-file_contract" class="form-control modal-file_contract" accept="image/*, application/pdf" style="display: none;"/>
                                    <label for="modal-file_contract" id="file-label-contract" style="border: 1px solid #ccc;
                                    display: inline-block;
                                    padding: 6px 12px;
                                    cursor: pointer;">Choose File...</label>
                                </div>
                                <div class="col-md-6">
                                    <a onclick="preview_contract()" class="btn btn-default preview_contract" style="display: none"><i class="fa fa-search-plus"></i> View</a>
                                </div>
                                {{-- <output id='result_certicate'/> --}}
                            </div>
                        </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-info btn-sm" id="add_modal_contract">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="modal_file_contract" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body form-horizontal">
                <output id="result_contract"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<style type="text/css">
    .ui-autocomplete{
            z-index: 9999999 !important;
        }
</style>
@section('footer-script')
    <style type="text/css">
        .staff-branch-select, .head-branch-select {
            display: none;
        }
        .swal {
            margin: 10px;
        }
        
    </style>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Date picker plugins css -->
    <link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker-employee/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker-employee/bootstrap-datepicker.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/sweetalert2/4.2.4/sweetalert2.min.js"></script>
    <script src="{{ asset('js/administrator/karyawan-create.js') }}"></script>
    <script type="text/javascript">
        function preview_ktp()
        {
            $('#modal_file_ktp').modal('show');
        }
        function preview_kk()
        {
            $('#modal_file_kk').modal('show');
        }
        function preview_sim()
        {
            $('#modal_file_sim').modal('show');
        }
        function preview_cv()
        {
            $('#modal_file_cv').modal('show');
        }
        function preview_certificate()
        {
            $('#modal_file_certificate').modal('show');
        }

        function preview_contract()
        {
            $('#modal_file_contract').modal('show');
        }

        window.onload = function() {
            //Check File API support
            if (window.File && window.FileList && window.FileReader) {
                // var filesInput = document.getElementById("modal-certificate_photo");
                // filesInput.addEventListener("change", function(event) {
                //     var files = event.target.files; //FileList object
                //     var output = document.getElementById("result_certicate");
                //     $("#result_certicate").html("");
                //     for (var i = 0; i < files.length; i++) {
                //         var file = files[i];
                //         //Only pics and pdf
                //         if (!file.type.match('image') && !file.type === 'application/pdf')
                //             continue;
                //         var picReader = new FileReader();
                //         picReader.addEventListener("load", function(event) {
                //             var picFile = event.target;
                //             var div = document.createElement("div");
                //             if(!file.type.match('image')){
                //                 div.innerHTML = "<embed src='" + picFile.result + "' >";
                //             } else {
                //                 div.innerHTML = "<img width='100%' src='" + picFile.result + "' />";
                //             }
                //             output.insertBefore(div, null);
                //         });
                //         //Read the image
                //         picReader.readAsDataURL(file);
                //     }
                // });

                var filesInput = document.getElementById("change_photo");
                filesInput.addEventListener("change", function(event) {
                    var files = event.target.files; //FileList object
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        //Only pics and pdf
                        if (!file.type.match('image'))
                            continue;
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function(event) {
                            var picFile = event.target;
                            if(file.type.match('image')){
                                $('#result_change_photo').attr('src', picFile.result);
                            }
                        });
                        //Read the image
                        picReader.readAsDataURL(file);
                    }
                });

            } else {
                console.log("Your browser does not support File API");
            }
        }
    function submit() {
        @if(checkModuleAdmin(13))
        $(document).on('click', '.SwalBtn1', function() {
            checkResign();
        });
        $(document).on('click', '.SwalBtn2', function() {
            swal.clickConfirm();
        });
        
        swal({
            html: "PTKP field on the payroll tab will automatically follow the marital status and gender but you can change it, once the form is submitted PTKP cannot be changed again, are you sure you want to submit?" +
                "<br>" +
                '<button type="button" role="button" tabindex="0" class="SwalBtn1 swal btn btn-success">' + 'Submit' + '</button>' +
                '<button type="button" role="button" tabindex="0" class="SwalBtn2 swal btn btn-danger">' + 'Cancel' + '</button>',
            showCancelButton: false,
            showConfirmButton: false
        });
        @else
        checkResign();
        @endif
    }

    function checkResign() {
        $(document).on('click', '.CheckBtn1', function() {
            realSubmit()
        });
        $(document).on('click', '.CheckBtn2', function() {
            swal.clickConfirm();
        });
        
        if ($("input[name='inactive_date']").val() || $("input[name='end_date_contract']").val()) {
            swal({
                html: "Are you sure you want to fill end contract/last work/login date? Once the date has passed, it cannot be change again" +
                    "<br>" +
                    '<button type="button" role="button" tabindex="0" class="CheckBtn1 swal btn btn-success">' + 'Submit' + '</button>' +
                    '<button type="button" role="button" tabindex="0" class="CheckBtn2 swal btn btn-danger">' + 'Cancel' + '</button>',
                showCancelButton: false,
                showConfirmButton: false
            });
        } else {
            realSubmit()
        }
    }

    function realSubmit() {
        $('#form-karyawan').submit();
    }

    $(document).ready(function(){
        var checkVal = $("#DivVisitType").val();
        if(checkVal=='1'){
            $("#DivBranch").show();
        }
        else{
            $("#DivBranch").hide();
        }

        optJenisCuti()
    });

    $('#optShift').change(function() {
        optJenisCuti()
    })

    function optJenisCuti() {
        $("#jenis_cuti").html('')
        $.ajax({
            url: "{{route('ajax.leave.list')}}",
            type: 'GET',
            data: {
                'user_id': null,
                'shift_id': $('#optShift').val()
            },
            success: function(data){
                $.each(data, function(i, data){
                    $("#jenis_cuti").append('<option value="'+data.id+'" data-id="'+data.id+'" data-leavetype="'+data.jenis_cuti+'" data-kuota="'+data.kuota+'">'+data.description+'</option>')
                })
            }
        });
    }

    jQuery(function($) {
    $('#DivVisitType').on('change', function() {
      if ( this.value == '1')
      {
        $("#DivBranch").show();
      }
       else  
      {
        $("#DivBranch").hide();
      }
    });
    });
        $('#branch_id').on('change', function(){
            var branch_id = $(this).val()
            $.ajax({
                url: "{{route('shift.list')}}",
                type: 'GET',
                data: {'branch_id': branch_id},
                dataType: 'JSON',
                contentType: 'application/json',
                success: function(data){
                    if(data.message == 'success'){
                        var temp = $('#optShift')
                        temp.empty()
                        $('#optShift').append("<option value=''> - Select Shift - </option>");
                        $.each(data.data, function(i, data){
                            $('<option>', {
                                value: data.id,
                                text: data.name
                            }).html(data.name).appendTo('#optShift')
                        })
                    }
                    else{
                        var temp = $('#optShift')
                        temp.empty()
                        $('#optShift').append("<option value=''> - Select Shift - </option>");
                    }
                    console.log(data)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            })            
        })

        $('#optShift').on('change', function(){
            console.log($(this).val())
        })

        jQuery('.datepicker2').datepicker({
            format: 'yyyy-mm-dd',
        }).on('change', function(){
            $('.datepicker').hide();
        });;

        $("#modal-fakultas").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('ajax.get-university') }}",
                    method:"POST",
                    data: {'word' : request.term, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType:"json",
                    success:function(data)
                    {
                        response(data);
                    }
                })
            },
            select: function( event, ui ) {
                $("input[name='modal-fakultas']").val(ui.item.id)
            },
            showAutocompleteOnFocus: true
        });

        $("#modal-kota").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('ajax.get-city') }}",
                    method:"POST",
                    data: {'word' : request.term, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType:"json",
                    success:function(data)
                    {
                        response(data);
                    }
                })
            },
            select: function( event, ui ) {
                $("input[name='modal-kota']").val(ui.item.id)
            },
            showAutocompleteOnFocus: true
        });

          
        function open_dialog_photo()
        {
            $("input[name='foto']").trigger('click');   
        }

        $("select[name='empore_organisasi_direktur']").on('change', function(){
            var id  = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-manager-by-direktur') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {
                    var el = '<option value="">Choose</option>';

                    $(data.data).each(function(k,v){
                        console.log(v);
                       el += '<option value="'+ v.id +'">'+ v.name +'</option>';
                    });

                    $("select[name='empore_organisasi_manager_id']").html(el);
                }
            });
        }); 


        $("select[name='empore_organisasi_manager_id']").on('change', function(){
            var id  = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-staff-by-manager') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {
                    var el = '<option value="">Choose</option>';

                    $(data.data).each(function(k,v){
                        console.log(v);
                       el += '<option value="'+ v.id +'">'+ v.name +'</option>';
                    });

                    $("select[name='empore_organisasi_staff_id']").html(el);
                }
            });
        }); 

         $("select[name='jabatan_cabang']").on('change', function(){

            if($(this).val() =='Staff')
            {
                $('.head-branch-select').hide();
                $('.staff-branch-select').show();
            }
            else if($(this).val() =='Head')
            {
                $('.head-branch-select').show();
                $('.staff-branch-select').hide();   
            }
            else
            {
                $('.head-branch-select').hide();
                $('.staff-branch-select').hide();
            }

        });

        $("select[name='branch_type']").on('change', function(){

            if($(this).val() == 'BRANCH')
            {
                $(".section-cabang").show();
            }
            else
            {
                $(".section-cabang").hide();
            }
        });


        $("#add_inventaris_lainnya").click(function(){

            $("#modal_inventaris_lainnya").modal('show');
        });

        $("#add_modal_inventaris_lainnya").click(function(){

            var el = '<tr>';
            var modal_jenis            = $('.modal-inventaris-jenis').val();
            var modal_description                 = $('.modal-inventaris-description').val();
          
            el += '<td>'+ (parseInt($('.table_mobil tr').length) + 1)  +'</td>';
            el +='<td>'+ modal_jenis +'</td>';
            el +='<td>'+ modal_description +'</td>';;
            el +='<input type="hidden" name="inventaris_lainnya[jenis][]" value="'+ modal_jenis +'" />';
            el +='<input type="hidden" name="inventaris_lainnya[description][]" value="'+ modal_description +'" />';

            $('.table_inventaris_lainnya').append(el);
            $('#modal_inventaris_lainnya').modal('hide');
        });

        $("#add_cuti").click(function(){
            let array = [];
            $(".cuti-id").each(function() {
                array.push($(this).val());
            });

            $("#jenis_cuti option").show();
            $("#jenis_cuti option").each(function() {
                if(array.includes($(this).attr('value')))
                    $(this).hide();
            });
           
            if (join_date.value != "")
            {
               $('.modal-kuota').val("");
               $('#jenis_cuti').val("");
               $("#modal_cuti").modal('show');
            }
            else
            {
            bootbox.alert('<label style="color: red;">Please Select Join Date First </label>');
            }
        });

        $('#join_date').change(function(){
            var join_date            = $("#join_date").val();
          
        });

        $("select[name='jenis_cuti']").on('change', function(){
            var jenis_cuti = $("#jenis_cuti").val();
            var joindate = join_date.value;
            var url = '{{ route("administrator.karyawan.get-annual", ":cuti_id/:join_date") }}';
            url = url.replace(':cuti_id', jenis_cuti );
            url = url.replace(':join_date', joindate );
            var kuotacuti = $('#kuota').val();
            $.ajax({
                type: "GET",
                url:url,
                data: {
                    kuotacuti: kuotacuti
                },

                success: function(data) {
                    $('.modal-kuota').val(data);
                }
            });
        });
            
        $("#add_modal_cuti").click(function(){

            if($('#jenis_cuti').val() == "" || $('#kuota').val() == "")
            {
                bootbox.alert('Please Select Leave Name');
                return false;
            }

            var jenis_cuti = $('.modal-jenis_cuti :selected');
            var kuota = $('.modal-kuota').val();

            var el = '<tr><td>'+ (parseInt($('.table_cuti tr').length) + 1) +'</td><td>'+ jenis_cuti.text() +'</td><td>'+ kuota +'</td></tr>';
            
            el += '<input type="hidden" class="cuti-id" value="'+ jenis_cuti.val() +'" />';
            el += '<input type="hidden" name="cuti[cuti_id][]" value="'+ jenis_cuti.val() +'" />';
            el += '<input type="hidden" name="cuti[kuota][]" value="'+ kuota +'" />';

            $("form.frm-modal-cuti").trigger('reset');

            $('.table_cuti').append(el);

            $("#modal_cuti").modal('hide');
        });

        $("#add_inventaris_mobil").click(function(){

            $("#modal_inventaris_mobil").modal('show');
        });

        $("#add_modal_inventaris_mobil").click(function(){

            var el = '<tr>';
            var modal_tipe_mobil            = $('.modal-tipe_mobil').val();
            var modal_tahun                 = $('.modal-tahun').val();
            var modal_no_polisi             = $('.modal-no_polisi').val();
            var modal_status_mobil          = $('.modal-status_mobil').val();
            
            el += '<td>'+ (parseInt($('.table_mobil tr').length) + 1)  +'</td>';
            el +='<td>'+ modal_tipe_mobil +'</td>';
            el +='<td>'+ modal_tahun +'</td>';
            el +='<td>'+ modal_no_polisi +'</td>';
            el +='<td>'+ modal_status_mobil +'</td>';
            el +='<input type="hidden" name="inventaris_mobil[tipe_mobil][]" value="'+ modal_tipe_mobil +'" />';
            el +='<input type="hidden" name="inventaris_mobil[tahun][]" value="'+ modal_tahun +'" />';
            el +='<input type="hidden" name="inventaris_mobil[no_polisi][]" value="'+ modal_no_polisi +'" />';
            el +='<input type="hidden" name="inventaris_mobil[status_mobil][]" value="'+ modal_status_mobil +'" />';

            $('.table_mobil').append(el);
            $('#modal_inventaris_mobil').modal('hide');
        });


        $("#add_modal_dependent").click(function(){

            var el = '<tr>';
            var modal_nama                  = $('.modal-nama').val();
            var modal_hubungan              = $('.modal-hubungan').val();
            var modal_contact               = $('.modal-contact').val();
            var modal_tempat_lahir          = $('.modal-tempat_lahir').val();
            var modal_tanggal_lahir         = $('.modal-tanggal_lahir').val();
            var modal_tanggal_meninggal     = $('.modal-tanggal_meninggal').val();
            var modal_jenjang_pendidikan    = $('.modal-jenjang_pendidikan').val();
            var modal_pekerjaan             = $('.modal-pekerjaan').val();
            var modal_tertanggung           = $('.modal-tertanggung').val();
            
            el += '<td>'+ parseInt($('.dependent_table tr').length) + 1  +'</td>';
            el +='<td>'+ modal_nama +'</td>';
            el +='<td>'+ modal_hubungan +'</td>';
            el +='<td>'+ modal_contact +'</td>';
            el +='<td>'+ modal_tempat_lahir +'</td>';
            el +='<td>'+ modal_tanggal_lahir +'</td>';
            el +='<td>'+ modal_tanggal_meninggal +'</td>';
            el +='<td>'+ modal_jenjang_pendidikan +'</td>';
            el +='<td>'+ modal_pekerjaan +'</td>';
            el +='<td>'+ modal_tertanggung +'</td>';
            el +='<input type="hidden" name="dependent[nama][]" value="'+ modal_nama +'" />';
            el +='<input type="hidden" name="dependent[hubungan][]" value="'+ modal_hubungan +'" />';
            el +='<input type="hidden" name="dependent[contact][]" value="'+ modal_contact +'" />';
            el +='<input type="hidden" name="dependent[tempat_lahir][]" value="'+ modal_tempat_lahir +'" />';
            el +='<input type="hidden" name="dependent[tanggal_lahir][]" value="'+ modal_tanggal_lahir +'" />';
            el +='<input type="hidden" name="dependent[tanggal_meninggal][]" value="'+ modal_tanggal_meninggal +'" />';
            el +='<input type="hidden" name="dependent[jenjang_pendidikan][]" value="'+ modal_jenjang_pendidikan +'" />';
            el +='<input type="hidden" name="dependent[pekerjaan][]" value="'+ modal_pekerjaan +'" />';
            el +='<input type="hidden" name="dependent[tertanggung][]" value="'+ modal_tertanggung +'" />';

            $('.dependent_table').append(el);
            $('.frm-modal-dependent').trigger('reset');
            $('#modal_dependent').modal('hide');
        });

        $("#add_modal_education").click(function(){
            var el = '<tr>';
            var modal_pendidikan            = $('.modal-pendidikan').val();
            var modal_fakultas              = $('.modal-fakultas').val();
            var modal_tahun_awal            = $('.modal-tahun_awal').val();
            var modal_tahun_akhir           = $('.modal-tahun_akhir').val();
            var modal_jurusan               = $('.modal-jurusan').val();
            var modal_nilai                 = $('.modal-nilai').val();
            var modal_kota                  = $('.modal-kota').val();
            
            el += '<td>'+ (parseInt($('.education_table tr').length) + 1 )  +'</td>';
            el +='<td>'+ modal_pendidikan +'</td>';
             el +='<td>'+ modal_fakultas +'</td>';
            el +='<td>'+ modal_tahun_awal +'</td>';
            el +='<td>'+ modal_tahun_akhir +'</td>';
            el +='<td>'+ modal_jurusan +'</td>';
            el +='<td>'+ modal_nilai +'</td>';
            el +='<td>'+ modal_kota +'</td>';
            el +='<input type="hidden" name="education[pendidikan][]" value="'+ modal_pendidikan +'" />';
            el +='<input type="hidden" name="education[fakultas][]" value="'+ modal_fakultas +'" />';
            el +='<input type="hidden" name="education[tahun_awal][]" value="'+ modal_tahun_awal +'" />';
            el +='<input type="hidden" name="education[tahun_akhir][]" value="'+ modal_tahun_akhir +'" />';
            el +='<input type="hidden" name="education[jurusan][]" value="'+ modal_jurusan +'" />';
            el +='<input type="hidden" name="education[nilai][]" value="'+ modal_nilai +'" />';
            el +='<input type="hidden" name="education[kota][]" value="'+ modal_kota +'" />';

            $('.education_table').append(el);

            $('#modal_education').modal('hide');
            $('form.frm-modal-education').reset();
        });

        $("#add_modal_certification").click(function(){
            var el = '<tr>';
            var modal_name                  = $('.modal-name').val();
            var modal_date                  = $('.modal-date').val();
            var modal_organizer             = $('.modal-organizer').val();
            var modal_certificate_number    = $('.modal-certificate_number').val();
            var modal_score                 = $('.modal-score').val();
            var modal_description           = $('.modal-description').val();
            var modal_certificate_photo     = $('.modal-certificate_photo').val();
            var photo                       = $(".modal-certificate_photo").prop("files");
            
            el +='<td>'+ (parseInt($('.certification_table tr').length) + 1 )  +'</td>';
            el +='<td>'+ modal_name +'</td>';
            el +='<td>'+ modal_date +'</td>';
            el +='<td>'+ modal_organizer +'</td>';
            el +='<td>'+ modal_certificate_number +'</td>';
            el +='<td>'+ modal_score +'</td>';
            el +='<td>'+ modal_description +'</td>';
            el +='<td>'+ modal_certificate_photo +'</td>';
            el +='<input type="hidden" name="certification[name][]" value="'+ modal_name +'" />';
            el +='<input type="hidden" name="certification[date][]" value="'+ modal_date +'" />';
            el +='<input type="hidden" name="certification[organizer][]" value="'+ modal_organizer +'" />';
            el +='<input type="hidden" name="certification[certificate_number][]" value="'+ modal_certificate_number +'" />';
            el +='<input type="hidden" name="certification[score][]" value="'+ modal_score +'" />';
            el +='<input type="hidden" name="certification[description][]" value="'+ modal_description +'" />';
            // el +='<input type="file" name="certification[certificate_photo][]" class="temp_file" style="display: none;"/>';
            $('#certificate-photo-container').append('<input type="file" name="certification[certificate_photo][]" class="certificate-photo-file"/>')
            $(".certificate-photo-file:last").prop("files",$(".modal-certificate_photo").prop("files"));
            // $("input[name='certification[certificate_photo][]']").prop("files",$(".modal-certificate_photo").prop("files"));


            $('.certification_table').append(el);
            
            $('#modal_certification').modal('hide');

            $('#file-label').html('Choose File...')
            $('form.frm-modal-certification input[type="text"]').val('')
            $('form.frm-modal-certification textarea').val('')
            // $('form.frm-modal-certification').trigger('reset')
        });

        $("#add_modal_contract").click(function(){
            var el = '<tr>';
            var modal_number                  = $('.modal-number').val();
            var modal_type                  = $('.modal-contract_type').val();
            var modal_start_date                  = $('.modal-contract_start_date').val();
            var modal_end_date                  = $('.modal-contract_end_date').val();
            var modal_date_contract                 = $('.modal-date_contract').val();
            var modal_contract_sent           = $('.modal-contract_sent').val();
            var modal_return_contract    = $('.modal-return_contract').val();
            var modal_file_contract    = $('.modal-file_contract').val();
            var photo                       = $(".modal-file_contract").prop("files");
            
            el +='<td>'+ (parseInt($('.contract_table tr').length) + 1 )  +'</td>';
            el +='<td>'+ modal_number +'</td>';
            el +='<td>'+ modal_type +'</td>';
            el +='<td>'+ modal_start_date +'</td>';
            el +='<td>'+ modal_end_date +'</td>';
            // el +='<td>'+ modal_date_contract +'</td>';
            el +='<td>'+ modal_contract_sent +'</td>';
            el +='<td>'+ modal_return_contract +'</td>';
            el +='<td>'+ modal_file_contract +'</td>';
            el +='<input type="hidden" name="contract[number][]" value="'+ modal_number +'" />';
            el +='<input type="hidden" name="contract[type][]" value="'+ modal_type +'" />';
            el +='<input type="hidden" name="contract[start_date][]" value="'+ modal_start_date +'" />';
            el +='<input type="hidden" name="contract[end_date][]" value="'+ modal_end_date +'" />';
            // el +='<input type="hidden" name="contract[date][]" value="'+ modal_date_contract +'" />';
            el +='<input type="hidden" name="contract[contract_sent][]" value="'+ modal_contract_sent +'" />';
            el +='<input type="hidden" name="contract[return_contract][]" value="'+ modal_return_contract +'" />';
            $('#contract-photo-container').append('<input type="file" name="contract[file_contract][]" class="contract-photo-file"/>')
            $(".contract-photo-file:last").prop("files",$(".modal-file_contract").prop("files"));
            $('.contract_table').append(el);
            
            $('#modal_contract').modal('hide');
            $('#file-label-contract').html('Choose File...')
            $('form.frm-modal-contract input[type="text"]').val('')
            $('form.frm-modal-contract input[type="date"]').val('')
            // $('form.frm-modal-contract').trigger('reset')
        });

        $(document).on('change', '.modal-certificate_photo', function(){
            var fileName = $(this)[0].files[0].name;
            $('#file-label').html(fileName)
        })

        $(document).on('change', '.modal-file_contract', function(){
            var fileName = $(this)[0].files[0].name;
            $('#file-label-contract').html(fileName)
        })

        $("#btn_modal_dependent").click(function(){

            $('#modal_dependent').modal('show');

        });

        $("#btn_modal_education").click(function(){

            $('#modal_education').modal('show');

        });

        $("#btn_modal_certification").click(function(){

            $('#modal_certification').modal('show');

        });

        $("#btn_modal_contract").click(function(){

            $('#modal_contract').modal('show');

        });

        function get_kabupaten(el)
        {
            var id = $(el).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-kabupaten-by-provinsi') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value="">Choose Districts</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id_kab +"\">"+ v.nama +"</option>";
                    });

                    $(el).parent().find('select').html(html_);
                }
            });
        }

        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
        });

        $("select[name='provinsi_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-kabupaten-by-provinsi') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value="">Choose Districts</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id_kab +"\">"+ v.nama +"</option>";
                    });

                    $("select[name='kabupaten_id'").html(html_);
                }
            });
        });

        $("select[name='kabupaten_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.get-kecamatan-by-kabupaten') }}',
                    data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {

                        var html_ = '<option value=""> Choose Sub-District</option>';

                        $(data.data).each(function(k, v){
                            html_ += "<option value=\""+ v.id_kec +"\">"+ v.nama +"</option>";
                        });

                        $("select[name='kecamatan_id'").html(html_);
                    }
            });
        });

        $("select[name='kecamatan_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.get-kelurahan-by-kecamatan') }}',
                    data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                    dataType: 'json',
                    success: function (data) {

                        var html_ = '<option value=""> Choose Village</option>';

                        $(data.data).each(function(k, v){
                            html_ += "<option value=\""+ v.id_kel +"\">"+ v.nama +"</option>";
                        });

                        $("select[name='kelurahan_id'").html(html_);
                    }
            });
        });

        $("select[name='division_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-department-by-division') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value=""> Choose Department</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id +"\">"+ v.name +"</option>";
                    });

                    $("select[name='department_id'").html(html_);
                }
            });
        });

        $("select[name='department_id']").on('change', function(){

            var id = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-section-by-department') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    var html_ = '<option value=""> Choose Section</option>';

                    $(data.data).each(function(k, v){
                        html_ += "<option value=\""+ v.id +"\">"+ v.name +"</option>";
                    });

                    $("select[name='section_id'").html(html_);
                }
            });
        });
        $("#organisasi_status").on('change',function(){
            if(!$(this).val() || $(this).val()=='Permanent'){
                $('.contract_container').addClass('hidden');
                $("select[name='status_contract']").val('');
                $("input[name='start_date_contract']").datepicker('setDate','');
                $("input[name='end_date_contract']").datepicker('setDate','');
            }
            else{
                $('.contract_container').removeClass('hidden');
            }
        });
        $("#modal-contract_type").on('change',function(){
            if(!$(this).val() || $(this).val()=='Amendment'){
                $('#form_contract_start_date').addClass('hidden');
            }
            else{
                $('#form_contract_start_date').removeClass('hidden');
            }
        });
        $("#foreigners_status").change(function() {
            if(this.checked) {
                $("select[name='payroll_country_id']").removeClass('hidden');
            } else {
                $("select[name='payroll_country_id']").addClass('hidden');
                $("select[name='payroll_country_id']").val('');
            }
        });
        $("#marital_status, #jenis_kelamin").change(function() {
            if ($("#jenis_kelamin").val() == 'Female' || $("#jenis_kelamin").val() == "") {
                $("#ptkp").val('TK-0');
            } else if ($("#jenis_kelamin").val() == 'Male') {
                if($("#marital_status").val() == 'Bujangan/Wanita' || $("#marital_status").val() == "") {
                    $("#ptkp").val('TK-0');
                }
                else if($("#marital_status").val() == 'Menikah') {
                    $("#ptkp").val('K-0');
                }
                else if($("#marital_status").val() == 'Menikah Anak 1') {
                    $("#ptkp").val('K-1');
                }
                else if($("#marital_status").val() == 'Menikah Anak 2') {
                    $("#ptkp").val('K-2');
                }
                else if($("#marital_status").val() == 'Menikah Anak 3') {
                    $("#ptkp").val('K-3');
                }
            }
        });
        $("input[name='end_date_contract']").datepicker().on("change", function (e) {
            $("input[name='inactive_date']:not(:disabled)").datepicker('setDate', e.target.value);
        });
    </script>
@endsection
@endsection