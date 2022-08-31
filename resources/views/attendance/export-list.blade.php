<!DOCTYPE html>
<html>
<head>
	<title>Attendance Summary</title>
</head>
<body>
    <h3>Date: {{ $date }}</h3>
    @if($min)
        <h3>Minimum Attendaces: {{ $min }}</h3>
    @endif
	<table>
		<thead>
			<tr>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>No</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>NIK</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Name</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Position</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Branch</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Total Attendances</strong></td>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $key => $item)
                <tr>
                    <td style="border: 1px solid #000000;">{{ $key+1 }} </td>
                    <td style="border: 1px solid #000000;">{{ $item->nik }} </td>
                    <td style="border: 1px solid #000000;">{{ $item->name }}</td>
                    <td style="border: 1px solid #000000;">{{ isset($item->structure->position->name) ? $item->structure->position->name : '' }}{{ isset($item->structure->division->name) ? ' - '.$item->structure->division->name : '' }}{{ isset($item->structure->title->name) ? ' - '.$item->structure->title->name : '' }}</td>
                    <td style="border: 1px solid #000000;">{{ isset($item->cabang->name) ? $item->cabang->name : '' }}</td>
                    <td style="border: 1px solid #000000;">{{ $item->absensi_item_count }}</td>
                </tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
