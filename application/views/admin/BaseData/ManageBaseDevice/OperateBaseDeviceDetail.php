<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
        .m-0,.form-inline .m-0{margin: 0;}
        .m-15{margin-right: 15px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        .form-inline{width:100%;}
        .col-xs-2{text-align: right;line-height: 41px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .form-control{margin-right: 0}
	</style>
    <script type="text/javascript" language="javascript">
        var deviceid = "<?php echo $deviceid ?>";

        
        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g,"");
        }

        function submit(){
            var coor_x = $('#coor_x').val();
            var coor_y = $('#coor_y').val();

            JAjax('admin/baseData/BaseDeviceLogic','saveDetailMsg',{deviceid:deviceid,coor_x:coor_x,coor_y:coor_y},function(data){
                if (data.Success)
                    closeLayerPageJs();
                else
                    ShowMsg('提示:'+data.Message);
            },null);
        }

    </script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">设备经纬度</div>
        <div class="panel-body">
            <div class="form-inline">
                <div class="form-group">
                    <div class="col-xs-2">经度:</div>
                    <div class="col-xs-10">
                        <input type="text" id="coor_x" class="form-control" value="<?php echo isset($coor_x)?$coor_x:'' ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-2">纬度:</div>
                    <div class="col-xs-10">
                        <input type="text" id="coor_y" class="form-control" value="<?php echo isset($coor_y)?$coor_y:'' ?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存" id="new" onclick="submit();" class="btn btn-info m-15" >
                <input type="button" value="返 回" id="del" onclick="closeLayerPageJs();" class="btn btn-danger" >
            </div>
        </div>
    </div>
</body>
</html>