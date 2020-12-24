<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uploadimg extends CI_Controller {

	/**
	 * 保存上传图片 
	 */
	public function upload(){

		header('Content-type: image/jpg');
		$imagefile=$_FILES['file'];
		$a=date('YmdHis').substr(microtime(), 2, 3);
		$upFilePath = '../GSTHuNanAdmin/img/'.$a.'.jpg';


			if(isset($_FILES['file']['tmp_name'])){
				if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
					$upFilePathsmall='img/'.$a.'_small'.'.jpg';
					$size= getimagesize($upFilePath);
					$w=$size[0];
					$h=$size[1];
					
					$w1=$w*0.3;
					$h1=$h*0.3;
					if($w1<200){
						$w1=200;
						$h1=$h*($w1/$w);
					}


					mkthumbnail($upFilePath, $w1, $h1,$upFilePathsmall); 

					// get_small_image($upFilePath,$upFilePathsmall);
					// imagezoom($upFilePath, $upFilePathsmall, 100, 100, '#FFFFFF'); 
					echo $upFilePath.','.$upFilePathsmall;	
				}else{

				}
			}
			
	}
	/**
	 * 保存多张上传图片
	 */
	public function uploadmore(){

		header('Content-type: image/jpg');
		$this->load->helper('mkthumbnail');
		$imagefile=$_FILES['file'];
		$a=date('YmdHis').substr(microtime(), 2, 3);
		$upFilePath = '../GSTHuNanAdmin/img/'.$a.'.jpg';
			if(isset($_FILES['file']['tmp_name'])){
				if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
					$upFilePathsmall='img/'.$a.'_small'.'.jpg';
					$size= getimagesize($upFilePath);
					$w=$size[0];
					$h=$size[1];
					
					$w1=$w*0.3;
					$h1=$h*0.3;
					if($w1<200){
						$w1=200;
						$h1=$h*($w1/$w);
					}


					mkthumbnail($upFilePath, $w1, $h1,$upFilePathsmall); 

					// get_small_image($upFilePath,$upFilePathsmall);
					// imagezoom($upFilePath, $upFilePathsmall, 100, 100, '#FFFFFF'); 
					echo $upFilePath.','.$upFilePathsmall;	
				}else{

				}
			}
			
	}

	// 无缩略图
	public function uploadser(){

		
		header('Content-type: image/jpg');
		$imagefile=$_FILES['file'];
		
		$a=date('YmdHis').substr(microtime(), 2, 3);
		$imgurl=$this->config->item("img_url");
		$upFilePath = 'img/'.$a.'.jpg';
		$upurl =$imgurl.$upFilePath;
		$url=array(
					'upFilePath'=>$upFilePath,
					'upurl'=>$upurl
				);

		$upFilePath = $url['upFilePath'];
		$upurl = $url['upurl'];
			if(isset($_FILES['file']['tmp_name'])){
				if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
					echo $upurl;	
				}
			   }else{

			}
	}

	// 无缩略图,监控快拍上传图片
	public function uploadser1(){

		header('Content-type: image/jpg');
		$imagefile=$_FILES['file'];
		
		$a=date('YmdHis').substr(microtime(), 2, 3);
		//$imgurl=$this->config->item("img_url");
		$upFilePath = 'cctv/'.$a.'.jpg';
		//$upurl = $imgurl.$upFilePath;
		$upurl = 'http://hunangstapi.u-road.com/HuNanGSTAppAPIServer/images/'.$upFilePath;
		$url=array(
			'upFilePath'=>$upFilePath,
			'upurl'=>$upurl
		);

		$upFilePath = '../HuNanGSTAppAPIServer/images/cctv/'.$a.'.jpg';
		$upurl = $url['upurl'];
			if(isset($_FILES['file']['tmp_name'])){
				if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
					echo $upurl;
				}
		   	}else{

			}
	}
			
	/**
	 * 保存多张上传图片
	 */
	public function uploadmoreser(){

		header('Content-type: image/jpg');
		
		$imagefile=$_FILES['file'];
		$type=$this->input->get('type');

		$this->load->helper('uploadtype');
		
		$url=uploadname($type);

		$upFilePath = $url['upFilePath'];
		$upurl = $url['upurl'];
			if(isset($_FILES['file']['tmp_name'])){
				if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
					echo $upurl;
					}	
				}else{

				}
			}
	// / 分类
		public function uploadclassser(){

			header('Content-type: image/jpg');
			$imagefile=$_FILES['file'];
			$type=$this->input->get('type');

			$this->load->helper('uploadtype');
			
			$url=uploadname($type);

			$upFilePath = $url['upFilePath'];
			$upurl = $url['upurl'];
			
				if(isset($_FILES['file']['tmp_name'])){
					if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
						echo $upurl;	
					}
					}else{

					}
				}


	/**
	 * [angularFileUpload 上传插件上传的文件]
	 * @version 2016-09-06 1.0
	 * @return  [type]     [description]
	 */
	public function angularFileUpload(){
		header("content-type:text/html;charset=utf-8");
		$file = $_FILES['file'];

		$fileNameArray = explode('.', $file['name']);
		//获取当前毫秒数
		list($s1, $s2) = explode(' ', microtime());
		$millisecond = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
		$newName = date('YmdHis').substr($millisecond, 0, 2);

		$baseUrl = $this->config->item("img_url");

		//http://hunangstapi.u-road.com/GSTHuNanAdmin/fileUpload/23206.jpg
		$upFilePath = 'fileUpload/word/'.$newName.'_'.$fileNameArray[0].'.'.$fileNameArray[1];
		/*$htmlFilePath = 'fileUpload/html/'.$newName.'_'.$fileNameArray[0].'.html';
		$upurl = $baseUrl.$htmlFilePath;*/
		$upurl = $baseUrl.$upFilePath;
		/*$url = array(
			'upFilePath'=>$upFilePath,
			'upurl'=>$upurl
		);*/

		if(isset($_FILES['file']['tmp_name'])){
			if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
				//上传成功,转html
				//$this->word2html($upFilePath,$htmlFilePath);
				//
				echo $upurl;
			}else
				echo 'failure';
		}else{
			echo 'failure';
		}
	}


	/**
	 * [word2html word转化成html]
	 * @version 2016-09-06 1.0
	 */
	private function word2html($wordname,$htmlname){
		$doc = '/home/wwwroot/GSTHuNanAdmin/'.$wordname;///home/wwwroot/GSTHuNanAdmin/fileUpload
		$html = '/home/wwwroot/GSTHuNanAdmin/'.$htmlname;
		$command = 'java -jar /home/apps/jodconverter-2.2.2/lib/jodconverter-cli-2.2.2.jar '.$doc.' '.$html.' 2>&1';
//var_dump($command);exit;
		$log = '';
		$status = 1;
		exec($command,$log,$status);
		if ($status != 0) {
			print_r($log);
			/*var_dump($command.$status);
			var_dump($log);*/
		}
	}


	/**
	 * [getHtml 获取上传后或转换成html的文件的内容]
	 * @version 2016-09-21 1.0
	 * @return  [type]     [description]
	 */
	public function getHtml(){
		$url = $this->input->post('url');

		$url = str_replace('http://hunangstapi.u-road.com/GSTHuNanAdmin', '.', $url);

		if (file_exists($url)) {
			$content = file_get_contents($url);

			//将其中的图片的路径进行转换，使获得完整路径
			$content = str_replace('<IMG SRC="', '<IMG SRC="http://hunangstapi.u-road.com/GSTHuNanAdmin/fileUpload/html/', $content);

			$outputarray = array('status' => 'Success', 'data' => $content);

		}else{
			$outputarray = array('status' => 'Failure', 'data' => '未能找到转换后的HTML文件，请重试!');
		}

		echo json_encode($outputarray);

	}



	/**
	 * [angularHtmlFileUpload 上传插件上传的文件]
	 * @version 2016-09-06 1.0
	 * @return  [type]     [description]
	 */
	public function angularHtmlFileUpload(){
		header("content-type:text/html;charset=utf-8");
		$file = $_FILES['file'];

		$fileNameArray = explode('.', $file['name']);
		//获取当前毫秒数
		list($s1, $s2) = explode(' ', microtime());
		$millisecond = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
		$newName = date('YmdHis').substr($millisecond, 0, 2);

		$baseUrl = $this->config->item("img_url");

		$upFilePath = 'fileUpload/html/'.$newName.'_'.$fileNameArray[0].'.'.$fileNameArray[1];
		//$htmlFilePath = 'fileUpload/html/'.$newName.'_'.$fileNameArray[0].'.html';
		//$upurl = $baseUrl.$htmlFilePath;
		$upurl = $baseUrl.$upFilePath;
		$url = array(
			'upFilePath'=>$upFilePath,
			'upurl'=>$upurl
		);

		if(isset($_FILES['file']['tmp_name'])){
			if(move_uploaded_file($_FILES['file']['tmp_name'],$upFilePath)){
				//上传成功,转html
				//$this->word2html($upFilePath,$htmlFilePath);
				//
				echo $upurl;
			}else
				echo 'failure';
		}else{
			echo 'failure';
		}
	}
}