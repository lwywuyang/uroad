<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>权限管理主要页面</title>
    <?php $this->load->view('admin/common'); ?>
    <script type="text/javascript">
    var id='<?php echo $id ?>'; 

        function Refash(){
              var buTable=$("#BuTable").val();
              var disFiledID=$("#DisFiledID").val();
              var disFiledF=$("#DisFiledF").val();
              var disFiledS=$("#DisFiledS").val();
              var selfLinkFiled=$("#SelfLinkFiled").val();
              var frFiled=$("#FrFiled").val();
            JAjax('admin/Organization/BusiDataPerLogic','RefashData',{BuTable:buTable,DisFiledID:disFiledID,DisFiledF:disFiledF,DisFiledS:disFiledS,SelfLinkFiled:selfLinkFiled,FrFiled:frFiled,BuID:id},function (data){
                    if(data.Success)
                    {
                       // 刷新页面
                      location.href="<?php  echo base_url('index.php/admin/Organization/BusiDataPerLogic/DataPerMian') ?>/"+id;

                    }
                    else
                    {
                        ShowMsg("刷新失败：" + data.Message);
                    }             
              },null);
        }

        var setting = {

            data: {
                simpleData: {
                    enable: true
                }
            }
        };
        var zNodes=<?php echo $Dataper; ?>;
        // alert(zNodes);
        function reLoadPage() {
            location.reload();
        }

        $(document).ready(function () {
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);

        });
        function detail() {
            showLayerPageJs("<?php echo base_url('/index.php/admin/Organization/BusiDataPerLogic/addBUDataType') ?>/"+id,'数据类型信息维护', 1000, 320, reLoadPage);
        }
      
    </script>
    <style>
        body {
            
            overflow:auto;
        }
    </style>
</head>
<body>       
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="#" class="minimize" onclick="panelClick(this)" >&minus;</a>
            </div>
            <h4 class="panel-title">
                数据类型信息维护
                <input type="button" value="修改" class="btn btn-primary ml10" onclick="detail();" /> 
                <input type="button" name="Refash" value="刷新业务数据" id="Refash" class="btn btn-primary ml10" onclick="Refash()" /> 
            </h4>
        </div>
        <div class="panel-body form-horizontal">
            <div class="form-group">
                <label class="col-xs-1 control-label">编码:</label>
                <div class="col-xs-3">
                    <input name="DataTypeCode" type="text" value='<?php echo $BUData[0]['DataTypeCode'];?>' maxlength="10" readonly="readonly" id="BodyContent_conDataTypeCode" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">名称:</label>
                <div class="col-xs-3">
                    <input name="BuName" type="text" value="<?php echo $BUData[0]['BuName'];?>" maxlength="50" readonly="readonly" id="BodyContent_conBuName" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">表名:</label>
                <div class="col-xs-3">
                     <input name="BuTable" type="text" value="<?php echo $BUData[0]['BuTable'];?>" maxlength="50" readonly="readonly" id="BuTable" NotNull="true" class="form-control" />
                      
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">ID字段:</label>
                <div class="col-xs-3">
                    <input name="DisFiledID" type="text" value="<?php echo $BUData[0]['DisFiledID'];?>" maxlength="10" readonly="readonly" id="DisFiledID" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">编码字段:</label>
                <div class="col-xs-3">
                    <input name="DisFiledF" type="text" value="<?php echo $BUData[0]['DisFiledF'];?>" maxlength="50" readonly="readonly" id="DisFiledF" NotNull="true" class="form-control" />
                    
                </div>
                <label class="col-xs-1 control-label">名称字段:</label>
                <div class="col-xs-3">
                    <input name="DisFiledS" type="text" value="<?php echo $BUData[0]['DisFiledS'];?>" maxlength="50" readonly="readonly" id="DisFiledS" NotNull="true" class="form-control" />
                    
                </div>  
            </div>
            <div class="form-group">
                <label class="col-xs-1 control-label">自关联字段:</label>
                <div class="col-xs-3">
                    <input name="SelfLinkFiled" type="text" value="<?php echo $BUData[0]['SelfLinkFiled'];?>" maxlength="10" readonly="readonly" id="SelfLinkFiled" NotNull="true" class="form-control" />
                </div>
                <label class="col-xs-1 control-label">过滤条件:</label>
                <div class="col-xs-3">
                    <input name="FrFiled" type="text" maxlength="50" readonly="readonly" id="FrFiled" NotNull="true" class="form-control" value="">
                </div>
                
            </div>
            

        </div>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#subFunctions" data-toggle="tab"><strong>业务数据</strong></a></li>
    </ul>
    <div class="tab-content mb30">
        <div class="tab-pane active " id="subFunctions" >
            <ul id="treeDemo" class="ztree" ></ul>
        </div>
    </div>



    
</body>
</html>
