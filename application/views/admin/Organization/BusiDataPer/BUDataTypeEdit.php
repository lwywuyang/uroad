<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>数据修改和添加</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <?php $this->load->view('admin/common'); ?>
</head>
<script type="text/javascript">
    //显示数据    
         var isedit='<?php echo $edit ?>';
         // 自己id
         var id='<?php echo $id ?>';
         //系统的id
         var systemID='<?php echo $systemID ?>';
        
        function add(){
            //取出数据
            var dataTypeCode=$("#BodyContent_conDataTypeCode").val();
            var buName=$("#BodyContent_conBuName").val();
            var buTable=$("#BodyContent_conBuTable").val();
            var disFiledID=$("#BodyContent_conDisFiledID").val();
            var disFiledF=$("#BodyContent_conDisFiledF").val();
            var disFiledS=$("#BodyContent_conDisFiledS").val();
            var selfLinkFiled=$("#BodyContent_conSelfLinkFiled").val();
            var frFiled=$("#BodyContent_conFrFiled").val();

            //提交后台
            if(dataTypeCode==''){
                alert('不能空');
               
            }else {


                  JAjax('admin/Organization/BusiDataPerLogic','doaddBUDataType',{DataTypeCode:dataTypeCode,BuName:buName,BuTable:buTable,DisFiledID:disFiledID,DisFiledF:disFiledF,DisFiledS:disFiledS,SelfLinkFiled:selfLinkFiled,FrFiled:frFiled,ID:id,SystemID:systemID},function (data){
                    if(data.Success)
                    {
                        closeLayerPageJs(1000, 330);  
                    }
                    else
                    {
                        ShowMsg("添加失败：" + data.Message);
                    }             
              },"pager");
            }
        }

  </script> 
<body>        
    <div class="panel panel-default form-horizontal ">
        
        <div class="panel-body ">
            <div class="form-group">
                <label class="col-xs-1 control-label">编码:</label>
                <div class="col-xs-3">
                    <input name="DataTypeCode" type="text"  id="BodyContent_conDataTypeCode" NotNull="true" class="form-control" value="<?php if($edit){echo $BUData[0]['DataTypeCode'];} ?>"/>
                </div>
                <label class="col-xs-1 control-label">名称:</label>
                <div class="col-xs-3">
                    <input name="BuName" type="text" maxlength="50" id="BodyContent_conBuName" NotNull="true" class="form-control" value="<?php if($edit){echo $BUData[0]['BuName'];} ?>"/>
                </div>
                <label class="col-xs-1 control-label">表名:</label>
                <div class="col-xs-3">
                     <input name="BuTable" type="text" maxlength="50" id="BodyContent_conBuTable" NotNull="true" class="form-control" value="<?php if($edit){echo $BUData[0]['BuTable'];} ?>"/>
                      
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">ID字段:</label>
                <div class="col-xs-3">
                    <input name="DisFiledID" type="text"  id="BodyContent_conDisFiledID" NotNull="true" class="form-control" value="<?php if($edit){echo $BUData[0]['DisFiledID'];} ?>"/>
                </div>
                <label class="col-xs-1 control-label">编码字段:</label>
                <div class="col-xs-3">
                    <input name="DisFiledF" type="text" maxlength="50" id="BodyContent_conDisFiledF" NotNull="true" class="form-control" value="<?php if($edit){echo $BUData[0]['DisFiledF'];} ?>"/>
                    
                </div>
                <label class="col-xs-1 control-label">名称字段:</label>
                <div class="col-xs-3">
                    <input name="DisFiledS" type="text" maxlength="50" id="BodyContent_conDisFiledS" NotNull="true" class="form-control" value="<?php if($edit){echo $BUData[0]['DisFiledS'];} ?>"/>
                    
                </div>  
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">根字段:</label>
                <div class="col-xs-3">
                    <input name="SelfLinkFiled" type="text"  id="BodyContent_conSelfLinkFiled" data-original-title="该字段设置树结构的自关联字段，根的值必须为空" data-toggle="tooltip" data-trigger="hove" NotNull="true" class="form-control tooltips" value="<?php if($edit){echo $BUData[0]['SelfLinkFiled'];} ?>"/>
                </div>
                <label class="col-xs-1 control-label">过滤条件:</label>
                <div class="col-xs-3">
                    <input name="FrFiled" type="text" maxlength="50" id="BodyContent_conFrFiled" NotNull="true" class="form-control" value="<?php if($edit){echo $BUData[0]['FrFiled'];} ?>"/>                    
                </div>                
            </div>            
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="submit" name="ctl00$BodyContent$btnSave" value="确定" id="BodyContent_btnSave" class="btn btn-primary" onclick="add();"/>
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayerPageJs();" />
        </div>
    </div>   
</body>
</html>
