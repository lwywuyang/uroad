<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>添加弹出框</title>

  <?php $this->load->view('admin/common') ?>
  <script type="text/javascript">
         var id ="<?php echo $ID ?>";
        function save(){
            //取出数据
            var code=$("#BodyContent_conCode").val();
            var name=$("#BodyContent_conName").val();
            var sysType=$("#BodyContent_conSysType").val();

            //提交后台
            if(code==''||name==''||sysType==''){
                alert('不能空');
               
            }else {
               
                  JAjax('admin/Organization/PlatFormLogic','onSave',{Code:code,Name:name,SysType:sysType,ID:id},function (data){
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

  </script>
</head>
<body style="">
    <div class="panel panel-default form-horizontal ">
        <div class="panel-body ">
            <div class="form-group">
                <label class="col-xs-1 control-label">系统编码:</label>
                <div class="col-xs-3">
                    <input name="Code" type="text"  id="BodyContent_conCode" notnull="true" class="form-control"  value='<?php  echo isset($Code)?$Code:"" ?>'>
                </div>
                <label class="col-xs-1 control-label">系统名称:</label>
                <div class="col-xs-3">
                    <input name="Name" type="text" maxlength="50" id="BodyContent_conName" notnull="true" class="form-control"  value='<?php  echo isset($Name)?$Name:"" ?>'>
                </div>
                 <label class="col-xs-1 control-label">系统类型:</label>
                <div class="col-xs-3">

                  <select name="SysType" id="BodyContent_conSysType" class="form-control">
                    <!-- 数据库循环 -->
                    <option selected="selected" value="">---选择分类---</option>
                    <option value="webForm">webForm</option>
                    <option value="智能客户端">智能客户端</option>
                    <option value="webserver">webserver</option>
                    <option value="其它">其它</option>
                  </select>
                </div>
            </div>
            <script type="text/javascript">
                $(".form-control").find("option[value='<?php  echo isset($SysType)?$SysType:"" ?>']").attr("selected",true);
            </script>
        <!-- panel-body -->
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="submit" name="add" value="确定" id="BodyContent_btnSave" class="btn btn-primary" onclick="save();">
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayerPageJs();">          
        </div>
    </div>
    


</body>
</html>