<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>修改角色权限页面</title>
    <?php $this->load->view('admin/common'); ?>
    <script type="text/javascript">
        var userid = '<?php echo $userid ?>';

        var userService = '<?php echo $userService ?>';

        var zNodes = <?php echo $service ?>;

        var setting = {
            check: {
                enable: true
            },
            data: {
                simpleData: {
                    enable: true
                }
            }
        };

        $().ready(function(){

            //树状
            $.fn.zTree.init($("#serviceTree"), setting, zNodes);
            //这是选中和取消选中的父子节点关联
            var zTree = $.fn.zTree.getZTreeObj("serviceTree");
            zTree.setting.check.chkboxType = { "Y" : "ps", "N" : "ps" };
            //设置树状默认选中

            
            if (userService != '') {
                userService = userService.split(',');
                $.each(userService,function(index,value){
                    var node = zTree.getNodeByParam("id",value);
                    node.checked = true;
                    zTree.updateNode(node);
                });
            }

        });

        //取出已经选择的id
        function saveUserService() {

            //获取树形选中
            var zTreeDemo = $.fn.zTree.getZTreeObj("serviceTree");
            var checkedNode = zTreeDemo.getCheckedNodes(true);
            var checkedName = '';
            var checkedId = '';
            var roadName = 'NoService';
            for (var i = 0; i < checkedNode.length; i++) {
                /*if (checkedNode[i].pId == null) {
                    roadName = checkedNode[i].name;
                }*/
                if (checkedNode[i].id!='' && typeof(checkedNode[i].pId)=='string'){
                    checkedId += checkedNode[i].id + ",";
                }
            }

            if (checkedId != ''){
                checkedId = checkedId.substr(0, checkedId.length - 1);
            }

            JAjax("admin/baseData/UserServiceLogic", "setUserService", {userid:userid,checkedId:checkedId}, function (data) {
                if (data.Success)
                    ShowMsg('操作成功!');
                else
                    ShowMsg('error:'+data.Message);
            }, null);

        }

    </script>
    <style >
        body {overflow:auto;}
    </style>
</head>
<body>
    <div class="tab-content mb30" >
        <div class="tab-pane active" id="functions">
            <input type="button" name="ctl00$BodyContent$btnFunctionsTree" value="保存" onclick="saveUserService();" id="serviceZtree" class="btn btn-primary"/>
            <ul id="serviceTree" class="ztree" ></ul>
        </div>
    </div>
</body>
</html>