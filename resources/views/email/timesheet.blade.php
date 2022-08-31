@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Timesheet Period</th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->start_date)) }} - {{ date('d F Y', strtotime($data->end_date)) }}</th>
		</tr>
	</thead>
</table>
<br />	

List Approval :
<div class="modal-body" id="modal_content_history_approval">
	<div class="panel-body">
		@foreach($value as $item)
			<table>
				<tr>
					<th style="text-align: left;">Category {{ $item->name }} : </th>
                </tr>
                @if($item->settingApproval->count())
                    @foreach($item->settingApproval as $key => $item2)
                        <tr>
                            <td style="text-align: left;">{{ ($key+1).". ".$item2->user->nik." - ".$item2->user->name }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td style="text-align: left;">-</td>
                    </tr>
                @endif
			</table>
		@endforeach
	</div>
</div>

@endsection