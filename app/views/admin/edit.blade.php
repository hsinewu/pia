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
      $('.select').each(function(cnt,ele){
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

  <script>
    var val=parseInt($('#level').find('input').first().attr('value'));
    //if(val & 1)  $('#checkbox_1').prop("checked", true);
    //if(val & 2)  $('#checkbox_2').prop("checked", true);
    //if(val & 4)  $('#checkbox_4').prop("checked", true);
    for(var i=1; i<=16; i*=2){
      if(val & i)  $('#checkbox_'+i).prop("checked", true);
    }

    $(document).ready(function(){
      $('.level').click(function(){
        if($(this).prop("checked")==true) val+=parseInt($(this).attr('value'));
        else  val-=parseInt($(this).attr('value'));
        $('#level').find('input').first().attr('value', val);
        //console.log(val);
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

      <form class="form-horizontal" role="form" action="{{ is_null($id) ? route('admin_edit_process',$type) : route('admin_edit_process',array($type,$id)) }}" method="POST">
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
          @elseif(strpos($v[0],"checkbox") !== false)
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ $v[2] }}</label>
              <div class="col-sm-10">
                @include('macro/checkbox',array(
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
