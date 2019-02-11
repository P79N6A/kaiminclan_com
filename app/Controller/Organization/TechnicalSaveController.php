<?php
/**
 *
 * 职称编辑
 *
 * 20180301
 *
 */
class TechnicalSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'technicalId'=>array('type'=>'digital','tooltip'=>'职称ID','default'=>0),
			'code'=>array('type'=>'string','tooltip'=>'编码','length'=>20),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'position_identity'=>array('type'=>'digital','tooltip'=>'岗位'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$technicalId = $this->argument('technicalId');
		
		$setarr = array(
			'position_identity' => $this->argument('position_identity'),
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
			'content' => $this->argument('content')
		);
		
		if($technicalId){
			$this->service('OrganizationTechnical')->update($setarr,$technicalId);
		}else{
			
			if($this->service('OrganizationTechnical')->checkTitle($setarr['title'],$setarr['position_identity'])){
				
				$this->info('职称已存在',4001);
			}
			
			$this->service('OrganizationTechnical')->insert($setarr);
		}
	}
}
?>