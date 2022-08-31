<!DOCTYPE html>
<html>

<head>
	<title>Timesheet List</title>
</head>

<body>
    <h3>{{ get_setting('title') }}</h3>
    <h3>From: {{ $date[0] }}</h3>
    <h3>To: {{ $date[1] }}</h3>
	<table>
		<thead>
			<tr>
				<th rowspan="1">No</th>
                <th rowspan="1">NIK</th>
                <th rowspan="1">Name</th>
                <th rowspan="1">Position</th>
                <th rowspan="1">Division</th>
                <th rowspan="1">Timesheet Category</th>
                <th rowspan="1">Timesheet Activity</th>
                <th rowspan="1">Description</th>
                <th rowspan="1">Date</th>
                <th rowspan="1">Day</th>
                <th rowspan="1">Start Time</th>
                <th rowspan="1">End Time</th>
                <th rowspan="1">Duration</th>
                <th rowspan="1">Note</th>
		</thead>
		<tbody>
			@foreach($data as $no => $item)
            <tr>
                <td>{{ $no+1 }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->position }}</td>
                <td>{{ $item->division }}</td>
                <td>{{ $item->category ?: 'Other Category' }}</td>
                <td>{{ $item->activity }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ date('l', strtotime($item->date)) }}</td>
                <td>{{ $item->start_time }}</td>
                <td>{{ $item->end_time }}</td>
                <td>{{ $item->total_time }}</td>
                <td>{{ $item->admin_note }}</td>
            </tr>
			@endforeach
		</tbody>
	</table>
</body>

</html>