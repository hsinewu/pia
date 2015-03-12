<select class="select" id="filter-event">
	<option value="ALL">全部</option>
@foreach(PiaEvent::all() as $e)
	<option value="{{$e->event_id}}">{{$e->event_id}}</option>
@endforeach
</select>