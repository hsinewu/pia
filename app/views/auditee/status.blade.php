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

          <!-- Modal -->
        </div>

        <div class="panel-body">
            <div class="col-xs-3">
                @include("macro/event_filter")
            </div><!-- /.col-lg-9 -->
            <div class="col-xs-9">
              <div class="input-group">
                <span class="input-group-addon">filter</span>
                <input class="form-control" id="filter" type="text">
              </div><!-- /input-group -->
          </div><!-- /.col-lg-9 -->
        </div>

        <!-- Table -->
        <table class="table table-hover">
          <thead>
            <tr>
              <td>所屬事件</td>
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
                  <td>{{ $a->event_id }}</td>
                  <td>{{ $a->ad_time_from }}</td>
                  <td>{{ $a->ad_time_end }}</td>
                  @if( $show_sub_item = !is_null($r = $a->report()->first()))
                  @if( $show_sub_item = $r->is_finished() )
                  <td>{{ $r->r_serial }}</td>
                  <td>{{ $r->status }}</td>
                  <td><a class="expand" for="{{ $r->r_serial }}" href="#">展開矯正預防清單</a>｜<a href="{{ route('auditee_view_report', $r->r_id) }}">觀看報告</a></td>
                  @else
                  <td>{{ $r->r_serial }}</td>
                  <td>尚未回報完成</td>
                  <td>-</td>
                  @endif
                  @else
                  <td>未有回報</td>
                  <td>未有回報</td>
                  <td>-</td>
                  @endif
              </tr>
              @if( $show_sub_item && !is_null($items = ($r->items->all())))
                <tr class="{{ $r->r_serial }} sub_item sub_head">
                  <td></td>
                  <td>\\</td>
                  <td>條款</td>
                  <td>發現</td>
                  <td>建議</td>
                  <td>狀態以及動作</td>
                </tr>
                @foreach($items as $i)
                <tr class="{{ $r->r_serial }} sub_item">
                  <td></td>
                  <td>--</td>
                  <td>{{ $i->ri_base }}</td>
                  <td>{{ $i->ri_discover }}</td>
                  <td>{{ $i->ri_recommand }}</td>
                  <td>
                      @if($i->is_editable())
                        <a href="{{ route('auditee_feedback',$i->ri_id) }}">{{ $i->ri_status }}，填寫矯正預防</a>
                      @else
                        {{ $i->ri_status }}
                      @endif
                        ｜<a href="{{ route('auditee_view_report_item',$i->ri_id) }}">觀看矯正預防報告</a>
                  </td>
                </tr>
                @endforeach
                </div>
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

@stop
