<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Payroll;
use App\User;
use App\Models\Bank;
use App\Models\PayrollHistory;
use App\Models\PayrollOthers;
use App\Models\PayrollPtkp;
use App\Models\PayrollEarningsEmployee;
use App\Models\PayrollEarningsEmployeeHistory;
use App\Models\PayrollDeductionsEmployee;
use App\Models\PayrollDeductionsEmployeeHistory;
use App\Models\PayrollEarnings;
use App\Models\PayrollDeductions;
use App\Models\RequestPaySlip;
use App\Models\RequestPaySlipItem;
use App\Models\OrganisasiDivision;
use App\Models\OrganisasiPosition;
use App\Models\PayrollNpwp;

class PayrollController2 extends Controller
{   

	public function __construct(\Maatwebsite\Excel\Excel $excel)
	{
	    parent::__construct();
	    $this->excel = $excel;
	}

    public function getCalculatePayroll(Request $request)
    {
        $biaya_jabatan  = PayrollOthers::where('id', 1)->first()->value;
        $upah_minimum   = PayrollOthers::where('id', 2)->first()->value;
        $bpjs_pensiunan_batas = PayrollOthers::where('id', 3)->first()->value;
        $bpjs_kesehatan_batas = PayrollOthers::where('id', 4)->first()->value;

        $params = [];
        if($request->ajax())
        {
            $request->salary    = replace_idr($request->salary);

            //Jika salary dibawah UMP, maka salary untuk perhitungan BPJS berdasarkan UMP
            if($request->salary && $request->salary != 0 && $request->salary<$upah_minimum){
                $salary=$upah_minimum;
            }
            else{
                $salary=$request->salary;
            }
            $payroll_type = $request->payroll_type;

            $request->bonus     = replace_idr($request->bonus);
            $request->thr     = replace_idr($request->thr);

            //JHT EMPLOYEE
            $bpjs_ketenagakerjaan2_persen = get_setting('bpjs_jaminan_jht_employee');
            $bpjs_ketenagakerjaan2 = ($salary * $bpjs_ketenagakerjaan2_persen / 100);
            // start custom
            if($request->edit_bpjs_ketenagakerjaan_employee != 0 )
            {
                if(replace_idr($request->bpjs_ketenagakerjaan_employee) != $bpjs_ketenagakerjaan2)
                {
                    $bpjs_ketenagakerjaan2 = replace_idr($request->bpjs_ketenagakerjaan_employee);
                }
            }
            // end custom
            //JHT COMPANY
            $bpjs_jht_company_persen = get_setting('bpjs_jht_company');
            $bpjs_jht_company = ($salary * $bpjs_jht_company_persen / 100);
            // start custom
            if($request->edit_bpjs_jht_company != 0 )
            {
                if(replace_idr($request->bpjs_jht_company) != $bpjs_jht_company)
                {
                    $bpjs_jht_company = replace_idr($request->bpjs_jht_company);
                }
            }
            // end custom
            //JP EMPLOYEE
            $bpjs_pensiun2        = 0;
            $bpjs_pensiun2_persen = get_setting('bpjs_jaminan_jp_employee');
            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun2     = ($salary * $bpjs_pensiun2_persen / 100);
            }
            else
            {
                $bpjs_pensiun2     = ($bpjs_pensiunan_batas * $bpjs_pensiun2_persen / 100);
            }
            // start custom
            if($request->edit_edit_bpjs_pensiun_employee != 0)
            {
                if(replace_idr($request->bpjs_pensiun_employee) != $bpjs_pensiun2)
                {
                    $bpjs_pensiun2 = replace_idr($request->bpjs_pensiun_employee);
                }
            }
            // end custom
            //JP COMPANY
            $bpjs_pensiun_company_persen = get_setting('bpjs_pensiun_company');
            $bpjs_pensiun_company = 0;

            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun_company     = ($salary * $bpjs_pensiun_company_persen / 100);
            }
            else
            {
                $bpjs_pensiun_company     = ($bpjs_pensiunan_batas * $bpjs_pensiun_company_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_pensiun_company != 0 )
            {
                if(replace_idr($request->bpjs_pensiun_company) != $bpjs_pensiun_company)
                {
                    $bpjs_pensiun_company = replace_idr($request->bpjs_pensiun_company);
                }
            }
            // end custom
            //KESEHATAN EMPLOYEE
            $bpjs_kesehatan2        = 0;
            $bpjs_kesehatan2_persen  = get_setting('bpjs_kesehatan_employee');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan2     = ($salary * $bpjs_kesehatan2_persen / 100);
            }
            else
            {
                $bpjs_kesehatan2     = ($bpjs_kesehatan_batas * $bpjs_kesehatan2_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_kesehatan_employee !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_employee) != $bpjs_kesehatan2)
                {
                    $bpjs_kesehatan2 = replace_idr($request->bpjs_kesehatan_employee);
                }
            }
            // end custom
            //KESEHATAN COMPANY
            $bpjs_kesehatan_company = 0;
            $bpjs_kesehatan_company_persen = get_setting('bpjs_kesehatan_company');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan_company     = ($salary * $bpjs_kesehatan_company_persen / 100);
            }
            else
            {
                $bpjs_kesehatan_company     = ($bpjs_kesehatan_batas * $bpjs_kesehatan_company_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_kesehatan_company !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_company) != $bpjs_kesehatan_company)
                {
                    $bpjs_kesehatan_company = replace_idr($request->bpjs_kesehatan_company);
                }
            }
            // end custom
            //JKK COMPANY
            $bpjs_jkk_company_persen = get_setting('bpjs_jkk_company');
            $bpjs_jkk_company = ($salary * $bpjs_jkk_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkk_company != 0 )
            {
                if(replace_idr($request->bpjs_jkk_company) != $bpjs_jkk_company)
                {
                    $bpjs_jkk_company = replace_idr($request->bpjs_jkk_company);
                }
            }
            // end custom

            //JKM COMPANY
            $bpjs_jkm_company_persen = get_setting('bpjs_jkm_company');
            $bpjs_jkm_company = ($salary * $bpjs_jkm_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkm_company != 0 )
            {
                if(replace_idr($request->bpjs_jkm_company) != $bpjs_jkm_company)
                {
                    $bpjs_jkm_company = replace_idr($request->bpjs_jkm_company);
                }
            }
            // end custom
            $bpjstotalearning = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_jht_company + $bpjs_pensiun_company + $bpjs_kesehatan_company;
            //$bpjspenambahan = $bpjstotalearning;
            //$bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2 +$bpjs_kesehatan2 + $bpjstotalearning;

            $bpjspenambahan = $bpjs_jkk_company + $bpjs_jkm_company+$bpjs_kesehatan_company;
            $bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2;

            $earnings               = 0;
            $taxable_earning        = 0;
            if(isset($request->earnings))
            {
                foreach($request->earnings as $index => $item)
                {
                    $earnings += replace_idr($item);

                    $earningItem = PayrollEarnings::find($request->earning_items[$index]);
                    if($earningItem && $earningItem->taxable == 1){
                        $taxable_earning += replace_idr($item);
                    }
                }
            }
            info($request->deduction_items);
            info($request->deductions);
            $deductions             = 0;
            $taxable_deduction      = 0;
            if(isset($request->deductions))
            {
                foreach($request->deductions as $index => $item)
                {
                    $deductions += replace_idr($item);

                    $deductionItem = PayrollDeductions::find($request->deduction_items[$index]);
                    if($deductionItem && $deductionItem->taxable == 1){
                        $taxable_deduction += replace_idr($item);
                    }
                }
            }



//            $gross_income           = (($request->salary + $earnings + $bpjspenambahan) * 12) + $request->bonus + $request->thr;
            $gross_income           = (($request->salary + $taxable_earning + $bpjspenambahan) * 12) + $request->bonus + $request->thr;
            // burdern allowance
//            $burden_allow           = 5 *  ($request->salary + $earnings + $bpjspenambahan + $request->bonus) / 100;
            $burden_allowYear           = 5*($gross_income)/100;
            $burden_allow = $burden_allowYear/12;


            $biaya_jabatan_bulan    = $biaya_jabatan / 12;

            if($burden_allow > $biaya_jabatan_bulan)
            {
                $burden_allow = $biaya_jabatan_bulan;
            }

//            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12);
            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12) + ($taxable_deduction * 12);
            //$net_yearly_income          = $gross_income - $total_deduction;
            $net_yearly_val          = $gross_income - $total_deduction;
            $net_yearly_ratusan      = substr($net_yearly_val, -3);
            $net_yearly_income       = $net_yearly_val - $net_yearly_ratusan;

            $untaxable_income = 0;

            $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();
            if($request->jenis_kelamin == 'Female' || $request->jenis_kelamin == "")
            {
                $untaxable_income = $ptkp->bujangan_wanita;
            }elseif ($request->jenis_kelamin == 'Male') {
                # code...
                if($request->marital_status == 'Bujangan/Wanita' || $request->marital_status == "")
                {
                    $untaxable_income = $ptkp->bujangan_wanita;
                }
                if($request->marital_status == 'Menikah')
                {
                    $untaxable_income = $ptkp->menikah;
                }
                if($request->marital_status == 'Menikah Anak 1')
                {
                    $untaxable_income = $ptkp->menikah_anak_1;
                }
                if($request->marital_status == 'Menikah Anak 2')
                {
                    $untaxable_income = $ptkp->menikah_anak_2;
                }
                if($request->marital_status == 'Menikah Anak 3')
                {
                    $untaxable_income = $ptkp->menikah_anak_3;
                }
            }

            //$taxable_yearly_income = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_val     = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_ratusan = substr($taxable_yearly_income_val, -3);
            $taxable_yearly_income         = $taxable_yearly_income_val - $taxable_yearly_income_ratusan;

            $pph_setting_1  = \App\Models\PayrollPPH::where('id', 1)->first();
            // Perhitungan 5 persen
            $income_tax_calculation_5 = 0;
            if($taxable_yearly_income < 0)
            {
                $income_tax_calculation_5 = 0;
            }
            elseif($taxable_yearly_income <= $pph_setting_1->batas_atas)
            {
                $income_tax_calculation_5 = ($pph_setting_1->tarif / 100) * $taxable_yearly_income;
            }
            if($taxable_yearly_income >= $pph_setting_1->batas_atas)
            {
                $income_tax_calculation_5 = ($pph_setting_1->tarif / 100) * $pph_setting_1->batas_atas;
            }

            $pph_setting_2  = \App\Models\PayrollPPH::where('id', 2)->first();
            // Perhitungan 15 persen
            $income_tax_calculation_15 = 0;
            if($taxable_yearly_income >= $pph_setting_2->batas_atas)
            {
                $income_tax_calculation_15 = ($pph_setting_2->tarif / 100) * ($pph_setting_2->batas_atas - $pph_setting_2->batas_bawah);
            }
            if($taxable_yearly_income >= $pph_setting_2->batas_bawah and $taxable_yearly_income <= $pph_setting_2->batas_atas)
            {
                $income_tax_calculation_15 = ($pph_setting_2->tarif / 100) * ($taxable_yearly_income - $pph_setting_2->batas_bawah);
            }

            $pph_setting_3  = \App\Models\PayrollPPH::where('id', 3)->first();
            // Perhitungan 25 persen
            $income_tax_calculation_25 = 0;
            if($taxable_yearly_income >= $pph_setting_3->batas_atas)
            {
                $income_tax_calculation_25 = ($pph_setting_3->tarif / 100)  * ($pph_setting_3->batas_atas - $pph_setting_3->batas_bawah);
            }

            if($taxable_yearly_income <= $pph_setting_3->batas_atas and $taxable_yearly_income >= $pph_setting_3->batas_bawah)
            {
                $income_tax_calculation_25 = ($pph_setting_3->tarif / 100) * ($taxable_yearly_income - $pph_setting_3->batas_bawah);
            }

            $pph_setting_4  = \App\Models\PayrollPPH::where('id', 4)->first();
            $income_tax_calculation_30 = 0;
            if($taxable_yearly_income >= $pph_setting_4->batas_atas)
            {
                $income_tax_calculation_30 = ($pph_setting_4->tarif / 100) * ($taxable_yearly_income - $pph_setting_4->batas_bawah);
            }

            $yearly_income_tax              = $income_tax_calculation_5 + $income_tax_calculation_15 + $income_tax_calculation_25 + $income_tax_calculation_30;

            $monthly_income_tax             = $yearly_income_tax / 12;

            $gross_income_per_month         = ($request->salary + $earnings + $bpjspenambahan)  + $request->bonus + $request->thr;//$gross_income / 12;

            $less                           = $bpjspengurangan + $monthly_income_tax;
//            $gross_thp                      = ($request->salary + $earnings + $request->bonus);




            $params['untaxable_income']     = number_format($untaxable_income);
            $params['gross_income']         = number_format($gross_income);
            $params['burden_allow']         = number_format($burden_allow);
            //$params['bpjs_ketenagakerjaan'] = number_format($bpjs_ketenagakerjaan);
            $params['bpjs_ketenagakerjaan2'] = number_format($bpjs_ketenagakerjaan2);
            //$params['bpjs_kesehatan']         = number_format($bpjs_kesehatan);
            $params['bpjs_kesehatan2']        = number_format($bpjs_kesehatan2);
            //$params['bpjs_pensiun']         = number_format($bpjs_pensiun);
            $params['bpjs_pensiun2']        = number_format($bpjs_pensiun2);

            $params['bpjs_jkk_company']         = number_format($bpjs_jkk_company);
            $params['bpjs_jkm_company']         = number_format($bpjs_jkm_company);
            $params['bpjs_jht_company']         = number_format($bpjs_jht_company);
            $params['bpjs_pensiun_company']     = number_format($bpjs_pensiun_company);
            $params['bpjs_kesehatan_company']   = number_format($bpjs_kesehatan_company);
            $params['bpjstotalearning']         = number_format($bpjstotalearning);

            $params['total_deduction']      = number_format($total_deduction);
            $params['net_yearly_income']    = number_format($net_yearly_income);
            $params['untaxable_income']     = number_format($untaxable_income);
            $params['taxable_yearly_income']        = number_format($taxable_yearly_income);
            $params['income_tax_calculation_5']     = number_format($income_tax_calculation_5);
            $params['income_tax_calculation_15']    = number_format($income_tax_calculation_15);
            $params['income_tax_calculation_25']    = number_format($income_tax_calculation_25);
            $params['income_tax_calculation_30']    = number_format($income_tax_calculation_30);
            $params['yearly_income_tax']            = number_format($yearly_income_tax);
            $params['gross_income_per_month']       = number_format($gross_income_per_month);
            $params['less']                         = number_format($less);

            $non_bonus = $this->getCalculatePayrollNonBonus($request);

            $params['yearly_income_tax_non_bonus']  = $non_bonus['yearly_income_tax'];
            $params['monthly_income_tax']           = $yearly_income_tax - $non_bonus['yearly_income_tax'] + ($non_bonus['yearly_income_tax'] / 12);
            if($payroll_type == 'NET')
                $thp = ($request->salary + $request->bonus + $request->thr + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $bpjstotalearning);
            else
                $thp = ($request->salary + $request->bonus + $request->thr + $earnings + $bpjstotalearning) - ($deductions + $bpjs_ketenagakerjaan2 + $bpjs_kesehatan2 + $bpjs_pensiun2 + $params['monthly_income_tax'] + $bpjstotalearning);

            $params['monthly_income_tax']           = number_format($params['monthly_income_tax']);
            // start custom



            $params['thp']                          = number_format($thp);
            // end custom
            $params['bpjs_pengurang']               = number_format($bpjspengurangan);
            $params['bpjs_penambahan']              = number_format($bpjspenambahan);
        }

        return response()->json($params);
    }

    /**
     * Calcualte Payroll Non Bonus
     * @param  Request $request
     * @return json
     */
    public function getCalculatePayrollNonBonus($request)
    {
        $biaya_jabatan  = PayrollOthers::where('id', 1)->first()->value;
        $upah_minimum   = PayrollOthers::where('id', 2)->first()->value;
        $bpjs_pensiunan_batas = PayrollOthers::where('id', 3)->first()->value;
        $bpjs_kesehatan_batas = PayrollOthers::where('id', 4)->first()->value;

        $params = [];
        if($request->ajax())
        {
            $request->salary    = replace_idr($request->salary);

            //Jika salary dibawah UMP, maka salary untuk perhitungan BPJS berdasarkan UMP
            if($request->salary && $request->salary != 0 && $request->salary<$upah_minimum){
                $salary=$upah_minimum;
            }
            else{
                $salary=$request->salary;
            }

            $request->bonus     = replace_idr($request->bonus);
            $request->thr     = replace_idr($request->thr);

            //JHT EMPLOYEE
            $bpjs_ketenagakerjaan2_persen = get_setting('bpjs_jaminan_jht_employee');
            $bpjs_ketenagakerjaan2 = ($salary * $bpjs_ketenagakerjaan2_persen / 100);
            // start custom
            if($request->edit_bpjs_ketenagakerjaan_employee != 0 )
            {
                if(replace_idr($request->bpjs_ketenagakerjaan_employee) != $bpjs_ketenagakerjaan2)
                {
                    $bpjs_ketenagakerjaan2 = replace_idr($request->bpjs_ketenagakerjaan_employee);
                }
            }
            // end custom

            //JHT COMPANY
            $bpjs_jht_company_persen = get_setting('bpjs_jht_company');
            $bpjs_jht_company = ($salary * $bpjs_jht_company_persen / 100);
            // start custom
            if($request->edit_bpjs_jht_company != 0 )
            {
                if(replace_idr($request->bpjs_jht_company) != $bpjs_jht_company)
                {
                    $bpjs_jht_company = replace_idr($request->bpjs_jht_company);
                }
            }
            // end custom

            //JP EMPLOYEE
            $bpjs_pensiun2        = 0;
            $bpjs_pensiun2_persen = get_setting('bpjs_jaminan_jp_employee');

            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun2     = ($salary * $bpjs_pensiun2_persen / 100);
            }
            else
            {
                $bpjs_pensiun2     = ($bpjs_pensiunan_batas * $bpjs_pensiun2_persen / 100);
            }

            // start custom
            if($request->edit_edit_bpjs_pensiun_employee != 0)
            {
                if(replace_idr($request->bpjs_pensiun_employee) != $bpjs_pensiun2)
                {
                    $bpjs_pensiun2 = replace_idr($request->bpjs_pensiun_employee);
                }
            }
            // end custom

            //JP COMPANY
            $bpjs_pensiun_company_persen = get_setting('bpjs_pensiun_company');
            $bpjs_pensiun_company = 0;

            if($salary <= $bpjs_pensiunan_batas)
            {
                $bpjs_pensiun_company     = ($salary * $bpjs_pensiun_company_persen / 100);
            }
            else
            {
                $bpjs_pensiun_company     = ($bpjs_pensiunan_batas * $bpjs_pensiun_company_persen / 100);
            }
            // start custom
            if($request->edit_bpjs_pensiun_company != 0 )
            {
                if(replace_idr($request->bpjs_pensiun_company) != $bpjs_pensiun_company)
                {
                    $bpjs_pensiun_company = replace_idr($request->bpjs_pensiun_company);
                }
            }
            // end custom

            //KESEHATAN EMPLOYEE
            $bpjs_kesehatan2        = 0;
            $bpjs_kesehatan2_persen  = get_setting('bpjs_kesehatan_employee');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan2     = ($salary * $bpjs_kesehatan2_persen / 100);
            }
            else
            {
                $bpjs_kesehatan2     = ($bpjs_kesehatan_batas * $bpjs_kesehatan2_persen / 100);
            }

            // start custom
            if($request->edit_bpjs_kesehatan_employee !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_employee) != $bpjs_kesehatan2)
                {
                    $bpjs_kesehatan2 = replace_idr($request->bpjs_kesehatan_employee);
                }
            }
            // end custom

            //KESEHATAN COMPANY
            $bpjs_kesehatan_company = 0;
            $bpjs_kesehatan_company_persen = get_setting('bpjs_kesehatan_company');
            if($salary <= $bpjs_kesehatan_batas)
            {
                $bpjs_kesehatan_company     = ($salary * $bpjs_kesehatan_company_persen / 100);
            }
            else
            {
                $bpjs_kesehatan_company     = ($bpjs_kesehatan_batas * $bpjs_kesehatan_company_persen / 100);
            }

            // start custom
            if($request->edit_bpjs_kesehatan_company !=0 )
            {
                if(replace_idr($request->bpjs_kesehatan_company) != $bpjs_kesehatan_company)
                {
                    $bpjs_kesehatan_company = replace_idr($request->bpjs_kesehatan_company);
                }
            }
            // end custom

            //JKK COMPANY
            $bpjs_jkk_company_persen = get_setting('bpjs_jkk_company');
            $bpjs_jkk_company = ($salary * $bpjs_jkk_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkk_company != 0 )
            {
                if(replace_idr($request->bpjs_jkk_company) != $bpjs_jkk_company)
                {
                    $bpjs_jkk_company = replace_idr($request->bpjs_jkk_company);
                }
            }
            // end custom

            //JKM COMPANY
            $bpjs_jkm_company_persen = get_setting('bpjs_jkm_company');
            $bpjs_jkm_company = ($salary * $bpjs_jkm_company_persen / 100);

            // start custom
            if($request->edit_bpjs_jkm_company != 0 )
            {
                if(replace_idr($request->bpjs_jkm_company) != $bpjs_jkm_company)
                {
                    $bpjs_jkm_company = replace_idr($request->bpjs_jkm_company);
                }
            }
            // end custom
            $bpjstotalearning = $bpjs_jkk_company + $bpjs_jkm_company + $bpjs_jht_company + $bpjs_pensiun_company + $bpjs_kesehatan_company;
            //$bpjspenambahan = $bpjstotalearning;
            //$bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2 +$bpjs_kesehatan2 + $bpjstotalearning;

            $bpjspenambahan = $bpjs_jkk_company + $bpjs_jkm_company+$bpjs_kesehatan_company;
            $bpjspengurangan = $bpjs_ketenagakerjaan2 + $bpjs_pensiun2;

            $earnings = 0;
            $taxable_earning = 0;
            if(isset($request->earnings))
            {
                foreach($request->earnings as $index => $item)
                {
                    $earnings += replace_idr($item);

                    $earningItem = PayrollEarnings::find($request->earning_items[$index]);
                    if($earningItem && $earningItem->taxable == 1){
                        $taxable_earning += replace_idr($item);
                    }

                }
            }

            $deductions             = 0;
            $taxable_deduction      = 0;
            if(isset($request->deductions))
            {
                foreach($request->deductions as $index => $item)
                {
                    $deductions += replace_idr($item);

                    $deductionItem = PayrollDeductions::find($request->deduction_items[$index]);
                    if($deductionItem && $deductionItem->taxable == 1){
                        $taxable_deduction += replace_idr($item);
                    }
                }
            }

//            $gross_income           = ($request->salary + $earnings + $bpjspenambahan) * 12;
            $gross_income           = ($request->salary + $taxable_earning + $bpjspenambahan) * 12;

            // burdern allowance
//            $burden_allow = 5 * ($request->salary + $earnings + $bpjspenambahan) / 100;
            $burden_allowYear           = 5*($gross_income)/100;
            $burden_allow = $burden_allowYear/12;

            $biaya_jabatan_bulan    = $biaya_jabatan / 12;

            if($burden_allow > $biaya_jabatan_bulan)
            {
                $burden_allow = $biaya_jabatan_bulan;
            }

//            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12);
            $total_deduction = ($bpjspengurangan * 12) + ($burden_allow * 12) + ($taxable_deduction * 12);

            //$net_yearly_income          = $gross_income - $total_deduction;
            $net_yearly_val          = $gross_income - $total_deduction;
            $net_yearly_ratusan      = substr($net_yearly_val, -3);
            $net_yearly_income       = $net_yearly_val - $net_yearly_ratusan;

            $untaxable_income = 0;

            $ptkp = \App\Models\PayrollPtkp::where('id', 1)->first();
            if($request->jenis_kelamin == 'Female' || $request->jenis_kelamin == "")
            {
                $untaxable_income = $ptkp->bujangan_wanita;
            }elseif ($request->jenis_kelamin == 'Male') {
                if($request->marital_status == 'Bujangan/Wanita' || $request->marital_status == "")
                {
                    $untaxable_income = $ptkp->bujangan_wanita;
                }
                if($request->marital_status == 'Menikah')
                {
                    $untaxable_income = $ptkp->menikah;
                }
                if($request->marital_status == 'Menikah Anak 1')
                {
                    $untaxable_income = $ptkp->menikah_anak_1;
                }
                if($request->marital_status == 'Menikah Anak 2')
                {
                    $untaxable_income = $ptkp->menikah_anak_2;
                }
                if($request->marital_status == 'Menikah Anak 3')
                {
                    $untaxable_income = $ptkp->menikah_anak_3;
                }
            }

            //$taxable_yearly_income = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_val     = $net_yearly_income - $untaxable_income;
            $taxable_yearly_income_ratusan = substr($taxable_yearly_income_val, -3);
            $taxable_yearly_income         = $taxable_yearly_income_val - $taxable_yearly_income_ratusan;

            $pph_setting_1  = \App\Models\PayrollPPH::where('id', 1)->first();
            // Perhitungan 5 persen
            $income_tax_calculation_5 = 0;
            if($taxable_yearly_income < 0)
            {
                $income_tax_calculation_5 = 0;
            }
            elseif($taxable_yearly_income <= $pph_setting_1->batas_atas)
            {
                $income_tax_calculation_5 = ($pph_setting_1->tarif / 100) * $taxable_yearly_income;
            }
            if($taxable_yearly_income >= $pph_setting_1->batas_atas)
            {
                $income_tax_calculation_5 = ($pph_setting_1->tarif / 100) * $pph_setting_1->batas_atas;
            }

            $pph_setting_2  = \App\Models\PayrollPPH::where('id', 2)->first();
            // Perhitungan 15 persen
            $income_tax_calculation_15 = 0;
            if($taxable_yearly_income >= $pph_setting_2->batas_atas)
            {
                $income_tax_calculation_15 = ($pph_setting_2->tarif / 100) * ($pph_setting_2->batas_atas - $pph_setting_2->batas_bawah);
            }
            if($taxable_yearly_income >= $pph_setting_2->batas_bawah and $taxable_yearly_income <= $pph_setting_2->batas_atas)
            {
                $income_tax_calculation_15 = ($pph_setting_2->tarif / 100) * ($taxable_yearly_income - $pph_setting_2->batas_bawah);
            }

            $pph_setting_3  = \App\Models\PayrollPPH::where('id', 3)->first();
            // Perhitungan 25 persen
            $income_tax_calculation_25 = 0;
            if($taxable_yearly_income >= $pph_setting_3->batas_atas)
            {
                $income_tax_calculation_25 = ($pph_setting_3->tarif / 100)  * ($pph_setting_3->batas_atas - $pph_setting_3->batas_bawah);
            }

            if($taxable_yearly_income <= $pph_setting_3->batas_atas and $taxable_yearly_income >= $pph_setting_3->batas_bawah)
            {
                $income_tax_calculation_25 = ($pph_setting_3->tarif / 100) * ($taxable_yearly_income - $pph_setting_3->batas_bawah);
            }

            $pph_setting_4  = \App\Models\PayrollPPH::where('id', 4)->first();
            $income_tax_calculation_30 = 0;
            if($taxable_yearly_income >= $pph_setting_4->batas_atas)
            {
                $income_tax_calculation_30 = ($pph_setting_4->tarif / 100) * ($taxable_yearly_income - $pph_setting_4->batas_bawah);
            }

            $yearly_income_tax              = $income_tax_calculation_5 + $income_tax_calculation_15 + $income_tax_calculation_25 + $income_tax_calculation_30;

            $params['yearly_income_tax']            = $yearly_income_tax;
        }

        return $params;
    }
}
