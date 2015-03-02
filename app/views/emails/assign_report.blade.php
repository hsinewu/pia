<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>個資稽核系統，您被指定填寫矯正預防處理單</h2>

    <p>您好，這是個資稽核系統</p>
    <p>下方為稽核報告，或者下載附加檔案</p>
    <hr>

    {{ $report_content }}

    <hr>
    <p>確認後，以下為您被指定填寫的矯正預防處理單</p>
    <hr>

    {{ $report_item_content }}

    <hr>
    <p>確認後，請點選下方連結開始填寫矯正預防：</p>
    <p>
      <a href="{{ route('feedback_assign', $es_code) }}">{{ route('feedback_assign', $es_code) }}</a>
    </p>

  </body>
</html>
