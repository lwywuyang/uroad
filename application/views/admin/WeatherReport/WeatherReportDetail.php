<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<?php $this->load->view('admin/common'); ?>
	<link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
	<script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
	<script type="text/javascript">
	var id = '<?php echo isset($id)?$id:'1' ?>';

	jQuery(document).ready(function(){
		UE.getEditor('html');
	});

	function save(status){
		//取出数据
		var html = UE.getEditor('html').getContent();

		JAjax('admin/WeatherReport/WeatherReportLogic','saveWeatherReport',{id:id,html:html},function (data){
			if(data.Success){
				ShowMsg("提示:保存成功!");
				//closeLayerPageJs();
			}else{
				//ShowMsg("提示:" + data.Message);
			}
		},null);
	}

	/*function see(){
		return;
		showLayerPageJs("http://test.u-road.com/HuNanGSTAppAPIServer/index.php?/news/getnewsdetail?id=1", '预览', 400, 600);
	}*/

</script>
<style>
	.m-10{margin-right: 10px;}
	.dis-none{display:none;}
</style>
</head>
<body>
	<div class="panel panel-default form-horizontal">
		<div class="panel-body">
			<textarea style="height:650px; width:100%" id="html"><?php echo isset($html)?$html:"" ?></textarea>
		</div>
		<!-- panel-body -->
		<div class="panel-footer">
			<input type="button" id="Save" value="保存" class="btn btn-primary m-10" onclick="save();"/>
			<!-- <input type="button" id="see" value="预览" class="btn btn-info m-10" onclick="see();"/>
			<input type="button" id="dropout" value="关闭" class="btn btn-danger" onclick="closeLayerPageJs();" /> -->
		</div>
	</div>
</body>
</html>