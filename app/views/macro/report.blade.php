<table class="table table-bordered">
    <tbody>
        <tr>
            <td class="text-center" colspan="6">
                個人資料管理制度內部稽核報告
            </td>
        </tr>
        <tr>
            <td>
                文件編號
            </td>
            <td>
                NCHU-PIMS-D-019
            </td>
            <td>
                機密等級
            </td>
            <td>
                內部限閱
            </td>
            <td>
                版次
            </td>
            <td>
                1.0
            </td>
        </tr>
    </tbody>
</table>
<div style="width:49.5%; display: inline-block;">紀錄編號： {{ $report->r_serial }}</div>
<div style="width:49.5%; display: inline-block;">填表時間： {{ $report->r_time }}</div>
<h4>1. 稽核目的</h4>
<p>為落實及評估國立中興大學(以下簡稱「本校」)執行個人資訊管理制度之 成效,以確保個人資料管理政策、法令、技術及現行作業之有效性及可行性。</p>
<h4>2. 稽核範圍</h4>
<p>以本校導入個人資訊管理制度為稽核範圍。</p>
<h4>3. 稽核項目</h4>
<p>BS 10012:2009 本文項目及個人資料保護法要求。</p>
<h4>4. 稽核結果及其他建議事項</h4>
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>
                項次
            </td>
            <td>
                標準條文 / 稽核項目
            </td>
            <td>
                稽核發現
            </td>
            <td>
                建議事項
            </td>
        </tr>

        @foreach($items as $k => $v)
            <tr>
                <td>
                    {{ $k }}
                </td>
                <td>
                    {{ $v->get_base_str() }}
                </td>
                <td>
                    {{ $v->ri_discover }}
                </td>
                <td>
                    {{ $v->ri_recommand }}
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="4">
                其他建議事項:
                <br/>
                {{ $report->r_msg }}
            </td>
        </tr>
    </tbody>
</table>
<h4>5. 缺失矯正與預防處理</h4>
<p>
    受稽單位於接獲稽核報告後，應依據「個人資料矯正預防程序書」之規定，最晚於十五個工作天內將該單位之缺失分析原因及擬採行之矯正與預防措施填列於「個人資料矯正與預防處理單」內，且經主管核定後回覆資安暨個資保護稽核小組。
</p>
<h4>6. 附件</h4>
<p>6.1 個人資料內部稽核底稿</p>
@if(!isset($hide_sign))
    <table class="table table-bordered table-no-bottom">
        <tbody>
            <tr>
                <td>
                </td>
                <td>
                    受稽單位權責主管
                </td>
                <td>
                    資安暨個資保護稽核小組
                </td>
                <td>
                    資訊安全暨個人資料保護推動委員會
                </td>
            </tr>
            <tr>
                <td>
                    日期
                </td>
                <td>
                    {{ $report->r_auth_signed ? $report->r_auth_signed : "尚未簽署" }}
                </td>
                <td>
                    {{ $report->r_auditor_signed ? $report->r_auditor_signed : "尚未簽署" }}
                </td>
                <td>
                    {{ $report->r_comm_signed ? $report->r_comm_signed : "尚未簽署" }}
                </td>
            </tr>
        </tbody>
    </table>
@endif
