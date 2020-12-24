<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>角色添加编辑页面</title>
    <?php $this->load->view('admin/common'); ?> 
    <script type="text/javascript">
         var isedit='<?php echo $edit ?>';
         var id='<?php echo $id ?>';
         //上级公司的id
         var comid='<?php echo $comid ?>';
        function add(){
            //取出数据
            var roleName=$("#BodyContent_conRoleName").val();
            var remark=$("#BodyContent_conRemark").val();

            //提交后台
            if(roleName==''){
                alert('名字不能空');
               
            }else {



                  JAjax('admin/Organization/RoleLogic','doaddRole',{RoleName:roleName,Remark:remark,ID:id,CompanyID:comid},function (data){
                    if(data.Success)
                    {
                        closeLayerPageJs(1000, 300);  
                    }
                    else
                    {
                        ShowMsg("添加失败：" + data.Message);
                    }
                       
              },"pager");
            }
        }
    </script>

</head>
<body>


        
    <div class="panel panel-default form-horizontal ">

        <div class="panel-body ">
            <div class="form-group">
                <label class="col-xs-1 control-label">角色名称:</label>
                <div class="col-xs-11">
                    <input name="RoleName" type="text"  id="BodyContent_conRoleName" NotNull="true" class="form-control" value="<?php if($edit){echo $role[0]['RoleName'];} ?>" />
                </div>
                
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">描述:</label>
                <div class="col-xs-11">
                    <textarea name="Remark" rows="3" cols="20" id="BodyContent_conRemark" NotNull="true" class="form-control"><?php if($edit){echo $role[0]['Remark'];} ?></textarea>
                </div>
                
            </div>       
        <!-- panel-body -->
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="submit" name="add" value="确定" id="BodyContent_btnSave" class="btn btn-primary" onclick="add();"/>
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayerPageJs();" />
            
        </div>
    </div>



    
</body>
</html>
