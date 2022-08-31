<!DOCTYPE html>
<html>
<head>
	<title>Payroll Template</title>
</head>
<body>
	<table style="table-layout:auto;">
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
					<td style="border: 1px solid #000000;">{{ array_values($val)[0] }}</td>
				@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>