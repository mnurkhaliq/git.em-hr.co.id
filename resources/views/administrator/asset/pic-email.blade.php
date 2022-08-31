@extends('email.general')

@section('content')

<h3>Assiged as PIC Asset</h3>
<p>You have been assigned as PIC of this asset with following detail:</p>
<table>
	<tr>
		<td>PIC Name</td>
		<td> : {{ $data->pic->name }}</td>
	</tr>
	<tr>
		<td>Number of Asset</td>
		<td> : {{ $data->asset_number }}</td>
	</tr>
	<tr>
		<td>Asset Name</td>
		<td> : {{ $data->asset_name }}</td>
	</tr>
	<tr>
		<td>Type of Asset</td>
		<td> : {{ $data->asset_type->name }}</td>
	</tr>
	<tr>
		<td>Serial Number</td>
		<td> : {{ $data->asset_sn }}</td>
	</tr>
	<tr>
		<td>Specification :</td>
		<td>{!! $data->spesifikasi !!}</td>
	</tr>
	<tr>
		<td>Asset Condition</td>
		<td> : {{ $data->asset_condition }}</td>
	</tr>
</table>
@endsection