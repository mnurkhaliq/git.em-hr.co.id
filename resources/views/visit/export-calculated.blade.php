<!DOCTYPE html>
<html>

<head>
	<title>Visit Calculated</title>
</head>

<body>
    @if(\Session::get('filter_start'))
        <h3>From: {{ \Session::get('filter_start') }}</h3>
    @endif
    @if(\Session::get('filter_end'))
        <h3>To: {{ \Session::get('filter_end') }}</h3>
    @endif
	<table>
		<thead>
			<tr>
				<th rowspan="1">No</th>
				<th rowspan="1">NIK</th>
				<th rowspan="1">Name</th>
				<th rowspan="1">Total</th>
                @foreach(array_shift($data) as $value)
                    <th rowspan="1">{{ $value['activityname'] }}</th>
                @endforeach
                <th rowspan="1">Description</th>
		</thead>
		<tbody>
            @foreach($data as $no => $item)
                <tr>
                    <td>{{ $no+1 }}</td>
                    <td>{{ $item['nik'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ array_sum($item['total']) }}</td>
                    @foreach($item['total'] as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                    <td>{{ $item['description'] }}</td>
                </tr>
			@endforeach
		</tbody>
	</table>
</body>

</html>