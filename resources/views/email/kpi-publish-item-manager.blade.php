@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
	<p><strong>Dear Sir/Madam {{$user->name}} (Admin Performance Management)</strong>,</p>
	<p>KPI Items for period started from <strong>{{date_format(date_create($period->start_date),"d F Y")." - ".date_format(date_create($period->end_date),"d F Y")}}</strong> for position {{$position}} has been published</p>
@endsection