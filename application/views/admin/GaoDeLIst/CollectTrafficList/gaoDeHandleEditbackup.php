<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=aaaacfd2066bd9ff25658d55741539ae&plugin=AMap.Geocoder"></script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery.nicescroll.js') ?>">
    </script>
	<style>
        /*上传图片*/
        .panel-div-pic{float: left;width: 1055px;height: 300px;margin: 10px;}
        .webuploader-pick{padding: 0px 7px;height: 30px !important;}
        .webuploader-pick div{height: 30px !important;}
        .webuploader-element-invisible{width: 56px !important;}
        .images-div{width: 100px;height: 100px;margin: 8px 5px;position: relative;}
        .images-img{width: 100px;height: 100px;float: left;}
        .delete-img:hover{src:;}
        .legend-pic{width: 200px;}
        .nav-tabs {background: #1d5a86;}
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus { background-color: #fff; border: 0;border-radius: 0;}
        .nav-tabs > li > a {color: #fff;}
        .sgxxname{
            width: 4%;text-align: right;padding-left: 5px;
        }
        .textwidth{
            min-width:0px;
        }
        .rdolist, .chklist {
            display: none;
        }

        .rdobox, .chkbox {
            display: inline-block;
            padding: 10px;
            height: 35px;
            line-height: 16px;
            background: #eee;
            border-radius: 3px;
            color: #666;
            font-family: 'Microsoft YaHei';
        }
        .table{
            box-shadow: 0 0 0 rgba(12,12,12,0.03);
            -webkit-box-shadow: 0 0 0 rgba(12,12,12,0.03);
            -moz-box-shadow: 0 0 0 rgba(12,12,12,0.03);
        }


        .unchecked {
            background: #ECF0F1;
            color: #666;
            transition-property: background;
            transition-delay: 0s;
            transition-duration: .1s;
            transition-timing-function: linear;
        }

        .unchecked:hover {
            background: #dedfe0;
        }

        .checked {
            background: #3498DB;
            color: #fff;
        }
        .textred{
            color:red;
            font-size:20px;
        }
        .title_text{
            color: #888;
            text-align: center;
        }
        .modal-backdrop {
            opacity: 0 !important;
            filter: alpha(opacity=0) !important;
        }
        #mysltype {
            overflow-y:hidden;
        }
        #sjglupdate tr td{
            vertical-align:middle
        }
        .nav-tabs{
            background:#fff;
        }
        .invalid,#sjglsave,#sjglupdatexx,#cjgd,#cancelhandle{
            display: none;
        }
        .processtitle{
            font-size: 20px;
            margin-bottom: 10px;
            color: #0f70d8;
        }
        .well{
            float: right;
            margin-left: 1%;
            width: 25%;
            color: #ef6d6d;
            font-size: 18px;
            background-color: #6af1ca;
            display: none;
        }
        .del-pic-p{
            position: absolute;
            right: -15px;
            top: -15px;
        }
        .processcontenttitle,.handlecontenttitle{
            border: 1px solid #eee;
            height: 35px;
            line-height: 35px;
            text-align: center;
        }
        .gaodetitle{
            height: 50px;
            line-height: 50px;
            font-size: 20px;
            color: #217BDB;
        }
        #sjglupdatenew{
            border-top:1px solid #000;
        }
        .operator_div{
            text-align: center;
            padding-top: 5%;
        }
        .operatorstatus{
            margin-bottom: 5%;
        }
        #operatorname{}
        .operator_btn{
            margin-top: 10%;
        }
        .onetd{
            border-top:1px solid #0F70D8;
        }
	</style>

</head>
<body marginwidth="0" marginheight="0" style="">
<div class="tab-content mb30">
    <div class="gaodetitle">
        高德路况详情
    </div>
    <div class="tab-pane active" id="nav_tfsjxq" >
        <div>
            <div style="float: left;margin-bottom: 30px;">
                <input id="handle" type="button" value="处理" class="btn btn-primary" onclick="savehandle(1002001)" />
                <input id="cancelhandle" type="button" value="取消处理" class="btn btn-danger" onclick="savehandle(1002002)" />
            </div>
            <div class="well well-lg">
                当前操作人：<span id="operatorname"></span>
            </div>
        </div>
        <div style="margin-bottom: 30px">
            <table class="table mb30 table-bordered " id="sjglupdate">
                <tr>
                    <td style="border-top:2px solid #0F70D8;" width="40%">路段名称：<span id="roadname"></span></td>
                    <td style="border-top:2px solid #0F70D8;" width="40%">发布时间：<span id="inserttime"></span></td>
                    <td style="border-top:2px solid #0F70D8;" rowspan="4">
                        <div class="operator_div">
                                <div class="operatorstatus">处理中</div>
                                <div id="operatorname">处理人：<span id="operatorname"></span></div>
                                <div class="operator_btn">
                                    <input id="handle" type="button" value="取消处理" class="btn btn-danger" onclick="savehandle(1002001)" />
                                </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>起始站点：<span id="startStation"></span></td>
                    <td>结束站点：<span id="endStation"></span></td>
                </tr>
                <tr>
                    <td>开始桩号：<span id="startmile"></span></td>
                    <td>结束桩号：<span id="endmile"></span></td>
                </tr>
                <tr>
                    <td style="text-align: center;" colspan="2"><input id="matchingPoi" type="button" value="匹配收费站" class="btn btn-primary" onclick="matchingPoi()" /></td>
                </tr>
                <tr>
                    <td colspan="3" style="background:#F5FAFF;padding: 0;">
                        <iframe id="lkimg" name="lkimg" frameborder="0" width="100%" marginheight="0"  marginwidth="0" style="height:215px;  padding: 10px 10px 10px 10px; display: block; background: #F5FAFF; "  src="<?php echo $this->config->item('getgdlkimgbyempid').$eventid.'&selecttype='.$selecttype;?>">
                        </iframe>
                    </td>
                </tr>
            </table>
            <table class="table mb30 table-bordered " id="sjglupdate">
                <tr>
                    <td id="sjeventid" width="100px">
                        高德路况
                    </td>
                    <td colspan="7">
                        <iframe id="lkimg" name="lkimg" frameborder="0" width="100%" marginheight="0"  marginwidth="0" style="height:215px;  padding: 10px 10px 10px 10px; display: block; background: rgb(228, 231, 234); "  src="<?php echo $this->config->item('getgdlkimgbyempid').$eventid.'&selecttype='.$selecttype;?>">
                        </iframe>
                    </td>
                </tr>
                <tr>
                    <td>
                        路段名称:
                    </td>
                    <td id="roadname">
                    </td>
                    <td>
                        起始站：
                    </td>
                    <td id="startStation">
                    </td>
                    <td>
                        结束站：
                    </td>
                    <td id="endStation">
                    </td>
                    <td>
                        发布时间：
                    </td>
                    <td  id="inserttime">
                    </td>
                </tr>
                <tr>
                    <td>
                        事件状态:
                    </td>
                    <td>
                        <span id="sjzt" style="color:red"></span>
                    </td>
                    <td>
                        开始桩号:
                    </td>
                    <td id="startmile">
                    </td>
                    <td>
                        结束桩号:
                    </td>
                    <td id="endmile">
                    </td>
                    <td colspan="6">
                        <input id="matchingPoi" type="button" value="匹配收费站" class="btn btn-primary" onclick="matchingPoi()" />
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-bottom: 30px;height: 400px;">
            <div class="row" style="margin-left:0">
                <div class="col-md-6" style="border: 1px solid #ddd;height: 400px;">
                   <div id="container" style="height: 100%;">

                   </div>
                </div>
                <div class="col-md-6">
                    <div style="height: 180px;margin-bottom: 20px;">
                        <div class="processtitle">高德事件进展</div>
                        <div>
                            <div class="col-md-12 processcontenttitle">
                                <div class="col-md-4" style="border-right: 1px solid #eee;">路段名称</div>
                                <div class="col-md-2" style="border-right: 1px solid #eee;">时速</div>
                                <div class="col-md-2" style="border-right: 1px solid #eee;">拥堵距离</div>
                                <div class="col-md-4">更新时间</div>
                            </div>
                            <div id="processcontent" style="clear:both;height: 105px;overflow: hidden;">
                                <div class="col-md-12 processcontenttitle" style="border-top:0px solid">
                                    <img style="width: 20px;" src="<?php $this->load->helper('url');echo base_url('/asset/images/loadgif.gif') ?>">
                                </div>
                            </div>
                            <!-- <table class="table mb30 table-bordered" id="trafficprocess">
                                <thead>
                                    <tr>
                                        <th>路段名称</th>
                                        <th>时速</th>
                                        <th>拥堵距离</th>
                                        <th>更新时间</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="processtr">
                                        <th colspan="4" style="text-align: center;"><img style="width: 20px;" src="<?php $this->load->helper('url');echo base_url('/asset/images/loadgif.gif') ?>"></th>
                                    </tr>
                                </tbody>
                            </table> -->
                        </div>
                    </div>
                    <div>
                        <div class="processtitle">事件操作进展</div>
                        <div>
                            <div class="col-md-12 handlecontenttitle">
                                <div class="col-md-4" style="border-right: 1px solid #eee;">操作人</div>
                                <div class="col-md-2" style="border-right: 1px solid #eee;">事件状态</div>
                                <div class="col-md-2" style="border-right: 1px solid #eee;">操作状态</div>
                                <div class="col-md-4">操作事件</div>
                            </div>
                            <div id="handlecontent" style="clear:both;height: 105px;overflow: hidden;">
                                <div class="col-md-12 handlecontenttitle" style="border-top:0px solid">
                                    <img style="width: 20px;" src="<?php $this->load->helper('url');echo base_url('/asset/images/loadgif.gif') ?>">
                                </div>
                            </div>
                            <!-- <table class="table mb30 table-bordered" id="handleprocess">
                                <tr>
                                    <th>操作人</th>
                                    <th>事件状态</th>
                                    <th>操作状态</th>
                                    <th>操作事件</th>
                                </tr>
                                <tr class="processtr">
                                    <th colspan="4" style="text-align: center;"><img style="width: 20px;" src="<?php $this->load->helper('url');echo base_url('/asset/images/loadgif.gif') ?>"></th>
                                </tr>
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 30px;">
            <div class="title-font" style="border-bottom: 3px solid #0beaea;height: 45px;margin-bottom: 10px">
                关联事件&nbsp;&nbsp;<span style="color:red;display: none" id="cztxfont">(已经操作)</span>
                <input style="float: right;" id="jtkfyjbutton" statusType="0" type="button" value="保 存" onclick="saveHandleResult(this);" class="btn btn-primary qxclass">
            </div>
            <p data-toggle="tooltip" data-placement="right" title="当前系统检测到可能与该报料有关联的突发事件如下：（点击工单编号可以查看对应的事件详情信息）">可能关联事件&nbsp;<img style="width:1.2rem" src="<?php $this->load->helper('url');echo base_url('/asset/images/wenhao.png') ?>"  /></p>

            <!-- 未处理状态的table -->
            <table class="table mb30 table-bordered " id="sjglsave">
                <tr>
                    <td class="form-inline" id="sjeventid" width="150px">
                        <input type="radio" id="cfwx" val="invalid"  name="sjglradio" />触发提醒无效
                    </td>
                    <td colspan="3" >
                        <input id="yycontent" value="经核实，该路段通行正常。" class="form-control" type="text"/>
                    </td>
                </tr>
                <tr class="qxclass">
                    <td>
                        <input type="submit" name="add" value="上传图片"  id="upload1" class="btn btn-primary qxclass"
                               onclick="uploadFile();"/>
                        <input id="picFile" type="file" size="45" style="width:1px; height:1px;"
                               name="picFile" class="input" onchange="readFile(this)">
                    </td>
                    <td>
                        <div id="theImgDiv">
                            <div id="uploader-demo" style="display: none;">
                                <!--用来存放item-->
                                <div id="fileList" class="uploader-list" style="display: none;"></div>
                            </div>
                            <div id="imgupload"  width="100%" style="display: none;"></div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- 已处理状态的table -->
            <table class="table mb30 table-bordered " id="sjglupdatexx">
                <tr class="invalid">
                    <td class="form-inline" id="sjeventid" width="150px">
                        触发提醒无效
                    </td>
                    <td colspan="3" >
                        <input id="clcontent" disabled="true"  class="form-control" type="text"/>
                    </td>
                </tr>
                <tr class="invalid">
                    <td>
                        <input type="submit" name="add" value="上传图片" id="upload2" class="btn btn-primary"
                               onclick="uploadFile2();"/>
                        <input id="picFile2" type="file" size="45" style="width:1px; height:1px;"
                               name="picFile2" class="input" onchange="readFile2(this)">
                    </td>
                   <td id="theImgDiv1" colspan="3">
                        <div id="theImgDiv2">
                            <div id="uploader-demo" style="display: none;">
                                <!--用来存放item-->
                                <div id="fileList" class="uploader-list" style="display: none;"></div>
                            </div>
                            <div id="imgupload"  width="100%" style="display: none;"></div>
                        </div>
                    </td>
                </tr>
            </table>

        </div>

        <div style="margin-top: 30px">
            <div style="text-align: center">
                <input id="fh"  type="button" value="返回" onclick="fh();" class="btn btn-primary">
                <input id="cjgd"  type="button" value="创建突发事件工单" onclick="goaddsj();" class="btn btn-primary qxclass">
            </div>
        </div>
   </div>
</div>
</body>
</html>
<script>
    var hasCheckcg = "<?php echo $hasCheckcg?>";
    var gaodeeventid = "<?php echo empty($eventid)?'':$eventid?>";
    var selecttype = "<?php echo empty($selecttype)?'':$selecttype?>";//区分是当天数据还是历史数据 1 当天 2历史
    var isnew = 0;//是否为已经处理状态 0 是未处理 1是已处理

    var gaodejamDist = "";//拥堵距离
    var gaodejamSpeed = 0;//时速
    var intime = "";//发生时间
    var roadid = "";//路ID
    var pubrunstatus = "";//拥堵状态
    var endStation = "";//结束站
    var startStation = "";//起始站

    var startstationx = "";//起始站纬度
    var startstationy = "";//起始站经度
    var endstationx = "";//终点站纬度
    var endstationy = "";//终点站经度

    var base_url = "<?php echo $this->config->base_url(); ?>";
    var detailData = '';
    var xy = '';
    var longTime = '';
    var roadName = '';

    var deletePicUrl = "<?php $this->load->helper('url');echo base_url('/asset/images/delete.png') ?>";
    function goaddsj(){
        var src = "<?php echo base_url('index.php/admin/UnifiedRelease/InfoReleaseLogic/showDetailMsg') ?>?isnew="+isnew+"&roadcolor="+pubrunstatus+"&roadid="+roadid+"&endStation="+endStation+"&startStation="+startStation+"&intime="+intime+"&gaodejamSpeed="+gaodejamSpeed+"&gaodejamDist="+gaodejamDist+"&gaodeeventid="+gaodeeventid;
        $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
    }


    function fh(){
        var src = "<?php echo base_url('index.php/admin/GaoDeLIst/GaoDeListLogic/gaoDeIndexPage') ?>";
        $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
    }

    $(function(){
        if(hasCheckcg==0){
            $("#jtkfyjbutton").hide();
            $("#upload1").hide();
            $("#upload2").hide();
            $("#cjgd").hide();
        }
        sx();

        $('#processcontent').niceScroll({
            cursorcolor: "#ddd",//#CC0071 光标颜色
            cursoropacitymax: 1, //改变不透明度非常光标处于活动状态（scrollabar“可见”状态），范围从1到0
            touchbehavior: false, //使光标拖动滚动像在台式电脑触摸设备
            cursorwidth: "5px", //像素光标的宽度
            cursorborder: "0", //   游标边框css定义
            cursorborderradius: "5px",//以像素为光标边界半径
            autohidemode: true //是否隐藏滚动条
        });

        $('#handlecontent').niceScroll({
            cursorcolor: "#ddd",//#CC0071 光标颜色
            cursoropacitymax: 1, //改变不透明度非常光标处于活动状态（scrollabar“可见”状态），范围从1到0
            touchbehavior: false, //使光标拖动滚动像在台式电脑触摸设备
            cursorwidth: "5px", //像素光标的宽度
            cursorborder: "0", //   游标边框css定义
            cursorborderradius: "5px",//以像素为光标边界半径
            autohidemode: true //是否隐藏滚动条
        });
    })

    function sx(){
        reLoad();
        getTrafficProcess();//获取对应高德事件的历史进展
        getHandleProcess();//获取对应高德事件的操作进展
    }

    /**
     * 获取对应高德事件的历史进展
     * @Author   RaK
     * @DateTime 2017-05-11T15:11:14+0800
     * @return   {[type]}                 [description]
     */
    function getTrafficProcess(){
        JAjax('admin/GaoDeLIst/GaoDeListLogic','onLoadTrafficProcess',{gaodeeventid:gaodeeventid},function (data){
            if(data.Success){
                var trafficprocesstr = "";
                var trafficprocesslength = data.data.length;
                for (var i = 0; i < trafficprocesslength; i++) {
                    // if(i<5){
                        trafficprocesstr+='<div class="col-md-12 processcontenttitle" style="border-top:0px solid">';
                        trafficprocesstr+='<div class="col-md-4" style="border-right: 1px solid #eee;">'+data.data[i].roadname+"</div>";
                        trafficprocesstr+='<div class="col-md-2" style="border-right: 1px solid #eee;">'+data.data[i].jamspeed+"</div>";
                        trafficprocesstr+='<div class="col-md-2" style="border-right: 1px solid #eee;">'+data.data[i].jamdist+"</div>";
                        trafficprocesstr+='<div class="col-md-4">'+data.data[i].inserttime+"</div>";
                        trafficprocesstr+="</div>";
                    // }else{
                    //     trafficprocesstr+="<tr class='processtr trafficprocessmoretr' style='display:none'>";
                    //     trafficprocesstr+="<td>"+data.data[i].roadname+"</td>";
                    //     trafficprocesstr+="<td>"+data.data[i].jamspeed+"</td>";
                    //     trafficprocesstr+="<td>"+data.data[i].jamdist+"</td>";
                    //     trafficprocesstr+="<td>"+data.data[i].inserttime+"</td>";
                    //     trafficprocesstr+="</tr>";
                    // }
                };
                // if(trafficprocesslength>5){
                //     trafficprocesstr+="<tr class='processtr'>";
                //     trafficprocesstr+="<td id='trafficprocessmore' moreclass='trafficprocessmoretr' style='text-align: center;color: #154bec;cursor: pointer;' colspan='4' onclick='showMore(this)'>显示更多</td>";
                //     trafficprocesstr+="</tr>";
                // }
                // $("#trafficprocess .processtr").remove();
                $("#processcontent").html(trafficprocesstr);
            }
        },null,false)
    }

    /**
     * 显示更多&收起
     * @Author   RaK
     * @DateTime 2017-05-17T11:46:35+0800
     * @return   {[type]}                 [description]
     */
    function showMore(e){
        var moretrid = $(e).attr('id');
        var moreclass = $(e).attr('moreclass');
        $("."+moreclass).toggle(300);
        var morename = $("#"+moretrid).text();
        var newmorename = morename=='显示更多'?'收起':'显示更多';
        var morename = $("#"+moretrid).text(newmorename);
    }

    /**
     * 获取对应高德事件的操作进展
     * @Author   RaK
     * @DateTime 2017-05-11T15:11:14+0800
     * @return   {[type]}                 [description]
     */
    function getHandleProcess(){
        JAjax('admin/GaoDeLIst/GaoDeListLogic','onLoadHandleProcess',{gaodeeventid:gaodeeventid},function (data){
            if(data.Success){
                var handleprocesstr = "";
                var handleprocesslength = data.data.length;
                for (var i = 0; i < handleprocesslength; i++) {
                    var eventstatusname = data.data[i].eventstatusname==null?'未处理':data.data[i].eventstatusname;
                     handleprocesstr+='<div class="col-md-12 handlecontenttitle" style="border-top:0px solid">';
                        handleprocesstr+='<div class="col-md-4" style="border-right: 1px solid #eee;">'+data.data[i].operatorname+"</div>";
                        handleprocesstr+='<div class="col-md-2" style="border-right: 1px solid #eee;">'+eventstatusname+"</div>";
                        handleprocesstr+='<div class="col-md-2" style="border-right: 1px solid #eee;">'+data.data[i].operatorstatusname+"</div>";
                        handleprocesstr+='<div class="col-md-4">'+data.data[i].operatortime+"</div>";
                        handleprocesstr+="</div>";
                    // if(i<5){
                        // var eventstatusname = data.data[i].eventstatusname==null?'未处理':data.data[i].eventstatusname;
                        // handleprocesstr+="<tr class='processtr'>";
                        // handleprocesstr+="<td>"+data.data[i].operatorname+"</td>";
                        // handleprocesstr+="<td>"+eventstatusname+"</td>";
                        // handleprocesstr+="<td>"+data.data[i].operatorstatusname+"</td>";
                        // handleprocesstr+="<td>"+data.data[i].operatortime+"</td>";
                        // handleprocesstr+="</tr>";
                    // }else{
                    //     var eventstatusname = data.data[i].eventstatusname==null?'未处理':data.data[i].eventstatusname;
                    //     handleprocesstr+="<tr class='processtr handleprocessmoretr' style='display:none'>";
                    //     handleprocesstr+="<td>"+data.data[i].operatorname+"</td>";
                    //     handleprocesstr+="<td>"+eventstatusname+"</td>";
                    //     handleprocesstr+="<td>"+data.data[i].operatorstatusname+"</td>";
                    //     handleprocesstr+="<td>"+data.data[i].operatortime+"</td>";
                    //     handleprocesstr+="</tr>";
                    // }
                };
                // if(handleprocesslength>5){
                //     handleprocesstr+="<tr class='processtr'>";
                //     handleprocesstr+="<td id='handleprocessmore' moreclass='handleprocessmoretr' style='text-align: center;color: #154bec;cursor: pointer;' colspan='4' onclick='showMore(this)'>显示更多</td>";
                //     handleprocesstr+="</tr>";
                // }
                // $("#handleprocess .processtr").remove();
                $("#handlecontent").html(handleprocesstr);
            }
        },null,false)
    }

    /**
     * 获取数据
     * @Author   RaK
     * @DateTime 2017-05-05T14:13:09+0800
     * @return   {[type]}                 [description]
     */
    function reLoad(){
        var loadingLayerIndex=layer.load('加载中…');
        JAjax('admin/GaoDeLIst/GaoDeListLogic','onLoadGaoDeHandleList',{gaodeeventid:gaodeeventid,selecttype:selecttype},
            function (data){
            layer.close(loadingLayerIndex);
            if(data.Success){
                gaodejamDist = data.data.jamdist;
                gaodejamSpeed = data.data.jamspeed;
                intime = data.data.inserttime;
                roadid = data.data.roadid;
                pubrunstatus = data.data.pubrunstatus;
                startStation = data.data.startstationname;
                endStation = data.data.endstationname;
                detailData = data.data.xys;
                xy = data.data.xy;
                longTime = data.data.longtime;
                roadName = data.data.roadname;
                startstationx = data.data.startstationx;
                startstationy = data.data.startstationy;
                endstationx = data.data.endstationx;
                endstationy = data.data.endstationy;

                var empid = data.data.empid;//当前登录人的ID
                $("#roadname").text(data.data.roadname);//路段名称
                $("#inserttime").text(data.data.inserttime);//发布时间
                $("#sjzt").text(data.data.eventstatusname);//事件状态
                $("#startStation").text(data.data.startstationname+"("+data.data.startstationmiles+")");//起始站
                $("#endStation").text(data.data.endstationname+"("+data.data.endstationmiles+")");//结束站
                var startstack = data.data.startstack;
                var endstack = data.data.endstack;
                $("#startmile").text('K'+startstack.replace(".","+"));//起始站桩号
                $("#endmile").text('K'+endstack.replace(".","+"));//结束站桩号

                generateMap();//生成地图
                if(startStation!=""){
                    generatePoi();//生成站点图标
                }



                if(data.data.eventstatus==1001001){//未处理
                    //正在发布中的事件
                    var eventallhtml = "";
                    for (var i = 0; i < data.data.relationeventall.length; i++) {
                        eventallhtml+='<tr class="eventalltr">';
                        eventallhtml+='<td class="form-inline" id="sjeventid" width="250px">';
                        eventallhtml+='<input  type="radio" num="'+data.data.relationeventall[i]['eventno']+'" val="'+data.data.relationeventall[i]['eventid']+'" class="sjglradio" name="sjglradio">&nbsp;<span class="eventspan" id="eventno'+data.data.relationeventall[i]['eventid']+'" >'+data.data.relationeventall[i]['eventno']+'</span></td>';
                        eventallhtml+='<td id="ksz'+data.data.relationeventall[i]['eventid']+'">';
                        eventallhtml+=data.data.relationeventall[i]['ksz'];
                        eventallhtml+='</td>';
                        eventallhtml+='<td id="occtime'+data.data.relationeventall[i]['eventid']+'">';
                        eventallhtml+=data.data.relationeventall[i]['occtime'];
                        eventallhtml+='</td>';
                        eventallhtml+='</tr>';
                    };
                    $(".eventalltr").remove();
                    $("#sjglsave").prepend(eventallhtml);
                    $("#sjglsave").show();
                    $("#cjgd").show();
                }else{
                    isnew = 1;
                    if(data.data.eventstatus==1001002){//有效
                        var eventallhtml = "";
                            eventallhtml+= "<tr class='eventalltr'>";
                            eventallhtml+= "<td><span onclick='lookevent("+data.data.gsteventid+")' style='cursor: pointer;color:#2dd462'>"+data.data.eventno+"</span></td>";
                            eventallhtml+= "<td>"+data.data.ksz+"</td>";
                            eventallhtml+= "<td>"+data.data.occtime+"</td>";
                            eventallhtml+= "</tr>";
                        $(".eventalltr").remove();
                        $("#sjglupdatexx").prepend(eventallhtml);
                        $("#jtkfyjbutton").hide();
                        $("#sjglsave").hide();
                    }else if(data.data.eventstatus==1001003){//无效
                        var imgurl = data.data.picfiles;
                        if(imgurl!="" && imgurl!=null){
                            var imgurlarray = imgurl.split(',');
                            var imghtml = '';
                            if(imgurlarray[0]!=''){
                                $("#theImgDiv1 .images-img,#theImgDiv1 .images-div").remove();
                                for(var i=0;i<imgurlarray.length;i++){
                                    imghtml+='<div style="float:left;margin-left:10px;"><img style="width:100px;" class="images-img" src="'+imgurlarray[i]+'" onclick="showLayerImageJs(this.src)"></div>';
                                }
                            }
                            $("#theImgDiv1").append(imghtml);
                        }
                        $("#sjglsave").hide();
                        $(".invalid").show();
                        $("#clcontent").val(data.data.msg);
                    }
                    $("#cjgd").hide();
                    $("#sjglupdatexx").show();
                }

                //判断操作状态，确定当前人员是否存在对应操作权限
                if(data.data.operatorstatus=='1002001' && empid==data.data.operatorid){
                    //处理中状态并且处理操作人是当前登录的人就可以对事件进行操作
                    var operatorname = data.data.operatorname;
                    $("#operatorname").text(operatorname);
                    $(".well").show();
                    $("#handle").hide();
                    $("#cancelhandle").show();
                    $(".qxclass").show();
                }else if(data.data.operatorstatus=='1002003'){
                    //改事件已经处理完成就不显示按钮
                    $("#cancelhandle").hide();
                    $("#handle").hide();
                }else if(data.data.operatorstatus=='1002001' && empid!=data.data.operatorid){
                    //处理中状态并且处理操作人不是当前登录的人就提示改事件正在处理中！
                    var operatorname = data.data.operatorname;
                    $("#operatorname").text(operatorname);
                    $(".well").show();
                    $("#cancelhandle").hide();
                    $("#handle").hide();
                    $(".qxclass").hide();
                }else{
                    //否则除《处理》 按钮外其他按钮都不显示
                    $(".qxclass").hide();
                }
            }else{
                console.log("获取数据失败");
            }
        },null,false);
    }


    /**
     * 点击跳转查看对应eventid事件
     * @Author   RaK
     * @DateTime 2017-05-05T15:02:30+0800
     * @param    {[type]}                 eventid [description]
     * @return   {[type]}                         [description]
     */
    function lookevent(eventid){
        var src = "<?php echo base_url('/index.php/admin/UnifiedRelease/InfoReleaseLogic/checkUserPower?eventid='); ?>"+eventid;
        $(window.top.document).find('#iframeContent').eq(0).attr('src','').attr('src',src);
    }


    //修改对外公告
    function updateReportout(eventid,newintime,gaodejamDist,gaodejamSpeed,gaodeeventid){
        showLayerPageJs("<?php echo base_url('/index.php/admin/GaoDeLIst/GaoDeListLogic/updateReportout?eventid='); ?>"+eventid+"&newintime="+newintime+"&newgaodejamDist="+gaodejamDist+"&newgaodejamSpeed="+gaodejamSpeed+"&gaodeeventid="+gaodeeventid, '更新对外信息', 650, 530,sx);
    }

    //刷新当前框架
    function reloadiframe(){
    	var myiframeUrl = $(window.top.document).find('#iframeContent').attr("src");
		$(window.top.document).find('#iframeContent').attr("src",myiframeUrl);
    }

    /**
     * 保存处理结果
     * @Author   RaK
     * @DateTime 2017-05-05T13:23:14+0800
     * @param    {[type]}                 e [description]
     * @return   {[type]}                   [description]
     */
    function saveHandleResult(e){
        var eventid = $('input:radio[name="sjglradio"]:checked').attr("val");//关联的eventid 等于-2代表为无效
        var msg = $('#yycontent').val();//备注
        var imgurl = getimgurls();//图片
        if(eventid==undefined && isnew==0){
            ShowMsg("请先选择关联的工单编号");
            return false;
        }
        var eventstatus = 1001002;//默认有效
        if(eventid=='invalid' || eventid==undefined){//等于无效
            eventstatus = 1001003;
        }
        if(eventid!='invalid' && eventid!=undefined){
            var intimes = intime.substring(11,intime.length);
            var newintime = intimes.substring(0,(intimes.length-3));
            updateReportout(eventid,newintime,gaodejamDist,gaodejamSpeed,gaodeeventid);
            return false;
        }
        var loadingLayerIndex=layer.load('操作中…');
        JAjax('admin/GaoDeLIst/GaoDeListLogic','gaoDeHandle',{gaodeeventid:gaodeeventid,eventid:eventid,msg:msg,imgurl:imgurl,eventstatus:eventstatus,isnew:isnew,selecttype:selecttype},function (data){
            layer.close(loadingLayerIndex);
            if(data.Success){
                reLoad();
                getHandleProcess();
                $('.well').hide();
                ShowMsg("保存成功");
            }else{
                ShowMsg("保存失败");
            }
        },null,false);
    }


    /**
     * 获取上传的图片的url
     * @Author   RaK
     * @DateTime 2017-05-05T13:35:36+0800
     * @return   {[type]}                 [description]
     */
    function getimgurls(){
        var imgs = "";
        $(".images-img").each(function(){
            var imgurl = $(this).attr("src");
            imgs+=imgurl+",";
        })
        imgs = imgs.replace(/(^,*)|(,*$)/g,"");
        return imgs;
    }

    /**
     * 上传图片
     * @Author   RaK
     * @DateTime 2017-05-05T13:35:50+0800
     * @param    {[type]}                 obj [description]
     * @return   {[type]}                     [description]
     */
    function readFile(obj){
        var file = obj.files[0];
        //判断类型是不是图片
        if(!/image\/\w+/.test(file.type)){
            alert("请确保文件为图像类型");
            return false;
        }
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(e){
            img=this.result;

            var strArry = img.split(",");

            JAjax('admin/UnifiedRelease/InfoReleaseLogic','imgupload2',{imagebase64:strArry[1]},function (data){
                if(data.Success)
                {
                    newInnerHtml = '<div style="float:left" id="img" class="images-div"><img class="images-img" src="'+data.data['url']+'" onclick="showLayerImageJs(this.src)"><p class="del-pic-p"><img onmouseover="changeSrcHover(this);" onmouseout="changeSrc(this);" class="deleteImg" src="'+deletePicUrl+'" onclick="deleteThisImg(this)" /></p></div>';
                    $('#theImgDiv').append(newInnerHtml);
                }
                else
                {
                    ShowMsg(data.Message);
                }

            },null,true);
        }
    }

    function uploadFile()
    {
        $("#picFile").trigger("click");
    }
    function delimg(){
        $("#imgDiv").html('');
    }

    //失效状态的上传图片
    function readFile2(obj){
        var file = obj.files[0];
        //判断类型是不是图片
        if(!/image\/\w+/.test(file.type)){
            alert("请确保文件为图像类型");
            return false;
        }
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(e){
            img=this.result;

            var strArry = img.split(",");

            JAjax('admin/UnifiedRelease/InfoReleaseLogic','imgupload2',{imagebase64:strArry[1]},function (data){
                if(data.Success)
                {
                    newInnerHtml = '<div style="float:left" id="img" class="images-div"><img class="images-img" src="'+data.data['url']+'" onclick="showLayerImageJs(this.src)"><p class="del-pic-p"><img onmouseover="changeSrcHover(this);" onmouseout="changeSrc(this);" class="deleteImg" src="'+deletePicUrl+'" onclick="deleteThisImg(this)" /></p></div>';
                    $('#theImgDiv2').append(newInnerHtml);
                }
                else
                {
                    ShowMsg(data.Message);
                }

            },null,true);
        }
    }

    function uploadFile2()
    {
        $("#picFile2").trigger("click");
    }

    //删除图片
    function deleteThisImg(object){
        object.parentNode.parentNode.remove();
    }

    function changeSrcHover(object){
        var hoverSrc = "<?php $this->load->helper('url');echo base_url('/asset/images/delete_hover.png') ?>";
        $(object).attr('src',hoverSrc);
    }
    function changeSrc(object){
        var noHoverSrc = "<?php $this->load->helper('url');echo base_url('/asset/images/delete.png') ?>";
        $(object).attr('src',noHoverSrc);
    }

    /**
     * 修改对应高德事件的处理状态
     * @Author   RaK
     * @DateTime 2017-05-09T15:11:57+0800
     * @return   {[type]}                 [description]
     */
    function savehandle(status){
        JAjax('admin/GaoDeLIst/GaoDeListLogic','savehandle',{gaodeeventid:gaodeeventid,status:status},function (data){
            if(data.Success){
                if(status==1002001){
                    $("#handle").hide();
                    $("#cancelhandle").show();
                    $(".qxclass").show();
                    $("#operatorname").text(data.data.operatorname);
                    $(".well").show();
                    ShowMsg("已接受，可对事件进行操作！");
                }else{
                    $("#handle").show();
                    $("#cancelhandle").hide();
                    $(".qxclass").hide();
                    $(".well").hide();
                    $("#operatorname").text("");
                    ShowMsg("已取消处理！");
                }
                getHandleProcess();
            }else{
                ShowMsg(data.Message);
            }
        },null,true);
    }
    var map;
    var marker;

    function generateMap(){
        // var jsondata = JSON.parse(detailData);
        var lineAllArr = detailData.split('|');
        // console.log(lineAllArr);
        map = new AMap.Map('container', {
            resizeEnable: true,
            zoom:13,
            center: [xy.split(",")[0],xy.split(",")[1]]
        });
        for (var i = 0; i < lineAllArr.length; i++) {
            var xys = lineAllArr[i].split(';');
            var lineArr = [];
            for (var j = 0; j < xys.length; j++) {
                lineArr[j] = [xys[j].split(",")[0] , xys[j].split(",")[1]];
            };
             var polyline = new AMap.Polyline({
                path: lineArr,          //设置线覆盖物路径
                strokeColor: "#ff0000", //线颜色
                strokeOpacity: 1,       //线透明度
                strokeWeight: 5,        //线宽
                strokeStyle: "solid",   //线样式
                strokeDasharray: [10, 5] //补充线样式
            });
            polyline.setMap(map);

        };

        marker = new AMap.Marker({
            map: map,
            position: [xy.split(",")[0],xy.split(",")[1]],
            offset: new AMap.Pixel(-20, -20), //相对于基点的偏移位置
            clickable: true  
        }); 
        marker.setMap(map);



        var trafficLayer = new AMap.TileLayer.Traffic({
            zIndex: 10
        });
        trafficLayer.setMap(map);
        trafficLayer.hide();
        marker.on('click',function(){
            showInfoWindows();
        });

        var startXY = [xy.split(",")[0],xy.split(",")[1]];

        var geocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: "all"
        });
        geocoder.getAddress(startXY, function(status, result) {
            if (status === 'complete' && result.info === 'OK') {
                startAddress = result.regeocode.formattedAddress;
                showInfoWindows();
            }
        });
        AMap.plugin(['AMap.ToolBar'],
            function(){
            map.addControl(new AMap.ToolBar());
        });
        // console.log(jsondata);
    }

    function showInfoWindows(){
        var info = [];
        var infoWindow;
        info.push("<div><b>" + roadName + "</b>");
        info.push("地点： "+ startAddress);
        info.push("事件创建时间： " + intime);
        info.push( "时速： "+gaodejamSpeed + " 公里/小时");
        info.push("拥堵： "+ gaodejamDist +" 公里");
        info.push("持续： "+ longTime +"  分钟</div>");
        infoWindow = new AMap.InfoWindow({
            content: info.join("<br/>")  //使用默认信息窗体框样式，显示信息内容
        });
        infoWindow.open(map, marker.getPosition());
    }

    function generatePoi(){
        new AMap.Marker({
            map: map,
            position: [startstationy, startstationx],
            icon: new AMap.Icon({
                size: new AMap.Size(40, 50),  //图标大小
                image: "<?php echo $this->config->base_url(); ?>/asset/images/gaitubao_com_14954220251649.png"
            })
        });

        new AMap.Marker({
            map: map,
            position: [endstationy, endstationx],
            icon: new AMap.Icon({
                size: new AMap.Size(40, 50),  //图标大小
                image: "<?php echo $this->config->base_url(); ?>/asset/images/gaitubao_com_14954219717874.png"
            })
        });
    }

    /**
     * 手动匹配收费站
     * @Author   RaK
     * @DateTime 2017-05-24T16:25:38+0800
     * @return   {[type]}                 [description]
     */
    function matchingPoi(){
        JAjax('admin/GaoDeLIst/GaoDeListLogic','matchingPoi',{gaodeeventid:gaodeeventid,xys:detailData},function (data){
            if(data.status=="OK"){
                var startStation = data.data.startstationstack
                var endStation = data.data.endstationstack
                $("#startStation").text(data.data.startstationname+"(K"+startStation.replace('.','+')+")");
                $("#endStation").text(data.data.endstationname+"(K"+endStation.replace('.','+')+")");
            }else{
                ShowMsg("匹配失败");
            }
        },null,false);
    }



</script>