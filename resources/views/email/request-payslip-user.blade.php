@extends('email.general')

@section('content')
{!! $text !!}
<table>
	<thead>
		<tr>
			<th style="text-align: left;">Payroll :</th>
		</tr>
	</thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td style="text-align: left;">{{ $item }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br />
@endsection