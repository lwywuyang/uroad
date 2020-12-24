<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class role_model extends CI_Model{
 	/**
	  * 增加
	  */
	public function insert($form,$data)
		{
			//新增
			$this->db->insert($form,$data);
		}
	public function update($form,$data,$id)
	{
		//编辑
		$this->db->update($form,$data,array('ID'=>$id));

	}
	
	public function save($form,$data)
	{
		if(isset($data["id"]))
		{
			$this->update($form,$data,$data['id']);
		}
		else
		{
			$this->insert($form,$data);
		}
	}
    //分页查找角色
    function getAllRole($key,$pagerOrder,$CompanyID){
    	$sql="select * from sys_role where 1=1";	
		$params=array();
		if(!isEmpty($key)){
			$sql.=" and RoleName like concat(?,'%')";
			array_push($params, $key);
		}
		if(!isEmpty($CompanyID)){
			$sql.=" and CompanyID = ?";
			array_push($params, $CompanyID);
		}
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		return $data;
    }
    /**
     * 根据Id查找数据
     */
    function checkRoleId($id){
    	$sql="select * from  sys_role  where ID = ? ";
		$data=$this->db->query($sql,array($id))->result_array(); 		
		return $data;
    }
    /**
     * 删除角色
     */
    public function delrole($id){
		//删除顶级
		 $sql="delete from sys_role where ID=$id ";
		 $query=$this->db->query($sql); 

	}

}

?>