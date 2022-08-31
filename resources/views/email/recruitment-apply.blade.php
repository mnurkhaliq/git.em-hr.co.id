@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Job Name </th>
			<th style="text-align: left;"> : {{ $data['position'] }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Applied At </th>
			<th style="text-align: left;"> : {{$data['created_at']}}</th>
		</tr>
	</thead>
</table>

	<p>Our team will be reviewing your application. You will be notified when it's done, Thank you.</p>
@endsection