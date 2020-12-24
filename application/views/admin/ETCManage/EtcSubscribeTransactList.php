<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ETC管理-ETC预约办理-列表页</title>
    <?php $this->load->view('admin/common') ?>
    <style>
        /* common */
        th {
            font-weight: bold !important;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .m-r-10 {
            margin-right: 10px;
        }

        /* 表单 */
        .filtrate-wrap {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 20px 20px 0;
            list-style: none;
        }

        .filtrate-item {
            position: relative;
            display: flex;
            align-items: center;
            margin: 0 10px 10px 0;
        }

        .filtrate-item label {
            white-space: nowrap;
        }

        .name {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
</head>

<body>
<div class="panel panel-default">
    <!-- 筛选 -->
    <div class="filtrate-wrap">
        <div class="filtrate-item">
            <label class="name">申请时间：</label>
            <input type="text" class="form-control" name="startTime" autocomplete="off" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
            <label class="name">&nbsp;&nbsp;-&nbsp;&nbsp;</label>
            <input type="text" class="form-control" name="endTime" autocomplete="off" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
        </div>
        <div class="filtrate-item">
            <label class="name">银行：</label>
            <select class="form-control" name="bankno" onchange="getListData(1)"></select>
        </div>
        <div class="filtrate-item">
            <label class="name">关键字：</label>
            <input type="text" class="form-control" name="keyword" placeholder="请输入关键字">
        </div>
        <div class="filtrate-item">
            <input type="button" value="查 询" class="btn btn-primary m-r-10" onclick="getListData(1)">
            <input type="button" value="导 出" class="btn btn-warning m-r-10" onclick="exportExcel()">
        </div>
    </div>
    <!-- 列表 -->
    <div class="panel-body">
        <table class="table table-hover table-bordered dataTable" id="dataGrid">
            <thead>
            <tr>
                <!-- <th class="title" width="3%" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                    <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                </th> -->
                <th class="title" width="8%" itemvalue="name">姓名</th>
                <th class="title" width="10%" itemvalue="mobile">手机号</th>
                <th class="title" width="8%" itemvalue="platenum">车牌号</th>
                <th class="title" width="8%" itemvalue="color">车牌颜色</th>
                <th class="title" width="8%" itemvalue="bank">办理银行</th>
                <th class="title" width="8%" itemvalue="applytime">预约时间</th>
                <th class="title" width="8%" itemvalue="createtime">申请时间</th>
                <th class="title" width="36%" itemvalue="address">就近地址</th>
                <th class="title" width="6%" itemvalue="operation">操作</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- 分页器 -->
    <div class="panel-footer">
        <div id="pager" fun="getListData" class="pager" pagerobj=""></div>
    </div>
</div>

<script>
    let page = 1; // 页码

    $(function() {
        getBankList();
        getListData(1);
    });

    /**
     * 获取银行列表
     */
    function getBankList() {
        JAjax("admin/ETCManage/EtcSubscribeTransact", "getBankList", {}, function(res) {
            // console.log(res);
            if (res.Success) {
                let str = '<option value="">全部</option>';
                res.data.forEach(function(item) {
                    str += '<option value="' + item.code + '">' + item.name + '</option>';
                });
                $("select[name=bankno]").empty().html(str);
            }
        });
    }

    /**
     * 获取列表数据
     * @param {String/Number} t 页码
     */
    function getListData(t) {
        page = t;
        JAjax("admin/ETCManage/EtcSubscribeTransact", "getListData", {
            page: t,
            startTime: $("input[name=startTime]").val(),
            endTime: $("input[name=endTime]").val(),
            bankno: $("select[name=bankno]").val(),
            keyword: $("input[name=keyword]").val(),
        }, function(res) {
            ReloadTb('dataGrid', res.data);
        }, "pager", true);
    }

    /**
     * 显示 -> 编辑页
     */
    function edit(id) {
        showLayerPageJs("<?php echo base_url('/index.php/admin/ETCManage/EtcSubscribeTransact/edit?id='); ?>" + id, "查看", 400, 500, function() {
            let session = window.sessionStorage.getItem("cancelReload");
            session == 1 && getListData(page);
            window.sessionStorage.removeItem("cancelReload");
        });
    }

    /**
     * 导出Excel
     */
    function exportExcel() {
        let url = "<?php echo base_url('/index.php/admin/ETCManage/EtcSubscribeTransact'); ?>";
        let startTime = $('input[name=startTime]').val();
        let endTime = $('input[name=endTime]').val();
        let bankno = $('select[name=bankno]').val();
        let keyword = $('input[name=keyword]').val();
        const down = new Promise((resolve, reject) => {
            let params = `startTime=${startTime}&endTime=${endTime}&bankno=${bankno}&keyword=${keyword}`;
            url += '/exportExcel?' + params;
            window.location.href = url;
            resolve();
        });
        let loadingLayerIndex = layer.load('导出中…');
        down.then(() => {
            layer.close(loadingLayerIndex);
        });
    }
</script>
</body>

</html>