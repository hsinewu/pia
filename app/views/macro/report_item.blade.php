<table class="table table-bordered">
    <tbody>
        <tr>
            <td class="text-center" colspan="6">
                個人資料管理制度矯正預防處理單
            </td>
        </tr>
        <tr>
            <td>
                文件編號
            </td>
            <td>
                NCHU-PIMS-D-020
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
                1.1
            </td>
        </tr>
    </tbody>
</table>
<h6 style="text-align: right;">流水號： {{ $item->get_serial() }}</h3>
<table class="table table-bordered table-no-bottom">
    <tbody>
        <tr>
            <td>
                提出單位
            </td>
            <td>
                {{ $raise_depart }}
            </td>
            <td>
                提出人員
            </td>
            <td>
                {{ $auditor->p_name }}
            </td>
            <td>
                提出日期
            </td>
            <td>
                {{ $report->r_time }}
            </td>
        </tr>
        <tr>
            <td>
                處理單位
            </td>
            <td>
                {{ $dept->dept_name }}
            </td>
            <td>
                處理人員
            </td>
            <td>
                {{ $item->handler_name }}
            </td>
            <td>
                填寫日期
            </td>
            <td>
                {{ $item->fill_date }}
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered table-no-bottom">
    <tbody>
        <tr>
            <td width="50%">
                事件分類：<br/>□建議 □觀察 □次要缺失 □主要缺失 □潛在風險<br/>註:內部稽核不填寫此欄位
            </td>
            <td width="50%">
                事件來源：<br/>■內部稽核 □外部稽核 □個資事件 □自行發現 <br/>□其他＿＿＿＿＿＿＿
            </td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered table-no-bottom">
    <tbody>
        <tr>
            <td width="20%">
                問題或缺失說明
            </td>
            <td width="80%">
                {{ $item->ri_discover }}
            </td>
        </tr>
        <tr>
            <td width="20%">
                原因分析
            </td>
            <td width="80%">
                {{ $item->analysis }}
            </td>
        </tr>
        <tr>
            <td width="20%">
                矯正措施
            </td>
            <td width="80%">
                <span style="color:red;">{{ $item->scan_help ? "■" : "□" }}須計算機及資訊網路中心協助進行主機弱點掃描作業。(請照會計算機及資訊網路中心)</span>
                <br/>
                {{ $item->rectify_measure }}
                <br/>
                <table class="table table-bordered table-no-bottom">
                    <tbody>
                        <tr>
                            <td>
                                預定完成日期：{{ $item->rec_finish_date }}
                            </td>
                            <td>
                                追蹤人：
                            </td>
                            <td>
                                追蹤日期：
                            </td>
                            <td>
                                確認結果：
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="20%">
                預防措施
            </td>
            <td width="80%">
                {{ $item->precautionary_measure }}
                <br/>
                <table class="table table-bordered table-no-bottom">
                    <tbody>
                        <tr>
                            <td>
                                預定完成日期：{{ $item->pre_finish_date }}
                            </td>
                            <td>
                                追蹤人：
                            </td>
                            <td>
                                追蹤日期：
                            </td>
                            <td>
                                確認結果：
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@if(!isset($hide_sign))
<table class="table table-bordered">
    <tbody>
        <tr>
            <td>
            </td>
            <td>
                權責單位承辦人員
            </td>
            <td>
                權責單位主管
            </td>
            <td style="color:red;">
                照會其他單位主管 (如有需要)
            </td>
            <td>
                資安暨個資保護稽核小組組長
            </td>
        </tr>
        <tr>
            <td>
                日期
            </td>
            <td>
                {{ $item->fill_date ? $item->fill_date . ($item->handler_email ? "由 " . $item->handler_name . " " . $item->handler_email . " 所填寫" : "") . "" : "尚未填寫過" }}
            </td>
            <td>
                {{ $item->confirm_timestamp1 ? $item->confirm_timestamp1 : "尚未簽署" }}
            </td>
            <td>
            </td>
            <td>
                {{ $item->confirm_timestamp2 ? $item->confirm_timestamp2 : "尚未簽署" }}
            </td>
        </tr>
    </tbody>
</table>
@endif
