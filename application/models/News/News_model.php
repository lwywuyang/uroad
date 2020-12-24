<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class News_model extends CI_Model{
	public function updateNewAdd(){
		$sql_nonew = 'update gde_news set isnewadd=0';
		$this->db->query($sql_nonew);
		if (!($this->db->affected_rows() >= 0))
			return false;//'更新表是否新增标记出错!';
		else
			return true;
	}

	/**
	 * [getNewsdata 查询图文资讯列表信息]
	 * @version 2016-05-20 3.0
	 *          log 2.0
	 *          	首先将gde_news表的isnewadd字段的新增标记全部设成'否'
	 *          	再进行查询
	 *          log 3.0
	 *          	将该逻辑独立出新方法
	 * @return  [type]                 [description]
	 */
	function getNewsdata($newstype,$startTime,$endTime,$keyword,$typeSel,$subtypeSel,$pagerOrder){
		$sql="SELECT
				a.jpgurl,
				a.id,
				a.title,
				a.intime,
				a.status,
				a.commentcount,
				a.viewcount,
				a.url,
				b.name statusname,
				a.newstype,
				a.istop,
				a.subnewstype,
				c.name subnewstypename,
				a.seq,
				a.isnewadd,
				a.html,
				a.sta
			FROM
				gde_news a
			JOIN gde_dict b on a.status=b.dictcode
			left join gde_dict c on a.subnewstype=c.dictcode 
			WHERE
				newstype = ? and status !='1012005'";
		$params=array($newstype);
		if (!isEmpty($startTime)) {
			$startTime .= ' 00:00:00';
			$sql .= ' and UNIX_TIMESTAMP(a.intime) >= UNIX_TIMESTAMP(?)';
			array_push($params,$startTime);
		}
		if (!isEmpty($endTime)) {
			$endTime .= ' 23:59:59';
			$sql .= ' and UNIX_TIMESTAMP(a.intime) <= UNIX_TIMESTAMP(?)';
			array_push($params,$endTime);
		}
		if (!isEmpty($keyword)) {
			$sql .= " and a.title like concat('%',?,'%')";
			array_push($params,$keyword);
		}
		if (!isEmpty($typeSel)) {
			$sql .= " and a.status=?";
			array_push($params,$typeSel);
		}
		if (!isEmpty($subtypeSel)) {
			$sql .= " and a.subnewstype=?";
			array_push($params,$subtypeSel);
		}
		
		$data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
		$data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);
		
		return $data;
	}
	
	/**
	 * 按照id查找数据
	 */
	function checkNewsdata($id){
		$sql="SELECT
				a.*,
				b.name status
			FROM
				gde_news a
			left JOIN gde_dict b on a.status=b.dictcode
			WHERE a.id=?";
		$params = array($id);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
	 	return isset($data[0])?$data[0]:array();
	}
    /**
     * 按照时间查找id
     */
    function save($data){
        $sql = 'update gde_news set isnewadd=0';
        $this->db->query($sql);

//        $this->db->trans_begin();

        $sql = 'SELECT name FROM gde_dict where dictcode=?';
        $typename = $this->db->query($sql,[$data['newstype']])->row_array();
        $typename = empty($typename)?'':$typename['name'];

        if(isset($data['id'])){
            $content = '修改'.$typename.'资讯 '.$data['title'];
//            $content = '修改'.$typename.'资讯 '.$data['title'].' id:'.$data['id'];
            saveLog($content,2010005);
            $this->db->update('gde_news', $data, array('id' => $data['id']));
        }else {
            $data['id']=create_guid();
            $content = '新增'.$typename.'资讯 '.$data['title'];
//            $content = '新增'.$typename.'资讯 '.$data['title'].' id:'.$data['id'];
            saveLog($content,2010005);
            $data['viewcount'] = rand(2000, 50000);
            $this->db->insert('gde_news', $data);
            //var_dump($this->db->last_query());exit;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }

	
	/**
	 * 删除
	 */
	public function del($id,$titles){
		//删除顶级
		$this->db->trans_begin();

        $content = '删除资讯 '.$titles.' id:'.$id;
        saveLog($content,2010005);
		$sql="update gde_news set status ='1012005' where id in(".$id.") ";
		$query = $this->db->query($sql);
		
		if ($this->db->trans_status() === FALSE) {
                  $this->db->trans_rollback();
                	return false;
                }else{
                    $this->db->trans_commit();
                    return true;
                   
            }
	}
	
	/**
	 * 点击发布
	 */
	public function statuschange($id,$status,$title){

		$this->db->trans_begin();
			if($status=='1012001'){
                $content = '发布资讯 '.$title.' id:'.$id;
				$sql="update gde_news set status=1012004 where id = ?";
			}else{
                $content = '取消发布资讯 '.$title.' id:'.$id;
				$sql="update gde_news set status=1012001,istop=0 where id = ?";
			}
            saveLog($content,2010005);
			
			$query = $this->db->query($sql,array($id));
		if ($this->db->trans_status() === FALSE) {
                  $this->db->trans_rollback();
                	return false;
                }else{
                    $this->db->trans_commit();
                    return true;
                   
            }
	}

	/**
	 * 查找条数
	 */
	public function checknum($newstype){
		$sql="select count(*) num from gde_news where status = '1002001' and newstype=?";
		$params=array($newstype);
		$data=$this->mysqlhelper->GetRecordBySql($sql,$params);
	 	return $data;
	}


	public function setNewsToTop($id,$title){
		$sql = 'update gde_news set istop=1 where id=?';
		$params = array($id);

        $content = '置顶资讯 '.$title.' id:'.$id;
        saveLog($content,2010005);

		return $this->mysqlhelper->ExecuteSqlParams($sql,$params);

		//return $res>0?true:false;
	}

	public function selectSubNewsType($newstype){
		$sql = 'select dictcode,name from gde_dict where codetype=?';
		$params = array($newstype);
		return $this->mysqlhelper->QueryParams($sql,$params);
	}

	/**
	 * [updateSeqToUpOrDown 对资讯的排序进行加一或减一操作]
	 * @version 2016-05-03 1.0
	 * @param   [type]     $id [description]
	 * @param   [type]     $up [description]
	 * @return  [type]         [description]
	 */
	public function updateSeqToUpOrDown($id,$up){
		if ($up == '1')
			$sql = 'update gde_news set seq=seq-1 where id=\''.$id.'\'';
		else
			$sql = 'update gde_news set seq=seq+1 where id=\''.$id.'\'';
		
		return $this->mysqlhelper->ExecuteSql($sql);
	}

	/**
	 * [getMaxSeq 查询资讯的最大排序]
	 * @version 2016-05-03 1.0
	 * @return  [type]     [description]
	 */
	public function getMaxSeq($newstype){
		$sql = 'select max(seq) maxseq from gde_news where newstype=?';
		$params = array($newstype);
		$data = $this->mysqlhelper->QueryParams($sql,$params);
		if ($data[0]['maxseq'] == 0) {
			$sql = 'select count(1) maxseq from gde_news where newstype=?';
			$data = $this->mysqlhelper->QueryParams($sql,$params);
		}
		return $data[0]['maxseq'];
	}
}
