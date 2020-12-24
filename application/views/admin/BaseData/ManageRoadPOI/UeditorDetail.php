<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<?php $this->load->view('admin/common'); ?>
	<script type="text/javascript">
	var poiid = '<?php echo isset($poiid)?$poiid:"0" ?>';
	var jpgurl = '<?php echo isset($picurl)?$picurl:'' ?>';
	var poiname = '<?php echo isset($name)?$name:'' ?>';

	jQuery(document).ready(function(){
		UE.getEditor('html');

		//显示图片
        if(jpgurl!=''){
            $("#imgupload").html("<img src="+jpgurl+" width='200px' onclick='showLayerImage(this.src)'/>");
        }
	});

	function save(status){
		//取出数据
		var html = UE.getEditor('html').getContent();

		JAjax('admin/baseData/RoadPOILogic','saveUeditor',{poiid:poiid,html:html,jpgurl:jpgurl,poiname:poiname},function (data){
			if(data.Success)
				closeLayerPageJs();
			else
				ShowMsg("提示:" + data.Message);
		},null);
	}

	function dropout(){
		window.parent.parent.closeAll();
	}

</script>
<style>
	.m-10{margin-right: 10px;}
	.dis-none{display:none;}
</style>
</head>
<body>
	<div class="panel panel-default form-horizontal ">
		<div class="panel-body">
			<div class="form-group">
				<div class="col-sm-2">
					图片:
                    <div id="uploader-demo">
                        <div id="fileList" class="uploader-list"></div>
                        <div id="filePicker" class="button-pic" style="margin-bottom: 15px;">上传</div>
                    </div>
				</div>
				<div class="col-sm-10">
					<div id="imgupload" width="100%"></div>
				</div>
			</div>
			<div class="form-group">
				<textarea style="height:400px; width:100%" id="html"><?php echo isset($html)?$html:"" ?></textarea>
			</div>
		</div>
		<div class="panel-footer">
			<input type="button" id="Save" value="保存" class="btn btn-primary m-10" onclick="save();"/>
			<input type="button" id="dropout" value="关闭" class="btn btn-danger" onclick="dropout();" />
		</div>
	</div>
</body>
</html>