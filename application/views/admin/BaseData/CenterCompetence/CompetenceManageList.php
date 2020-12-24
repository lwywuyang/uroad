<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
	<style>
        .m-0,.form-inline .m-0{margin: 0;}
		.m-5{margin-right:5px;}
		.m-20{margin-right:20px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        /* .form-inline .col-xs-10{width:83.33333333%;} */
        .vc_table{margin: 0;padding:0;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        a {cursor: pointer;}
        .dir-select{float: left;max-width: 150px;}
        .padding-0{padding-top:0 !important;padding-bottom: 0 !important;}
        .must{color: #FE4E4E;}
        .td-left{width: 10%;}
        .td-right{width: 90%;}
	</style>
    
    <script type="text/javascript" language="javascript">
        var id = "<?php echo isset($subMsg['id'])?$subMsg['id']:''; ?>";
        var roadOldArr = new Array();
        $().ready(function(){
            //alert(eventId);
            //修改事件信息的时候,设置页面的初始值
            if (id != '0') {
                var startRoadOldIds = "<?php echo isset($subMsg['roadoldids'])?$subMsg['roadoldids']:''; ?>";
                roadOldArr = startRoadOldIds.split(',');

                $.each(roadOldArr,function(n,value){
                    $('input:checkbox[value="'+value+'"]:checkbox').attr('checked','checked');
                });

            }
        });


        function dropOut() {
            closeLayerPageJs();
        }

        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        
        /**
         * @desc   获取站点多选框的选中值
         * @return {[type]}            [description]
         */
        function getAllCheckedValues(name,attr) {
            var result = '';
            if (attr == 'text') {
                $("input[name='" + name + "']:checked").each(function(){ 
                    result += $(this).attr('text') + ',';
                });
            }else{
                $("input[name='" + name + "']:checked").each(function(){ 
                    result += $(this).attr('value') + ',';
                });
            }
            result = result.substring(0, result.length - 1);//去掉最后一个逗号
            return result;
        }

        //保存
        function saveMsg(){
            var centerName = $('#centerName').val();
            var roadIds = getAllCheckedValues('road','value');//获取多选框的值

            if(trimStr(centerName) == ''){alert('分中心名称不能为空!');return;}

            JAjax("admin/baseData/CenterCompetenceLogic","saveSubCenterMsg",{id:id,centerName:centerName,roadIds:roadIds}, function (data){
                if (data.Success) {
                    //ShowMsg("操作成功!");
                    closeLayerPageJs();
                }else{
                    ShowMsg(data.Message);
                }
            },null);

        }

</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">分中心权限管理</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-left">
                            分中心:
                        </td>
                        <td class="td-right">
                            <input type="text" class="form-control" id="centerName" placeholder="请输入分中心名称" value="<?php echo isset($subMsg['name'])?$subMsg['name']:'' ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-left">
                            管理高速:
                        </td>
                        <td class="td-right" colspan="3" id="stationCheckBox">
                            <?php foreach($road as $item): ?>
                                <label><input type="checkbox" name="road" value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadname'] ?></label>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存"  id="new" onclick="saveMsg();" class="btn btn-info m-5" >
                <input type="button" value="返 回" id="del" onclick="dropOut();" class="btn btn-danger m-20" >
            </div>
        </div>
    </div>
</body>
</html>