<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> 编辑页面</title>
    <?php $this->load->view('admin/common'); ?> 
</head>
<script type="text/javascript">
    //显示数据    
         var isedit='<?php echo $edit ?>';
         // 自己id
         var id='<?php echo $id ?>';
         //系统的id
         var systemID='<?php echo $systemID ?>';
         var pid='<?php echo $pID ?>';
         var pID='';
         if(pid!=0){
            pID=pid;
         }
        function add(){
            //取出数据
            var funcCode=$("#BodyContent_conFuncCode").val();
            var funcName=$("#BodyContent_conFuncName").val();
            var funcType=$("#BodyContent_conFuncType").val();
            var funcSerial=$("#BodyContent_conFuncSerial").val();
            var status=$("#BodyContent_conStatus").val();
            var url=$("#BodyContent_conURI").val();


            //提交后台
            if(funcCode==''||funcName==''){
                ShowMsg('不能空');
               
            }else {


                  JAjax('admin/Organization/FunctionLogic','doaddFun',{FuncCode:funcCode,FuncName:funcName,FuncType:funcType,FuncSerial:funcSerial,Status:status,URL:url,ID:id,SystemID:systemID,PID:pID},function (data){
                    if(data.Success)
                    {
                        closeLayerPageJs(1000, 320);  
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
                <label class="col-xs-1 control-label">功能编码:</label>
                <div class="col-xs-3">
                    <input name="FuncCode" type="text"  id="BodyContent_conFuncCode" NotNull="true" class="form-control" value="<?php if($edit){echo $function[0]['FuncCode'];} ?>"/>
                </div>
                <label class="col-xs-1 control-label">功能名称:</label>
                <div class="col-xs-3">
                    <input name="FuncName" type="text" maxlength="50" id="BodyContent_conFuncName" NotNull="true" class="form-control" value="<?php if($edit){echo $function[0]['FuncName'];} ?>"/>
                </div>
                <label class="col-xs-1 control-label">类型:</label>
                <div class="col-xs-3">
             <select name="FuncType" id="BodyContent_conFuncType" class="form-control">
              
                    <option value="1101302">菜单</option>
                    <option value="1101304">页面</option>
               
                     <option value="1101305">按钮</option>
                    <option value="1101306">功能</option>
         
            </select>
                </div>
            </div>
            <script type="text/javascript">
                $("#BodyContent_conFuncType").find("option[value='<?php if($edit){echo $function[0]['FuncType'];} ?>']").attr("selected",true);
            </script>
            <div class="form-group">
                <label class="col-xs-1 control-label">次序:</label>
                <div class="col-xs-3">
                    <input name="FuncSerial" type="text"  id="BodyContent_conFuncSerial" NotNull="true" class="form-control" value="<?php if($edit){echo $function[0]['FuncSerial'];} ?>" onkeyup="value=value.replace(/[^\-?\d.]/g,'')"/>
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
                $("#BodyContent_conStatus").find("option[value='<?php if($edit){echo $function[0]['Status'];} ?>']").attr("selected",true);
            </script>
            <div class="form-group">
                <label class="col-xs-1 control-label">url:</label>
                <div class="col-xs-11">
                    <input name="URI" type="text" id="BodyContent_conURI" NotNull="true" class="form-control" value="<?php if($edit){echo $function[0]['URI'];} ?>"/>
                </div>
                </div>
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="submit" name="ctl00$BodyContent$btnSave" value="确定" id="BodyContent_btnSave" class="btn btn-primary" onclick="add();"/>
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayerPageJs();" />
        </div>
    </div>
<script type="text/javascript">
//<![CDATA[
if(window.parent.clearMask)window.parent.clearMask();//]]>
</script>    
</body>
</html>
