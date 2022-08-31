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
	<div class="panel-body">
		@foreach($assets as $key => $item)
			<table>
				<tr>
					<th style="text-align: left;">Asset {{$item->asset->asset_name}}</th>
					<th style="text-align: left;"> : {{$item->userApproved?($item->userApproved->name)." (".$item->asset->asset_type->pic_department.")":""}}</th>

				</tr>
			</table>
		@endforeach
	</div>
</div>
@endsection