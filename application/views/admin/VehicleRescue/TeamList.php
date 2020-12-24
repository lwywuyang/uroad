<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>中队</title>
    <?php $this->load->view('admin/common') ?>
    <style>
        .table{margin-bottom: 0;}
    </style>
    <script type="text/javascript" language="javascript">
        var page = 1;

        function reLoad(){
            Load(page);
        }

        /**
         * @desc  拉取'设备维护'页面表格数据
         * @param {[string]}    t [页码,默认第一页]
         */
        function Load(t) {
            page = t;
            var managerSel = $('#managerSel').val();
            var typeSel = $('#typeSel').val();
            var search = $('#searchTxt').val();

            JAjax("admin/VehicleRescue/TeamLogic", 'onLoadTeam', {page:page,managerSel:managerSel,typeSel:typeSel,search:search}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        /**
         * @desc 打开'新增设备'页面
         */
        function operateTeam(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/VehicleRescue/TeamLogic/operateTeamMsg') ?>?id="+id,'详情',550,560,reLoad);
        }

        /**
         * @desc   获取所有选取项,组成字符串
         * @param  {[type]}    name    [description]
         * @param  {[type]}    context [description]
         * @return {[string]}            [description]
         */
        function getAllCheckedValues(name, context) {
            var target = context ? context : "";
            var result = "";
            $(target + " input[name='" + name + "']:checked").each(function () {
                result += $(this).val() + ",";
            });
            result = result.substring(0, result.length - 1);//去掉最后一个逗号
            return result;
        }

        function deleteTeam(){
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");

            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗?", function () {
                    JAjax("admin/VehicleRescue/TeamLogic", "delTeam", { deleteValue: values}, function (data) {
                        if (data.Success)
                            reLoad();
                        else
                            ShowMsg("删除失败:" + data.Message);
                    }, null);
                });
            }else{
                ShowMsg("请至少选择一条记录！");
            }
        }

    </script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="searchTxt">管理处:</label>
                    <select class="form-control" id="managerSel" onchange="Load(1)">
                        <option value=''>全部</option>
                        <?php foreach($manager as $item): ?>
                            <option value="<?=$item['id']?>"><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="typeSel">类型:</label>
                    <select class="form-control" id="typeSel" onchange="Load(1)">
                        <option value=''>全部</option>
                        <?php foreach($teamType as $item): ?>
                            <option value="<?=$item['dictcode']?>"><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchTxt">关键字:</label>
                    <input type="text" class="form-control" id="searchTxt" placeholder="请输入关键字">
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary" >
                </div>
                <div class="form-group">
                    <input type="button" value="新 增" id="new" onclick="operateTeam(0);" class="btn btn-info" >
                </div>
                <div class="form-group">
                    <input type="button" value="删 除" id="del" onclick="deleteTeam();" class="btn btn-danger">
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title" width="30px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="" itemvalue="name" center="true">中队名称
                            </th>
                            <th class="title" width="" itemvalue="managerName" center="true">所属管理处  
                            </th>
                            <th class="title" width="" itemvalue="phone" center="true">电话
                            </th>
                            <th class="title" width="" itemvalue="managerzone" center="true">管辖范围
                            </th>
                            <th class="title" width="" itemvalue="seq" center="true">备注
                            </th>
                            <th class="title" width="" itemvalue="operate" center="true">操作
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <div id="pager" fun="Load" class="pager" pagerobj="">
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>