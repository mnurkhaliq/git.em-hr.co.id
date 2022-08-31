<!DOCTYPE html>
<html>

<head>
	<title>Visit List</title>
</head>

<body>
	<table>
		<thead>
			<tr>
				<th rowspan="1">No</th>
				<th rowspan="1">NIK</th>
				<th rowspan="1">Name</th>
				<th rowspan="1">Visit Type</th>
				<th rowspan="1">Visit Category</th>
				<th rowspan="1">Date</th>
				<th rowspan="1">Day</th>
				<th rowspan="1">Timezone</th>
				<th rowspan="1">Branch Name / Place Name</th>
				<th rowspan="1">Location Name</th>
				<th rowspan="1">Activity Name</th>
				<th rowspan="1">Description</th>
				<th rowspan="1">PIC Name</th>
                <th rowspan="1">Visit Point</th>
		</thead>
		<tbody>
			@foreach($data as $no => $item)
			@if(!isset($item->nik) || empty($item->visit_time))
                <?php  ?>
                @endif
			<tr>
				<td>{{ $no+1 }}</td>
				<td>{{ $item->nik}}</td>
				<td>{{ $item->username}}</td>
				<td>{{ $item->master_visit_type_name}}</td>
				<td>{{ $item->master_category_name}}</td>
				<td>{{ $item->visit_time }}</td>
				<td>{{ $item->timetable }}</td>
				<td>{{ $item->timezone}}</td>
				@if($item->master_visit_type_name == 'Unlock' || ( $item->master_visit_type_name == 'Lock' && $item->isoutbranch == 1 ))
                <td>{{ $item->placename}}</td>
                @else
                <td>{{ $item->cabang_name}}</td>
                @endif                
				<td>{{ $item->locationname}}</td>
				<td>{{ $item->activityname}}</td>
				<td>{{ $item->description}}</td>
				<td>{{ $item->picname }}</td>
                <td>{{ $item->point }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>

</html>