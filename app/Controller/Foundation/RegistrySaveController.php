<?php
/**
 *
 * 注册表编辑
 *
 * 20180301
 *
 */
class RegistrySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'registry'=>array('type'=>'doc','tooltip'=>'配置值'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$registryData = $this->argument('registry');
		list($code) = array_keys($registryData);
		list($settingList) = array_values($registryData);
		
		$registrInsertData = array(
			'code'=>$code,
			'valume'=>json_encode($settingList,JSON_UNESCAPED_UNICODE)
		);
			
		$registrySetting = $this->service('FoundationRegistry')->getRegistryByCode($code);
		if(!$registrySetting){
			$this->service('FoundationRegistry')->insert($registrInsertData);	
		}else{
			$this->service('FoundationRegistry')->update($registrInsertData,$registrySetting['identity']);	
		}
		
	}
	
	protected function addChild($code,$value){
		$where = array();
	}
}
?>