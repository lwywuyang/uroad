<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>修改角色权限页面</title>
    <?php $this->load->view('admin/common'); ?>    
    <script type="text/javascript">
    var id='<?php echo $id ?>';
    var TypeID='';
        function BindFunctionsTree() {           
            var setting = {
                view: {
                    fontCss: getFont,
                    nameIsHTML: true
                },
                data: {
                    simpleData: {
                        enable: true
                    }
                },
                check: {
                    enable: true,
                    chkboxType: { "Y": "ps", "N":"s" },
                    chkDisabledInherit: true
                }
            };     
             var zNodes = <?php echo $fun ?>;
            function getFont(treeId, node) {
                return node.font ? node.font : {};
            }            
            $.fn.zTree.init($("#functionsTree"), setting, zNodes);
        }
        function BindBuDataTypesTree() {
            var setting = {
                data: {
                    simpleData: {
                        enable: true
                    }
                },
                callback: {
                    onClick: onClick
                }
            };

            var zNodes = <?php echo $datatype ?>;
            //点击显示右边列表
            function onClick(e, treeId, treeNode) {
                //自身id
                LoadBuDatas(treeNode.id);
            }
            $.fn.zTree.init($("#budatatypesTree"), setting, zNodes);
        }
        //隔壁数据权限
        function BindBuDatasTree(zNodes) {
            var setting = {

                data: {
                    simpleData: {
                        enable: true
                    }
                },
                check: {
                    enable: true,
                    chkboxType: { "Y": "ps", "N":"s" },
                    chkDisabledInherit: true
                }
               
            };

            $.fn.zTree.init($("#budatasTree"), setting, zNodes);
        }
        //取出已经选择的id
        function getCheckValueFunction() {
            var zTree = $.fn.zTree.getZTreeObj("functionsTree");
            var checkNode = zTree.getCheckedNodes(true);
            var checkValue = "";
            for (var i = 0; i < checkNode.length; i++) {
                if(checkNode[i].id!='system'&&checkNode[i].pId!='system')
                    checkValue += checkNode[i].id+",";
            }
            if (checkValue != "")
                checkValue = checkValue.substr(0, checkValue.length - 1);
          
            //提交数据到后台
            JAjax("admin/Organization/RolePerLogic", "CheckFunPer", { CheckValue: checkValue, RoleID: id }, function (data) {
                 if(data.data['staut']){
                   

                 }else{
                    alert('保存不成功');
                 }
            }, null,true);
            return true;
        }
        //数据权限保存
        function getCheckValueBuDatas() {
            var zTree = $.fn.zTree.getZTreeObj("budatasTree");
            var checkNode = zTree.getCheckedNodes(true);
            var checkValue = "";
            for (var i = 0; i < checkNode.length; i++) {
                if (checkNode[i].id != 'system' && checkNode[i].pid != 'system')
                    checkValue += checkNode[i].val + ",";
            }
            if (checkValue != "")
                checkValue = checkValue.substr(0, checkValue.length - 1);
             // alert(TypeID);
                
            //提交数据到后台
            JAjax("admin/Organization/RolePerLogic", "CheckRolePer", { CheckValue: checkValue, RoleID: id ,TypeID:TypeID}, function (data) {
                 if(data.data['staut']){
                   
                 }else{
                    alert('保存不成功');
                 }
            }, null,true);

            return true;
        }
        
        //显示ajax右边数据权限
        function LoadBuDatas(typeid) {
            TypeID = typeid;
            var role = id;
           

            JAjax("admin/Organization/RolePerLogic", "LoadBuDatas", { TypeID: TypeID, Role: role }, function (data) {
                
                var datas = new Array();
                if (data && data.data && data.data.length > 0) {
                    for (var i = 0; i < data.data.length; i++) {
                        var o = new Object();
                        o.id = data.data[i].BUDataID;
                        o.pId = data.data[i].PID;
                        o.name = data.data[i].BUDataName;
                        o.val = data.data[i].ID;
                        if (data.data[i].IsRoleCheck == 1)
                            o.checked = true;
                        if (parseInt(data.data[i].BUDataCode, 10) > 1) {
                            o.open = false;
                        }
                        else {
                            o.open = true;
                        }
                        datas.push(o);
                    }
                    
                }
                BindBuDatasTree(datas);
               
            }, null);
        }
        $(document).ready(function () {

           BindFunctionsTree();
           BindBuDataTypesTree();
           BindRoleUsers(0);
           var selectTab = '';
            if (selectTab != "") {
                $('#tab a[href="#' + selectTab + '"]').tab('show');
            }
        });
        var page =0;

        function reLoad() {
            BindRoleUsers(page);
        }
        //载入角色员工数据
        function BindRoleUsers(t) {
            page = t;
            var name = $("#txtKey").val();
             var role = id;
             

            JAjax("admin/Organization/RolePerLogic", "onloadroleemp", { key: name, RoleID: role, page: t }, function (data) {
                ReloadTb('dataGrid', data.data);
            
            }, "pager");
        }

        function add() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/RolePerLogic/selectUser')?>/" + id, '选择员工', 600, 650, reLoad);
            
        
        }
        function deleteInfo() {
            var values = getCheckedValues("rpcheckbox", "#dataGrid", 'string');

            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    isdel(values);
                });
            }else{
                ShowMsg("请至少选择一条记录！");
            }
        }

         function isdel(values){
            JAjax("admin/Organization/RolePerLogic", "deleteroleemp", { OID: values }, function (data) {
                if (data.Success){
                    reLoad();
                }else{
                    ShowMsg("删除失败：" + data.Message);
                }
            }, null);
        }
    </script>
    <style >
        body {overflow:auto;}
    </style>
</head>
<body>
    <ul class="nav nav-tabs" id="tab">
        <li class="active"><a href="#functions" data-toggle="tab"><strong>功能权限</strong></a></li>
        <li><a href="#budata" data-toggle="tab"><strong>数据权限</strong></a></li>
        <li><a href="#employee" data-toggle="tab"><strong>角色人员</strong></a></li>
    </ul>
    <div class="tab-content mb30" >
        <!-- 功能权限 -->
        <div class="tab-pane active " id="functions">
            <input type="button" name="ctl00$BodyContent$btnFunctionsTree" value="保存" onclick="return getCheckValueFunction();" id="BodyContent_btnFunctionsTree" class="btn btn-primary"/>
             <ul id="functionsTree" class="ztree" ></ul>
        </div>
        <!-- 数据权限 -->
        <div class=" tab-pane " id="budata">
            <input name="ctl00$BodyContent$budataTreeValue" type="text" id="BodyContent_budataTreeValue" style=" display:none" />
            <input type="button" name="ctl00$BodyContent$btnBuDatasTree" value="保存" onclick="return getCheckValueBuDatas();" id="BodyContent_btnBuDatasTree" class="btn btn-primary mb10" />
            <div class="row">
                <div class=" col-sm-3   ">
                   <div class="alert alert-warning"> <ul id="budatatypesTree" class="ztree" ></ul></div>
                </div>
                <div class="col-sm-9  ">                   
                    <div class="alert alert-warning"> 
                         <h5 id="msg">请选择左侧的数据权限节点</h5>
                        <ul id="budatasTree" class="ztree" ></ul></div>
                </div>
            </div>
        </div>
        <!-- 角色员工权限 -->
         <div class=" tab-pane " id="employee">
             <div class="form-inline mb10">
                    <input type="button" value="选择人员" onclick="add();" class="btn btn-info" />
                    <input type="button" value="删 除" onclick="deleteInfo();" class="btn btn-danger" />
                    <div class="form-group">
                        <label for="txtKey">关键字:</label>
                        <input type="email" class="form-control" id="txtKey" />
                    </div>
                    <input type="button" value="查 询" onclick="reLoad(0);" class="btn btn-primary" />
                </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <th class="title" style="width: 25px" itemvalue="EmpID" showtype="checkbox" attr="name='rpcheckbox'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');" />
                        </th>
                        <th class="title" itemvalue="EmplCode" center="true" >编码(工号) 
                        </th>
                        <th class="title" itemvalue="EmplName" center="true" >姓名 
                        </th>
                        <th class="title" itemvalue="CompName" center="true" >公司
                        </th>
                         <th class="title" itemvalue="DepaName" center="true" >部门 
                        </th>                       
                    </thead>
                </table>
                <div id="pager" class="pager" Fun="BindRoleUsers">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
