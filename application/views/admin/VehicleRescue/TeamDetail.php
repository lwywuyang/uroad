<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
        .m-0,.form-inline .m-0{margin: 0;}
        .m-r-10{margin-right: 10px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .form-group select{width: 100% !important;}
	</style>
    <script type="text/javascript" language="javascript">
        var id = "<?php echo $id ?>";
        var managerid = '<?php echo isset($managerid)?$managerid:'' ?>';
        var type = '<?php echo isset($type)?$type:'' ?>';

        $().ready(function(){
            $('#typeSel').find('option[value='+type+']').attr('selected','selected');
            $('#managerSel').find('option[value='+managerid+']').attr('selected','selected');
        });
        
        function trimStr(str){
            var res = str.replace(/(^\s*)|(\s*$)/g,"");
            return res;
        }

        function submit(){
            var typeSel = $('#typeSel').val();
            var name = $('#name').val();
            var managerid = $('#managerSel').val();
            var phone = $('#phone').val();
            var managerzone = $('#managerzone').val();
            var seq = $('#seq').val();

            if(trimStr(name) == ''){alert('名称不能为空!');return;}
            if(trimStr(phone) == ''){alert('电话不能为空!');return;}
            //if(trimStr(managerzone) == ''){alert('管辖范围不能为空!');return;}

            JAjax('admin/VehicleRescue/TeamLogic','saveTeamMsg',{id:id,typeSel:typeSel,name:name,managerid:managerid,phone:phone,managerzone:managerzone,seq:seq},function(data){
                if (data.Success)
                    closeLayerPageJs();
                else
                    ShowMsg(data.Message);
            },null);
        }

    </script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">中/大队详细信息</div>
        <div class="panel-body">
            <div class="form-inline">
                <div class="form-group col-xs-12 m-0 p-b-15" >
                    <label for="typeSel" class="col-xs-3 control-label m-t-10">类型:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <select class="form-control col-xs-10" id="typeSel">
                            <?php foreach($teamType as $item): ?>
                                <option value="<?=$item['dictcode']?>"><?=$item['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-xs-12 m-0 p-b-15" >
                    <label for="name" class="col-xs-3 control-label m-t-10">名称:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="name" placeholder="<?php echo isset($name)?$name:'' ?>" value="<?php echo isset($name)?$name:'' ?>">
                    </div>
                </div>
                <div class="form-group col-xs-12 m-0 p-b-15" >
                    <label for="managerSel" class="col-xs-3 control-label m-t-10">所属管理处:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <select class="form-control col-xs-10" id="managerSel">
                            <?php foreach($roadper as $item): ?>
                                <option value="<?=$item['id']?>"><?=$item['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-xs-12 m-0 p-b-15" >
                    <label for="roadold" class="col-xs-3 control-label m-t-10">电话:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="phone" placeholder="<?php echo isset($phone)?$phone:'' ?>" value="<?php echo isset($phone)?$phone:'' ?>">
                    </div>
                </div>
                <div class="form-group col-xs-12 m-0 p-b-15">
                    <label for="direction" class="col-xs-3 control-label m-t-10">管辖范围:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="managerzone" placeholder="<?php echo isset($managerzone)?$managerzone:'' ?>" value="<?php echo isset($managerzone)?$managerzone:'' ?>">
                    </div>
                </div>
                <div class="form-group col-xs-12 m-0 p-b-15">
                    <label for="remark" class="col-xs-3 control-label m-t-10">备注:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="seq" placeholder="<?php echo isset($seq)?$seq:'' ?>" value="<?php echo isset($seq)?$seq:'' ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存" id="save" onclick="submit();" class="btn btn-info m-r-10" >
                <input type="button" value="返 回" id="del" onclick="closeLayerPageJs();" class="btn btn-danger" >
            </div>
        </div>
    </div>
</body>
</html>