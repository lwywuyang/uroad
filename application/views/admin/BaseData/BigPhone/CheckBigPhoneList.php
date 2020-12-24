<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        //var poiid = '<?php if(isset($poiid)){echo $poiid;}?>';
        var phoneid = '<?php echo $id ?>';
        //设置下拉框默认值为站点属性值
        $().ready(function(){

            if (phoneid == '') {
                alert('不正确的ID');
                var src = "<?php echo base_url('/index.php/admin/baseData/BigPhoneLogic/indexPage'); ?>";
                $(window.top.document).find('#iframeContent'),eq(0).attr('src','').attr('src',src);
            }
            
        });


        function trimStr(str){ //删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        /**
         * @desc   检查id是否重复
         * @data   datatime
         * @return {[type]}    [description]
         */
        function checkID(){
            var deviceid = $('#deviceid').val();
            if (trimStr(deviceid) != '') {
                $.ajax({
                    type:'post',
                    url:InpageUrl+'admin/baseData/BigPhoneLogic/checkDeviceId',                       
                    data:{deviceid:deviceid,phoneid:phoneid},
                    success:function(result){
                        if (result == true) {
                            alert('大手机ID已存在,请修改!');
                        }
                    },
                    error:function(){}
                });
            }
        }


        /**
         * @desc   保存站点信息
         * @data   2015-9-17 17:05:55
         * @return {[type]}    [description]
         */
        function submit(){
            var deviceid = $('#deviceid').val();
            var devicename = $('#devicename').val();
            var longitude = $('#longitude').val();
            var latitude = $('#latitude').val();
            var remark = $('#remark').val();
            var city = $('#city').val();

            if (trimStr(deviceid) == '') {alert('大手机设备ID不能为空');return;}
            if (trimStr(devicename) == '') {alert('大手机名不能为空');return;}

            $.ajax({
                type:'post',
                url:InpageUrl+'admin/baseData/BigPhoneLogic/checkDeviceId',                       
                data:{deviceid:deviceid,phoneid:phoneid},
                success:function(result){
                    if (result == true) {
                        alert('大手机ID已存在,无法提交,请修改!');
                    }else{
                        JAjax("admin/baseData/BigPhoneLogic", 'saveBigPhoneMsg', {
                            phoneid:phoneid,deviceid:deviceid,devicename:devicename,longitude:longitude,
                            latitude:latitude,remark:remark,city:city
                        }, function (data) {
                            if (data.data == true) {
                                alert('操作成功');closeLayerPageJs();
                            }else{
                                ShowMsg("操作失败:" + data.data);
                            }
                        }, "pager");
                    }
                },
                error:function(){
                    alert('提交失败');
                }
            });

        }
        
        function closeqw(){
            closeLayerPageJs();
        }


    </script>
    <style type="text/css">
        .panel-body{
            padding: 0;
        }
        #upicon{
            cursor:pointer ;
        }
        .content{
            color:#0000FF;
        }
        table{
            border-collapse: collapse;
            margin-right:10px;
        }
        .vc_table .name{
            width: 80px !important;
        }
        #address{
            width: 98%;
        }
        .btn{
            float: right;
        }
        .vc_table .content{max-width: 180px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">大手机详细信息</div>
    <div class="panel-body" style="padding: 10px;padding-left: 0">
        <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
            <tbody>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        大手机ID:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="deviceid" placeholder="<?php if(isset($data[0]['deviceid'])){echo $data[0]['deviceid'];}?>" value="<?php if(isset($data[0]['deviceid'])){echo $data[0]['deviceid'];}?>" onblur="checkID();" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        大手机名:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="devicename" placeholder="<?php if(isset($data[0]['devicename'])){echo $data[0]['devicename'];}?>" value="<?php if(isset($data[0]['devicename'])){echo $data[0]['devicename'];}?>" />
                        <!-- <select class="form-control" id="typeSel">
                            <?php foreach($type as $item): ?>
                                <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                            <?php endforeach; ?>
                            
                        </select> -->
                    </td>
                    
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        经度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="longitude" placeholder="<?php if(isset($data[0]['longitude'])){echo $data[0]['longitude'];}?>" value="<?php if(isset($data[0]['longitude'])){echo $data[0]['longitude'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        纬度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="latitude" placeholder="<?php if(isset($data[0]['latitude'])){echo $data[0]['latitude'];}?>" value="<?php if(isset($data[0]['latitude'])){echo $data[0]['latitude'];}?>" />
                        <!-- <select class="form-control" id="roadSel">
                            <?php foreach($road as $item): ?>
                                <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['shortname'] ?></option>
                            <?php endforeach; ?>
                            
                        </select> -->
                    </td>
                    <!-- <td class="name" nowrap="nowrap" >
                        电话:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="phone" placeholder="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" value="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" />
                    </td> -->
                    
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        备注:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="remark" placeholder="<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>" value="<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        所在城市:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="city" placeholder="<?php if(isset($data[0]['city'])){echo $data[0]['city'];}?>" value="<?php if(isset($data[0]['city'])){echo $data[0]['city'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        状态:
                    </td>
                    <td class="content">
                        <?php if(isset($data[0]['statusName'])){echo $data[0]['statusName'];}?>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        添加时间:
                    </td>
                    <td class="content">
                        <?php if(isset($data[0]['updatetime'])){echo $data[0]['updatetime'];}?>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        IP:
                    </td>
                    <td class="content">
                        <?php if(isset($data[0]['lastip'])){echo $data[0]['lastip'];}?>
                    </td>
                </tr>
            </tbody>
        </table>
     </div>
    <div class="panel-footer">
        <input type="button" value="返回" class="btn btn-danger" onclick="closeqw();" />
        <input type="button" value="确定" class="btn btn-info" style="margin-right: 15px;" onclick="submit();" />
        
<!--        <input type="button" value="封号" class="btn btn-primary" onclick="fenhao();" />-->
    </div>
 </div>
</body>
</html>