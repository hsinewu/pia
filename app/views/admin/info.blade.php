@extends('master')


@section('head_css')
  @parent

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
  <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.min.js"></script>
  <script src="{{ asset('assets/js/filter.js'); }}"></script>
@stop

@section('content')
  <div class="row">
    <div class="col-xs-3">
      @include("menu")
    </div>
    <div class="col-xs-9">

      <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading" style="height: 55px; line-height: 34px;">
        {{ $title }}

          <!-- Button trigger modal -->
          @if($obj->creatable)
              <a class="btn btn-primary pull-right create" href="{{ route('admin_edit', $type) }}">
                ＋ 新增
              </a>
          @endif
          <!-- Modal -->
        </div>

        <div class="panel-body">
          <div class="col-lg-12">
            <div class="input-group">
              事件過濾
              <select class="select" id="filter-event">
                <option value="ALL">全部</option>
                @foreach(PiaEvent::all() as $e)
                  <option value="{{$e->event_id}}">{{$e->event_id}}</option>
                @endforeach
              </select>
              <span class="input-group-addon">filter</span>
              <input type="text" class="form-control" id='filter'>
            </div><!-- /input-group -->
          </div><!-- /.col-lg-12 -->

        </div>

        <!-- Table -->
        <table class="table table-hover">
          <thead>
            <tr>
              @foreach($columns as $v)
                <td>{{ $v }}</td>
              @endforeach
              <td>動作</td>
            </tr>
          </thead>
          <tbody>
            @foreach($info as $i)
              <tr>
                @foreach($columns as $c => $v)
                  <td>{{ $i->$c }}</td>
                @endforeach
                <td>
                  <a href="{{ route('admin_edit', array($type,$i->{$obj->getPK()})) }}">Edit</a>
                  @if($obj->deletable)
                      or
                      <a href="{{ route('admin_del', array($type,$i->{$obj->getPK()})) }}">Delete</a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
