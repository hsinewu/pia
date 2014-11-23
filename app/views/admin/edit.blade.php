@extends('master')


@section('head_css')
  @parent
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css">

  <style>
    table.table.table-hover td.action{
      text-align: center;
    }

    table.table tr.sample{
      display: none;
    }
  </style>
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.datetimepicker.css'); }}"/ >
@stop

@section('footer_scripts')
  @parent
  <script src="{{ asset('assets/js/jquery.datetimepicker.js'); }}"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/i18n/defaults-zh_TW.min.js"></script>


  <script type="text/javascript">

    $(document).ready(function(){
      $('.selectpicker').each(function(cnt,ele){
        ele = $(ele);
        ele.val(ele.attr('value'));
        ele.selectpicker('refresh');
      });
    });
    jQuery('[type^=date_timepicker]').datetimepicker();
    jQuery(function(){
      jQuery('#date_timepicker_start').datetimepicker({
        format:'Y/m/d H:i',
        onShow:function( ct ){
          this.setOptions({
            maxDate:jQuery('#date_timepicker_end').val()?jQuery('#date_timepicker_end').val():false
          })
        },
      });
      jQuery('#date_timepicker_end').datetimepicker({
        format:'Y/m/d H:i',
        onShow:function( ct ){
          this.setOptions({
            minDate:jQuery('#date_timepicker_start').val()?jQuery('#date_timepicker_start').val():false
          })
        },
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

      <form class="form-horizontal" role="form" action="{{ route('admin_edit_process',$type) }}" method="POST">
        @foreach($fields as $k => $v)
          @if(strpos($v[0],"select") !== false)
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ $v[2] }}</label>
              <div class="col-sm-10">
                @include('macro/select',array(
                  'type' => str_replace("select.", "", $v[0]),
                  'name' => $k,
                  'value' => $obj->$k,
                ))
              </div>
            </div>
          @else
          <div class="form-group">
            <label class="col-sm-2 control-label">{{ $v[2] }}</label>
            <div class="col-sm-10">
              <input type="{{ $v[0] }}" id="{{ $v[0] }}" name="{{ $k }}" class="form-control" placeholder="{{ $v[1] }}" value="{{ $obj->$k }}">
            </div>
          </div>
          @endif
        @endforeach
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">送出</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
