<?php
/**
 *
 * 文件上传
 *
 * 资源库
 *
 * 附件
 * 20180301
 *
 */
class DocumentUploadController extends Controller {
	
	protected $accept = 'application/json';
	
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'documentId'=>array('type'=>'digital','tooltip'=>'附件ID','default'=>0),
			'thumbnail'=>array('type'=>'digital','tooltip'=>'缩略图','default'=>0),
			'watermark'=>array('type'=>'digital','tooltip'=>'水印','default'=>0),
			'chunk'=>array('type'=>'digital','tooltip'=>'分块','default'=>0),
			'chunks'=>array('type'=>'digital','tooltip'=>'块数','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$watermark = $this->argument('watermark');
		$thumbnail = $this->argument('thumbnail');
		$document_identity = $this->argument('documentId');
		
		$chunk = $this->argument('chunk');
		$chunks = $this->argument('chunks');
		
		// Settings
		$temp_dir = ini_get('upload_tmp_dir');
		if(!$temp_dir)
		{
			$temp_dir = __STORAGE__.'/tmp';
		}
		$targetDir = $temp_dir . DIRECTORY_SEPARATOR . 'plupload';
		//$targetDir = 'uploads';
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
	
		// Create target dir
		if (!is_dir($targetDir)) {
			@mkdir($targetDir,0777,1);
		}

		// Get a file name
		if (!empty($_FILES)) {
			$fileName = $_FILES["filedata"]["name"];
		} else {
			$fileName = uniqid("file_");
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		
		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {				
				$msg = 'Failed to open temp directory';
				$this->assign('jsonrpc',2.0);
				$this->assign('id','id');
				$this->assign('error',array('code'=>100,'message'=>$msg));
				$this->info($msg,100);
			}
		
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
		
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}.part") {
					continue;
				}
		
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
					@unlink($tmpfilePath);
				}
			}
			closedir($dir);
		}	
		
		
		// Open temp file
		if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
				$msg = 'Failed to open output stream.';
				$this->assign('jsonrpc',2.0);
				$this->assign('id','id');
				$this->assign('error',array('code'=>102,'message'=>$msg));
				$this->info($msg,102);
		}
		
		if (!empty($_FILES)) {
			if ($_FILES["filedata"]["error"] || !is_uploaded_file($_FILES["filedata"]["tmp_name"])) {
				$msg = 'Failed to move uploaded file.';
				$this->assign('jsonrpc',2.0);
				$this->assign('id','id');
				$this->assign('error',array('code'=>103,'message'=>$msg));
				$this->info($msg,103);
			}
		
			// Read binary input stream and append it to temp file
			if (!$in = @fopen($_FILES["filedata"]["tmp_name"], "rb")) {
				$msg = 'Failed to open input stream.';
				$this->assign('jsonrpc',2.0);
				$this->assign('id','id');
				$this->assign('error',array('code'=>101,'message'=>$msg));
				$this->info($msg,101);
			}
		} else {	
			if (!$in = @fopen("php://input", "rb")) {
				$msg = 'Failed to open input stream.';
				$this->assign('jsonrpc',2.0);
				$this->assign('id','id');
				$this->assign('error',array('code'=>101,'message'=>$msg));
				$this->info($msg,101);
			}
		}
		
		while ($buff = fread($in, 4096)) {
			fwrite($out, $buff);
		}
		
		
		@fclose($out);
		@fclose($in);
		
		if (!$chunks || $chunk == $chunks - 1) {
			rename("{$filePath}.part", $filePath);
		}
		
		
		$attach_dir = __DATA__.'/attachment';
		$attach_url = str_replace(__ROOT__,'',$attach_dir);
        $document_data = array();
		if($document_identity){
            $where  = array();
            $where['identity'] = $document_identity;
            $document_data = $this->model('AttachmentDocument')->where($where)->find();
        }
		if($document_data)
		{
			$folder = $attach_dir.'/'.dirname($document_data['attach']);
			$_filename = basename($document_data['attach']);
			$file_arr = explode('.',$_filename);
			$target = $attach_dir.'/'.$document_data['attach'];
		}else{
			$folder = $attach_dir.'/'.date('Ym').'/'.date('d');
			if(!is_dir($folder))
			{
				mkdir($folder,0777,1);
			}
			$file_arr = explode('.',$fileName);
		
			$_temp_name = md5(time().$fileName.mt_rand(1,999999999).$this->getUID());
			$_temp_end = $file_arr[count($file_arr)-1];
			$target = $folder.'/'.$_temp_name.'.'.$_temp_end;
		}
		$source = $filePath;
		
		if(is_file($target))
		{
			rename($target,$target.'.bak_'.$this->getUID());
		}
		
		
		$targetList = explode('.',$target);
		$len = count($targetList)-1;
		$fileType = $targetList[$len];
		
		unset($targetList[$len]);
		
		rename($source,$target);
		
		$serverId = $this->service('AttachmentServer')->getAvailableServerId();
		$attachData = array(
			'remote'=>0,
			'attach'=>str_replace($attach_dir,'',$target),
			'server_identity'=>$serverId,
			'filename'=>$_FILES["filedata"]["name"],
			'filesize'=>$_FILES["filedata"]["size"],
			'filetype'=>$fileType,
		);
		
		$documentId = $this->service('AttachmentDocument')->insert($attachData);
		
		$urlData = $this->service('AttachmentDocument')->getAttachUrl($documentId);
		
		$this->assign('jsonrpc',2.0);
		$this->assign('width',$width);
		$this->assign('height',$hegiht);
		$this->assign('attach',$urlData[$documentId]['attach']);
		$this->assign('thumb',$urlData[$documentId]['attach']['thumb']);
		$this->assign('document_identity',$documentId);
	}
}
?>