<?php
/**
 *
 * 职位编辑
 *
 * 20180301
 *
 */
class PositionSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'positionId'=>array('type'=>'digital','tooltip'=>'职位ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'department_identity'=>array('type'=>'digital','tooltip'=>'部门'),
			'quarters_identity'=>array('type'=>'digital','tooltip'=>'岗位'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$positionId = $this->argument('positionId');
		
		$setarr = array(
			'department_identity' => $this->argument('department_identity'),
			'quarters_identity' => $this->argument('quarters_identity'),
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'remark' => $this->argument('remark'),
		);
		
		if($positionId){
			$this->service('OrganizationPosition')->update($setarr,$positionId);
		}else{
			
			if($this->service('OrganizationPosition')->checkPositionTitle($setarr['title'],$setarr['quarters_identity'])){
				
				$this->info('职位已存在',4001);
			}
			
			$this->service('OrganizationPosition')->insert($setarr);
		}
	}
}
?>