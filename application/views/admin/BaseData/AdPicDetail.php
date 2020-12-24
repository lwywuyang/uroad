<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<?php $this->load->view('admin/common'); ?>
	<link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
	<script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
	<style>
		.m-10{margin-right: 10px;}
		.dis-none{display:none;}
	</style>
</head>
<body>
<div class="panel panel-default form-horizontal">
	<div class="panel-body">
		<table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
			<tbody>
				<tr height="150px">
					<td width="10%" nowrap="nowrap">广告图:
						<div id="uploader-demo">
							<!--用来存放item-->
							<div id="fileList" class="uploader-list"></div>
							<div id="filePicker">上传</div>
						</div>
					</td>
					<td width="90%" algin="center">
						<div id="imgupload" width="100%"></div>
					</td>
				</tr>
				<tr>
					<td width='10%' nowrap="nowrap">链接:</td>
					<td width='90%'>
						<input type="text" id="redirecturl" class="form-control" value="<?php echo isset($redirecturl)?$redirecturl:"" ?>" />
					</td>
				</tr>
				<tr>
					<td width='10%' nowrap="nowrap">排序:</td>
					<td width='90%'>
						<input type="text" id="seq" class="form-control" value="<?php echo isset($seq)?$seq:"" ?>" />
					</td>
				</tr>
			</tbody>
		</table> 
	</div>
	<div class="panel-footer">
		<input type="button" id="Save" value="保存" class="btn btn-primary m-10" onclick="save();"/>
		<input type="button" id="dropout" value="关闭" class="btn btn-danger" onclick="closeLayerPageJss();" />
	</div>
</div>
</body>
<script type="text/javascript">
	var id = '<?php echo isset($id)?$id:'0' ?>';
	var jpgurl = '<?php echo isset($imageurl)?$imageurl:"" ?>';


	$().ready(function(){
		if(jpgurl!=''){
			$("#imgupload").html("<img src="+jpgurl+" width='200px' onclick='showLayerImage(this.src)'/>");
		}
	});


	function save(status){
		var redirecturl = $("#redirecturl").val();
		var seq = $('#seq').val();

		JAjax('admin/baseData/AdPicLogic','onSaveAdPic',{id:id,redirecturl:redirecturl,seq:seq,imageurl:jpgurl},function (data){
			if(data.Success)
				closeLayerPageJss();
			else
				ShowMsg("Failure Tips:" + data.Message);
		},null);
	}



	function showLayerImage(src){
		window.parent.parent.showLayerImage(src);
	}

	function closeLayerPageJss(){
		window.parent.parent.closeAll();
	}

</script>
</html>