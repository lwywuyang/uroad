<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *新闻页面
 */
class WeatherReportLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WeatherReport/WeatherReport_model', 'WeatherReport');
		$this->load->model('Dict_model', 'dict');
		checksession();
	}

    /**
     * 列表查看
     */
    public function indexPage(){
        $this->load->view('admin/WeatherReport/WeatherReport2List');
    }

    /**
     * 查找数据
     */
    public function onLoadWeatherReport(){
        $pageOnload=page_onload();
        // 判断排序是否存在
        if($pageOnload['OrderDesc']==""){
            $pageOnload['OrderDesc'] = 'order by id desc';
        }

        $startTime = $this->input->post('startTime');
        $endTime = $this->input->post('endTime');
        $warningfrom = $this->input->post('warningfrom');

        $data = $this->WeatherReport->getWeatherReportdata($startTime, $endTime, $warningfrom, $pageOnload);


        foreach ($data['data'] as $i => $v) {
            if ($v['warningimg'] != '') {
                $warningimg = $v['warningimg'];
                $data['data'][$i]['warningimg'] = '<img src="' . $warningimg . '" width="60px" height="40px" onclick="showLayerImage(this.src)" />';
            } else {
                $data['data'][$i]['warningimg'] = '';
            }
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
			$data = $this->WeatherReport->checkWeatherReportdata($id);
		}

		$data['id'] = $id;
		$data['newstype'] = $newstype;
		//$data['subnewstypeData'] = $this->WeatherReport->selectSubWeatherReportType($newstype);
		$data['subnewstypeData'] = $this->dict->selectDict($newstype);

		if ($newstype == '1011031')
			$this->load->view('admin/WeatherReport/WeatherWordDetail',$data);
		else
			$this->load->view('admin/WeatherReport/WeatherReportEdit',$data);
	}

	public function detailHtml(){

		$id=$this->input->get('id');
		$newstype=$this->input->get('newstype');

		if($id=='0'){

		}else{
			$data = $this->WeatherReport->checkWeatherReportdata($id);
		}

		$data['id'] = $id;
		$data['newstype'] = $newstype;
		//$data['subnewstypeData'] = $this->WeatherReport->selectSubWeatherReportType($newstype);
		$data['subnewstypeData'] = $this->dict->selectDict($newstype);

		if ($newstype == '1011031')
			$this->load->view('admin/WeatherReport/WeatherHtmlDetail',$data);
		else
			$this->load->view('admin/WeatherReport/WeatherReportEdit',$data);
	}
	
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

		if ($data['newstype'] != '1011031') {
			$data['smallhtml'] = $this->getSmallHtml($data['html']);
		}
		
		//var_dump($smallhtml);exit;

		if($this->WeatherReport->save($data)===true)
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
	public function delWeatherReport(){
		$Oid = $this->input->post('OID');
		$operatetime=date('Y-m-d H:i:s',time());
		$operator=getsessionempname();
		$isSuccess=$this->WeatherReport->del($Oid);

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
		
		$isSuccess=$this->WeatherReport->statuschange($id,$status);

		//返回success
		if($isSuccess)
			ajax_success(NULL,NULL);
		else
			ajax_error('失败');
	}

	public function pushWeatherReportToTop(){
		$id = $this->input->post('id');

		$res = $this->WeatherReport->setWeatherReportToTop($id);

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('置顶操作失败!');
	}

	public function putUpOrDown(){
		$id = $this->input->post('id');
		$up = $this->input->post('up');

		$res = $this->WeatherReport->updateSeqToUpOrDown($id,$up);

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
		$data['html']=$this->input->post('html');
		$data['newstype']=$this->input->post('newstype');
		$data['jpgurl']=$this->input->post('jpgurl');

		if($this->WeatherReport->save($data)===true)
			ajax_success(true,NULL);
		else
			ajax_error('保存失败');
	}

}
