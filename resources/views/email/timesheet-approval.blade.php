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
        <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th scope="col" style="border:1px solid black; padding: 0 5px;">No</th>
                    <th scope="col" style="border:1px solid black; padding: 0 5px;">Date</th>
                    <th scope="col" style="border:1px solid black; padding: 0 5px;">Category</th>
                    <th scope="col" style="border:1px solid black; padding: 0 5px;">Start</th>
                    <th scope="col" style="border:1px solid black; padding: 0 5px;">End</th>
                    <th scope="col" style="border:1px solid black; padding: 0 5px;">Approver</th>
                    <th scope="col" style="border:1px solid black; padding: 0 5px;">Status</th>
                </tr>
            </thead>
            <tbody>
		    @foreach($data->timesheetPeriodTransaction as $key => $item)
                <tr>
                    <th scope="row" style="border:1px solid black; padding: 0 5px;">{{ $key+1 }}</th>
                    <td style="border:1px solid black; padding: 0 5px;">{{ $item->date }}</td>
                    <td style="border:1px solid black; padding: 0 5px;">{{ $item->timesheetCategory->name }}</td>
                    <td style="border:1px solid black; padding: 0 5px;">{{ $item->start_time }}</td>
                    <td style="border:1px solid black; padding: 0 5px;">{{ $item->end_time }}</td>
                    <td style="border:1px solid black; padding: 0 5px;">{{ $item->userApproved ? $item->userApproved->name : null }}</td>
                    @if($item->status == 3)
                        <td style="border:1px solid black; padding: 0 5px;">
                            <strong style="color: red;">REVISION</strong>
                        </td>
                    @else
                        <td style="border:1px solid black; padding: 0 5px;">
                            <strong style="color: green;">APPROVED</strong>
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
	</div>
</div>

@endsection