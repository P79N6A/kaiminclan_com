<?php
/**
 *
 * 岗位编辑
 *
 * 20180301
 *
 */
class QuartersSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'quartersId'=>array('type'=>'digital','tooltip'=>'岗位ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'department_identity'=>array('type'=>'digital','tooltip'=>'部门'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'obligation'=>array('type'=>'doc','tooltip'=>'主要工作'),
			'competency'=>array('type'=>'doc','tooltip'=>'任职资格'),
			'outstand'=>array('type'=>'doc','tooltip'=>'其他'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$quartersId = $this->argument('quartersId');
		
		$setarr = array(
			'department_identity' => $this->argument('department_identity'),
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'obligation' => $this->argument('obligation'),
			'competency' => $this->argument('competency'),
			'remark' => $this->argument('remark'),
			'outstand' => $this->argument('outstand')
		);
		
		if($quartersId){
			$this->service('OrganizationQuarters')->update($setarr,$quartersId);
		}else{
			
			if($this->service('OrganizationQuarters')->checkQuartersTitle($setarr['title'],$setarr['department_identity'])){
				
				$this->info('岗位已存在',4001);
			}
			
			$this->service('OrganizationQuarters')->insert($setarr);
		}
	}
}
?>