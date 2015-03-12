@extends('master')


@section('head_css')
  @parent

  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/tokenfield-typeahead.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.datetimepicker.css'); }}"/ >

    <style>
    table.table.table-hover td.action{
      text-align: center;
    }

    table.table tr.sample{
      display: none;
    }

    div#assign{display: none;}

    .toogle-hide {
      position: absolute;
      top: -9999px;
      left: -9999px;
      visibility: hidden;
    }

    .assign label:before {
      content: "+ 指定處理人員";
      font-weight: normal;
    }

    /* Change Icon */
    input[type=checkbox]:checked ~ .assign label:before {
        content: "X 取消指定人員";
    }

  </style>
@stop

@section('footer_scripts')
  @parent
  <script src="{{ asset('assets/js/jquery.datetimepicker.js'); }}"></script>
  <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.min.js"></script>
  <script src="{{ asset('assets/js/form.js'); }}"></script>

  <script>
    jQuery('#rectify_time').datetimepicker({
      format:'Y-m-d H:i:s',
      minDate:'0'
    });
    jQuery('#prevent_time').datetimepicker({
      format:'Y-m-d H:i:s',
      minDate:'0'
    });
  </script>
@stop

@section('content')

  <div class="row">
      <form class="form-horizontal" role="form" action="{{ route('feedback_assign_process', $es_code) }}" method="POST">
        <div class="form-group">
          <label class="col-sm-2 control-label">提出單位</label>
          <div class="col-sm-10">
            <input type="text" id="auditor_dept" class="form-control" placeholder="" value="{{ $auditor_dept->dept_name }}" readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">提出人員</label>
          <div class="col-sm-10">
            <input type="text" id="auditor" class="form-control" placeholder="" value="{{ $auditor->p_name }}" readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">處理單位</label>
          <div class="col-sm-10">
            <input type="text" id="auditee_dept" class="form-control" placeholder="" value="{{ $auditee_dept->dept_name }}" readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">處理人員</label>
          <div class="col-sm-10">
            <input type="text" id="auditee" class="form-control" placeholder="" value="{{ $auditee }}" readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">事件分類</label>
          <div class="col-sm-10">
            <input type="text" id="event" class="form-control" placeholder="" value="" readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">事件來源</label>
          <div class="col-sm-10">
            <input type="text" id="source" class="form-control" placeholder="" value="" readonly>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">問題或缺失說明</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="problem" name="problem" rows="3" placeholder="{{ $reportItem->ri_discover }}" readonly></textarea>
          </div>
        </div>
        <fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label">原因分析</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="原因分析"></textarea>
            </div>
          </div>
          <hr>
          <div class="form-group">
            <label class="col-sm-2 control-label">矯正措施</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="rectify" name="rectify" rows="3" placeholder="矯正措施"></textarea>
              <input type="checkbox" name="rectify_check"> <span style="color: red;">須計算機及資訊網路中心協助進行主機弱點掃描作業。(請照會計算機及資訊網路中心)</span>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">預定完成日期</label>
            <div class="col-sm-10">
              <input type="date_timepicker" id="rectify_time" name="rectify_time" class="form-control" placeholder="預定完成日期">
            </div>
          </div>
          <hr>
          <div class="form-group">
            <label class="col-sm-2 control-label">預防措施</label>
            <div class="col-sm-10">
              <textarea class="form-control" id="prevent" name="prevent" rows="3" placeholder="預防措施"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">預定完成日期</label>
            <div class="col-sm-10">
              <input type="date_timepicker" id="prevent_time" name="prevent_time" class="form-control" placeholder="預定完成日期">
            </div>
          </div>
        </fieldset>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">送出</button>
          </div>
        </div>
      </form>

  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
