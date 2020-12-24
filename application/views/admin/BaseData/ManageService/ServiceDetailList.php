<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
    <style type="text/css">
        .panel-body{padding: 0;}
        #upicon{cursor:pointer ;}
        
        .form-control{float: left;}
        .margin-l{margin-left: 15px;}
        .button-pic{float: left;height: 20px;}
        #uploader-demo .button-pic{float: right;}
        .panel-heading{color: #FF634D !important;font-size: 22px;}
        /********************表格样式********************/
        table{border-collapse: collapse;}
        .vc_table tbody tr td{float: left;}
        .vc_table .name{width: 130px !important;line-height: 40px;}
        .vc_table .content{width: 240px !important;}
        .content{line-height: 41px;}

        #oilTable,#detailTable{margin: 10px;width: 99%;}
        /* #gasTable,#featureTable{margin: 0px 15px 30px 15px;} */
        /********************菜单切换********************/
        .heading-ul{width: 100%;height: 35px;line-height: 35px;padding: 0;}
        .heading-li{width: 12.5%;height: 35px;float: left;list-style: none;margin: 0 auto;border-radius: 3px;text-align: center;}
        .li-hover:hover{background-color: #D1DEF0;}
        .li-color{background-color: #428BCA;}
        .li-color a{color: white;}
        .heading-a{font-size: 20px;display: block;}
        .dis-none{display: none;}

        .menu-li{width: 100%;height: 100%;margin: 10px;margin-bottom: 0;}
        #shopDiv,#specialDiv,#foodDiv,#gasDiv,#parkingDiv,#repairDiv,#toiletDiv,#hotelDiv{
            margin: 10px;margin-top: 0px;
        }
        .imageslist{max-width: 60px;max-height: 60px;}
        .table-responsive{padding-right: 30px; overflow-x:none;}
    </style>
    <script type="text/javascript">
        //页面初始数据
        var poiid = '<?php echo isset($poiid)?$poiid:''; ?>';
        var poiname = '<?php echo isset($poiname)?$poiname:''; ?>';
        var roadoldid = '<?php echo isset($data["roadoldid"])?$data["roadoldid"]:''; ?>';
        var jpgurl = '<?php echo isset($data["pic"])?$data["pic"]:''; ?>';
        var level = '<?php echo isset($data["level"])?$data["level"]:'1'; ?>';

        var hasTypeArr = ['hasShop','hasSpecial','hasFood','hasGas','hasParking','hasRepair','hasToilet','hasHotel','hasSpeciallist','haswifi','hasrescue','haschargingpile','hasqizhan'];
        var startType = new Array(9);
        startType[0] = '<?php echo !empty($data["hasshop"])?$data["hasshop"]:'0'; ?>';
        startType[1] = '<?php echo !empty($data["hasspecial"])?$data["hasspecial"]:'0'; ?>';
        startType[2] = '<?php echo !empty($data["hasfood"])?$data["hasfood"]:'0'; ?>';
        startType[3] = '<?php echo !empty($data["hasgasstation"])?$data["hasgasstation"]:'0'; ?>';
        startType[4] = '<?php echo !empty($data["hasparking"])?$data["hasparking"]:'0'; ?>';
        startType[5] = '<?php echo !empty($data["hasrepair"])?$data["hasrepair"]:'0'; ?>';
        startType[6] = '<?php echo !empty($data["hastoilet"])?$data["hastoilet"]:'0'; ?>';
        startType[7] = '<?php echo !empty($data["hashotel"])?$data["hashotel"]:'0'; ?>';
        startType[8] = '<?php echo !empty($data["hasspeciallist"])?$data["hasspeciallist"]:'0'; ?>';//特色服务

        startType[9] = '<?php echo !empty($data["haswifi"])?$data["haswifi"]:'0'; ?>';//是否有wifi
        startType[10] = '<?php echo !empty($data["hasrescue"])?$data["hasrescue"]:'0'; ?>';//是否有救援
        startType[11] = '<?php echo !empty($data["haschargingpile"])?$data["haschargingpile"]:'0'; ?>';//是否有充电桩
        startType[12] = '<?php echo !empty($data["hasqizhan"])?$data["hasqizhan"]:'0'; ?>';//是否有加气站
        //服务区状态
        //var serviceStatus = '<?php echo isset($data["servicestatus"])?$data["servicestatus"]:''; ?>';

        //初始分页数
        var page = 1;

        //获取当前域名
        var base_url = '<?php $this->load->helper("url");echo base_url() ?>';
        
        $().ready(function(){
            //显示默认服务区图片
            if(jpgurl != ''){
                $("#imgupload").html("<img src="+jpgurl+" id='imgurl' width='200px' onclick= 'showLayerImageJs(this.src)'/>");
            }

            $('#level').find('option[value='+level+']').attr('selected',true);

            //设置服务区构成初始状态
            setSelectStartType(startType);
            //设置服务区状态初始状态
            var serviceStatus = '<?php echo isset($data["servicestatus"])?$data["servicestatus"]:''; ?>';
            //alert(serviceStatus);
            $('#serviceStatusSel').find("option[value='"+serviceStatus+"']").attr('selected',true);

            //获取油类和服务区特色表格数据
            onLoadGasAndFeatureMsg();

            //textarea文本输入框
            UE.getEditor('shopHtml');
            UE.getEditor('specialHtml');
            UE.getEditor('foodHtml');
            UE.getEditor('gasHtml');
            UE.getEditor('parkingHtml');
            UE.getEditor('repairHtml');
            UE.getEditor('toiletHtml');
            UE.getEditor('hotelHtml');

            //页面默认展示商店HTML
            controlLiAndDiv('shopLi','shopDiv');
        });


        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        /**
         * @desc  设置服务区构成下拉框的初始状态
         */
        function setSelectStartType(typeArray){
            $.each(typeArray,function(n,value){
                if (trimStr(value) != '') {
                    $('#'+hasTypeArr[n]).find('option[value="'+value+'"]').attr('selected',true);
                }
            });
        }

        /**
         * @desc   展示油类表格内容和服务区特色表格内容
         */
        function onLoadGasAndFeatureMsg(){
            if (trimStr(poiid) == '') {
                ShowMsg('错误的服务区ID!');
                return;
            }
            JAjax("admin/baseData/ServiceLogic", 'getGasAndFeatureAndImagesMsg', {poiid:poiid}, function (data) {
                var gasArr = data.data.gas;
                var featureArr = data.data.feature;
                var imagesArr = data.data.images;
                //alert(featureArr);
                ReloadTb('gasTable', gasArr);
                ReloadTb('featureTable', featureArr);
                ReloadTb('imagesTable', imagesArr);
            }, "pager");
        }


        //新增油类
        function addGas(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/ServiceLogic/operateGasMsg?poiid='); ?>"+poiid+'&id=0', '新增', 550, 350, onLoadGasAndFeatureMsg);
        }

        function checkGas(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/ServiceLogic/operateGasMsg?poiid='); ?>"+poiid+'&id='+id, '查看', 550, 350, onLoadGasAndFeatureMsg);
        }

        function deleteGas(){
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getCheckedValues("gasTable", "#gasTable",'string');
            //alert(values);return;
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/ServiceLogic", "deleteGasMsg", { value: values}, function (data) {
                        if (data.Success)
                            onLoadGasAndFeatureMsg();
                        else
                            ShowMsg("删除失败:" + data.Message);
                    }, "pager");
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        //新增服务区特色
        function addFeature(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/ServiceLogic/operateFeatureMsg?poiid='); ?>"+poiid+'&eventid=0', '新增', 820, 450, onLoadGasAndFeatureMsg);
        }

        //查看服务区特色
        function checkFeature(id){
            //alert(id);
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/ServiceLogic/operateFeatureMsg?poiid='); ?>"+poiid+'&eventid='+id, '查看', 820, 450, onLoadGasAndFeatureMsg);
        }

        function deleteFeature(){
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getCheckedValues("featureTable", "#featureTable",'string');
            //alert(values);return;
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/ServiceLogic", "deleteFeatureMsg", { value: values}, function (data) {
                        if (data.Success)
                            onLoadGasAndFeatureMsg();
                        else
                            ShowMsg("删除失败:" + data.Message);
                    }, "pager");
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        //新增图集
        function addImages(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/ServiceLogic/operateImagesMsg?poiid='); ?>"+poiid+'&id=0', '新增', 700, 450, onLoadGasAndFeatureMsg);
        }

        function deleteImages(){
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getCheckedValues("imagesTable", "#imagesTable",'string');
            //alert(values);return;
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/ServiceLogic", "deleteImagesMsg", { value: values}, function (data) {
                        if (data.Success)
                            onLoadGasAndFeatureMsg();
                        else
                            ShowMsg("删除失败:" + data.Message);
                    }, "pager");
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        function cancelPush(id){
            ShowConfirm("您确定要取消发布吗？", function () {
                JAjax("admin/baseData/ServiceLogic", "cancelPush", {id:id}, function (data) {
                    if (data.Success)
                        onLoadGasAndFeatureMsg();
                    else
                        ShowMsg("失败" + data.Message);
                }, null);
            });
        }


        


        /**
         * @desc   处理'服务区详细介绍'->底部HTML介绍菜单的点击事件,控制li和div的效果
         * @param  string    liId  点击的li的id
         */
        function controlLiAndDiv(liId){
            var liArr = ['shopLi','specialLi','foodLi','gasLi','parkingLi','repairLi','toiletLi','hotelLi'];
            var divArr = ['shopDiv','specialDiv','foodDiv','gasDiv','parkingDiv','repairDiv','toiletDiv','hotelDiv'];

            $.each(liArr,function(n,value){
                if (value == liId) {//要显示的div
                    $('#'+value).addClass('li-color');
                    $('#'+value).removeClass('li-hover');
                    $('#'+divArr[n]).removeClass('dis-none');
                }else{
                    $('#'+value).removeClass('li-color');
                    $('#'+value).addClass('li-hover');
                    $('#'+divArr[n]).addClass('dis-none');
                }
            });
        }

      
        function submitDetail(){
            //var imgUrl = $('#imgurl').attr('src');
            var imgUrl = jpgurl;
            var level = $('#level').val();
            var hasShop = $('#hasShop').val();
            var hasSpecial = $('#hasSpecial').val();
            var hasFood = $('#hasFood').val();
            var hasGas = $('#hasGas').val();
            var hasParking = $('#hasParking').val();
            var hasRepair = $('#hasRepair').val();
            var hasToilet = $('#hasToilet').val();
            var hasHotel = $('#hasHotel').val();

            var haswifi = $('#haswifi').val();
            var hasrescue = $('#hasrescue').val();
            var haschargingpile = $('#haschargingpile').val();
            var hasqizhan = $('#hasqizhan').val();
            var chargingpilenum = $('#chargingpilenum').val();
            var parkingspacenum = $('#parkingspacenum').val();

            var serviceStatusSel = $('#serviceStatusSel').val();
            var hasSpeciallist = $('#hasSpeciallist').val();
            var serviceSummary = $('#serviceSummary').val();
            var shopHtml=UE.getEditor('shopHtml').getContent();
            var specialHtml=UE.getEditor('specialHtml').getContent();
            var foodHtml=UE.getEditor('foodHtml').getContent();
            var gasHtml=UE.getEditor('gasHtml').getContent();
            var parkingHtml=UE.getEditor('parkingHtml').getContent();
            var repairHtml=UE.getEditor('repairHtml').getContent();
            var toiletHtml=UE.getEditor('toiletHtml').getContent();
            var hotelHtml=UE.getEditor('hotelHtml').getContent();

            JAjax("admin/baseData/ServiceLogic", "saveDetailMsg", {poiid:poiid,imgUrl:imgUrl,hasShop:hasShop,hasSpecial:hasSpecial,hasFood:hasFood,hasGas:hasGas,hasParking:hasParking,hasRepair:hasRepair,hasToilet:hasToilet,hasHotel:hasHotel,serviceStatusSel:serviceStatusSel,hasSpeciallist:hasSpeciallist,serviceSummary:serviceSummary,shopHtml:shopHtml,specialHtml:specialHtml,foodHtml:foodHtml,gasHtml:gasHtml,parkingHtml:parkingHtml,repairHtml:repairHtml,toiletHtml:toiletHtml,hotelHtml:hotelHtml,level:level,haswifi:haswifi,hasrescue:hasrescue,haschargingpile:haschargingpile,hasqizhan:hasqizhan,chargingpilenum:chargingpilenum,parkingspacenum:parkingspacenum,poiname:poiname}, function (data) {
                if (data.Success){
                    /*var src = "<?php echo base_url('/index.php/admin/baseData/ServiceLogic/indexPage'); ?>";
                    $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);*/
                    closeLayerPageJs();
                }else{
                    ShowMsg("操作失败:" + data.Message);
                }
            }, null);
        }


        function cancel(){
            /*var src = "<?php echo base_url('/index.php/admin/baseData/ServiceLogic/indexPage'); ?>";
            $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);*/
            closeLayerPageJs();
        }

    </script>
    
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">
        <div class="form-inline mb10">
            <div class="form-group" style="margin-left: 0;margin-top: 10px;">
                <?php echo isset($data['name'])?$data['name']:''; ?>服务区信息（首页图文展示、服务信息）
            </div>
        </div>
    </div>
    <div class="panel-body ">
        <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%;margin-top: 20px;">
            <tbody>
                <tr>
                    <td class="name" nowrap="nowrap" style="line-height: 21px !important;"><p>服务区图片:
                        <div id="uploader-demo">
                            <!--用来存放item-->
                            <div id="fileList" class="uploader-list"></div>
                            <div id="filePicker" class="button-pic" style="margin-bottom: 15px;">选择</div>
                        </div></p>
                    </td>
                    <td class="content" colspan="3">
                        <div id="imgupload"  width="100%"></div>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">服务区构成:
                    </td>
                    <td class="content" colspan="3">
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap">服务区星级:
                    </td>
                    <td class="content" colspan="3">
                        <!-- <input type="text" id="level" class="form-control" value="<?php echo isset($data['level'])?$data['level']:'' ?>" placeholder="<?php echo isset($data['level'])?$data['level']:'' ?>" /> -->
                        <select class="form-control" id="level" >
                            <option value="1">★</option>
                            <option value="2">★★</option>
                            <option value="3">★★★</option>
                            <option value="4">★★★★</option>
                            <option value="5">★★★★★</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        是否有商店:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasShop">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        是否有特产:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasSpecial">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        是否有美食:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasFood">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        是否有加油站:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasGas">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        是否有停车场:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasParking">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        是否有维修站:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasRepair">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        是否有厕所:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasToilet">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        是否有住宿:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasHotel">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        是否有wifi:
                    </td>
                    <td class="content">
                        <select class="form-control" id="haswifi">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        是否有加气站:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasqizhan">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        是否有救援:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasrescue">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        是否有充电桩:
                    </td>
                    <td class="content">
                        <select class="form-control" id="haschargingpile">
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        服务区状态:
                    </td>
                    <td class="content">
                        <select class="form-control" id="serviceStatusSel">
                            <!-- <?php foreach($status as $item): ?>
                                <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                            <?php endforeach; ?> -->
                            <option value="1">可用</option>
                            <option value="0">不可用</option>
                        </select>
                    </td>
                    <td class="name" nowrap="nowrap" >
                        是否有特色服务:
                    </td>
                    <td class="content">
                        <select class="form-control" id="hasSpeciallist">
                            <option value="1">显示</option>
                            <option value="0">不显示</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="name" nowrap="nowrap" >
                        充电桩数量:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="chargingpilenum" value="<?php echo empty($data['chargingpilenum'])?0:$data['chargingpilenum'] ?>">
                    </td>
                    <td class="name" nowrap="nowrap" >
                        停车位数量:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="parkingspacenum" value="<?php echo empty($data['parkingspacenum'])?0:$data['parkingspacenum'] ?>">
                    </td>
                </tr>


                <tr>
                    <td class="name" nowrap="nowrap">服务区简介:
                    </td>
                    <td class="content" colspan="3"><!--横向最多5个td-->
                        <textarea cols="110" rows="3" id="serviceSummary"><?php echo isset($data['content'])?$data['content']:''; ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<!-- </tbody>
        </table> -->
    <div class="panel-body">
        <!--油类表格-->
        <div class="form-inline mb10" style="padding: 0px !important;">油类:
            <input type="button" value="添加" class="btn btn-primary margin-l" onclick="addGas()" style="" />
            <input type="button" value="删除" class="btn btn-danger margin-l" onclick="deleteGas()" style="" />
        </div>
        <div class="table-responsive">
            <table class="table mb30 table-hover table-bordered dataTable" id="gasTable">
                <thead>
                    <tr>
                        <th class="title"  width="30px" itemvalue="id" showtype="checkbox" attr="name='gasTable' href='javascript:void(0)' ">
                            <input type="checkbox" id="chkall" onclick="checkall('#gasTable', this, 'gasTable');">
                            <!--InPage.js-->
                        </th>
                        <th class="title" width="120px" itemvalue="gasname" center="true" >油类</th>
                        <th class="title" width="120px" itemvalue="price" center="true">价格</th>
                        <th class="title" width="150px" itemvalue="status" center="true">状态</th>
                        <th class="title" width="" itemvalue="operate" center="true">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据 -->
                </tbody>
            </table>
        </div>
        <!--********************************************************************************-->
        <!--服务区特色表格-->
        <div class="form-inline mb10" style="padding: 0px !important;">服务区特色:
            <input type="button" value="添加" class="btn btn-primary margin-l" onclick="addFeature();" />
            <input type="button" value="删除" class="btn btn-danger margin-l" onclick="deleteFeature();" />
        </div>
        <div class="table-responsive">
            <table class="table mb30 table-hover table-bordered dataTable" id="featureTable">
                <thead>
                    <tr>
                        <th class="title"  width="30px" itemvalue="id" showtype="checkbox" attr="name='featureTable' href='javascript:void(0)' ">
                            <input type="checkbox" id="chkall" onclick="checkall('#featureTable', this, 'featureTable');">
                            <!--InPage.js-->
                        </th>
                        <th class="title" width="80px" itemvalue="jpgimages" center="true" >图片</th>
                        <th class="title" width="80px" itemvalue="seq" center="true" >序号</th>
                        <th class="title" width="" itemvalue="title" center="true">标题</th>
                        <th class="title" width="150px" itemvalue="operate" center="true">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据 -->
                </tbody>
            </table>
        </div>
        <!--********************************************************************************-->
        <!--服务区图集表格-->
        <div class="form-inline mb10" style="padding: 0px !important;">服务区图集:
            <input type="button" value="添加" class="btn btn-primary margin-l" onclick="addImages();" />
            <input type="button" value="删除" class="btn btn-danger margin-l" onclick="deleteImages();" />
        </div>
        <div class="table-responsive">
            <table class="table mb30 table-hover table-bordered dataTable" id="imagesTable">
                <thead>
                    <tr>
                        <th class="title"  width="30px" itemvalue="id" showtype="checkbox" attr="name='imagesTable' href='javascript:void(0)' ">
                            <input type="checkbox" id="chkall" onclick="checkall('#imagesTable', this, 'imagesTable');">
                            <!--InPage.js-->
                        </th>
                        <th class="title" width="80px" itemvalue="picture" center="true" >图片</th>
                        <th class="title" width="" itemvalue="name" center="true">服务区</th>
                        <!-- <th class="title" width="150px" itemvalue="operate" center="true">操作</th> -->
                    </tr>
                </thead>
                <tbody>
                    <!-- 数据 -->
                </tbody>
            </table>
        </div>

        <div class="form-inline mb10" style="padding: 15px;">
            <ul class="heading-ul">
                <li class="heading-li" id="shopLi">
                    <a onclick="controlLiAndDiv('shopLi');" class="heading-a">商店介绍</a>
                </li>
                <li class="heading-li" id="specialLi">
                    <a onclick="controlLiAndDiv('specialLi');" class="heading-a">特产介绍</a>
                </li>
                <li class="heading-li" id="foodLi">
                    <a onclick="controlLiAndDiv('foodLi');" class="heading-a">美食介绍</a>
                </li>
                <li class="heading-li" id="gasLi">
                    <a onclick="controlLiAndDiv('gasLi');" class="heading-a">加油站介绍</a>
                </li>
                <li class="heading-li" id="parkingLi">
                    <a onclick="controlLiAndDiv('parkingLi');" class="heading-a">停车场介绍</a>
                </li>
                <li class="heading-li" id="repairLi">
                    <a onclick="controlLiAndDiv('repairLi');" class="heading-a">维修站介绍</a>
                </li>
                <li class="heading-li" id="toiletLi">
                    <a onclick="controlLiAndDiv('toiletLi');" class="heading-a">洗手间介绍</a>
                </li>
                <li class="heading-li" id="hotelLi">
                    <a onclick="controlLiAndDiv('hotelLi');" class="heading-a">住宿介绍</a>
                </li>
            </ul>
        </div>
        <div class="form-inline mb10">
            <div id="shopDiv">
                <textarea style="height:300px;width:100%" id="shopHtml"><?php echo isset($data['shoptext'])?$data['shoptext']:''; ?></textarea>
            </div>
            <div id="specialDiv" class="dis-none">
                <textarea style="height:300px;width:100%" id="specialHtml"><?php echo isset($data['specialtext'])?$data['specialtext']:''; ?></textarea>
            </div>
            <div id="foodDiv" class="dis-none">
                <textarea style="height:300px;width:100%" id="foodHtml"><?php echo isset($data['foodtext'])?$data['foodtext']:''; ?></textarea>
            </div>
            <div id="gasDiv" class="dis-none">
                <textarea style="height:300px;width:100%" id="gasHtml"><?php echo isset($data['gastext'])?$data['gastext']:''; ?></textarea>
            </div>
            <div id="parkingDiv" class="dis-none">
                <textarea style="height:300px;width:100%" id="parkingHtml"><?php echo isset($data['parkingtext'])?$data['parkingtext']:''; ?></textarea>
            </div>
            <div id="repairDiv" class="dis-none">
                <textarea style="height:300px;width:100%" id="repairHtml"><?php echo isset($data['repairtext'])?$data['repairtext']:''; ?></textarea>
            </div>
            <div id="toiletDiv" class="dis-none">
                <textarea style="height:300px;width:100%" id="toiletHtml"><?php echo isset($data['toilettext'])?$data['toilettext']:''; ?></textarea>
            </div>
            <div id="hotelDiv" class="dis-none">
                <textarea style="height:300px;width:100%" id="hotelHtml"><?php echo isset($data['hoteltext'])?$data['hoteltext']:''; ?></textarea>
            </div>
        </div>

        
    </div>
    <div class="panel-footer">
        <input type="button" value="确定" class="btn btn-primary margin-l" onclick="submitDetail();" />
        <input type="button" value="返回" class="btn btn-danger margin-l" onclick="cancel();" />
    </div>
</div>
</body>
</html>