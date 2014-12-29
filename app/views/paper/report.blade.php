<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
  body { font-family: DejaVu Sans, sans-serif; }
</style>
<title>個人資料管理制度內部稽核報告</title>
</head>
<body>
  <h1 style="text-align: center;">個人資料管理制度內部稽核報告</h1>
  <div style="width:19.5%; display: inline-block;">文件編號</div>
  <div style="width:19.5%; display: inline-block;">NCHU-PIMS-D-019</div>
  <div style="width:19.5%; display: inline-block;">內部限閱</div>
  <div style="width:19.5%; display: inline-block;">版次</div>
  <div style="width:19.5%; display: inline-block;">1.0</div>
  <div style="width:49.5%; display: inline-block;">紀錄編號： {{ $report->r_serial }}</div>
  <div style="width:49.5%; display: inline-block;">填表時間： {{ $report->r_time }}</div>
  <h3>1. 稽核目的</h3>
  <h3>為落實及評估國立中興大學(以下簡稱「本校」)執行個人資訊管理制度之 成效,以確保個人資料管理政策、法令、技術及現行作業之有效性及可行性。</h3>
  <h3>2. 稽核範圍</h3>
  <h3>以本校導入個人資訊管理制度為稽核範圍。</h3>
  <h3>3. 稽核項目</h3>
  <h3>BS 10012:2009 本文項目及個人資料保護法要求。</h3>
  <h3>4. 稽核結果及其他建議事項</h3>
  <div style="width:10%; display: inline-block;">項次</div>
  <div style="width:29.5%; display: inline-block;">標準條文/ 稽核項目</div>
  <div style="width:29.5%; display: inline-block;">稽核發現</div>
  <div style="width:29.5%; display: inline-block;">建議事項</div>
  @foreach($items as $k => $v)
    <div style="width:10%; display: inline-block;">{{ $k }}</div>
    <div style="width:29.5%; display: inline-block;">{{ $v->get_base_str() }}</div>
    <div style="width:29.5%; display: inline-block;">{{ $v->ri_discover }}</div>
    <div style="width:29.5%; display: inline-block;">{{ $v->ri_recommand }}</div>
  @endforeach
  <h3>其他建議事項</h3>
  <p>{{ $report->r_msg }}</p>
</body>
</html>
