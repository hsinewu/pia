<div class="alert alert-info" role="alert">
  {{ Session::get('user')->p_name }}您好，<a href="{{ route('logout') }}">登出</a>
</div>
@if(Session::get('user')->is('admin'))
	<p>Admin bar</p>
	@include('admin_menu')
@endif
@if(Session::get('user')->is('audit'))
	<p>Audit bar</p>
	@include('audit_menu')
@endif
@if(Session::get('user')->is('auditee'))
	<p>Auditee bar</p>
	@include('auditee_menu')
@endif