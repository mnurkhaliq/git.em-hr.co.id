<!DOCTYPE html>
<html>
<head>
	<title>Approval Facilities {{ $tracking->user->nik .'/'. $tracking->user->name }}</title>
	<style type="text/css">
		table {
			border-collapse: collapse;
			border-spacing: 0;
		}
		table.border tr th, table.border tr td {
			border: 1px solid black;
			padding: 5px 10px;
		}
		.page-break {
			page-break-after: always;
		}
	</style>
</head>
<body>
	{{--<img src="{{  asset(get_setting('logo')) }}" style="height: 40; float: right;" />--}}
	<img src="{{  public_path(get_setting('logo')) }}" style="height: 40; float: right;" />
	<h3>{{ get_setting('title') }} </h3>
	<p><strong>Dear Managers,</strong></p>
	<br/>
    <br/>
    <p>{{$tracking->user->name }} has returned the assets with following details:</p>
	<table style="width: 100%;" class="border">
		<tr>
			<th style="border-bottom: 1px solid black;">NO</th>
            <th style="border-bottom: 1px solid black;">ASSET NUMBER</th>
            <th style="border-bottom: 1px solid black;">ASSET NAME</th>
            <th style="border-bottom: 1px solid black;">ASSET TYPE</th>
            <th style="border-bottom: 1px solid black;">SERIAL/PLAT NUMBER</th>
			<th style="border-bottom: 1px solid black;">SPECIFICATION</th>
            <th style="border-bottom: 1px solid black;">PURCHASE/RENTAL DATE</th>
            <th style="border-bottom: 1px solid black;">ASSET CONDITION</th>
            <th style="border-bottom: 1px solid black;">STATUS ASSET</th>
            <th style="border-bottom: 1px solid black;">PIC</th>
		</tr>
		<tr>
			<td style="border-bottom: 1px solid black;">1</td>   
            <td style="border-bottom: 1px solid black;">{{ $tracking->asset->asset_number }}</td>
            <td style="border-bottom: 1px solid black;">{{ $tracking->asset->asset_name }}</td>
            <td style="border-bottom: 1px solid black;">{{ isset($tracking->asset->asset_type->name) ? $tracking->asset->asset_type->name : ''  }}</td>
            <td style="border-bottom: 1px solid black;">{{ $tracking->asset->asset_sn }}</td>
			<td style="border-bottom: 1px solid black;">{!! isset($tracking->asset->spesifikasi) ?  $tracking->asset->spesifikasi : ''!!}</td>
            <td style="border-bottom: 1px solid black;">{{ format_tanggal($tracking->asset->purchase_date) }}</td>
            <td style="border-bottom: 1px solid black;">{{ $tracking->asset_condition_return }}</td>
            <td style="border-bottom: 1px solid black;">{{ $tracking->assign_to}}</td>
            <td style="border-bottom: 1px solid black;">{{ $tracking->user->name }}</td>
		</tr>
	</table>
    <br>
    <br>
    <p>On {{date('d F Y', strtotime($tracking->date_return))}}, with this document is legitimized that the user has agreed to return of asset.</p>
	<br>
	<br>
	<table  style="width: 100%;">
		<tr>
			<th style="text-align: center; ">Requester</th>
			<th style="text-align: center;">Approved</th>
		</tr>
		<tr>
			<th style="text-align: center; "><b><h3>SUBMITTED</h3></b></th>
			<th style="text-align: center;"><b><h3></h3></b></th>
		</tr>
		<tr>
			<th style="text-align: center;">{{$tracking->user->name }}</th>
			<th style="text-align: center;">Admin</th>
		</tr>
	</table>
</body>
</html>
