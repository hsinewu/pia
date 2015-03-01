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
              <td>流水號</td>
              <td>狀態</td>
              <td>動作</td>
            </tr>
          </thead>
          <tbody>
            @foreach($reports as $r)
              <tr>
                <td>{{ $r->r_serial }}</td>
                <td>{{ $r->status }}</td>
                <td>
                  <a href="{{ route('admin_view_report',$r->r_id) }}" class="preview">觀看回報內容</a>

                  @if($show_sub_item = $r->is_finished())
                    ｜
                    @if($show_sub_item = count($items = $r->items()->get()->all()))
                        <a onclick="$('.{{ $r->r_serial }}').toggle()" href="#">稽核發現</a>
                    @else
                        沒有稽核發現
                    @endif
                  @endif
                </td>
              </tr>
              @if($show_sub_item)
                <tr class="{{ $r->r_serial }}" style="display:none;">
                  <td>條款</td>
                  <td>發現</td>
                  <td>狀態，點擊觀看詳細訊息</td>
                </tr>
                @foreach($items as $i)
                  <tr class="{{ $r->r_serial }}" style="display:none;">
                    <td>{{ $i->ri_base }}</td>
                    <td>{{ $i->ri_discover }}</td>
                    <td><a href="{{ route('auditee_feedback',$i->ri_id) }}">{{ $i->ri_status }}</a></td>
                  </tr>
                @endforeach
              @endif
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
