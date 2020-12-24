<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>功能列表</title>
     <?php $this->load->view('admin/common'); ?> 
    <base target="_self" />
    <script type="text/javascript" language="javascript">
        var page = 0;
        var systemID = '<?php  echo $id ?>';
        //链接+自己id+系统id+自己pid(上级id)
        function reLoad() {
            Load(page);
        }
        function Load(t) {
            page = t;
            var name = $("#txtKey").val();

            JAjax("admin/Organization/FunctionLogic", "getFun", { key: name, SystemID: systemID, page: t }, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }
        function add() {       
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/FunctionLogic/addFun') ?>/"+0+'/'+systemID+'/'+0+'/'+0 , '功能菜单信息', 1000, 330, reLoad);
        }
        function detail(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/FunctionLogic/addFun') ?>/"+id+'/'+systemID+'/'+0, '功能菜单信息', 1000, 330, reLoad);
        }
        function deleteInfo() {

            var values = getCheckedValues("rpcheckbox", "#dataGrid", 'string');
            // alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/Organization/FunctionLogic", "delFun", { OID: values }, function (data) {
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
                        <th class="title" itemvalue="FuncCode" center="true" width="150px">功能编码
                        </th>
                        <th class="title" itemvalue="FuncName" center="true" showtype="a" attr="onclick= detail('{ID}') href='javascript:void(0)'"
                            itemtext="{itemvalue}" width="150px">功能名称
                        </th>
                        <th class="title" itemvalue="FuncTypeName" center="true" width="100px">类型
                        </th>
                         <th class="title" itemvalue="FuncSerial" center="true" width="100px">次序
                        </th>
                         <th class="title" itemvalue="URI" center="true">URI
                        </th>
                         <th class="title" itemvalue="StatusName" center="true" width="100px">状态
                        </th>
                    </thead>
                </table>
                <div id="pager" Fun="Load" class="pager">
                </div>
            </div>
        </div>
        
        <!-- panel-body -->
    </div>

    <script type="text/javascript" language="javascript">
        Load(0);
    </script>





    
</body>
</html>
