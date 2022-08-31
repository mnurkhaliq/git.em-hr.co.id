@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
	<p><strong>Dear Sir/Madam {{$user->name}}</strong>,</p>
	<p>KPI Period started from <strong>{{date_format(date_create($period->start_date),"d F Y")." - ".date_format(date_create($period->end_date),"d F Y")}}</strong> has been published</p>
	<p>You are requested to fill the KPI questions</p>
@endsection