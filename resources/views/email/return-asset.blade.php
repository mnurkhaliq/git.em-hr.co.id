@extends('email.general',['logo' => $logo ?? null,'title' => $title ?? null,'mail_signature' => $mail_signature ?? null])

@section('content')
{!! $text !!}

<table>
	<thead>
        <th style="text-align: left;">NO</th>
		<th style="text-align: left;">ASSET NUMBER</th>
        <th style="text-align: left;">ASSET NAME</th>
        <th style="text-align: left;">ASSET TYPE</th>
        <th style="text-align: left;">SERIAL/PLAT NUMBER</th>
        <th style="text-align: left;">PURCHASE/RENTAL DATE</th>
        <th style="text-align: left;">ASSET CONDITION</th>
        <th style="text-align: left;">STATUS ASSET</th>
        <th style="text-align: left;">PIC</th>
	</thead>
    <tbody>
        <td class="text-center">1</td>   
        <td>{{ $data->asset_number }}</td>
        <td>{{ $data->asset_name }}</td>
        <td>{{ isset($data->asset_type->name) ? $data->asset_type->name : ''  }}</td>
        <td>{{ $data->asset_sn }}</td>
        <td>{{ format_tanggal($data->purchase_date) }}</td>
        <td>{{ $data->asset_condition }}</td>
        <td>{{ $data->assign_to}}</td>
        <td>{{ $data->user->name }}</td>
    </tbody>
</table>

On {{$data->handover_date}}, with this document is legitimized that the user has agreed to return of asset.


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