<?php
/***
 *
 * 这是一个代码卸载插件
 *
 * 开始->应用检测->文件删除->结束
 *
 */

class UnloadController extends Controller
{
	
	//初始化资源
	public function __construct(){
		$this->removeFolder(__STORAGE__.'/install');
	}
	
	
	/**
	 *
	 * 创建目录
	 *
	 */
	public function mkdir($folder,$mode){
		
		if(function_exists('mb_convert_encoding')){
			$folder = mb_convert_encoding($folder,'UTF8');
		}
		elseif(function_exists('iconv')){
			$folder = iconv('GBK','UTF8//IGNORE',$folder);
		}
		
		if((int)PHP_VERSION < 5){
			$this->_mkdir($folder,$mode);
		}else{
			$result = mkdir($folder,$mode,1);
			if(!$result){
				die($folder.' Created Failed.');
			}
		}
	}
	
	
	/**
	 *
	 * 创建目录
	 *
	 * 兼容旧版
	 *
	 */
	public function _mkdir($folders,$mode){
		$folders = explode('/', $folders);
		$dir='';
		foreach ($folders as $folder) {
			$dir.=$folder.'/';
			if (!is_dir($dir) && strlen($dir)>0){
				$result = mkdir($dir, $mode);
				if(!$result){
					die($dir.' Created Failed.');
				}
			}
		}
	}
	
	/**
	 *
	 *
	 *删除目录及所有文件
	 */
	public function removeFolder($folder){
		$handle = opendir($folder);
		while($filename = readdir($handle)){
			if(in_array($filename,array('.','..'))) continue;
			$subFolder = $folder.'/'.$filename;
			if(is_dir($subFolder)){
				$this->removeFolder($subFolder);
			}else{
				$isRemoved = unlink($subFolder);
				if(!$isRemoved){
					die($subFolder.' Deleted Failed');
				}
			}
		}
		
		closedir($handle);
		
		$isDelete = rmdir($folder);
		if(!$isDelete){
			die($folder.' Deleted Failed');
		}
	}
	
	//释放资源
	public function  __destruct (){
		
	}
}
