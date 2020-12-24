<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class WeatherReport_model extends CI_Model{
	public function updateNewAdd(){
		$sql_nonew = 'update gde_news set isnewadd=0';
		$this->db->query($sql_nonew);
		if (!($this->db->affected_rows() >= 0))
			return false;//'更新表是否新增标记出错!';
		else
			return true;
	}

    /**
     * [getWeatherReportdata 查询图文资讯列表信息]
     * @version 2016-05-20 3.0
     *          log 2.0
     *          	首先将gde_news表的isnewadd字段的新增标记全部设成'否'
     *          	再进行查询
     *          log 3.0
     *          	将该逻辑独立出新方法
     * @return  [type]                 [description]
     */
    function getWeatherReportdata($startTime, $endTime, $warningfrom, $pagerOrder){
        $sql="SELECT id,warningimg, warningfrom,warnningstate, warnningtext, created FROM gde_weatherwarning where 1 = 1";


        $params=array();
        if (!isEmpty($startTime)) {
            $startTime .= ' 00:00:00';
            $sql .= ' and UNIX_TIMESTAMP(created) >= UNIX_TIMESTAMP(?)';
            array_push($params,$startTime);
        }
        if (!isEmpty($endTime)) {
            $endTime .= ' 23:59:59';
            $sql .= ' and UNIX_TIMESTAMP(created) <= UNIX_TIMESTAMP(?)';
            array_push($params,$endTime);
        }
        if (!isEmpty($warningfrom)) {
            $sql .= " and warningfrom like concat('%',?,'%')";
            array_push($params,$warningfrom);
        }

        $data['data']=$this->mysqlhelper->QueryPage($sql,$params,$pagerOrder);
        $data['PagerOrder']=$this->mysqlhelper->GetPageOrder($sql,$params,$pagerOrder);

        return $data;
    }

    /**
	 * 按照id查找数据
	 */
	function checkWeatherReportdata($id){
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

		$this->db->trans_begin();

		if(isset($data['id'])){
			$this->db->update('gde_news', $data, array('id' => $data['id']));
		}else {
			$data['id']=create_guid();
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
	public function del($id){
		//删除顶级
		$this->db->trans_begin();
		
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
	public function statuschange($id,$status){
		$this->db->trans_begin();
			if($status=='1012001'){
				$sql="update gde_news set status=1012004 where id = ?";
			}else{
				$sql="update gde_news set status=1012001,istop=0 where id = ?";
			}
			
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


	public function setWeatherReportToTop($id){
		$sql = 'update gde_news set istop=1 where id=?';
		$params = array($id);

		return $this->mysqlhelper->ExecuteSqlParams($sql,$params);

		//return $res>0?true:false;
	}

	public function selectSubWeatherReportType($newstype){
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