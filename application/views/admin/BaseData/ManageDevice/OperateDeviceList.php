<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
        .m-0,.form-inline .m-0{margin: 0;}
		.m-5{margin-right:5px;}
        .m-10{margin-right: 10px;}
		.m-20{margin-right:20px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        .form-inline .col-xs-10{width:100%;}
        .col-xs-10, .col-xs-10{padding-right: 0}
        .col-xs-3{width: 25% !important;text-align: right;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .form-control{margin-right: 0}
        .col-xs-3, .col-xs-6{padding-right: 0;padding-left:0;}
        .col-xs-8{width: 75%}
        .form-group select{width: 100% !important;}
	</style>

    <script type="text/javascript" language="javascript">
    var tag = '<?php echo $tag ?>';
    var deviceid = "<?php echo $deviceid ?>";
console.log(deviceid);
    $().ready(function(){
        var type = '<?php if(isset($data[0]['devicetype'])){echo $data[0]['devicetype'];}?>';
        var road = '<?php if(isset($data[0]['roadoldid'])){echo $data[0]['roadoldid'];}?>';
        var typeSelect = document.getElementById('typeSel');
        var typeOption = typeSelect.getElementsByTagName('option');
        for (var i=0; i<typeOption.length; i++) {
            if (typeOption[i].value == type) {
                typeOption[i].selected=true;
                break;
            }
        }
        var roadSelect = document.getElementById('roadSel');
        var roadOption = roadSelect.getElementsByTagName('option');
        for (var i=0; i<roadOption.length; i++) {
            if (roadOption[i].value == road) {
                roadOption[i].selected=true;
                break;
            }
        }
    });
    
    function trimStr(str){
        var res = str.replace(/(^\s*)|(\s*$)/g,"");
        return res;
    }

    function submit(){
        var name = $('#name').val();
        var type = $('#typeSel').val();
        var roadold = $('#roadSel').val();
        var direction = $('#direction').val();
        var coor_x = $('#coor_x').val();
        var coor_y = $('#coor_y').val();
        var miles = $('#miles').val();
        var remark = $('#remark').val();
        var picture = $('#picture').val();

        if(trimStr(name) == ''){alert('名称不能为空');return;}
        if(trimStr(type) == ''){alert('请选择类型');return;}
        if(trimStr(roadold) == ''){alert('请选择路段');return;}

        if (tag == '0') {//新增
            JAjax('admin/baseData/DeviceLogic','saveDeviceMsg',{deviceid:0,name:name,type:type,roadold:roadold,direction:direction,coor_x:coor_x,coor_y:coor_y,miles:miles,remark:remark,picture:picture},function(data){
                if (data.data) {
                    alert('新增成功!');closeLayerPageJs();
                }else{
                    ShowMsg('新增失败');
                }
            },'page');
        }else if(tag == '1'){//查看-修改
            if(deviceid == ''){
                ShowMsg('获取设备ID出错!');return;
            }
            JAjax('admin/baseData/DeviceLogic','saveDeviceMsg',{deviceid:deviceid,name:name,type:type,roadold:roadold,direction:direction,coor_x:coor_x,coor_y:coor_y,miles:miles,remark:remark,picture:picture},function(data){
                if (data.data) {
                    alert('操作成功!');closeLayerPageJs();
                }else{
                    ShowMsg('操作失败');
                }
            },'page');
        }
    }


    /**
     * @desc   关闭子窗口
     */
    function dropOut(){
        closeLayerPageJs();
    }

</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">设备(CCTV、VMS)信息</div>
        <div class="panel-body">
            <div class="form-inline">
                <div class="form-group col-xs-6 m-0 p-b-15" >
                    <label for="name" class="col-xs-3 control-label m-t-10">名称:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="name" placeholder="<?php if(isset($data[0]['name'])){echo $data[0]['name'];}?>" value="<?php if(isset($data[0]['name'])){echo $data[0]['name'];}?>">
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15" >
                    <label for="type" class="col-xs-3 control-label m-t-10">类型:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <select class="form-control col-xs-10" id="typeSel">
                            <?php foreach($select['type'] as $item): ?>
                                <option value="<?=$item['dictcode']?>"><?=$item['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15" >
                    <label for="roadold" class="col-xs-3 control-label m-t-10">路段归属:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <select class="form-control col-xs-10" id="roadSel">
                            <?php foreach($select['roadold'] as $item): ?>
                                <option value="<?=$item['roadoldid']?>"><?=$item['shortname']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15">
                    <label for="direction" class="col-xs-3 control-label m-t-10">方向:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="direction" placeholder="<?php if(isset($data[0]['direction'])){echo $data[0]['direction'];}?>" value="<?php if(isset($data[0]['direction'])){echo $data[0]['direction'];}?>">
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15">
                    <label for="coor_x" class="col-xs-3 control-label m-t-10">经度:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="coor_x" placeholder="<?php if(isset($data[0]['coor_x'])){echo $data[0]['coor_x'];}?>" value="<?php if(isset($data[0]['coor_x'])){echo $data[0]['coor_x'];}?>">
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15">
                    <label for="coor_y" class="col-xs-3 control-label m-t-10">纬度:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="coor_y" placeholder="<?php if(isset($data[0]['coor_y'])){echo $data[0]['coor_y'];}?>" value="<?php if(isset($data[0]['coor_y'])){echo $data[0]['coor_y'];}?>">
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15">
                    <label for="miles" class="col-xs-3 control-label m-t-10">公里数:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="miles" placeholder="<?php if(isset($data[0]['miles'])){echo $data[0]['miles'];}?>" value="<?php if(isset($data[0]['miles'])){echo $data[0]['miles'];}?>">
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15">
                    <label for="remark" class="col-xs-3 control-label m-t-10">备注:</label>
                    <div class="col-xs-8" style="height:41px;">
                        <input type="text" class="form-control col-xs-10" id="remark" placeholder="<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>" value="<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>">
                    </div>
                </div>
                <div class="form-group col-xs-6 m-0 p-b-15" style="width: 100%;">
                    <label for="picture" class="col-xs-3 control-label m-t-10" style="width: 12.5% !important;">快拍:</label>
                    <div class="col-xs-8" style="width: 87.5%;">
                        <!-- <input type="text" class="form-control col-xs-10" style="width: 100% !important;" id="picture" placeholder="<?php if(isset($data[0]['picturefile'])){echo $data[0]['picturefile'];}?>" value="<?php if(isset($data[0]['picturefile'])){echo $data[0]['picturefile'];}?>"> -->
                        <textarea class="form-control col-xs-10" style="height:100px;" id="picture" spellcheck="false" placeholder="<?php echo isset($data[0]['picturefile'])?$data[0]['picturefile']:'' ?>"><?php echo isset($data[0]['picturefile'])?$data[0]['picturefile']:'' ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存" id="new" onclick="submit();" class="btn btn-info m-10" >
                <input type="button" value="返 回" id="del" onclick="dropOut();" class="btn btn-danger" >
            </div>
        </div>
    </div>
</body>
</html>