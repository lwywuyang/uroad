<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>编辑顶级公司</title>
   <?php $this->load->view('admin/common'); ?> 
   <script type="text/javascript">
    //显示数据    
         var id='<?php echo $ID ?>';
         var pid='<?php echo $PID ?>';
        function save(){
            //取出数据
            var compCode=$("#BodyContent_conCompCode").val();
            var compShortName=$("#BodyContent_conCompShortName").val();
            var compName=$("#BodyContent_conCompName").val();
            //提交后台
            if(compCode==''||compShortName==''||compName==''){
                alert('不能空');
               
            }else {
                  JAjax('admin/Organization/OrgManageLogic','onSaveCom',{CompCode:compCode,CompShortName:compShortName,CompName:compName,ID:id,PID:pid},function (data){
                    if(data.Success)
                    {
                        closeLayerPageJs(1000, 210);  
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
                <label class="col-xs-1 control-label">公司编码:</label>
                <div class="col-xs-3">
                    <input name="CompCode" type="text"  id="BodyContent_conCompCode" NotNull="true" class="form-control" value="<?php  echo isset($CompCode)?$CompCode:"" ?>" />
                </div>
                <label class="col-xs-1 control-label">公司简称:</label>
                <div class="col-xs-3">
                    <input name="CompShortName" type="text" maxlength="50" id="BodyContent_conCompShortName" NotNull="true" class="form-control" value="<?php  echo isset($CompShortName)?$CompShortName:"" ?>"/>
                </div>
                <label class="col-xs-1 control-label">公司全称:</label>
                <div class="col-xs-3">
                    <input name="CompName" type="text" maxlength="50" id="BodyContent_conCompName" NotNull="true" class="form-control" value="<?php  echo isset($CompName)?$CompName:"" ?>"/>
                </div>
            </div>     
        <!-- panel-body -->
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="submit" name="add" value="确定" id="BodyContent_btnSave" class="btn btn-primary" onclick="save();"/>
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayerPageJs();" />           
        </div>
    </div>
<script type="text/javascript">
//<![CDATA[
if(window.parent.clearMask)window.parent.clearMask();//]]>
</script>    
</body>
</html>
