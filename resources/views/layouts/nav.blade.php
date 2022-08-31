@if(Auth::user()->access_id == 1 && session('access')=='admin')
<ul class="nav" id="side-menu">
    <li class="user-pro">
        <a href="javascript:void(0)" class="waves-effect">
            @if(!empty(\Auth::user()->foto))
            <img src="{{ asset('storage/foto/'. \Auth::user()->foto) }}" alt="user-img" class="img-circle" />
            @else
            <img src="{{ asset('admin-css/images/user.png') }}" alt="user-img" class="img-circle" />
            @endif
            <span class="hide-menu"> {{ Auth::user()->name }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('administrator.dashboard') }}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_DASHBOARD_GREEN.png')}}')">Dashboard
        </a>
    </li>
    <li class="devider"></li>
    @if(checkModuleAdmin(3))
    <li>
        <a href="{{ route('administrator.karyawan.index') }}" class="{{ \Request::segment(2) == 'karyawan' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EMPLOYEE_GREEN.png')}}')">
            <span class="hide-menu">@lang('menu.employee')<span class="fa arrow"></span></span>
        </a>
    </li>
    @endif
    @if(checkModuleAdmin(4) || checkModuleAdmin(5) || checkModuleAdmin(6) || checkModuleAdmin(7) || checkModuleAdmin(8) || checkModuleAdmin(9) || checkModuleAdmin(13) || checkModuleAdmin(27) || checkModuleAdmin(29) || checkModuleAdmin(32) || checkModuleAdmin(33))
    @php($recruitment_request_count = getWaitingHRCount())
    @php($request_pay_slip_count = getWaitingPayslipCount())
    @php($loan_count = getLoanWaitingHRCount())
    @php($loan_payment_count = getLoanPaymentWaitingHRCount())
    @php($cash_advance_count =getCashAdvanceWaitingTransferCount())
    @php($payment_request_count =getPaymentRequestCount())
    <li class="mega-nav">
        <a href="#" style="position: relative;" class="{{ \Request::segment(2) == 'leaveCustom' || \Request::segment(2) == 'paymentRequestCustom' || \Request::segment(2) == 'timesheetCustom' || \Request::segment(2) == 'overtimeCustom'
        || \Request::segment(2) == 'trainingCustom' || \Request::segment(2) == 'medicalCustom' || \Request::segment(2) == 'exitCustom' || \Request::segment(2) == 'request-pay-slip' 
        || \Request::segment(2) == 'recruitment-request' || \Request::segment(2) == 'approval-cash-advance' || \Request::segment(2) == 'loan' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_WORKFLOW_GREEN.png')}}')">
            <span class="hide-menu">Workflow Monitoring<span class="fa arrow"></span></span>
            @if($recruitment_request_count > 0 || $request_pay_slip_count > 0 || $loan_count > 0)
            <div class="notify" style="position: absolute;top: 61px;right: 10px;"> <span class="heartbit"></span> <span class="point"></span> </div>
            @endif
        </a>

        <ul class="nav nav-second-level">
            @if(checkModuleAdmin(4))
            <li><a href="{{ route('administrator.leaveCustom.index') }}" class="{{ \Request::segment(2) == 'leaveCustom' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LEAVE_GREEN.png')}}')">
                <span class="hide-menu">@lang('menu.leave_or_permit')</span></a></li>
            @endif
            @if(checkModuleAdmin(6))
            <li><a href="{{ route('administrator.paymentRequestCustom.index') }}" class="{{ \Request::segment(2) == 'paymentRequestCustom' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYMENT_REQUEST_GREEN.png')}}')">
                <span class="hide-menu">@lang('menu.payment_request')</span>
                {{-- @if($payment_request_count > 0)
                <label class="btn btn-danger btn-xs" style="margin-left:4px; margin-bottom: 2px">{{$payment_request_count}}</label>
                @endif --}}
            </a></li>
            @endif
            @if(checkModuleAdmin(29))
            <li><a href="{{ route('administrator.timesheetCustom.index') }}" class="{{ \Request::segment(2) == 'timesheetCustom' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_GREEN.png')}}')">
                <span class="hide-menu">@lang('menu.timesheet') </span></a></li>
            @endif
            @if(checkModuleAdmin(7))
            <li><a href="{{ route('administrator.overtimeCustom.index') }}" class="{{ \Request::segment(2) == 'overtimeCustom' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_OVERTIME_GREEN.png')}}')">
                <span class="hide-menu">@lang('menu.overtime_sheet') </span></a></li>
            @endif
            @if(checkModuleAdmin(8))
            <li><a href="{{ route('administrator.trainingCustom.index') }}" class="{{ \Request::segment(2) == 'trainingCustom' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BUSINESS_TRIP_GREEN.png')}}')">
                <span class="hide-menu">Business Trip</span></a></li>
            @endif
            @if(checkModuleAdmin(5))
            <li><a href="{{ route('administrator.medicalCustom.index') }}" class="{{ \Request::segment(2) == 'medicalCustom' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MEDICAL_GREEN.png')}}')">
                <span class="hide-menu">Medical Reimbursement</span></a></li>
            @endif
            @if(checkModuleAdmin(9))
            <li><a href="{{ route('administrator.exitCustom.index') }}" class="{{ \Request::segment(2) == 'exitCustom' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_INTERVIEW_GREEN.png')}}')">
                <span class="hide-menu">Exit Interview & Clearance</span></a></li>
            @endif
            @if(checkModuleAdmin(13))
            <li><a href="{{ route('administrator.request-pay-slip.index') }}" class="{{ \Request::segment(2) == 'request-pay-slip' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYSLIP_GREEN.png')}}')">
                <span class="hide-menu">Request Pay Slip</span>
                    @if($request_pay_slip_count > 0)
                    <label class="btn btn-danger btn-xs" style="margin-left:4px; margin-bottom: 2px">{{$request_pay_slip_count}}</label>
                    @endif
                </a>
            </li>
            @endif
            @if(checkModuleAdmin(27))
            <li><a href="{{ route('administrator.recruitment-request.index') }}" class="{{ \Request::segment(2) == 'recruitment-request' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_GREEN.png')}}')">
                <span class="hide-menu">Recruitment Request</span>
                    @if($recruitment_request_count > 0)
                    <label class="btn btn-danger btn-xs" style="margin-left:4px; margin-bottom: 2px">{{$recruitment_request_count}}</label>
                    @endif
                </a>
            </li>
            @endif
            @if(checkModuleAdmin(32))
            <li><a href="{{ route('administrator.approval.cash-advance.index') }}" class="{{ \Request::segment(2) == 'approval-cash-advance' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_CASH_ADVANCE_GREEN.png')}}')">
                <span class="hide-menu">Cash Advance</span>
                {{-- @if($cash_advance_count > 0)
                    <label class="btn btn-danger btn-xs" style="margin-left:4px; margin-bottom: 2px">{{$cash_advance_count}}</label>
                @endif --}}
            </a></li>
            @endif
            @if(checkModuleAdmin(33))
            <li><a href="{{ route('administrator.loan.index') }}" class="{{ \Request::segment(2) == 'loan' ? 'active' : ''}}">
                <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_GREEN.png')}}')">
                <span class="hide-menu">Loan</span>
                    @if($loan_count > 0)
                    <label class="btn btn-danger btn-xs" style="margin-left:4px; margin-bottom: 2px">{{$loan_count}}</label>
                    @endif
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    @if(checkModuleAdmin(3))
    <li>
        <a href="{{ route('administrator.organization-structure-custom.index') }}" class="waves-effect {{ \Request::segment(2) == 'organization-structure-custom' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_STRUCTURE_ORGANIZATION_GREEN.png')}}')">
            <span class="hide-menu">@lang('menu.organization_structure')<span class="fa arrow"></span></span>
        </a>
    </li>
    <li>
        <a href="javascript:void(0)" class="{{\Request::segment(2) == 'news' || \Request::segment(2) == 'internal-memo' || \Request::segment(2) == 'product' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_NEWS_GREEN.png')}}')">
            <span class="hide-menu">News List / Memo<span class="fa arrow"></span></span>
        </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ route('administrator.news.index') }}" class="{{ \Request::segment(2) == 'news' ? 'active' : ''}}"><i class="mdi mdi-book-multiple fa-fw"></i><span class="hide-menu">News</span></a></li>
            <li><a href="{{ route('administrator.internal-memo.index') }}" class="{{ \Request::segment(2) == 'internal-memo' ? 'active' : ''}}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Internal Memo</span></a></li>
            <li><a href="{{ route('administrator.product.index') }}" class="{{ \Request::segment(2) == 'product' ? 'active' : ''}}"><i class="mdi mdi-file-document-box fa-fw"></i><span class="hide-menu">Product Information</span></a></li>
        </ul>
    </li>
    @endif
    @if(checkModuleAdmin(13))
    <li>
        <a href="javascript:void(0)" class="{{\Request::segment(2) == 'payroll' || \Request::segment(2) == 'payroll-monthly' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYROLL_GREEN.png')}}')">
            <span class="hide-menu">Payroll<span class="fa arrow"></span></span>
        </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ route('administrator.payroll.index') }}" class="{{ \Request::segment(2) == 'payroll' ? 'active' : ''}}"><img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYROLL_DEFAULT_GREEN.png')}}')"><span class="hide-menu">Payroll Default</span></a></li>
            <li><a href="{{ route('administrator.payroll-monthly.index') }}" class="{{ \Request::segment(2) == 'payroll-monthly' ? 'active' : ''}}"><img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYROLL_MONTHLY_GREEN.png')}}')"><span class="hide-menu">Payroll Monthly</span></a></li>
        </ul>
    </li>
    @endif
    @if(checkModuleAdmin(14))
    <li>
        <a href="javascript:void(0)" class="{{ \Request::segment(2) == 'asset' || \Request::segment(2) == 'asset-tracking' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_FACILITY_MANAGEMENT_GREEN.png')}}')">
            <span class="hide-menu">Facilities Management</span>
        </a>
        <ul class="nav nav-second-level">
            <li>
                <a href="{{ route('administrator.asset.index') }}" class="{{ \Request::segment(2) == 'asset' ? 'active' : ''}}"><i class="mdi mdi-home-modern fa-fw"></i><span class="hide-menu">Facilities</span></a>
            </li>
            {{-- <li>
                <a href="{{ route('administrator.asset-type.index') }}"><i class="mdi mdi-plus-network fa-fw"></i><span class="hide-menu">Facilities Type</span></a>
            </li> --}}
            <li>
                <a href="{{ route('administrator.asset-tracking.index') }}" class="{{ \Request::segment(2) == '.asset-tracking' ? 'active' : ''}}"><i class="mdi mdi-chemical-weapon fa-fw"></i><span class="hide-menu">Facilities Tracking</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if(checkModuleAdmin(15))
    <li>
        <a href="javascript:void(0)" class="{{ \Request::segment(2) == 'remote-attendance' || \Request::segment(1) == 'attendance' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_ATTENDANCE_GREEN.png')}}')">
            <span class="hide-menu">Attendance</span>  
        </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ route('administrator.remote-attendance.index') }}" class="{{ \Request::segment(2) == 'remote-attendance' ? 'active' : ''}}"><i class="mdi mdi-airplane fa-fw"></i><span class="hide-menu">Remote Attendance</span></a></li>
            <li><a href="{{ route('attendance.index') }}" class="{{ \Request::segment(2) == 'attendance' ? 'active' : ''}}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Attendance List</span></a></li>
            <li><a href="{{ route('attendance.list') }}" class="{{ \Request::segment(2) == 'attendance' ? 'active' : ''}}"><i class="mdi mdi-calendar-text fa-fw"></i><span class="hide-menu">Attendance Summary</span></a></li>
        </ul>
    </li>
    @endif
    @if(checkModuleAdmin(28))
    <li>
        <a href="{{ route('visit.index') }}" style="position: relative" class="{{ \Request::segment(2) == 'visit' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_VISIT_GREEN.png')}}')">
            <span class="hide-menu">Visit</span>
        </a>
    </li>
    @endif
    @if(checkModuleAdmin(29))
    <li>
        <a href="{{ route('timesheet.index') }}" class="{{ \Request::segment(2) == 'timesheet' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_GREEN.png')}}')">
            <span class="hide-menu">Timesheet</span>
        </a>
    </li>
    @endif
    @if(checkModuleAdmin(25))
    <li>
        <a href="javascript:void(0)" class="{{ \Request::segment(2) == 'kpi-item' || \Request::segment(2) == 'kpi-survey' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PERFORMANCE_GREEN.png')}}')">
            <span class="hide-menu">Performance Management<span class="fa arrow"></span></span>
        </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ route('administrator.kpi-item.index') }}" class="{{ \Request::segment(2) == 'kpi-item' ? 'active' : ''}}"><i class="mdi mdi-view-list fa-fw"></i><span class="hide-menu">KPI Items</span></a></li>
            <li><a href="{{ route('administrator.kpi-survey.index') }}" class="{{ \Request::segment(2) == 'kpi-survey' ? 'active' : ''}}"><i class="mdi mdi-account-star fa-fw"></i><span class="hide-menu">KPI Surveys</span></a></li>
        </ul>
    </li>
    @endif
    @if(checkModuleAdmin(26))
    <li>
        <a href="{{ url('administrator/career') }}" class="{{ \Request::segment(2) == 'career' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_CAREER_GREEN.png')}}')">
            <span class="hide-menu">Career</span>
        </a>
    </li>
    @endif

    @if(checkModuleAdmin(27))
    {{--<li>--}}
    {{--<a href="{{ route('administrator.recruitment-request.index') }}">--}}
    {{--<i class="mdi mdi-account-plus fa-fw"></i> <span class="hide-menu">Recruitment<span class="fa arrow"></span></span>--}}
    {{--</a>--}}
    {{--</li>--}}
    
    <li>
        <a href="{{ route('administrator.recruitment.index') }}" class="{{ \Request::segment(2) == 'recruitment' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_GREEN.png')}}')">
            <span class="hide-menu">Recruitment</span>
        </a>
    </li>
    @endif

    @if(checkModuleAdmin(31))
    <li>
        <a href="{{ route('administrator.bank-cv.index') }}" class="{{ \Request::segment(2) == 'bank-cv' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BANK_CV_GREEN.png')}}')">
            <span class="hide-menu">Bank CV</span>
        </a>
    </li>
    @endif

    @if(checkModuleAdmin(33))
    <li>
        <a href="{{ route('administrator.loan-payment.index') }}" class="{{ \Request::segment(2) == 'loan-payment' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_GREEN.png')}}')">
            <span class="hide-menu">Loan Payment</span>
            @if($loan_payment_count > 0)
            <label class="btn btn-danger btn-xs" style="margin-left:4px; margin-bottom: 2px">{{$loan_payment_count}}</label>
            @endif
        </a>
    </li>
    @endif

    <li class="mega-nav">
        <a href="{{ route('administrator.setting.index') }}" class="waves-effect {{ \Request::segment(2) == 'setting' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_SETTING_GREEN.png')}}')">
            <span class="hide-menu">@lang('menu.setting')</span>
        </a>
    </li>

    <!--As Karyawan
        @if(Auth::user()->project_id != 1)
            <li class="devider"></li>

          @if(checkModule(4) || checkModule(5) || checkModule(6) || checkModule(7) || checkModule(8) || checkModule(9) || checkModule(13))
        <li class="mega-nav">
            <a href="javascript:void(0)" class="waves-effect" id="menu_form">
                <i class="mdi mdi-account-multiple fa-fw"></i> <span class="hide-menu">Management Form<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level">
                     @if(checkModule(4))
                    <li>
                        <a href="{{ route('administrator.leave.index') }}"><i class="mdi mdi-calendar-multiple-check fa-fw"></i><span class="hide-menu">Leave / Permit</span></a>
                    </li>
                    @endif
                    @if(checkModule(6))
                    <li>
                        <a href="{{ route('administrator.payment-request-custom.index') }}"><i class="mdi mdi-cash-multiple fa-fw"></i><span class="hide-menu">Payment Request</span></a>
                    </li>
                    @endif
                    @if(checkModule(7))
                    <li>
                    <a href="{{ route('administrator.overtime-custom.index') }}"><i class="mdi mdi-clock-fast fa-fw"></i><span class="hide-menu">Overtime Sheet </span></a>
                    </li>
                    @endif
                    @if(checkModule(8))
                    <li>
                    <a href="{{ route('administrator.training-custom.index') }}"><i class="mdi mdi-taxi fa-fw"></i><span class="hide-menu">Business Trip</span></a>
                    </li>
                    @endif
                    @if(checkModule(5))
                    <li>
                    <a href="{{ route('administrator.medical-custom.index') }}"><i class="mdi mdi-stethoscope fa-fw"></i><span class="hide-menu">Medical Reimbursement</span></a>
                    </li>
                    @endif
                    @if(checkModule(9))
                    <li>
                        <a href="{{ route('administrator.exit-custom.index') }}"><i class="mdi mdi-account-remove fa-fw"></i><span class="hide-menu">Exit Interview & Clearance</span></a>
                    </li>
                    @endif
                    @if(checkModule(13))
                    <li class="mega-nav">
                        <a href="{{ route('administrator.request-pay-slip-karyawan.index') }}" class="waves-effect">
                            <i class="mdi mdi-library-books fa-fw"></i> <span class="hide-menu">Request Pay Slip</span>
                        </a>
                    </li>
                    @endif
            </ul>
        </li>
        @endif



        @php($leave_menu = count_leave_approval())
        @php($payment_menu = count_payment_request_approval())
        @php($overtime_menu = count_overtime_approval())
        @php($training_menu = count_training_approval())
        @php($medical_menu = count_medical_approval())
        @php($exit_menu = count_exit_approval())
        @php($clearance_menu = count_clearance_approval())
        @php($cash_advance_menu = count_cash_advance_approval())
        @if($leave_menu['all'] > 0 || $payment_menu['all'] > 0 || $overtime_menu['all'] > 0 || $training_menu['all'] > 0 || $medical_menu['all'] > 0 || $exit_menu['all'] > 0 || $clearance_menu['all'] > 0  || $cash_advance_menu['all'] > 0)

            @if(checkModule(4) || checkModule(5) || checkModule(6) || checkModule(7) || checkModule(8) || checkModule(9))
            <li style="position: relative;">
                    <a href="javascript:void(0)" class="waves-effect" id="menu_approval">
                        <i class="mdi mdi-account-check fa-fw"></i> <span class="hide-menu">Management Approval<span class="fa arrow"></span></span>
                    </a>
                    @if($leave_menu['waiting'] > 0 || $payment_menu['waiting'] > 0 || $overtime_menu['waiting'] > 0 || $training_menu['waiting'] > 0 || $medical_menu['waiting'] > 0 || $exit_menu['waiting'] > 0 || $clearance_menu['waiting'] > 0 || $cash_advance_menu['waiting'] > 0)
                        <div class="notify" style="position: absolute;top: 61px;right: 10px;"> <span class="heartbit"></span> <span class="point"></span> </div>
                    @endif

                <ul class="nav nav-second-level">
                    @if(checkModule(4))
                    <li>
                        <a href="{{ route('administrator.approval.leave-custom.index') }}"><i class="mdi mdi-calendar-check fa-fw"></i><span class="hide-menu">Leave/Permit</span>
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $leave_menu['waiting'] }}</label>
                        </a>
                    </li>
                    @endif
                    @if(checkModule(6))
                    <li>
                        <a href="{{ route('administrator.approval.payment-request-custom.index') }}"><i class="mdi mdi-cast fa-fw"></i><span class="hide-menu">Payment Request</span>
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $payment_menu['waiting'] }}</label>
                        </a>
                    </li>
                    @endif
                    @if(checkModule(7))
                    <li>
                        <a href="{{ route('administrator.approval.overtime-custom.index') }}"><i class="mdi mdi-checkbox-multiple-marked-circle-outline fa-fw"></i><span class="hide-menu">Overtime Sheet</span>
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $overtime_menu['waiting'] }}</label>
                        </a>
                    </li>
                    @endif
                    @if(checkModule(8))
                    <li>
                        <a href="{{ route('administrator.approval.training-custom.index') }}"><i class="mdi mdi-car-connected fa-fw"></i><span class="hide-menu">Business Trip</span>
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $training_menu['waiting'] }}</label>
                        </a>
                    </li>
                    @endif
                    @if(checkModule(5))
                    <li>
                        <a href="{{ route('administrator.approval.medical-custom.index') }}"><i class="mdi mdi-hospital-building fa-fw"></i><span class="hide-menu">Medical Reimbursement</span>
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $medical_menu['waiting'] }}</label>
                        </a>
                    </li>
                    @endif
                    @if(checkModule(9))
                    <li>
                        <a href="{{ route('administrator.approval.exit-custom.index') }}"><i class="mdi mdi-arrow-right-bold-circle-outline fa-fw"></i><span class="hide-menu">Exit Interview</span>
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $exit_menu['waiting'] }}</label>
                        </a>
                    </li>
                     <li>
                        <a href="{{ route('administrator.approval.clearance-custom.index') }}"><i class="mdi mdi-checkbox-multiple-marked-outline fa-fw"></i><span class="hide-menu">Exit Clearance</span>
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $clearance_menu['waiting'] }}</label>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
                @endif
            @endif
        @endif
            -->
</ul>
@elseif(Auth::user()->access_id == 3)
<ul class="nav" id="side-menu">
    <li class="user-pro">
        <a href="javascript:void(0)" class="waves-effect">
            @if(!empty(\Auth::user()->foto))
            <img src="{{ asset('storage/foto/'. \Auth::user()->foto) }}" alt="user-img" class="img-circle" />
            @else
            <img src="{{ asset('admin-css/images/user.png') }}" alt="user-img" class="img-circle" />
            @endif
            <span class="hide-menu"> {{ Auth::user()->name }}</span>
        </a>
    </li>
    <li><a href="{{ route('superadmin.dashboard') }}">
        <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_DASHBOARD_GREEN.png')}}')">
        Dashboard 
    </a></li>
    <li class="devider"></li>
    <li>
        <a href="{{ route('superadmin.admin.index') }}"  class="{{ \Request::segment(2) == 'admin' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EMPLOYEE_GREEN.png')}}')">
            <span class="hide-menu">Admin<span class="fa arrow"></span></span>
        </a>
    </li>
    <li class="mega-nav">
        <a href="#" style="position: relative;"  class="{{ \Request::segment(2) == 'setting' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_SETTING_GREEN.png')}}')">
            <span class="hide-menu">Setting<span class="fa arrow"></span></span>
        </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ route('superadmin.setting.general') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">General</span></a></li>
            <li><a href="{{ route('superadmin.setting.email') }}"><i class="mdi mdi-email fa-fw"></i><span class="hide-menu">Email</span></a></li>
            @if(Auth::user()->project_id == '1' || Auth::user()->project_id == Null)
            <li><a href="{{ route('superadmin.setting.backup') }}"><i class="mdi mdi-backup-restore fa-fw"></i><span class="hide-menu">Backup App & Database</span></a></li>
            @endif
        </ul>
    </li>
</ul>
@else
<ul class="nav" id="side-menu">
    <li class="user-pro">
        <a href="javascript:void(0)" class="waves-effect">
            @if(!empty(\Auth::user()->foto))
            <img src="{{ asset('storage/foto/'. \Auth::user()->foto) }}" alt="user-img" class="img-circle" />
            @else
            <img src="{{ asset('admin-css/images/user.png') }}" alt="user-img" class="img-circle" />
            @endif
            <span class="hide-menu"> {{ Auth::user()->name }}</span>
        </a>
    </li>
    <li> <a href="{{ route('karyawan.dashboard') }}" class="waves-effect">
        <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_DASHBOARD_GREEN.png')}}')">Home
    </a></li>
    <li class="devider"></li>
    @if(checkModule(4) || checkModule(5) || checkModule(6) || checkModule(7) || checkModule(8) || checkModule(9) || checkModule(13) || checkModule(25) || checkModule(27) || checkModule(29))
    <li class="mega-nav">
        <a href="javascript:void(0)" class="waves-effect {{ \Request::segment(2) == 'leave' || \Request::segment(2) == 'payment-request-custom' || \Request::segment(2) == 'timesheet' || \Request::segment(2) == 'overtime-custom'
        || \Request::segment(2) == 'training-custom' || \Request::segment(2) == 'medical-custom' || \Request::segment(2) == 'exit-custom' || \Request::segment(2) == 'request-pay-slip' 
        || \Request::segment(2) == 'performance-evaluation' || \Request::segment(2) == 'recruitment-request' || \Request::segment(2) == 'cash-advance' || \Request::segment(2) == 'facilities' || \Request::segment(2) == 'loan' 
        ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MANAGEMENT_FORM_BLACK.png')}}')">
            <span class="hide-menu">Management Form
                <span class="fa arrow"></span>
                @if(count_facilities_user()>0)
                    <div class="notify" style="position: absolute;top: 61px;right: 10px;"> <span class="heartbit"></span> <span class="point"></span> </div>
                @endif
            </span>
        </a>
        <ul class="nav nav-second-level">
            @if(checkModule(4))
            <li>
                <a href="{{ route('karyawan.leave.index') }}"  class="{{ \Request::segment(2) == 'leave' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LEAVE_GREEN.png')}}')">
                    <span class="hide-menu">Leave / Permit</span></a>
            </li>
            @endif
            @if(checkModule(6))
            <li>
                <a href="{{ route('karyawan.payment-request-custom.index') }}"  class="{{ \Request::segment(2) == 'payment-request-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYMENT_REQUEST_GREEN.png')}}')">
                    <span class="hide-menu">Payment Request</span></a>
            </li>
            @endif
            @if(checkModule(29))
            <li>
                <a href="{{ route('karyawan.timesheet.index') }}"  class="{{ \Request::segment(2) == 'timesheet' ? 'active' : ''}}"> 
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_GREEN.png')}}')">
                    <span class="hide-menu">Timesheet</span></a>
            </li>
            @endif
            @if(checkModule(7))
            @if(Auth::user()->overtime_entitle)
            <li>
                <a href="{{ route('karyawan.overtime-custom.index') }}"  class="{{ \Request::segment(2) == 'overtime-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_OVERTIME_GREEN.png')}}')">
                    <span class="hide-menu">Overtime Sheet </span></a>
            </li>
            @else
            <li>
                <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_OVERTIME_GREEN.png')}}')">
                    <span class="hide-menu">Overtime Sheet </span></a>
            </li>
            @endif
            @endif
            @if(checkModule(8))
            <li>
                <a href="{{ route('karyawan.training-custom.index') }}"  class="{{ \Request::segment(2) == 'training-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BUSINESS_TRIP_GREEN.png')}}')">
                    <span class="hide-menu">Business Trip</span></a>
                {{--@if(getTrainingWaitingTransferUserCount()>0)
                <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{getTrainingWaitingTransferUserCount()}}</label>
                @endif--}}
            </li>
            @endif
            @if(checkModule(5))
            <li>
                <a href="{{ route('karyawan.medical-custom.index') }}"  class="{{ \Request::segment(2) == 'medical-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MEDICAL_GREEN.png')}}')">
                    <span class="hide-menu">Medical Reimbursement</span></a>
            </li>
            @endif
            @if(checkModule(9))
            <li>
                <a href="{{ route('karyawan.exit-custom.index') }}"  class="{{ \Request::segment(2) == 'exit-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_INTERVIEW_GREEN.png')}}')">
                    <span class="hide-menu">Exit Interview & Clearance</span></a>
            </li>
            @endif
            @if(checkModule(13))
            <li class="mega-nav">
                <a href="{{ route('karyawan.request-pay-slip.index') }}" class="waves-effect {{ \Request::segment(2) == 'request-pay-slip' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYSLIP_GREEN.png')}}')">
                    <span class="hide-menu">Request Pay Slip</span>
                </a>
            </li>
            @endif
            @if(checkModule(25))
            <li>
                <a href="{{ route('karyawan.performance-evaluation.index') }}"  class="{{ \Request::segment(2) == 'performance-evaluation' ? 'active' : ''}}">
                    <i class="mdi mdi-star fa-fw"></i>
                    <span class="hide-menu">Performance Evaluation</span></a>
            </li>
            @endif
            @if(checkModule(27))
            @if(Auth::user()->recruitment_entitle)
            <li>
                <a href="{{ route('karyawan.recruitment-request.index') }}"  class="{{ \Request::segment(2) == 'recruitment-request' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_GREEN.png')}}')">
                    <span class="hide-menu">Recruitment Request</span></a>
            </li>
            @else
            <li>
                <a href="javascript:void(0)" class="disabled" onclick="alert('You do not have permission to access this menu')">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_GREEN.png')}}')">
                    <span class="hide-menu">Recruitment Request</span></a>
            </li>
            @endif
            <li>
                <a href="{{ route('karyawan.recruitment-application.index') }}"  class="{{ \Request::segment(2) == 'recruitment-application' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_GREEN.png')}}')">
                    <span class="hide-menu">Recruitment Application</span>
                </a>
            </li>
            @endif
            @if(checkModule(32))
            <li>
                <a href="{{ route('karyawan.cash-advance.index') }}"  class="{{ \Request::segment(2) == 'cash-advance' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_CASH_ADVANCE_GREEN.png')}}')">
                    <span class="hide-menu">Cash Advance</span>
                    {{--@if(getCashAdvanceWaitingTransferUserCount()>0)
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{getCashAdvanceWaitingTransferUserCount()}}</label>
                    @endif--}}
                </a>
            </li>
            @endif
            @if(checkModule(9))
            <li>
                <a href="{{ route('karyawan.facilities.index') }}"  class="{{ \Request::segment(2) == 'facilities' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_FACILITY_MANAGEMENT_GREEN.png')}}')">
                    <span class="hide-menu">Facilities</span>
                    @if(count_facilities_user()>0)
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{count_facilities_user()}}</label>
                    @endif
                </a>
            </li>
            @endif
            @if(checkModule(33))
            <li>
                <a href="{{ route('karyawan.loan.index') }}"  class="{{ \Request::segment(2) == 'loan' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_GREEN.png')}}')">
                    <span class="hide-menu">Loan</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    @if(checkModule(25) && checkManager())
    <li>
        <a href="javascript:void(0)"  class="{{ \Request::segment(2) == 'kpi-item' || \Request::segment(2) == 'kpi-survey' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PERFORMANCE_GREEN.png')}}')">
            <span class="hide-menu">Performance Management<span class="fa arrow"></span></span>
        </a>
        <ul class="nav nav-second-level">
            <li><a href="{{ route('karyawan.kpi-item.index') }}"  class="{{ \Request::segment(2) == 'kpi-item' ? 'active' : ''}}"><i class="mdi mdi-view-list fa-fw"></i><span class="hide-menu">KPI Items</span></a></li>
            <li><a href="{{ route('karyawan.kpi-survey.index') }}"  class="{{ \Request::segment(2) == 'kpi-survey' ? 'active' : ''}}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">KPI Surveys</span></a></li>
        </ul>
    </li>
    @endif
    @if(checkModule(33))
    <li>
        <a href="{{ route('karyawan.loan-payment.index') }}"  class="{{ \Request::segment(2) == 'loan-payment' ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_GREEN.png')}}')">
            <span class="hide-menu">Loan Payment</span>
        </a>
    </li>
    @endif
    @php($leave_menu = count_leave_approval())
    @php($payment_menu = count_payment_request_approval())
    @php($timesheet_menu = count_timesheet_approval())
    @php($overtime_menu = count_overtime_approval())
    @php($training_menu = count_training_approval())
    @php($medical_menu = count_medical_approval())
    @php($recruitment_menu = count_recruitment_approval())
    @php($exit_menu = count_exit_approval())
    @php($clearance_menu = count_clearance_approval())
    @php($cash_advance_menu = count_cash_advance_approval())
    @php($facilities_menu = count_facilities_approval())
    @php($loan_menu = count_loan_approval())
    @if($leave_menu['all'] > 0 || $payment_menu['all'] > 0 || $recruitment_menu['all'] > 0 || $timesheet_menu['all'] > 0 || $overtime_menu['all'] > 0 || $training_menu['all'] > 0 || $medical_menu['all'] > 0 || $exit_menu['all'] > 0 || $clearance_menu['all'] > 0 || $cash_advance_menu['all'] > 0 || $loan_menu['all'] > 0)

    @if(checkModule(4) || checkModule(5) || checkModule(6) || checkModule(7) || checkModule(8) || checkModule(9) || checkModule(27) || checkModule(29))
    <li class="mega-nav">
        <a href="javascript:void(0)" class="waves-effect {{ \Request::segment(2) == 'approval-leave-custom' || \Request::segment(2) == 'approval-payment-request-custom' || \Request::segment(2) == 'approval-timesheet-custom' || \Request::segment(2) == 'approval-overtime-custom'
        || \Request::segment(2) == 'approval-training-custom' || \Request::segment(2) == 'approval-medical-custom' || \Request::segment(2) == 'approval-exit-custom' 
        || \Request::segment(2) == 'approval-recruitment-request' || \Request::segment(2) == 'approval-cash-advance' || \Request::segment(2) == 'approval-facilities' || \Request::segment(2) == 'approval-loan' 
        ? 'active' : ''}}">
            <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MANAGEMENT_APPROVAL_GREEN.png')}}')">
            <span class="hide-menu">Management Approval
                <span class="fa arrow"></span>
                @if($leave_menu['waiting'] > 0 || $payment_menu['waiting'] > 0 || $timesheet_menu['waiting'] > 0 || $overtime_menu['waiting'] > 0 || $training_menu['waiting'] > 0 || $medical_menu['waiting'] > 0 || $exit_menu['waiting'] > 0 || $clearance_menu['waiting'] > 0 || $recruitment_menu['waiting'] > 0 || $cash_advance_menu['waiting'] > 0 || $loan_menu['waiting'] > 0)
                <div class="notify" style="position: absolute;top: 61px;right: 10px;"> <span class="heartbit"></span> <span class="point"></span> </div>
                @endif
            </span>
        </a>

        <ul class="nav nav-second-level">
            @if(checkModule(4))
            <li>
                <a href="{{ route('karyawan.approval.leave-custom.index') }}" class="{{ \Request::segment(2) == 'approval-leave-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LEAVE_GREEN.png')}}')">
                    <span class="hide-menu">Leave/Permit</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $leave_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(6))
            <li>
                <a href="{{ route('karyawan.approval.payment-request-custom.index') }}" class="{{ \Request::segment(2) == 'approval-payment-request-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_PAYMENT_REQUEST_GREEN.png')}}')">
                    <span class="hide-menu">Payment Request</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $payment_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(29))
            <li>
                <a href="{{ route('karyawan.approval.timesheet-custom.index') }}" class="{{ \Request::segment(2) == 'approval-timesheet-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_TIMESHEET_GREEN.png')}}')">
                    <span class="hide-menu">Timesheet</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $timesheet_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(7))
            <li>
                <a href="{{ route('karyawan.approval.overtime-custom.index') }}" class="{{ \Request::segment(2) == 'approval-overtime-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_OVERTIME_GREEN.png')}}')">
                    <span class="hide-menu">Overtime Sheet</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $overtime_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(8))
            <li>
                <a href="{{ route('karyawan.approval.training-custom.index') }}" class="{{ \Request::segment(2) == 'approval-training-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_BUSINESS_TRIP_GREEN.png')}}')">
                    <span class="hide-menu">Business Trip</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $training_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(5))
            <li>
                <a href="{{ route('karyawan.approval.medical-custom.index') }}" class="{{ \Request::segment(2) == 'approval-medical-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_MEDICAL_GREEN.png')}}')">
                    <span class="hide-menu">Medical Reimbursement</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $medical_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(27))
            <li>
                <a href="{{ route('karyawan.approval.recruitment-request.index') }}" class="{{ \Request::segment(2) == 'approval-recruitment-request' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_RECRUITMENT_GREEN.png')}}')">
                    <span class="hide-menu">Recruitment Request</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $recruitment_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(9))
            <li>
                <a href="{{ route('karyawan.approval.exit-custom.index') }}" class="{{ \Request::segment(2) == 'approval-exit-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_INTERVIEW_GREEN.png')}}')">
                    <span class="hide-menu">Exit Interview</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $exit_menu['waiting'] }}</label>
                </a>
            </li>
            <li>
                <a href="{{ route('karyawan.approval.clearance-custom.index') }}" class="{{ \Request::segment(2) == 'approval-clearance-custom' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_EXIT_CLEARANCE_GREEN.png')}}')">
                    <span class="hide-menu">Exit Clearance</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $clearance_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(32))
            <li>
                <a href="{{ route('karyawan.approval.cash-advance.index') }}" class="{{ \Request::segment(2) == 'approval-cash-advance' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_CASH_ADVANCE_GREEN.png')}}')">
                    <span class="hide-menu">Cash Advance</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $cash_advance_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(9))
            <li>
                <a href="{{ route('karyawan.approval.facilities.index') }}" class="{{ \Request::segment(2) == 'approval-facilities' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_FACILITY_MANAGEMENT_GREEN.png')}}')">
                    <span class="hide-menu">Facilities</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $facilities_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
            @if(checkModule(33))
            <li>
                <a href="{{ route('karyawan.approval-loan.index') }}" class="{{ \Request::segment(2) == 'approval-loan' ? 'active' : ''}}">
                    <img style="-webkit-mask-image: url('{{url('/admin-css/icon/ICON_LOAN_GREEN.png')}}')">
                    <span class="hide-menu">Loan</span>
                    <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ $loan_menu['waiting'] }}</label>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    @endif

</ul>
@endif
<style>
    @if(get_setting('menu_color') != "")
            #side-menu > li > a.active > img{
                background-color: {{ get_setting('menu_color')  }};
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }

            #side-menu > li > ul > li > a.active {
                color : {{ get_setting('menu_color')  }};
            }

            #side-menu > li > ul > li > a.active >img{
                background-color: {{ get_setting('menu_color')  }};
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }
            
        @else 
            #side-menu > li > a.active > img {
                background-color: #0E9A88;
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }

            #side-menu > li > ul > li > a.active {
                color : #0E9A88;
            }

            #side-menu > li > ul > li > a.active >img{
                background-color: #0E9A88;
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }
    @endif
    @if(get_setting('header_text_color') != "")
            #side-menu > li > a > img{
                background-color: {{ get_setting('header_text_color')  }};
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }

            #side-menu > li > ul > li > a {
                color : {{ get_setting('header_text_color')  }};
            }

            #side-menu > li > ul > li > a >img{
                background-color: {{ get_setting('header_text_color')  }};
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }
            
        @else 
            #side-menu > li > a > img {
                background-color: black;
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }

            #side-menu > li > ul > li > a {
                color : black;
            }

            #side-menu > li > ul > li > a >img{
                background-color: black;
                width:30px;
                height:30px;
                -webkit-mask-repeat : no-repeat;
                -webkit-mask-size : contain;
            }
    @endif
</style>
