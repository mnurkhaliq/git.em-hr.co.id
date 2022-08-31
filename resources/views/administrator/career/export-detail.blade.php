<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
</head>
<body>
	<h1>{{ $title }}</h1>
	<table>
		<tbody>
			@foreach($data as $key => $item)
                <tr>
                    <td style="background:#7f7f7f;color: #ffffff;text-align:center;"><strong></strong></td><td colspan="4" style="background:#7f7f7f;color: #ffffff;text-align:center;"><strong>CURRENT POSITION</strong></td><td colspan="4" style="background:#7f7f7f;color: #ffffff;text-align:center;"><strong>PREVIOUS POSITION</strong></td>
                </tr>
                <tr>
                    @foreach($item as $header => $val)
                        <td style="background:#7f7f7f;color: #ffffff;text-align:center;"><strong>{{ $header }}</strong></td>
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