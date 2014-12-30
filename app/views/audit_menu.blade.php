<div class="alert alert-info" role="alert">
  {{ Session::get('user')->p_name }}您好，<a href="{{ route('logout') }}">登出</a>
</div>
<div class="list-group">
  <a class="list-group-item" href="{{ route('audit_calendar') }}">稽核行事曆</a>
  <a class="list-group-item" href="{{ route('audit_tasks') }}">稽核任務</a>
</div>
