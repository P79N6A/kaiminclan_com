<?php
/**
 *
 * 应用编辑
 *
 * 20180301
 *
 */
class FunctionalSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'functionalId'=>array('type'=>'digital','tooltip'=>'应用ID','default'=>0),
			'code'=>array('type'=>'string','tooltip'=>'编码','length'=>80),
			'title'=>array('type'=>'doc','tooltip'=>'应用名称'),
			'application_identity'=>array('type'=>'digital','tooltip'=>'应用'),
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
		
		$functionalId = $this->argument('functionalId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'application_identity' => $this->argument('application_identity'),
			'summary' => $this->argument('summary'),
			'version' => $this->argument('version'),
			'help' => $this->argument('help'),
			'issuance' => $this->argument('issuance'),
			'support' => $this->argument('support')
		);
		
		if($functionalId){
			$this->service('ProgramFunctional')->update($setarr,$functionalId);
		}else{
			
			if($this->service('ProgramFunctional')->checkFunctionalTitle($setarr['title'],$setarr['application_identity'])){
				
				$this->info('应用已存在',4001);
			}
			
			$this->service('ProgramFunctional')->insert($setarr);
		}
	}
}
?>