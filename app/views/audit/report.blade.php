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

    function add_item(){
      $('#sample-item').clone().removeClass("hidden").insertBefore('.item-end');
      return false;
    }

    $(document).ready(function(){
      $('.selectpicker').each(function(cnt,ele){
        ele = $(ele);
        ele.val(ele.attr('value'));
        ele.selectpicker('refresh');
      });

      $('.add-item').click(add_item);
      add_item();

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
      @include("menu")
    </div>
    <div class="col-xs-9">

    <div id="sample-item" class="hidden">
      <hr>
      <div class="form-group">
        <label class="col-sm-2 control-label">標準條文 / 稽核項目</label>
        <div class="col-sm-10">
          @include('macro/select',array(
            'type' => "report_base",
            'name' => "ri_base[]",
            'value' => "",
          ))
        </div>
      </div>
      <textarea class="form-control" name="ri_discover[]" rows="3" placeholder="稽核發現"></textarea>
      <div class="row">&nbsp;</div>
      <textarea class="form-control" name="ri_recommand[]" rows="3" placeholder="稽核建議"></textarea>
    </div>

      {{ Form::open(array('url' => route('audit_report',$audit->a_id), 'class'=>'form-horizontal', 'role'=>'form')) }}
        <div class="form-group">
          <label class="col-sm-2 control-label">紀錄編號</label>
          <div class="col-sm-10">
            <input type="text" name="r_id" class="form-control" placeholder="紀錄編號" value="{{ $report->r_serial }}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">填表日期</label>
          <div class="col-sm-10">
            <input type="date_timepicker" name="r_id" class="form-control" placeholder="填表日期" value="{{ date('Y-m-d') }}">
          </div>
        </div>
        <hr class="item-end">
        <div class="form-group">
          <label class="col-sm-2 control-label">其他建議</label>
          <div class="col-sm-10">
            <input type="text" name="ri_otherMsg" class="form-control" placeholder="其他建議" value="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">送出</button>
            <button class="btn btn-default add-item">增加稽核發現</button>
          </div>
        </div>
      {{ Form::close() }}
    </div>
  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
