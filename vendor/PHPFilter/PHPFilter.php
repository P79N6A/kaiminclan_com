<?php
/**
 *
 * 数据过滤
 *
 * 
 */
class PHPFilter {
	private $setting = array();
	private $data = '';
	
	private static $obj = array();
	public function __construct($config = array()){
		$this->setting = $config;
	}
	
	public function info($msg,$status){
		throw new Exception($msg,$status);
	}
	public function init($field,$setting){
		
		if(!isset($setting[$field])){
			$this->info('请求参数 '.$field.' 未定义',2001);
		}
		
		$setting = $setting[$field];
		
		
		$value = isset($_GET[$field])?$_GET[$field]:'';
				
		$len = 0;
		if(is_array($value)){
			$len = count($value);
		}else{
			$len = strlen($value);
		}
		if($len < 1){
			if(!isset($setting['default'])){
				$this->info('还没有提供'.$setting['tooltip'],2002);
			}
		}
		if(!isset($setting['type'])){
			$this->info('过滤插件 未定义',2003);
		}
		
		//驱动加载
		$isValidate = 0;
		$typeList = explode('#',$setting['type']);
		foreach($typeList as $key=>$type){
			if(empty(self::$obj[$type])){
				$filterFile = __ROOT__.'/vendor/PHPFilter/'.$type.'.php';
				if(!is_file($filterFile)){
					$this->info('插件'.$setting['type'].'不存在',1001);
				}
				include_once __ROOT__.'/vendor/PHPFilter/'.$type.'.php';
				
				$object = 'filter_'.$type;
				self::$obj[$type] =  new $object;
			}
			if($len){
				$isValidate = self::$obj[$type]->filter($value,$setting['format']);
				if($isValidate){
					if(method_exists(self::$obj[$type],'__toString')){
						$value = self::$obj[$type]->__toString();
					}
					break;
				}
			}
			
		}
		if($len){
			if(!$isValidate){
				$this->info($setting['tooltip'].'格式错误.',2003);
			}
		}else{
			$value = $setting['default'];
		}
		$this->data = $value;
		return $this;
	}
	
	public function _toData(){
		return $this->data;
	}
}