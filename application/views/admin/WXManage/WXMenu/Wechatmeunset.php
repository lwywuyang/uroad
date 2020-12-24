<!DOCTYPE html>
<html>
<head>
	<TITLE>微信菜单配置</TITLE>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	 <?php $this->load->view('admin/common'); ?> 
	<link rel="stylesheet" href="<?php $this->load->helper('url');echo base_url('/asset/ztree/css/demo.css') ?>" type="text/css">
	<link rel="stylesheet" href="<?php $this->load->helper('url');echo base_url('/asset/ztree/css/zTreeStyle/zTreeStyle.css') ?>" type="text/css">
	<script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/ztree/js/jquery.ztree.core-3.5.js') ?>"></script>
	<script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/ztree/js/jquery.ztree.excheck-3.5.js') ?>"></script>
	<script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/ztree/js/jquery.ztree.exedit-3.5.js') ?>"></script>
	<script type="text/javascript">
		var topnum='<?php echo isset($topnum)?$topnum:"" ?>';
		var setting = {
			view: {
				addHoverDom: addHoverDom,
				removeHoverDom: removeHoverDom,
				selectedMulti: false
			},
			edit: {
				enable: true,
				editNameSelectAll: true,
				showRemoveBtn: showRemoveBtn,
				showRenameBtn: showRenameBtn
			},
			data: {
				simpleData: {
					enable: true
				}
			},
			callback: {
				beforeDrag: beforeDrag,
				beforeEditName: beforeEditName,
				beforeRemove: beforeRemove,
				beforeRename: beforeRename,
				onRemove: onRemove,
				onClick: zTreeOnClick
			}
		};

		var zNodes =<?php echo $meun ?>;
		//console.log(zNodes);

		var className = "dark";
		
		function beforeDrag(treeId, treeNodes) {
			return false;
		}
		//编辑用于捕获节点编辑按钮的 click 事件，并且根据返回值确定是否允许进入名称编辑状态

		function beforeEditName(treeId, treeNode) {

			var id=treeNode.id;

			if(treeNode.getParentNode()){
				var node = treeNode.getParentNode();
				var pid=node.id;
			}else{
				var pid=0;
			}
			//console.log(id+','+pid);
			// alert(pid);
			detailmeun(id,pid);
			 //不进人编辑状态
			 	return false;
		}
				//删除
		function beforeRemove(treeId, treeNode) {
			var id=treeNode.id;
			//console.log(treeId);
			//console.log(id);
			ShowConfirm("您确定要删除吗？", function () {
			    JAjax("admin/WXManage/WXMenuLogic", "deletewechatmeun", { id: id}, function (data) {
			        if (data.Success) {
			        	setalgin();//刷新
			        }else {
			        	return false;
			        }
			    }, "null");
			});
			return false;
			
		}
		// 用于捕获删除节点之后的事件回调函数。
		function onRemove(e, treeId, treeNode) {
			//console.log(e);
				return true;
			
		}
		function beforeRename(treeId, treeNode, newName, isCancel) {

				return true;
		}

				
		//显示去除按钮
		function showRemoveBtn(treeId, treeNode) {
			// return !treeNode.isFirstNode;
			return  true;

		}
		//显示编辑按钮
		function showRenameBtn(treeId, treeNode) {
			// return !treeNode.isLastNode;
			return true;
		}
				

		var newCount = 1;
		//鼠标滑过显示
		function addHoverDom(treeId, treeNode) {
			//console.log(treeNode);
			if(treeNode.showadd==1){
				if(treeNode.children){
					if(treeNode.children.length<6){
						var sObj = $("#" + treeNode.tId + "_span");
						if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
						// 新增的按钮
						var addStr = "<span class='button add' id='addBtn_" + treeNode.tId + "' title='" + treeNode.id + "' onfocus='this.blur();'></span>";

						sObj.after(addStr);

						var btn = $("#addBtn_"+treeNode.tId);

						if (btn) btn.bind("click", function(){
							//增加子节点函数
							var pid=treeNode.id;
							detailmeun(0,pid);

						});
					}
							
				}else{
					var sObj = $("#" + treeNode.tId + "_span");
					if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
					// 新增的按钮
					var addStr = "<span class='button add' id='addBtn_" + treeNode.tId + "' title='" + treeNode.id + "' onfocus='this.blur();'></span>";

					sObj.after(addStr);

					var btn = $("#addBtn_"+treeNode.tId);

					if (btn) btn.bind("click", function(){
						//增加子节点函数
						var pid=treeNode.id;

						detailmeun(0,pid);

					});
				}
						
			}
					
		};
		//鼠标移开显示
		function removeHoverDom(treeId, treeNode) {
			$("#addBtn_"+treeNode.tId).unbind().remove();
		};
		function selectAll() {
			var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			zTree.setting.edit.editNameSelectAll =  $("#selectAll").attr("checked");
		}

		//新增顶级菜单(34,30)
		function detailmeun(id,pid){
			//主id
			//子类pid,
			showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/WXMenuLogic/wechatedit') ?>?id="+id+'&pid='+pid, '菜单配置', 600, 350, setalgin);
		}

		function zTreeOnClick(event, treeId, treeNode) {
			var id=treeNode.id;
			var name=treeNode.name;
			var meuntype=treeNode.meuntype;
			var meunurl=treeNode.meunurl;
			var pid=treeNode.meunpid;
			//alert(treeNode.meunurl);
			//console.log(meuntype);
		    //console.log(id+','+name+','+meuntype+','+meunurl+','+pid);
		    // alert(treeNode.children);
		    //如果有子节点

		    if(treeNode.children){

		    	$("#message").show();
		    	$("#urlinfo").hide();
		    	$("#newlist").hide();
		    	
		    }else{
		    	$("#urlinfo").show();
		    	$("#message").hide();
		    	$("#newlist").hide();
		    	//console.log(meuntype);
		    	if(meuntype=='0'){
		    		showurl(treeNode);
		    		return;
		    	}
		    	if(meuntype=='1'){
		    		$("#urlinfo").hide();
		    		$("#message").hide();
		    		$("#newlist").show();
		    		shownewlist(treeNode.id);
		    		return;
		    	}

		    	
		    }
		    // treeNode.children
		}

		$(document).ready(function(){
			//console.log(topnum);
			if(topnum>=3){
				//console.log(topnum);
				$("#addtopmeun").hide();
			}
			 
			//console.log(zNodes);
			config();
		});
		function config(){
			$.fn.zTree.init($("#treeDemo"), setting, zNodes);
			$("#selectAll").bind("click", selectAll);
		}
		// 刷新
		function setalgin(){
			var url ='<?php $this->load->helper("url");echo base_url("index.php/admin/WXManage/WXMenuLogic/indexPage") ?>';    
			window.location.href = url;
		}
		//显示详情
		function showurl(obj){
			//console.log(obj.meunurl);
			var url=obj.meunurl;
			var con='';
			$("#urlinfo").html('');
			con='<span>链 接：</span><input type="text" value="'+url+'"  class="form-control " id="url"><input type="button" value=" 保 存 " onclick="save('+obj.id+','+obj.meuntype+')" class="btn btn-primary" id="save">';
			$("#urlinfo").append(con);
		}
		// 显示新闻详情
		function shownewlist(id){
			$("#meunlist").css('display','none');
			$("#newlist").show();
			// var id =obj.id;
			$(".newlistdetail").html('');
			//console.log(id);
			// var code=obj.meuncode;
			var con=' <input type="button" value="新 增"  id="new" onclick="detailnew('+id+',0);" class="btn btn-info m-10" ><input type="button" value="删 除" id="del" onclick="delnew('+id+');" class="btn btn-danger" >';
			$(".newlistdetail").append(con);
			JAjax("admin/WXManage/WXMenuLogic", 'shownewlist', {id:id}, function (data) {
			   ReloadTb('dataGrid', data.data);
			}, 'null');
		}

		//这个保存信息
		function save(id,type){
			var url=$("#url").val();
			JAjax("admin/WXManage/WXMenuLogic", "saveurl", { url:url,id:id}, function (data) {
			    if (data.Success) {
					ShowMsg("保存信息");
					setalgin();//刷新
			    }
			    else {
			       
			    }
			}, "null");
		}

		function showLayerImage(url){
		        window.parent.showLayerImage(url);
		}

		//编辑图文信息
		//menu->gde_news的菜单id
		function detailnew(menuid,newid){
			// alert(newid);
			showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/WXMenuLogic/detailnew') ?>?id="+menuid+'&newid='+newid, '图文信息', 800, 600, shownewlist,menuid);
		}
				
				
				 
		/*删除函数*/
		//删除图文信息
		function delnew(id) {
			//获取选中选框,属性的name元素,dataGrid上下文对象
			var values = getCheckedValues("rpcheckbox", "#dataGrid",'string');
			if (values != "" && values != undefined) {
				ShowConfirm("您确定要删除吗？", function () {
					JAjax("admin/WXManage/WXMenuLogic", "delnew", { OID: values}, function (data) {
						if (data.Success) {
							shownewlist(id);
						}
						else {
							ShowMsg("删除失败：" + data.Message);
						}
					}, "pager");
				});
			}
			else {
				ShowMsg("请至少选择一条记录！");
			}
		}  

		function changestatus(newid,type,id){
			
			ShowConfirm("您确定要此操作吗？", function () {
			//console.log(newid+','+type+','+id);
				JAjax("admin/WXManage/WXMenuLogic", "statuschange", {id:newid,type:type}, function (data) {
					if (data.Success) {
						shownewlist(id);
					}
					else {
						ShowMsg("失败" + data.Message);
					}
				}, "pager");

			});

		}

		function setweixinmeun(){

			ShowConfirm("您确定要配置吗吗？", function () {
				JAjax("admin/WXManage/WXMenuLogic", "setweixinmeun", {}, function (data) {
					if (data.Success) {
						ShowMsg("配置成功");
					}
					else {
						ShowMsg("配置失败");
					}
				}, null);


			});
		}
		//-->
	</SCRIPT>
	<style type="text/css">
		div.content_wrap {width: 20%;}
		.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
		.zTreeDemoBackground{width: 20%;height: 100%;}
		ul.ztree{border: 0;background: #FCFCFC;}
		.meunedit{width: 78%;height: 100%;background: #FCFCFC;margin-left: 10px;}
		.panel-body{width: 100%;}
		.left{float: left;}
		.meunlist{text-align: left;width: 100%;padding-top: 10%;}
		.meunlist #url{width: 80%;}
		.meunlist span,.meunlist #url{height: 40px;float: left;line-height: 40px;}
		a,a:hover{text-decoration: none;cursor :pointer ;}
		.picture{max-width: 50px;}
		.m-10{margin-right: 10px;margin-left: 5px;}
		.btn-xs{margin: 1px 0px;}
	</style>
</head>

<body>
	<div class="panel panel-default" id="content_list">
	    <div class="panel-heading">
	        <div class="form-inline mb10">
	            <input type="button" value="配置微信菜单" onclick="setweixinmeun()" class="btn btn-primary">
	            <label>更新微信菜单，微信端会有一定时间缓存，需要等待一段时间，或者重新关注。</label>
	        </div>
	    </div>
	    <div class="panel-body">
	    	<div class="form-inline mb10">
	    	    <input type="button" value="新增菜单" onclick="detailmeun(0,0)" class="btn btn-primary" id="addtopmeun">
	    	</div>
	    	<div class="content_wrap left">
	    		<div class="zTreeDemoBackground " >
	    			<ul id="treeDemo" class="ztree"></ul>
	    		</div>
	    	</div>
	    	<div class="left meunedit">
	    		 <div id="newlist" style="display:none">
	    		 	<div class="form-inline mb10 newlistdetail">
	    		 	   
	    		 	</div>
	    			<table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
	    			    <thead>               
	    			        <tr>
	    			            <th class="title"  width="30px" itemvalue="newid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
	    			                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
	    			            </th>
	    			            <th class="title" width="6%" itemvalue="sort" center="true">序号 
	    			            </th>
	    			           <th class="title" itemvalue="picture" width="70px" center="true" showtype="a" attr="onclick= showLayerImage('{imgurl}') href='javascript:void(0)'" itemtext="{itemvalue}">封面
	    			            </th>
	    			            <th class="title" nowrap="nowrap" itemvalue="title" center="true" showtype="a" attr="onclick= detailnew('{id}','{newid}') href='javascript:void(0)'" itemtext="{itemvalue}">标题  
	    			            </th>
	    			            <th class="title" width="10%" itemvalue="pubtime" center="true" showformat="yyyy-MM-dd hh:mm">发布时间
	    			            </th>
	    			            <th class="title" width="70px" itemvalue="statusName" center="true" >状态
	    			            </th>
	    			            <th class="title" width="8%" itemvalue="viewcount" center="true">浏览数
	    			            </th>
	    			            <th class="title" width="12%" itemvalue="statuschange" center="true">操作
	    			            </th>
	    			            <!-- <th class="title" width="8%" nowrap="nowrap" itemvalue="statuschange" center="true" showtype="a" attr="onclick= detailnew('{id}','{newid}') href='javascript:void(0)'" itemtext="{itemvalue}">操作  
	    			            </th> -->
	    			        </tr>
	    			    </thead>
	    			    <tbody>
	    			        <!-- 数据 -->
	    			    </tbody>
	    			</table>
	    		 </div>
	    		<div class="meunlist">
	    			<div id="urlinfo" style="display:none">
	    				
	    			</div>
	    			<div id="message" style="display:none">
	    				<span>已有子菜单，无法设置动作。</span>
	    			</div>
	    		</div>
				 
	    	</div>
	    	
	    	
	    </div>
	    <!-- panel-body -->
	</div>	
</body>
</html>