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
  </style>
@stop

@section('footer_scripts')
  @parent
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/moment-with-locales.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.js"></script>

  <script>
    $(document).ready(function(){
      $('#calendar').fullCalendar({
          // put your options and callbacks here
      });
    });
  </script>
@stop

@section('content')

  <div class="row">
    <div class="col-xs-3">
      <div class="alert alert-info" role="alert">
        xxx您好
      </div>
      <div class="list-group">
        <a class="list-group-item" href="{{ route('admin_info','dept') }}">單位資料表</a>
        <a class="list-group-item" href="{{ route('admin_info','person') }}">人員資料表</a>
        <a class="list-group-item" href="{{ route('admin_info','audit') }}">稽核設定</a>
        <a class="list-group-item" href="{{ route('admin_cal') }}">行事曆</a>
        <a class="list-group-item" href="{{ route('admin_info','event') }}">事件設定</a>
      </div>
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
