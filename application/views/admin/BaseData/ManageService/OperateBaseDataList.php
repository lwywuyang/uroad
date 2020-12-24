<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        //var poiid = '<?php if(isset($poiid)){echo $poiid;}?>';
        var poiid = '<?php echo $poiid ?>';
        //设置下拉框默认值为站点属性值
        $().ready(function(){
            //alert(poiid);
            if (poiid != 0) {
                var roadId = '<?php if(isset($data[0]['roadoldid'])){echo $data[0]['roadoldid'];}?>';
                var styleCode = '<?php if(isset($data[0]['pointtype'])){echo $data[0]['pointtype'];}?>';
                //var roadId = '<?php echo $data[0]['roadoldid'] ?>';
                //var styleCode = '<?php echo $data[0]['pointtype'] ?>';

                var roadSelect = document.getElementById('roadSel');
                var typeSelect = document.getElementById('typeSel');
                var roadOption = roadSelect.getElementsByTagName('option');
                for (var i=0; i<roadOption.length; i++) {
                    if (roadOption[i].value == roadId) {
                        roadOption[i].selected=true;
                        break;
                    }
                }

                var typeOption = typeSelect.getElementsByTagName('option');
                for (var i=0; i<typeOption.length; i++) {
                    if (typeOption[i].value == styleCode) {
                        typeOption[i].selected=true;
                        break;
                    }
                }

                var direction = '<?php if(isset($data[0]['direction'])){echo $data[0]['direction'];}?>';
                var directionSelect = document.getElementById('directionSel');
                var directionOption = directionSelect.getElementsByTagName('option');
                for (var i=0; i<directionOption.length; i++) {
                    if (directionOption[i].value == direction) {
                        directionOption[i].selected=true;
                        break;
                    }
                }
            }
            
        });

        function trimStr(str){ //删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        /**
         * @desc   保存服务区信息
         * @return {[type]}    [description]
         */
        function submit(){
            var name = $('#name').val();
            var typeSel = $('#typeSel').val();
            var roadSel = $('#roadSel').val();
            var direction = $('#directionSel').val();
            var coor_x = $('#coor_x').val();
            var coor_y = $('#coor_y').val();
            var miles = $('#miles').val();
            var phone = $('#phone').val();
            var city = $('#city').val();
            var address = $('#address').val();
            var stationcode = $('#stationcode').val();

            if (trimStr(name) == '') {alert('站点名称不能为空');return;}
            if (typeSel == '') {alert('获取站点类型出错');return;}
            if (roadSel == '') {alert('获取所属路段出错');return;}
            if (direction == '') {alert('获取方向出错');return;}
            /*if (coor_x == '') {alert('经度不能为空');return;}
            if (coor_y == '') {alert('纬度不能为空');return;}
            if (miles == '') {alert('公里数不能为空');return;}
            if (address == '') {alert('站点地址不能为空');return;}*/


            if (poiid == 0) {//新增
                JAjax("admin/baseData/ServiceLogic", 'saveServiceMsg', {poiid:0,name:name,typeSel:typeSel,roadSel:roadSel,direction:direction,coor_x:coor_x,coor_y:coor_y,miles:miles,address:address,phone:phone,city:city,stationcode:stationcode}, function (data) {
                    if (data.data == true) {
                        alert('新增成功');closeLayerPageJs();
                    }else{
                        //alert('操作失败');
                        console.log(data);
                        ShowMsg("新增失败:" + data.Message);
                    }
                    //ReloadTb('dataGrid', data.data);
                }, "pager");
            }else{//修改
                JAjax("admin/baseData/ServiceLogic", 'saveServiceMsg', {poiid:poiid,name:name,typeSel:typeSel,roadSel:roadSel,direction:direction,coor_x:coor_x,coor_y:coor_y,miles:miles,address:address,phone:phone,city:city,stationcode:stationcode}, function (data) {
                    if (data.data == true) {
                        alert('操作成功');closeLayerPageJs();
                    }else{
                        ShowMsg("操作失败:" + data.Message);
                    }
                }, "pager");
            }
        }
        
        function closeqw(){
            closeLayerPageJs();
        }

    </script>
    <style type="text/css">
        #upicon{cursor:pointer ;}
        .content{
            color:#0000FF;
            width: 180px;
            width: 180px;
        }
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
                        名称:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="name" placeholder="<?php if(isset($data[0]['name'])){echo $data[0]['name'];}?>" value="<?php if(isset($data[0]['name'])){echo $data[0]['name'];}?>" />
                    </td>
                    <td class="td-width">
                        类型:
                    </td>
                    <td class="content">
                        <select class="form-control" id="typeSel">
                            <?php foreach($type as $item): ?>
                                <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="td-width">
                        路段归属:
                    </td>
                    <td class="content">
                        <select class="form-control" id="roadSel">
                            <?php foreach($road as $item): ?>
                                <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['shortname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="td-width">
                        方向:
                    </td>
                    <td class="content">
                        <select class="form-control" id="directionSel">
                            <option value="1">上行</option>
                            <option value="2">下行</option>
                            <option value="0">双向</option>
                        </select>
                        <!-- <input type="text" class="form-control" id="direction" placeholder="<?php if(isset($data[0]['direction'])){echo $data[0]['direction'];}?>" value="<?php if(isset($data[0]['direction'])){echo $data[0]['direction'];}?>" /> -->
                    </td>
                </tr>
                <tr>
                    <td class="td-width">
                        经度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="coor_x" placeholder="<?php if(isset($data[0]['coor_x'])){echo $data[0]['coor_x'];}?>" value="<?php if(isset($data[0]['coor_x'])){echo $data[0]['coor_x'];}?>" />
                    </td>
                    <td class="td-width">
                        纬度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="coor_y" placeholder="<?php if(isset($data[0]['coor_y'])){echo $data[0]['coor_y'];}?>" value="<?php if(isset($data[0]['coor_y'])){echo $data[0]['coor_y'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="td-width">
                        公里数:
                    </td>
                    <td class="content">  
                        <input type="text" class="form-control" id="miles" placeholder="<?php if(isset($data[0]['miles'])){echo $data[0]['miles'];}?>" value="<?php if(isset($data[0]['miles'])){echo $data[0]['miles'];}?>" />
                    </td>
                    <td class="td-width">
                        电话:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="phone" placeholder="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" value="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="td-width">
                        城市:
                    </td>
                    <td class="content">  
                        <input type="text" class="form-control" id="city" placeholder="<?php if(isset($data[0]['city'])){echo $data[0]['city'];}?>" value="<?php if(isset($data[0]['city'])){echo $data[0]['city'];}?>" />
                    </td>
                    <td class="td-width">
                        编码:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="stationcode" placeholder="<?php if(isset($data[0]['stationcode'])){echo $data[0]['stationcode'];}?>" value="<?php if(isset($data[0]['stationcode'])){echo $data[0]['stationcode'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="td-width">
                        地址:
                    </td>
                    <td class="content" colspan="3">
                        <input type="text" class="form-control" id="address" placeholder="<?php if(isset($data[0]['address'])){echo $data[0]['address'];}?>" value="<?php if(isset($data[0]['address'])){echo $data[0]['address'];}?>" />
                    </td>
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