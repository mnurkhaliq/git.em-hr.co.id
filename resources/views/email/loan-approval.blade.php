@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Purpose</th>
			<th style="text-align: left;"> : {{ $data->loan_purpose }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Expected Cash Disbursement Date</th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->expected_disbursement_date)) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Max Plafond</th>
			<th style="text-align: left;"> : {{ number_format($data->plafond) }}</th>
		</tr>
        <tr>
			<th style="text-align: left;">Avaiable Plafond</th>
			<th style="text-align: left;"> : {{ number_format($data->available_plafond) }}</th>
		</tr>
        <tr>
			<th style="text-align: left;">Amount</th>
			<th style="text-align: left;"> : {{ number_format($data->amount) }}</th>
		</tr>
        <tr>
			<th style="text-align: left;">Total Tenor(Month)</th>
			<th style="text-align: left;"> : {{ $data->rate }}</th>
		</tr>
        <tr>
			<th style="text-align: left;">Interest(%)</th>
			<th style="text-align: left;"> : {{ $data->interest }}%</th>
		</tr>
        <tr>
			<th style="text-align: left;">Calculated Amount</th>
			<th style="text-align: left;"> : {{ number_format($data->calculated_amount) }}</th>
		</tr>
        <tr>
			<th style="text-align: left;">Refund Method</th>
			<th style="text-align: left;"> : {{ $data->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company' }}</th>
		</tr>
	</thead>
</table>
<br />	
<div class="modal-body" id="modal_content_history_approval">
	<div class="panel-body">
		@foreach($value as $key => $item)
			<table>
				<tr>
					<th style="text-align: left;">{{$item->level->name}}</th>
					<th style="text-align: left;"> : {{ (isset($item->structure->position) ? $item->structure->position->name:'').(isset($item->structure->division) ? ' - '.$item->structure->division->name:'').(isset($item->structure->title) ? ' - '.$item->structure->title->name:'') }}</th>
				</tr>
			</table>
		@endforeach
	</div>
</div>

@endsection