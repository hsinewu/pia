@extends('master')


@section('head_css')
  @parent

  <link href="http://fullcalendar.io/js/fullcalendar-2.2.3/fullcalendar.css" rel="stylesheet">
  <link href="http://fullcalendar.io/js/fullcalendar-2.2.3/fullcalendar.print.css" rel="stylesheet" media="print">

    <style>
    table.table.table-hover td.action{
      text-align: center;
    }

    table.table tr.sample{
      display: none;
    }

    #calendar .fc-button-group, .fc button{
      display: block;
    }

    td.fc-state-highlight.fc-today{
      color: rgba(243, 120, 0, 1);
    }
  </style>
@stop

@section('footer_scripts')
  @parent
  <script src="http://fullcalendar.io/js/fullcalendar-2.2.3/lib/moment.min.js"></script><style type="text/css"></style>
  <script src="http://fullcalendar.io/js/fullcalendar-2.2.3/fullcalendar.min.js"></script>

  <script>
    $(document).ready(function() {

      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
                  right: 'month,agendaWeek,agendaDay'

        },
        events: [
          @foreach($info as $row)
          {
            title: '{{ $row->p_name }} => {{ $row->dept_name }}',
            start: '{{ $row->ad_time_from }}',
            end: '{{ $row->ad_time_end }}'
          },
          @endforeach
        ]
      });

    });
  </script>

@stop

@section('content')

  <div class="row">
    <div class="col-xs-3">
      @include("menu")
    </div>
    <div class="col-xs-9">
      <div id="calendar"></div>
    </div>
  </div>

@stop
