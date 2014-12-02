@extends('master')


@section('head_css')
  @parent

  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.print.css">

  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/tokenfield-typeahead.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">

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
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/moment-with-locales.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.js"></script>

  <script>
    

    $(document).ready(function() {
      
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,basicWeek,basicDay'
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

      <div class="alert alert-info">{{ $title }}</div>
      <div id="calendar"></div>
    </div>
  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
