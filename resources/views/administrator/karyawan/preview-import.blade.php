@extends('layouts.administrator')

@section('title', 'Preview Import Employee')

@section('sidebar')

@endsection

@section('content')

<style>
    .table-responsive .table-bordered th {
        vertical-align: middle;
    }
</style>

<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Dashboard</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Employee</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Manage Preview Import </h3>
                    <a href="{{ route('administrator.karyawan.import-all') }}" onclick="return confirm('Process all data?')" class="btn btn-info btn-sm">Process All Data</a>
                    <br>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>JOIN DATE</th>
                                    <th>GENDER</th>
                                    <th>PLACE OF BIRTH</th>
                                    <th>DATE OF BIRTH</th>
                                    <th>ID ADDRESS</th>
                                    <th>CURRENT ADDRESS</th>
                                    <th>TELP</th>
                                    <th>MOBILE 1</th>
                                    <th>MOBILE 2</th>
                                    <th>EMAIL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td><center>{{ $no+1 }}</center></td>
                                        <td>
                                            {{ $item->nik }}<br />
                                            <a class="btn btn-info btn-xs" onclick="slide_toogle(this)"><i class="fa fa-info"></i> detail</a>
                                            @if(!empty($item->user))
                                            <a href="{{ route('administrator.karyawan.edit', $item->user->id) }}" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-info"></i> view data yang sama </a>
                                            @endif
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->join_date }}</td>
                                        <td>{{ $item->gender }}</td>
                                        <td>{{ $item->place_of_birth }}</td>
                                        <td>{{ $item->date_of_birth }}</td>
                                        <td>{{ $item->id_address }}</td>
                                        <td>{{ $item->current_address }}</td>
                                        <td>{{ $item->telp }}</td>
                                        <td>{{ $item->mobile_1 }}</td>
                                        <td>{{ $item->mobile_2 }}</td>
                                        <td>{{ $item->email }}</td>
                                    </tr>
                                    <tr class="sub_detail" style="display: none;">
                                        <td colspan="18">
                                          <div style="border: 3px solid #dcd1d1;padding:10px;">
                                            <ul class="nav customtab nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#indentity{{ $item->id }}" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Indentity</span></a></li>

                                                <li role="presentation" class=""><a href="#dependent{{ $item->id }}" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Dependent</span></a></li>

                                                <li role="presentation" class=""><a href="#education{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Education</span></a></li>

                                                <li role="presentation" class=""><a href="#certification{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Training</span></a></li>

                                                <li role="presentation" class=""><a href="#department{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Branch and Position</span></a></li>

                                                <li role="presentation" class=""><a href="#emergency{{ $item->id }}" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Emergency Contact</span></a></li>

                                                <li role="presentation" class=""><a href="#status{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Employee Status</span></a></li>

                                                <li role="presentation" class=""><a href="#rekening_bank{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Bank Account</span></a></li>
                                                @if(checkModuleAdmin(4))
                                                <li role="presentation" class=""><a href="#cuti{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Annual Leave</span></a></li>
                                                @endif
                                                @if(checkModuleAdmin(28))
                                                <li role="presentation" class=""><a href="#VisitAssign{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Visit</span></a></li>
                                                @endif
                                                @if(checkModuleAdmin(7))
                                                <li role="presentation" class=""><a href="#OvertimeAssign{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Overtime</span></a></li>
                                                @endif
                                                @if(checkModuleAdmin(13))
                                                <li role="presentation" class=""><a href="#PayrollAssign{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Payroll</span></a></li>
                                                @endif
                                                @if(checkModuleAdmin(27))
                                                <li role="presentation" class=""><a href="#RecruitmentAssign{{ $item->id }}" aria-controls="settings" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-settings"></i></span> <span class="hidden-xs">Recruitment</span></a></li>
                                                @endif
                                            </ul>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade active in" id="indentity{{ $item->id }}">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>Employee Number</td>
                                                            <td>{{$item->employee_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Absence Number</td>
                                                            <td>{{$item->absensi_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>KTP Number</td>
                                                            <td>{{$item->ktp_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Passport Number</td>
                                                            <td>{{$item->passport_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>KK Number</td>
                                                            <td>{{$item->kk_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>NPWP Number</td>
                                                            <td>{{$item->npwp_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>BPJS Tenaga Kerja Number</td>
                                                            <td>{{$item->jamsostek_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>BPJS Kesehatan Number</td>
                                                            <td>{{$item->bpjs_number}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Marital Status</td>
                                                            <td>{{$item->marital_status}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Religion</td>
                                                            <td>{{$item->agama}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Blood Type</td>
                                                            <td>{{$item->blood_type}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ext</td>
                                                            <td>{{$item->ext}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Country</td>
                                                            <td>{{$item->payroll_country_id ? $item->payrollCountry->name : $item->payroll_country_id}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Working Shift</td>
                                                            <td>{{$item->shift_id ? $item->shift->name : $item->shift_id}}</td>
                                                        </tr>
                                                    </table>
                                               </div>
                                                <div role="tabpanel" class="tab-pane fade" id="dependent{{ $item->id }}">
                                                     <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Relationship</th>
                                                                <th>Contact Number</th>
                                                                <th>Relative Name</th>
                                                                <th>Gender</th>
                                                                <th>Date of Birth</th>
                                                                <th>Occupation</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($item->family as $no => $i)
                                                            <tr>
                                                                <td><center>{{ $no+1 }}</center></td>
                                                                <td>{{ $i->hubungan  }}</td>
                                                                <td>{{ $i->contact  }}</td>
                                                                <td>{{ $i->nama  }}</td>
                                                                <td>{{ $i->gender  }}</td>
                                                                <td>{{ $i->tanggal_lahir  }}</td>
                                                                <td>{{ $i->pekerjaan  }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <br />
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="education{{ $item->id }}">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Education</th>
                                                                <th>Start Year</th>
                                                                <th>End Year</th>
                                                                <th>Institution</th>
                                                                <th>City</th>
                                                                <th>Major</th>
                                                                <th>GPA</th>
                                                                <th>Certificate</th>
                                                                <th>Note</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($item->education as $no => $i)
                                                            <tr>
                                                                <td><center>{{ $no+1 }}</center></td>
                                                                <td>{{ $i->pendidikan }}</td>
                                                                <td>{{ $i->tahun_awal }}</td>
                                                                <td>{{ $i->tahun_akhir }}</td>
                                                                <td>{{ $i->fakultas }}</td>
                                                                <td>{{ $i->kota }}</td>
                                                                <td>{{ $i->jurusan }}</td>
                                                                <td>{{ $i->nilai }}</td>
                                                                <td>{{ $i->certificate }}</td>
                                                                <td>{{ $i->note }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="certification{{ $item->id }}">
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
                                                        <tbody>
                                                            @foreach($item->certification as $no => $i)
                                                            <tr>
                                                                <td><center>{{ $no+1 }}</center></td>
                                                                <td>{{ $i->name }}</td>
                                                                <td>{{ $i->date }}</td>
                                                                <td>{{ $i->organizer }}</td>
                                                                <td>{{ $i->certificate_number }}</td>
                                                                <td>{{ $i->score }}</td>
                                                                <td>{{ $i->description }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div role="tabpanel" class="tab-pane fade" id="department{{ $item->id }}">
                                                     <table class="table table-bordered">
                                                        <tr>
                                                            <td>Branch</td>
                                                            <td>{{$item->branch ? $item->cabang->name : $item->branch}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Position</td>
                                                            <td>{{$item->structure && isset($item->structure->position) ? $item->structure->position->name : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Division</td>
                                                            <td>{{$item->structure && isset($item->structure->division) ? $item->structure->division->name : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Title</td>
                                                            <td>{{$item->structure && isset($item->structure->title) ? $item->structure->title->name : ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Project</td>
                                                            <td>{{$item->project ? $item->project->name : $item->project}}</td>
                                                        </tr>
                                                     </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="emergency{{ $item->id }}">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>Emergency Contact Name</td>
                                                            <td>{{ $item->emergency_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Emergency Contact Relationship</td>
                                                            <td>{{ $item->emergency_relationship }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Emergency Contact Number</td>
                                                            <td>{{ $item->emergency_contact }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="status{{ $item->id }}">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>Employee Status</td>
                                                            <td>{{ $item->organisasi_status }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Status Contract</td>
                                                            <td>{{ $item->status_contract }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Start Date Contract</td>
                                                            <td>{{ $item->start_date_contract }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>End Date Contract</td>
                                                            <td>{{ $item->end_date_contract }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="rekening_bank{{ $item->id }}">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td>Bank Name</td>
                                                            <td>{{ $item->bank_1 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Account Name</td>
                                                            <td>{{ $item->bank_account_name_1 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bank Account Number</td>
                                                            <td>{{ $item->bank_account_number }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="VisitAssign{{ $item->id }}">
                                                     <table class="table table-bordered">
                                                        <tr>
                                                            <td>Visit Type</td>
                                                            <td>{{$item->VisitType ? $item->VisitType->master_visit_type_name : $item->VisitType}}</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Visit Category</td>
                                                            <td>{{$item->CategoryActivityVisit ? $item->CategoryActivityVisit->master_category_name : $item->CategoryActivityVisit}}</td>
                                                        </tr>
                                                     </table>
                                                     <br>
                                                     <br>
                                                     <b>Visit Branch</b>
                                                     <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th width="70" class="text-center">No</th>
                                                                <th>BranchName</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($item->branchVisit as $no => $i)
                                                            <tr>
                                                                <td><center>{{ $no+1 }}</center></td>
                                                                <td>{{ $i->branch->name  }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="OvertimeAssign{{ $item->id }}">
                                                    <table class="table table-bordered">
                                                       <tr>
                                                           <td>Overtime Entitlement</td>
                                                           <td>{{$item->overtime_entitle == null ? $item->overtime_entitle : ($item->overtime_entitle ? 'Entitle Overtime' : 'Not Entitle Overtime')}}</td>
                                                       </tr>
                                                       
                                                       <tr>
                                                           <td>Overtime Payment Setting</td>
                                                           <td>{{$item->overtimePayroll ? $item->overtimePayroll->name : $item->overtimePayroll}}</td>
                                                       </tr>
                                                    </table>
                                                    <br />
                                                    <div class="clearfix"></div>   
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="PayrollAssign{{ $item->id }}">
                                                    <table class="table table-bordered">
                                                       <tr>
                                                           <td>UMR Setting</td>
                                                           <td>{{$item->payrollUMR ? $item->payrollUMR->label : $item->payrollUMR}}</td>
                                                       </tr>

                                                       <tr>
                                                           <td>Payroll Cycle</td>
                                                           <td>{{$item->payrollCycle ? $item->payrollCycle->label : $item->payrollCycle}}</td>
                                                       </tr>

                                                       <tr>
                                                           <td>Attendance Cycle</td>
                                                           <td>{{$item->attendanceCycle ? $item->attendanceCycle->label : $item->attendanceCycle}}</td>
                                                       </tr>

                                                       <tr>
                                                        <td>PTKP</td>
                                                        <td>{{$item->ptkp}}</td>
                                                    </tr>
                                                    </table>
                                                    <br />
                                                    <div class="clearfix"></div>   
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="cuti{{ $item->id }}">
                                                     <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th width="70" class="text-center">No</th>
                                                                <th>Quota</th>
                                                                <th>Leave Taken</th>
                                                                <th>Leave Balance</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>{{ $item->cuti_length_of_service}}</td>
                                                                <td>{{ $item->cuti_terpakai}}</td>
                                                                <td>{{ $item->cuti_sisa_cuti}}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <br />
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="RecruitmentAssign{{ $item->id }}">
                                                    <table class="table table-bordered">
                                                       <tr>
                                                           <td>Recruitment Entitlement</td>
                                                           <td>{{$item->recruitment_entitle == null ? $item->recruitment_entitle : ($item->recruitment_entitle ? 'Entitle Recruitment' : 'Not Entitle Recruitment')}}</td>
                                                       </tr>
                                                    </table>
                                                    <br />
                                                    <div class="clearfix"></div>   
                                                </div>
                                            </div>
                                            <br />
                                            <br />
                                          </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">NO</th>
                                    <th>ROW NUMBER IN EXCEL</th>
                                    <th>LOG MESSAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log as $no => $item)
                                <tr>
                                    <td><center>{{$no+1}}</center></td>
                                    <td>{{$item->row_number}}</td>
                                    <td>{{$item->message}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
@section('footer-script')
<!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> -->
<script type="text/javascript">
    function slide_toogle(el)
    {
        $(el).parent().parent().next().slideToggle();
    }
</script>
@endsection

@endsection
