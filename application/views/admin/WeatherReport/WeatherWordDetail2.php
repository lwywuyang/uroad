<!DOCTYPE html>
<html id="ng-app" ng-app="app">
<head>
    <title>天气预报</title>
    <?php $this->load->view('admin/common'); ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
    <style>
        a{cursor: pointer; text-decoration: none;}
        a:focus, a:hover{text-decoration: none;}
        body{background-color: #FCFCFC;}
        .m-r-15{margin-right: 15px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .panel-body{padding: 0;}

        .row .col-xs-12{margin-top: 10px;}
        .del-pic-p{position: absolute; top: -10%; left: 95%; z-index: 99;}
    </style>
</head>
<body ng-controller="AppController" nv-file-drop="" uploader="uploader" filters="queueLimit, customFilter">
    <div class="panel-heading">
        <div class="form-inline">
            <div class="form-group" style="margin-left: 0;margin-top: 10px;">
                天气预报
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="row m-0" style="margin: 15px 0; height: auto;">
            <div class="col-xs-2" style="line-height: 40px;">
                标题：
            </div>
            <div class="col-xs-10">
                <input type="text" id="title" class="form-control" value="<?php echo isset($title)?$title:"" ?>" />
            </div>
        </div>
        <div class="row m-0" style="margin: 15px 0; height: auto;">
            <div class="col-xs-2">WORD文件:
            </div>
            <div class="col-xs-10">
                <input type="file" nv-file-select="" uploader="uploader" multiple class="getfile-btn" />
            </div>
            <div class="col-xs-12" id="uploadresult" ></div>
            <div class="col-xs-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="20%">文件名</th>
                            <th width="30%">保存路径</th>
                            <th ng-show="uploader.isHTML5">文件大小</th>
                            <th ng-show="uploader.isHTML5">进度</th>
                            <th>状态</th>
                            <th width="135px">操作</th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        <tr ng-repeat="item in uploader.queue">
                            <td>{{ item.file.name }}</td>
                            <td class="word-break"></td>
                            <td ng-show="uploader.isHTML5" nowrap>{{ item.file.size/1024|number:2 }} KB</td>
                            <td ng-show="uploader.isHTML5">
                                <div class="progress" style="margin-bottom: 0;">
                                    <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                                <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                                <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                            </td>
                            <td nowrap>
                                <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                                    <span class="glyphicon glyphicon-upload"></span>&nbsp;上传
                                </button>
                                <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                                    <span class="glyphicon glyphicon-trash"></span>&nbsp;删除
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div class="row m-0" style="margin: 15px 0; height: auto;">
            <textarea style="height:500px; width:98%; margin: 0 auto;" id="weatherHtml"><?php echo isset($html)?$html:"" ?></textarea>
        </div> -->
        <div class="row m-0" style="margin: 15px 0; height: auto;">
            <div class="col-xs-2">封面:
                <div id="uploader-demo">
                    <div id="fileList" class="uploader-list"></div>
                    <div id="filePicker">选择</div>
                </div>
            </div>
            <div class="col-xs-10">
                <div id="imgupload"  width="100%"></div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <input type="button" value="确定" class="btn btn-primary m-r-15" onclick="submitDetail();" />
        <input type="button" value="返回" class="btn btn-danger" onclick="closeLayerPageJss();" />
    </div>
</body>
<script src="<?php echo base_url('/asset/plugs/angular-upload/jquery-1.8.3.min.js') ?>"></script>
<script src="<?php echo base_url('/asset/plugs/angular-upload/es5-shim.min.js') ?>"></script>
<script src="<?php echo base_url('/asset/plugs/angular-upload/es5-sham.min.js') ?>"></script>

<script src="<?php echo base_url('/asset/plugs/angular-upload/console-sham.js') ?>"></script>
<script src="<?php echo base_url('/asset/plugs/angular-upload/angular.min.js') ?>"></script>
<script src="<?php echo base_url('/asset/plugs/angular-upload/angular-file-upload.min.js') ?>"></script>
<script src="<?php echo base_url('/asset/plugs/angular-upload/controllers.js?159158') ?>"></script>
<script type="text/javascript">
    var id='<?php echo $id ?>';
    var newstype='<?php echo isset($newstype)?$newstype:"" ?>';
    var jpgurl='<?php echo isset($jpgurl)?$jpgurl:"" ?>';
    //var wordUrl = '';

    jQuery(document).ready(function(){
        //UE.getEditor('weatherHtml');
        //显示图片
        if(jpgurl!=''){
            $("#imgupload").html("<img src="+jpgurl+"  width='200px' onclick= 'showLayerImage(this.src)'/>");
        }
    });


    function submitDetail(){
        var title = $("#title").val();
        var wordUrl = localStorage.getItem('weatherWord');

        //var html = UE.getEditor('weatherHtml').getContent();

        JAjax('admin/News/NewsLogic','saveWeather',{id:id,newstype:newstype,title:title,url:wordUrl,jpgurl:jpgurl},function (data){
            if(data.Success){
                ShowMsg('操作成功!');
                closeLayerPageJss();
            }else
                ShowMsg("失败：" + data.Message);
        },null);
    }

    function closeLayerPageJss(){
        window.parent.parent.closeAll();
    }
</script>
</html>