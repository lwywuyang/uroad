<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<?php $this->load->view('admin/common'); ?>
	<link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
	<script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
	<script type="text/javascript">
	var id='<?php echo $id ?>';
	var newstype='<?php echo isset($newstype)?$newstype:"" ?>';
	var status='<?php echo isset($status)?$status:"" ?>';
	var jpgurl='<?php echo isset($jpgurl)?$jpgurl:"" ?>';
	var baseimgurl='<?php echo $this->config->item("img_url"); ?>';
	var src='';
	var subnewstype = '<?php echo isset($subnewstype)?$subnewstype:"" ?>';
	var type = '<?php echo isset($linktype)?$linktype:'' ?>';


	$().ready(function(){
		if (newstype == '1011003' || newstype == '1011008') {
			$('#subtypeTr').removeClass('dis-none');
		}
		$('#subtypeSel').find('option[value='+subnewstype+']').attr('selected',true);

		if (newstype == '1011001' || newstype == '1011002' || newstype == '1011025' || newstype == '1011026') {//沿途美食,景点,住宿或购物
			$('#longitudeTr').removeClass('dis-none');
			$('#latitudeTr').removeClass('dis-none');
		}

		//首页广告或预警预报,路政公示,批复文件以及ETC模块的各个资讯菜单
		if (newstype == '1011018' || newstype == '1006008' || newstype == '1011029' || newstype == '1011030' || newstype=='1011023' || newstype == '1011013' || newstype == '1011015' || newstype == '1011016' || newstype == '1011017' || newstype == '1011027' || newstype == '1011032') {
			//$('#seqTr').removeClass('dis-none');
			$('#typeTr').removeClass('dis-none');
		}

		if (newstype == '') {//特色风景等等
			$('#uploadPicTr').addClass('dis-none');
		}

		$('#typeSel').find('option[value='+type+']').attr('selected','selected');
		changeType(type);
		$("#sta").val(<?php echo isset($sta)?$sta:0 ?>);
	});

	//关联方法
	function  same(){
		if($("#sta").val() == 1 || $("#sta").val() == "1"){
			$("#sta").val(0);
			$("#same").val("关联首页");
			$("#same").attr("class","btn btn-info");
		}else{
			$("#sta").val(1);
			$("#same").val("取消关联");
			$("#same").attr("class","btn btn-success");
		}

	}

	function save(status){
		//取出数据
		var title = $("#title").val();
		var type = $('#typeSel').val();
		//if (type == '1') {
			var html = UE.getEditor('html').getContent();
		//}else{
			var url = $('#url').val();
		//}
		
		var longitude = $('#longitude').val();
		var latitude = $('#latitude').val();
		//var seq = $('#seq').val();
		var subtypeSel = $('#subtypeSel').val();
		//关联状态
		var sta = $("#sta").val();

		if(title==''){
			ShowMsg('标题不能空');
		}else {
			JAjax('admin/News/NewsLogic','onSave',{title:title,html:html,url:url,type:type,id:id,newstype:newstype,jpgurl:jpgurl,longitude:longitude,latitude:latitude,subtypeSel:subtypeSel,sta:sta},function (data){
				if(data.Success){
					ShowMsg('操作成功!');
					closeLayerPageJss();
				}else
					ShowMsg("失败：" + data.Message);
			},null);
		}
	}

	jQuery(document).ready(function(){
		UE.getEditor('html');
		//显示图片
		if(jpgurl!=''){
			$("#imgupload").html("<img src="+jpgurl+"  width='200px' onclick= 'showLayerImage(this.src)'/>");
		}
	});


	function showLayerImage(src){
		window.parent.parent.showLayerImage(src);
	}

	function closeLayerPageJss(){
		window.parent.parent.closeAll();
	}

	function changeType(typeSelected){
		if (typeSelected == '1') {
			$('#urlTr').removeClass('dis-none');
			$('#htmlTr').addClass('dis-none');
		}else{
			$('#htmlTr').removeClass('dis-none');
			$('#urlTr').addClass('dis-none');
		}
	}

	</script>
<style>
	.m-10{margin-right: 10px;}
	.dis-none{display:none;}
</style>
</head>
<body>
	<div class="panel panel-default form-horizontal ">
		<div class="panel-body ">
			<table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
				<tbody>
					<tr id="subtypeTr" class="dis-none">
						<td width='10%' nowrap="nowrap">资讯细分类型:</td>
						<td width='90%'>
							<select class="form-control" id="subtypeSel">
			                    <?php foreach($subnewstypeData as $item): ?>
			                        <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
			                    <?php endforeach;?>
			                </select>
						</td>
					</tr>
					<tr>
						<td width='10%' nowrap="nowrap">标题:</td>
						<td width='90%'>
							<input type="text" id="title" NotNull="true" class="form-control" value="<?php echo isset($title)?$title:"" ?>" />
							<input type="hidden" id="sta" />
						</td>
					</tr>
					<tr class="dis-none" id="longitudeTr">
						<td width='10%' nowrap="nowrap">经度:</td>
						<td width='90%'>
							<input type="text" id="longitude" NotNull="true" class="form-control" value="<?php echo isset($longitude)?$longitude:"" ?>" />
						</td>
					</tr>
					<tr class="dis-none" id="latitudeTr">
						<td width='10%' nowrap="nowrap">纬度:</td>
						<td width='90%'>
							<input type="text" id="latitude" NotNull="true" class="form-control" value="<?php echo isset($latitude)?$latitude:"" ?>" />
						</td>
					</tr>
					<!-- <tr class="dis-none" id="seqTr">
						<td width='10%' nowrap="nowrap">排序:</td>
						<td width='90%'>
							<input type="text" id="seq" NotNull="true" class="form-control" value="<?php echo isset($seq)?$seq:"" ?>" />
						</td>
					</tr> -->
					<tr class="dis-none" id="typeTr">
						<td width='10%' nowrap="nowrap">类型:</td>
						<td width='90%'>
							<select class="form-control" id="typeSel" onclick="changeType(this.value)">
								<option value="0">HTML图文</option>
								<option value="1">外部URL链接</option>
							</select>
						</td>
					</tr>
					<tr id="htmlTr">
						<td width='10%' nowrap="nowrap">详细内容:</td>
						<td width='90%' >
							<textarea style="height:400px; width:100%" id="html"><?php echo isset($html)?$html:"" ?></textarea>
						</td>
					</tr>
					<tr class="dis-none" id="urlTr">
						<td width='10%' nowrap="nowrap">URL链接:</td>
						<td width='90%'>
							<input type="text" id="url" NotNull="true" class="form-control" value="<?php echo isset($url)?$url:"" ?>" />
						</td>
					</tr>
					<tr height="150px" id="uploadPicTr">
						<td width="10%" nowrap="nowrap">封面图片:
							<div id="uploader-demo">
								<!--用来存放item-->
								<div id="fileList" class="uploader-list"></div>
								<div id="filePicker">选择</div>
							</div>
						</td>
						<td width="90%" algin="center" >
							<div id="imgupload"  width="100%"></div>
						</td>
					</tr>
				</tbody>
			</table> 
		</div>
		<!-- panel-body -->
		<div class="panel-footer">
			<input type="button"  id="Save" value="保存"  class="btn btn-primary m-10"  onclick="save();"/>
			<?php  if(($newstype == '1011016' || $newstype == '1011018')){
				if(isset($sta)  && $sta === 1)
					echo "<input type='button'  id='same'  value='取消关联' class='btn btn-success' onclick='same();' />";
				else
					echo "<input type='button'  id='same'  value='关联首页' class='btn btn-info' onclick='same();' />";

			}
			?>
			<input type="button"  id="dropout"  value="关闭" class="btn btn-danger" onclick="closeLayerPageJss();" />
		</div>
	</div>
</body>
</html>
