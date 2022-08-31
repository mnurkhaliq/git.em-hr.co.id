@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Applicant Name </th>
			<th style="text-align: left;"> : {{ $data->internal?$data->internal->applicant->name:$data->external->applicant->name }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Position </th>
			<th style="text-align: left;"> : {{ (isset($data->recruitmentRequest->structure->position) ? $data->recruitmentRequest->structure->position->name:'').(isset($data->recruitmentRequest->structure->division) ? ' - '.$data->recruitmentRequest->structure->division->name:'').(isset($data->recruitmentRequest->structure->title) ? ' - '.$data->recruitmentRequest->structure->title->name:'') }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Interview Schedule </th>
			<th style="text-align: left;"> : {{date('d F Y H:i',strtotime($data->internal?$data->internal->interview_test_schedule:$data->external->interview_test_schedule)) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Interview Location </th>
			<th style="text-align: left;"> : {{ $data->internal?$data->internal->interview_test_location:$data->external->interview_test_location}}</th>
		</tr>
	</thead>
</table>
@endsection