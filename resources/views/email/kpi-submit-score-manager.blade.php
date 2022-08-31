@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
	<p><strong>Dear Sir/Madam {{$user->name}}</strong>,</p>
	<p>Your Manager <strong>{{$manager->name}}</strong> has just submitted your final scores for KPI Period <strong>{{date_format(date_create($period->start_date),"d F Y")." - ".date_format(date_create($period->end_date),"d F Y")}}</strong></p>
	<p>Please log in to check your final score and acknowledge it</p>
@endsection