<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Department;
use App\Models\Provinsi;
use App\Models\UserEducation;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Division;
use App\Models\Section;
use App\Models\Payroll;
use DB;
use App\Models\PayrollNet;
use App\Models\PayrollGross;
use App\Models\UserTemp;
use App\Models\Shift;
use App\Models\ShiftDetail;
use App\Models\Bank;
use App\Models\UserCuti;
use App\Models\UserEducationTemp;
use App\Models\UserFamilyTemp;
use App\Models\UserFamily;
use App\Models\UserCertification;
use App\Models\UserCertificationTemp;
use App\Models\UserContract;
use App\Models\EmporeOrganisasiDirektur;
use App\Models\EmporeOrganisasiManager;
use App\Models\EmporeOrganisasiStaff;
use App\Models\Cabang;
use App\Models\ImportLog;
use App\Models\UserInventarisMobil;
use App\Models\UserInventaris;
use App\Models\StructureOrganizationCustom;
use App\Models\RequestPaySlip;
use App\Models\RequestPaySlipItem;
use App\Models\Cuti;
use App\Models\OrganisasiTitle;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\AbsensiSetting;
use App\Models\AbsensiItem;
use App\Models\LiburNasional;
use App\Models\MasterCategoryVisit;
use App\Models\MasterVisitType;
use App\Models\UsersBranchVisit;
use App\Models\UsersBranchVisitTemp;
use App\Models\VisitList;
use App\Models\VisitPict;
use App\Models\OvertimePayroll;
use App\Models\PayrollUMR;
use App\Models\PayrollCycle;
use App\Models\CareerHistory;
use App\Models\ShiftScheduleChange;
use App\Models\ShiftScheduleChangeEmployee;
use App\Models\PayrollCountry;
use App\Models\Project;

use PHPExcel_Worksheet_Drawing;
use Psy\Exception\ErrorException;
use File;
use DataTables;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('module:3');
    }



    // public function getannualcutikouta($cuti_id, $join_date)
    // {
    //     //dd($join_date);
    //     //$join_date=request()->get('amount');
    //     $cuti = Cuti::where('id', $cuti_id)->first();
    //     $kuotacuti = $cuti->kuota;
    //     $jeniscuti = $cuti->jenis_cuti;

    //     if ($jeniscuti != "Annual Leave") {
    //         return $kuotacuti;
    //     }

    //     //$karyawan = \App\User::where('id', $user_id)->first();
    //     //$join_date=$karyawan->join_date;
    //     $cur_date = \Carbon\Carbon::now();
    //     $diffYears = $cur_date->diffInYears($join_date);
    //     $diffdays = $cur_date->diffInDays($join_date);
    //     $diffMonths = $cur_date->diffInMonths($join_date);
    //     $NextJanFromJoinDate = (\Carbon\Carbon::parse($join_date)->addyear(1))->startOfYear();
    //     $diffMonthstoNextJan = $NextJanFromJoinDate->diffInMonths($join_date);
    //     $diffYearNextJanToCur = $cur_date->diffInYears($NextJanFromJoinDate);
    //     $iscarryforward = $cuti->iscarryforward;
    //     $carryforwardleave = $cuti->carryforwardleave;
    //     $OneYearFromJoinDate = (\Carbon\Carbon::parse($join_date)->addyear());
    //     $One2YearFromJoinDate = (\Carbon\Carbon::parse($join_date)->addyear(2));
    //     $diffdaysjoindate = (int) $OneYearFromJoinDate->diffInDays($join_date);
    //     $diffdaysjoindate2y = (int) $One2YearFromJoinDate->diffInDays($join_date);
    //     $NextJanFrom1yJoinDate = (\Carbon\Carbon::parse($join_date)->addyear(2))->startOfYear();
    //     $diffMonthstoNextJanAfter1y = (int) $OneYearFromJoinDate->diffInMonths($NextJanFrom1yJoinDate);
    //     $diffYearNextJan1yToCur = (int) $cur_date->diffInYears($NextJanFrom1yJoinDate);
    //     $typecuti = $cuti->master_cuti_type_id;
    //     $cutoffmonth = $cuti->cutoffmonth;
    //     $customday = (int) strtok($cutoffmonth, '-');
    //     $custommonth = (int) str_replace('-', '', substr($cutoffmonth, -2));
    //     $customyear = (\Carbon\Carbon::parse($join_date))->format("Y"); //($join_date)->format("Y");
    //     $customdate = (\Carbon\Carbon::parse(date("Y-m-d", mktime(0, 0, 0, $custommonth, $customday, $customyear))));
    //     $customyearformonthly = $cur_date->format("Y");
    //     $customdateformonthly = (\Carbon\Carbon::parse(date("Y-m-d", mktime(0, 0, 0, $custommonth, $customday, $customyearformonthly))));

    //     if ($join_date > $customdate) {
    //         $firstcutoff = (\Carbon\Carbon::parse($customdate)->addyear());
    //     } else {
    //         $firstcutoff = $customdate;
    //     }

    //     $diffMonthstoNextCutoff = (int) $firstcutoff->diffInMonths($join_date);
    //     $diffYearFirstCutOffToCur = (int) $cur_date->diffInYears($firstcutoff);

    //     if ($cur_date <= $customdateformonthly) {
    //         $customdateformonthly = (\Carbon\Carbon::parse($customdateformonthly)->addyear(-1));
    //     } else {
    //         $customdateformonthly = $customdateformonthly;
    //     }

    //     if ($join_date) {
    //         if ($typecuti == 2) {
    //             if ($iscarryforward == true && $diffdays >= $diffdaysjoindate2y) {
    //                 return $kuotacuti = ($kuotacuti) + ($carryforwardleave);
    //             }
    //             if ($iscarryforward == true && $diffdays < $diffdaysjoindate2y && $diffdays >= ($diffdaysjoindate)) {
    //                 return $kuotacuti = ($kuotacuti);
    //             }
    //             if ($iscarryforward == false && $diffdays >= $diffdaysjoindate) {
    //                 return $kuotacuti = $kuotacuti;
    //             } else {
    //                 return 0;
    //             }
    //         } else if ($typecuti == 1) {
    //             if ($diffMonthstoNextJan < 12 && $diffYearNextJanToCur < 1 && $NextJanFromJoinDate <= $cur_date) {
    //                 return $kuotacuti = $diffMonthstoNextJan + 1;
    //             }
    //             if ($diffYearNextJanToCur >= 1 && $iscarryforward == false && $NextJanFromJoinDate <= $cur_date) {
    //                 return $kuotacuti = $kuotacuti;
    //             }
    //             if ($diffYearNextJanToCur <= 1 && $iscarryforward == true && $NextJanFromJoinDate <= $cur_date) {
    //                 if ($diffMonthstoNextJan + 1 >= $carryforwardleave) {
    //                     return $kuotacuti = $kuotacuti + $carryforwardleave;
    //                 } else {
    //                     return $kuotacuti = $kuotacuti + $diffMonthstoNextJan + 1;
    //                 }
    //             }
    //             if ($diffYearNextJanToCur > 1 && $iscarryforward == true && $NextJanFromJoinDate <= $cur_date) {
    //                 return $kuotacuti = $kuotacuti + $carryforwardleave;
    //             } else {
    //                 return 0;
    //             }
    //         } else if ($typecuti == 3) {
    //             if ($diffdays >= $diffdaysjoindate) {
    //                 if ($NextJanFrom1yJoinDate <= $cur_date && $diffYearNextJan1yToCur < 1 && $iscarryforward == true && $diffMonthstoNextJanAfter1y + 1 >= $carryforwardleave) {
    //                     return $kuotacuti = $kuotacuti + $carryforwardleave;
    //                 } else if ($NextJanFrom1yJoinDate <= $cur_date && $diffYearNextJan1yToCur < 1 && $iscarryforward == true && $diffMonthstoNextJanAfter1y + 1 < $carryforwardleave) {
    //                     return $kuotacuti = $carryforwardleave + $diffMonthstoNextJanAfter1y + 1;
    //                 } else if ($NextJanFrom1yJoinDate <= $cur_date && $diffYearNextJan1yToCur >= 1 && $iscarryforward == true) {
    //                     return $kuotacuti = $kuotacuti + $carryforwardleave;
    //                 } else if ($NextJanFrom1yJoinDate <= $cur_date && $diffYearNextJan1yToCur >= 1 && $iscarryforward == false) {
    //                     return $kuotacuti = $kuotacuti;
    //                 } else if ($NextJanFrom1yJoinDate <= $cur_date && $diffYearNextJan1yToCur < 1 && $iscarryforward == false) {
    //                     return $kuotacuti = $diffMonthstoNextJanAfter1y + 1;
    //                 } else {
    //                     return $kuotacuti;
    //                 }
    //             } else {
    //                 return 0;
    //             }
    //         } else if ($typecuti == 5) {
    //             if ($diffMonthstoNextCutoff < 12 && $diffYearFirstCutOffToCur < 1 && $firstcutoff <= $cur_date) {
    //                 return $kuotacuti = $diffMonthstoNextCutoff + 1;
    //             } else if ($diffYearFirstCutOffToCur >= 1 && $iscarryforward == false && $firstcutoff <= $cur_date) {
    //                 return $kuotacuti = $kuotacuti;
    //             } else if ($diffYearFirstCutOffToCur <= 1 && $iscarryforward == true && $firstcutoff <= $cur_date) {
    //                 if ($diffMonthstoNextCutoff + 1 >= $carryforwardleave) {
    //                     return $kuotacuti = $kuotacuti + $carryforwardleave;
    //                 } else {
    //                     return $kuotacuti = $kuotacuti + $diffMonthstoNextCutoff + 1;
    //                 }
    //             } else if ($diffYearFirstCutOffToCur > 1 && $iscarryforward == true && $firstcutoff <= $cur_date) {
    //                 return $kuotacuti = $kuotacuti + $carryforwardleave;
    //             } else {
    //                 return 0;
    //             }
    //         } else if ($typecuti == 4) {
    //             if ($join_date >= $customdateformonthly) {
    //                 return $kuotacuti = (((int) $cur_date->diffInMonths($join_date)) + 1) * $kuotacuti;
    //             } else {
    //                 return $kuotacuti = (((int) $cur_date->diffInMonths($customdateformonthly)) + 1) * $kuotacuti;
    //             }
    //         } else {
    //             return $kuotacuti;
    //         }
    //     }
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        synchronize_all_career();
        $params['structure'] = getStructureName();

        $user = \Auth::user();

        if (isset($_GET['layout_karyawan'])) {
            $auth = $user;
            if ($auth) {
                if ($auth->project_id != NULL) {
                    $setting = \App\Models\Setting::where('key', 'layout_karyawan')->where('project_id', $auth->project_id)->first();
                } else {
                    $setting = \App\Models\Setting::where('key', 'layout_karyawan')->first();
                }
            } else {
                $setting = \App\Models\Setting::where('key', 'layout_karyawan')->first();
            }

            if (!$setting) {
                info($auth);
                $setting = new \App\Models\Setting();
                $setting->key = 'layout_karyawan';
                if ($auth->project_id != NULL) {
                    $setting->project_id = $auth->project_id;
                }
            }

            $setting->value = $_GET['layout_karyawan'];
            $setting->save();
        }

        if (isset($_GET['employee_softdeletes'])) {
            $auth = $user;
            if ($auth) {
                if ($auth->project_id != NULL) {
                    $setting = \App\Models\Setting::where('key', 'employee_softdeletes')->where('project_id', $auth->project_id)->first();
                } else {
                    $setting = \App\Models\Setting::where('key', 'employee_softdeletes')->first();
                }
            } else {
                $setting = \App\Models\Setting::where('key', 'employee_softdeletes')->first();
            }

            if (!$setting) {
                info($auth);
                $setting = new \App\Models\Setting();
                $setting->key = 'employee_softdeletes';
                if ($auth->project_id != NULL) {
                    $setting->project_id = $auth->project_id;
                }
            }

            $setting->value = $_GET['employee_softdeletes'];
            $setting->save();
        }

        if ($user->project_id != NULL) {

            $data = User::select("users.*")->whereIn('access_id', ['1', '2'])->where('users.project_id', $user->project_id);
            $params['division'] = OrganisasiDivision::where('organisasi_division.project_id', $user->project_id)->select('organisasi_division.*')->orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::where('organisasi_position.project_id', $user->project_id)->select('organisasi_position.*')->orderBy('organisasi_position.name', 'asc')->get();
            $notDefinePos = User::whereIn('access_id', ['1', '2'])->whereNull('structure_organization_custom_id')->where('users.project_id', $user->project_id)->get();
        } else {
            $data = User::select("users.*")->whereIn('access_id', ['1', '2']);
            $params['division'] = OrganisasiDivision::orderBy('organisasi_division.name', 'asc')->get();
            $params['position'] = OrganisasiPosition::orderBy('organisasi_position.name', 'asc')->get();
            $notDefinePos = User::whereIn('access_id', ['1', '2'])->whereNull('structure_organization_custom_id')->get();
        }
        $params['project'] = Project::orderBy('name', 'asc')->get();

        $params['countPos'] = count($notDefinePos);
        if (isset($_GET["position"]) and $_GET["position"] == 1) {
            $data = $data->whereNull('structure_organization_custom_id');
        }
        if(count(request()->all())) {
            \Session::put('e-employee_status', request()->employee_status);
            \Session::put('e-position_id', request()->position_id);
            \Session::put('e-division_id', request()->division_id);
            \Session::put('e-name', request()->name);
            \Session::put('e-start_date_join', request()->start_date_join);
            \Session::put('e-end_date_join', request()->end_date_join);
            \Session::put('e-start_date_resign', request()->end_date_resign);
            \Session::put('e-end_date_resign', request()->end_date_resign);
            \Session::put('e-start_date_end_contract', request()->start_date_end_contract);
            \Session::put('e-end_date_end_contract', request()->end_date_end_contract);
            \Session::put('e-employee_resign', request()->employee_resign);
        }

        $employee_status    = \Session::get('e-employee_status');
        $position_id        = \Session::get('e-position_id');
        $division_id        = \Session::get('e-division_id');
        $name               = \Session::get('e-name');
        $start_date_join    = \Session::get('e-start_date_join');
        $end_date_join      = \Session::get('e-end_date_join');
        $start_date_resign  = \Session::get('e-start_date_resign');
        $end_date_resign    = \Session::get('e-end_date_resign');
        $start_date_end_contract      = \Session::get('e-start_date_end_contract');
        $end_date_end_contract  = \Session::get('e-end_date_end_contract');
        $employee_resign    = \Session::get('e-employee_resign');

        if (request()) {
            if (!empty($name)) {
                $data = $data->where(function ($table) use($name) {
                    $table->where('users.name', 'LIKE', '%' . $name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . $name . '%');
                });
            }

            if (!empty($employee_resign) || get_setting('employee_softdeletes')) {
                if ($employee_resign == 'Active' || get_setting('employee_softdeletes') == 1)
                    $data = $data->where(function($query) {
                        $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                    })->where(function($query) {
                        $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                    });
                else
                    $data = $data->where(function($query) {
                        $query->where(function($query) {
                            $query->whereNotNull('non_active_date')->where('non_active_date', '<=', \Carbon\Carbon::now());
                        })->orWhere(function($query) {
                            $query->whereNotNull('join_date')->where('join_date', '>', \Carbon\Carbon::now());
                        });
                    });
            }

            if (!empty($employee_status)) {
                $data = $data->where('users.organisasi_status', $employee_status);
            }

            if ((!empty($division_id)) and (empty($position_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id', $division_id);
            }
            if ((!empty($position_id)) and (empty($division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', $position_id);
            }
            if ((!empty($position_id)) and (!empty($division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', $position_id)->where('structure_organization_custom.organisasi_division_id', $division_id);
            }

            if (!empty($start_date_join)) {
                $data = $data->where('users.join_date', '>=', $start_date_join);
            }
            if (!empty($end_date_join)) {
                $data = $data->where('users.join_date', '<=', $end_date_join);
            }

            if (!empty($start_date_resign)) {
                $data = $data->where('users.resign_date', '>=', $start_date_resign);
            }
            if (!empty($end_date_resign)) {
                $data = $data->where('users.resign_date', '<=', $end_date_resign);
            }

            if (!empty($start_date_end_contract)) {
                $data = $data->where('users.end_date_contract', '>=', $start_date_end_contract);
            }
            if (!empty($end_date_end_contract)) {
                $data = $data->where('users.end_date_contract', '<=', $end_date_end_contract);
            }

            if (request()->action == 'download') {
                return $this->downloadExcel($data->get());
            }
            if (request()->action == 'download_leave') {
                return $this->downloadExcelLeave($data->get());
            }
            if (request()->action == 'download_contract') {
                return $this->downloadExcelContract($data->get());
            }
        }

        if(request()->reset == 1)
        {
            \Session::forget('e-employee_status');
            \Session::forget('e-position_id');
            \Session::forget('e-division_id');
            \Session::forget('e-name');
            \Session::forget('e-start_date_join');
            \Session::forget('e-end_date_join');
            \Session::forget('e-start_date_resign');
            \Session::forget('e-end_date_resign');
            \Session::forget('e-start_date_end_contract');
            \Session::forget('e-end_date_end_contract');
            \Session::forget('e-employee_resign');

            return redirect()->route('administrator.approval-cash-advance.index');
        }


        $params['data'] = $data->orderBy('users.id', 'DESC')->get();

        return view('administrator.karyawan.index')->with($params);
    }

    public function table()
    {
        $user = \Auth::user();

        if ($user->project_id != NULL) {
            $data = User::select("users.*")->whereIn('access_id', ['1', '2'])->where('users.project_id', $user->project_id);
        } else {
            $data = User::select("users.*")->whereIn('access_id', ['1', '2']);
        }

        if (isset($_GET["position"]) and $_GET["position"] == 1) {
            $data = $data->whereNull('structure_organization_custom_id');
        }

        if (request()) {
            if (!empty(request()->name)) {
                $data = $data->where(function ($table) {
                    $table->where('users.name', 'LIKE', '%' . request()->name . '%')
                        ->orWhere('users.nik', 'LIKE', '%' . request()->name . '%');
                });
            }

            if (!empty(request()->employee_resign)) {
                if (request()->employee_resign == 'Active')
                    $data = $data->where(function($query) {
                        $query->whereNull('non_active_date')->orWhere('non_active_date', '>', \Carbon\Carbon::now());
                    })->where(function($query) {
                        $query->whereNull('join_date')->orWhere('join_date', '<=', \Carbon\Carbon::now());
                    });
                else
                    $data = $data->where(function($query) {
                        $query->where(function($query) {
                            $query->whereNotNull('non_active_date')->where('non_active_date', '<=', \Carbon\Carbon::now());
                        })->orWhere(function($query) {
                            $query->whereNotNull('join_date')->where('join_date', '>', \Carbon\Carbon::now());
                        });
                    });
            }

            if (!empty(request()->employee_status)) {
                $data = $data->where('users.organisasi_status', request()->employee_status);
            }

            if ((!empty(request()->division_id)) and (empty(request()->position_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_division_id', request()->division_id);
            }
            if ((!empty(request()->position_id)) and (empty(request()->division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', request()->position_id);
            }
            if ((!empty(request()->position_id)) and (!empty(request()->division_id))) {
                $data = $data->join('structure_organization_custom', 'users.structure_organization_custom_id', '=', 'structure_organization_custom.id')->where('structure_organization_custom.organisasi_position_id', request()->position_id)->where('structure_organization_custom.organisasi_division_id', request()->division_id);
            }

            if (!empty(request()->start_date_join)) {
                $data = $data->where('users.join_date', '>=', request()->start_date_join);
            }
            if (!empty(request()->end_date_join)) {
                $data = $data->where('users.join_date', '<=', request()->end_date_join);
            }

            if (!empty(request()->start_date_resign)) {
                $data = $data->where('users.resign_date', '>=', request()->start_date_resign);
            }
            if (!empty(request()->end_date_resign)) {
                $data = $data->where('users.resign_date', '<=', request()->end_date_resign);
            }

            if (!empty(request()->start_date_end_contract)) {
                $data = $data->where('users.end_date_contract', '>=', request()->start_date_end_contract);
            }
            if (!empty(request()->end_date_end_contract)) {
                $data = $data->where('users.end_date_contract', '<=', request()->end_date_end_contract);
            }
        }

        $data = $data->with(['structure.position', 'cabang']);

        return DataTables::of($data)
            ->addColumn('column_nik', function ($item) {
                return '<a href="'.route('administrator.karyawan.edit', $item->id).'" data-toggle="tooltip" data-placement="bottom" title="'.$item->nik.'"><b>'.strtoupper($item->nik).'</b></a>';
            })
            ->addColumn('column_name', function ($item) {
                return '<a href="'.route('administrator.karyawan.edit', $item->id).'" data-toggle="tooltip" data-placement="bottom" title="'.$item->name.'"><b>'.strtoupper(Str::limit($item->name,20)).'</b></a>';
            })
            ->addColumn('column_position', function ($item) {
                return (isset($item->structure->position) ? $item->structure->position->name : '') . (isset($item->structure->division) ? ' - ' . $item->structure->division->name : '') . (isset($item->structure->title) ? ' - ' . $item->structure->title->name : '');
            })
            ->addColumn('column_cabang', function ($item) {
                $cabang = null;
                if (!empty($item->cabang_id))
                    $cabang = getNamaCabang($item->cabang_id);
                return $cabang ? $cabang->name : '';
            })
            ->addColumn('column_status', function ($item) {
                $html = $item->organisasi_status." ";
                if($item->organisasi_status && $item->organisasi_status != 'Permanent' && $item->end_date_contract != null) {
                    $endDate = \Carbon\Carbon::parse($item->end_date_contract);
                    $now = \Carbon\Carbon::now()->startOfDay();
                    $diff = $endDate->diffInDays($now);
                    if($now >= $endDate)
                        $html .= '<span class="btn btn-danger btn-xs mdi mdi-calendar-remove" data-toggle="tooltip" data-placement="bottom" title="Contract has expired"></span>';
                    else if($diff <= 30)
                        $html .= '<span class="btn btn-warning btn-xs mdi mdi-bell" data-toggle="tooltip" data-placement="bottom" title="Contract will be expired within '.$diff.' days"></span>';
                }
                return $html;
            })
            ->addColumn('column_join', function ($item) {
                $html = '';
                if($item->join_date)
                    $html .= \Carbon\Carbon::parse($item->join_date)->format('Y-m-d');
                return $html;
            })
            ->addColumn('column_resign', function ($item) {
                $html = '<label style="width: 80px; font-weight: 100;">'.$item->resign_date.'</label>';
                if(isset($item->resign_date) && \Carbon\Carbon::parse($item->resign_date)->isPast())
                    $html .= '<span class="badge badge-danger" style="text-align: center;">R</span>';
                return $html;
            })
            ->addColumn('column_action', function ($item) {
                $html = '<div class="btn-group m-r-10">
                        <button aria-expanded="false" data-toggle="dropdown" class="btn btn-xs btn-default dropdown-toggle waves-effect waves-light" type="button">Action 
                            <span class="caret"></span>
                        </button>
                        <ul role="menu" class="dropdown-menu">
                            <li>
                                <a href="'.route('administrator.karyawan.edit', $item->id).'"><i class="fa fa-search-plus"></i> Detail</a>
                            </li>';
                            if(isset($item->non_active_date) && \Carbon\Carbon::parse($item->non_active_date)->isPast() && !$item->is_rejoined)
                                $html .= '<li>
                                        <a href="'.route('administrator.karyawan.rejoin', $item->id).'"><i class="fa fa-refresh"></i> Rejoin</a>
                                    </li>';
                            $html .= '<li>
                                <a href="'.route('administrator.karyawan.print-profile', $item->id).'" target="_blank"><i class="fa fa-print"></i> Print</a>
                            </li>';
                            if(!empty($item->generate_kontrak_file))
                                $html .= '<li>
                                        <a href="'.asset('/storage/file-kontrak/'.$item->id.'/'.$item->generate_kontrak_file).'" target="_blank"><i class="fa fa-search-plus"></i> View Contract File</a> 
                                    </li>';
                            $html .= '<li>
                                <a onclick="confirm_loginas(\''.$item->name.'\',\''.route('administrator.karyawan.autologin', $item->id).'\')"><i class="fa fa-key"></i> Autologin</a>
                            </li>
                        </ul>
                    </div>';
                return $html;
            })
            ->rawColumns(['column_nik', 'column_name', 'column_position', 'column_cabang', 'column_status', 'column_join', 'column_resign', 'column_action'])
            ->make(true);
    }

    /**
     * [printPayslip description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function printPayslip($id)
    {
        $params['data'] = Payroll::where('user_id', $id)->first();

        $view =  view('administrator.karyawan.print-payslip')->with($params);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream();
    }

    public function printPayslipNet($id)
    {
        $params['data'] = PayrollNet::where('user_id', $id)->first();

        $view =  view('administrator.karyawan.print-payslipnet')->with($params);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream();
    }

    public function printPayslipGross($id)
    {
        $params['data'] = PayrollGross::where('user_id', $id)->first();

        $view =  view('administrator.karyawan.print-payslipgross')->with($params);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream();
    }

    /**
     * [uploadDokumentFile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function uploadDokumentFile(Request $request)
    {
        $data   = User::where('id', $request->user_id)->first();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/file-kontrak/' . $data->id);
            $file->move($destinationPath, $fileName);

            $data->generate_kontrak_file = $fileName;
        }

        $data->save();

        return redirect()->route('administrator.karyawan.index')->with('message-success', 'Document uploaded successfully');
    }

    /**
     * [gengerateFileKontrak description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function generateDocumentFile(Request $request)
    {
        // Update profile users
        $user               = User::where('id', $request->user_id)->first();
        $user->join_date    = $request->join_date;
        $user->end_date     = $request->end_date;
        $user->organisasi_status = $request->organisasi_status;
        $user->save();

        $params['data'] = User::where('id', $request->user_id)->first();

        if (!$request->organisasi_status || $request->organisasi_status == 'Permanent')
            $view = view('administrator.karyawan.dokumen-permanent')->with($params);
        else
            $view = view('administrator.karyawan.dokumen-kontrak')->with($params);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream();
    }

    /**
     * [printProfile description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function printProfile($id)
    {
        $params['data'] = User::where('id', $id)->first();

        $view = view('administrator.karyawan.print')->with($params);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream();
    }

    /**
     * [importAll description]
     * @return [type] [description]
     */
    public function importAll()
    {
        ini_set('xdebug.max_nesting_level', 10000);
        $temp = UserTemp::all();
        $impLog = ImportLog::all();
        $errorcheck = ImportLog::where('message', 'like', '%Error%')->get();
        if (count($temp) == 0 || count($errorcheck) != 0) {
            return redirect()->route('administrator.karyawan.index')->with('message-error', 'No data to be imported.');
        } 
        else if (count($temp) < count($impLog)) {
            return redirect()->route('administrator.karyawan.index')->with('message-error', 'Some data from your excel failed to be imported.');
        }

        $userLogin = \Auth::user();

        if ($userLogin->project_id != NULL) {
            // $countUpdate = 0;
            // $countNew = 0;
            // foreach ($temp as $item) {
            //     $cekuserTemp = User::where('nik', $item->nik)->first();
            //     if ($cekuserTemp) {
            //         $countUpdate = $countUpdate + 1;
            //     } else {
            //         $countNew = $countNew + 1;
            //     }
            // }

            $countNew = UserTemp::doesnthave('user')->count();

            $module = \App\Models\CrmModule::where('project_id', $userLogin->project_id)->where('crm_product_id', 3)->first();
            $User = total_karyawan();
            if ($module->limit_user != NULL || $module->limit_user > 0) {
                if ($countNew > (($module->limit_user) - $User)) {
                    UserTemp::truncate();
                    UserEducationTemp::truncate();
                    UserFamilyTemp::truncate();
                    UserCertificationTemp::truncate();
                    UsersBranchVisitTemp::truncate();
                    ImportLog::truncate();

                    return redirect()->route('administrator.karyawan.index')->with('message-error', 'You can not import user anymore. You have reached the limit!');
                }
            }
        }

        $userArray = [];
        $userCareerArray = [];
        $userCutiArray = [];
        $userEducationArray = [];
        $userFamilyArray = [];
        $userCertificationArray = [];
        $userBranchVisitArray = [];

        $tempShift = [];

        foreach ($temp as $no => $item) {
            info($no);
            $cekuser = User::where('nik', $item->nik)->first();

            if ($cekuser) {
                $user  = $cekuser;
                $tempShift[$no] = $user->shift_id != $item->shift_id;
                if ($user->cabang_id != $item->branch)
                    $user->shift_id = null;
            } else {
                $user               = new User();
                $user->nik          = $item->nik;
                // $user->password         = bcrypt('12345'); // ganti di bawah setelah convert array
                $user->access_id        = 2;
                $tempShift[$no] = true;
            }

            $user->name         = empty($item->name) ? $user->name : $item->name;
            $user->join_date    = empty($item->join_date) ? $user->join_date : $item->join_date;
            $user->jenis_kelamin = empty($item->gender) ? $user->jenis_kelamin : $item->gender;
            $user->employee_number = empty($item->employee_number) ? $user->employee_number : $item->employee_number;
            $user->absensi_number = empty($item->absensi_number) ? $user->absensi_number : $item->absensi_number;
            $user->marital_status   = empty($item->marital_status) ? $user->marital_status : $item->marital_status;
            $user->agama        = empty($item->agama) ? $user->agama : $item->agama;
            $user->bpjs_number = empty($item->bpjs_number) ? $user->bpjs_number : $item->bpjs_number;
            $user->jamsostek_number = empty($item->jamsostek_number) ? $user->jamsostek_number : $item->jamsostek_number;
            $user->tempat_lahir     = empty($item->place_of_birth) ? $user->tempat_lahir : $item->place_of_birth;
            $user->tanggal_lahir    = empty($item->date_of_birth) ? $user->tanggal_lahir : $item->date_of_birth;
            $user->id_address       = empty($item->id_address) ? $user->id_address : $item->id_address;
            $user->current_address  = empty($item->current_address) ? $user->current_address : $item->current_address;
            $user->telepon          = empty($item->telp) ? $user->telepon : $item->telp;
            $user->mobile_1         = empty($item->mobile_1) ? $user->mobile_1 : $item->mobile_1;
            $user->mobile_2         = empty($item->mobile_2) ? $user->mobile_2 : $item->mobile_2;
            $user->emergency_name = empty($item->emergency_name) ? $user->emergency_name : $item->emergency_name;
            $user->emergency_relationship = empty($item->emergency_relationship) ? $user->emergency_relationship : $item->emergency_relationship;
            $user->emergency_contact = empty($item->emergency_contact) ? $user->emergency_contact : $item->emergency_contact;
            #$user->status           = 1;
            $user->blood_type       = empty($item->blood_type) ? $user->blood_type : $item->blood_type;
            $user->ktp_number       = empty($item->ktp_number) ? $user->ktp_number : $item->ktp_number;
            $user->passport_number  = empty($item->passport_number) ? $user->passport_number : $item->passport_number;
            $user->kk_number        = empty($item->kk_number) ? $user->kk_number : $item->kk_number;
            $user->npwp_number      = empty($item->npwp_number) ? $user->npwp_number : $item->npwp_number;
            $user->ext              = empty($item->ext) ? $user->ext : $item->ext;
            $user->master_visit_type_id      = empty($item->master_visit_type_id) ? $user->master_visit_type_id : $item->master_visit_type_id;
            $user->master_category_visit_id  = empty($item->master_category_visit_id) ? $user->master_category_visit_id : $item->master_category_visit_id;
            $user->overtime_entitle          = $item->overtime_entitle == null ? $user->overtime_entitle : ($item->overtime_entitle ?: null);
            $user->overtime_payroll_id       = empty($item->overtime_payroll_id) ? $user->overtime_payroll_id : $item->overtime_payroll_id;
            $user->payroll_umr_id            = empty($item->payroll_umr_id) ? $user->payroll_umr_id : $item->payroll_umr_id;
            $user->payroll_cycle_id          = empty($item->payroll_cycle_id) ? $user->payroll_cycle_id : $item->payroll_cycle_id;
            $user->attendance_cycle_id       = empty($item->attendance_cycle_id) ? $user->attendance_cycle_id : $item->attendance_cycle_id;
            $user->recruitment_entitle       = $item->recruitment_entitle == null ? $user->recruitment_entitle : ($item->recruitment_entitle ?: null);
            $user->shift_id = empty($item->shift_id) ? $user->shift_id : $item->shift_id;
            if ($item->email != "-") $user->email            = $item->email;

            if (!$cekuser) {
                $user->payroll_jenis_kelamin = empty($item->ptkp) ? $item->gender : ($item->ptkp != 'TK-0' ? 'Male' : $item->gender);
                $user->payroll_marital_status = empty($item->ptkp) ? $item->marital_status : ($item->ptkp == 'TK-0' ? ($item->gender == 'Male' ? 'Bujangan/Wanita' : $item->marital_status) : ($item->ptkp == 'K-0' ? 'Menikah' : ($item->ptkp == 'K-1' ? 'Menikah Anak 1' : ($item->ptkp == 'K-2' ? 'Menikah Anak 2' : 'Menikah Anak 3'))));
            }

            // find bank
            $bank  = Bank::where('name', 'LIKE', '%' . $item->bank_1 . '%')->first();
            if ($bank) $user->bank_id = $bank->id;
            $user->nama_rekening        = $item->bank_account_name_1;
            $user->nomor_rekening       = $item->bank_account_number;

            $user->foreigners_status    = empty($item->foreigners_status) ? $user->foreigners_status : $item->foreigners_status;
            $user->payroll_country_id   = empty($item->payroll_country_id) ? $user->payroll_country_id : $item->payroll_country_id;
            $user->custom_project_id    = empty($item->custom_project_id) ? $user->custom_project_id : $item->custom_project_id;

            if ((!$user->non_active_date || \Carbon\Carbon::now() < $user->non_active_date) && !$user->is_exit) {
                $user->structure_organization_custom_id = empty($item->structure_id) ? $user->structure_organization_custom_id : $item->structure_id;
                $user->cabang_id = empty($item->branch) ? $user->cabang_id : $item->branch;
                $user->organisasi_status    = empty($item->organisasi_status) ? $user->organisasi_status : $item->organisasi_status;
                if ($user->organisasi_status && $user->organisasi_status != 'Permanent') {
                    $user->status_contract      = empty($item->status_contract) ? $user->status_contract : $item->status_contract;
                    $user->start_date_contract  = empty($item->start_date_contract) ? $user->start_date_contract : $item->start_date_contract;
                    $user->end_date_contract    = empty($item->end_date_contract) ? $user->end_date_contract : $item->end_date_contract;
                    if ($user->end_date_contract) {
                        $user->non_active_date  = $user->end_date_contract;
                        $user->inactive_date    = $user->end_date_contract;
                    }
                    $user->status               = null;
                    $user->resign_date          = null;
                } else {
                    $user->status_contract      = null;
                    $user->start_date_contract  = null;
                    $user->end_date_contract    = null;
                }
                if (!$user->resign_date && !$user->end_date_contract) {
                    $user->non_active_date      = null;
                    $user->inactive_date        = null;
                }
            }

            $projectId = \Auth::user()->project_id;
            if (!empty($projectId)) {
                $user->project_id = $projectId;
            }
            // $user->save();
            $user = $user->toArray();
            if (!$cekuser)
                $user['password'] = empty($item->password) ? bcrypt('Temp1234$') : bcrypt($item->password); // set default password
            array_push($userArray, $user);
        }

        \Batch::update(new User, array_filter($userArray, function ($value) {
            return isset($value['id']);
        }), 'id');
        User::insert(array_filter($userArray, function ($value) {
            return !isset($value['id']);
        }));

        foreach ($temp as $no => $item) {
            $user = (object) $userArray[$no];
            if (!isset($user->id)) {
                $user->id = User::select('id')->where('nik', $item->nik)->first()->id;
            } else {
                cleaning_future_career($user);
            }

            if ($tempShift[$no]) {
                $this->updateSchedule($user->id, $user->shift_id);
            }

            //save career
            $career = CareerHistory::where('user_id', $user->id)
                ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                ->orderBy('effective_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
            if (checkModule(26) || $career) {
                if (!$career) {
                    $career = new CareerHistory();
                    $career->user_id = $user->id;
                    $career->effective_date = $user->join_date ?: \Carbon\Carbon::now()->format('Y-m-d');
                }
                $career->cabang_id = $user->cabang_id;
                $career->structure_organization_custom_id = $user->structure_organization_custom_id;
                $career->status = $user->organisasi_status ?: '';
                $career->start_date = $user->start_date_contract;
                $career->end_date = $user->end_date_contract;
                // $career->job_desc = $user->job_desc;
                // $career->sub_grade_id = $user->sub_grade_id;
                // $career->save();
                array_push($userCareerArray, $career->toArray());
            }

            //add user cuti sesuai master cuti
            if ($user->shift_id) {
                $masterCuti = Cuti::where('jenis_cuti', 'Annual Leave')->get();
                foreach ($masterCuti as $key => $value) {
                    # code...
                    $userCuti = UserCuti::where('user_id', $user->id)->where('cuti_id', $value->id)->first();
                    if (!$userCuti) {
                        $c = new UserCuti();
                        $c->user_id     = $user->id;
                        $c->cuti_id     = $value->id;
                        $c->kuota       = $item->cuti_length_of_service;
                        $c->sisa_cuti   = $item->cuti_sisa_cuti;
                        $c->cuti_terpakai = $item->cuti_terpakai;
                        // $c->save();
                        array_push($userCutiArray, $c->toArray());
                    }
                }
            }

            // EDUCATION
            $edu_temp = UserEducationTemp::where('user_temp_id', $item->id)->get();
            foreach ($edu_temp as $edu) {
                if ($edu->pendidikan == "") continue;

                // cek pendidikan
                $education = UserEducation::where('user_id', $user->id)->where('pendidikan', $edu->pendidikan)->first();

                if (empty($education)) {
                    $education                  = new UserEducation();
                    $education->user_id         = $user->id;
                }

                $education->pendidikan      = !empty($edu->pendidikan) ? $edu->pendidikan : $education->pendidikan;
                $education->tahun_awal      = !empty($edu->tahun_awal) ? $edu->tahun_awal : $education->tahun_awal;
                $education->tahun_akhir     = !empty($edu->tahun_akhir) ? $edu->tahun_akhir : $education->tahun_akhir;
                $education->fakultas        = !empty($edu->fakultas) ? $edu->fakultas : $education->fakultas;
                $education->jurusan         = !empty($edu->jurusan) ? $edu->jurusan : $education->jurusan;
                $education->nilai           = !empty($edu->nilai) ? $edu->nilai : $education->nilai;
                $education->kota            = !empty($edu->kota) ? $edu->kota : $education->kota;
                $education->certificate     = !empty($edu->certificate) ? $edu->certificate : $education->certificate;
                $education->note            = !empty($edu->note) ? $edu->note : $education->note;
                // $education->save();
                array_push($userEducationArray, $education->toArray());
            }

            

            // FAMILY
            $family_temp = UserFamilyTemp::where('user_temp_id', $item->id)->get();
            foreach ($family_temp as $fa) {
                if ($fa->nama == "") continue;

                $family     = UserFamily::where('user_id', $user->id)->where('hubungan', $fa->hubungan)->first();

                if (empty($family)) {
                    $family                 = new UserFamily();
                    $family->user_id        = $user->id;
                }

                $family->nama           = !empty($fa->nama) ? $fa->nama : $family->nama;
                $family->hubungan       = !empty($fa->hubungan) ? $fa->hubungan : $family->hubungan;
                $family->contact       = !empty($fa->contact) ? $fa->contact : $family->contact;
                $family->tempat_lahir   = !empty($fa->tempat_lahir) ? $fa->tempat_lahir : $family->tempat_lahir;
                $family->tanggal_lahir  = !empty($fa->tanggal_lahir) ? $fa->tanggal_lahir : $family->tanggal_lahir;
                $family->jenjang_pendidikan = !empty($fa->jenjang_pendidikan) ? $fa->jenjang_pendidikan : $family->jenjang_pendidikan;
                $family->pekerjaan      = !empty($fa->pekerjaan) ? $fa->pekerjaan : $family->pekerjaan;
                // $family->save();
                array_push($userFamilyArray, $family->toArray());
            }

            // CERTIFICATION
            $certification_temp = UserCertificationTemp::where('user_temp_id', $item->id)->get();
            foreach ($certification_temp as $cert) {
                if ($cert->name == "") continue;

                $certification = UserCertification::where('user_id', $user->id)->where('name', $cert->name)->first();

                if (empty($certification)) {
                    $certification                  = new UserCertification();
                    $certification->user_id         = $user->id;
                }

                $certification->name                = !empty($cert->name) ? $cert->name : $certification->name;
                $certification->date                = !empty($cert->date) ? $cert->date : $certification->date;
                $certification->organizer           = !empty($cert->organizer) ? $cert->organizer : $certification->organizer;
                $certification->certificate_number  = !empty($cert->certificate_number) ? $cert->certificate_number : $certification->certificate_number;
                $certification->score               = !empty($cert->score) ? $cert->score : $certification->score;
                $certification->description         = !empty($cert->description) ? $cert->description : $certification->description;
                // $certification->save();
                array_push($userCertificationArray, $certification->toArray());
            }

            // UserBranchVisit
           $UsersBranchVisit_Temp = UsersBranchVisitTemp::where('user_id_temp', $item->id)->get();
           foreach ($UsersBranchVisit_Temp as $ub) {
                if ($ub->cabang_id == "") continue;

                $UsersBranchVisit     = UsersBranchVisit::where('user_id', $user->id)->where('cabang_id', $ub->cabang_id)->first();

                if (empty($UsersBranchVisit)) {
                    $UsersBranchVisit                 = new UsersBranchVisit();
                    $UsersBranchVisit->user_id        = $user->id;
                    $UsersBranchVisit->cabang_id      =!empty((int)$ub->cabang_id) ? (int)$ub->cabang_id : (int)$UsersBranchVisit->cabang_id;
                    // $UsersBranchVisit->save();
                    array_push($userBranchVisitArray, $UsersBranchVisit->toArray());
                }
            }
       
        }

        \Batch::update(new CareerHistory, array_filter($userCareerArray, function ($value) {
            return isset($value['id']);
        }), 'id');
        \Batch::update(new UserCuti, array_filter($userCutiArray, function ($value) {
            return isset($value['id']);
        }), 'id');
        \Batch::update(new UserEducation, array_filter($userEducationArray, function ($value) {
            return isset($value['id']);
        }), 'id');
        \Batch::update(new UserFamily, array_filter($userFamilyArray, function ($value) {
            return isset($value['id']);
        }), 'id');
        \Batch::update(new UserCertification, array_filter($userCertificationArray, function ($value) {
            return isset($value['id']);
        }), 'id');
        \Batch::update(new UsersBranchVisit, array_filter($userBranchVisitArray, function ($value) {
            return isset($value['id']);
        }), 'id');

        CareerHistory::insert(array_filter($userCareerArray, function ($value) {
            return !isset($value['id']);
        }));
        UserCuti::insert(array_filter($userCutiArray, function ($value) {
            return !isset($value['id']);
        }));
        UserEducation::insert(array_filter($userEducationArray, function ($value) {
            return !isset($value['id']);
        }));
        UserFamily::insert(array_filter($userFamilyArray, function ($value) {
            return !isset($value['id']);
        }));
        UserCertification::insert(array_filter($userCertificationArray, function ($value) {
            return !isset($value['id']);
        }));
        UsersBranchVisit::insert(array_filter($userBranchVisitArray, function ($value) {
            return !isset($value['id']);
        }));

        // delete all table temp
        UserTemp::truncate();
        UserEducationTemp::truncate();
        UserFamilyTemp::truncate();
        UserCertificationTemp::truncate();
        UsersBranchVisitTemp::truncate();

        return redirect()->route('administrator.karyawan.index')->with('message-success', 'Employee data successfully imported');
    }

    /**
     * [import description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function importData(Request $request)
    {
        if ($request->hasFile('file')) {
            //$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            $branchsvisitcount=0;
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }

            // delete all table temp
            UserTemp::truncate();
            UserEducationTemp::truncate();
            UserFamilyTemp::truncate();
            UserCertificationTemp::truncate();
            UsersBranchVisitTemp::truncate();
            ImportLog::truncate();

            $userArray = [];
            $userEducationArray = [];
            $userFamilyArray = [];
            $userCertificationArray = [];
            $userBranchVisitArray = [];

            // $userShift = User::whereNotNull('shift_id')->pluck('nik')->toArray();
            $existing_user = array_column(User::select('nik', 'name')->get()->toArray(), 'name', 'nik');
            $existing_user_active = array_column(User::select('nik', 'non_active_date')->get()->toArray(), 'non_active_date', 'nik');
            $existing_user_resign = array_column(User::select('nik', 'is_exit')->get()->toArray(), 'is_exit', 'nik');
            
            foreach ($rows as $key => $item) {
                if (empty($item[2])) continue;

                if ($key >= 3) {
                    $user = new UserTemp();
                    $log = new ImportLog();
                    /**
                     * FIND USER
                     *
                     */
                    // $find_user = User::where('nik', $item[2])->first();
                    // if ($find_user) {
                    //     $user->user_id = $find_user->id;
                    // }

                    // $userTmp = UserTemp::where('nik', $item[2])->first();
                    $userTmp = array_search($item[2], array_column($userArray, 'nik'));
                    if ($userTmp) {
                        info("$item[2] USERTEMP ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Duplicate data entry.";
                        $log->save();
                        continue;
                    }

                    if (!empty($item[35])) {
                        if (!isset($existing_user_active[$item[2]]) ? true : (isset($existing_user_active[$item[2]])== true ? ($existing_user_active[$item[2]] > \Carbon\Carbon::now()) : false)) {
                            if (!isset($existing_user_resign[$item[2]])) {
                                if (count($code = explode("-", $item[35])) > 3) {
                                    info("$item[2] POSITION2 ERROR");
                                    $log->row_number = $key + 1;
                                    $log->message = "Error : Position is not valid.";
                                    $log->save();
                                    continue;
                                }
        
                                $posCode = OrganisasiPosition::where('code', trim($code[0]))->orWhere('name', trim($code[0]))->first();
                                if (!isset($code[1]))
                                    $divCode = null;
                                else
                                    $divCode = OrganisasiDivision::where('code', trim($code[1]))->orWhere('name', trim($code[1]))->first();
                                if (!isset($code[2]))
                                    $titCode = null;
                                else
                                    $titCode = OrganisasiTitle::where('code', trim($code[2]))->orWhere('name', trim($code[2]))->first();
                                
                                $checkPosition = null;
                                if (isset($posCode)) {
                                    $checkPosition = StructureOrganizationCustom::where('organisasi_position_id', $posCode->id);
                                    if (isset($divCode)) {
                                        $checkPosition = $checkPosition->where('organisasi_division_id', $divCode->id);
                                        if (isset($titCode)) {
                                            $checkPosition = $checkPosition->where('organisasi_title_id', $titCode->id);
                                        } else {
                                            $checkPosition = $checkPosition->whereNull('organisasi_title_id');
                                        }
                                    } else {
                                        $checkPosition = $checkPosition->whereNull('organisasi_division_id');
                                    }
                                    $checkPosition = $checkPosition->first();
                                }
        
                                if (isset($checkPosition)) {
                                    $user->structure_id = $checkPosition->id;
                                } else {
                                    info("$item[2] POSITION3 ERROR");
                                    $log->row_number = $key + 1;
                                    $log->message = "Error : Position not found in organization structure.";
                                    $log->save();
                                    continue;
                                }
                            } else {
                                info("$item[2] POSITION5 ERROR");
                                $log->row_number = $key + 1;
                                $log->message = "Error : Cant Change Position on Employee That Already Approved Exit Interview";
                                $log->save();
                                continue;
                            }
                        } else {
                            info("$item[2] POSITION4 ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : Cant Change Position on Non Active User";
                            $log->save();
                            continue;
                        }
                    } else if (array_key_exists($item[2], $existing_user)) {
                        $user->structure_id = null;
                    } else {
                        info("$item[2] POSITION ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Position cannot be blank.";
                        $log->save();
                        continue;
                    }
                    
                    if (!empty($item[34])) {
                        if (!isset($existing_user_active[$item[2]]) ? true : (isset($existing_user_active[$item[2]])== true ? ($existing_user_active[$item[2]] > \Carbon\Carbon::now()) : false)) {
                            if (!isset($existing_user_resign[$item[2]])) {
                                $branch = Cabang::where('name', $item[34])->first();
                                if (isset($branch)) {
                                    $user->branch = $branch->id;
                                } else {
                                    info("$item[2] BRANCH2 ERROR");
                                    $log->row_number = $key + 1;
                                    $log->message = "Error : Branch not found.";
                                    $log->save();
                                    continue;
                                }
                            } else {
                                info("$item[2] BRANCH4 ERROR");
                                $log->row_number = $key + 1;
                                $log->message = "Error : Cant Change Branch on Employee That Already Approved Exit Interview";
                                $log->save();
                                continue;
                            }
                        } else {
                            info("$item[2] BRANCH3 ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : Cant Change Branch on Non Active User";
                            $log->save();
                            continue;
                        }
                    } else if (array_key_exists($item[2], $existing_user)) {
                        $user->branch = null;
                    } else {
                        info("$item[2] BRANCH ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Branch cannot be blank.";
                        $log->save();
                        continue;
                    }

                    //shift
                    if (!empty($item[28])) {
                        $absensiSetting = Shift::where('name', 'LIKE', $item[28])->where('branch_id', $user->branch ?: User::where('nik', $item[2])->first()->cabang_id)->first();
                        if (isset($absensiSetting)) {
                            $user->shift_id = $absensiSetting->id;
                        } else {
                            $log->row_number = $key + 1;
                            $log->message = "Error : Shift not found in this branch.";
                            $log->save();
                            continue;
                        }
                    } else {
                        $user->shift_id = null;
                    }

                    $user->employee_number  = $item[0];
                    $user->absensi_number   = $item[1];
                    $user->nik              = $item[2];
                    $user->password         = $item[3];
                    $user->name             = strtoupper($item[4] ?: ($existing_user[$item[2]] ?: $item[4]));
                    if (!empty($item[5])) {
                        info("join date : $item[5]");
                        try {
                            $user->join_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[5])->format('Y-m-d');
                            // $user->join_date = $item[5];
                        } catch (\Exception $e) {
                            info("$item[2] JOIN DATE2 ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : Join Date is not valid.";
                            $log->save();
                            continue;
                        }
                    } else if (array_key_exists($item[2], $existing_user)) {
                        $user->join_date = null;
                    } else {
                        info("$item[2] JOIN DATE ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Join Date cannot be blank.";
                        $log->save();
                        continue;
                    }


                    if (!empty($item[6])) {
                        if ($item[6] == 'Male' || $item[6] == 'male' || $item[6] == 'Laki-laki' || $item[6] == 'laki-laki' || strtoupper($item[6]) == 'PRIA') {
                            $user->gender           = 'Male';
                        } else if ($item[6] == 'Female' || $item[6] == 'female' || $item[6] == 'Perempuan' || $item[6] == 'perempuan' || strtoupper($item[6]) == 'WANITA') {
                            $user->gender           = 'Female';
                        } else {
                            info("$item[2] GENDER2 ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : Gender is not valid.";
                            $log->save();
                            continue;
                        }
                    } else if (array_key_exists($item[2], $existing_user)) {
                        $user->gender = null;
                    } else {
                        info("$item[2] GENDER ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Gender cannot be blank.";
                        $log->save();
                        continue;
                    }

                    if (!empty($item[7])) {
                        $user->marital_status = $item[7];
                    } else if (array_key_exists($item[2], $existing_user)) {
                        $user->marital_status = null;
                    } else {
                        info("$item[2] MARITAL STATUS ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Marital Status cannot be blank.";
                        $log->save();
                        continue;
                    }

                    $agama = $item[8];

                    if (strtoupper($agama) == 'ISLAM') {
                        $agama = 'Muslim';
                    }
                    $user->agama            = $agama;
                    $user->ktp_number       = $item[9];
                    $user->passport_number  = $item[10];
                    $user->kk_number        = $item[11];
                    $user->npwp_number      = $item[12];
                    $user->bpjs_number      = $item[13];
                    $user->jamsostek_number = $item[14];
                    $user->place_of_birth   = strtoupper($item[15]);

                    if (!empty($item[16])) {
                        try {
                            $user->date_of_birth = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[16])->format('Y-m-d');
                            // $user->date_of_birth = $item[16];
                        } catch (\Exception $e) {
                            $log->row_number = $key + 1;
                            $log->message = "Error : Birth Date is not valid";
                            $log->save();
                            continue;
                        }
                    } else {
                        // $user->date_of_birth = null;
                        info("$item[2] BIRTH DATE ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Birth Date cannot be blank.";
                        $log->save();
                        continue;
                    }

                    $user->id_address       = strtoupper($item[17]);
                    $user->current_address  = strtoupper($item[18]);
                    $user->telp             = $item[19];
                    $user->ext              = $item[20];
                    $user->mobile_1         = $item[21];
                    $user->mobile_2         = $item[22];
                    $user->emergency_name   = $item[23];
                    $user->emergency_relationship = $item[24];
                    $user->emergency_contact = $item[25];
                    $user->email            = $item[26];
                    $user->blood_type       = $item[27];

                    $user->bank_1           = $item[37];
                    $user->bank_account_name_1 = $item[38];
                    $user->bank_account_number = $item[39];
                                        
                    if (!empty($item[29])) {
                        $payrollCountry = PayrollCountry::where('name', 'LIKE', $item[29])->first();
                        if (isset($payrollCountry)) {
                            $user->foreigners_status = 1;
                            $user->payroll_country_id = $payrollCountry->id;
                        } else {
                            $log->row_number = $key + 1;
                            $log->message = "Error : Country not found.";
                            $log->save();
                            continue;
                        }
                    } else {
                        $user->foreigners_status = null;
                        $user->payroll_country_id = null;
                    }

                    if (!empty($item[36])) {
                        $project = Project::where('name', $item[36])->first();
                        if (isset($project)) {
                            $user->custom_project_id = $project->id;
                        } else {
                            $log->row_number = $key + 1;
                            $log->message = "Error : Project not found.";
                            $log->save();
                            continue;
                        }
                    } else {
                        $user->custom_project_id = null;
                    }

                    if (!empty($item[30])) {
                        if (!isset($existing_user_active[$item[2]]) ? true : (isset($existing_user_active[$item[2]])== true ? ($existing_user_active[$item[2]] > \Carbon\Carbon::now()) : false)) {
                            if (!isset($existing_user_resign[$item[2]]))
                                $user->organisasi_status = $item[30];
                            else {
                                info("$item[2] EMPLOYEE STATUS 3 ERROR");
                                $log->row_number = $key + 1;
                                $log->message = "Error : Cant Change Employee Status That Already Approved Exit Interview";
                                $log->save();
                                continue;
                            }
                        } else {
                            info("$item[2] EMPLOYEE STATUS 2 ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : Cant Change Employee Status on Non Active User";
                            $log->save();
                            continue;
                        }
                    } else if (array_key_exists($item[2], $existing_user)) {
                        $user->organisasi_status = null;
                    } else {
                        info("$item[2] EMPLOYEE STATUS ERROR");
                        $log->row_number = $key + 1;
                        $log->message = "Error : Employee Status cannot be blank.";
                        $log->save();
                        continue;
                    }

                    if (!empty($item[31])) {
                        if (!isset($existing_user_active[$item[2]]) ? true : (isset($existing_user_active[$item[2]])== true ? ($existing_user_active[$item[2]] > \Carbon\Carbon::now()) : false)) {
                            if (!isset($existing_user_resign[$item[2]])) 
                                $user->status_contract = $item[31];
                            else {
                                info("$item[2] STATUS CONTRACT 2 ERROR");
                                $log->row_number = $key + 1;
                                $log->message = "Error : Cant Change Status Contract on Employee That Already Approved Exit Interview";
                                $log->save();
                                continue;
                            }
                        } else {
                            info("$item[2] STATUS CONTRACT ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : Cant Change Status Contract on Non Active User";
                            $log->save();
                            continue;
                        }
                    } else {
                        $user->status_contract = null;
                    }

                    if (!empty($item[32])) {
                        try {
                            if (!isset($existing_user_active[$item[2]]) ? true : (isset($existing_user_active[$item[2]])== true ? ($existing_user_active[$item[2]] > \Carbon\Carbon::now()) : false)) {
                                if (!isset($existing_user_resign[$item[2]])) 
                                    $user->start_date_contract = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[32])->format('Y-m-d');
                                else {
                                    info("$item[2] START CONTRACT 3 ERROR");
                                    $log->row_number = $key + 1;
                                    $log->message = "Error : Cant Change Start Contract Date on Employee That Already Approved Exit Interview";
                                    $log->save();
                                    continue;
                                }
                            } else {
                                info("$item[2] START CONTRACT 2 ERROR");
                                $log->row_number = $key + 1;
                                $log->message = "Error : Cant Change Start Contract Date on Non Active User";
                                $log->save();
                                continue;
                            }
                        } catch (\Exception $e) {
                            info("$item[2] START CONTRACT ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : Start Contract Date is not valid";
                            $log->save();
                            continue;
                        }
                    } else {
                        $user->start_date_contract = null;
                    }

                    if (!empty($item[33])) {
                        try {
                            if (!isset($existing_user_active[$item[2]]) ? true : (isset($existing_user_active[$item[2]])== true ? ($existing_user_active[$item[2]] > \Carbon\Carbon::now()) : false)) {
                                if (!isset($existing_user_resign[$item[2]])) 
                                    $user->end_date_contract = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[33])->format('Y-m-d');
                                else {
                                    info("$item[2] END CONTRACT 3 ERROR");
                                    $log->row_number = $key + 1;
                                    $log->message = "Error : Cant Change End Contract Date on Employee That Already Approved Exit Interview";
                                    $log->save();
                                    continue;
                                }
                            } else {
                                info("$item[2] END CONTRACT 2 ERROR");
                                $log->row_number = $key + 1;
                                $log->message = "Error : Cant Change End Contract Date on Non Active User";
                                $log->save();
                                continue;
                            }
                        } catch (\Exception $e) {
                            info("$item[2] END CONTRACT ERROR");
                            $log->row_number = $key + 1;
                            $log->message = "Error : End Contract Date is not valid";
                            $log->save();
                            continue;
                        }
                    } else {
                        $user->end_date_contract = null;
                    }

                    if (checkModuleAdmin(4)) {
                        // if (!in_array($user->nik, $userShift) && !$user->shift_id && (!empty($item[235]) || !empty($item[236]) || !empty($item[237]))) {
                        //     info("$item[2] ANNUAL LEAVE ERROR");
                        //     $log->row_number = $key + 1;
                        //     $log->message = "Error : No shift cant import annual leave.";
                        //     $log->save();
                        //     continue;
                        // }  else {
                            $user->cuti_length_of_service = $item[235];
                            $user->cuti_terpakai          = $item[236];
                            $user->cuti_sisa_cuti         = $item[237];
                        // }
                    }

                    if (checkModuleAdmin(28)) {
                        if (!empty($item[238])) {
                            $MasterVisitType = MasterVisitType::where('master_visit_type_name', '=', $item[238])->first();
                            if (isset($MasterVisitType)) {
                                $user->master_visit_type_id = $MasterVisitType->id;
                            } else {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Visit Type not found.";
                                $log->save();
                                continue;
                            }
                        } else {
                            $user->master_visit_type_id = null;
                        }
                        if (!empty($item[239])) {
                            $MasterCategoryVisit = MasterCategoryVisit::where('master_category_name', '=', $item[239])->first();
                            if (isset($MasterCategoryVisit)) {
                                $user->master_category_visit_id = $MasterCategoryVisit->id;
                            } else {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Visit Category not found.";
                                $log->save();
                                continue;
                            }
                        } else {
                            $user->master_category_visit_id = null;
                        }
                    }
                    
                    if (checkModuleAdmin(7)) {
                        if (!empty($item[241])) {
                            $user->overtime_entitle = $item[241] == 'Entitle Overtime' ? 1 : 0;
                        } else {
                            $user->overtime_entitle = null;
                        }
                        if (!empty($item[242])) {
                            $OvertimePayroll = OvertimePayroll::where('name', '=', $item[242])->first();
                            if (isset($OvertimePayroll)) {
                                $user->overtime_payroll_id = $OvertimePayroll->id;
                            } else {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Overtime Payment Setting not found.";
                                $log->save();
                                continue;
                            }
                        } else {
                            $user->overtime_payroll_id = null;
                        }
                    }

                    if (checkModuleAdmin(13)) {
                        if (!empty($item[243])) {
                            $PayrollUMR = PayrollUMR::where('label', '=', $item[243])->first();
                            if (isset($PayrollUMR)) {
                                $user->payroll_umr_id = $PayrollUMR->id;
                            } else {
                                $log->row_number = $key + 1;
                                $log->message = "Error : UMR Setting not found.";
                                $log->save();
                                continue;
                            }
                        } else {
                            $user->payroll_umr_id = null;
                        }

                        if (!array_key_exists($item[2], $existing_user)) {
                            if (!empty($item[244])) {
                                if (in_array($item[244], ['TK-0', 'K-0', 'K-1', 'K-2', 'K-3'])) {
                                    $user->ptkp = $item[244];
                                } else {
                                    $log->row_number = $key + 1;
                                    $log->message = "Error : PTKP not found.";
                                    $log->save();
                                    continue;
                                }
                            } else {
                                $user->ptkp = null;
                            }
                        } else {
                            $user->ptkp = null;
                        }

                        if (!empty($item[245])) {
                            $PayrollCycle = PayrollCycle::where('key_name', 'payroll_custom')->where('label', '=', $item[245])->first();
                            if (isset($PayrollCycle)) {
                                $user->payroll_cycle_id = $PayrollCycle->id;
                            } else {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Payroll Cycle Setting not found.";
                                $log->save();
                                continue;
                            }
                        } else {
                            $user->payroll_cycle_id = null;
                        }

                        if (!empty($item[246])) {
                            $AttendanceCycle = PayrollCycle::where('key_name', 'attendance_custom')->where('label', '=', $item[246])->first();
                            if (isset($AttendanceCycle)) {
                                $user->attendance_cycle_id = $AttendanceCycle->id;
                            } else {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Attendance Cycle Setting not found.";
                                $log->save();
                                continue;
                            }
                        } else {
                            $user->attendance_cycle_id = null;
                        }
                    }

                    if (checkModuleAdmin(27)) {
                        if (!empty($item[247])) {
                            $user->recruitment_entitle = $item[247] == 'Entitle Recruitment' ? 1 : 0;
                        } else {
                            $user->recruitment_entitle = null;
                        }
                    }

                    $user->id = $key + 1;
                    // $user->save();
                    array_push($userArray, $user->toArray());

                    // SD
                    if (!empty($item[40])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "SD";
                        $education->tahun_awal      = $item[40];
                        $education->tahun_akhir     = $item[41];
                        $education->fakultas        = strtoupper($item[42]);
                        $education->kota            = strtoupper($item[43]); // CITY
                        $education->jurusan         = strtoupper($item[44]); // MAJOR
                        $education->nilai           = $item[45]; // GPA
                        $education->certificate     = $item[46];
                        $education->note            = strtoupper($item[47]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // SMP
                    if (!empty($item[48])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "SMP";
                        $education->tahun_awal      = $item[48];
                        $education->tahun_akhir     = $item[49];
                        $education->fakultas        = strtoupper($item[50]);
                        $education->kota            = strtoupper($item[51]); // CITY
                        $education->jurusan         = strtoupper($item[52]); // MAJOR
                        $education->nilai           = $item[53]; // GPA
                        $education->certificate     = $item[54];
                        $education->note            = strtoupper($item[55]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // SMA/SMK
                    if (!empty($item[56])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "SMA/SMK";
                        $education->tahun_awal      = $item[56];
                        $education->tahun_akhir     = $item[57];
                        $education->fakultas        = strtoupper($item[58]);
                        $education->kota            = strtoupper($item[59]); // CITY
                        $education->jurusan         = strtoupper($item[60]); // MAJOR
                        $education->nilai           = $item[61]; // GPA
                        $education->certificate     = $item[62];
                        $education->note            = strtoupper($item[63]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // D1
                    if (!empty($item[64])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "D1";
                        $education->tahun_awal      = $item[64];
                        $education->tahun_akhir     = $item[65];
                        $education->fakultas        = strtoupper($item[66]);
                        $education->kota            = strtoupper($item[67]); // CITY
                        $education->jurusan         = strtoupper($item[68]); // MAJOR
                        $education->nilai           = $item[69]; // GPA
                        $education->certificate     = $item[70];
                        $education->note            = strtoupper($item[71]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // D2
                    if (!empty($item[72])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "D2";
                        $education->tahun_awal      = $item[72];
                        $education->tahun_akhir     = $item[73];
                        $education->fakultas        = strtoupper($item[74]);
                        $education->kota            = strtoupper($item[75]); // CITY
                        $education->jurusan         = strtoupper($item[76]); // MAJOR
                        $education->nilai           = $item[77]; // GPA
                        $education->certificate     = $item[78];
                        $education->note            = strtoupper($item[79]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // D3
                    if (!empty($item[80])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "D3";
                        $education->tahun_awal      = $item[80];
                        $education->tahun_akhir     = $item[81];
                        $education->fakultas        = strtoupper($item[82]);
                        $education->kota            = strtoupper($item[83]); // CITY
                        $education->jurusan         = strtoupper($item[84]); // MAJOR
                        $education->nilai           = $item[85]; // GPA
                        $education->certificate     = $item[86];
                        $education->note            = strtoupper($item[87]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // S1
                    if (!empty($item[88])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "S1";
                        $education->tahun_awal      = $item[88];
                        $education->tahun_akhir     = $item[89];
                        $education->fakultas        = strtoupper($item[90]);
                        $education->kota            = strtoupper($item[91]); // CITY
                        $education->jurusan         = strtoupper($item[92]); // MAJOR
                        $education->nilai           = $item[93]; // GPA
                        $education->certificate     = $item[94];
                        $education->note            = strtoupper($item[95]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // S2
                    if (!empty($item[96])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "S2";
                        $education->tahun_awal      = $item[96];
                        $education->tahun_akhir     = $item[97];
                        $education->fakultas        = strtoupper($item[98]);
                        $education->kota            = strtoupper($item[99]); // CITY
                        $education->jurusan         = strtoupper($item[100]); // MAJOR
                        $education->nilai           = $item[101]; // GPA
                        $education->certificate     = $item[102];
                        $education->note            = strtoupper($item[103]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    // S3
                    if (!empty($item[104])) {
                        $education                  = new UserEducationTemp();
                        $education->user_temp_id    = $key + 1;
                        $education->pendidikan      = "S3";
                        $education->tahun_awal      = $item[104];
                        $education->tahun_akhir     = $item[105];
                        $education->fakultas        = strtoupper($item[106]);
                        $education->kota            = strtoupper($item[107]); // CITY
                        $education->jurusan         = strtoupper($item[108]); // MAJOR
                        $education->nilai           = $item[109]; // GPA
                        $education->certificate     = $item[110];
                        $education->note            = strtoupper($item[111]);
                        // $education->save();
                        array_push($userEducationArray, $education->toArray());
                    }

                    //AYAH
                    if (!empty($item[112])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Ayah Kandung";
                        $family->nama               = strtoupper($item[112]);
                        $family->gender             = $item[113];
                        $family->tempat_lahir       = strtoupper($item[114]);
                        if (!empty($item[115])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[115])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Ayah Kandung Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[116]);
                        $family->pekerjaan          = strtoupper($item[117]);
                        $family->contact          = strtoupper($item[118]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //IBU
                    if (!empty($item[119])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Ibu Kandung";
                        $family->nama               = strtoupper($item[119]);
                        $family->gender             = $item[120];
                        $family->tempat_lahir       = strtoupper($item[121]);

                        if (!empty($item[122])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[122])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Ibu Kandung Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[123]);
                        $family->pekerjaan          = strtoupper($item[124]);
                        $family->contact          = strtoupper($item[125]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //ISTRI
                    if (!empty($item[126])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Istri";
                        $family->nama               = strtoupper($item[126]);
                        $family->gender             = $item[127];
                        $family->tempat_lahir       = strtoupper($item[128]);

                        if (!empty($item[129])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[129])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Istri Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[130]);
                        $family->pekerjaan          = strtoupper($item[131]);
                        $family->contact          = strtoupper($item[132]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //SUAMI
                    if (!empty($item[133])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Suami";
                        $family->nama               = strtoupper($item[133]);
                        $family->gender             = $item[134];
                        $family->tempat_lahir       = strtoupper($item[135]);

                        if (!empty($item[136])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[136])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Suami Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[137]);
                        $family->pekerjaan          = strtoupper($item[138]);
                        $family->contact          = strtoupper($item[139]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //Anak 1
                    if (!empty($item[140])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Anak 1";
                        $family->nama               = strtoupper($item[140]);
                        $family->gender             = $item[141];
                        $family->tempat_lahir       = strtoupper($item[142]);

                        if (!empty($item[143])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[143])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Anak 1 Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[144]);
                        $family->pekerjaan          = strtoupper($item[145]);
                        $family->contact          = strtoupper($item[146]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //Anak 2
                    if (!empty($item[147])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Anak 2";
                        $family->nama               = strtoupper($item[147]);
                        $family->gender             = $item[148];
                        $family->tempat_lahir       = strtoupper($item[149]);

                        if (!empty($item[150])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[150])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Anak 2 Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[151]);
                        $family->pekerjaan          = strtoupper($item[152]);
                        $family->contact          = strtoupper($item[153]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //Anak 3
                    if (!empty($item[154])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Anak 3";
                        $family->nama               = strtoupper($item[154]);
                        $family->gender             = $item[155];
                        $family->tempat_lahir       = strtoupper($item[156]);

                        if (!empty($item[157])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[157])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Anak 3 Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[158]);
                        $family->pekerjaan          = strtoupper($item[159]);
                        $family->contact          = strtoupper($item[160]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //Anak 4
                    if (!empty($item[161])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Anak 4";
                        $family->nama               = strtoupper($item[161]);
                        $family->gender             = $item[162];
                        $family->tempat_lahir       = strtoupper($item[163]);

                        if (!empty($item[164])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[164])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Anak 4 Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[165]);
                        $family->pekerjaan          = strtoupper($item[166]);
                        $family->contact          = strtoupper($item[167]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //Anak 5
                    if (!empty($item[168])) {
                        $family                     = new UserFamilyTemp();
                        $family->user_temp_id       = $key + 1;
                        $family->hubungan           = "Anak 5";
                        $family->nama               = strtoupper($item[168]);
                        $family->gender             = $item[169];
                        $family->tempat_lahir       = strtoupper($item[170]);

                        if (!empty($item[171])) {
                            try {
                                $family->tanggal_lahir      = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[171])->format('Y-m-d');
                            } catch (\Exception $e) {
                                $log->row_number = $key + 1;
                                $log->message = "Error : Anak 5 Birth Date is not valid";
                                $log->save();
                                continue;
                            }
                        } else {
                            $family->tanggal_lahir = null;
                        }
                        $family->jenjang_pendidikan = strtoupper($item[172]);
                        $family->pekerjaan          = strtoupper($item[173]);
                        $family->contact          = strtoupper($item[174]);
                        // $family->save();
                        array_push($userFamilyArray, $family->toArray());
                    }

                    //Certificate
                    $indexItem = 174;
                    for ($index=1; $index <= 10; $index++) { 
                        if (!empty($item[++$indexItem])) {
                            $certification                      = new UserCertificationTemp();
                            $certification->user_temp_id        = $key + 1;
                            $certification->name                = $item[$indexItem];

                            if (!empty($item[++$indexItem])) {
                                try {
                                    $certification->date        = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[$indexItem])->format('Y-m-d');
                                } catch (\Exception $e) {
                                    $log->row_number = $key + 1;
                                    $log->message = "Error : Training ".$item[$indexItem-1]." Date is not valid";
                                    $log->save();
                                    $indexItem += 4;
                                    continue;
                                }
                            } else {
                                $certification->date = null;
                            }
                            $certification->organizer           = $item[++$indexItem];
                            $certification->certificate_number  = $item[++$indexItem];
                            $certification->score               = $item[++$indexItem];
                            $certification->description         = $item[++$indexItem];
                            // $certification->save();
                            array_push($userCertificationArray, $certification->toArray());
                        } else {
                            $indexItem += 5;
                        }
                    }

                    if (checkModuleAdmin(28)) {
                        if (!empty($item[240])) {
                            $cabang = array();
                            $cabang = explode(',', $item[240]);
                            $errorcount=0;
                            $brancherror = "";
                            foreach ($cabang as $cabangstring) {
                                $branchnew = Cabang::whereRaw("LOWER(REPLACE(name,' ','')) = ?", str_replace(' ', '',strtolower($cabangstring)))->first();
                                if (isset($branchnew)) {
                                    $userbranchvisit    = new UsersBranchVisitTemp();
                                    $userbranchvisit->user_id_temp    = $key + 1;
                                    $userbranchvisit->cabang_id  = $branchnew->id;
                                    // $userbranchvisit->save();
                                    array_push($userBranchVisitArray, $userbranchvisit->toArray());
                                } 
                                else {
                                    $errorcount++;
                                    $brancherror != "" && $brancherror .= ",";
                                    $brancherror.= $cabangstring;
                                }
                                $branchsvisitcount++;
                            }
                            if ($errorcount>0) {
                                info("BRANCH VISIT ERROR");
                                $log->row_number = $key + 1 ;
                                $log->message = "Error : Branch Visit : $brancherror  not found.";
                                $log->save();
                                continue;
                            }
                        }
                    }
                    
                    $log->row_number = $key + 1;
                    if (strpos($log->message, 'Error') === false)
                        $log->message = "Success";
                    $log->save();
                }
                
            }

            UserTemp::insert($userArray);
            UserEducationTemp::insert($userEducationArray);
            UserFamilyTemp::insert($userFamilyArray);
            UserCertificationTemp::insert($userCertificationArray);
            UsersBranchVisitTemp::insert($userBranchVisitArray);
            
            // dd(count($rows));
            if (count($userArray) == 0) {
                return redirect()->route('administrator.karyawan.preview-import')->with('message-error', 'No data to be imported.');
            }
           
            
            //            else if(count($tmp) < count($rows)-3){
            //                return redirect()->route('administrator.karyawan.preview-import')->with('message-error', 'Your data failed to be imported. Please check error messages below.');
            //            }

            return redirect()->route('administrator.karyawan.preview-import')->with('message-success', 'Data successfully Preview-imported');
        } else {
            return redirect()->route('administrator.karyawan.index')->with('message-error', 'File not found!');
        }
    }

    /**
     * [previewImport description]
     * @return [type] [description]
     */
    public function previewImport()
    {
        $params['data'] = UserTemp::all();
        $params['log'] = ImportLog::all();

        return view('administrator.karyawan.preview-import')->with($params);
    }

    /**
     * [deleteDependent description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteDependent($id)
    {
        $data = UserFamily::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Dependent Data Successfully deleted !');
    }

    /**
     * [deleteCertification description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteCertification($id)
    {
        $data = UserCertification::where('id', $id)->first();
        if($data->certificate_photo != null){
            $destinationPath = public_path('/storage/certificate/');
            File::delete($destinationPath.$data->certificate_photo);
        }
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Training Data Successfully deleted !');
    }

    /**
     * [deleteInvetarisMobil description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteInvetarisMobil($id)
    {
        $data = UserInventarisMobil::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Invetaris Data Successfully deleted !');
    }
    

    /**
     * [deleteInvetaris description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteInvetaris($id)
    {
        $data = UserInventaris::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Invetaris Data Successfully deleted !');
    }

    /**
     * [deleteEducation description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteEducation($id)
    {
        $data = UserEducation::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Educatuin data was successfully deleted !');
    }

    public function deleteContract($id)
    {
        $data = UserContract::where('id', $id)->first();
        $id = $data->user_id;
        if($data->file_contract != null){
            $destinationPath = public_path('/storage/contract/');
            File::delete($destinationPath.$data->file_contract);
        }
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Contract data was successfully deleted !');
    }

    /**
     * [deleteTemp description]
     * @return [type] [description]
     */
    public function deleteTemp($id)
    {
        $data = UserTemp::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.karyawan.preview-import')->with('message-success', 'Temporary Data was successfully deleted');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id, Request $request)
    {
        $params['tab'] = $request->tab ?: false;
        $params['visitlist'] = VisitList::join('users', 'users.id', '=', 'visit_list.user_id')
            ->leftJoin('cabang', 'cabang.id', '=', 'visit_list.cabang_id')
            ->leftJoin('master_visit_type', 'visit_list.master_visit_type_id', '=', 'master_visit_type.id')
            ->leftJoin('master_category_visit', 'visit_list.master_category_visit_id', '=', 'master_category_visit.id')
            ->select(
                'users.nik as nik',
                'users.name as username',
                'master_visit_type.master_visit_type_name as master_visit_type_name',
                'cabang.name as cabang_name',
                'master_category_visit.master_category_name as master_category_name',
                'visit_list.*'
            )
            ->where('users.id', $id)
            ->orderBy('visit_list.visit_time', 'DESC')
            ->get();


        $params['branchsuser'] = UsersBranchVisit::select('cabang_id')->where('user_id', $id)->pluck('cabang_id')->all();
        $params['userbranch'] = UsersBranchVisit::where('user_id', $id)->get();
        $params['VisitTypeList'] = MasterVisitType::orderBy('id', 'ASC')->get();
        $params['CategoryVisitList'] = MasterCategoryVisit::orderBy('id', 'ASC')->get();
        $params['OvertimePayroll'] = OvertimePayroll::all();
        $params['PayrollUMR'] = PayrollUMR::all();
        $params['PayrollCycle'] = PayrollCycle::where('key_name', 'payroll_custom')->get();
        $params['AttendanceCycle'] = PayrollCycle::where('key_name', 'attendance_custom')->get();
        $params['payrollCountry'] = PayrollCountry::all();
        $params['project'] = Project::all();
        $params['career'] = CareerHistory::where('user_id', $id)
            ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
            ->orderBy('effective_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
        $params['data'] = User::where('id', $id)->first();
        if ($params['data']) {
            for ($i = 1; $i <= date('t'); $i++) {
                $dates[] = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                $ShiftScheduleChange = ShiftScheduleChange::where('change_date', '<=', $dates[count($dates)-1])->whereHas('shiftScheduleChangeEmployees', function($query) use ($id) {
                    $query->where('user_id', $id);
                })->orderBy('change_date', 'DESC')->first();
                $ShiftScheduleChange = $ShiftScheduleChange ? $ShiftScheduleChange->shift : $params['data']->shift;
                $shiftSchedule['shift'][] = $ShiftScheduleChange ? $ShiftScheduleChange->name : null;
                $ShiftScheduleChangeDetail = $ShiftScheduleChange ? $ShiftScheduleChange->details->where('day', date('l', strtotime($dates[count($dates)-1])))->first() : null;
                $shiftSchedule['shift_in'][] = $ShiftScheduleChangeDetail ? $ShiftScheduleChangeDetail->clock_in : null;
                $shiftSchedule['shift_out'][] = $ShiftScheduleChangeDetail ? $ShiftScheduleChangeDetail->clock_out : null;
                $shiftDay[] = $ShiftScheduleChangeDetail;
            }
            
            // dd($shiftDay);
            $params['shiftSchedule'] = $shiftSchedule;
            $params['shiftScheduleChange'] = ShiftScheduleChange::whereHas('shiftScheduleChangeEmployees', function($query) use ($id) {
                $query->where('user_id', $id);
            })->with('shift')->orderBy('change_date', 'DESC')->orderBy('shift_id', 'ASC')->get();
            $params['dates'] = $dates;
            $params['shiftDay'] = $shiftDay;
            $params['department'] = Department::where('division_id', $params['data']['division_id'])->get();
            $params['provinces'] = Provinsi::all();
            $params['dependent'] = UserFamily::where('user_id', $id)->first();
            $params['certification'] = UserCertification::where('user_id', $id)->first();
            $params['education'] = UserEducation::where('user_id', $id)->first();
            $params['kabupaten'] = Kabupaten::where('id_prov', $params['data']['provinsi_id'])->get();
            $params['kecamatan'] = Kecamatan::where('id_kab', $params['data']['kabupaten_id'])->get();
            $params['kelurahan'] = Kelurahan::where('id_kec', $params['data']['kecamatan_id'])->get();
            $params['division'] = Division::all();
            $params['section'] = Section::where('division_id', $params['data']['division_id'])->get();
            $params['payroll'] = Payroll::where('user_id', $id)->first();
            $params['list_manager'] = EmporeOrganisasiManager::where('empore_organisasi_direktur_id', $params['data']['empore_organisasi_direktur'])->get();
            $params['list_staff'] = EmporeOrganisasiStaff::where('empore_organisasi_manager_id', $params['data']['empore_organisasi_manager_id'])->get();
            $params['absensi_item'] = AbsensiItem::where('user_id', $id)
                ->orderBy('date', 'DESC')
                ->orderBy('clock_in', 'DESC')
                ->get();
            // dd($params['absensi_item']);
            $params['structure'] = getStructureName();
            return view('administrator.karyawan.edit')->with($params);
        }
        return redirect()->route('administrator.karyawan.index');
    }

    public function rejoin($id)
    {
        $params['data'] = User::where('id', $id)->first();
        $params['branchsuser'] = UsersBranchVisit::select('cabang_id')->where('user_id', $id)->pluck('cabang_id')->all();
        $params['userbranch'] = UsersBranchVisit::where('user_id', $id)->get();
        $params['dependent'] = UserFamily::where('user_id', $id)->first();
        $params['certification'] = UserCertification::where('user_id', $id)->first();
        $params['education'] = UserEducation::where('user_id', $id)->first();
        $params['payroll'] = Payroll::where('user_id', $id)->first();

        $params['VisitTypeList'] = MasterVisitType::orderBy('id', 'ASC')->get();
        $params['CategoryVisitList'] = MasterCategoryVisit::orderBy('id', 'ASC')->get();
        $params['OvertimePayroll'] = OvertimePayroll::all();
        $params['PayrollUMR'] = PayrollUMR::all();
        $params['PayrollCycle'] = PayrollCycle::where('key_name', 'payroll_custom')->get();
        $params['AttendanceCycle'] = PayrollCycle::where('key_name', 'attendance_custom')->get();
        $params['payrollCountry'] = PayrollCountry::all();
        $params['project'] = Project::all();
        $params['department']   = Department::all();
        $params['provinces']    = Provinsi::all();
        $params['division']     = Division::all();
        $params['department']   = Department::all();
        $params['section']      = Section::all();
        $params['structure']    = getStructureName();

        return view('administrator.karyawan.rejoin')->with($params);
    }

    public function getVisitPhotos($visitid)
    {
        $data = VisitPict::select(
            'visit_list_id',
            DB::raw("CONCAT('/', photo) AS photo"),
            'photocaption'
        )
            ->where('visit_list_id', $visitid)->get();
        if ($data) {
            if (count($data) > 0) {
                $res['message'] = 'success';
                $res['data']    = $data;
            } else {
                $res['message'] = 'failed';
            }
        } else {
            $res['message'] = 'failed';
        }

        return response($res);
    }

    public function ajaxEdit($id)
    {
        $params['data'] = User::where('id', $id)->first();
        if ($params['data']) {
            $params['holidays'] = [];

            $holiday = Shift::where('id', $params['data']->shift_id)->first();
            if ($holiday) {
                if ($holiday->is_holiday == '0') {
                    $hol = LiburNasional::all();
                    $params['holidays'] = $hol;
                } else {
                    $params['holidays'] = [];
                }
            }

            $params['department'] = Department::where('division_id', $params['data']['division_id'])->get();
            $params['provinces'] = Provinsi::all();
            $params['dependent'] = UserFamily::where('user_id', $id)->first();
            $params['certification'] = UserCertification::where('user_id', $id)->first();
            $params['education'] = UserEducation::where('user_id', $id)->first();
            $params['kabupaten'] = Kabupaten::where('id_prov', $params['data']['provinsi_id'])->get();
            $params['kecamatan'] = Kecamatan::where('id_kab', $params['data']['kabupaten_id'])->get();
            $params['kelurahan'] = Kelurahan::where('id_kec', $params['data']['kecamatan_id'])->get();
            $params['division'] = Division::all();
            $params['section'] = Section::where('division_id', $params['data']['division_id'])->get();
            $params['payroll'] = Payroll::where('user_id', $id)->first();
            $params['list_manager'] = EmporeOrganisasiManager::where('empore_organisasi_direktur_id', $params['data']['empore_organisasi_direktur'])->get();
            $params['list_staff'] = EmporeOrganisasiStaff::where('empore_organisasi_manager_id', $params['data']['empore_organisasi_manager_id'])->get();
            $params['absensi_item'] = AbsensiItem::where('user_id', $id)
                ->leftJoin('cabang as ci', 'cabang_id_in', '=', 'ci.id')
                ->leftJoin('cabang as co', 'cabang_id_out', '=', 'co.id')
                ->leftJoin('shift', 'shift.id', '=', 'absensi_item.shift_id')
                ->leftJoin('shift_detail', 'absensi_item.shift_id', '=', 'shift_detail.shift_id')
                ->orderBy('date', 'DESC')
                ->orderBy('clock_in', 'DESC')
                ->select(['absensi_item.*', 'ci.name as cabang_in', 'co.name as cabang_out', 'shift.name as shift_name', 'shift_detail.id as shift_detail_id', 'shift_detail.clock_in as shift_in', 'shift_detail.clock_out as shift_out'])
                ->where(function($query) {
                    $query->whereColumn('shift_detail.day', 'absensi_item.timetable')
                        ->orWhereNull('absensi_item.shift_id')                                   
                        ->orWhere('absensi_item.shift_id', 0);
                })
                ->get();
            $params['structure'] = getStructureName();
            $params['message'] = 'success';

            return response($params);
        }
        $params['message'] = 'failed';

        return response($params);
    }

    /**
     * [` description]
     * @return [type] [description]
     */
    public function create()
    {
        $params['VisitTypeList'] = MasterVisitType::orderBy('id', 'ASC')->get();
        $params['CategoryVisitList'] = MasterCategoryVisit::orderBy('id', 'ASC')->get();
        $params['OvertimePayroll'] = OvertimePayroll::all();
        $params['PayrollUMR'] = PayrollUMR::all();
        $params['PayrollCycle'] = PayrollCycle::where('key_name', 'payroll_custom')->get();
        $params['AttendanceCycle'] = PayrollCycle::where('key_name', 'attendance_custom')->get();
        $params['payrollCountry'] = PayrollCountry::all();
        $params['project'] = Project::all();
        $params['department']   = Department::all();
        $params['provinces']    = Provinsi::all();
        $params['division']     = Division::all();
        $params['department']   = Department::all();
        $params['section']      = Section::all();
        $params['structure']    = getStructureName();

        return view('administrator.karyawan.create')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        if ($request->master_visit_type_id == 1 && !empty($request->input("userbranchs"))) {
            $del_item = UsersBranchVisit::where('user_id', $id);
            if ($del_item != null) {
                $del_item->delete();
            }
            foreach ($request->input("userbranchs") as $key => $value) {
                $add_item = new UsersBranchVisit;
                $add_item->cabang_id = (int)$value;
                $add_item->user_id = $id;
                $add_item->save();
            }
        }
        else if ($request->master_visit_type_id == 1 && empty($request->input("userbranchs"))) {
            $del_item = UsersBranchVisit::where('user_id', $id);
            if ($del_item != null) {
                $del_item->delete();
            }
        }
        else
        {
            $del_item = UsersBranchVisit::where('user_id', $id);
            if ($del_item != null) {
                $del_item->delete();
            }
        }
        $data       = User::where('id', $id)->first();

        if ($data->shift_id != $request->shift_id) {
            $this->updateSchedule($data->id, $request->shift_id);
        }

        if ($request->password != $data->password) {
            if (!empty($request->password)) {
                $this->validate(
                    $request,
                    [
                        'password' => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[_#?!@$%^&*-]).{8,}$/',
                        'confirmation'      => 'same:password',
                    ],
                    [
                        'password.regex' => 'Password should contain : Lowercase(s), Uppercase(s), Number(s), Symbol!'
                    ]
                );

                $data->password                = bcrypt($request->password);
                $data->is_reset_first_password = null;
            }
        }
        if (get_setting('struktur_organisasi') == 3) {
            $this->validate($request, [
                'nik'               => 'required|unique:users,nik,' . $id,
                'name'              => 'required',
                'marital_status'    => 'required',
                'jenis_kelamin'     => 'required',
                'join_date'         => 'required',
                'tanggal_lahir'     => 'required',
                'organisasi_status' => 'required',
                'branch_id'         => 'required',
                'structure_organization_custom_id' => 'required',
            ],
            [
                'tanggal_lahir.required' => 'The date of birth field is required.',
                'jenis_kelamin.required' => 'The gender field is required.',
                'organisasi_status.required' => 'The employee status field is required',
                'branch_id.required' => 'The branch field is required.',
                'structure_organization_custom_id.required' => 'The position field is required.',
            ]);
        } else {
            $this->validate($request, [
                'nik'               => 'required|unique:users,nik,' . $id,
                'name'              => 'required',
                'marital_status'    => 'required',
                'jenis_kelamin'     => 'required',
                'join_date'         => 'required',
                'tanggal_lahir'     => 'required',
                'organisasi_status' => 'required',
            ],
            [
                'tanggal_lahir.required' => 'The date of birth field is required.',
                'jenis_kelamin.required' => 'The gender field is required.',
                'organisasi_status.required' => 'The employee status field is required',
            ]);
        }

        $data->name         = strtoupper($request->name);
        $data->employee_number      = $request->employee_number;
        $data->absensi_number       = $request->absensi_number;
        $data->nik                  = $request->nik;
        $data->ext                  = $request->ext;
        $data->tempat_lahir         = $request->tempat_lahir;
        $data->tanggal_lahir        = $request->tanggal_lahir;
        $data->marital_status       = $request->marital_status;
        $data->jenis_kelamin        = $request->jenis_kelamin;
        $data->blood_type           = $request->blood_type;
        $data->email                = $request->email;
        $data->join_date            = $request->join_date;
        $data->inactive_date        = $data->inactive_date && \Carbon\Carbon::now() >= $data->inactive_date ? $data->inactive_date : $request->inactive_date;
        if ((!$data->non_active_date || \Carbon\Carbon::now() < $data->non_active_date) && !$data->is_exit) {
            $data->cabang_id        = $request->branch_id;
            $data->structure_organization_custom_id = $request->structure_organization_custom_id;    
            $data->organisasi_status = $request->organisasi_status;
            if (!$data->organisasi_status || $data->organisasi_status == 'Permanent') {
                $data->status_contract     = null;
                $data->start_date_contract = null;
                $data->end_date_contract   = null;
                if ($request->status) {
                    $data->status       = $request->status;
                    $data->resign_date  = $request->resign_date;
                    if ($data->resign_date) {
                        $data->non_active_date = $data->resign_date;
                    }
                } else {
                    $data->status       = null;
                    $data->resign_date  = null;
                }
            } else {
                $data->status_contract     = $request->status_contract;
                $data->start_date_contract = $request->start_date_contract;
                $data->end_date_contract   = $request->end_date_contract;
                if ($data->end_date_contract) {
                    $data->non_active_date = $data->end_date_contract;
                }
                $data->status       = null;
                $data->resign_date  = null;
            }
            if (!$data->resign_date && !$data->end_date_contract) {
                $data->non_active_date = null;
            }
        }

        $data->npwp_number          = $request->npwp_number;
        $data->bpjs_number          = $request->bpjs_number;
        $data->jamsostek_number     = $request->jamsostek_number;
        $data->ktp_number           = $request->ktp_number;
        $data->passport_number      = $request->passport_number;
        $data->kk_number            = $request->kk_number;
        $data->telepon              = $request->telepon;
        $data->mobile_1             = $request->mobile_1;
        $data->mobile_2             = $request->mobile_2;
        $data->emergency_name       = $request->emergency_name;
        $data->emergency_relationship = $request->emergency_relationship;
        $data->emergency_contact    = $request->emergency_contact;
        $data->agama                = $request->agama;
        $data->current_address      = $request->current_address;
        $data->id_address           = $request->id_address;

        //$data->access_id            = 2;
        $data->branch_type          = $request->branch_type;
        $data->hak_cuti             = 12;
        $data->cuti_yang_terpakai   = 0;
        $data->shift_id             = $request->shift_id;
        $data->nama_rekening        = $request->nama_rekening;
        $data->nomor_rekening       = $request->nomor_rekening;
        $data->bank_id              = $request->bank_id;
        $data->ext                  = $request->ext;
        $data->is_pic_cabang        = isset($request->is_pic_cabang) ? $request->is_pic_cabang : 0;
        $data->empore_organisasi_direktur       = $request->empore_organisasi_direktur;
        $data->empore_organisasi_manager_id     = $request->empore_organisasi_manager_id;
        $data->empore_organisasi_staff_id       = $request->empore_organisasi_staff_id;
        $data->master_visit_type_id             = $request->master_visit_type_id;
        $data->master_category_visit_id         = $request->master_category_visit_id;
        $data->overtime_entitle                 = $request->overtime_entitle;
        $data->overtime_payroll_id              = $request->overtime_payroll_id;
        $data->payroll_umr_id                   = $request->payroll_umr_id;
        $data->payroll_cycle_id                 = $request->payroll_cycle_id;
        $data->attendance_cycle_id              = $request->attendance_cycle_id;
        $data->recruitment_entitle              = $request->recruitment_entitle;
        $data->foreigners_status                = $request->foreigners_status;
        $data->payroll_country_id               = $request->payroll_country_id;
        $data->custom_project_id                = $request->custom_project_id;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/foto/') . $company_url;
            $file->move($destinationPath, $fileName);

            $data->foto = $company_url . $fileName;
        }

        if ($request->hasFile('foto_ktp')) {
            $fileKtp = $request->file('foto_ktp');
            $fileNameKtp = md5(rand() . $fileKtp->getClientOriginalName() . time()) . "." . $fileKtp->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/fotoktp/') . $company_url;
            $fileKtp->move($destinationPath, $fileNameKtp);

            $data->foto_ktp = $company_url . $fileNameKtp;
        }

        if ($request->hasFile('foto_kk')) {
            $fileKK = $request->file('foto_kk');
            $fileNameKK = md5(rand() . $fileKK->getClientOriginalName() . time()) . "." . $fileKK->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/fotokk/') . $company_url;
            $fileKK->move($destinationPath, $fileNameKK);

            $data->foto_kk = $company_url . $fileNameKK;
        }

        if ($request->hasFile('foto_sim')) {
            $fileSim = $request->file('foto_sim');
            $fileNameSim = md5(rand() . $fileSim->getClientOriginalName() . time()) . "." . $fileSim->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/fotosim/') . $company_url;
            $fileSim->move($destinationPath, $fileNameSim);

            $data->foto_sim = $company_url . $fileNameSim;
        }

        if ($request->hasFile('foto_cv')) {
            $fileCV = $request->file('foto_cv');
            $fileNameCV = md5(rand() . $fileCV->getClientOriginalName() . time()) . "." . $fileCV->getClientOriginalExtension();
            $company_url = session('company_url', 'umum') . '/';
            $destinationPath = public_path('/storage/fotocv/') . $company_url;
            $fileCV->move($destinationPath, $fileNameCV);

            $data->foto_cv = $company_url . $fileNameCV;
        }

        $data->save();

        //save career
        cleaning_future_career($data);
        $career = CareerHistory::where('user_id', $data->id)
            ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
            ->orderBy('effective_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();
        if (get_setting('struktur_organisasi') == 3 && (checkModule(26) || $career)) {
            if ($request->career_action == 1) {
                if (!$career) {
                    $career = new CareerHistory();
                    $career->user_id = $data->id;
                    $career->effective_date = $data->join_date ?: \Carbon\Carbon::now()->format('Y-m-d');
                }
            } else {
                $career = new CareerHistory();
                $career->user_id = $data->id;
                $career->effective_date = \Carbon\Carbon::now()->format('Y-m-d');
            }
            $career->cabang_id = $data->cabang_id;
            $career->structure_organization_custom_id = $data->structure_organization_custom_id;
            $career->status = $data->organisasi_status ?: '';
            $career->start_date = $data->start_date_contract;
            $career->end_date = $data->end_date_contract;
            // $career->job_desc = $data->job_desc;
            // $career->sub_grade_id = $data->sub_grade_id;
            $career->save();
        }

        if (isset($request->dependent)) {
            foreach ($request->dependent['nama'] as $key => $item) {
                $dep = new UserFamily();
                $dep->user_id           = $data->id;
                $dep->nama          = $request->dependent['nama'][$key];
                $dep->hubungan      = $request->dependent['hubungan'][$key];
                $dep->contact      = $request->dependent['contact'][$key];
                $dep->tempat_lahir  = $request->dependent['tempat_lahir'][$key];
                $dep->tanggal_lahir = $request->dependent['tanggal_lahir'][$key];
                $dep->tanggal_meninggal = $request->dependent['tanggal_meninggal'][$key];
                $dep->jenjang_pendidikan = $request->dependent['jenjang_pendidikan'][$key];
                $dep->pekerjaan = $request->dependent['pekerjaan'][$key];
                $dep->tertanggung = $request->dependent['tertanggung'][$key];
                $dep->save();
            }
        }

        if (isset($request->certification)) {
            foreach ($request->certification['name'] as $key => $item) {
                $cert = new UserCertification();
                $cert->user_id               = $data->id;
                $cert->name                  = $request->certification['name'][$key];
                $cert->date                  = $request->certification['date'][$key];
                $cert->organizer             = $request->certification['organizer'][$key];
                $cert->certificate_number    = $request->certification['certificate_number'][$key];
                $cert->score                 = $request->certification['score'][$key];
                $cert->description           = $request->certification['description'][$key];

                if ($request->hasFile('certification.certificate_photo.'.$key)) {
                    $file = $request->file('certificate_photo');
                    $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $company_url = session('company_url', 'umum') . '/';
                    $destinationPath = public_path('/storage/certificate/') . $company_url;
                    $file->move($destinationPath, $fileName);
        
                    $cert->certificate_photo = $company_url . $fileName;
                }
                $cert->save();
            }
        }

        if (isset($request->inventaris_mobil)) {
            foreach ($request->inventaris_mobil['tipe_mobil'] as $k => $item) {
                $inventaris                 = new UserInventarisMobil();
                $inventaris->user_id        = $data->id;
                $inventaris->tipe_mobil     = $request->inventaris_mobil['tipe_mobil'][$k];
                $inventaris->tahun          = $request->inventaris_mobil['tahun'][$k];
                $inventaris->no_polisi      = $request->inventaris_mobil['no_polisi'][$k];
                $inventaris->status_mobil   = $request->inventaris_mobil['status_mobil'][$k];
                $inventaris->save();
            }
        }

        if (isset($request->education)) {
            foreach ($request->education['pendidikan'] as $key => $item) {
                $edu = new UserEducation();
                $edu->user_id = $data->id;
                $edu->pendidikan    = $request->education['pendidikan'][$key];
                $edu->tahun_awal    = $request->education['tahun_awal'][$key];
                $edu->tahun_akhir   = $request->education['tahun_akhir'][$key];
                $edu->fakultas      = $request->education['fakultas'][$key];
                $edu->jurusan       = $request->education['jurusan'][$key];
                $edu->nilai         = $request->education['nilai'][$key];
                $edu->kota          = $request->education['kota'][$key];
                $edu->save();
            }
        }

        if (isset($request->cuti)) {
            // user Education
            foreach ($request->cuti['cuti_id'] as $key => $item) {
                $permitleave = Cuti::where('id', $request->cuti['cuti_id'][$key])->first();
                $c = new UserCuti();
                $c->user_id = $data->id;
                $c->cuti_id    = $request->cuti['cuti_id'][$key];
                $c->kuota    = $request->cuti['kuota'][$key];
                if ($permitleave->jenis_cuti=='Permit')
                {
                    $c->cuti_terpakai    = null;
                    $c->sisa_cuti    = null;
                }
                else
                {
                    $c->cuti_terpakai    = $request->cuti['terpakai'][$key];
                    $c->sisa_cuti    = $request->cuti['kuota'][$key] - $request->cuti['terpakai'][$key];
                }
              
                $c->save();
            }
        }

        if (isset($request->inventaris_lainnya['jenis'])) {
            foreach ($request->inventaris_lainnya['jenis'] as $k => $i) {
                $i              = new UserInventaris();
                $i->user_id     = $data->id;
                $i->jenis       = $request->inventaris_lainnya['jenis'][$k];
                $i->description = $request->inventaris_lainnya['description'][$k];
                $i->save();
            }
        }

        if (isset($request->contract)) {
            foreach ($request->contract['number'] as $key => $item) {
                $cont = new UserContract();
                $cont->user_id            = $data->id;
                $cont->number             = $request->contract['number'][$key];
                $cont->date               = $request->contract['date'][$key];
                $cont->contract_sent      = $request->contract['contract_sent'][$key];
                $cont->return_contract    = $request->contract['return_contract'][$key];
                $cont->save();
            }
        }

        return redirect()->route('administrator.karyawan.edit', $data->id)->with('message-success', 'Data saved successfully');
    }

    

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        // dd($request);
        if (isset($request->id)) {
            $lastUser = User::find($request->id);
        }
        $data               = new User();
        if (checkUserLimit()) {
            if (get_setting('struktur_organisasi') == 3) {
                $this->validate(
                    $request,
                    [
                        'nik'               => 'required|unique:users',
                        'name'              => 'required',
                        'marital_status'    => 'required',
                        'jenis_kelamin'     => 'required',
                        'join_date'         => 'required',
                        'tanggal_lahir'     => 'required',
                        'organisasi_status' => 'required',
                        'branch_id'         => 'required',
                        'structure_organization_custom_id' => 'required',
                        'ptkp'              => 'required',
                        'password'          => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[_#?!@$%^&*-]).{8,}$/',
                        'confirmation'      => 'same:password',
                    ],
                    [
                        'tanggal_lahir.required' => 'The date of birth field is required.',
                        'jenis_kelamin.required' => 'The gender field is required.',
                        'organisasi_status.required' => 'The employee status field is required',
                        'password.regex' => 'Password should contain : Lowercase(s), Uppercase(s), Number(s), Symbol!',
                        'branch_id.required' => 'The Branch field is required.',
                        'structure_organization_custom_id.required' => 'The Position field is required.',
                    ]
                );
            } else {
                $this->validate(
                    $request,
                    [
                        'nik'               => 'required|unique:users',
                        'name'              => 'required',
                        'marital_status'    => 'required',
                        'jenis_kelamin'     => 'required',
                        'join_date'         => 'required',
                        'tanggal_lahir'     => 'required',
                        'organisasi_status' => 'required',
                        'ptkp'              => 'required',
                        'password'          => 'required|string|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[_#?!@$%^&*-]).{8,}$/',
                        'confirmation'      => 'same:password',
                    ],
                    [
                        'tanggal_lahir.required' => 'The date of birth field is required.',
                        'jenis_kelamin.required' => 'The gender field is required.',
                        'organisasi_status.required' => 'The employee status field is required',
                        'password.regex' => 'Password should contain : Lowercase(s), Uppercase(s), Number(s), Symbol!',
                    ]
                );
            }

            $data->password             = bcrypt($request->password);
            $data->name                 = strtoupper($request->name);
            $data->employee_number      = $request->employee_number;
            $data->absensi_number       = $request->absensi_number;
            $data->nik                  = $request->nik;
            $data->ext                  = $request->ext;
            $data->tempat_lahir         = $request->tempat_lahir;
            $data->tanggal_lahir        = $request->tanggal_lahir;
            $data->marital_status       = $request->marital_status;
            $data->jenis_kelamin        = $request->jenis_kelamin;
            $data->payroll_marital_status = $request->ptkp == 'TK-0' ? ($request->jenis_kelamin == 'Male' ? 'Bujangan/Wanita' : $request->marital_status) : ($request->ptkp == 'K-0' ? 'Menikah' : ($request->ptkp == 'K-1' ? 'Menikah Anak 1' : ($request->ptkp == 'K-2' ? 'Menikah Anak 2' : 'Menikah Anak 3')));
            $data->payroll_jenis_kelamin = $request->ptkp != 'TK-0' ? 'Male' : $request->jenis_kelamin;
            $data->blood_type           = $request->blood_type;
            $data->email                = $request->email;
            $data->join_date            = $request->join_date;
            $data->organisasi_status    = $request->organisasi_status;
            if (!$data->organisasi_status || $data->organisasi_status == 'Permanent') {
                $data->status_contract     = null;
                $data->start_date_contract = null;
                $data->end_date_contract   = null;
            } else {
                $data->status_contract     = $request->status_contract;
                $data->start_date_contract = $request->start_date_contract;
                $data->end_date_contract   = $request->end_date_contract;
                $data->non_active_date     = $request->end_date_contract;
                $data->inactive_date       = $request->inactive_date;
            }
            $data->npwp_number          = $request->npwp_number;
            $data->bpjs_number          = $request->bpjs_number;
            $data->jamsostek_number     = $request->jamsostek_number;
            $data->ktp_number           = $request->ktp_number;
            $data->passport_number      = $request->passport_number;
            $data->kk_number            = $request->kk_number;
            $data->telepon              = $request->telepon;
            $data->mobile_1             = $request->mobile_1;
            $data->mobile_2             = $request->mobile_2;
            $data->emergency_name       = $request->emergency_name;
            $data->emergency_relationship = $request->emergency_relationship;
            $data->emergency_contact    = $request->emergency_contact;
            $data->agama                = $request->agama;
            $data->current_address      = $request->current_address;
            $data->id_address           = $request->id_address;
            $data->access_id            = 2;
            //$data->branch_type          = $request->branch_type;
            //$data->hak_cuti             = 12;
            //$data->cuti_yang_terpakai   = 0;
            $data->cabang_id            = $request->branch_id;
            $data->shift_id             = $request->shift_id;
            $data->nama_rekening        = $request->nama_rekening;
            $data->nomor_rekening       = $request->nomor_rekening;
            $data->bank_id              = $request->bank_id;
            $data->ext                  = $request->ext;

            //$data->is_pic_cabang        = isset($request->is_pic_cabang) ? $request->is_pic_cabang : 0;

            //$data->empore_organisasi_direktur   = $request->empore_organisasi_direktur;
            //$data->empore_organisasi_manager_id = $request->empore_organisasi_manager_id;
            //$data->empore_organisasi_staff_id   = $request->empore_organisasi_staff_id;
            $data->structure_organization_custom_id  = $request->structure_organization_custom_id;
            $data->master_visit_type_id              = $request->master_visit_type_id;
            $data->master_category_visit_id          = $request->master_category_visit_id;
            $data->overtime_entitle                  = $request->overtime_entitle;
            $data->overtime_payroll_id               = $request->overtime_payroll_id;
            $data->payroll_umr_id                    = $request->payroll_umr_id;
            $data->payroll_cycle_id                  = $request->payroll_cycle_id;
            $data->attendance_cycle_id               = $request->attendance_cycle_id;
            $data->recruitment_entitle               = $request->recruitment_entitle;
            $data->foreigners_status                 = $request->foreigners_status;
            $data->payroll_country_id                = $request->payroll_country_id;
            $data->custom_project_id                = $request->custom_project_id;

            if (request()->hasFile('foto')) {
                $file = $request->file('foto');
                $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/foto/') . $company_url;
                $file->move($destinationPath, $fileName);

                $data->foto = $company_url . $fileName;
            } else if (isset($lastUser)) {
                $data->foto = $lastUser->foto;
            }
            if (request()->hasFile('foto_ktp')) {
                $fileKtp = $request->file('foto_ktp');
                $fileNameKtp = md5(rand() . $fileKtp->getClientOriginalName() . time()) . "." . $fileKtp->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/fotoktp/'). $company_url;
                $fileKtp->move($destinationPath, $fileNameKtp);

                $data->foto_ktp = $company_url . $fileNameKtp;
            } else if (isset($lastUser)) {
                $data->foto_ktp = $lastUser->foto_ktp;
            }

            if ($request->hasFile('foto_kk')) {
                $fileKK = $request->file('foto_kk');
                $fileNameKK = md5(rand() . $fileKK->getClientOriginalName() . time()) . "." . $fileKK->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/fotokk/') . $company_url;
                $fileKK->move($destinationPath, $fileNameKK);
    
                $data->foto_kk = $company_url . $fileNameKK;
            } else if (isset($lastUser)) {
                $data->foto_kk = $lastUser->foto_kk;
            }
    
            if ($request->hasFile('foto_sim')) {
                $fileSim = $request->file('foto_sim');
                $fileNameSim = md5(rand() . $fileSim->getClientOriginalName() . time()) . "." . $fileSim->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/fotosim/') . $company_url;
                $fileSim->move($destinationPath, $fileNameSim);
    
                $data->foto_sim = $company_url . $fileNameSim;
            } else if (isset($lastUser)) {
                $data->foto_sim = $lastUser->foto_sim;
            }
    
            if ($request->hasFile('foto_cv')) {
                $fileCV = $request->file('foto_cv');
                $fileNameCV = md5(rand() . $fileCV->getClientOriginalName() . time()) . "." . $fileCV->getClientOriginalExtension();
                $company_url = session('company_url', 'umum') . '/';
                $destinationPath = public_path('/storage/fotocv/') . $company_url;
                $fileCV->move($destinationPath, $fileNameCV);
    
                $data->foto_cv = $company_url . $fileNameCV;
            } else if (isset($lastUser)) {
                $data->foto_cv = $lastUser->foto_cv;
            }

            $projectId = \Auth::user()->project_id;
            if (!empty($projectId)) {
                $data->project_id = $projectId;
            }
            $data->save();
            
            if (isset($lastUser)) {
                $lastUser->is_rejoined = 1;
                $lastUser->save();
            }

            $this->updateSchedule($data->id, $data->shift_id);

            //save career
            cleaning_future_career($data);
            $career = CareerHistory::where('user_id', $data->id)
                ->whereDate('effective_date', '<=', \Carbon\Carbon::now()->format('Y-m-d'))
                ->orderBy('effective_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
            if (get_setting('struktur_organisasi') == 3 && (checkModule(26) || $career)) {
                if (!$career) {
                    $career = new CareerHistory();
                    $career->user_id = $data->id;
                    $career->effective_date = $data->join_date ?: \Carbon\Carbon::now()->format('Y-m-d');
                }
                $career->cabang_id = $data->cabang_id;
                $career->structure_organization_custom_id = $data->structure_organization_custom_id;
                $career->status = $data->organisasi_status ?: '';
                $career->start_date = $data->start_date_contract;
                $career->end_date = $data->end_date_contract;
                // $career->job_desc = $data->job_desc;
                // $career->sub_grade_id = $data->sub_grade_id;
                $career->save();
            }

            //save userbranchvisit
            if (isset($request->master_visit_type_id) && $request->master_visit_type_id == 1) {
                foreach ($request->input("userbranchs") as $key => $value) {
                    $add_item = new UsersBranchVisit;
                    $add_item->cabang_id = (int)$value;
                    $add_item->user_id = $data->id;
                    $add_item->save();
                }
            }

            // user Dependent
            if (isset($request->dependent)) {
                foreach ($request->dependent['nama'] as $key => $item) {
                    $dep = new UserFamily();
                    $dep->user_id           = $data->id;
                    $dep->nama          = $request->dependent['nama'][$key];
                    $dep->hubungan      = $request->dependent['hubungan'][$key];
                    $dep->contact      = $request->dependent['contact'][$key];
                    $dep->tempat_lahir  = $request->dependent['tempat_lahir'][$key];
                    $dep->tanggal_lahir = $request->dependent['tanggal_lahir'][$key];
                    $dep->tanggal_meninggal = $request->dependent['tanggal_meninggal'][$key];
                    $dep->jenjang_pendidikan = $request->dependent['jenjang_pendidikan'][$key];
                    $dep->pekerjaan = $request->dependent['pekerjaan'][$key];
                    $dep->tertanggung = $request->dependent['tertanggung'][$key];
                    $dep->save();
                }
            }

            // user Certification
            if (isset($request->certification)) {
                foreach ($request->certification['name'] as $key => $item) {
                    $cert = new UserCertification();
                    $cert->user_id               = $data->id;
                    $cert->name                  = $request->certification['name'][$key];
                    $cert->date                  = $request->certification['date'][$key];
                    $cert->organizer             = $request->certification['organizer'][$key];
                    $cert->certificate_number    = $request->certification['certificate_number'][$key];
                    $cert->score                 = $request->certification['score'][$key];
                    $cert->description           = $request->certification['description'][$key];
                    if ($request->hasFile('certification.certificate_photo.'.$key)) {
                        $file = $request->file('certification.certificate_photo.'.$key);
                        $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                        $company_url = session('company_url', 'umum') . '/';
                        $destinationPath = public_path('/storage/certificate/') . $company_url;
                        $file->move($destinationPath, $fileName);
            
                        $cert->certificate_photo = $company_url . $fileName;
                    }
                    if (isset($request->certification['certificate_photo_base64'][$key])) {
                        $file = $request->certification['certificate_photo_base64'][$key];
                        $fileName = md5(rand() . time()) . "." . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                        $company_url = session('company_url', 'umum') . '/';
                        $destinationPath = public_path('/storage/certificate/') . $company_url;
                        file_put_contents($destinationPath . $fileName, file_get_contents($file));
            
                        $cert->certificate_photo = $company_url . $fileName;
                    }
                    $cert->save();
                }
            }

            if (isset($request->inventaris_mobil)) {
                foreach ($request->inventaris_mobil['tipe_mobil'] as $k => $item) {
                    $inventaris                 = new UserInventarisMobil();
                    $inventaris->user_id        = $data->id;
                    $inventaris->tipe_mobil     = $request->inventaris_mobil['tipe_mobil'][$k];
                    $inventaris->tahun          = $request->inventaris_mobil['tahun'][$k];
                    $inventaris->no_polisi      = $request->inventaris_mobil['no_polisi'][$k];
                    $inventaris->status_mobil   = $request->inventaris_mobil['status_mobil'][$k];
                    $inventaris->save();
                }
            }
            if (isset($request->education)) {
                // user Education
                foreach ($request->education['pendidikan'] as $key => $item) {
                    $edu = new UserEducation();
                    $edu->user_id = $data->id;
                    $edu->pendidikan    = $request->education['pendidikan'][$key];
                    $edu->tahun_awal    = $request->education['tahun_awal'][$key];
                    $edu->tahun_akhir   = $request->education['tahun_akhir'][$key];
                    $edu->fakultas      = $request->education['fakultas'][$key];
                    $edu->jurusan       = $request->education['jurusan'][$key];
                    $edu->nilai         = $request->education['nilai'][$key];
                    $edu->kota          = $request->education['kota'][$key];
                    $edu->save();
                }
            }
            if (isset($request->cuti)) {
                // user Cuti
                foreach ($request->cuti['cuti_id'] as $key => $item) {
                    $c = new UserCuti();
                    $c->user_id = $data->id;
                    $c->cuti_id    = $request->cuti['cuti_id'][$key];
                    $c->kuota    = $request->cuti['kuota'][$key];
                    $c->save();
                }
            } else {
                $masterCuti = Cuti::where('jenis_cuti', 'Special Leave')->get();
                foreach ($masterCuti as $key => $value) {
                    # code...
                    $userCuti = UserCuti::where('user_id', $data->id)->where('cuti_id', $value->id)->first();
                    if (!$userCuti) {
                        $c = new UserCuti();
                        $c->user_id     = $data->id;
                        $c->cuti_id     = $value->id;
                        $c->kuota      = $value->kuota;
                        $c->sisa_cuti   = $value->kuota;
                        $c->save();
                    }
                }
            }
            if (isset($request->inventaris_lainnya['jenis'])) {
                foreach ($request->inventaris_lainnya['jenis'] as $k => $i) {
                    $i              = new UserInventaris();
                    $i->user_id     = $data->id;
                    $i->jenis       = $request->inventaris_lainnya['jenis'][$key];
                    $i->description = $request->inventaris_lainnya['description'][$key];
                    $i->save();
                }
            }

            if (isset($request->contract)) {
                foreach ($request->contract['number'] as $key => $item) {
                    $cont = new UserContract();
                    $cont->user_id            = $data->id;
                    $cont->number             = isset($request->contract['number'][$key]) ? $request->contract['number'][$key] : '';
                    $cont->type               = isset($request->contract['type'][$key]) ? $request->contract['type'][$key] : '';;
                    $cont->start_date         = isset($request->contract['start_date'][$key]) ? $request->contract['start_date'][$key] : '';;
                    $cont->end_date           = isset($request->contract['end_date'][$key]) ? $request->contract['end_date'][$key] : '';;
                    $cont->date               = isset($request->contract['date'][$key]) ? $request->contract['date'][$key] : '';;
                    $cont->contract_sent      = isset($request->contract['contract_sent'][$key]) ? $request->contract['contract_sent'][$key] : '';;
                    $cont->return_contract    = isset($request->contract['return_contract'][$key]) ? $request->contract['return_contract'][$key] : '';;
                    if ($request->hasFile('contract.file_contract.'.$key)) {
                        $file = $request->file('contract.file_contract.'.$key);
                        $fileName = md5(rand() . $file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                        $company_url = session('company_url', 'umum') . '/';
                        $destinationPath = public_path('/storage/contract/') . $company_url;
                        $file->move($destinationPath, $fileName);
            
                        $cont->file_contract = $company_url . $fileName;
                    }
                    $cont->save();
                }
            }
            
            return redirect()->route('administrator.karyawan.index')->with('message-success', 'Data saved successfully !');
        } else {
            return redirect()->route('administrator.karyawan.index')->with('message-error', ' You can not add a user anymore. You have reached the limit!');
        }
    }
    /**
     * [deleteInvetaris description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteInventarisLainnya($id)
    {
        $data = UserInventaris::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Invetaris Data Successfully deleted!');
    }

    /**
     * [DeleteCuti description]
     * @param [type] $id [description]
     */
    public function DeleteCuti($id)
    {
        $data = UserCuti::where('id', $id)->first();
        $user_id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $user_id)->with('message-success', 'Leave data successfully deleted');
    }

    /**
     * [deleteOldUser description]
     * @return [type] [description]
     */
    public function deleteOldUser($id)
    {
        $data = User::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.karyawan.preview-import')->with('message-success', 'Old data was successfully deleted');
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = User::where('id', $id)->first();
        $data->delete();

        UserFamily::where('user_id', $id)->delete();

        UserEducation::where('user_id', $id)->delete();

        return redirect()->route('administrator.karyawan.index')->with('message-success', 'Data successfully deleted');
    }

    /**
     * [autologin description]
     * @return [type] [description]
     */
    public function autologin($id)
    {
        \Session::put('is_login_administrator', \Auth::user()->id);
        \Auth::loginUsingId($id);

        $user = User::where('id', $id)->first();
        $user->last_logged_in_at = date('Y-m-d H:i:s');
        $user->save();

        return redirect()->route('karyawan.dashboard');
    }

    public function downloadExcel($data)
    {
        #$data       = User::where('access_id', 2)->orderBy('id', 'DESC')->get();
        $params = [];
        // dd($data);
        foreach ($data as $k =>  $item) {
            if ($item->marital_status == 'Menikah') {
                $item->marital_status = 'Married';
            }
            if ($item->marital_status == 'Menikah Anak 1') {
                $item->marital_status = 'Married with 1 Child';
            }
            if ($item->marital_status == 'Menikah Anak 2') {
                $item->marital_status = 'Married with 2 Child';
            }
            if ($item->marital_status == 'Menikah Anak 3') {
                $item->marital_status = 'Married with 3 Child';
            }
            if ($item->marital_status == 'Bujangan/Wanita') {
                $item->marital_status = 'Single/Female';
            }

            if ($item->agama == 'Muslim') {
                $item->agama = 'Islam';
            }

            if ($item->agama == 'Katolik') {
                $item->agama = 'Catholic';
            }
            if ($item->agama == 'Kristen') {
                $item->agama = 'Christian';
            }
            if ($item->agama == 'Konghucu') {
                $item->agama = 'Confucius';
            }

            $params[$k]['No']                   = $k + 1;
            $params[$k]['Employee Number ']     = $item->employee_number;
            $params[$k]['Absence Number']       = $item->absensi_number;
            $params[$k]['NIK']                  = $item->nik;
            $params[$k]['Name']                 = $item->name;
            $params[$k]['Join Date']            = date('Y-m-d', strtotime($item->join_date));
            $params[$k]['Gender']               = $item->jenis_kelamin;
            $params[$k]['Marital Status']      = $item->marital_status;
            $params[$k]['Religion']             = $item->agama;
            $params[$k]['KTP Number']           = $item->ktp_number;
            $params[$k]['Passport Number']      = $item->passport_number;
            $params[$k]['KK Number']            = $item->kk_number;
            $params[$k]['NPWP Number']          = $item->npwp_number;
            $params[$k]['BPJS Tenaga Kerja Number']  = $item->bpjs_number;
            $params[$k]['BPJS Kesehatan Number']    = $item->jamsostek_number;
            $params[$k]['Place of Birth']       = $item->tempat_lahir;
            $params[$k]['Date of Birth']        = $item->tanggal_lahir;
            $params[$k]['ID Address']           = $item->id_address;
            //$params[$k]['ID City']              = isset($item->kota->nama) ? $item->kota->nama : '';
            //$params[$k]['ID Zip Code']          = $item->id_zip_code;
            $params[$k]['Current Address']      = $item->current_address;
            $params[$k]['Telp']                 = $item->telepon;
            $params[$k]['Ext']                  = $item->ext;
            $params[$k]['Mobile 1']             = $item->mobile_1;
            $params[$k]['Mobile 2']             = $item->mobile_2;
            $params[$k]['Emergency Contact Name'] = $item->emergency_name;
            $params[$k]['Emergency Contact Relationship'] = $item->emergency_relationship;
            $params[$k]['Emergency Contact Number'] = $item->emergency_contact;
            $params[$k]['Email']                = $item->email;
            $params[$k]['Blood Type']           = $item->blood_type;

            if (!empty($item->shift_id)) {
                if ($abs = Shift::where('id', $item->shift_id)->first()) {
                    $params[$k]['Working Shift'] = $abs->name;
                } else {
                    $params[$k]['Working Shift'] = '';
                }
            } else {
                $params[$k]['Working Shift'] = '';
            }

            $params[$k]['Foreigners'] = $item->foreigners_status ? 'Yes' : 'No';
            $params[$k]['Country'] = $item->payroll_country_id ? $item->payrollCountry->name : '';

            $params[$k]['Employee Status'] = $item->organisasi_status;

            if (!$item->organisasi_status || $item->organisasi_status == 'Permanent') {
                $params[$k]['Status Contract'] = '';
                $params[$k]['Start Date Contract'] = '';
                $params[$k]['End Date Contract']   = '';
            } else {
                $params[$k]['Status Contract'] = $item->status_contract;
                $params[$k]['Start Date Contract'] = $item->start_date_contract;
                $params[$k]['End Date Contract']   = $item->end_date_contract;
            }

            if (!empty($item->cabang_id)) {
                $branch = Cabang::where('id', $item->cabang_id)->first();
                if ($branch)
                    $params[$k]['Branch'] = $branch->name;
                else
                    $params[$k]['Branch'] = '';
            } else {
                $params[$k]['Branch'] = '';
            }

            $params[$k]['Position (Position - Division - Title)'] = '';
            if (!empty($item->structure_organization_custom_id)) {
                $struct = StructureOrganizationCustom::where('id', $item->structure_organization_custom_id)->first();
                if ($struct) {
                    $pos = OrganisasiPosition::where('id', $struct->organisasi_position_id)->first();
                    $div = OrganisasiDivision::where('id', $struct->organisasi_division_id)->first();
                    $tit = OrganisasiTitle::where('id', $struct->organisasi_title_id)->first();
                    if ($pos) {
                        $params[$k]['Position (Position - Division - Title)'] = $pos->name;
                    }
                    if ($div) {
                        $params[$k]['Position (Position - Division - Title)'] .= ' - ' . $div->name;
                    }
                    if ($tit) {
                        $params[$k]['Position (Position - Division - Title)'] .= ' - ' . $tit->name;
                    }
                }
            }

            $pos = "";

            if (!empty($item->empore_organisasi_staff_id)) {
                $pos = "Staff";
            } elseif (empty($item->empore_organisasi_staff_id) and !empty($item->empore_organisasi_manager_id)) {
                $pos = "Manager";
            } elseif (empty($item->empore_organisasi_staff_id) and empty($item->empore_organisasi_manager_id) and !empty($item->empore_organisasi_direktur)) {
                $pos = "Direktur";
            }

            $params[$k]['Position']             = $pos;

            $jobrule = "";

            if (!empty($item->empore_organisasi_staff_id)) {
                $jobrule = isset($item->empore_staff->name) ? $item->empore_staff->name : '';
            } elseif (empty($item->empore_organisasi_staff_id) and !empty($item->empore_organisasi_manager_id)) {
                $jobrule = isset($item->empore_manager->name) ? $item->empore_manager->name : '';
            }

            $params[$k]['Job Rule']             = $jobrule;

            $params[$k]['Project'] = $item->custom_project_id ? $item->project->name : '';

            if (!empty($item->bank_id)) {
                $params[$k]['Bank ']                = $item->bank->name;
            } elseif (empty($item->bank_id)) {
                $params[$k]['Bank ']                = "";
            }

            $params[$k]['Bank Account Name']    = $item->nama_rekening;
            $params[$k]['Bank Account Number']  = $item->nomor_rekening;

            $cuti_kuota = [];
            $cuti_terpakai = [];
            $cuti_sisa = [];
            $dataCuti = UserCuti::where('user_id', $item->id)->get();
            // dd($dataCuti);
            if (count($dataCuti) > 0) {
                info("Cuti User $item->name");
                info($dataCuti);
                foreach ($dataCuti as $c) {
                    $cutiMaster = Cuti::where('id', $c->cuti_id)->first();
                    if ($cutiMaster && $cutiMaster->jenis_cuti == 'Annual Leave') {
                        array_push($cuti_kuota, $c->kuota);
                        array_push($cuti_terpakai, $c->cuti_terpakai);
                        array_push($cuti_sisa, $c->sisa_cuti);
                    }
                }
                // for($x = 0; $x <= count($dataCuti); $x++){
                //     $cutiMaster = Cuti::where('id', $dataCuti[$x]->cuti_id)->first();
                //     // dd($cutiMaster);
                //     if($cutiMaster){
                //         // dd($cutiMaster->jenis_cuti);
                //         if($cutiMaster->jenis_cuti == 'Annual Leave'){
                //             // dd('annual leave');
                //             $cutiBaru = UserCuti::where('cuti_id', $cutiMaster->id)->first();
                //             // dd($cuti_kuota);
                // array_push($cuti_kuota, $cutiBaru->kuota);
                // array_push($cuti_terpakai, $cutiBaru->cuti_terpakai);
                // array_push($cuti_sisa, $cutiBaru->sisa_cuti);
                //         }
                //     }
                // }
            }

            $sd = UserEducation::where('user_id', $item->id)->where('pendidikan', 'SD')->first();

            if (!empty($sd)) {
                $params[$k]['Education SD']           = $sd->pendidikan;
                $params[$k]['Start Year SD']          = $sd->tahun_awal;
                $params[$k]['End Year SD']            = $sd->tahun_akhir;
                $params[$k]['Institution SD']         = $sd->fakultas;
                $params[$k]['City Education SD']      = $sd->kota;
                $params[$k]['Major SD']               = $sd->jurusan;
                $params[$k]['GPA SD']                 = $sd->nilai;
            } else {
                $params[$k]['Education SD']           = "-";
                $params[$k]['Start Year SD']          = "-";
                $params[$k]['End Year SD']            = "-";
                $params[$k]['Institution SD']         = "-";
                $params[$k]['City Education SD']      = "-";
                $params[$k]['Major SD']               = "-";
                $params[$k]['GPA SD']                 = "-";
            }
            $smp = UserEducation::where('user_id', $item->id)->where('pendidikan', 'SMP')->first();
            if (!empty($smp)) {
                $params[$k]['Education SMP']           = $smp->pendidikan;
                $params[$k]['Start Year SMP']          = $smp->tahun_awal;
                $params[$k]['End Year SMP']            = $smp->tahun_akhir;
                $params[$k]['Institution SMP']         = $smp->fakultas;
                $params[$k]['City Education SMP']      = $smp->kota;
                $params[$k]['Major SMP']               = $smp->jurusan;
                $params[$k]['GPA SMP']                 = $smp->nilai;
            } else {
                $params[$k]['Education SMP']           = "-";
                $params[$k]['Start Year SMP']          = "-";
                $params[$k]['End Year SMP']            = "-";
                $params[$k]['Institution SMP']         = "-";
                $params[$k]['City Education SMP']      = "-";
                $params[$k]['Major SMP']               = "-";
                $params[$k]['GPA SMP']                 = "-";
            }

            $sma = UserEducation::where('user_id', $item->id)->where('pendidikan', 'SMA/SMK')->first();
            if (!empty($sma)) {
                $params[$k]['Education SMA/SMK']           = $sma->pendidikan;
                $params[$k]['Start Year SMA/SMK']          = $sma->tahun_awal;
                $params[$k]['End Year SMA/SMK']            = $sma->tahun_akhir;
                $params[$k]['Institution SMA/SMK']         = $sma->fakultas;
                $params[$k]['City Education SMA/SMK']      = $sma->kota;
                $params[$k]['Major SMA/SMK']               = $sma->jurusan;
                $params[$k]['GPA SMA/SMK']                 = $sma->nilai;
            } else {
                $params[$k]['Education SMA/SMK']           = "-";
                $params[$k]['Start Year SMA/SMK']          = "-";
                $params[$k]['End Year SMA/SMK']            = "-";
                $params[$k]['Institution SMA/SMK']         = "-";
                $params[$k]['City Education SMA/SMK']      = "-";
                $params[$k]['Major SMA/SMK']               = "-";
                $params[$k]['GPA SMA/SMK']                 = "-";
            }

            $diploma = UserEducation::where('user_id', $item->id)->where('pendidikan', 'D1')->first();
            if (!empty($diploma)) {
                $params[$k]['Education D1']           = $diploma->pendidikan;
                $params[$k]['Start Year D1']          = $diploma->tahun_awal;
                $params[$k]['End Year D1']            = $diploma->tahun_akhir;
                $params[$k]['Institution D1']         = $diploma->fakultas;
                $params[$k]['City Education D1']      = $diploma->kota;
                $params[$k]['Major D1']               = $diploma->jurusan;
                $params[$k]['GPA D1']                 = $diploma->nilai;
            } else {
                $params[$k]['Education D1']           = "-";
                $params[$k]['Start Year D1']          = "-";
                $params[$k]['End Year D1']            = "-";
                $params[$k]['Institution D1']         = "-";
                $params[$k]['City Education D1']      = "-";
                $params[$k]['Major D1']               = "-";
                $params[$k]['GPA D1']                 = "-";
            }

            $diploma2 = UserEducation::where('user_id', $item->id)->where('pendidikan', 'D2')->first();
            if (!empty($diploma2)) {
                $params[$k]['Education D2']           = $diploma2->pendidikan;
                $params[$k]['Start Year D2']          = $diploma2->tahun_awal;
                $params[$k]['End Year D2']            = $diploma2->tahun_akhir;
                $params[$k]['Institution D2']         = $diploma2->fakultas;
                $params[$k]['City Education D2']      = $diploma2->kota;
                $params[$k]['Major D2']               = $diploma2->jurusan;
                $params[$k]['GPA D2']                 = $diploma2->nilai;
            } else {
                $params[$k]['Education D2']           = "-";
                $params[$k]['Start Year D2']          = "-";
                $params[$k]['End Year D2']            = "-";
                $params[$k]['Institution D2']         = "-";
                $params[$k]['City Education D2']      = "-";
                $params[$k]['Major D2']               = "-";
                $params[$k]['GPA D2']                 = "-";
            }

            $diploma3 = UserEducation::where('user_id', $item->id)->where('pendidikan', 'D3')->first();
            if (!empty($diploma3)) {
                $params[$k]['Education D3']           = $diploma3->pendidikan;
                $params[$k]['Start Year D3']          = $diploma3->tahun_awal;
                $params[$k]['End Year D3']            = $diploma3->tahun_akhir;
                $params[$k]['Institution D3']         = $diploma3->fakultas;
                $params[$k]['City Education D3']      = $diploma3->kota;
                $params[$k]['Major D3']               = $diploma3->jurusan;
                $params[$k]['GPA D3']                 = $diploma3->nilai;
            } else {
                $params[$k]['Education D3']           = "-";
                $params[$k]['Start Year D3']          = "-";
                $params[$k]['End Year D3']            = "-";
                $params[$k]['Institution D3']         = "-";
                $params[$k]['City Education D3']      = "-";
                $params[$k]['Major D3']               = "-";
                $params[$k]['GPA D3']                 = "-";
            }

            $s1 = UserEducation::where('user_id', $item->id)->where('pendidikan', 'S1')->first();
            if (!empty($s1)) {
                $params[$k]['Education S1']           = $s1->pendidikan;
                $params[$k]['Start Year S1']          = $s1->tahun_awal;
                $params[$k]['End Year S1']            = $s1->tahun_akhir;
                $params[$k]['Institution S1']         = $s1->fakultas;
                $params[$k]['City Education S1']      = $s1->kota;
                $params[$k]['Major S1']               = $s1->jurusan;
                $params[$k]['GPA S1']                 = $s1->nilai;
            } else {
                $params[$k]['Education S1']           = "-";
                $params[$k]['Start Year S1']          = "-";
                $params[$k]['End Year S1']            = "-";
                $params[$k]['Institution S1']         = "-";
                $params[$k]['City Education S1']      = "-";
                $params[$k]['Major S1']               = "-";
                $params[$k]['GPA S1']                 = "-";
            }

            $s2 = UserEducation::where('user_id', $item->id)->where('pendidikan', 'S2')->first();
            if (!empty($s2)) {
                $params[$k]['Education S2']           = $s2->pendidikan;
                $params[$k]['Start Year S2']          = $s2->tahun_awal;
                $params[$k]['End Year S2']            = $s2->tahun_akhir;
                $params[$k]['Institution S2']         = $s2->fakultas;
                $params[$k]['City Education S2']      = $s2->kota;
                $params[$k]['Major S2']               = $s2->jurusan;
                $params[$k]['GPA S2']                 = $s2->nilai;
            } else {
                $params[$k]['Education S2']           = "-";
                $params[$k]['Start Year S2']          = "-";
                $params[$k]['End Year S2']            = "-";
                $params[$k]['Institution S2']         = "-";
                $params[$k]['City Education S2']      = "-";
                $params[$k]['Major S2']               = "-";
                $params[$k]['GPA S2']                 = "-";
            }

            $s3 = UserEducation::where('user_id', $item->id)->where('pendidikan', 'S3')->first();
            if (!empty($s3)) {
                $params[$k]['Education S3']           = $s3->pendidikan;
                $params[$k]['Start Year S3']          = $s3->tahun_awal;
                $params[$k]['End Year S3']            = $s3->tahun_akhir;
                $params[$k]['Institution S3']         = $s3->fakultas;
                $params[$k]['City Education S3']      = $s3->kota;
                $params[$k]['Major S3']               = $s3->jurusan;
                $params[$k]['GPA S3']                 = $s3->nilai;
            } else {
                $params[$k]['Education S3']           = "-";
                $params[$k]['Start Year S3']          = "-";
                $params[$k]['End Year S3']            = "-";
                $params[$k]['Institution S3']         = "-";
                $params[$k]['City Education S3']      = "-";
                $params[$k]['Major S3']               = "-";
                $params[$k]['GPA S3']                 = "-";
            }

            $ayah = UserFamily::where('user_id', $item->id)->where('hubungan', 'Ayah Kandung')->first();
            if (!empty($ayah)) {
                $params[$k]['Father\'s Relative Name']           = $ayah->nama;
                $params[$k]['Father\'s Contact Number']          = $ayah->contact;
                $params[$k]['Father\'s Place of birth']          = $ayah->tempat_lahir;
                $params[$k]['Father\'s Date of birth']           = $ayah->tanggal_lahir;
                $params[$k]['Father\'s Education level']         = $ayah->jenjang_pendidikan;
                $params[$k]['Father\'s Occupation']              = $ayah->pekerjaan;
            } else {
                $params[$k]['Father\'s Relative Name']           = "-";
                $params[$k]['Father\'s Contact Number']          = "-";
                $params[$k]['Father\'s Place of birth']          = "-";
                $params[$k]['Father\'s Date of birth']           = "-";
                $params[$k]['Father\'s Education level']         = "-";
                $params[$k]['Father\'s Occupation']              = "-";
            }
            $ibu = UserFamily::where('user_id', $item->id)->where('hubungan', 'Ibu Kandung')->first();
            if (!empty($ibu)) {
                $params[$k]['Mother\'s Relative Name']           = $ibu->nama;
                $params[$k]['Mother\'s Contact Number']          = $ibu->contact;
                $params[$k]['Mother\'s Place of birth']          = $ibu->tempat_lahir;
                $params[$k]['Mother\'s Date of birth']           = $ibu->tanggal_lahir;
                $params[$k]['Mother\'s Education level']         = $ibu->jenjang_pendidikan;
                $params[$k]['Mother\'s Occupation']              = $ibu->pekerjaan;
            } else {
                $params[$k]['Mother\'s Relative Name']           = "-";
                $params[$k]['Mother\'s Contact Number']          = "-";
                $params[$k]['Mother\'s Place of birth']          = "-";
                $params[$k]['Mother\'s Date of birth']           = "-";
                $params[$k]['Mother\'s Education level']         = "-";
                $params[$k]['Mother\'s Occupation']              = "-";
            }

            $istri = UserFamily::where('user_id', $item->id)->where('hubungan', 'Istri')->first();
            if (!empty($istri)) {
                $params[$k]['Wife\'s Relative Name']           = $istri->nama;
                $params[$k]['Wife\'s Contact Number']          = $istri->contact;
                $params[$k]['Wife\'s Place of birth']          = $istri->tempat_lahir;
                $params[$k]['Wife\'s Date of birth']           = $istri->tanggal_lahir;
                $params[$k]['Wife\'s Education level']         = $istri->jenjang_pendidikan;
                $params[$k]['Wife\'s Occupation']              = $istri->pekerjaan;
            } else {
                $params[$k]['Wife\'s Relative Name']           = "-";
                $params[$k]['Wife\'s Contact Number']          = "-";
                $params[$k]['Wife\'s Place of birth']          = "-";
                $params[$k]['Wife\'s Date of birth']           = "-";
                $params[$k]['Wife\'s Education level']         = "-";
                $params[$k]['Wife\'s Occupation']              = "-";
            }

            $suami = UserFamily::where('user_id', $item->id)->where('hubungan', 'Suami')->first();
            if (!empty($suami)) {
                $params[$k]['Husband\'s Relative Name']           = $suami->nama;
                $params[$k]['Husband\'s Contact Number']          = $suami->contact;
                $params[$k]['Husband\'s Place of birth']          = $suami->tempat_lahir;
                $params[$k]['Husband\'s Date of birth']           = $suami->tanggal_lahir;
                $params[$k]['Husband\'s Education level']         = $suami->jenjang_pendidikan;
                $params[$k]['Husband\'s Occupation']              = $suami->pekerjaan;
            } else {
                $params[$k]['Husband\'s Relative Name']           = "-";
                $params[$k]['Husband\'s Contact Number']          = "-";
                $params[$k]['Husband\'s Place of birth']          = "-";
                $params[$k]['Husband\'s Date of birth']           = "-";
                $params[$k]['Husband\'s Education level']         = "-";
                $params[$k]['Husband\'s Occupation']              = "-";
            }

            $anak1 = UserFamily::where('user_id', $item->id)->where('hubungan', 'Anak 1')->first();
            if (!empty($anak1)) {
                $params[$k]['1st Child\'s Relative Name']           = $anak1->nama;
                $params[$k]['1st Child\'s Contact Number']          = $anak1->contact;
                $params[$k]['1st Child\'s Place of birth']          = $anak1->tempat_lahir;
                $params[$k]['1st Child\'s Date of birth']           = $anak1->tanggal_lahir;
                $params[$k]['1st Child\'s Education level']         = $anak1->jenjang_pendidikan;
                $params[$k]['1st Child\'s Occupation']              = $anak1->pekerjaan;
            } else {
                $params[$k]['1st Child\'s Relative Name']           = "-";
                $params[$k]['1st Child\'s Contact Number']          = "-";
                $params[$k]['1st Child\'s Place of birth']          = "-";
                $params[$k]['1st Child\'s Date of birth']           = "-";
                $params[$k]['1st Child\'s Education level']         = "-";
                $params[$k]['1st Child\'s Occupation']              = "-";
            }

            $anak2 = UserFamily::where('user_id', $item->id)->where('hubungan', 'Anak 2')->first();
            if (!empty($anak2)) {
                $params[$k]['2nd Child\'s Relative Name']           = $anak2->nama;
                $params[$k]['2nd Child\'s Contact Number']          = $anak2->contact;
                $params[$k]['2nd Child\'s Place of birth']          = $anak2->tempat_lahir;
                $params[$k]['2nd Child\'s Date of birth']           = $anak2->tanggal_lahir;
                $params[$k]['2nd Child\'s Education level']         = $anak2->jenjang_pendidikan;
                $params[$k]['2nd Child\'s Occupation']              = $anak2->pekerjaan;
            } else {
                $params[$k]['2nd Child\'s Relative Name']           = "-";
                $params[$k]['2nd Child\'s Contact Number']          = "-";
                $params[$k]['2nd Child\'s Place of birth']          = "-";
                $params[$k]['2nd Child\'s Date of birth']           = "-";
                $params[$k]['2nd Child\'s Education level']         = "-";
                $params[$k]['2nd Child\'s Occupation']              = "-";
            }

            $anak3 = UserFamily::where('user_id', $item->id)->where('hubungan', 'Anak 3')->first();
            if (!empty($anak3)) {
                $params[$k]['3rd Child\'s Relative Name']           = $anak3->nama;
                $params[$k]['3rd Child\'s Contact Number']          = $anak3->contact;
                $params[$k]['3rd Child\'s Place of birth']          = $anak3->tempat_lahir;
                $params[$k]['3rd Child\'s Date of birth']           = $anak3->tanggal_lahir;
                $params[$k]['3rd Child\'s Education level']         = $anak3->jenjang_pendidikan;
                $params[$k]['3rd Child\'s Occupation']              = $anak3->pekerjaan;
            } else {
                $params[$k]['3rd Child\'s Relative Name']           = "-";
                $params[$k]['3rd Child\'s Contact Number']          = "-";
                $params[$k]['3rd Child\'s Place of birth']          = "-";
                $params[$k]['3rd Child\'s Date of birth']           = "-";
                $params[$k]['3rd Child\'s Education level']         = "-";
                $params[$k]['3rd Child\'s Occupation']              = "-";
            }

            $anak4 = UserFamily::where('user_id', $item->id)->where('hubungan', 'Anak 4')->first();
            if (!empty($anak4)) {
                $params[$k]['4th Child\'s Relative Name']           = $anak4->nama;
                $params[$k]['4th Child\'s Contact Number']          = $anak4->contact;
                $params[$k]['4th Child\'s Place of birth']          = $anak4->tempat_lahir;
                $params[$k]['4th Child\'s Date of birth']           = $anak4->tanggal_lahir;
                $params[$k]['4th Child\'s Education level']         = $anak4->jenjang_pendidikan;
                $params[$k]['4th Child\'s Occupation']              = $anak4->pekerjaan;
            } else {
                $params[$k]['4th Child\'s Relative Name']           = "-";
                $params[$k]['4th Child\'s Contact Number']          = "-";
                $params[$k]['4th Child\'s Place of birth']          = "-";
                $params[$k]['4th Child\'s Date of birth']           = "-";
                $params[$k]['4th Child\'s Education level']         = "-";
                $params[$k]['4th Child\'s Occupation']              = "-";
            }

            $anak5 = UserFamily::where('user_id', $item->id)->where('hubungan', 'Anak 5')->first();
            if (!empty($anak5)) {
                $params[$k]['5th Child\'s Relative Name']           = $anak5->nama;
                $params[$k]['5th Child\'s Contact Number']          = $anak5->contact;
                $params[$k]['5th Child\'s Place of birth']          = $anak5->tempat_lahir;
                $params[$k]['5th Child\'s Date of birth']           = $anak5->tanggal_lahir;
                $params[$k]['5th Child\'s Education level']         = $anak5->jenjang_pendidikan;
                $params[$k]['5th Child\'s Occupation']              = $anak5->pekerjaan;
            } else {
                $params[$k]['5th Child\'s Relative Name']           = "-";
                $params[$k]['5th Child\'s Contact Number']          = "-";
                $params[$k]['5th Child\'s Place of birth']          = "-";
                $params[$k]['5th Child\'s Date of birth']           = "-";
                $params[$k]['5th Child\'s Education level']         = "-";
                $params[$k]['5th Child\'s Occupation']              = "-";
            }

            $certification = UserCertification::where('user_id', $item->id)->get();
            for ($index=1; $index <= 10; $index++) { 
                $title = '';
                switch ($index) {
                    case 1:
                        $title = $index.'st';
                        break;
                    case 2:
                        $title = $index.'nd';
                        break;
                    case 3:
                        $title = $index.'rd';
                        break;
                    default:
                        $title = $index.'th';
                }
                if (!empty($certification[$index-1])) {
                    $params[$k][$title.' Training\'s Name']                 = $certification[$index-1]->name;
                    $params[$k][$title.' Training\'s Date']                 = $certification[$index-1]->date;
                    $params[$k][$title.' Training\'s Organizer']            = $certification[$index-1]->organizer;
                    $params[$k][$title.' Training\'s Certificate Number']   = $certification[$index-1]->certificate_number;
                    $params[$k][$title.' Training\'s Score']                = $certification[$index-1]->score;
                    $params[$k][$title.' Training\'s Description']          = $certification[$index-1]->description;
                } else {
                    $params[$k][$title.' Training\'s Name']                 = "-";
                    $params[$k][$title.' Training\'s Date']                 = "-";
                    $params[$k][$title.' Training\'s Organizer']            = "-";
                    $params[$k][$title.' Training\'s Certificate Number']   = "-";
                    $params[$k][$title.' Training\'s Score']                = "-";
                    $params[$k][$title.' Training\'s Description']          = "-";
                }
            }

            if(checkModuleAdmin(34)){
                $contract = UserContract::where('user_id', $item->id)->get();
                $company_url = session('company_url', 'umum') . '/';
                for ($index=1; $index <= 10; $index++) { 
                    $title = '';
                    switch ($index) {
                        case 1:
                            $title = $index.'st';
                            break;
                        case 2:
                            $title = $index.'nd';
                            break;
                        case 3:
                            $title = $index.'rd';
                            break;
                        default:
                            $title = $index.'th';
                    }
                    if (!empty($contract[$index-1])) {
                        $params[$k][$title.' Contract\'s Number']                 = $contract[$index-1]->number;
                        $params[$k][$title.' Contract\'s Type']                 = $contract[$index-1]->type;
                        $params[$k][$title.' Contract\'s Start Date']            = $contract[$index-1]->start_date;
                        $params[$k][$title.' Contract\'s End Date']   = $contract[$index-1]->end_date;
                        $params[$k][$title.' Contract\'s Sent Date']                = $contract[$index-1]->contract_sent;
                        $params[$k][$title.' Contract\'s Return Date']          = $contract[$index-1]->return_contract;
                    } else {
                        $params[$k][$title.' Contract\'s Number']                 = "-";
                        $params[$k][$title.' Contract\'s Type']                 = "-";
                        $params[$k][$title.' Contract\'s Start Date']            = "-";
                        $params[$k][$title.' Contract\'s End Date']   = "-";
                        $params[$k][$title.' Contract\'s Sent Date']                = "-";
                        $params[$k][$title.' Contract\'s Return Date']= "-";
                    }
                }
            }
            
            if(checkModuleAdmin(4)) {
                $params[$k]['Annual Leave Quota'] = array_sum($cuti_kuota);
                $params[$k]['Annual Leave Taken'] = array_sum($cuti_terpakai);
                $params[$k]['Annual Leave Balance'] = array_sum($cuti_sisa);
            }

            if(checkModuleAdmin(28)) {
                $params[$k]['Visit Type'] = $item->master_visit_type_id ? $item->VisitType->master_visit_type_name : '';
                $params[$k]['Visit Category'] = $item->master_category_visit_id ? $item->CategoryActivityVisit->master_category_name : '';
                $params[$k]['Visit Branch'] = '';
                foreach ($item->branchVisit as $key => $value) {
                    if($key != 0)
                        $params[$k]['Visit Branch'] = $params[$k]['Visit Branch'] . ', ';
                    $params[$k]['Visit Branch'] = $params[$k]['Visit Branch'] . $value->cabang->name;
                }
            }

            if(checkModuleAdmin(7)) {
                $params[$k]['Overtime Entitlement'] = $item->overtime_entitle ? 'Entitle Overtime' : 'Not Entitle Overtime';
                $params[$k]['Overtime Payment Setting'] = $item->overtime_payroll_id ? $item->overtimePayroll->name : '';
            }

            if(checkModuleAdmin(13)) {
                $params[$k]['Payroll UMR']  = $item->payroll_umr_id ? $item->payrollUMR->label : '';
                $params[$k]['Payroll Cycle']  = $item->payroll_cycle_id ? $item->payrollCycle->label : '';
                $params[$k]['Attendance Cycle']  = $item->attendance_cycle_id ? $item->attendanceCycle->label : '';
            }

            if(checkModuleAdmin(27)) {
                $params[$k]['Recruitment Entitlement'] = $item->recruitment_entitle ? 'Entitle Recruitment' : 'Not Entitle Recruitment';
            }
        }

        return (new \App\Models\KaryawanExport($params, 'Report Employee ' . date('d M Y')))->download('EM-HR.Report-Employee-' . date('d-m-Y') . '.xlsx');

        // $styleHeader = [
        //     'font' => [
        //         'bold' => true,
        //     ],
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        //     ],
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '000000'],
        //         ],
        //     ],
        //     'fill' => [
        //         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        //         'rotation' => 90,
        //         'startColor' => [
        //             'argb' => 'FFA0A0A0',
        //         ],
        //         'endColor' => [
        //             'argb' => 'FFFFFFFF',
        //         ],
        //     ],
        //     ''
        // ];
        // return \Excel::create('Report-Employee-'.date('d-m-Y'),  function($excel) use($params, $styleHeader){
        //       $excel->sheet('Karyawan',  function($sheet) use($params){

        //         $sheet->cell('A1:EJ1', function($cell) {
        //                 $cell->setFontSize(12);
        //                 $cell->setBackground('#EEEEEE');
        //                 $cell->setFontWeight('bold');
        //                 $cell->setBorder('solid');
        //             });


        //         $borderArray = array(
        //             'borders' => array(
        //                 'outline' => array(
        //                     'style' => \PHPExcel_Style_Border::BORDER_THICK,
        //                     'color' => array('argb' => 'FFFF0000'),
        //                 ),
        //             ),
        //         );

        //         $sheet->fromArray($params, null, 'A1', true);

        //       });

        //     $excel->getActiveSheet()->getStyle('A5:EI1')->applyFromArray($styleHeader);

        // })->download('xls');
    }

    public function downloadExcelLeave($data)
    {
        $params = [];
        $jenis = Cuti::select('jenis_cuti')->groupBy('jenis_cuti')->orderBy('jenis_cuti')->get();
        foreach ($data as $index => $item) {
            $params[$index]['No'] = $index + 1;
            $params[$index]['NIK'] = $item->nik;
            $params[$index]['Name'] = $item->name;

            $cuti_kuota = [0, 0, 0];
            $cuti_terpakai = [0, 0, 0];
            $cuti_sisa = [0, 0, 0];
            foreach ($jenis as $i => $j) {
                $cuti = Cuti::where('jenis_cuti', $j->jenis_cuti)->get();
                foreach ($cuti as $key => $value) {
                    $dataCuti = UserCuti::where('user_id', $item->id)->where('cuti_id', $value->id)->first();
                    if ($dataCuti) {
                        $cuti_kuota[$i] += $dataCuti->kuota;
                        $cuti_terpakai[$i] += $dataCuti->cuti_terpakai;
                        $cuti_sisa[$i] += $dataCuti->sisa_cuti;
                        $params[$index][$value->description.' Quota'] = $dataCuti->kuota ?: 0;
                        $params[$index][$value->description.' Taken'] = $dataCuti->cuti_terpakai ?: 0;
                        $params[$index][$value->description.' Balance'] = $dataCuti->sisa_cuti ?: 0;
                    } else {
                        $params[$index][$value->description.' Quota'] = '-';
                        $params[$index][$value->description.' Taken'] = '-';
                        $params[$index][$value->description.' Balance'] = '-';
                    }
                }
                $params[$index][$j->jenis_cuti.' Total Quota'] = $cuti_kuota[$i];
                $params[$index][$j->jenis_cuti.' Total Taken'] = $cuti_terpakai[$i];
                $params[$index][$j->jenis_cuti.' Total Balance'] = $cuti_sisa[$i];
            }
            $params[$index]['Total Quota'] = array_sum($cuti_kuota);
            $params[$index]['Total Taken'] = array_sum($cuti_terpakai);
            $params[$index]['Total Balance'] = array_sum($cuti_sisa);
        }

        return (new \App\Models\KaryawanExportLeave($params, 'Report Employee ' . date('d M Y')))->download('EM-HR.Report-Employee-' . date('d-m-Y') . '.xlsx');
    }

    public function downloadExcelContract($data)
    {
        $params = [];
        foreach ($data as $k => $item) {
            $params[$k]['No'] = $k + 1;
            $params[$k]['NIK'] = $item->nik;
            $params[$k]['Name'] = $item->name;

            if(checkModuleAdmin(34)){
                $contract = UserContract::where('user_id', $item->id)->get();
                $company_url = session('company_url', 'umum') . '/';
                for ($index=1; $index <= 10; $index++) { 
                    $title = '';
                    switch ($index) {
                        case 1:
                            $title = $index.'st';
                            break;
                        case 2:
                            $title = $index.'nd';
                            break;
                        case 3:
                            $title = $index.'rd';
                            break;
                        default:
                            $title = $index.'th';
                    }
                    if (!empty($contract[$index-1])) {
                        $params[$k]['Number '.$title]                 = $contract[$index-1]->number;
                        $params[$k]['Type '.$title]                 = $contract[$index-1]->type;
                        $params[$k]['Start Date '.$title]            = $contract[$index-1]->start_date;
                        $params[$k]['End Date '.$title]   = $contract[$index-1]->end_date;
                        $params[$k]['Sent Date '.$title]                = $contract[$index-1]->contract_sent;
                        $params[$k]['Return Date '.$title]          = $contract[$index-1]->return_contract;
                    } else {
                        $params[$k]['Number '.$title]                 = "-";
                        $params[$k]['Type '.$title]                 = "-";
                        $params[$k]['Start Date '.$title]            = "-";
                        $params[$k]['End Date '.$title]   = "-";
                        $params[$k]['Sent Date '.$title]                = "-";
                        $params[$k]['Return Date '.$title]                = "-";
                    }
                }
            }
        }

        return (new \App\Models\KaryawanExport($params, 'Report Employee ' . date('d M Y')))->download('EM-HR.Report-Employee-' . date('d-m-Y') . '.xlsx');
    }

    private function updateSchedule($user_id, $shift_id)
    {
        if ($user_id && $shift_id) {
            $item = ShiftScheduleChange::where('change_date', \Carbon\Carbon::today())->where('shift_id', $shift_id)->first();
            if (!$item) {
                $item = new ShiftScheduleChange();
                $item->change_date = \Carbon\Carbon::today();
                $item->shift_id = $shift_id;
                $item->save();
            }

            ShiftScheduleChangeEmployee::where('user_id', $user_id)->whereHas('shiftScheduleChange', function ($query) use ($item) {
                $query->where('change_date', '=', $item->change_date);
            })->delete();

            ShiftScheduleChangeEmployee::create([
                'user_id' => $user_id,
                'shift_schedule_change_id' => $item->id,
            ]);
        }
    }

    private function diffInMonths($date1, $date2) {
        return $date1->diff($date2)->m + ($date1->diff($date2)->d >= 28 && $date1->format("d") == $date2->format("d") ? 1 : 0);
    }

    public function getannualcutikouta($cuti_id, $join_date)
    {
        $cuti = Cuti::where("id", $cuti_id)->first();
        if ($cuti->jenis_cuti != "Annual Leave") {
            return $cuti->kuota;
        }
        
        $quota = 0;

        $currentDate = \Carbon\Carbon::now()->startOfDay();
        $nextStartOfMonth = \Carbon\Carbon::now()->addMonth()->startOfMonth();
        $joinDate = \Carbon\Carbon::parse($join_date)->startOfDay();

        $lastCutOffAnnually =  \Carbon\Carbon::now()->startOfYear();
        $lastCutOffAnniversary = \Carbon\Carbon::parse($currentDate->format("Y") . "-" . $joinDate->format("m") . "-" . $joinDate->format("d"))->startOfDay();
        if ($currentDate->lt($lastCutOffAnniversary)) {
            $lastCutOffAnniversary->subYear();
        }
        $lastCutOffCustom = explode('-', $cuti->cutoffmonth);
        if (count($lastCutOffCustom) == 2) {
            $lastCutOffCustom = \Carbon\Carbon::createFromDate($currentDate->format("Y"), $lastCutOffCustom[1], $lastCutOffCustom[0])->startOfDay();
            if ($currentDate->lt($lastCutOffCustom)) {
                $lastCutOffCustom->subYear();
            }
        }

        if ($cuti->master_cuti_type_id == 1)
            $lastCutOff = $lastCutOffAnnually;
        else if ($cuti->master_cuti_type_id == 2)
            $lastCutOff = $lastCutOffAnniversary;
        else if ($cuti->master_cuti_type_id == 3) {
            if ($currentDate->diffInYears($joinDate) >= 1 && $currentDate->gte(\Carbon\Carbon::parse($join_date)->addYears(2)->startOfYear()))
                $lastCutOff = $lastCutOffAnnually->subYear();
            else
                $lastCutOff = $lastCutOffAnniversary;
        }
        else if ($cuti->master_cuti_type_id == 4 || $cuti->master_cuti_type_id == 5)
            $lastCutOff = $lastCutOffCustom;

        if ($cuti->master_cuti_type_id != 4 && $joinDate->lt($lastCutOff))
            $quota = $joinDate->diffInYears($lastCutOff) >= 1 ? $cuti->kuota : floor(($this->diffInMonths($joinDate, $lastCutOff) + ($joinDate->format("d") == $lastCutOff->format("d") ? 0 : 1)) / 12 * $cuti->kuota);
        else if ($cuti->master_cuti_type_id == 4 && $joinDate->lt($nextStartOfMonth))
            $quota = ($joinDate->lt($lastCutOff) ? $this->diffInMonths($lastCutOff, $nextStartOfMonth) : $this->diffInMonths($joinDate, $nextStartOfMonth) + ($joinDate->format("d") == "01" ? 0 : 1)) * $cuti->kuota;
    
        if ($cuti->iscarryforward) {
            $carryForward = 0;
            if ($cuti->master_cuti_type_id == 3)
                $lastCutOff = ($currentDate->gte(\Carbon\Carbon::parse($join_date)->addYears(3)->startOfYear()) ? $lastCutOffAnnually->addYear() : ($currentDate->lte(\Carbon\Carbon::parse($join_date)->addYears(2)->startOfYear()) || $currentDate->diffInYears($joinDate) >= 2 ? $lastCutOffAnniversary : $lastCutOffAnniversary->addYear()));
            if ($cuti->master_cuti_type_id != 4 && $joinDate->lt($lastCutOff->subYear()))
                $carryForward = ($joinDate->diffInYears($lastCutOff) * $cuti->kuota) + floor((($this->diffInMonths($joinDate, $lastCutOff) + ($joinDate->format("d") == $lastCutOff->format("d") ? 0 : 1)) % 12) / 12 * $cuti->kuota);
            else if ($cuti->master_cuti_type_id == 4 && $joinDate->lt($lastCutOff)) {
                if ($lastCutOff->format("d") == "01")
                    $lastCutOff->subMonth();
                else
                    $lastCutOff->startOfMonth();
                $carryForward = ($lastCutOff->lt($joinDate) ? 1 : ($joinDate->diffInYears($lastCutOff) * 12) + $this->diffInMonths($lastCutOff, $joinDate) + ($joinDate->format("d") == "01" ? 1 : 2)) * $cuti->kuota;
            }
            $quota += $carryForward > $cuti->carryforwardleave ? $cuti->carryforwardleave : $carryForward;
        }

        return $quota;
    }
}
