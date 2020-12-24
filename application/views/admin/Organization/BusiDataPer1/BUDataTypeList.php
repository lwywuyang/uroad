<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>平台权限列表</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <?php $this->load->view('admin/common'); ?> 
    <base target="_self" />
    <script type="text/javascript" language="javascript">
        var page = 0;
        var systemID = '<?php echo $systemid ?>';
        
        function reLoad() {
            Load(page);
        }
        function Load(t) {
            page = t;
            var name = $("#txtKey").val();

            JAjax("admin/Organization/BusiDataPerLogic", "onLoad", { key: name, SystemID: systemID, page: t }, function (data) {
                ReloadTb('dataGrid', data.data);
     
            }, "pager");
        }
        function add() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/BusiDataPerLogic/addBUDataType') ?>/"+0+'/'+systemID,'数据类型信息维护', 1000, 330, reLoad);
        }
        function detail(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/BusiDataPerLogic/addBUDataType') ?>/"+id,'数据类型信息维护', 1000, 330, reLoad);
        }

        function deleteInfo() {
            var values = getCheckedValues("rpcheckbox", "#dataGrid");
            if (values != "" && values != undefined) {
                ConfirmLayer('提示','您确定要删除吗？',isdel,values);
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

         function isdel(values){
             JAjax("admin/Organization/BusiDataPerLogic", "delete", { OID: values }, function (data) {
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
                    <input type="button" value="查 询" onclick="Load(0);" class="btn btn-primary" />

                </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');" />
                        </th>
                        <th class="title" itemvalue="DataTypeCode" center="true" >编码
                        </th>
                        <th class="title" itemvalue="BuName" center="true" showtype="a" attr="onclick= detail('{ID}') href='javascript:void(0)'"
                            itemtext="{itemvalue}" >名称
                        </th>
                        <th class="title" itemvalue="BuTable" center="true" >表名
                        </th>
                         <th class="title" itemvalue="DisFiledID" center="true" >ID字段
                        </th>
                         <th class="title" itemvalue="DisFiledF" center="true">编码字段
                        </th>
                        <th class="title" itemvalue="DisFiledS" center="true" >名称字段
                        </th>
                        <th class="title" itemvalue="SelfLinkFiled" center="true" >父节点字段
                        </th>

                    </thead>
                </table>
                <div id="pager" Fun="Load">
                </div>
            </div>
        </div>
        
        <!-- panel-body -->
        <div class="panel-footer">
            <div id="pager" Fun="Load"></div>
        </div>
    </div>

    <script type="text/javascript" language="javascript">
        Load(0);
    </script>   
</body>
</html>
