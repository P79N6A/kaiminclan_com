<?php
/**
 *
 * 应用编辑
 *
 * 20180301
 *
 */
class InterfaceSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'interfaceId'=>array('type'=>'digital','tooltip'=>'应用ID','default'=>0),
			'code'=>array('type'=>'string','tooltip'=>'编码','length'=>80),
			'title'=>array('type'=>'doc','tooltip'=>'应用名称'),
			'summary'=>array('type'=>'doc','tooltip'=>'介绍'),
			'version'=>array('type'=>'doc','tooltip'=>'版本'),
			'help'=>array('type'=>'doc','tooltip'=>'帮助'),
			'support'=>array('type'=>'doc','tooltip'=>'支持'),
			'issuance'=>array('type'=>'doc','tooltip'=>'发布人')
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$interfaceId = $this->argument('interfaceId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'summary' => $this->argument('summary'),
			'version' => $this->argument('version'),
			'help' => $this->argument('help'),
			'issuance' => $this->argument('issuance'),
			'support' => $this->argument('support')
		);
		
		if($interfaceId){
			$this->service('ProgramInterface')->update($setarr,$interfaceId);
		}else{
			
			if($this->service('ProgramInterface')->checkInterfaceTitle($setarr['title'])){
				
				$this->info('应用已存在',4001);
			}
			
			$this->service('ProgramInterface')->insert($setarr);
		}
	}
}
?>