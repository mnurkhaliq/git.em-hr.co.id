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
            <td style="text-align: left;">{{ date('F Y', strtotime('1-' . substr($item,4) . '-' . substr($item,0,4))) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br />
@endsection