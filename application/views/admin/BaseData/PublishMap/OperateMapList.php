<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        var id = '<?php echo $id; ?>';

        function trimStr(str){ //删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        /**
         * @desc   保存站点信息
         * @data   2015-9-17 17:05:55
         * @return {[type]}    [description]
         */
        function submit(){
            var mapid = $('#mapid').val();
            var pubcode = $('#pubcode').val();
            var x = $('#x').val();
            var y = $('#y').val();

            if (trimStr(mapid) == '') {alert('简图ID不能为空');return;}
            if (trimStr(pubcode) == '') {alert('编码不能为空');return;}
            if (trimStr(x) == '') {alert('X不能为空');return;}
            if (trimStr(y) == '') {alert('Y不能为空');return;}

            JAjax("admin/baseData/PublishMapLogic", 'saveMapMsg', {id:id,mapid:mapid,pubcode:pubcode,x:x,y:y}, function (data) {
                //ReloadTb('dataGrid', data.data);
                if (data.Success) {
                    alert('操作成功!');
                    closeqw();
                }else{
                    alert('操作失败!');
                }
            }, "pager");

        }
        
        function closeqw(){
            closeLayerPageJs();
        }
    </script>
    <style type="text/css">
        .panel-body{padding: 0;}
        #upicon{cursor:pointer;}
        .content{color:#0000FF;}
        table{border-collapse: collapse;margin-right:10px;}
        .vc_table .name{width: 80px !important;}
        #address{width: 98%;}
        .vc_table .content{max-width: 180px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .star{color: red;}
        .m-r-10{margin-right: 10px;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">简图发布段</div>
    <div class="panel-body" style="padding: 10px;padding-left: 0">
        <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
            <tbody>
                <tr>
                    <td class="name" nowrap="nowrap">
                        <span class="star">*</span>简图ID:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="mapid" placeholder="<?php if(isset($data[0]['mapid'])){echo $data[0]['mapid'];}?>" value="<?php if(isset($data[0]['mapid'])){echo $data[0]['mapid'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        <span class="star">*</span>编码:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="pubcode" placeholder="<?php if(isset($data[0]['pubcode'])){echo $data[0]['pubcode'];}?>" value="<?php if(isset($data[0]['pubcode'])){echo $data[0]['pubcode'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        <span class="star">*</span>X:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="x" placeholder="<?php if(isset($data[0]['x'])){echo $data[0]['x'];}?>" value="<?php if(isset($data[0]['x'])){echo $data[0]['x'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        <span class="star">*</span>Y:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="y" placeholder="<?php if(isset($data[0]['y'])){echo $data[0]['y'];}?>" value="<?php if(isset($data[0]['y'])){echo $data[0]['y'];}?>" />
                    </td>
                </tr>
            </tbody>
        </table>
     </div>
    <div class="panel-footer">
        <input type="button" value="确 定" class="btn btn-info m-10" onclick="submit();" />
        <input type="button" value="返 回" class="btn btn-danger" onclick="closeqw();" />
    </div>
</div>
</body>
</html>