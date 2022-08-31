<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
	<style type="text/css">
		td {
			border: 1px solid #000000;
		}
	</style>
</head>
@php($user = \App\User::where('id', $user_id)->first())
<body>
<h2>{{ $user->nik }} / {{ $user->name }}</h2>
<br />
<h3>PPh 21 Calculation</h3>
<br />
<table class="border">
	<tbody>
	<tr>
		<th style="width: 45px;border: 1px solid #000000;background: #7f7f7f;color: #ffffff;"><strong>Month</strong></th>
		<th  style="width: 30px;background:#7f7f7f;border: 1px solid #000000;color: #ffffff;">EM - HR</th>
		@for ($month = 1; $month <= 12; $month++)
			<th style="color: #ffffff;background: #7f7f7f;text-align: center;border: 1px solid #000000; width: 15px">{{ date('M', mktime(0, 0, 0, $month, 10)) }}</th>
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000">Monthly PPH 21</td>
		<td style="border: 1px solid #000000">MONTHLY INCOME TAX</td>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 25px">0</th>
            @else
			    <th style="border: 1px solid #000000; width: 25px">{{ format_idr( isset($data[$month]->pph21) ? $data[$month]->pph21 : 0) }}</th>
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;">Cummulative PP21 (PAID)</td>
		<td style="border: 1px solid #000000;"></td>
		@php($commulative_pph21 = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                @php($commulative_pph21 += isset($data[$month]->pph21) ? $data[$month]->pph21 : 0)
                <th style="border: 1px solid #000000; width: 15px">
                    {{ format_idr( $commulative_pph21) }}
                </th>
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;">Payroll Type</td>
		<td style="border: 1px solid #000000;"></td>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 25px">-</th>
            @else
			    <th style="border: 1px solid #000000; width: 25px">{{ isset($data[$month]->payroll_type)?$data[$month]->payroll_type:"-"}}</th>
            @endif
		@endfor
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td><strong>YTD</strong></td>
	</tr>
	@for ($month = 1; $month <= 12; $month++)
		@php($gross[$month] = 0 )
        @php($net[$month] = 0 )
		@php($total_deduction[$month] = 0 )
	@endfor
	<tr>
		<td style="background: #7f7f7f;border: 1px solid #000000;width: 32px;color: #ffffff;"> Earnings (Salary and Overtime) </td>
		<td style="background: #7f7f7f;border: 1px solid #000000; width: 30px;color: #ffffff;">  EM - HR </td>
		@for ($month = 1; $month <= 12; $month++)
			<th style="border: 1px solid #000000;background: #7f7f7f; width: 15px;color: #ffffff;">{{ date('M', mktime(0, 0, 0, $month, 10)) }}</th>
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;"> Basic salary w.o. Tunjangan Pajak</td>
		<td style="border: 1px solid #000000;"> SALARY </td>
		@php($gross_year = 0)
		@php($salary = 0)
		@php($salary_ = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                @php($salary += get_payroll_history_param($user_id, $year, $month, 'salary') )

                @if($salary !=0 and $salary_==0)
                    @php($salary_ = $salary)
                @endif

                @php($gross_year += get_payroll_history_param($user_id, $year, $month, 'salary'))
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($salary) }}</th>
                @php($gross[$month] += $salary)
            @endif
		@endfor
    </tr>
    <tr>
		<td style="border: 1px solid #000000;"> Overtime </td>
		<td style="border: 1px solid #000000;"> EARNING 1 </td>
		@php($overtime = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                @php($overtime += get_payroll_history_param($user_id, $year, $month, 'overtime') )
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($overtime) }}</th>
                @php($gross[$month] += $overtime)
            @endif
		@endfor
	</tr>
	@foreach(get_earnings() as $key => $item)
		@if($item->taxable == 1)
			<tr>
				<td style="border: 1px solid #000000;">{{ $item->title }}</td>
				<td style="border: 1px solid #000000;">EARNING {{ $key+2 }}</td>
				@php($earning = 0)
				@for ($month = 1; $month <= 12; $month++)
                    @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                        <th style="border: 1px solid #000000; width: 15px">0</th>
                    @else
                        @php($earning += get_payroll_earning_history_param((isset($data[$month]->id) ? $data[$month]->id : 0), $year, $month, $item->id))
                        <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($earning) }}</th>
                        @php($gross[$month] += $earning)
                    @endif
				@endfor
			</tr>
		@endif
	@endforeach
	<tr>
		<td style="border: 1px solid #000000;"> Jaminan Kecelakaan Kerja (JKK) - paid for by company </td>
		<td style="border: 1px solid #000000;"> BPJS JKK (company) </td>
		@php($bpjs1 = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px;">0</th>
            @else
                @php($bpjs1 += get_payroll_history_param($user_id, $year, $month, 'bpjs_jkk_company'))
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($bpjs1) }}</th>
                @php($gross[$month] += $bpjs1)
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;"> Jaminan Kematian (JK) - paid for by company </td>
		<td style="border: 1px solid #000000;"> BPJS JKematian (company) </td>
		@php($bpjs2 = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px;">0</th>
            @else
                @php($bpjs2 += get_payroll_history_param($user_id, $year, $month, 'bpjs_jkm_company') )
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($bpjs2) }}</th>
                @php($gross[$month] += $bpjs2)
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;"> BPJS Kesehatan </td>
		<td style="border: 1px solid #000000;"> BPJS Kesehatan (company) </td>
		@php($bpjs3 = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px;">0</th>
            @else
                @php($bpjs3 += get_payroll_history_param($user_id, $year, $month, 'bpjs_kesehatan_company') )
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($bpjs3) }}</th>
                @php($gross[$month] += $bpjs3)
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;"> Bonus </td>
		<td style="border: 1px solid #000000;"> </td>
		@php($bonus = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                @php($bonus += get_payroll_history_param($user_id, $year, $month, 'bonus') )
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($bonus) }}</th>
                @php($gross[$month] += $bonus)
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;"> THR </td>
		<td style="border: 1px solid #000000;"> </td>
		@php($thr = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                @php($thr += get_payroll_history_param($user_id, $year, $month, 'thr') )
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($thr) }}</th>
                @php($gross[$month] += $thr)
            @endif
		@endfor
    </tr>
	<tr>
		<th style="border: 1px solid #000000;background: #deeaf6;"> Gross Salary / ytd (A) </th>
		<th style="background: #deeaf6;"> </th>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px;background: #deeaf6;">0</th>
            @else
			    <th style="border: 1px solid #000000; width: 15px;background: #deeaf6;"><strong>{{ format_idr($gross[$month]) }}</strong></th>
            @endif
		@endfor
	</tr>
	<tr>
		<td style="background: #7f7f7f;border: 1px solid #000000;width: 32px;color: #ffffff;"> Deductions </td>
		<td style="background: #7f7f7f;border: 1px solid #000000; width: 30px;color: #ffffff;">  EM - HR </td>
		@for ($month = 1; $month <= 12; $month++)
            <th style="border: 1px solid #000000;background: #7f7f7f; width: 15px;color: #ffffff;">{{ date('M', mktime(0, 0, 0, $month, 10)) }}</th>
        @endfor
	</tr>
	@foreach(get_deductions() as $key => $item)
		@if($item->taxable == 1)
			<tr>
				<td style="border: 1px solid #000000;">{{ $item->title }}</td>
				<td style="border: 1px solid #000000;">DEDUCTION {{ $key+1 }}</td>
				@php($deduction = 0)
				@for ($month = 1; $month <= 12; $month++)
                    @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                        <th style="border: 1px solid #000000; width: 15px">0</th>
                    @else
                        @php($deduction += get_payroll_deduction_history_param((isset($data[$month]->id) ? $data[$month]->id : 0), $year, $month, $item->id))
                        <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($deduction) }}</th>
                        @php($total_deduction[$month] += $deduction)
                    @endif
				@endfor
			</tr>
		@endif
	@endforeach

	<tr>
		<td style="border: 1px solid #000000;"> Tunjangan Jabatan</td>
        <td style="border: 1px solid #000000;"> 5% * SALARY</td>
        @php($biaya_jabatan_maks = \App\Models\PayrollOthers::where('id', 1)->first()->value/12)
        @php($activeMonth = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($burden_allow = isset($gross[$month]) ? ($gross[$month]/++$activeMonth*5/100 <= $biaya_jabatan_maks ? $gross[$month]/$activeMonth*5/100 : $biaya_jabatan_maks)*$activeMonth : 0) }}</th>
                @php($total_deduction[$month] += $burden_allow)
            @endif
		@endfor
	</tr>

	<tr>
		<td style="border: 1px solid #000000;"> Jaminan Tunjangan Hari Tua (JHT) - Staff</td>
		<td style="border: 1px solid #000000;"> BPJS JHT (employee)</td>
		@php($bpjs1 = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                @php($bpjs1 += get_payroll_history_param($user_id, $year, $month, 'bpjs_ketenagakerjaan_employee'))
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($bpjs1) }}</th>
                @php($total_deduction[$month] += $bpjs1)
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000;"> Jaminan Pension (JP) - Staff</td>
		<td style="border: 1px solid #000000;"> BPJS JP (employee)</td>
		@php($bpjs2 = 0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">0</th>
            @else
                @php($bpjs2 += get_payroll_history_param($user_id, $year, $month, 'bpjs_pensiun_employee'))
                <th style="border: 1px solid #000000; width: 15px;">{{ format_idr($bpjs2) }}</th>
                @php($total_deduction[$month] += $bpjs2)
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000; background: #deeaf6;"> <strong>Total Deductions/ ytd (B)</strong></td>
		<td style="border: 1px solid #000000; background: #deeaf6;"> </td>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <td style="border: 1px solid #000000; background: #deeaf6;">0</td>
            @else
			    <td style="border: 1px solid #000000; background: #deeaf6;"><strong>{{ format_idr($total_deduction[$month]) }}</strong></td>
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000; background: #deeaf6;"> <strong>Nett Salary / ytd (A-B)</strong> </td>
		<td style="border: 1px solid #000000; background: #deeaf6;"> </td>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px;background: #deeaf6;">0</th>
            @else
			    <th style="border: 1px solid #000000; width: 15px;background: #deeaf6;">{{ format_idr($net[$month] = $gross[$month] - $total_deduction[$month]) }}</th>
            @endif
		@endfor
	</tr>
	{{-- <tr>
		<td style="border: 1px solid #000000; background: #deeaf6;"> <strong>Nett Salary / year (C)</strong> </td>
		<td style="border: 1px solid #000000; background: #deeaf6;"> </td>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px;background: #deeaf6;">0</th>
            @else
			    <th style="border: 1px solid #000000; width: 15px;background: #deeaf6;">{{ format_idr($net[$month] = ($net[$month]*12/$month)) }}</th>
            @endif
		@endfor
	</tr> --}}
	<tr>
		<td style="border: 1px solid #000000;"> PTKP Status</td>
		<td style="border: 1px solid #000000;"> PTKP Status</td>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px">-</th>
            @else
                <th style="border: 1px solid #000000; width: 15px;">{{ get_status_ptkp($user_id, $year) }}</th>
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000; background: #deeaf6;"> <strong> PTKP / year (E) </strong></td>
		<td style="border: 1px solid #000000; background: #deeaf6;">  PTKP value </td>
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px; background: #deeaf6;">0</th>
            @else
			    <th style="border: 1px solid #000000; width: 15px; background: #deeaf6;">{{ format_idr( get_ptkp($user_id, $year)) }}</th>
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000; background: #deeaf6;"> <strong> Penghasilan Kena Pajak PKP / year (D-E) </strong></td>
		<td style="border: 1px solid #000000; background: #deeaf6;"> </td>
		@php($taxable_val=0)
		@php($taxable_ratusan=0)
		@php($taxable=0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <th style="border: 1px solid #000000; width: 15px; background: #deeaf6;">0</th>
            @else
            @php($net[$month] = ($net[$month] - get_ptkp($user_id, $year))/1000)
            @php($net[$month] = ($net[$month] > 0 ? floor($net[$month]) : ceil($net[$month]))*1000)
                <th style="border: 1px solid #000000; width: 15px; background: #deeaf6;">{{ format_idr($net[$month]) }}</th>
            @endif
		@endfor
	</tr>
	<tr>
		<td style="border: 1px solid #000000; background: #deeaf6;"> <strong> PPh terutang / year</strong></td>
		<td style="border: 1px solid #000000; background: #deeaf6;"></td>
		@php($taxable_valA=0)
		@php($taxable_ratusanA=0)
		@php($taxableA=0)
		@for ($month = 1; $month <= 12; $month++)
            @if(!isset($data[$month]) || (isset($data[$month]) && !$data[$month]->is_lock))
                <td style="border: 1px solid #000000; background: #deeaf6;">0</td>
            @else
                <td style="border: 1px solid #000000; background: #deeaf6;">
                    {{ format_idr( getpphYear($net[$month])) }}
                </td>
            @endif
		@endfor
	</tr>
	</tbody>
</table>
</body>
</html>
