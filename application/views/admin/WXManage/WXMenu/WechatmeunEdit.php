<!DOCTYPE html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>菜单配置</title>
    <?php $this->load->view('admin/common') ?>
    <script type="text/javascript" language="javascript">
        var page = 0;
        var id='<?php echo isset($id)?$id:"" ?>';
        var pid='<?php echo isset($pid)?$pid:"" ?>';
        // alert(pid);
         function save(){
            //取出数据
            var title=$("#title").val();
            var sort=$("#sort").val();
            var itypeid=$("#itypeid").val();
            var url=$("#url").val();
            var cateid=$("#cateid").val();
            if (cateid == '') {cateid == '0';}
            //var developercode=$("#developercode").val();
            if(title==''){
              ShowMsg('名称不能空');
            }else {
              JAjax('admin/WXManage/WXMenuLogic','onSave',{id:id,pid:pid,title:title,sort:sort,itypeid:itypeid,url:url,cateid:cateid},function (data){
                if(data.Success)
                {
                  closeLayerPageJs();  
                }
                else
                {
                  ShowMsg("失败：" + data.Message);
                }

              },"pager");
            }
          }  

          //选择类型
          function selecttype(a){

            //跳转URL
            if(a=='0'){
              $("#urlseet").show();
              $("#cateidset").hide();
              $("#setself").hide();
              return;
            }
            //图文消息
            if(a=='1'){
               $("#urlseet").hide();
              $("#cateidset").show();
              $("#setself").hide();
              return;
            }
            //自定义开发
            if(a=='2'){
              $("#urlseet").hide();
              $("#cateidset").hide();
              $("#setself").show();
              return;
            }
          }
          jQuery(document).ready(function() {
                 var itypeid='<?php echo isset($itype)?$itype:"" ?>';
                 if(itypeid!=''){
                    selecttype(itypeid);
                 }
            });
    </script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-body">
            <div class="form-inline mb10">
               <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                       <tbody>                   
                         <tr>
                           <td  width="10%" nowrap="nowrap" >菜单名称:</td>
                           <td   width="60%"  >
                             <input  type="text"  id="title"  class="form-control " value="<?php echo isset($title)?$title:"" ?>" style="width:90%" />
                           </td> 
                           <td  width="10%" nowrap="nowrap" >排序:</td>
                           <td  width="20%">
                             <input  type="text"  id="sort"  class="form-control " value="<?php echo isset($sort)?$sort:"" ?>" />
                           </td> 
                        </tr>

                        <tr>
                          <td  nowrap="nowrap" >类型:</td>
                          <td>
                            <select name="itypeid" id="itypeid" class="form-control procon" style="width:90%" onchange="selecttype(this.value)">  
                              <!-- <?php foreach ($itypeids as $k):?>
                                <option  value="<?php echo $k['dictcode'] ?>"><?php echo $k['name'] ?></option>
                              <?php  endforeach ?> -->
                              <option  value="0">url跳转</option>
                              <option  value="1">关键词读表</option>
                              <option  value="2">其他</option>
                            </select>
                              <script type="text/javascript">
                                  $("#itypeid").find("option[value='<?php echo isset($itypeid)?$itypeid:"" ?>']").attr("selected",true);
                              </script>
                         </td>
                       </tr> 
                       <tr id='urlseet'>
                         <td>
                           url:
                         </td>
                         <td colspan='3'>
                           <input  type="text"  id="url"  class="form-control " value="<?php echo isset($url)?$url:"" ?>" style="width:100%" />
                         </td>
                       </tr>
                       <tr id="cateidset" style="display:none">
                         <td>
                           图文标识ID:
                         </td>
                         <td>
                           <input  type="text"  id="cateid"  class="form-control " value="<?php echo isset($cateid)?$cateid:"" ?>" style="width:90%" />
                         </td>
                       </tr>
                       <tr id="setself" style="display:none">
                          
                       </tr>
               </tbody>
               </table> 
            </div>
             
        </div>
        <div class="panel-footer">
         <input type="button"  id="btnSave" value="保存"  class="btn btn-primary" onclick="save();" />
         <input type="button"  value="取消" class="btn btn-primary" onclick="closeLayerPageJs();" />
        </div>
        <!-- panel-body -->
    </div>

</body>
</html>
