<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>充值订单</title>
    <?php $this->load->view('admin/common') ?>
    <style type="text/css">
        .m-10{margin-right: 10px;}
        .pager .form-control{height: 35px!important;}
    </style>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="form-inline">
                <div class="form-group">
                    <label for="StartTime">下单时间:</label>
                    <input type="text" class="form-control" id="StartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="EndTime">至</label>
                    <input type="text" class="form-control m-10" id="EndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="txtKey">关键字:</label>
                    <input type="text" class="form-control" id="txtKey">
                </div>
                <input type="button" value="查 询" onclick="Load(0);" class="btn btn-primary">
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title" width="" itemvalue="orderno">订单号
                            </th>
                            <th class="title" width="" itemvalue="payno">支付单号
                            </th>
                            <th class="title" width="" itemvalue="price">金额
                            </th>
                            <th class="title" width="" itemvalue="paytype">支付类型
                            </th>
                            <th class="title" width="" itemvalue="ordertime" showformat="yyyy-MM-dd hh:mm:ss">下单时间
                            </th>
                            <th class="title" width="" itemvalue="username">用户名
                            </th>
                            <th class="title" width="" itemvalue="phone">手机号码
                            </th>
                            <th class="title" width="" itemvalue="cardno">卡号
                            </th>
                            <th class="title" width="" itemvalue="paytime" showformat="yyyy-MM-dd hh:mm:ss">支付时间
                            </th>
                            <th class="title" width="" itemvalue="status">订单状态
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div id="pager" fun="Load" class="pager" pagerobj="">
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" language="javascript">
    var page = 0;

    function reLoad() {
        Load(page);
    }

    function Load(t) {
        page = t;
        var StartTime = $("#StartTime").val();
        var EndTime = $("#EndTime").val();
        var keyword = $("#txtKey").val();

        JAjax("admin/ETCManage/RechargeOrderLogic", 'onLoadRechargeOrder', {page:t,StartTime:StartTime,EndTime:EndTime,keyword:keyword}, function (data) {
            ReloadTb('dataGrid', data.data);
        }, "pager");
    }

    Load(1);
</script>
</html>
