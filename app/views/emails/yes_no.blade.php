<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>個資稽核系統</h2>

		<p>您好，這份 {{ isset($type) ? $type : '報告' }} 請您通過或否決</p>
		<hr>
		<p>{{ isset($content) ? $content : 'put something here'}}</p>
		<hr>
		<p><a href="{{ route( $url_alias, [$es_code, 'yes']) }}">通過</a>這份文件</p>
		<p><a href="{{ route( $url_alias, [$es_code, 'no']) }}">否決</a>這份文件</p>

	</body>
</html>
