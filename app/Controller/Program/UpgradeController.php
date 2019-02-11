<?php
/***
 * 这是一个代码更新插件
 *
 * 开始->版本检测->读取文件信息->文件下载->权限验证->旧版备份->文件覆盖->结束
 */

class UpgradeController extends Controller
{
	private $setting = __ROOT__.'/app/domain.xml';
	//本地配置
	private $config = '';
	
	
	//新文件包
	private $newZipArchives = null;
	//备份文件包
	private $backupZipArchives = null;
	
	//新文件
	private  $newFile = '';
	//备份文件列表
	private $backFiles = array();
	
	//初始化资源
	public function __construct(){
		
		$this->checkVersion();
	}
	
	/**
	 * 检测版本
	 */
	public function checkVersion(){
		
		
		$domainFile = $this->setting;
		if(!is_file($domainFile)){
			die('不存在的文件');
		}
		
		$appList = simplexml_load_file($domainFile);
		
		
		foreach($appList->program as $key=>$appInfo){
			
			//更新地址
			$upgradeUri = (string)$appInfo->upgrade;
			
			$param = array(
				'host'=>(string)$appList->name,
				'name'=>(string)$appInfo->name,
				'license'=>(string)$appList->license
			);
			
			$param['sign'] = md5($param['host'].date('H').$param['license'].date('Ymd'));
			
			$newVersionData = $this->helper('curl')->init($upgradeUri)->data($param)->post();
			$newVersionData = json_decode($newVersionData,true);
			
			if($newVersionData && $newVersionData['status'] == 200){
				$newVersionData = $newVersionData['data'];
			}else{
				var_dump('upgrade failed');
				continue;
			}
			
			if($newVersionData['version'] > (int)$appInfo->version){
				//线上版本大于本地版，发起文件更新任务
				if($newVersionData['filesize'] > 1024*1024*5){
					//大于5M，手动更新
					
				}else{
					//自动更新
					$newFile = $this->downFile($newVersionData['file']);
					
					$fileData = pathinfo($newFile);
					$this->newFile = $newFile;
					$this->replaceFile($param['name'],'version',$newVersionData['version']);
				}
			}
		}
	}
	
	private function changeXml($app,$field,$value){
		$doc = new DOMDocument(); 
		$doc->load($this->setting); 
		//查找 videos 节点 
		$root = $doc->getElementsByTagName('program'); 
		//遍历record节点的集合
		foreach($root as $record){
			if($record->getElementsByTagName('name')->item(0)->nodeValue == $app){
				$record->getElementsByTagName($field)->item(0)->nodeValue = $value;
			}
		}
		
		$doc->save($this->setting);
	}
	
	private function helper($helperName){
		return new $helperName;
	}
	
	/**
	 * 文件覆盖
	 */
	public function replaceFile($app,$field,$value){
		$this->newZipArchives = new ZipArchive;		
		
		$newFile = $this->newFile;
		
		if($this->newZipArchives->open($newFile) !== true){
			//文件打开失败
			die('sdfs');
		}
		
		$length = $this->newZipArchives->numFiles;
		
		for($i=0;$i<$length;$i++){
			$this->backFiles[] = $this->newZipArchives->getNameIndex($i);
		}
		
		//备份文件
		$this->backupFile();
		
		
		$this->newZipArchives->extractTo(__ROOT__);
		
		$this->newZipArchives->close();		
		
		$this->changeXml($app,$field,$value);
		
	}
	/**
	 * 文件备份
	 */
	public function backupFile(){
		
		if(count($this->backFiles) < 1){
			return '';
		}
		
		$this->backupZipArchives = new ZipArchive;
		
		//$backupFile = __ROOT__.'/'.date('Ymd').'@'.str_replace('.zip','.zip.bak',$this->newFile);
		$backupFile = str_replace('.zip','.bak.zip',$this->newFile);
		
		if(is_file($backupFile)){
			unlink($backupFile);
		}
		$result = $this->backupZipArchives->open($backupFile,ZipArchive::CREATE);
		
		foreach($this->backFiles as $key=>$filename){
			$oldFilename = __ROOT__.'/'.$filename;
			
			if(is_dir($oldFilename)){
				//目录处理
				$this->backupZipArchives->addEmptyDir($filename);
			}
			
			if(is_file($oldFilename)){
				//文件处理
				$this->backupZipArchives->addFile($oldFilename,$filename);
			}
		}
			
		$this->backupZipArchives->close();
	}
	
	//释放资源
	public function  __destruct (){
		
	}
	
	/***
	 *
	 * 文件下载
	 *
	 */
	public function downFile($url, $file="", $timeout=60){
		$folder = __STORAGE__.'/upgrade';
		if(!is_dir($folder)){
			$result  = mkdir($folder,0777,1);
			if(!$result){
				//无法创建文件夹
				die('sdfs');
			}
		}
		
		if(!$file){
			$fileData = pathinfo($url);
			$file = $folder.'/'.$fileData['basename'];
		}	
		
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			$header=array('chunk:1','User-Agent:Mozilla/5.0(Macintosh;IntelMacOSX10.10;rv:47.0)Gecko/20100101Firefox/47.0',);
			curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书 
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名 
			
			$temp = curl_exec($ch);
		
			$response = curl_getinfo($ch);
			if($response['http_code'] != 200){
				die('down file failed');
			}
			file_put_contents($file, $temp);
		}
		return $file;
	}
}