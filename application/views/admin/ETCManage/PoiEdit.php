<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <?php $this->load->view('admin/common'); ?>
  <script type="text/javascript">
    var id='<?php echo isset($id)?$id:'0' ?>';
    var array_image=new Array();
    var zoneIdArr = <?php echo $zoneId_json ?>;
    var zoneNameArr = <?php echo $zoneName_json ?>;

      //上级公司的id
      function save(){
        //取出数据
        var newid = $('#id').val();
        var title=$("#title").val();
        var address=$("#address").val();
        var longitude=$("#longitude").val();
        var latitude=$("#latitude").val();
        var city=$("#city").val();
        var zone=$("#zone").val();
        var businesstypeid=$("#businesstypeid").val();           
        var businessstatusid=$("#businessstatusid").val();
        var phone=$("#phone").val();
        var businesstime=$("#businesstime").val();
        var remark=$("#remark").val();

        //提交后台
        if(title==''){
          ShowMsg('标题不能空');

        }else {
          JAjax('admin/ETCManage/PoiLogic','onSavePoi',{title:title,address:address,longitude:longitude,latitude:latitude,city:city,zone:zone,businesstypeid:businesstypeid,businessstatusid:businessstatusid,phone:phone,businesstime:businesstime,remark:remark,id:id,newid:newid},function (data){
            if(data.Success){
              closeLayerPageJs();  
            }else{
              ShowMsg("失败:" + data.Message);
            }
          },"pager");
        }
      }

      jQuery(document).ready(function(){
        $("#city").find("option[value='<?php echo isset($city)?$city:"" ?>']").attr("selected",true);
        changeCity();
        $("#zone").find("option[value='<?php echo isset($zone)?$zone:"" ?>']").attr("selected",true);
        // jQuery(".chosen-select").chosen({'width':'100%','white-space':'nowrap',"search_contains": "true"});
        // jQuery("#category").chosen({'width':'100%','white-space':'nowrap',"search_contains": "true"}).change(function(){
        //   var forum=$(this).val();
        //   getTags(forum);
      });


      function changeCity(){
        var city = $('#city option:selected').val();//城市选中的值
        var zone = document.getElementById('zone');
        zone.length = 0;
        
        for(var i=0;i<zoneIdArr[city].length;i++){
            zone.options.add(new Option(zoneNameArr[city][i],zoneIdArr[city][i]));
        }
      }





</script>
<style>
  .m-10{margin-right: 10px;}
</style>
</head>
<body>     
  <div class="panel panel-default form-horizontal ">
    <div class="panel-body ">
      <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
        <tbody>                   
          <tr>
            <td class="name" nowrap="nowrap" >名称:</td>
            <td class="content" colspan='5'>
              <input name="title" type="text"  id="title" NotNull="true" class="form-control" value="<?php echo isset($title)?$title:"" ?>" />
            </td>  
          </tr>
          <tr>
            <td class="name" nowrap="nowrap" >地址:</td>
            <td class="content" colspan='5'>
              <input name="address" type="text"  id="address" NotNull="true" class="form-control" value="<?php echo isset($address)?$address:"" ?>" />
            </td>                     
          </tr>
          <tr>
            <td class="name" nowrap="nowrap">网点编号:</td>
            <td class="content">
              <input name="id" type="text"  id="id" NotNull="true" class="form-control" value="<?php echo isset($id)?$id:"" ?>"/>
            </td>
            <td class="name" nowrap="nowrap">经度:</td>
            <td class="content">
              <input name="longitude" type="text"  id="longitude" NotNull="true" class="form-control" value="<?php echo isset($longitude)?$longitude:"" ?>" />
            </td>
            <td class="name" nowrap="nowrap">纬度:</td>
            <td class="content">
              <input name="latitude" type="text"  id="latitude" NotNull="true" class="form-control" value="<?php echo isset($latitude)?$latitude:"" ?>" />
            </td>
          </tr>
          <tr>
            <td class="name" nowrap="nowrap" >城市:</td>
            <td class="content">
              <select class="form-control " id="city" onchange="changeCity()">
                <?php foreach ($allCity as $k): ?>
                  <option value="<?php echo $k['id'] ?>"><?php echo $k['city'] ?></option>
                <?php endforeach ?>
              </select>
              <script type="text/javascript">
              </script>
            </td>
            <td class="name" nowrap="nowrap" >地区:</td>
            <td class="content">
              <select class="form-control " id="zone">
                <option value="">请选择地区</option>
              </select>
              <!-- <script type="text/javascript">
                $("#zone").find("option[value='<?php echo isset($zone)?$zone:"" ?>']").attr("selected",true);
              </script> -->
            </td>

            <td class="name" nowrap="nowrap" >联系电话:</td>
            <td class="content" >
              <input name="phone" type="text"  id="phone" NotNull="true" class="form-control" value="<?php echo isset($phone)?$phone:"" ?>" />
            </td> 
          </tr>
          <tr>
            <td class="name" nowrap="nowrap" >类型:</td>
            <td class="content">
              <select name="businesstypeid" id="businesstypeid" class="form-control">
                <?php foreach ($businesstypeids as $k): ?>
                  <option  value="<?php echo $k['dictcode'] ?>"><?php echo $k['name'] ?></option>
                <?php endforeach ?>
              </select> 
              <script type="text/javascript">
                $("#businesstypeid").find("option[value='<?php echo isset($businesstypeid)?$businesstypeid:"" ?>']").attr("selected",true);
              </script>
            </td>
            <td class="name" nowrap="nowrap" >状态:</td>
            <td class="content">
              <select name="businessstatusid" id="businessstatusid" class="form-control">
                <?php foreach ($status as $k): ?>
                  <option  value="<?php echo $k['dictcode'] ?>"><?php echo $k['name'] ?></option>
                <?php endforeach ?>
              </select>
              <script type="text/javascript">
                $("#businessstatusid").find("option[value='<?php echo isset($businessstatusid)?$businessstatusid:"" ?>']").attr("selected",true);
              </script>
            </td>
            <td class="name" nowrap="nowrap" >营业时间:</td>
            <td class="content" >
              <input name="businesstime" type="text"  id="businesstime" NotNull="true" class="form-control" value="<?php echo isset($businesstime)?$businesstime:"" ?>" />
            </td>
          </tr>
          <tr> 
            <td class="name" nowrap="nowrap" >备注:</td>
            <td  style=" padding-left:10px" colspan="5">
              <textarea cols="125" class="form-control" id="remark"><?php echo isset($remark)?$remark:"" ?></textarea>                  
            </td>
          </tr>                     
        </tbody>
      </table> 
      <!-- panel-body -->
    </div>
    <!-- panel-body -->
    <div class="panel-footer">
      <input type="submit" name="add" value="确定" id="Save" class="btn btn-primary m-10" onclick="save();"/>
      <input type="button" value="取消" class="btn btn-danger" onclick="closeLayerPageJs();" />

    </div>
  </div>
</body>
</html>