<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
</head>
<body>
    <h3>KPI Period: {{ $period }}</h3>
    <h3>Position: {{ $position }}</h3>
    <h3>Min - Max Score: {{ $minmax }}</h3>
	<table>
		<thead>
			<tr>
				<td width="10" rowspan="2">No</td>
				<td rowspan="2">NIK</td>
				<td rowspan="2">Name</td>
				@foreach($data['header'] as $key => $item)
                    <td style="text-align: center;" colspan="4">{{ $item->name }} ({{ $item->weightage }}%)</td>
                @endforeach
				<td rowspan="2">Final Score</td>
				<td rowspan="2">Status</td>
			</tr>
            <tr>
                @foreach($data['header'] as $key => $item)
                    <td>Self Score</td>
                    <td>Justification</td>
                    <td>SPV Score</td>
                    <td>Comment</td>
                @endforeach
            </tr>
		</thead>
		<tbody>
			@foreach($data['data'] as $key => $items)
				@php(info($items));
                <tr>
                    <td>{{ $key+1 }}</td>
                    @foreach($items as $key => $item)
                        <td>{{ $item }}</td>
                    @endforeach
                </tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
