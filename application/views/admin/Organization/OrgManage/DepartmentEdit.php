<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>编辑部门</title>
    <?php $this->load->view('admin/common'); ?> 

     <script type="text/javascript">
    //显示数据    
         var id='<?php echo $ID ?>';
         // 所属公司id
         var companyid='<?php echo $CompanyID ?>';
         //所属部门id
         var pid='<?php echo $PID ?>';
        function add(){
            //取出数据
            var depaCode=$("#BodyContent_conDepaCode").val();
            var depaName=$("#BodyContent_conDepaName").val();
            var depaSerial=$("#BodyContent_conDepaSerial").val();
            var depamanager=$("#BodyContent_conDepamanager").val();

            //提交后台
            if(depaCode==''||depaName==''||depaSerial==''||depamanager==''){
                alert('不能空');       
            }else {

                  JAjax('admin/Organization/OrgManageLogic','onSaveDep',{DepaCode:depaCode,DepaName:depaName,DepaSerial:depaSerial,Depamanager:depamanager,ID:id,CompanyID:companyid,PID:pid},function (data){
                    if(data.Success)
                    {
                        closeLayerPageJs(1000, 350);  
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
                <label class="col-xs-1 control-label">部门编码:</label>
                <div class="col-xs-3">
                    <input name="DepaCode" type="text"  id="BodyContent_conDepaCode" NotNull="true" class="form-control" value="<?php  echo isset($DepaCode)?$DepaCode:"" ?>"/>
                </div>
                <label class="col-xs-1 control-label">部门名称:</label>
                <div class="col-xs-3">
                    <input name="DepaName" type="text" maxlength="50" id="BodyContent_conDepaName" NotNull="true" class="form-control" value="<?php  echo isset($DepaName)?$DepaName:"" ?>"/>
                </div>
                <label class="col-xs-1 control-label">排序:</label>
                <div class="col-xs-3">
                    <input name="DepaSerial" type="text" maxlength="50" id="BodyContent_conDepaSerial" NotNull="true" placeholder="请输入数字" class="form-control" value="<?php  echo isset($DepaSerial)?$DepaSerial:"" ?>"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">负责人:</label>
                <div class="col-xs-3">
                    <input name="Depamanager" type="text"  id="BodyContent_conDepamanager" NotNull="true" class="form-control" value="<?php  echo isset($Depamanager)?$Depamanager:"" ?>" />
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

