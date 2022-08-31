<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
</head>
<body>
	<h1>{{ $title }}</h1>
	<table>
		<tbody>
            @php($class = true)
            @php($header_key = -1)
            @php($array_class = [])
            <tr>
            @foreach($data[0] as $header => $val)
                @php(++$header_key)
                @php(array_push($array_class, $class = (strpos($header, 'Total') !== false ? (count(explode(" ", $header)) == 2 ? 'total' : 'subtotal') : ($header_key % 3 == 0 ? (is_bool($class) ? !$class : $array_class[count($array_class) - 4]) : $class))))
                <td style="border: 1px solid #000000; background: {{ $header_key < 3 ? '#fffc4c' : ($class === 'total' ? '#ffc4c4;' : ($class === 'subtotal' ? '#c4c4ff;' : ($class ? '#ffffff;' : '#e2e2e2;'))) }}"><strong>{{ $header }}</strong></td>
            @endforeach
            </tr>
			@foreach($data as $header => $item)
				<tr>
                @php($header_key = -1)
				@foreach($item as $key => $val)
					<td style="border: 1px solid #000000; background: {{ ++$header_key < 3 ? '#fffc4c' : ($array_class[$header_key] === 'total' ? '#ffc4c4;' : ($array_class[$header_key] === 'subtotal' ? '#c4c4ff;' : ($array_class[$header_key] ? '#ffffff;' : '#e2e2e2;'))) }}">{{ $item[$key] }}</td>
				@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>