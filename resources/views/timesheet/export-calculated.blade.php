<!DOCTYPE html>
<html>

<head>
	<title>Timesheet Calculated</title>
</head>

<body>
    <h3>{{ get_setting('title') }}</h3>
    <h3>From: {{ $date[0] }}</h3>
    <h3>To: {{ $date[1] }}</h3>
	<table>
		<thead>
			<tr>
				<th rowspan="2" style="text-align:center;">No</th>
				<th rowspan="2" style="text-align:center;">NIK</th>
				<th rowspan="2" style="text-align:center;">Name</th>
                <th rowspan="2" style="text-align:center;">Total</th>
                @foreach($data['name'] as $value)
                    @if(isset($value['activity']))
                        <th rowspan="1" colspan="{{ count($value['activity']) }}" style="text-align:center;">{{ $value['name'] }}</th>
                    @else
                        <th rowspan="2" style="text-align:center;">{{ $value['name'] }}</th>
                    @endif
                @endforeach
                <th rowspan="2" style="text-align:center;">Description</th>
            </tr>
            <tr>
                @foreach(array_shift($data) as $value)
                    @if(isset($value['activity']))
                        @foreach($value['activity'] as $value2)
                            <th rowspan="1" style="text-align:center;">{{ $value2['name'] }}</th>
                        @endforeach
                    @endif
                @endforeach
            </tr>
		</thead>
		<tbody>
            @php($total = 0)
            @foreach($data as $no => $item)
                @php($total += array_sum($item['total']))
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
            <tr>
                <td colspan="3" style="text-align:right;">TOTAL</td>
                <td>{{ $total }}</td>
            </tr>
		</tbody>
	</table>
</body>

</html>