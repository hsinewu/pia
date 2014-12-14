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
      var i = 1;
      //$('#sample-item').find(".selectpicker").attr("id", "pick");
      $('#audit').find('.select').each(function(cnt,ele){
        ele = $(ele);
        ele.val(ele.attr('value'));
        ele.selectpicker('refresh');
      });
      //$('#sample-item').clone().removeClass("hidden").attr("id", "").insertBefore('.add-item');

      $('.add-item').click(function(){
        var a = $('#sample-item').clone().removeClass("hidden").attr("id", "audit"+i);
        a.find(".select").attr("class", "select"+i);
        a.find("#date_timepicker_start").attr("id", "date_timepicker_start"+i);
        a.find("#date_timepicker_end").attr("id", "date_timepicker_end"+i);

        a.insertBefore('.add-item');

        $('.select'+i).each(function(cnt,ele){
          ele = $(ele);
          ele.val(ele.attr('value'));
          ele.selectpicker('refresh');
        });

        jQuery(function(){
          jQuery('#date_timepicker_start'+i).datetimepicker({
            format:'Y/m/d H:i',
            onShow:function( ct ){
              this.setOptions({
                maxDate:jQuery('#date_timepicker_end'+i).val()?jQuery('#date_timepicker_end'+i).val():false
              })
            },
          });
          jQuery('#date_timepicker_end'+i).datetimepicker({
            format:'Y/m/d H:i',
            onShow:function( ct ){
              this.setOptions({
                minDate:jQuery('#date_timepicker_start'+i).val()?jQuery('#date_timepicker_start'+i).val():false
              })
            },
          });
        });
        i++;
        return false;
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
      @include("menu")
    </div>
    <div class="col-xs-9">

      <form class="form-horizontal" role="form" action="{{ is_null($id) ? route('admin_edit_process',$type) : route('admin_edit_process',array($type,$id)) }}" method="POST">
        <div id="audit">
          <div class="form-group">
            <label class="col-sm-2 control-label">稽核事件</label>
            <div class="col-sm-10">
              @include('macro/select',array(
                'type' => "event",
                'name' => "event_id",
                'value' => $obj->event_id,
              ))
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">稽核人</label>
            <div class="col-sm-10">
              @include('macro/select',array(
                'type' => "person",
                'name' => "p_id",
                'value' => $obj->p_id,
              ))
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">受稽單位</label>
            <div class="col-sm-10">
              @include('macro/select',array(
                'type' => "dept",
                'name' => "ad_dept_id",
                'value' => $obj->ad_dept_id,
              ))
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">開始時間</label>
            <div class="col-sm-10">
              <input type="date_timepicker_start" id="date_timepicker_start" name="ad_time_from" class="form-control" placeholder="開始時間" value="{{ $obj->ad_time_from }}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">結束時間</label>
            <div class="col-sm-10">
              <input type="date_timepicker_end" id="date_timepicker_end" name="ad_time_end" class="form-control" placeholder="結束時間" value="{{ $obj->ad_time_end }}">
            </div>
          </div>
        </div>


        <div id="sample-item" class="hidden">
          <hr>
          <div class="form-group">
            <label class="col-sm-2 control-label">受稽單位</label>
            <div class="col-sm-10">

              @include('macro/select',array(
                'type' => "dept",
                'name' => "ad_dept_id",
                'value' => $obj->ad_dept_id,
              ))
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">開始時間</label>
            <div class="col-sm-10">
              <input type="date_timepicker_start" id="date_timepicker_start" name="ad_time_from" class="form-control" placeholder="開始時間" value="{{ $obj->ad_time_from }}">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">結束時間</label>
            <div class="col-sm-10">
              <input type="date_timepicker_end" id="date_timepicker_end" name="ad_time_end" class="form-control" placeholder="結束時間" value="{{ $obj->ad_time_end }}">
            </div>
          </div>
        </div>

        <div class="pull-right add-item">
          <a href="#">+ 新增稽核單位</a>
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
