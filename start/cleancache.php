<?php 
/**
 * 清除缓存文件
 */
define('__ROOT__',substr(dirname(__FILE__),0,-5));

$folder = __ROOT__.'/storage/view';
define('__STRORAGE__',$folder);

removeFolder($folder);

function removeFolder($folder){
	$jump = array('.','..','index.htm');
	output('检测目录 '.$folder);
	$handle = opendir($folder);
	if($handle){
		while(($filename = readdir($handle)) !== false){
			if(in_array($filename,$jump)){
				continue;
			}
			$subFolder = $folder.'/'.$filename;
			if(is_dir($subFolder)){
				removeFolder($subFolder);
			}
			if(is_file($subFolder)){
				@unlink($subFolder);
				output('deleted '.$subFolder);
			}
		}
	}
	closedir($handle);
}

function output($file){
	echo str_replace(__STRORAGE__,'',$file).'<br />';
}
?>