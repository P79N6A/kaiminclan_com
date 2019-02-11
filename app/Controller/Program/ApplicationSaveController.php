<?php
/**
 *
 * 应用编辑
 *
 * 20180301
 *
 */
class ApplicationSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'applicationId'=>array('type'=>'digital','tooltip'=>'应用ID','default'=>0),
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
		
		$applicationId = $this->argument('applicationId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'summary' => $this->argument('summary'),
			'version' => $this->argument('version'),
			'help' => $this->argument('help'),
			'issuance' => $this->argument('issuance'),
			'support' => $this->argument('support')
		);
		
		if($applicationId){
			$this->service('ProgramApplication')->update($setarr,$applicationId);
		}else{
			
			if($this->service('ProgramApplication')->checkApplicationTitle($setarr['title'])){
				
				$this->info('应用已存在',4001);
			}
			
			$this->service('ProgramApplication')->insert($setarr);
		}
	}
}
?>