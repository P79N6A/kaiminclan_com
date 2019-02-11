<?php
/**
 *
 * 图片裁剪
 *
 * 资源库
 *
 * 附件
 *
 */
class DocumentShapeController extends Controller {
	
	protected $accept = 'application/json';
	
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentId'=>array('type'=>'digital','tooltip'=>'附件ID','default'=>0),
			'preview'=>array('type'=>'digital','tooltip'=>'预览','default'=>0),
			'w'=>array('type'=>'digital','tooltip'=>'宽'),
			'h'=>array('type'=>'digital','tooltip'=>'高'),
			'x'=>array('type'=>'digital','tooltip'=>'X坐标','default'=>0),
			'y'=>array('type'=>'digital','tooltip'=>'Y坐标','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$w = $this->argument('w');
		$h = $this->argument('h');	
		$x = $this->argument('x');
		$y = $this->argument('y');
		
		$preview = $this->argument('preview');
		$documentId = $this->argument('documentId');
		
		$documentData = $this->service('ResourcesAttachment')->getAttachmentInfo($documentId);
		
	
		$src = __ROOT__.$documentData['attach'];
		if(!is_file($src)){
			$this->info('原始文件不存在',6001);
		}
		
		$imageInfo = getimagesize($src);
		
		if(!$imageInfo){
			$this->info('不是图片文件',6002);
		}
		list($source_w,$source_h) = $imageInfo;
		
		switch($imageInfo['mime']){
			case 'image/png': 
				$img_r = imagecreatefrompng($src);
				break;
			case 'image/jpg': 
			case 'image/jpeg': 
				$img_r = imagecreatefromjpeg($src);
				break;
		}
		
		$dst_r = imagecreatetruecolor( $w, $h );
		

	
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$source_w,$source_h,$w,$h);
		
		$newAttachFile = 0;
		
		//开启预览
		if($preview){
			header('Expires:-1');
			header('Cache-Control:no_cache');
			header('Pragma:no-cache');
			header('Content-type: '.$imageInfo['mime']);
		}else{
			$attachRealUrl = date('/Ym/d').'.'.$documentData['filetype'];
			$newAttachFile = __ATTACH__.$attachRealUrl;
		}
		
		switch($imageInfo['mime']){
			case 'image/png': 
				imagepng($dst_r,$newAttachFile);
				break;
			case 'image/jpg': 
			case 'image/jpeg': 
				imagejpeg($dst_r,$newAttachFile,100);
				break;
		}
		
		imagedestroy($img_r);
		imagedestroy($dst_r);
		
		if($preview){
			exit();
		}
		
		$this->service('ResourcesUpload')->save($newAttachFile);
		
		$this->assign('attach',$attachRealUrl);
		$this->assign('thumb',$thumbnail);
		$this->assign('document_identity',$documentId);
	}
}
?>