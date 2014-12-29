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
    .add-item{
      padding-bottom: 10px;
    }

    div.item{
      padding-top: 10px;
      padding-bottom: 10px;
      border-radius: 6px;
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
    var i = 1;

    function add_item(){
      var new_item = $('#sample-item').clone().attr({"class":"item", "id":"item"+i});
      new_item.find("textarea,select").each(function(cnt,ele){
        $(ele).attr('name',$(ele).attr('name') + '[' + i + ']');
      });
      if(i%2==0){
        new_item.attr("style", "background-color: #eee;");
      }
      new_item.find('select').each(function(cnt,ele){
        ele = $(ele);
        ele.val(ele.attr('value'));
        ele.selectpicker('refresh');
      });
      new_item.removeClass("hidden").insertBefore('.add-item');
      i++;
      return false;
    }

    $(document).ready(function(){
      $('form .selectpicker').each(function(cnt,ele){
        ele = $(ele);
        ele.val(ele.attr('value'));
        ele.selectpicker('refresh');
      });

      jQuery('form [type^=date_timepicker]').datetimepicker();

      $('.add-item').click(add_item);
      add_item();

    });

  </script>

@stop

@section('content')

  <div class="row">
    <div class="col-xs-3">
      @include("audit_menu")
    </div>
    <div class="col-xs-9">

    <div id="sample-item" class="hidden">
      <div class="form-group">
        <label class="col-sm-2 control-label">標準條文 / 稽核項目</label>
        <div class="col-sm-10">
          @include('macro/select_report',array(
            'name' => "ri_base",
            'value' => "",
          ))
        </div>
      </div>
      <textarea class="form-control" name="ri_discover" rows="3" placeholder="稽核發現"></textarea>
      <div class="row">&nbsp;</div>
      <textarea class="form-control" name="ri_recommand" rows="3" placeholder="稽核建議"></textarea>
    </div>

      {{ Form::open(array('url' => route('audit_report_process',$audit->a_id), 'class'=>'form-horizontal', 'role'=>'form')) }}
        <div class="form-group">
          <label class="col-sm-2 control-label">紀錄編號</label>
          <div class="col-sm-10">
            <input type="text" name="r_serial" class="form-control" placeholder="紀錄編號" value="{{ $report->r_serial }}">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">填表日期</label>
          <div class="col-sm-10">
            <input type="date_timepicker" name="r_time" class="form-control" placeholder="填表日期" value="{{ $report->r_time }}">
          </div>
        </div>
        <div class="col-md-12 add-item">
          <a href="#" class="pull-right">+ 新增稽核發現</a>
        </div>
        <hr>
        <div class="form-group">
          <label class="col-sm-2 control-label">其他建議</label>
          <div class="col-sm-10">
            <input type="text" name="r_msg" class="form-control" placeholder="其他建議" value="{{ $report->r_msg }}">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="status" value="1" class="btn btn-default">送出</button>
            <button type="submit" name="status" value="0" class="btn btn-default">暫存</button>
          </div>
        </div>
      {{ Form::close() }}
    </div>
  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
