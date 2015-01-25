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
        </div>
          <form class="form-horizontal" role="form" action="" method="POST">
                <div class="form-group">
                  <label class="col-sm-2 control-label">問題或缺失說明</label>
                  <div class="col-sm-10">
                  <input type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">原因分析</label>
                  <div class="col-sm-10">
                  <input type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">問題或缺失說明</label>
                  <div class="col-sm-10">
                  <input type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">矯正措施</label>
                  <div class="col-sm-10">
                  <input type="text">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">預防措施</label>
                  <div class="col-sm-10">
                  <input type="text">
                  </div>
                </div>
                
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">送出</button>
              </div>
            </div>
          </form>

      </div>
    </div>
  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
