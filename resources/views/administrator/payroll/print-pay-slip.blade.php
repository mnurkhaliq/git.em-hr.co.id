<!DOCTYPE html>
<html>
<head>
	<title>Payslip</title>
	<style type="text/css">
		table {
			border-collapse: collapse;
    		border-spacing: 0;
		}
		table.border tr th, table.border tr td {
			border: 1px solid black;
			padding: 5px 10px;
		}
		.page-break {
			page-break-after: always;
		}
	</style>
</head>
<body>
	@foreach($dataArray as $k => $item)
	{{--<img src="{{  asset(get_setting('logo')) }}" style="height: 40; float: right;" />--}}
	<img src="{{  public_path(get_setting('logo')) }}" style="height: 40; float: right;" />
	<h3>{{ get_setting('title') }} </h3>
	<p><strong>PAYSLIP {{ $bulan }} {{ $tahun }}</strong></p>
	<br />
	<table style="width: 100%; text-align: left">
		<tr>
			<th style="width: 20%; text-align: left">Number</th>
			<th style="width: 30%; text-align: left"> : {{ $item->number }}</th>
			<th style="width: 25%; text-align: left">Status</th>
			<th style="width: 25%; text-align: left"> : {{ $item->user->organisasi_status }}</th>
		</tr>
		<tr>
			<th style="width: 20%; text-align: left">NIK</th>
			<th style="width: 30%; text-align: left"> : {{ $item->user->nik }}</th>
			<th style="width: 25%; text-align: left">NPWP</th>
			<th style="width: 25%; text-align: left"> : {{ $item->user->npwp_number }}</th>
		</tr>
		<tr>
			<th style="width: 20%; text-align: left">Name</th>
			<th style="width: 30%; text-align: left"> : {{ $item->user->name }}</th>
			<th style="width: 25%; text-align: left">BPJS</th>
			<th style="width: 25%; text-align: left"> : {{ $item->user->bpjs_number }}</th>
		</tr>
        <tr>
			<th style="width: 20%; text-align: left">Position</th>
			<th style="width: 30%; text-align: left"> : {{ empore_jabatan($item->user->id) }}</th>
		</tr>
	</table>
	<br />
	<strong>IDR Portion</strong>
	<table style="width: 100%">
		<tr>
			<td style="width: 49%;vertical-align: top;">
				<table style="width: 100%;" class="border">
					<tr>
						<th style="padding-bottom: 15px;padding-top: 15px; text-align: left">Income Description</th>
						<th style="text-align: right;">Amount</th>
					</tr> 
					<tr>
						<td>Salary</td>
						<td style="text-align: right;">{{ format_idr($item->salary) }}</td>
					</tr>
					@if($item->bonus > 0)
						<tr>
							<td>Bonus</td>
							<td style="text-align: right;">{{ format_idr($item->bonus) }}</td>
						</tr>
					@endif
					@if($item->thr > 0)
						<tr>
							<td>THR</td>
							<td style="text-align: right;">{{ format_idr($item->thr) }}</td>
						</tr>
                    @endif
                    @if($item->overtime > 0)
						<tr>
							<td>Overtime</td>
							<td style="text-align: right;">{{ format_idr($item->overtime) }}</td>
						</tr>
					@endif
					<tr>
						<td>BPJS JKK {{ get_setting('bpjs_jkk_company') }}% (Company) </td>
						<td style="text-align: right;">{{ format_idr( $item->bpjs_jkk_company ) }}</td>
					</tr>
					<tr>
						<td>BPJS JKM {{ get_setting('bpjs_jkm_company') }}% (Company) </td>
						<td style="text-align: right;">{{ format_idr( $item->bpjs_jkm_company ) }}</td>
					</tr>
					<tr>
						<td>BPJS JHT {{ get_setting('bpjs_jht_company') }}% (Company) </td>
						<td style="text-align: right;">{{ format_idr( $item->bpjs_jht_company ) }}</td>
					</tr>
					<tr>
						<td>BPJS Pensiun {{ get_setting('bpjs_pensiun_company') }}% (Company) </td>
						<td style="text-align: right;">{{ format_idr( $item->bpjs_pensiun_company ) }}</td>
					</tr>
					<tr>
						<td>BPJS Kesehatan {{ get_setting('bpjs_kesehatan_company') }}% (Company) </td>
						<td style="text-align: right;">{{ format_idr( $item->bpjs_kesehatan_company ) }}</td>
					</tr>
					@foreach(payrollEarningsEmployeeHistory($item->id) as $i)
                        @if(isset($i->payrollEarnings->title)  && !empty($i->nominal))
                          <tr>
                          	<td>
                          		{{ $i->payrollEarnings->title }}
                          	</td>
                          	<td style="text-align: right;">
                          		{{ format_idr($i->nominal) }}
                          	</td>
                          </tr>
                        @endif
                    @endforeach
					@if(isset($item->businessTrips))
						@foreach($item->businessTrips as $bt)
							@if($bt->pengambilan_uang_muka - ($bt->sub_total_1_disetujui + $bt->sub_total_2_disetujui + $bt->sub_total_3_disetujui + $bt->sub_total_4_disetujui) < 0)
							<tr>
								<td>
									{{$bt->number}}
								</td>
								<td style="text-align: right;">
									{{format_idr(-1 * ($bt->pengambilan_uang_muka - ($bt->sub_total_1_disetujui + $bt->sub_total_2_disetujui + $bt->sub_total_3_disetujui + $bt->sub_total_4_disetujui)))}}
								</td>
							</tr>
							@endif
						@endforeach
					@endif
					@if(isset($item->cashAdvances))
						@foreach($item->cashAdvances as $ca)
							@if($ca->cash_advance_form->sum('nominal_approved') < $ca->cash_advance_form->sum('nominal_claimed'))
							<tr>
								<td>
									{{$ca->number}}
								</td>
								<td style="text-align: right;">
									{{format_idr($ca->cash_advance_form->sum('nominal_claimed')-$ca->cash_advance_form->sum('nominal_approved'))}}
								</td>
							</tr>
							@endif
						@endforeach
					@endif
                    <tr>
                    	<td>Monthly Income Tax / PPh21 (ditanggung perusahaan)</td>
                    	<td style="text-align: right;">
                    		{{ (($item->payroll_type=='GROSS')?'0':format_idr($item->pph21)) }}
                    	</td>
                    </tr>
				</table>
				<table style="width: 100%;">
					<tr>
						<th style="width:78%; text-align: left">Total Earning </th>
						<th>{{ format_idr($item->total_earnings) }}</th>
					</tr>
				</table>
			</td>
			<td>&nbsp;</td>
			<td style="width: 49%;vertical-align: top;">
				<table style="width: 100%;" class="border">
					<tr>
						<th style="padding-bottom: 15px;padding-top: 15px; text-align: left">Deduction Description</th>
						<th style="text-align: right;">Amount</th>
					</tr>
					<tr>
						<td>BPJS Jaminan Hari Tua (JHT) {{ get_setting('bpjs_jaminan_jht_employee') }}% (Employee) </td>
						<td style="text-align: right;">{{ format_idr( $item->bpjs_ketenagakerjaan_employee ) }}</td>
					</tr>
					<tr> 
						<td>BPJS Kesehatan ({{ get_setting('bpjs_kesehatan_employee') }}%) (employee)</td>
						<td style="text-align: right;">{{ format_idr($item->bpjs_kesehatan_employee) }}</td>
					</tr>
					<tr>
						<td>BPJS Jaminan Pensiun (JP) {{ get_setting('bpjs_jaminan_jp_employee') }}% (Employee)</td>
						<td style="text-align: right;"> {{ format_idr($item->bpjs_pensiun_employee) }} </td>
					</tr>
					<tr>
						<td>Total BPJS Company</td>
						<td style="text-align: right;"> {{ format_idr($item->bpjstotalearning) }} </td>
					</tr>
					<tr>
						<td>PPH21</td>
						<td style="text-align: right;">{{ format_idr($item->pph21) }}</td>
					</tr>
					@foreach(payrollDeductionsEmployeeHistory($item->id) as $i)
                        @if(isset($i->payrollDeductions->title) && !empty($i->nominal))
                          <tr>
                          	<td>
                          		{{ $i->payrollDeductions->title }}
                          	</td>
                          	<td style="text-align: right;">
                          		{{ format_idr($i->nominal) }}
                          	</td>
                          </tr>
                        @endif
                    @endforeach
					@if(isset($item->loanPayments))
						@foreach($item->loanPayments as $lp)
							<tr>
								<td>
									{{$lp->loan->number}}
								</td>
								<td style="text-align: right;">
									{{format_idr($lp->amount)}}
								</td>
							</tr>
						@endforeach
					@endif
					@if(isset($item->businessTrips))
						@foreach($item->businessTrips as $bt)
							@if($bt->pengambilan_uang_muka - ($bt->sub_total_1_disetujui + $bt->sub_total_2_disetujui + $bt->sub_total_3_disetujui + $bt->sub_total_4_disetujui) > 0)
							<tr>
								<td>
									{{$bt->number}}
								</td>
								<td style="text-align: right;">
									{{format_idr($bt->pengambilan_uang_muka - ($bt->sub_total_1_disetujui + $bt->sub_total_2_disetujui + $bt->sub_total_3_disetujui + $bt->sub_total_4_disetujui))}}
								</td>
							</tr>
							@endif
						@endforeach
					@endif
					@if(isset($item->cashAdvances))
						@foreach($item->cashAdvances as $ca)
							@if($ca->cash_advance_form->sum('nominal_approved') > $ca->cash_advance_form->sum('nominal_claimed'))
							<tr>
								<td>
									{{$ca->number}}
								</td>
								<td style="text-align: right;">
									{{format_idr($ca->cash_advance_form->sum('nominal_approved')-$ca->cash_advance_form->sum('nominal_claimed'))}}
								</td>
							</tr>
							@endif
						@endforeach
					@endif
				</table>
				<table style="width: 100%;">
					<tr>
						<th style="width: 78%; text-align: left">Total Deduction </th>
						<th> {{ format_idr($item->total_deduction) }}</th>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table style="width: 50%;">
		<tr>
			<th style="border-bottom: 1px solid black;"></th>
			<th style="border-bottom: 1px solid black;">IDR Portion</th>
		</tr>
		<tr>
			<th style="text-align: left">Take Home Pay </th>
			<th> : {{ format_idr($item->thp) }}</th>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th style="text-align: left">Bank Transfer Details</th>
		</tr>
		<tr>
			<td>Bank</td>
			<td> : {{ isset($item->user->bank->name) ? $item->user->bank->name : '' }}</td>
		</tr>
		<tr>
			<td>A/C no</td>
			<td> : {{ isset($item->user->nomor_rekening) ? $item->user->nomor_rekening : '' }}</td>
		</tr>
		<tr>
			<td>Account name</td>
			<td> : {{ isset($item->user->nama_rekening) ? $item->user->nama_rekening : '' }}</td>
		</tr>
	</table>
	@if($k < count($dataArray)-1)
		<div class="page-break"></div>
	@endif
	@endforeach
</body>
</html>