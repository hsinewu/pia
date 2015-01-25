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

          <!-- Modal -->
        </div>

        <div class="panel-body">
          <div class="col-lg-12">
            <div class="input-group">
              <span class="input-group-addon">filter</span>
              <input type="text" class="form-control">
            </div><!-- /input-group -->
          </div><!-- /.col-lg-12 -->

        </div>

        <!-- Table -->
        <table class="table table-hover">
          <thead>
            <tr>
              <td>開始時間</td>
              <td>結束時間</td>
              <td>回報流水號</td>
              <td>回報狀態</td>
              <td>動作</td>
            </tr>
          </thead>
          <tbody>
            @foreach($audits as $a)
              <tr>
                  <td>{{ $a->ad_time_from }}</td>
                  <td>{{ $a->ad_time_end }}</td>
                  @if( !is_null($r = $a->report()->first()))
                  <td>{{ $r->r_serial }}</td>
                  <td>{{ $r->status }}</td>
                  @else
                  <td>未有回報</td>
                  <td>未有回報</td>
                  @endif
                <td>
                  -
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
