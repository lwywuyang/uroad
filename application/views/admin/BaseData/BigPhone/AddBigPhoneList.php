<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
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
                    data:{deviceid:deviceid},
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
                data:{deviceid:deviceid},
                success:function(result){
                    if (result == true) {
                        alert('大手机ID已存在,无法提交,请修改!');
                    }else{
                        JAjax("admin/baseData/BigPhoneLogic", 'saveBigPhoneMsg', {
                            phoneid:0,deviceid:deviceid,devicename:devicename,longitude:longitude,
                            latitude:latitude,remark:remark,city:city
                        }, function (data) {
                            if (data.data == true) {
                                alert('新增成功');closeLayerPageJs();
                            }else{
                                //alert('操作失败');
                                ShowMsg("新增失败:" + data.data);
                            }
                            //ReloadTb('dataGrid', data.data);
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
                        <input type="text" class="form-control" id="deviceid" onblur="checkID();" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        大手机名:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="devicename" />
                    </td>
                    
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        经度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="longitude" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        纬度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="latitude" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        备注:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="remark" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        所在城市:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="city" />
                    </td>
                </tr>
            </tbody>
        </table>
     </div>
    <div class="panel-footer">
        <input type="button" value="返回" class="btn btn-danger" onclick="closeqw();" />
        <input type="button" value="确定" class="btn btn-info" style="margin-right: 15px;" onclick="submit();" />
    </div>
 </div>
</body>
</html>