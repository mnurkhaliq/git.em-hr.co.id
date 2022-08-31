@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Business Trip Date </th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->tanggal_kegiatan_start)) }} - {{ date('d F Y', strtotime($data->tanggal_kegiatan_end)) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Business Trip Type </th>
			<th style="text-align: left;"> : {{ isset($data->training_type) ? $data->training_type->name:''}}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Destination </th>
			<th style="text-align: left;"> : {{ $data->tempat_tujuan }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Activity Topic </th>
			<th style="text-align: left;"> : {{ $data->topik_kegiatan }}</th>
		</tr>
		@if(isset($total))
		<tr>
			<th style="text-align: left;">Total Amount </th>
			<th style="text-align: left;"> : {{ $total }}</th>
		</tr>
		@endif
	</thead>
</table>
<br />	
<div class="modal-body" id="modal_content_history_approval">
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
</div>
@endsection