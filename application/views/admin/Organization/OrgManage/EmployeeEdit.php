<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>修改员工信息</title>  
    <?php $this->load->view('admin/common') ?>   
    <script type="text/javascript">
        var id='<?php echo $ID ?>';
        var departmentid='<?php echo $DepartmentID ?>';
        function save(){
            //取出数据
            var emplCode=$("#BodyContent_conEmplCode").val();
            var emplName=$("#BodyContent_conEmplName").val();
            var mobile=$("#BodyContent_conMobile").val();
            var status=$("#BodyContent_conStatus").val();
            var selectrole=$('#conrole').val();
            var password=$("#BodyContent_NewPassword").val();
            var reg = /^[0-9a-zA-Z]+$/;
            //提交后台
            if(emplCode==''||emplName==''||status==''){
                ShowMsg('不能空');       
            }else {
                    if(password.length<8&&password!=''){
                        ShowMsg("密码至少大于等于8位");
                        return;
                    }
                    /*
                    if(checkPass(password)==1){
                        ShowMsg("密码必须是数字和英文组合"); 
                        return;
                    }
                    */
               
                  JAjax('admin/Organization/OrgManageLogic','onSaveEmp',{EmplCode:emplCode,EmplName:emplName,Mobile:mobile,ID:id,DepartmentID:departmentid,Status:status,SelectRole:selectrole,PassWord:password},function (data){
                    if(data.Success)
                    {
                        closeLayerPageJs();  
                    }
                    else
                    {
                        ShowMsg("添加失败：" + data.Message);
                    }                     
              },"pager");

            }
        }
            
        // 验证密码格式
        // 验证密码格式
        // 验证密码格式
        function checkPass(pass){
            if(pass.length < 8){
                return 0;
            }
            var ls = 0;
            if(pass.match(/([a-z])+/)){
                ls++;
            }
            if(pass.match(/([0-9])+/)){
                ls++;
            }
            if(pass.match(/([A-Z])+/)){
                ls++;
            }
            if(pass.match(/[^a-zA-Z0-9]+/)){
                ls++;
            }
            return ls;
        }
        var emproleid='';
        if(id!='0'){
            <?php if(isset($RoleEmp)):?>
                <?php foreach ($RoleEmp as $v): ?>
                     emproleid+="<?php echo $v['RoleID']; ?>,";
                <?php endforeach ?>
            <?php endif ?>
        }
        
        $(document).ready(function () {
            roleData = jQuery.parseJSON('<?php echo $role; ?>');
            console.log(roleData);
            var userrole = emproleid.split(",");
            $("#conrole").html("");
            $("#conrole").append('<option value=""></option>');
            for (var i = 0; i < roleData.length; i++) {

                if ($.inArray(roleData[i].ID, userrole) == -1) {
                    $("#conrole").append('<option value="' + roleData[i].ID + '">' + roleData[i].RoleName + '</option>');
                }
                else {
                    $("#conrole").append('<option selected value="' + roleData[i].ID + '">' + roleData[i].RoleName + '</option>');
                }
                 
               
            }
            jQuery(".chosen-select").chosen({ 'width': '100%', 'white-space': 'nowrap' });
        });
    </script>
</head>
<style>
    /* .panel-body{
        height: 492px;
    } */
</style>
<body>
     <div class="panel panel-default form-horizontal ">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-1 control-label">用户名(工号):</label>
                <div class="col-xs-3">
                    <input name="EmplCode" type="text"  id="BodyContent_conEmplCode" NotNull="true" class="form-control" value="<?php  echo isset($emp['EmplCode'])?$emp['EmplCode']:"" ?>"/>
                </div>
                <label class="col-xs-1 control-label">姓名:</label>
                <div class="col-xs-3">
                    <input name="$EmplName" type="text" maxlength="50" id="BodyContent_conEmplName" NotNull="true" class="form-control" value="<?php  echo isset($emp['EmplName'])?$emp['EmplName']:"" ?>"/>
                </div>
                <label class="col-xs-1 control-label">角色:</label>
                <div class="col-xs-3">           
                    <select class="chosen-select" id="conrole" multiple data-placeholder="请选择角色">
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">电话号码:</label>
                <div class="col-xs-3">
                    <input name="Mobile" type="text"  id="BodyContent_conMobile" NotNull="true" class="form-control" value="<?php  echo isset($emp['Mobile'])?$emp['Mobile']:"" ?>"/>
                </div>               
                <label class="col-xs-1 control-label">密码:</label>
                <div class="col-xs-3">
                    <input name="Password" type="text"  id="BodyContent_NewPassword" placeholder="默认密码为Gstlw654321" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">状态:</label>
                <div class="col-xs-3">
                    <select name="Status" id="BodyContent_conStatus" class="form-control">
						<option selected="selected" value="1100102">确认</option>
						<option value="1100103">作废</option>
					</select>
                </div>
            </div>
            <script type="text/javascript">
                $(".form-control").find("option[value='<?php  echo isset($emp['Status'])?$emp['Status']:"" ?>']").attr("selected",true);
            </script>
            <!-- panel-body -->
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="submit" name="add" value="确定" onclick="save();" id="BodyContent_btnSave" class="btn btn-primary" />
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayerPageJs();" />
        </div>
    </div>   
    <div id="menuContent" class="well" style="display: none; position: absolute; ">
        <ul id="treeDemo" class="ztree" style="margin-top: 0; width: 180px; height: 300px;"></ul>
    </div> 
</body>
</html>
