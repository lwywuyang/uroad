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
        .m-20{margin-right:20px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        .form-inline .col-xs-10{width:83.33333333%;}
        .vc_table{margin: 0;padding:0;}
        .td-width{text-align: right;}
        .vc_table tr td:first{width: 50px;}
        .vc_table .content{max-width: 180px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
    </style>
    
    <script type="text/javascript" language="javascript">
        var id = "<?php echo isset($msg['id'])?$msg['id']:'0'; ?>";
        var roadoldid = "<?php echo isset($msg['roadoldid'])?$msg['roadoldid']:''; ?>";

        $().ready(function(){
            $('#roadSel').find('option[value='+roadoldid+']').attr('selected',true);
        });
        

        function dropOut() {
            closeLayerPageJs();
        }

        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function save(){
            var roadSel = $('#roadSel').val();
            var phone = $('#phone').val();
            var remark = $('#remark').val();
            
            if(trimStr(roadSel) == ''){alert('请选择相关路段');return;}
            if(trimStr(phone) == ''){alert('电话不能为空');return;}
            //if(trimStr(remark) == ''){alert('备注不能为空');return;}
            
            JAjax("admin/MsgPublish/PhoneLogic", "savePhoneMsg", {id:id,roadSel:roadSel,phone:phone,remark:remark}, function (data){
                if (data.Success) {
                    closeLayerPageJs();
                }else{
                    ShowMsg("error:" + data.Message);
                }
            },null);
        }

</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">救援电话</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-width">
                            路段:
                        </td>
                        <td class="content">
                            <select class="form-control" id="roadSel">
                                <?php foreach($roadold as $item):?>
                                    <option value="<?=$item['roadoldid']?>"><?=$item['shortname']?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="td-width">
                            电话:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control" id="phone" value="<?php echo isset($msg['phonenum'])?$msg['phonenum']:'' ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            备注:
                        </td>
                        <td class="content" colspan="3">
                            <textarea class="form-control" id="remark" value="<?php echo isset($msg['remark'])?$msg['remark']:'' ?>"><?php echo isset($msg['remark'])?$msg['remark']:'' ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存"  id="new" onclick="save();" class="btn btn-info m-20" >
                <input type="button" value="返 回" id="del" onclick="dropOut();" class="btn btn-danger m-20" >
            </div>
        </div>
    </div>
</body>
</html>