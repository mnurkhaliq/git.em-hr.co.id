@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Resign Date </th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->resign_date)) }}</th>
		</tr>
	</thead>
</table>
<br />	
<div class="modal-body" id="modal_content_history_approval">
	<h4>Exit Interview</h4>
	<div class="panel-body">
		@foreach($value as $key => $item)
			<table>
				<tr>
					<th style="text-align: left;">{{$item->level->name}} </th>
					<th style="text-align: left;"> : {{ (isset($item->structure->position) ? $item->structure->position->name:'').(isset($item->structure->division) ? ' - '.$item->structure->division->name:'').(isset($item->structure->title) ? ' - '.$item->structure->title->name:'') }}</th>

				</tr>
			</table>
		@endforeach
	</div>
	<h4>Exit Clearance</h4>
	<div class="panel-body">
		@if($assets && count($assets)>0)
			@foreach($assets as $key => $item)
				<table>
					<tr>
						<th style="text-align: left;">Asset {{$item->asset->asset_name}}</th>
						<th style="text-align: left;"> : {{$item->userApproved?($item->userApproved->name)." (".$item->asset->asset_type->pic_department.")":""}}</th>

					</tr>
				</table>
			@endforeach
		@else
			No Asset
		@endif
	</div>
</div>
@endsection