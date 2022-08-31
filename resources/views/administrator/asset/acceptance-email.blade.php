@extends('email.general')

@section('content')

<h3>Acceptance Report</h3>
<p>The company handover/deliver asset to employee with following detail:</p>
<table>
	<tr>
		<td>Employee Name</td>
		<td> : {{ $data->user->name }}</td>
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
<h3>Term and Agreement of Asset</h3>
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
<a href="{{ route('accept-asset', ['id'=>$data->encrypted_key,'company'=>session('company_url','')]) }}" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: #1e88e5; border-radius: 60px; text-decoration:none;">Accept</a>
<a href="{{ route('reject-asset', ['id'=>$data->encrypted_key,'company'=>session('company_url','')]) }}" style="display: inline-block; padding: 11px 30px; margin: 20px 0px 30px; font-size: 15px; color: #fff; background: red; border-radius: 60px; text-decoration:none;">Reject</a>
@endsection