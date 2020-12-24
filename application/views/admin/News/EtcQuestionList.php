<!-- 
    Author：LJK
    Email：lllsuyan@163.com
    Update：2019-08-06
    Name：ETC管理-ETC常见问题-列表页
 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ETC管理-ETC常见问题-列表页</title>
    <?php $this->load->view('admin/common') ?>
    <style>
        /* common */
        th {
            font-weight: bold !important;
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
                <label class="name">时间：</label>
                <input type="text" class="form-control" name="startTime" autocomplete="off" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" onchange="getListData(1);" />
                <label class="name">&nbsp;&nbsp;-&nbsp;&nbsp;</label>
                <input type="text" class="form-control" name="endTime" autocomplete="off" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" onchange="getListData(1);" />
            </div>
            <div class="filtrate-item">
                <label class="name">状态：</label>
                <select class="form-control" name="status" onchange="getListData(1);">
                    <option value="">全部</option>
                    <option value="0">登记</option>
                    <option value="1">发布</option>
                </select>
            </div>
            <div class="filtrate-item">
                <label class="name">关键字：</label>
                <input type="text" class="form-control" name="keyword" placeholder="请输入关键字">
            </div>
            <div class="filtrate-item">
                <input type="button" value="查 询" class="btn btn-primary m-r-10" onclick="getListData(1)">
                <input type="button" value="新 增" class="btn btn-info m-r-10" onclick="edit(0)">
                <input type="button" value="撤 销" class="btn btn-danger m-r-10" onclick="repeal()">
            </div>
        </div>
        <!-- 列表 -->
        <div class="panel-body">
            <table class="table table-hover table-bordered dataTable" id="dataGrid">
                <thead>
                    <tr>
                        <th class="title" width="3%" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                        </th>
                        <th class="title" width="25%" itemvalue="question" maxlength="16">问题</th>
                        <th class="title" width="28%" itemvalue="answer" maxlength="16">答案</th>
                        <th class="title" width="12%" itemvalue="created" showformat="yyyy-MM-dd hh:mm">更新时间</th>
                        <th class="title" width="10%" itemvalue="operatorname">操作人</th>
                        <th class="title" width="6%" itemvalue="statusName">状态</th>
                        <th class="title" width="16%" itemvalue="operation">操作</th>
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
            getListData(1);
        });

        /**
         * 获取列表数据
         * @param {String/Number} t 页码
         */
        function getListData(t) {
            page = t;
            JAjax("admin/News/EtcQuestion", "getListData", {
                page: t,
                startTime: $("input[name=startTime]").val(),
                endTime: $("input[name=endTime]").val(),
                status: $("select[name=status]").val(),
                keyword: $("input[name=keyword]").val(),
            }, function(res) {
                ReloadTb('dataGrid', res.data);
            }, "pager", true);
        }

        /**
         * 改变状态
         * @desc 0：登记，1：发布
         * @param {String/Number} id 问题id
         * @param {String/Number} status 状态id
         */
        function changeStatus(id, status) {
            JAjax("admin/News/EtcQuestion", "changeStatus", {
                id: id,
                status: status,
            }, function(res) {
                if (res.Success) {
                    getListData(page);
                } else {
                    ShowMsg("提示:" + res.Message);
                }
            }, null, true);
        }

        /**
         * 显示 -> 编辑页
         */
        function edit(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/News/EtcQuestion/edit?id='); ?>" + id, "信息", 400, 500, function() {
                let session = window.sessionStorage.getItem("cancelReload");
                session == 1 && getListData(1);
                window.sessionStorage.removeItem("cancelReload");
            });
        }

        /**
         * 撤销
         */
        function repeal() {
            let values = getCheckedValues("rpcheckbox", "#dataGrid");
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要撤销选中的问题吗？", function() {
                    JAjax("admin/News/EtcQuestion", "repeal", {
                        ids: values,
                    }, function(res) {
                        if (res.Success) {
                            getListData(1);
                        } else {
                            ShowMsg("提示:" + res.Message);
                        }
                    });
                });
            } else {
                ShowMsg("请至少选择一条记录！");
            }
        }
    </script>
</body>

</html>