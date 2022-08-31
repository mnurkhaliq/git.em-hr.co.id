@extends('layouts.administrator')

@section('title', 'Setting')

@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 pt-1 p-l-0 p-r-0">
                <div class="white-box">
                    <div>
                        <h5 class="box-title">Setting</h5>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.setting.general') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">General</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.cabang.index') }}"><i class="mdi mdi-office fa-fw"></i><span class="hide-menu">Branch</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.provinsi.index') }}"><i class="mdi mdi-google-maps fa-fw"></i><span class="hide-menu">@lang('setting.provinsi')</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.kabupaten.index') }}"><i class="mdi mdi-map-marker-radius fa-fw"></i><span class="hide-menu">@lang('setting.kabupaten')</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.kecamatan.index') }}"><i class="mdi mdi-map-marker fa-fw"></i><span class="hide-menu">@lang('setting.kecamatan')</span></a>
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(9))
                            <a href="{{ route('administrator.alasan-pengunduran-diri.index') }}"><i class="mdi mdi-playlist-remove fa-fw"></i><span class="hide-menu">Reason for Leaving</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-playlist-remove fa-fw"></i><span class="hide-menu">Reason for Leaving</span></a>
                            @endif

                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="col-md-2">
                            @if(checkModuleAdmin(4))
                            <a href="{{ route('administrator.cuti-bersama.index') }}"><i class="mdi mdi-calendar-text fa-fw"></i><span class="hide-menu">Collective Leave</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-calendar-text fa-fw"></i><span class="hide-menu">Collective Leave</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(4))
                            <a href="{{ route('administrator.libur-nasional.index') }}"><i class="mdi mdi-calendar-multiple fa-fw"></i><span class="hide-menu">Public Holiday</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-calendar-multiple fa-fw"></i><span class="hide-menu">Public Holiday</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(8))
                            <a href="{{ route('administrator.plafond-dinas.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BUSINESS_TRIP_BLACK.png')}}')">
                                <span class="hide-menu">Business Trip</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BUSINESS_TRIP_BLACK.png')}}')">
                                <span class="hide-menu">Business Trip</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.universitas.index') }}"><i class="mdi mdi-school fa-fw"></i><span class="hide-menu">University</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.program-studi.index') }}"><i class="mdi mdi-library-books fa-fw"></i><span class="hide-menu">Major</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.position.index') }}"><i class="mdi mdi-account-star fa-fw"></i><span class="hide-menu">Position</span></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="col-md-2">
                            <a href="{{ route('administrator.setting.email') }}"><i class="mdi mdi-email fa-fw"></i><span class="hide-menu">Email</span></a>
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(4))
                            <a href="{{ route('administrator.setting-master-cuti.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LEAVE_BLACK.png')}}')">
                                <span class="hide-menu">Leave</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LEAVE_BLACK.png')}}')"><span class="hide-menu">Leave</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.bank.index') }}"><i class="mdi mdi-bank fa-fw"></i><span class="hide-menu">Bank</span></a>
                        </div>
                        <div class="col-md-2">
                            @if(Auth::user()->project_id == '1' || Auth::user()->project_id == Null)
                            <a href="{{ route('administrator.setting.backup') }}"><i class="mdi mdi-backup-restore fa-fw"></i><span class="hide-menu">Backup App & Database</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-backup-restore fa-fw"></i><span class="hide-menu">Backup App & Database</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(13))
                            <a href="{{ route('administrator.payroll-setting.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYROLL_BLACK.png')}}')"><span class="hide-menu">Payroll</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYROLL_BLACK.png')}}')"><span class="hide-menu">Payroll</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.division.index') }}"><i class="mdi mdi-account-star-variant fa-fw"></i><span class="hide-menu">Division</span></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="col-md-2">
                            @if(checkModuleAdmin(5))
                            <a href="{{ route('administrator.medical-plafond.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MEDICAL_BLACK.png')}}')"><span class="hide-menu">Medical Plafond</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MEDICAL_BLACK.png')}}')"><span class="hide-menu">Medical Plafond</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(15))
                            <a href="{{ route('shift-setting.index') }}"><i class="mdi mdi-calendar-clock fa-fw"></i><span class="hide-menu">Shift</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-calendar-clock fa-fw"></i><span class="hide-menu">Attendance & Shift</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(25))
                            <a href="{{ route('administrator.setting-performance.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PERFORMANCE_BLACK.png')}}')"><span class="hide-menu">Performance Management</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PERFORMANCE_BLACK.png')}}')"><span class="hide-menu">Performance Management</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(28))
                            <a href="{{ route('administrator.setting-Visit.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_VISIT_BLACK.png')}}')"><span class="hide-menu">Visit</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_VISIT_BLACK.png')}}')"><span class="hide-menu">Visit</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(17))
                            <a href="{{ route('administrator.setting-mobile-attendance.index') }}"><i class="mdi mdi-cellphone fa-fw"></i><span class="hide-menu">Mobile Attendance</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-cellphone fa-fw"></i><span class="hide-menu">Mobile Attendance</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(26))
                            <a href="{{ route('administrator.grade.index') }}"><i class="mdi mdi-stairs fa-fw"></i><span class="hide-menu">Grade</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-stairs fa-fw"></i><span class="hide-menu">Grade</span></a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="col-md-2">
                            @if(checkModuleAdmin(29))
                            <a href="{{ route('administrator.setting-timesheet.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_BLACK.png')}}')"><span class="hide-menu">Timesheet</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_BLACK.png')}}')"><span class="hide-menu">Timesheet</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(7))
                            <a href="{{ route('administrator.setting-overtime-sheet.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_OVERTIME_BLACK.png')}}')"><span class="hide-menu">Overtime</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_OVERTIME_BLACK.png')}}')"><span class="hide-menu">Overtime</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(27))
                            <a href="{{ route('administrator.setting-recruitment.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_BLACK.png')}}')"><span class="hide-menu">Recruitment</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_BLACK.png')}}')"><span class="hide-menu">Recruitment</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(31))
                            <a href="{{ route('administrator.setting-bank-cv.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BANK_CV_BLACK.png')}}')"><span class="hide-menu">Bank CV</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BANK_CV_BLACK.png')}}')"><span class="hide-menu">Bank CV</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(3))
                            <a href="{{ route('administrator.setting.contract-email') }}"><i class="mdi mdi-account-alert fa-fw"></i><span class="hide-menu">End Contract Mail Scheduler</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-account-alert fa-fw"></i><span class="hide-menu">End Contract Mail Scheduler</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(14))
                            <a href="{{ route('administrator.asset-type.index') }}"><i class="mdi mdi-plus-network fa-fw"></i><span class="hide-menu">Facilities Type</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-plus-network fa-fw"></i><span class="hide-menu">Facilities Type</span></a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="col-md-2">
                            @if(checkModuleAdmin(33))
                            <a href="{{ route('administrator.loan-setting.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_BLACK.png')}}')"><span class="hide-menu">Loan</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_BLACK.png')}}')"><span class="hide-menu">Loan</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.project-setting.index') }}"><i class="mdi mdi-account-multiple fa-fw"></i><span class="hide-menu">Project</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.title.index') }}"><i class="mdi mdi-account-settings fa-fw"></i><span class="hide-menu">Title</span></a>
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(9))
                            <a href="{{ route('administrator.asset-setting.index') }}">
                               <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_FACILITY_MANAGEMENT_BLACK.png')}}')"><span class="hide-menu">Facilities</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                               <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_FACILITY_MANAGEMENT_BLACK.png')}}')"><span class="hide-menu">Facilities</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.birthday-wording.index') }}"><i class="mdi mdi-cake fa-fw"></i><span class="hide-menu">Birthday Wording</span></a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('administrator.payment-request-type.index') }}"><i class="mdi mdi-checkerboard fa-fw"></i><span class="hide-menu">Type Payment Request & Cash Advance</span></a>
                        </div>
                        <div class="clearfix"></div><br />
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 pt-1 p-l-0 p-r-0">
                <div class="white-box">
                    <div>
                        <h5 class="box-title">Setting Approval</h5>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(4))
                            <a href="{{ route('administrator.setting-approvalLeave.index') }}">
                               <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LEAVE_BLACK.png')}}')"><span class="hide-menu">Leave/Permit Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                               <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LEAVE_BLACK.png')}}')"><span class="hide-menu">Leave/Permit Approval</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(6))
                            <a href="{{ route('administrator.setting-approvalPaymentRequest.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYMENT_REQUEST_BLACK.png')}}')"><span class="hide-menu">Payment Request Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYMENT_REQUEST_BLACK.png')}}')"><span class="hide-menu">Payment Request Approval</span></a>
                            @endif

                        </div>
                        {{-- <div class="col-md-2">
                            @if(checkModuleAdmin(29))
                            <a href="{{ route('administrator.setting-approvalTimesheet.index') }}"><i class="mdi mdi-checkbox-multiple-marked-circle fa-fw"></i><span class="hide-menu">Timesheet Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')"><i class="mdi mdi-checkbox-multiple-marked-circle fa-fw"></i><span class="hide-menu">Timesheet Approval</span></a>
                            @endif
                            
                        </div> --}}
                        <div class="col-md-2">
                            @if(checkModuleAdmin(7))
                            <a href="{{ route('administrator.setting-approvalOvertime.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_BLACK.png')}}')"><span class="hide-menu">Overtime Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_BLACK.png')}}')"><span class="hide-menu">Overtime Approval</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(8))
                            <a href="{{ route('administrator.setting-approvalTraining.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BUSINESS_TRIP_BLACK.png')}}')"><span class="hide-menu">Business Trip Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BUSINESS_TRIP_BLACK.png')}}')"><span class="hide-menu">Business Trip Approval</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(32))
                            <a href="{{ route('administrator.setting-approvalCashAdvance.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_CASH_ADVANCE_BLACK.png')}}')"><span class="hide-menu">Cash Advance Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_CASH_ADVANCE_BLACK.png')}}')"><span class="hide-menu">Cash Advance Approval</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(6) || checkModuleAdmin(32) || checkModuleAdmin(8) || checkModuleAdmin(5))
                            <a href="{{ route('administrator.transfer-setting.index') }}">
                                <i class="mdi mdi-cash-100 fa-fw"></i><span class="hide-menu">PIC Transfer</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <i class="mdi mdi-cash-100 fa-fw"></i><span class="hide-menu">PIC Transfer</span></a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="col-md-2">
                            @if(checkModuleAdmin(5))
                            <a href="{{ route('administrator.setting-approvalMedical.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MEDICAL_BLACK.png')}}')"><span class="hide-menu">Medical Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MEDICAL_BLACK.png')}}')"><span class="hide-menu">Medical Approval</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(27))
                            <a href="{{ route('administrator.setting-approvalRecruitment.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_BLACK.png')}}')"><span class="hide-menu">Recruitment Request Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_BLACK.png')}}')"><span class="hide-menu">Recruitment Request Approval</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(9))
                            <a href="{{ route('administrator.setting-approvalExit.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_INTERVIEW_BLACK.png')}}')"><span class="hide-menu">Exit Interview Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_INTERVIEW_BLACK.png')}}')"><span class="hide-menu">Exit Interview Approval</span></a>
                            @endif

                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(9))
                            <a href="{{ route('administrator.setting-approvalClearance.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_CLEARANCE_BLACK.png')}}')"><span class="hide-menu">Return Asset & Exit Clearance Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_CLEARANCE_BLACK.png')}}')"><span class="hide-menu">Exit Clearance Approval</span></a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            @if(checkModuleAdmin(33))
                            <a href="{{ route('administrator.setting-approvalLoan.index') }}">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_BLACK.png')}}')"><span class="hide-menu">Loan Approval</span></a>
                            @else
                            <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_BLACK.png')}}')"><span class="hide-menu">Loan Approval</span></a>
                            @endif

                        </div>
                        <div class="clearfix"></div>
                        <hr />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
<style type="text/css">

    a > img{
        background-color: #337ab7;
        width:25px;
        height:25px;
        -webkit-mask-repeat : no-repeat;
        -webkit-mask-size : contain;
    }

    .box-title {
        margin-bottom: 25px !important;
        margin-left: 16px !important;
        font-size: 12px !important;
        color: black !important;
    }

    a.disabled {
        cursor: default !important;
    }
</style>
@endsection