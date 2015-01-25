@extends('master')


@section('head_css')
  @parent
@stop

@section('footer_scripts')
  @parent
@stop

@section('content')

  <div class="col-md-6">
      <div class="jumbotron">
        <h1>個資稽核系統</h1>
        @include('talk')
        <p><a class="btn btn-primary btn-lg" role="button" onclick="alert('先掛著')">使用規範 »</a></p>
      </div>
  </div>
  <div class="col-md-6">
    <!-- 這邊是填寫帳密的表單 -->
      <form class="form-signin" role="form"
                action = "{{ route('login_process') }}" method = "post">

        <h2 class="form-signin-heading">請登入</h2>
        <div class="col-md-2"></div>
        <input type="text" class="form-control" placeholder="請在此輸入帳號" required="" autofocus="" name="user"> <!-- name="usr_mail" -->
        <div class="row">&nbsp;</div>
        <input type="password" class="form-control" placeholder="請在此輸入密碼" required="" name="pwd"> <!-- name="pwd" -->
        <button class="btn btn-lg btn-primary btn-block" type="submit">登入</button>

      </form>
  </div>

@stop
