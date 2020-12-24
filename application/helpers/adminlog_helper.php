<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('adminlog')) {
	function adminlog($content,$type,$subtype)
	{
		// 发送信息
		
		$CI =& get_instance();

	    $CI->load->library('session');
		$empid=getsessionempid();
		$empname=getsessionempname();

		$logdata['content']=$empname.'['.$_SERVER["REMOTE_ADDR"].']'.'--'.$content;
		$logdata['intime']=date('Y-m-d H:i:s',time());
		$logdata['empid']=$empid;
		$logdata['empname']=$empname;
		$logdata['type']=logdict($type);
		$logdata['subtype']=logdict($type,$subtype);
		
			$CI->db->trans_begin();

			$CI->db->insert('sys_adminlog', $logdata); 

			if ($CI->db->trans_status() === FALSE)
			{
			    $CI->db->trans_rollback();
			    return false;
			}
			else
			{
			    $CI->db->trans_commit();
			     return true;
			}	 
	}


	//数组
	function logdict($type,$subtype=''){
		//后台admin
		$topdata['admin']='1';
		$data['admin']['loginin']='1001';
		$data['admin']['loginout']='1002';
		$data['admin']['editpassword']='1003';

		//组装机机构
		$topdata['orgmanage']='2';
		$data['orgmanage']['addcom']='2001';
		$data['orgmanage']['editcom']='2002';
		$data['orgmanage']['delcom']='2003';
		$data['orgmanage']['adddep']='2004';
		$data['orgmanage']['editdep']='2005';
		$data['orgmanage']['deldep']='2006';
		$data['orgmanage']['addemp']='2007';
		$data['orgmanage']['editemp']='2008';
		$data['orgmanage']['delemp']='2009';
		//用户审核
		$topdata['authentication']='3';
		$data['authentication']['checkyes']='3001';
		$data['authentication']['checkno']='3002';

		//快处快赔
		$topdata['accidentquick']='4';
		$data['accidentquick']['checkyes']='4001';
		$data['accidentquick']['checkno']='4002';
		//爆料审核
		$topdata['userreport']='5';
		$data['userreport']['checkyes']='5001';
		$data['userreport']['checkno']='5002';

		//报障审核
		$topdata['userhinder']='6';
		$data['userhinder']['checkyes']='6001';
		$data['userhinder']['checkno']='6002';

		//交警风采
		$topdata['new']='7';
		$data['new']['newadd']='7001';
		$data['new']['newedit']='7002';
		$data['new']['newdel']='7003';
		//交管动态
		$data['new']['newstrafadd']='7004';
		$data['new']['newstrafedit']='7005';
		$data['new']['newstrafdel']='7006';

		//poi管理
		$topdata['poi']='8';
		//poi分类
		$data['poi']['poitypeadd']='8001';
		$data['poi']['poitypeedit']='8002';
		$data['poi']['poitypedel']='8003';
		
		$data['poi']['poiadd']='8004';
		$data['poi']['poiedit']='8005';
		$data['poi']['poidel']='8006';

		//事件管理
		$topdata['event']='9';
		//突发事件EventburstLogic
		$data['event']['burstadd']='9001';
		$data['event']['burstedit']='9002';
		$data['event']['burstdel']='9003';
		//计划施工
		$data['event']['planadd']='9004';
		$data['event']['planedit']='9005';
		$data['event']['plandel']='9006';
		//路况
		$data['event']['trafficadd']='9007';
		$data['event']['trafficedit']='9008';
		$data['event']['trafficdel']='9009';

		//安全常识
		$topdata['carsafetyknowledge']='10';
		$data['carsafetyknowledge']['carsafetyknowledgeadd']='10001';
		$data['carsafetyknowledge']['carsafetyknowledgeedit']='10002';
		$data['carsafetyknowledge']['carsafetyknowledgedel']='10003';

		//办事指南
		
		$topdata['guideinfo']='11';
		$data['guideinfo']['guideinfoadd']='11001';
		$data['guideinfo']['guideinfoedit']='11002';
		$data['guideinfo']['guideinfodel']='11003';
		//停车公告
		$topdata['parkinfo']='12';
		$data['parkinfo']['parkinfoadd']='12001';
		$data['parkinfo']['parkinfoedit']='12002';
		$data['parkinfo']['parkinfodel']='12003';
		if($subtype==''){
			return $topdata[$type];
		}else{
			return $data[$type][$subtype];
		}
	}
}

if (!function_exists('saveLog')) {
    function saveLog($content,$type){
        $empid = getsessionempid();
        $empname = getsessionempname();
        $depname = getsessiondepaname();
        $CI =& get_instance();
        $content = '['.$depname.']'.$empname.'--'.$content;

        $sql = 'INSERT INTO sys_adminlog (empid,empname,intime,type,content) VALUES (?,?,NOW(),?,?)';
        $CI->db->query($sql,[$empid,$empname,$type,$content]);
    }
}