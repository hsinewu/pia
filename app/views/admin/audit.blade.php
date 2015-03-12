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

    div.audit{
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
  <script src="{{ asset('assets/js/form.js'); }}"></script>

  <script type="text/javascript">
    var i = 1;
    function add_item(){
            var a = $('#sample-item').clone().attr({"class":"audit", "id":"audit"+i});
            a.find(".select").attr("class", "select"+i);
            a.find("#date_timepicker_start").attr("id", "date_timepicker_start"+i);
            a.find("#date_timepicker_end").attr("id", "date_timepicker_end"+i);
            a.find("input,select").each(function(cnt,ele){
              $(ele).attr('name',$(ele).attr('name') + '[' + i + ']');
            });

            if(i==1){
              a.attr("style", "padding-top: 0px;");
            }else if(i%2==0){
              a.attr("style", "background-color: #eee;");
            }
            a.insertBefore('.add-item');

            $('.select'+i).each(function(cnt,ele){
              ele = $(ele);
              ele.val(ele.attr('value'));
              ele.selectpicker('refresh');
            });

            jQuery('#date_timepicker_start'+i).datetimepicker({
              format:'Y-m-d H:i:s',
              onShow:function( ct ){
                this.setOptions({
                  maxDate:jQuery('#date_timepicker_end'+i).val()?jQuery('#date_timepicker_end'+i).val():false
                })
              },
            });
            jQuery('#date_timepicker_end'+i).datetimepicker({
              format:'Y-m-d H:i:s',
              onShow:function( ct ){
                this.setOptions({
                  minDate:jQuery('#date_timepicker_start'+i).val()?jQuery('#date_timepicker_start'+i).val():false
                })
              },
            });
            i++;
            return false;
          }

    $(document).ready(function(){

      //$('#sample-item').find(".selectpicker").attr("id", "pick");
      $('#audit').find('.select').each(function(cnt,ele){
        ele = $(ele);
        ele.val(ele.attr('value'));
        ele.selectpicker('refresh');
      });
      //$('#sample-item').clone().removeClass("hidden").attr("id", "").insertBefore('.add-item');

      $('.add-item').click(add_item);
      add_item();
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
        <div class="form-group">
          <label class="col-sm-2 control-label">受稽單位</label>
          <div class="col-sm-10">

            @include('macro/select',array(
              'type' => "dept",
              'name' => "ad_dept_id",
              'value' => "",
            ))
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">開始時間</label>
          <div class="col-sm-10">
            <input type="date_timepicker_start" id="date_timepicker_start" name="ad_time_from" class="form-control" placeholder="開始時間" value="">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">結束時間</label>
          <div class="col-sm-10">
            <input type="date_timepicker_end" id="date_timepicker_end" name="ad_time_end" class="form-control" placeholder="結束時間" value="">
          </div>
        </div>
      </div>

      <form class="form-horizontal" role="form" action="{{ route('add_autitor_process') }}" method="POST">
        <div id="audit">
          <div class="form-group">
            <label class="col-sm-2 control-label">稽核事件</label>
            <div class="col-sm-10">
              @include('macro/select',array(
                'type' => "event",
                'name' => "event_id",
                'value' => "",
              ))
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">稽核人</label>
            <div class="col-sm-10">
              @include('macro/select',array(
                'type' => "auditor",
                'name' => "p_id",
                'value' => "",
              ))
            </div>
          </div>
        </div>

        <div class="pull-right add-item">
          <a href="#">+ 新增更多受稽單位</a>
        </div>
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
