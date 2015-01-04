<div class="alert alert-info" role="alert">
  {{ Session::get('user')->p_name }}您好，<a href="{{ route('logout') }}">登出</a>
</div>
<div class="list-group">
  <a class="list-group-item" href="{{ route('auditee_status') }}">稽核狀況</a>
</div>
