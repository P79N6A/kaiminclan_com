<?php
/***
 * 这是一个代码安装插件
 *
 * 开始->包检测->应用检测->代码部署->环境构建(执行SQL,初始化数据)->环境清理（备份文件，清除安装文件，更新应用列表）->结束
 *		 	|
 *		  解压
 */

class InstallController extends Controller
{
	//新文件包
	private $newZipArchives = null;
	
	//安装目录
	const INSTALL_FOLDER = __STORAGE__.'/install';
	
	//新文件
	private  $newFile = '';
	
	//初始化资源
	public function __construct(){
		$this->packageFile = __ROOT__.'/tmp/media@v2.0.0.1.zip';
		
		if(!is_dir(self::INSTALL_FOLDER)){
			$result = mkdir(self::INSTALL_FOLDER,077,1);
			if(!$result){
				die('Folder Created Failed.');
			}
		}
		
		$this->checkPackage();
	}
	
	/**
	 *
	 * 检测源码包
	 *
	 * 两种情况，文件夹；压缩包；文件夹直接读取；压缩包，执行解压操作；
	 */
	private function checkPackage(){
		
		$fileInfo = pathinfo($this->packageFile);
		
		if($fileInfo['extension'] == 'zip'){
			$zip = new ZipArchive();
			if($zip->open($this->packageFile) !== true){
				die($this->packageFile.' Opened Failed.');
			}
			$zip->extractTo(self::INSTALL_FOLDER);
			$zip->close();
		}
		$this->checkApp();
	}
	
	/**
	 * 应用检测
	 */
	private function checkApp(){
		$packageXml = self::INSTALL_FOLDER.'/package.xml';
		if(!is_file($packageXml)){
			die('Package Readed Failed.');
		}
		
		$packageData = simplexml_load_file($packageXml);
		
		$appName = (string)$packageData->name;
		
		//检测应用是否已安装
		$installedPackeage = simplexml_load_file(__ROOT__.'/config/domain.xml');
		foreach($installedPackeage->program as $key=>$program){
			if($program->name == $appName){
				die('APP '.$appName.' Is Installed.');
			}
		}
		
		die();
		//检测PHP版
		$requireList = $packageData->require;
		$appPhpVersion = (string)$requireList->php;
		$appPhpVersion = str_replace('.','',$appPhpVersion);
		$currentPhpVersion = str_replace('.','',PHP_VERSION);
		if($currentPhpVersion < $appPhpVersion){
			die('Php Version Too Low.');
		}
		
		//检测扩展
		$extendList = get_loaded_extensions();
		foreach($packageData->extend as $key=>$extend){
			$extendTitle = (string)$extend->name;
			if(!in_array($extendTitle,$extendList)){
				die('Dll '.$extendTitle.' Not Installed.');
			}
		}
		
		
	}
	/**
	 * 数据部署
	 */
	private function executeScript(){
		
	}
	/**
	 * 代码部署
	 */
	public function deployCode(){
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
		
		$this->newZipArchives->extractTo(__ROOT__);
		
		$this->newZipArchives->close();		
		
	}
	
	//释放资源
	public function  __destruct(){
		
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
