<!DOCTYPE html>
<html>
<head>
	<title>Payroll</title>
</head>
<body>
	<h1>Payroll</h1>
	<table>
		<tbody>
			@foreach($data as $key => $item)
                <tr>
                    @foreach($item as $header => $val)
                        <td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>{{ $header }}</strong></td>
                    @endforeach
                </tr>
                @break
			@endforeach
			@foreach($data as $header => $item)
				<tr>
				@foreach($item as $key => $val)
					<td style="border: 1px solid #000000;">{{ $item[$key] }}</td>
				@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>