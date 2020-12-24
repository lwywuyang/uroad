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
        .td-width{text-align: right;width: 45px !important;}
        .vc_table tr td:first{width: 80px;}
        .vc_table .content{width:auto;max-width: 220px;width: 255px !important;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        a {cursor: pointer;}
	</style>
    
    <script type="text/javascript" language="javascript">
        var id = "<?php echo isset($id)?$id:0; ?>";
        //var istop = "<?php if (isset($data['istop'])) {echo $data['istop'];}else{echo '';} ?>";
        //等同于以下语句效果
        var istop = "<?php echo isset($data['istop'])?$data['istop']:''; ?>";
        
        $().ready(function(){
            $('#topSel').find("option[value="+istop+"]").attr('selected',true);
        });

        /**
         * [dropOut 返回上个页面]
         */
       function dropOut() {
            closeLayerPageJs();
        }


        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        /**
         * [detailChange 保存数据更新]
         * @param  {[type]} id [路段id]
         * @return {[type]}    [description]
         */
        function saveDetailMsg(){
            var remark = $('#remark').val();
            var phone = $('#phone').val();
            var seq = $('#seq').val();
            var topSel = $('#topSel').val();
            //
            //
            if(trimStr(remark) == ''){alert('名称不能为空');return;}
            if(trimStr(phone) == ''){alert('电话不能为空');return;}
            if(trimStr(seq) == ''){alert('序号不能为空');return;}


            if(id == 0){//新增数据
                JAjax("admin/WXManage/HotLineLogic","saveDetailMsg",{id:0,remark:remark,phone:phone,seq:seq,topSel:topSel}, function (data){
                    if (data.Success) {
                        //ShowMsg("操作成功!");
                        closeLayerPageJs();
                    }else{
                        ShowMsg(data.Message);
                    }
                },'pager');
            }else{//保存数据
                JAjax("admin/WXManage/HotLineLogic","saveDetailMsg",{id:id,remark:remark,phone:phone,seq:seq,topSel:topSel}, function (data){
                    if (data.Success) {
                        //ShowMsg("操作成功!");
                        closeLayerPageJs();
                    }else{
                        ShowMsg(data.Message);
                    }
                },'pager');
            }
        }


</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">服务热线详细信息</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-width">
                            名称:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="remark" placeholder="<?php if(isset($data['remark'])){echo $data['remark'];}?>" value="<?php if(isset($data['remark'])){echo $data['remark'];}?>">
                        </td>
                        <td class="td-width">
                            电话:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="phone" placeholder="<?php if(isset($data['phonenumber'])){echo $data['phonenumber'];}?>" value="<?php if(isset($data['phonenumber'])){echo $data['phonenumber'];}?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            序号:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="seq" placeholder="<?php if(isset($data['seq'])){echo $data['seq'];}?>" value="<?php if(isset($data['seq'])){echo $data['seq'];}?>">
                        </td>
                        <td colspan="2" style="width: 300px !important;">
                            <p style="width: 130px;text-align: right;line-height: 41px;float: left;margin-bottom: 0">是否设置首页电话:</p>
                            <select class="form-control" id="topSel" style="width: 70px !important; float: right">
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存"  id="new" onclick="saveDetailMsg();" class="btn btn-info m-5" >
                <input type="button" value="返 回" id="del" onclick="dropOut();" class="btn btn-danger m-20" >
                
            </div>
        </div>
    </div>
</body>
</html>