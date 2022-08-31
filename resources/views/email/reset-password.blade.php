@extends('email.general')

@section('content')

<h3>Reset Password Request</h3>
<p>We've received your request to reset the password for Em-HR Apps. Here are your personal data : </p>
<table>
	<tr>
		<td>Employee ID</td>
		<td> : {{ $user->nik }}</td>
	</tr>
	<tr>
		<td>Employee Name</td>
		<td> : {{ $user->name }}</td>
	</tr>
</table>
<p>If you feel that you did not do this request, just simply ignore this email or contact us on support@empore.co.id</p>
<a href="{{ route('reset-password.reset', ['id'=>$user->password_reset_token,'company'=>$company]) }}" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #1e88e5; border-radius: 60px; text-decoration:none;">Reset Password</a>
@endsection