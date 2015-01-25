<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>個資稽核系統</h2>

		<p>您好，這是個資稽核系統，這裏有份稽核報告請您確認</p>
		<p>請確認下方稽核報告，或者下載附加檔案</p>
		<hr>
		@include('macro/report')
		<hr>
		<p>確認後點選下方連結</p>
		<p>
			<a href="{{ $sign_url }}">{{ $sign_url }}</a>
		</p>

	</body>
</html>
