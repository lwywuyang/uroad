<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>角色列表页</title>
   <?php $this->load->view('admin/common'); ?> 
    <base target="_self" />
    <script type="text/javascript" language="javascript">
        var page = 1;
        //取出自己的id
        var comid = '<?php echo $id ?>';
        function reLoad() {
            Load(page);
        }
        function Load(t) {
            page = t;
            var companyid=comid;
            var name = $("#txtKey").val();


            JAjax("admin/Organization/RoleLogic","getComRole", { key: name, CompanyID: companyid,page:t}, function (data) {
                ReloadTb('dataGrid', data.data);

            }, "pager");
        }
        function add() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/RoleLogic/addRole') ?>/" +0+'/'+ comid, '角色信息', 1000, 310, reLoad);
        }
        function detail(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/RoleLogic/addRole') ?>/" + id, '角色信息', 1000, 310, reLoad);
        }

        function deleteInfo() {
            var values = getCheckedValues("rpcheckbox", "#dataGrid", 'string');
            if (values != "" && values != undefined) {
                ConfirmLayer('提示','您确定要删除吗？',isdel,values);
              
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        function isdel(values){
             JAjax("admin/Organization/RoleLogic", "delRole", { OID: values }, function (data) {
                        if (data.Success) {
                            reLoad();
                        }
                        else {
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, "pager");
        }


    </script>
</head>
<body>
    <div class="panel panel-default">

        <div class="panel-body">
            <div class="form-inline mb10">
                    <input type="button" value="新 增" onclick="add();" class="btn btn-info" />
                    <input type="button" value="删 除" onclick="deleteInfo();" class="btn btn-danger" />
                    <div class="form-group">
                        <label for="txtKey">关键字:</label>
                        <input type="email" class="form-control" id="txtKey" />
                    </div>
                    <input type="button" value="查 询" onclick="Load(1);" class="btn btn-primary" />

                </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox' state='{Status}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');"
                                attr=" state='{Status}'" />
                        </th>

                        <th class="title" itemvalue="RoleName" center="true" showtype="a" attr="onclick= detail('{ID}') href='javascript:void(0)'"
                            itemtext="{itemvalue}" >角色名称
                        </th>
                        <th class="title" itemvalue="Remark" center="true">描述
                        </th>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
                 <div id="pager" fun="Load" class="pager" pagerobj="{OrderDesc:'asc',OrderField:'CompCode',PageSize:10}">
                </div>
            </div>
        </div>      
        <!-- panel-body -->
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>

</body>
</html>
