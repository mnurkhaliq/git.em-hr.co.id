@if(Session::has('message-success'))
<script type="text/javascript">
	_alert("{{ Session::pull('message-success') }}");
</script>
@endif

@if(Session::has('message-error'))
<script type="text/javascript">
	_alert_error("{{ Session::pull('message-error') }}");
</script>
@endif

@if(Session::has('message-error-format'))
<script type="text/javascript">
	_alert_error("{!! Session::pull('message-error-format') !!}");
</script>
@endif