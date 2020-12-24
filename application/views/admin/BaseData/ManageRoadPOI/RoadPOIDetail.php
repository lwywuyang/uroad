<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        var poiid = '<?php echo $poiid ?>';
        //设置下拉框默认值为站点属性值
        $().ready(function(){
            if (poiid != '0') {
                var roadId = '<?php if(isset($data[0]['roadoldid'])){echo $data[0]['roadoldid'];}?>';
                var styleCode = '<?php if(isset($data[0]['pointtype'])){echo $data[0]['pointtype'];}?>';
                //var status = '<?php if(isset($data[0]['status'])){echo $data[0]['status'];}?>';
                //$('#statusSel').find('option[value='+status+']').attr('selected',true);

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
            }

            if(styleCode == '1002003'){
                blockHere('hub');
                noneHere('waynum');
                noneHere('etcnum');

                noneHere('nextRoad');
                blockHere('directionTr');
            }else{
                blockHere('waynum');
                blockHere('etcnum');
                noneHere('hub');

                blockHere('nextRoad');
                noneHere('directionTr');
            }

            

            //设置多选框的默认选中
            var hubString = '<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>';
            var hubArray = hubString.split(','); 
            for (var i=0; i<hubArray.length; i++) {
                $("#hubSel").find("option[value='"+hubArray[i]+"']").attr("selected",true);
            }
            jQuery('.chosen-select').chosen({'width':'100%','white-space':'nowrap'});
        });



        /**
         * @desc   保存站点信息
         * @return {[type]}    [description]
         */
        function submit(){
            var name = $('#name').val();
            var typeSel = $('#typeSel').val();
            var stationcode = $('#stationcode').val();
            var roadSel = $('#roadSel').val();
            var phone = $('#phone').val();
            var city = $('#city').val();
            var miles = $('#miles').val();
            var coor_x = $('#coor_x').val();
            var coor_y = $('#coor_y').val();
            var nowinwaynum = $('#nowinwaynum').val();
            var nowexitwaynum = $('#nowexitwaynum').val();
            var nowinetcnum = $('#nowinetcnum').val();
            var nowexitetcnum = $('#nowexitetcnum').val();

            var hub = $('#hubSel').val();
            //alert(hub);exit;
            var address = $('#address').val();

            //2015-11-19新增
            var nextRoadLeft = $('#nextRoadLeft').val();
            var nextRoadStraight = $('#nextRoadStraight').val();
            var nextRoadRight = $('#nextRoadRight').val();
            var tagAddress = $('#tagAddress').val();
            var comeRoad = $('#comeRoad').val();
            var neighborRoad = $('#neighborRoad').val();
            var viewAndCompapny = $('#viewAndCompapny').val();

            //var status = $('#statusSel').val();
            var direction1 = $('#direction1').val();
            var direction2 = $('#direction2').val();

            if (name == '') {alert('站点名称不能为空');return;}
            if (typeSel == '') {alert('获取站点类型出错');return;}
            if (stationcode == '') {alert('站点编号不能为空');return;}
            if (roadSel == '') {alert('获取所属路段出错');return;}


            if (poiid == '0') {//新增
                JAjax("admin/baseData/RoadPOILogic", 'saveNewRoadPoiMsg', {
                    name:name,typeSel:typeSel,stationcode:stationcode,roadSel:roadSel,phone:phone,
                    city:city,miles:miles,coor_x:coor_x,coor_y:coor_y,nowinwaynum:nowinwaynum,
                    nowexitwaynum:nowexitwaynum,nowinetcnum:nowinetcnum,nowexitetcnum:nowexitetcnum,
                    hub:hub,address:address,nextRoadLeft:nextRoadLeft,nextRoadStraight:nextRoadStraight,nextRoadRight:nextRoadRight,tagAddress:tagAddress,comeRoad:comeRoad,neighborRoad:neighborRoad,viewAndCompapny:viewAndCompapny,direction1:direction1,direction2:direction2
                }, function (data) {
                    if (data.data == true) {
                        //alert('新增成功');
                        closeLayerPageJs();
                    }else{
                        //alert('操作失败');
                        ShowMsg("新增失败:" + data.data);
                    }
                }, "pager");
            }else{//修改
                JAjax("admin/baseData/RoadPOILogic", 'saveRoadPoiMsg', {
                    poiid:poiid,name:name,typeSel:typeSel,stationcode:stationcode,roadSel:roadSel,phone:phone,
                    city:city,miles:miles,coor_x:coor_x,coor_y:coor_y,nowinwaynum:nowinwaynum,
                    nowexitwaynum:nowexitwaynum,nowinetcnum:nowinetcnum,nowexitetcnum:nowexitetcnum,
                    hub:hub,address:address,nextRoadLeft:nextRoadLeft,nextRoadStraight:nextRoadStraight,nextRoadRight:nextRoadRight,tagAddress:tagAddress,comeRoad:comeRoad,neighborRoad:neighborRoad,viewAndCompapny:viewAndCompapny,direction1:direction1,direction2:direction2
                }, function (data) {
                    //alert(data.data);
                    if (data.data == true) {
                        //alert('操作成功');
                        closeLayerPageJs();
                    }else{
                        //alert('操作失败');
                        ShowMsg("操作失败:" + data.data);
                    }
                }, "pager");
            }

            
        }
        
        function closeqw(){
            closeLayerPageJs();
        }


        function blockHere(here){
            var obj = document.getElementById(here);
            obj.style.display='';
        }

        function noneHere(here){
            var obj = document.getElementById(here);
            obj.style.display='none';
        }

        function changeType(value){
            if (value == '1002003') {//互通立交
                blockHere('hub');
                noneHere('waynum');
                noneHere('etcnum');

                //blockHere('milesAndLL');
                noneHere('nextRoad');
                blockHere('directionTr');
            }else{
                blockHere('waynum');
                blockHere('etcnum');
                noneHere('hub');

                blockHere('nextRoad');
                //noneHere('milesAndLL');
                noneHere('directionTr');
            }
        }

    </script>
    <style type="text/css">
        .panel-body{padding: 10px;overflow: display !important;margin-right: 10px;}
        #upicon{cursor:pointer ;}
        .content{color:#0000FF;}
        table{border-collapse: collapse;}
        .vc_table .name{width: 80px !important;}
        .vc_table{padding-right: 5px;}
        #address{width: 100%;}
        .chosen-container-multi .chosen-choices{min-height: 41px !important;line-height: auto;padding: 2px;height: auto !important;}
        .default{width: 150px !important;}
        .autoheight{height: auto}
        .chosen-results{max-height: 200px !important;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .m-10{margin-right: 10px;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">收费站详细信息</div>
    <div class="panel-body" >
        <table cellspacing="1" cellpadding="4" class="vc_table" id="table">
            <tbody>
                <tr>
                    <td class="name" nowrap="nowrap">
                        名称:
                    </td>
                    <td class="content" colspan="5">
                        <input type="text" class="form-control" id="name" placeholder="<?php if(isset($data[0]['name'])){echo $data[0]['name'];}?>" value="<?php if(isset($data[0]['name'])){echo $data[0]['name'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        路段归属:
                    </td>
                    <td class="content">
                        <select class="form-control" id="roadSel">
                            <?php foreach($road as $item): ?>
                                <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['shortname'] ?></option>
                            <?php endforeach; ?>
                            
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap">
                        类型:
                    </td>
                    <td class="content">
                        <select class="form-control" id="typeSel" onchange="changeType(this.value)">
                            <?php foreach($type as $item): ?>
                                <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        收费站编号:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="stationcode" placeholder="<?php if(isset($data[0]['stationcode'])){echo $data[0]['stationcode'];}?>" value="<?php if(isset($data[0]['stationcode'])){echo $data[0]['stationcode'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        公里数:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="miles" placeholder="<?php if(isset($data[0]['miles'])){echo $data[0]['miles'];}?>" value="<?php if(isset($data[0]['miles'])){echo $data[0]['miles'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        电话:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="phone" placeholder="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" value="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" />
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
                        经度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="coor_x" placeholder="<?php if(isset($data[0]['coor_x'])){echo $data[0]['coor_x'];}?>" value="<?php if(isset($data[0]['coor_x'])){echo $data[0]['coor_x'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        纬度:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="coor_y" placeholder="<?php if(isset($data[0]['coor_y'])){echo $data[0]['coor_y'];}?>" value="<?php if(isset($data[0]['coor_y'])){echo $data[0]['coor_y'];}?>" />
                    </td>
                    <!-- <td class="name" nowrap="nowrap" >
                        可用状态:
                    </td>
                    <td class="content">
                        <select class="form-control" id="statusSel">
                            <?php foreach($status as $item): ?>
                                <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td> -->
                </tr>
                <tr id="waynum">
                    <td class="name" nowrap="nowrap">
                        入口车道数:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="nowinwaynum" placeholder="<?php if(isset($data[0]['nowinwaynum'])){echo $data[0]['nowinwaynum'];}?>" value="<?php if(isset($data[0]['nowinwaynum'])){echo $data[0]['nowinwaynum'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        出口车道数:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="nowexitwaynum" placeholder="<?php if(isset($data[0]['nowexitwaynum'])){echo $data[0]['nowexitwaynum'];}?>" value="<?php if(isset($data[0]['nowexitwaynum'])){echo $data[0]['nowexitwaynum'];}?>" />
                    </td>
                </tr>
                
                <tr id="etcnum">
                    <td class="name" nowrap="nowrap">
                        入口ETC车道数:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="nowinetcnum" placeholder="<?php if(isset($data[0]['nowinetcnum'])){echo $data[0]['nowinetcnum'];}?>" value="<?php if(isset($data[0]['nowinetcnum'])){echo $data[0]['nowinetcnum'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        出口ETC车道数:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="nowexitetcnum" placeholder="<?php if(isset($data[0]['nowexitetcnum'])){echo $data[0]['nowexitetcnum'];}?>" value="<?php if(isset($data[0]['nowexitetcnum'])){echo $data[0]['nowexitetcnum'];}?>" />
                    </td>
                </tr>
                <tr id="nextRoad">
                    <td class="name" nowrap="nowrap">
                        下道左转:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="nextRoadLeft" placeholder="<?php if(isset($data[0]['leadleft'])){echo $data[0]['leadleft'];}?>" value="<?php if(isset($data[0]['leadleft'])){echo $data[0]['leadleft'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        下道直行:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="nextRoadStraight" placeholder="<?php if(isset($data[0]['leadcenter'])){echo $data[0]['leadcenter'];}?>" value="<?php if(isset($data[0]['leadcenter'])){echo $data[0]['leadcenter'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        下道右转:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="nextRoadRight" placeholder="<?php if(isset($data[0]['leadright'])){echo $data[0]['leadright'];}?>" value="<?php if(isset($data[0]['leadright'])){echo $data[0]['leadright'];}?>" />
                    </td>
                </tr>
                <tr id="directionTr">
                    <td class="name" nowrap="nowrap">
                        正方向:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="direction1" placeholder="<?php if(isset($data[0]['direction1'])){echo $data[0]['direction1'];}?>" value="<?php if(isset($data[0]['direction1'])){echo $data[0]['direction1'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        反方向:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="direction2" placeholder="<?php if(isset($data[0]['direction2'])){echo $data[0]['direction2'];}?>" value="<?php if(isset($data[0]['direction2'])){echo $data[0]['direction2'];}?>" />
                    </td>
                </tr>
                <!-- <tr>
                    <td class="name" nowrap="nowrap">
                        标识地点:
                    </td>
                    <td class="content" colspan="5">
                        <input type="text" class="form-control" id="tagAddress" placeholder="<?php if(isset($data[0]['signplace'])){echo $data[0]['signplace'];}?>" value="<?php if(isset($data[0]['signplace'])){echo $data[0]['signplace'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        可到达道路:
                    </td>
                    <td class="content" colspan="5">
                        <input type="text" class="form-control" id="comeRoad" placeholder="<?php if(isset($data[0]['avrride'])){echo $data[0]['avrride'];}?>" value="<?php if(isset($data[0]['avrride'])){echo $data[0]['avrride'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        相邻道路:
                    </td>
                    <td class="content" colspan="5">
                        <input type="text" class="form-control" id="neighborRoad" placeholder="<?php if(isset($data[0]['nearroad'])){echo $data[0]['nearroad'];}?>" value="<?php if(isset($data[0]['nearroad'])){echo $data[0]['nearroad'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">
                        景区,企业,单位:
                    </td>
                    <td class="content" colspan="5">
                        <input type="text" class="form-control" id="viewAndCompapny" placeholder="<?php if(isset($data[0]['scenery'])){echo $data[0]['scenery'];}?>" value="<?php if(isset($data[0]['scenery'])){echo $data[0]['scenery'];}?>" />
                    </td>
                </tr> -->
                <tr id="hub" style="display: none;min-height: 61px;height: auto;">
                    <td class="name" nowrap="nowrap">
                        关联路段:
                    </td>
                    <td class="content" colspan="5">
                        <select class="form-control chosen-select autoheight procon" id="hubSel" multiple data-placeholder="请选择关联路段" >
                            <?php foreach($hub as $item): ?>
                                <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadName'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        详细地址:
                    </td>
                    <td class="content" colspan="5">  
                        <input type="text" class="form-control" id="address" placeholder="<?php if(isset($data[0]['address'])){echo $data[0]['address'];}?>" value="<?php if(isset($data[0]['address'])){echo $data[0]['address'];}?>" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <input type="button" value="确定" class="btn btn-info m-10" onclick="submit();" />
        <input type="button" value="返回" class="btn btn-danger" onclick="closeqw();" />
    </div>
 </div>
</body>
</html>