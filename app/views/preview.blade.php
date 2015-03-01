@extends('master')


@section('head_css')
  @parent
@stop

@section('footer_scripts')
  @parent
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
          <a class="btn btn-primary pull-right" href="{{ $download_url }}">
              下載為 PDF 檔案 ▼
          </a>
          <!-- Modal -->
        </div>

        <div class="panel-body">
            {{ $content }}
        </div>
      </div>
    </div>
  </div>
  <footer>
    <p>© Company 2014</p>
  </footer>

@stop
