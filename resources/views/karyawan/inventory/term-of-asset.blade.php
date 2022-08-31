<!DOCTYPE html>
<html>
<head>
	<title>Term and Agreement of Asset</title>
	<style type="text/css">
		table {
			border-collapse: collapse;
			border-spacing: 0;
		}
		table.border tr th, table.border tr td {
			/* border: 1px solid black; */
			padding: 5px 10px;
		}
		.page-break {
			page-break-after: always;
		}
	</style>
</head>
<body>
	{{--<img src="{{  asset(get_setting('logo')) }}" style="height: 40; float: right;" />--}}
	<img src="{{  public_path(get_setting('logo')) }}" style="height: 40; float: left;" />
	{{-- <h3>{{ get_setting('title') }} </h3> --}}
	<br/>
    <br/>
	<h4 style="text-align:center;"><b>MINUTES OF ASSETS TRANSFER</b></h4>
	<h4 style="text-align:center;"><b>{{ get_setting('title') }}</b></h4>
    <p> I, undersigned below: </p>
	<table style="width: 80%;  margin-left: auto; margin-right: auto;">
		<tr>
			<th style="text-align: left; width:20%;">Name</th>
			<th style="text-align: center; width:10%;">:</th>
			<th style="text-align: left; width:50%;"> {{ $tracking->user != null ? $tracking->user->name : '' }}</th>
		</tr>
		<tr>
			<th style="text-align: left; width:20%;">Position</th>
			<th style="text-align: center; width:10%;">:</th>
			<th style="text-align: left; width:50%;"> {{ $tracking->user != null ? empore_jabatan($tracking->user->id) : ''  }}</th>
		</tr>
		<tr>
			<th style="text-align: left; width:20%;">ID Number</th>
			<th style="text-align: center; width:10%;">:</th>
			<th style="text-align: left; width:50%;"> {{$tracking->user != null ? $tracking->user->ktp_number : '-' }}</th>
		</tr>
	</table>
	<p>Explaining that on  {{date('d F Y', strtotime($tracking->handover_date))}}, the company's assets have been handed over to me, with the following details:</p>
	<table  style="width: 100%;  margin-left: auto; margin-right: auto;">
		<tr>
			<th style="text-align: left; width: 30%;">Number of Asset</th>
			<th style="text-align: center;width: 10%;">:</th>
			<th style="text-align: left; width: 60%;"> {{ $tracking->asset->asset_number }}<</th>
		</tr>
		<tr>
			<th style="text-align: left; width: 30%;">Asset Name</th>
			<th style="text-align: center;width: 10%;">:</th>
			<th style="text-align: left; width: 60%;"> {{ $tracking->asset->asset_name }}<</th>
		</tr>
		<tr>
			<th style="text-align: left; width: 30%;">Type of Asset</th>
			<th style="text-align: center;width: 10%;">:</th>
			<th style="text-align: left; width: 60%;"> {{ isset($tracking->asset->asset_type->name) ? $tracking->asset->asset_type->name : ''  }}</th>
		</tr>
		<tr>
			<th style="text-align: left; width: 30%;">Serial Number</th>
			<th style="text-align: center;width: 10%;">:</th>
			<th style="text-align: left; width: 60%;"> {{ $tracking->asset->asset_sn }}</th>
		</tr>
		<tr>
			<th style="text-align: left; width: 30%;">Specification</th>
			<th style="text-align: center;width: 10%;">:</th>
			<th style="text-align: left; width: 60%;"> {!! isset($tracking->asset->spesifikasi) ?  $tracking->asset->spesifikasi : '' !!}</th>
		</tr>
		<tr>
			<th style="text-align: left; width: 30%;">Condition</th>
			<th style="text-align: center;width: 10%;">:</th>
			<th style="text-align: left; width: 60%;"> {{ $tracking->asset->asset_condition }}</th>
		</tr>
	</table>
    <br>
	@if(get_setting('term_and_agreement_asset') == '')
    <p style=" text-align: justify; text-justify: inter-word;">After a joint inspection, the assets in good condition, can function properly and can be used properly, then I declare that I am willing to promise to</p>
	<p style=" text-align: justify; text-justify: inter-word;"> 1.	Maintain (cleanliness, integrity and security) of the company's assets as mentioned above as much as possible;</p>
    <p style=" text-align: justify; text-justify: inter-word;"> 2.	If there is damage or loss of the asset caused by my carelessness or negligence, bear the cost of repair and or replacement of spare parts or replacement;</p>
    <p style=" text-align: justify; text-justify: inter-word;"> 3.	Use these assets in the right way, only on behalf and purposes of my job duties and responsibilities on {{ get_setting('title') }}/{{ get_setting('title') }} Clients;</p>
    <p style=" text-align: justify; text-justify: inter-word;"> 4.	Only use original software and if there is a need to install software with the knowledge of {{ get_setting('title') }};</p>
    <p style=" text-align: justify; text-justify: inter-word;"> 5.	Return and hand over the assets directly to the unit appointed by the company in intact condition and can still be used properly until my employment relationship ends for any reason or if at any time the company wants me to return the asset.</p>
	@else
	<p style=" text-align: justify; text-justify: inter-word;">After a joint inspection, the assets in good condition, can function properly and can be used properly, then I declare that I am willing to promise to</p>
    {!! get_setting('term_and_agreement_asset') !!}
	@endif
	<br>
	<br>
	<table  style="width: 100%;">
		<tr>
			<th style="text-align: right; float: right;">Jakarta, {{date('d F Y', strtotime($tracking->handover_date))}}</th>
		</tr>
		<tr>
			<th style="text-align: right; float: right;"><b><h3>APPROVED</h3></b></th>
		</tr>
		<tr>
			<th style="text-align: right; float: right;">{{$tracking->user->name }}</th>
		</tr>
	</table>
</body>
</html>
