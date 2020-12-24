<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>修改密码</title>
     <?php $this->load->view('admin/common') ?>
    <base target="_self" />
    <script type="text/javascript" language="javascript">
        function CheckCanSave() {
            var oldPwd=$("#BodyContent_txtOldPwd").val();
            var newpwd=$("#BodyContent_txtNewpwd").val();
            var newpwd2=$("#BodyContent_txtNewpwd2").val();        
            var empid='<?php echo $empid ?>';
             var reg = /^[0-9a-zA-Z]+$/;
            if(oldPwd==''||oldPwd==''||oldPwd==''){
               ShowMsg("不能为空");
                return false;
            }
            if (newpwd != newpwd2) {
                ShowMsg("两次输入密码不一致！请重新输入！");
                return false;
            }else{
                if(newpwd.length<8&&newpwd!=''){
                        ShowMsg("密码至少大于等于8位");
                        return;
                    }
                    /*
                    if(checkPass(newpwd)==1){

                        ShowMsg("密码不能纯数字"); 
                        return;
                    }
                    */
                  JAjax('admin/Login','doeditpassword',{OldPwd:oldPwd,Newpwd:newpwd,EmpID:empid},function (data){
                    if(data.Success)
                    { 
                        ShowMsg("修改成功");
                        closeLayerPageJs();                      
                    }
                    else
                    {
                        ShowMsg("修改失败：" + data.Message);
                    }
              },null,true);
            }
        }

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
                        return ls
             }
    </script>
</head>
<body>    
    <div class="panel panel-default form-horizontal ">
        <div class="panel-body " style="height:220px;" >
            <div class="form-group">
                <label class="col-xs-3  control-label">旧密码</label>
                <div class="col-xs-7">
                    <input name="OldPwd" type="password" id="BodyContent_txtOldPwd" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3  control-label">新密码</label>
                <div class="col-xs-7">
                    <input name="Newpwd" type="password" id="BodyContent_txtNewpwd" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3  control-label">确认密码</label>
                <div class="col-xs-7">
                    <input name="Newpwd2" type="password" id="BodyContent_txtNewpwd2" class="form-control"/>
                </div>              
            </div>  
            
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="submit" name="btnSave" value="确 定" onclick="return CheckCanSave();" id="BodyContent_btnSave" class="btn btn-primary" />
            <input type="button" value="取 消" class="btn btn-primary" onclick="closeLayerPageJs();" />
        </div>
    </div> 
</body>
</html>
