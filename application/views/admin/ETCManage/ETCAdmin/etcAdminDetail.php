<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript">
        //var poiid = '<?php if(isset($poiid)){echo $poiid;}?>';
        var poiid = '<?php echo $id ?>';
        //设置下拉框默认值为站点属性值
//        $().ready(function(){
//            //alert(poiid);
//            if (poiid != 0) {
//                var roadId = '<?php //if(isset($data[0]['roadoldid'])){echo $data[0]['roadoldid'];}?>//';
//                var styleCode = '<?php //if(isset($data[0]['pointtype'])){echo $data[0]['pointtype'];}?>//';
//                //var roadId = '<?php //echo $data[0]['roadoldid'] ?>//';
//                //var styleCode = '<?php //echo $data[0]['pointtype'] ?>//';
//
//                var roadSelect = document.getElementById('roadSel');
//                var typeSelect = document.getElementById('typeSel');
//                var roadOption = roadSelect.getElementsByTagName('option');
//                for (var i=0; i<roadOption.length; i++) {
//                    if (roadOption[i].value == roadId) {
//                        roadOption[i].selected=true;
//                        break;
//                    }
//                }
//
//                var typeOption = typeSelect.getElementsByTagName('option');
//                for (var i=0; i<typeOption.length; i++) {
//                    if (typeOption[i].value == styleCode) {
//                        typeOption[i].selected=true;
//                        break;
//                    }
//                }
//            }

//            if(styleCode == '1002003'){
//                blockHere('hub');
//                noneHere('waynum');
//                noneHere('etcnum');
//            }else{
//                blockHere('waynum');
//                blockHere('etcnum');
//                noneHere('hub');
//            }

            

            //设置多选框的默认选中
//            var hubString = '<?php //if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>//';
//            var hubArray = hubString.split(',');
//            //alert(hubArray);
//            for (var i=0; i<hubArray.length; i++) {
//                $("#hubSel").find("option[value='"+hubArray[i]+"']").attr("selected",true);
//            }
//            //$("#hubSel").find("option[value='4']").attr("selected",true);
//            jQuery('.chosen-select').chosen({'width':'100%','white-space':'nowrap'});
//        });



        /**
         * @desc   保存站点信息
         * @data   2015-9-17 17:05:55
         * @return {[type]}    [description]
         */
        function submit(){
//            var name = $('#name').val();
//            var typeSel = $('#typeSel').val();
//            var stationcode = $('#stationcode').val();
//            var roadSel = $('#roadSel').val();
//            var phone = $('#phone').val();
//            var city = $('#city').val();
//            var miles = $('#miles').val();
//            var coor_x = $('#coor_x').val();
//            var coor_y = $('#coor_y').val();
//            var nowinwaynum = $('#nowinwaynum').val();
//            var nowexitwaynum = $('#nowexitwaynum').val();
//            var nowinetcnum = $('#nowinetcnum').val();
//            var nowexitetcnum = $('#nowexitetcnum').val();
//
//            var hub = $('#hubSel').val();
//            //alert(hub);exit;
//            var address = $('#address').val();
//
//            if (name == '') {alert('站点名称不能为空');return;}
//            if (typeSel == '') {alert('获取站点类型出错');return;}
//            if (stationcode == '') {alert('站点编号不能为空');return;}
//            if (roadSel == '') {alert('获取所属路段出错');return;}
            var content = getInputValue();
            if (poiid == 0) {//新增
                JAjax("admin/ETCManage/ETCAdminLogic", 'saveNewRoadPoiMsg', {content:content}, function (data) {
                    if (data.data == true) {
                        alert('新增成功');closeLayerPageJs();
                    }else{
                        //alert('操作失败');
                        ShowMsg("新增失败:" + data.data);
                    }
                }, "pager");
            }else{//修改
                JAjax("admin/ETCManage/ETCAdminLogic", 'saveRoadPoiMsg', {id:poiid,content:content}, function (data) {
                    //alert(data.data);
                    if (data.data == true) {
                        alert('操作成功');closeLayerPageJs();
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
            //obj.style.display='block';
            //obj.style.display='table−row';
            obj.style.display='';
        }

        function noneHere(here){
            var obj = document.getElementById(here);
            obj.style.display='none';
        }

        function changeType(value){
            if (value == '1002003') {
                blockHere('hub');
                noneHere('waynum');
                noneHere('etcnum');
            }else{
                blockHere('waynum');
                blockHere('etcnum');
                noneHere('hub');
            }
        }

        /**
         * 获取指定表单的值
         * @return array 以name值为键值的关联数组
         */
        function getInputValue(){
            var inputType = ['text','password','hidden','textarea','checkbox','radio','select','file'];
            var content = new Object();
            for(var i=0;i<inputType.length;i++){
                if(inputType[i]=='textarea'){
                    $("textarea").each(function(){
                        var key = $(this).attr('name');
                        content[key] = $(this).val(); //这里的value就是每一个textarea的value值~
                    });
                }else if(inputType[i]=='select'){
                    $("select").each(function(){
                        var key = $(this).attr('name');
                        content[key] = $(this).val(); //这里的value就是每一个select的value值~
                    });
                }else{
                    $("input[type='"+inputType[i]+"']").each(function(){
                        var key = $(this).attr('name');
                        content[key] = $(this).val(); //这里的value就是每一个input的value值~
                    });
                }
            }
            return content;
        }
    </script>
    <style type="text/css">
        .panel-body{
            padding: 10px;
            overflow: display !important;
            margin-right: 10px;
        }
        #upicon{
            cursor:pointer ;
        }
        .content{
            color:#0000FF;
        }
        table{
            border-collapse: collapse;
        }
        .vc_table .name{
            width: 80px !important;
        }
        .vc_table{padding-right: 5px;}
        #address{
            width: 100%;
        }
        .btn{float: right;}
        .chosen-container-multi .chosen-choices{min-height: 41px !important;line-height: auto;padding: 2px;height: auto !important;}
        .default{width: 150px !important;}
        .autoheight{height: auto}
        .chosen-results{
            max-height: 200px !important;
        }
        .panel-heading{color: #FF634D !important;font-size: 18px;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">ETC用户详细信息</div>
    <div class="panel-body" >
        <table cellspacing="1" cellpadding="4" class="vc_table" id="table">
            <tbody>
                <tr>
                    <td class="name" nowrap="nowrap">
                        单位名称:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" name="username" id="username" placeholder="<?php if(isset($data[0]['username'])){echo $data[0]['username'];}?>" value="<?php if(isset($data[0]['username'])){echo $data[0]['username'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap">
                        名称:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" name="relateman" id="relateman" placeholder="<?php if(isset($data[0]['relateman'])){echo $data[0]['relateman'];}?>" value="<?php if(isset($data[0]['relateman'])){echo $data[0]['relateman'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        手机号:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" value="<?php if(isset($data[0]['phone'])){echo $data[0]['phone'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        车牌号:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" name="numberplate" id="numberplate" placeholder="<?php if(isset($data[0]['numberplate'])){echo $data[0]['numberplate'];}?>" value="<?php if(isset($data[0]['numberplate'])){echo $data[0]['numberplate'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        用户编码:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" name="usercode" id="usercode" placeholder="<?php if(isset($data[0]['usercode'])){echo $data[0]['usercode'];}?>" value="<?php if(isset($data[0]['usercode'])){echo $data[0]['usercode'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        卡号:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" name="cardid" id="cardid" placeholder="<?php if(isset($data[0]['cardid'])){echo $data[0]['cardid'];}?>" value="<?php if(isset($data[0]['cardid'])){echo $data[0]['cardid'];}?>" />
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        车型:
                    </td>
                    <td class="content">
                        <select class="form-control" id="vehtype" name="vehtype">
                            <option value="1">一型客</option>
                            <option value="2">二型客</option>
                            <option value="3">三型客</option>
                            <option value="4">四型客</option>
                            <option value="15">计重车</option>
                        </select>
                        <script type="text/javascript">
                            $("#vehtype").find("option[value='<?php echo isset($data[0]['vehtype'])?$data[0]['vehtype']:"" ?>']").attr("selected",true);
                        </script>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        车牌颜色:
                    </td>
                    <td class="content">
                        <select class="form-control" id="platecolor" name="platecolor">
                            <option value="0">蓝色</option>
                            <option value="1">黄色</option>
                            <option value="2">黑色</option>
                            <option value="3">白色</option>
                        </select>
                        <script type="text/javascript">
                            $("#platecolor").find("option[value='<?php echo isset($data[0]['platecolor'])?$data[0]['platecolor']:"" ?>']").attr("selected",true);
                        </script>
                    </td>
                </tr>
            <tr>
                <?php if(!empty($id)){?>
                    <td class="name" nowrap="nowrap" >
                        录入人:
                    </td>
                    <td class="content">
                        <input type="text" disabled class="form-control" name="operatorname" id="operatorname" placeholder="<?php if(isset($data[0]['operatorname'])){echo $data[0]['operatorname'];}?>" value="<?php if(isset($data[0]['operatorname'])){echo $data[0]['operatorname'];}?>" />
                    </td>
                    <td class="name" nowrap="nowrap" >
                        录入时间:
                    </td>
                    <td class="content">
                        <input type="text" disabled class="form-control" name="intime" id="intime" placeholder="<?php if(isset($data[0]['intime'])){echo $data[0]['intime'];}?>" value="<?php if(isset($data[0]['intime'])){echo $data[0]['intime'];}?>" />
                    </td>
                <?php }?>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <input type="button" value="返回" class="btn btn-danger" style="margin-right: 5px;" onclick="closeqw();" />
        <input type="button" value="确定" class="btn btn-info" style="margin-right: 15px;" onclick="submit();" />
    </div>
 </div>
</body>
</html>