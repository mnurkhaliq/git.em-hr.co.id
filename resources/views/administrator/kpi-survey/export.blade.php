<!DOCTYPE html>
<html>
<body>
	<table>
		<thead>
			<tr>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;" width="10"><strong>No</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Period</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Employee ID</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Employee Name</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Position</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Supervisor</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Status</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Organization KPI Score</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Manager KPI Score</strong></td>
				<td style="border: 1px solid #000000; background:#7f7f7f;color: #ffffff;"><strong>Final Score</strong></td>
			</tr>
		</thead>
		<tbody>

			@foreach($data['data'] as $key => $item)
				@php(info($item));
			<tr>
				<td style="border: 1px solid #000000;">{{$key+1}}</td>
				<td style="border: 1px solid #000000;">{{$item->period}}</td>
				<td style="border: 1px solid #000000;">{{$item->nik}}</td>
				<td style="border: 1px solid #000000;">{{$item->name}}</td>
				<td style="border: 1px solid #000000;">{{$item->position}}</td>
				<td style="border: 1px solid #000000;">{{$item->supervisor}}</td>
				<td style="border: 1px solid #000000;">{{$item->status}}</td>
				<td style="border: 1px solid #000000;">{{$item->organization_score}}</td>
				<td style="border: 1px solid #000000;">{{$item->manager_score}}</td>
				<td style="border: 1px solid #000000;">{{$item->final_score}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
