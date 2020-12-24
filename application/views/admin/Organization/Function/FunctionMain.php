<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>事件列表页</title>
    <?php $this->load->view('admin/common'); ?> 
    <script type="text/javascript">
        var EmployeePage = 1;
        //本身id,
        var SubFunctionsID = '<?php echo $id; ?>';
        // 系统id
        var systemID='<?php echo $systemID; ?>';
        function reLoadPage() {
            location.reload();
        }
        $(document).ready(function () {

            LoadSubFunctions(1);
        });
        function editThisFunctions() {

            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/FunctionLogic/addFun') ?>/"+SubFunctionsID, '功能菜单信息', 1000, 320, reLoadPage);
        }




        var SubFunctionsPage = 1;
        function reLoadSubFunctions() {
            LoadSubFunctions(SubFunctionsPage);
        }
        function LoadSubFunctions(t) {
            SubFunctionsPage = t;
            var name = $("#conKey").val();
            var id=SubFunctionsID;

            JAjax("admin/Organization/FunctionLogic", "getChildFun", { key: name, ID: id,SystemID:systemID,page: SubFunctionsPage }, function (data) {
                ReloadTb('SubFunctionsDataGrid', data.data);
            }, "SubFunctionsPager");
        }

        function addSubFunctions() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/FunctionLogic/addFun') ?>/"+0+'/'+systemID+'/'+SubFunctionsID+'/'+1, '功能菜单信息', 1000, 330, reLoadSubFunctions);
        
        }
        function detailSubFunctions(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/FunctionLogic/addFun') ?>/"+id+'/'+systemID+'/'+SubFunctionsID+'/'+1 , '功能菜单信息', 1000, 330, reLoadSubFunctions);
        }
        function deleteSubFunctionsInfo() {
            var values = getCheckedValues("rpcheckbox", "#SubFunctionsDataGrid", 'string');
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/Organization/FunctionLogic", "delFun", { OID: values }, function (data) {
                        if (data.Success) {
                            reLoadSubFunctions();
                        }
                        else {
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, "SubFunctionsPager");
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
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="#" class="minimize" onclick="panelClick(this)" >&minus;</a>
            </div>
            <h4 class="panel-title">功能详细信息<input type="button" value="修改" class="btn btn-primary ml10" onclick="editThisFunctions();" /></h4>
        </div>
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-xs-1 control-label">功能编码:</label>
                <div class="col-xs-3">
                    <input name="ctl00$BodyContent$conFuncCode" type="text" value="<?php echo $function[0]['FuncCode']; ?>" maxlength="10" readonly="readonly" id="BodyContent_conFuncCode" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">功能名称:</label>
                <div class="col-xs-3">
                    <input name="ctl00$BodyContent$conFuncName" type="text" value="<?php echo $function[0]['FuncName']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conFuncName" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">类型:</label>
                <div class="col-xs-3">
                    <input name="ctl00$BodyContent$conFuncTypeName" type="text" value="<?php echo $function[0]['FuncTypeName']; ?>" maxlength="50" readonly="readonly" id="BodyContent_conFuncTypeName" NotNull="true" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">次序:</label>
                <div class="col-xs-3">
                    <input name="ctl00$BodyContent$conFuncSerial" type="text" value="<?php echo $function[0]['FuncSerial']; ?>" maxlength="10" readonly="readonly" id="BodyContent_conFuncSerial" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">状态:</label>
                <div class="col-xs-3">
                    <input name="ctl00$BodyContent$conStatusName" type="text" value="<?php echo $function[0]['StatusName']; ?>" maxlength="10" readonly="readonly" id="BodyContent_conStatusName" NotNull="true" class="form-control" />
                </div>
                
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">url:</label>
                <div class="col-xs-11">
                    <input name="ctl00$BodyContent$conURI" type="text" maxlength="10" readonly="readonly" id="BodyContent_conURI" NotNull="true" class="form-control" value="<?php echo $function[0]['URI']; ?>"/>
                </div>
                </div>
        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#subFunctions" data-toggle="tab"><strong>下级功能操作</strong></a></li>
    </ul>
    <div class="tab-content mb30">
        <div class="tab-pane active " id="subFunctions">
            <div class="form-inline mb10">
                <input name="ctl00$BodyContent$BtnAdd" type="button" id="BodyContent_BtnAdd" value="新 增" onclick="addSubFunctions();" class="btn btn-info" />
                <input type="button" value="删 除" onclick="deleteSubFunctionsInfo();" class="btn btn-danger" />
                <div class="form-group">
                    <label for="txtKey">关键字:</label>
                    <input type="email" class="form-control" id="conKey" />
                </div>
                <input type="button" value="查 询" onclick="LoadSubFunctions(1);" class="btn btn-primary" />
            </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="SubFunctionsDataGrid">
                    <thead>
                        <th class="title" style="width: 25px" itemvalue="ID" showtype="checkbox" attr="name='rpcheckbox'">
                            <input type="checkbox" id="chkall" onclick="checkall('#SubFunctionsDataGrid', this, 'rpcheckbox');" />
                        </th>
                        <th class="title" itemvalue="FuncCode" center="true" width="150px">功能编码
                        </th>
                        <th class="title" itemvalue="FuncName" center="true" showtype="a" attr="onclick= detailSubFunctions('{ID}') href='javascript:void(0)'"
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
                <div id="SubFunctionsPager" class="pager" Fun="LoadSubFunctions">
                </div>
            </div>
        </div>
    </div>
</body>
</html>