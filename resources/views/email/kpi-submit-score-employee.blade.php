@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
	<p><strong>Dear Sir/Madam {{$user->name}}</strong>,</p>
	<p>Your Staff <strong>{{$staff->name}}</strong> has just submitted his/her self scores for KPI Period <strong>{{date_format(date_create($period->start_date),"d F Y")." - ".date_format(date_create($period->end_date),"d F Y")}}</strong></p>
	<p>You are requested to fill the final scores for him/her</p>
@endsection