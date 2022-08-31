@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Request Date </th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->created_at)) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Position </th>
			<th style="text-align: left;"> : {{ $data->job_position }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Branch </th>
			<th style="text-align: left;"> : {{ $data->branch->name }}</th>
		</tr>
	</thead>
</table>
<br />	
<div class="modal-body" id="modal_content_history_approval">
	<div class="panel-body">
		@foreach($value as $key => $item)
			<table>
				<tr>
					<th style="text-align: left;">{{$item->level->name}} </th>
					<th style="text-align: left;"> : {{ $data->job_position }}</th>
					<th style="text-align: left;">
						@if($item->is_approved!=null)
							@if($item->is_approved == 1)
								<strong style="color: green;">APPROVED</strong>
							@elseif($item->is_approved == 0)
								<strong style="color: red;">REJECTED</strong>
							@endif
						@endif
					</th>
				</tr>
			</table>
		@endforeach
	</div>
</div>
@endsection