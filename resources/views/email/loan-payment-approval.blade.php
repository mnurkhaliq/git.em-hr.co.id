@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
{!! $text !!}

<table>
	<thead>
        <tr>
			<th style="text-align: left;">Loan Number</th>
			<th style="text-align: left;"> : {{ $data->loan->number }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Tenor</th>
			<th style="text-align: left;"> : {{ $data->tenor }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Due Date</th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->due_date)) }}</th>
		</tr>
        <tr>
			<th style="text-align: left;">Amount</th>
			<th style="text-align: left;"> : {{ number_format($data->amount) }}</th>
		</tr>
        <tr>
			<th style="text-align: left;">Refund Method</th>
			<th style="text-align: left;"> : {{ $data->payment_type == 1 ? 'Deduct Salary' : 'Transfer to Company' }}</th>
		</tr>
	</thead>
</table>

@endsection