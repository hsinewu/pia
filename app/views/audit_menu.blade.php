<div class="alert alert-info" role="alert">
  {{ Session::get('user')->p_name }}您好，<a href="{{ route('logout') }}">登出</a>
</div>
<div class="list-group">
  <a class="list-group-item" href="{{ route('admin_cal') }}">行事曆(修正連結)</a>
  <a class="list-group-item" href="{{ route('audit_tasks') }}">稽核任務</a>
</div>
