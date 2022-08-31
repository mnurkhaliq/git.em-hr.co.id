@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
<h3>Request Pay-Slip</h3>
<p>The following is a Pay Slip attached</p>
@endsection