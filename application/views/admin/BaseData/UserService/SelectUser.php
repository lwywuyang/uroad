<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>添加员工</title>
    <?php $this->load->view('admin/common'); ?>
    <script type="text/javascript">
    var role ='<?php echo $roleid ?>';
        var setting = {
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                onClick: onClick
            },
            check: {
                enable: true,
                chkboxType: { "Y": "ps", "N": "s" },
                chkDisabledInherit: true
            }
        };
        var zNodes = <?php echo $RoleEmp ?>;
        function onClick(e, treeId, treNode) {
        }
        $(document).ready(function () {
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        });

        function getCheckValueFunction() {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            var checkNode = zTree.getCheckedNodes(true);
            var checkValue = "";
            for (var i = 0; i < checkNode.length; i++) {
                if (checkNode[i].isempl ==1)
                    checkValue += checkNode[i].id + ",";
            }
            if (checkValue != "")
                checkValue = checkValue.substr(0, checkValue.length - 1);

            //提交数据到后台
         
            JAjax("admin/Organization/RolePerLogic", "addselectUser", { CheckValue: checkValue, RoleID: role }, function (data) {
                 if(data.data['staut']){
                    closeLayerPageJs(1000, 200); 
                
                 }else{
                    alert('保存不成功');
                 }
            }, null);

            return true;
        }
    </script>
</head>
<body>
    <div class="panel panel-default form-horizontal">
        <div class="panel-heading">
            <h4 class="panel-title">选择员工</h4>
            <p>注意：此界面只能往角色添加员工，如要删除员工，请在上级页面操作</p>
        </div>
        <div class="panel-body ">
            <div style="width: 100%; overflow: auto; height: 400px">
                  <ul id="treeDemo" class="ztree"></ul>
            </div>
        </div>
        <!-- panel-body -->
        <div class="panel-footer"><input name="ctl00$BodyContent$emplTreeValue" type="text" id="BodyContent_emplTreeValue" style=" display:none" />
            <input type="submit" name="ctl00$BodyContent$btnSave" value="确定" onclick="return getCheckValueFunction();" id="BodyContent_btnSave" class="btn btn-primary" />
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayerPageJs();" />
        </div>
    </div>
</body>
</html>
