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
        .m-10{margin-right:10px;}
        .m-20{margin-right:20px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        .form-inline .col-xs-10{width:83.33333333%;}
        .vc_table{margin: 0;padding:0;}
        .td-width{text-align: right;}
        .vc_table tr td:first{width: 80px;}
        .vc_table .content{max-width: 180px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
	</style>
    <script type="text/javascript" language="javascript">
        var roadoldid = '<?php echo isset($id)?$id:'0' ?>';
        var base_url = '<?php $this->load->helper("url");echo base_url() ?>';
        var jpgurl = '<?php echo isset($data[0]['picurl'])?$data[0]["picurl"]:''; ?>';

        function reLoad() {
            var src = "<?php echo base_url('/index.php/admin/baseData/roadLogic/detail'); ?>";
            $(window.top.document).find("#iframeContent").eq(0).attr('src','').attr('src',src);
        }

        function dropOut() {
            closeLayerPageJs();
        }


        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        /**
         * [detailChange 保存数据更新]
         */
        function detailChange(){
            var roadName = $('#shortname').val();
            var newCode = $('#newcode').val();
            var directionUp = $('#direction1').val();
            var directionDown = $('#direction2').val();
            var startCity = $('#startcity').val();
            var endCity = $('#endcity').val();
            var longitude = $('#longitude').val();
            var latitude = $('#latitude').val();
            var seq = $('#seq').val();
            var location = $('#startend').val();
            //var imgurl = $('#imgurl').attr('src');
            var imgurl = jpgurl;

            if(trimStr(roadName) == ''){alert('路段名不能为空');return;}
            if(trimStr(newCode) == ''){alert('新国标编码不能为空');return;}

            JAjax("admin/baseData/RoadLogic", "saveDetailMsg", {id:roadoldid,roadName:roadName,newCode:newCode,directionUp:directionUp,directionDown:directionDown,startCity:startCity,endCity:endCity,longitude:longitude,latitude:latitude,seq:seq,location:location,imgurl:imgurl}, function (data){
                if (data.Success) {
                    closeLayerPageJs();
                }else{
                    ShowMsg("操作失败:" + data.Message);
                }
            },null);
        }


        /**
         * @desc   点击图片展示原尺寸大图
         */
        jQuery(document).ready(function(){
            //显示图片
            if(jpgurl!=''){
                //var allurl = jpgurl;
                $("#imgupload").html("<img src="+jpgurl+" id='imgurl' width='200px' onclick= 'showLayerImage(this.src)'/>");
            }
        });


</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">路段详细信息</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-width">
                            路段名称:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="shortname" placeholder="<?php if(isset($data[0]['shortname'])){echo $data[0]['shortname'];}?>" value="<?php if(isset($data[0]['shortname'])){echo $data[0]['shortname'];}?>">
                        </td>
                        <td class="td-width">
                            新国标编码:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="newcode" placeholder="<?php if(isset($data[0]['newcode'])){echo $data[0]['newcode'];}?>" value="<?php if(isset($data[0]['newcode'])){echo $data[0]['newcode'];}?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            上行方向:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="direction1" placeholder="<?php if(isset($data[0]['direction1'])){echo $data[0]['direction1'];}?>" value="<?php if(isset($data[0]['direction1'])){echo $data[0]['direction1'];}?>">
                        </td>
                        <td class="td-width">
                            下行方向:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="direction2" placeholder="<?php if(isset($data[0]['direction2'])){echo $data[0]['direction2'];}?>" value="<?php if(isset($data[0]['direction2'])){echo $data[0]['direction2'];}?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            开始城市:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="startcity" placeholder="<?php if(isset($data[0]['startcity'])){echo $data[0]['startcity'];}?>" value="<?php if(isset($data[0]['startcity'])){echo $data[0]['startcity'];}?>">
                        </td>
                        <td class="td-width">
                            结束城市:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="endcity" placeholder="<?php if(isset($data[0]['endcity'])){echo $data[0]['endcity'];}?>" value="<?php if(isset($data[0]['endcity'])){echo $data[0]['endcity'];}?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            经度:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="longitude" placeholder="<?php if(isset($data[0]['longitude'])){echo $data[0]['longitude'];}?>" value="<?php if(isset($data[0]['longitude'])){echo $data[0]['longitude'];}?>">
                        </td>
                        <td class="td-width">
                            纬度:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="latitude" placeholder="<?php if(isset($data[0]['latitude'])){echo $data[0]['latitude'];}?>" value="<?php if(isset($data[0]['latitude'])){echo $data[0]['latitude'];}?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            排列序号:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="seq" placeholder="<?php if(isset($data[0]['seq'])){echo $data[0]['seq'];}?>" value="<?php if(isset($data[0]['seq'])){echo $data[0]['seq'];}?>">
                        </td>
                        <td class="td-width">
                            始终位置:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="startend" placeholder="<?php if(isset($data[0]['startend'])){echo $data[0]['startend'];}?>" value="<?php if(isset($data[0]['startend'])){echo $data[0]['startend'];}?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <p>国标图片:
                                <div id="uploader-demo">
                                    <!--用来存放item-->
                                    <div id="fileList" class="uploader-list"></div>
                                    <div id="filePicker" class="button-pic" style="margin-bottom: 15px;">选择</div>
                                </div>
                            </p>
                        </td>
                        <td class="content" colspan="3">
                            <div id="imgupload"  width="100%"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存" id="new" onclick="detailChange();" class="btn btn-info m-10" >
                <input type="button" value="返 回" id="del" onclick="dropOut();" class="btn btn-danger" >
            </div>
        </div>
    </div>
</body>
</html>