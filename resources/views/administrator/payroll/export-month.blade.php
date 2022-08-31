<!DOCTYPE html>
<html>
<head>
	<title>Payroll</title>
</head>
<body>
	<h1>Payroll {{(!is_null($year) && !is_null($month))?"Month $month Year $year":" Default"}}</h1>
	<table>
		<tbody>
			@foreach($data as $key => $item)
                <tr>
                    @foreach($item as $header => $val)
                        <td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>{{ array_keys($val)[0] }}</strong></td>
                    @endforeach
                </tr>
                @break
			@endforeach
			@foreach($data as $header => $item)
				<tr>
				@foreach($item as $key => $val)
					<td style="border: 1px solid #000000;">{{ (!empty(array_values($val)[0]))?replace_idr(array_values($val)[0]):"" }}</td>
				@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>

{{--{{ (!empty($item[$key]))?($key=='Acc No'?"'".$item[$key]:replace_idr($item[$key])):"" }}--}}