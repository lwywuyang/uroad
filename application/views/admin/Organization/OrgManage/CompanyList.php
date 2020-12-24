
<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>点击组织显示的公司列表页面</title>
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript" language="javascript">
        var page = 0;
        function reLoad() {
            Load(page);
        }
        function Load(t) {
            page = t;
            var name = $("#txtKey").val();
            JAjax("admin/Organization/OrgManageLogic", 'onloadCom', {key:name,page:t}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }
        //顶级公司添加和修改
        function add() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageCom') ?>/"+0, '公司信息', 1000, 210, reLoad);
        }
         function detail(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/OrgManageLogic/detailPageCom') ?>/"+id, '系统信息维护', 1000, 210, reLoad);
        }


        /*删除函数*/
        function deleteInfo() {
          //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getCheckedValues("rpcheckbox", "#dataGrid",'string');
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/Organization/OrgManageLogic", "onDelCom", { OID: values}, function (data) {
                        if (data.Success) {
                            reLoad();
                        }
                        else {
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, "pager");
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }
    </script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-body">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="txtKey">关键字:</label>
                    <input type="email" class="form-control" id="txtKey">
                </div>
                <input type="button" value="查 询" onclick="Load(0);" class="btn btn-primary">

            </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr><th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                        </th>
                        <th class="title" itemvalue="CompCode" center="true">公司编码
                        </th>
                        <th class="title" itemvalue="CompName" center="true" showtype="a" attr="onclick= detail('{ID}') href='javascript:void(0)'" itemtext="{itemvalue}">公司名称
                        </th>
                        <th class="title" itemvalue="CompShortName" center="true">简称
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据 -->
                </tbody>
            </table>
                <div id="pager" fun="Load" class="pager" pagerobj="{OrderDesc:'asc',OrderField:'CompCode',PageSize:10}">
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <input type="button" value="新 增" onclick="add();" class="btn btn-info">
            <input type="button" value="删 除" onclick="deleteInfo();" class="btn btn-danger">
        </div>
        <!-- panel-body -->
    </div>
    <script type="text/javascript" language="javascript">
        Load(0);
    </script>

</body></html>