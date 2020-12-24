<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
   <?php $this->load->view('admin/common'); ?>
    <script type="text/javascript">
    var empid='<?php echo $empid ?>';
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
                    chkboxType: { "Y": "ps", "N": "s" },
                    chkDisabledInherit: false
                }
            };
            var zNodes=<?php echo $AllEmpPerFun ?>;          
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
            var zNodes = <?php echo $EmpPerDataType ?>;
            function onClick(e, treeId, treeNode) {
                LoadBuDatas(treeNode.id);
            }
            $.fn.zTree.init($("#budatatypesTree"), setting, zNodes);
        }
        function BindBuDatasTree(zNodes) {
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
                    chkboxType: { "Y": "ps", "N": "s" },
                    chkDisabledInherit: false
                }

            };
            function getFont(treeId, node) {
                return node.font ? node.font : {};
            }


            function onClick(e, treeId, treeNode) {


            }
            $.fn.zTree.init($("#budatasTree"), setting, zNodes);
        }
        function BindRolesTree() {

            var setting = {
                
                data: {
                    simpleData: {
                        enable: true
                    }
                },
                check: {
                    enable: true,
                    chkboxType: { "Y": "ps", "N": "ps" },
                    chkDisabledInherit: false
                }
            };
           
            var zNodes=<?php echo $getEmpRole ?>;
            function getFont(treeId, node) {
                return node.font ? node.font : {};
            }

            $.fn.zTree.init($("#rolesTree"), setting, zNodes);
        }

        //人员功能保存
        function getCheckValueFunction() {
            var zTree = $.fn.zTree.getZTreeObj("functionsTree");
            var checkNode = zTree.getCheckedNodes(true);
            var checkValue = "";
            for (var i = 0; i < checkNode.length; i++) {
                if (checkNode[i].id != 1 && checkNode[i].pId != 1 && (checkNode[i].chkDisabled || checkNode[i].chkDisabled==false))
                    checkValue += checkNode[i].id + ",";
            }
            if (checkValue != "")
                checkValue = checkValue.substr(0, checkValue.length - 1);          
            JAjax("admin/Organization/EmpPerLogic", "addFunEmp", { CheckValue: checkValue, EmpId: empid }, function (data) {  
                 if(data.data['staut']){
                   
                 }else{
                     // ShowMsg("保存失败，没有选择功能");
                 }
             
   
            }, null,true);
             
            return true;

        }

        //人员业务权限保存
        function getCheckValueBuDatas() {
            
            var loadi = layer.load('加载中…',2); 
            var index = layer.load(1, {time: 10*1000}); 
            var zTree = $.fn.zTree.getZTreeObj("budatasTree");
            var checkNode = zTree.getCheckedNodes(true);
            var checkValue = "";
            for (var i = 0; i < checkNode.length; i++) {
                if (checkNode[i].id != 'system' && checkNode[i].pId != 'system')
                    checkValue += checkNode[i].val + ",";
            }
            if (checkValue != "")
                checkValue = checkValue.substr(0, checkValue.length - 1);
            
            JAjax("admin/Organization/EmpPerLogic", "addEmpPer", { CheckValue: checkValue, EmpId: empid }, function (data) {
                 if(data.data['staut']){
                    ShowMsg('保存成功');
                 }
            }, null,true);
            return true;
        }


        //员工添加角色
        function getCheckValueRoles() {
            var zTree = $.fn.zTree.getZTreeObj("rolesTree");
            var checkNode = zTree.getCheckedNodes(true);
            var checkValue = "";
            for (var i = 0; i < checkNode.length; i++) {
                if (checkNode[i].isemp == 1)
                    checkValue += checkNode[i].id + ",";
            }

            if (checkValue != "")
                checkValue = checkValue.substr(0, checkValue.length - 1);
   

            JAjax("admin/Organization/EmpPerLogic", "addEmpRole", { CheckValue: checkValue, EmpId: empid }, function (data) {
                 if(data.data['staut']){
                    alert('保存成功');
                 }
             
            }, null);
            // 
            return true;
        }
//取出人员业务权限
        function LoadBuDatas(typeid) {
            var TypeID = typeid;
            $("#msg").html("加载中");            
            JAjax("admin/Organization/EmpPerLogic", "LoadDataPer", { TypeID: TypeID, EmpID: empid }, function (data) {
                var datas = new Array();
                if (data && data.data && data.data.length > 0) {
                    for (var i = 0; i < data.data.length; i++) {
                        var o = new Object();
                        o.id = data.data[i].BUDataID;
                        o.pId = data.data[i].PID;
                        o.name = data.data[i].BUDataName;
                        o.val = data.data[i].ID;
                        if (data.data[i].IsRoleCheck == 1) {
                            o.font = { 'color': 'red' };
                            o.checked = true;
                            o.chkDisabled = true
                        }
                        else {
                            if (data.data[i].IsEmpCheck == 1) {                              
                                o.checked = true;
                                o.chkDisabled = false
                            }
                        }
                        
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
                $("#msg").hide();
            }, null);
        }
        $(document).ready(function () {
            BindFunctionsTree();
            BindBuDataTypesTree();
            BindRolesTree();
            var selectTab = '';
           if (selectTab != "") {
               $('#tab a[href="#' + selectTab + '"]').tab('show');
           }
        });
       
    </script>
    <style >
        body {
            overflow:auto;
        }
    </style>
</head>
<body>
    <ul class="nav nav-tabs" id="tab">

        <li class="active"><a href="#functions" data-toggle="tab"><strong>功能权限</strong></a></li>
        <li><a href="#budata" data-toggle="tab"><strong>数据权限</strong></a></li>
        <li><a href="#role" data-toggle="tab"><strong>角色人员</strong></a></li>
    </ul>
    <div class="tab-content mb30" >
        <div class="tab-pane active " id="functions">
            <input name="ctl00$BodyContent$functionTreeValue" type="text" id="BodyContent_functionTreeValue" style=" display:none" />
            <input type="submit" name="ctl00$BodyContent$btnFunctionsTree" value="保存" onclick="return getCheckValueFunction();" id="BodyContent_btnFunctionsTree" class="btn btn-primary" />
             <ul id="functionsTree" class="ztree" ></ul>
        </div>
        <div class=" tab-pane " id="budata">
            <input name="ctl00$BodyContent$budataTreeValue" type="text" id="BodyContent_budataTreeValue" style=" display:none" />
            <input type="submit" name="ctl00$BodyContent$btnBuDatasTree" value="保存" onclick="return getCheckValueBuDatas();" id="BodyContent_btnBuDatasTree" class="btn btn-primary mb10" />
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
         <div class=" tab-pane " id="role">
             <input name="ctl00$BodyContent$roleTreeValue" type="text" id="BodyContent_roleTreeValue" style=" display:none" />
            <input type="submit" name="ctl00$BodyContent$btnRolesTree" value="保存" onclick="return getCheckValueRoles();" id="BodyContent_btnRolesTree" class="btn btn-primary" />
             <ul id="rolesTree" class="ztree" ></ul>
        </div>
    </div>
</body>
</html>
