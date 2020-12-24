<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        var poiid = '<?php echo isset($poiid)?$poiid:'' ?>';
        var id = '<?php echo isset($id)?$id:'0' ?>';
       /* var gasname = '<?php echo isset($gasname)?$gasname:'' ?>';
        var price = '<?php echo isset($price)?$price:'' ?>';
        var status = '<?php echo isset($status)?$status:'' ?>';*/

        //设置下拉框默认值为站点属性值
        $().ready(function(){
            
            
        });

        function trimStr(str){ //删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        /**
         * @desc   保存服务区信息
         * @return {[type]}    [description]
         */
        function submit(){
            var gasname = $('#gasname').val();
            var price = $('#price').val();
            var status = $('#statusName').val();
            
            if (trimStr(gasname) == '') {ShowMsg('油类名称不能为空');return;}
            if (trimStr(price) == '') {ShowMsg('价格不能为空');return;}
            if (trimStr(status) == '') {ShowMsg('状态不能为空');return;}

            JAjax("admin/baseData/ServiceLogic", 'saveGasMsg', {poiid:poiid,id:id,gasname:gasname,price:price,status:status}, function (data) {
                if (data.Success) {
                    closeLayerPageJs();
                }else{
                    ShowMsg("新增失败:" + data.Message);
                }
            }, null);
        }
        
        function closeqw(){
            closeLayerPageJs();
        }

    </script>
    <style type="text/css">
        #upicon{cursor:pointer ;}
        .content{color:#0000FF;width: 180px;width: 180px;}
        table{border-collapse: collapse;}
        table{margin: 10px;}
        .vc_table tr td.title{width: 50px;}
        .td-width{width: 70px !important;text-align: right;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .vc_table .content{width: 180px;}
        .m-10{margin-right: 10px;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">服务区基础数据</div>
    <div class="panel-body" style="padding:10px;">
        <table cellspacing="1" cellpadding="4" class="vc_table" >
            <tbody>                   
                <tr>
                    <td class="td-width">
                        油类:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="gasname" placeholder="<?php echo isset($data['gasname'])?$data['gasname']:''; ?>" value="<?php echo isset($data['gasname'])?$data['gasname']:''; ?>" />
                    </td>
                    <td class="td-width">
                        价格:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="price" placeholder="<?php echo isset($data['price'])?$data['price']:''; ?>" value="<?php echo isset($data['price'])?$data['price']:''; ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="td-width">
                        状态:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="statusName" placeholder="<?php echo isset($data['status'])?$data['status']:''; ?>" value="<?php echo isset($data['status'])?$data['status']:''; ?>" />
                    </td>
                    <td class="td-width"></td>
                    <td class="content"></td>
                </tr>
            </tbody>
        </table>
     </div>
    <div class="panel-footer">
        <input type="button" value="确定" class="btn btn-info m-10" onclick="submit();"/>
        <input type="button" value="返回" class="btn btn-danger" onclick="closeqw();" />
    </div>
 </div>
</body>
</html>