<div class="alert alert-info" role="alert">
  {{ Session::get('user')->p_name }}您好，<a href="{{ route('logout') }}">登出</a>
</div>
<div class="list-group">
  <a class="list-group-item" href="{{ route('admin_info','dept') }}">單位資料表</a>
  <a class="list-group-item" href="{{ route('admin_info','person') }}">人員資料表</a>
  <a class="list-group-item" href="{{ route('admin_info','audit') }}">稽核設定</a>
  <a class="list-group-item" href="{{ route('admin_cal') }}">行事曆</a>
  <a class="list-group-item" href="{{ route('admin_info','event') }}">事件設定</a>
</div>
<div class="list-group">
  <a class="list-group-item" href="{{ route('audit_tasks') }}">稽核任務</a>
</div>
