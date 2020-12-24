<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<?php $this->load->view('admin/common'); ?>
	<script type="text/javascript">
		var eventid = '<?php echo $eventid ?>';

		function save(){
			var caseValue = $('input:radio[name="caseradios"]:checked').val();
			var caseText = $("input:radio[name='caseradios']:checked").attr('text');

			if (caseValue == '4') {
				caseText = $('#otherCase').val();
			}
			if (caseText.length > 255) {
				ShowMsg("提示:原因太长!");
				return;
			}

			JAjax('admin/MsgPublish/ReportLogic','setEventStatus',{eventid:eventid,case:caseText,status:3},function (data){
				if(data.Success){
					closeLayerPageJs();
				}else
					ShowMsg("提示:" + data.Message);
			},null);
		}

		$(function(){
			$(':radio').click(function(){
				if ($(this).val() == '4') {
					$('#otherCase').removeAttr('disabled');
				}else{
					$('#otherCase').attr('disabled','disabled');
				}
			});
		});


	</script>
<style>
	.m-10{margin-right: 10px;}
	.dis-none{display:none;}
	#otherCase{width: 300px;}
	.form-control{margin-left: 0px;}
</style>
</head>
<body>
	<div class="panel panel-default form-horizontal ">
		<div class="panel-heading">请选择打回原因</div>
		<div class="panel-body">
			<div class="radio">
				<label>
					<input type="radio" name="caseradios" value="1" text='虚假信息'>虚假信息
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="caseradios" value="2" text='敏感信息'>敏感信息
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="caseradios" value="3" text='过期信息'>过期信息
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="caseradios" value="4" onclick="unDisabled()" text='其它'>其它
					<input type="text" placeholder="请输入其它原因" id="otherCase" disabled="disabled" class="form-control">
				</label>
			</div>
		</div>
		<!-- panel-body -->
		<div class="panel-footer">
			<input type="button" id="Save" value="确定" class="btn btn-primary m-10" onclick="save();"/>
			<input type="button" id="dropout" value="返回" class="btn btn-danger" onclick="closeLayerPageJs();" />
		</div>
	</div>
</body>
</html>