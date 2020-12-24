<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *新闻页面
 */
class NewsLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('News/News_model', 'News');
		$this->load->model('Dict_model', 'dict');
		checksession();
	}

	public function updateNews()
    {
        $this->load->database();
        $sql = "SELECT id as apple, intime from gde_news WHERE newstype != 1011031 and intime < '2019-07-02 11:21:23';";
        $result = $this->db->query($sql, []);
        $result = $result->result_array();

        foreach ($result as $item)
        {
            $sql = "UPDATE `gde_news` SET intime=FROM_UNIXTIME(UNIX_TIMESTAMP('".$item['intime']."')+31536000,'%Y-%m-%d %H:%i:%s') where id='".$item['apple']."'";
            $result = $this->db->query($sql, []);
        }

    }

	/**
	 * 列表查看
	 */
	public function indexPage(){

		$data['newstype']=$this->input->get('newstype');

		if ($data['newstype'] == '1011003' || $data['newstype'] == '1011008') {
			$data['subnewstype'] = $this->News->selectSubNewsType($data['newstype']);
		}

		$res = $this->News->updateNewAdd();
		if ($res === false) {
			$data['error'] = '更新表是否新增标记出错';
		}
		$this->load->view('admin/News/NewsList',$data);
	}

	/**
	 * 查找数据
	 */
	public function onLoadNews(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc'] = 'order by isnewadd desc,istop desc,seq asc,intime desc';
		}
		$newstype=$this->input->post('newstype');
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		$keyword = $this->input->post('keyword');
		$typeSel = $this->input->post('typeSel');
		$subtypeSel = $this->input->post('subtypeSel');

		$data = $this->News->getNewsdata($newstype,$startTime,$endTime,$keyword,$typeSel,$subtypeSel,$pageOnload);

		/*if ($data ===false) {
			ajax_error('更新表是否新增标记出错!');
		}*/
		$maxSeq = $this->News->getMaxSeq($newstype);
		$newstypeArray = array('1006008','1011018','1011013','1011015','1011016','1011017');

		$url=$this->config->item("img_url");

		$newadd = '';
		foreach ($data['data'] as $i => $v) {
			if ($v['isnewadd'] == '1') {
				$newadd = '<span style="margin-right:5px; color:red;">New</span>';
			}
			if($v['jpgurl']!=''){
				$jpgurl = $v['jpgurl'];
				$data['data'][$i]['jpgurl']='<img src="'.$jpgurl.'" width="60px" height="40px" onclick="showLayerImage(this.src)" />';
			}else{
				$data['data'][$i]['jpgurl']='';
			}

			$title = '';
			if ($v['subnewstype'] == '2006001' || $v['subnewstype'] == '2007001') {
				$title = '<span class="red-font">'.$v['subnewstypename'].'>></span>';
			}else if($v['subnewstype'] == '2006002' || $v['subnewstype'] == '2007002'){
				$title = '<span class="green-font">'.$v['subnewstypename'].'>></span>';
			}else if($v['subnewstype'] == '2006003' || $v['subnewstype'] == '2007003'){
				$title = '<span class="blue-font">'.$v['subnewstypename'].'>></span>';
			}

			if($v['status']=='1012001'){
				$data['data'][$i]['statuschange'] = '<lable class="btn btn-success btn-xs" onclick="statuschange(\''.$v['id'].'\',\''.$v['status'].'\',\''.$v['title'].'\')">发布</lable><lable class="btn btn-success btn-xs" onclick="detail(\''.$v['id'].'\')">修改</lable>';
				//<lable class="btn btn-info btn-xs" onclick="read(\''.$v['id'].'\')">预览</lable>

				$data['data'][$i]['title'] = $newadd.$title.$v['title'];

			}else if($v['status']=='1012004' && $v['istop']=='1') {
				$data['data'][$i]['statuschange'] = '<lable class="btn btn-warning btn-xs" onclick="statuschange(\''.$v['id'].'\',\''.$v['status'].'\',\''.$v['title'].'\')">取消发布</lable><lable class="btn btn-success btn-xs" onclick="detail(\''.$v['id'].'\')">修改</lable>';
				//<lable class="btn btn-info btn-xs"  onclick="read(\''.$v['id'].'\')">预览</lable>

				$data['data'][$i]['title'] = '<img src="'.$url.'asset/images/top.gif" style="margin-right:5px;">'.$title.$v['title'];

			}else if($v['status']=='1012004'){//&& $v['istop']=='0'
				$data['data'][$i]['statuschange'] = '<lable class="btn btn-warning btn-xs" onclick="statuschange(\''.$v['id'].'\',\''.$v['status'].'\',\''.$v['title'].'\')">取消发布</lable><lable class="btn btn-danger btn-xs" onclick="pushTop(\''.$v['id'].'\',\''.$v['title'].'\')">置顶</lable><lable class="btn btn-success btn-xs" onclick="detail(\''.$v['id'].'\')">修改</lable>';
				//<lable class="btn btn-info btn-xs"  onclick="read(\''.$v['id'].'\')">预览</lable>

				$data['data'][$i]['title'] = $title.$v['title'];
			}

			if ($v['html'] == '' || is_null($v['html'])) {
				$data['data'][$i]['statuschange'] .= '<lable class="btn btn-info btn-xs"  onclick="read(\''.$v['id'].'\',\''.$v['url'].'\')">预览</lable>';
			}else{
				$data['data'][$i]['statuschange'] .= '<lable class="btn btn-info btn-xs"  onclick="read(\''.$v['id'].'\',\'\')">预览</lable>';
			}


			/*if (in_array($v['newstype'], $newstypeArray)) {*/
				if ($v['seq'] != 0) {
					$data['data'][$i]['statuschange'] .= '<button class="btn btn-primary btn-xs" onclick="putUpOrDown(\''.$v['id'].'\',1)"><span class="glyphicon glyphicon-arrow-up"></span>上移</button>';
				}
				if ($v['seq'] != $maxSeq) {
					$data['data'][$i]['statuschange'] .= '<button class="btn btn-primary btn-xs" onclick="putUpOrDown(\''.$v['id'].'\',0)"><span class="glyphicon glyphicon-arrow-down"></span>下移</button>';
				}
			/*}*/

		}

		ajax_success($data['data'],$data["PagerOrder"]);
	}



	/**
	 * 编辑添加
	 */
	public function detail(){

		$id=$this->input->get('id');
		$newstype=$this->input->get('newstype');

		if($id=='0'){

		}else{
			$data = $this->News->checkNewsdata($id);
		}

		$data['id'] = $id;
		$data['newstype'] = $newstype;
		//$data['subnewstypeData'] = $this->News->selectSubNewsType($newstype);
		$data['subnewstypeData'] = $this->dict->selectDict($newstype);

		if ($newstype == '1011031')
			$this->load->view('admin/WeatherReport/WeatherWordDetail2',$data);
		else
			$this->load->view('admin/News/NewsEdit',$data);
	}

	/*public function detailHtml(){

		$id=$this->input->get('id');
		$newstype=$this->input->get('newstype');

		if($id=='0'){

		}else{
			$data = $this->News->checkNewsdata($id);
		}

		$data['id'] = $id;
		$data['newstype'] = $newstype;
		//$data['subnewstypeData'] = $this->News->selectSubNewsType($newstype);
		$data['subnewstypeData'] = $this->dict->selectDict($newstype);

		if ($newstype == '1011031')
			$this->load->view('admin/WeatherReport/WeatherHtmlDetail',$data);
		else
			$this->load->view('admin/News/NewsEdit',$data);
	}*/

	/**
	 * 保存操作
	 */
	public function onSave(){
		//提取前台数据
		$id=$this->input->post('id');
		if($id=="0"){
			$data['intime']=date('Y-m-d H:i:s',time());
			$data['status']='1012001';
			$data['isnewadd'] = 1;
		}else{
			$data['id']=$id;
			$data['updatetime']=date('Y-m-d H:i:s',time());
			$data['isnewadd'] = 0;
		}
		$data['title']=$this->input->post('title');
		$data['linktype'] = $this->input->post('type');
		$data['html']=$this->input->post('html');
		$data['url'] = $this->input->post('url');
		$data['newstype']=$this->input->post('newstype');
		$data['jpgurl']=$this->input->post('jpgurl');
		$data['longitude']=$this->input->post('longitude');
		$data['latitude']=$this->input->post('latitude');
		//$data['seq'] = $this->input->post('seq');
		$data['subnewstype'] = $this->input->post('subtypeSel');
		$data['sta'] = $this->input->post('sta');

//		if ($data['newstype'] != '1011031') {
//			$data['smallhtml'] = $this->getSmallHtml($data['html']);
//		}

		//var_dump($smallhtml);exit;

		if($this->News->save($data)===true)
			ajax_success(true,NULL);
		else
			ajax_error('保存失败');
	}

    /**
     * 保存操作
     */
    public function onSave2(){
        //提取前台数据
        $id=$this->input->post('id');
        if($id=="0"){
            $data['intime']=date('Y-m-d H:i:s',time());
            $data['status']='1012001';
            $data['isnewadd'] = 1;
        }else{
            $data['id']=$id;
            $data['updatetime']=date('Y-m-d H:i:s',time());
            $data['isnewadd'] = 0;
        }
        $data['title']=$this->input->post('title');
        $data['linktype'] = $this->input->post('type');
        $data['html']=$this->input->post('html');
        $data['url'] = $this->input->post('url');
        $data['newstype']=$this->input->post('newstype');
        $data['jpgurl']=$this->input->post('jpgurl');
        $data['longitude']=$this->input->post('longitude');
        $data['latitude']=$this->input->post('latitude');
        //$data['seq'] = $this->input->post('seq');
        $data['subnewstype'] = $this->input->post('subtypeSel');

//		if ($data['newstype'] != '1011031') {
//			$data['smallhtml'] = $this->getSmallHtml($data['html']);
//		}

        //var_dump($smallhtml);exit;

        if($this->News->save($data)===true)
            ajax_success(true,NULL);
        else
            ajax_error('保存失败');
    }

	private function getSmallHtml($html){
		$pattern = '#(http)(.*?)"#';

		preg_match_all($pattern,$html,$matchs);

		foreach ($matchs[0] as $key => $value) {
			$value = substr($value,0,-1);

			$slashWhere = strrpos($value,'/');

			$imageName = substr($value,$slashWhere+1);
			$imageArr = explode('.', $imageName);
			if (isset($imageArr[1])) {
				//判断是否是图片
				if ($this->checkImage($imageArr[1])) {

					$smallImage = $imageArr[0].'_small.'.$imageArr[1];
					$smallUrl = './ueditorupload/image/'.$smallImage;

					$this->get_small_image($value,$smallUrl);

					$html = str_replace($imageName, $smallImage, $html);
				}
			}

		}

		return $html;
	}

	private function checkImage($string){

		if ($string != 'jpg' && $string != 'png' && $string != 'jpeg') {
			return false;
		}
		return true;
	}

	/**
    * desription 压缩图片
    * @param sting $imgsrc 图片路径
    * @param string $imgdst 压缩后保存路径
    */
    private function get_small_image($imgsrc,$imgdst){

        list($width,$height,$type) = getimagesize($imgsrc);
        $new_width = ceil($width>500?($width*0.5):$width);
        $new_height = ceil($height>500?($width*0.5):$height);
        switch($type){
            case 1:
                $giftype = $this->check_gifcartoon($imgsrc);
                if($giftype){
                  header('Content-Type:image/gif');
                  $image_wp = imagecreatetruecolor($new_width,$new_height);
                  $image = imagecreatefromgif($imgsrc);
                  imagecopyresampled($image_wp,$image,0,0,0,0,$new_width,$new_height,$width,$height);
                  imagegif($image_wp,$imgdst);
                  imagedestroy($image_wp);
                }
                break;
            case 2:
                header('Content-Type:image/jpeg');
                $image_wp = imagecreatetruecolor($new_width,$new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp,$image,0,0,0,0,$new_width,$new_height,$width,$height);
                imagejpeg($image_wp,$imgdst);
                imagedestroy($image_wp);
                break;
            case 3:
                header('Content-Type:image/png');
                $image_wp = imagecreatetruecolor($new_width,$new_height);
                $image = imagecreatefrompng($imgsrc);
                imagecopyresampled($image_wp,$image,0,0,0,0,$new_width,$new_height,$width,$height);
                imagepng($image_wp,$imgdst);
                imagedestroy($image_wp);
                break;
            default:break;
        }
    }

    /**
    * desription 判断是否gif动画
    * @param sting $image_file 图片路径
    * @return boolean t 是 f 否
    */
    private function check_gifcartoon($image_file){
        $fp = fopen($image_file,'rb');
        $image_head = fread($fp,1024);
        fclose($fp);
        return preg_match("/".chr(0x21).chr(0xff).chr(0x0b).'NETSCAPE2.0'."/",$image_head)?false:true;
    }


	/**
	 * 删除
	 */
	public function delNews(){
		$Oid = $this->input->post('OID');
		$titles = $this->input->post('titles');
		$operatetime=date('Y-m-d H:i:s',time());
		$operator=getsessionempname();
		$isSuccess=$this->News->del($Oid,$titles);

		//返回success
		if($isSuccess)
			ajax_success(NULL,NULL);
		else
			ajax_error('失败');
	}
	/**
	 * 点发布
	 */
	public function statuschange(){
		$newstype=$this->input->post('newstype');
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$title = $this->input->post('title');

		$isSuccess=$this->News->statuschange($id,$status,$title);

		//返回success
		if($isSuccess)
			ajax_success(NULL,NULL);
		else
			ajax_error('失败');
	}

	public function pushNewsToTop(){
		$id = $this->input->post('id');
		$title = $this->input->post('title');

		$res = $this->News->setNewsToTop($id,$title);

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('置顶操作失败!');
	}

	public function putUpOrDown(){
		$id = $this->input->post('id');
		$up = $this->input->post('up');

		$res = $this->News->updateSeqToUpOrDown($id,$up);

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('修改内容排序出错');
	}

	/**
	 * [saveWeather 保存天气预报详情]
	 * @version 2016-09-21 1.0
	 * @return  [type]     [description]
	 */
	public function saveWeather(){
		$id=$this->input->post('id');
		if($id=="0"){
			$data['intime']=date('Y-m-d H:i:s',time());
			$data['status']='1012001';
			$data['isnewadd'] = 1;
		}else{
			$data['id']=$id;
			$data['updatetime']=date('Y-m-d H:i:s',time());
			$data['isnewadd'] = 0;
		}
		$data['title']=$this->input->post('title');
		$url=$this->input->post('url');
		$data['url']='http://ow365.cn/?i=11621&furl='.$url;
		$data['linktype']=1;
		$data['newstype']=$this->input->post('newstype');
		$data['jpgurl']=$this->input->post('jpgurl');

		if($this->News->save($data)===true)
			ajax_success(true,NULL);
		else
			ajax_error('保存失败');
	}

}
