<?php
/**
 *
 * 计划编辑
 *
 * 20180301
 *
 */
class ProspectusSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'prospectusId'=>array('type'=>'digital','tooltip'=>'计划ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'company_identity'=>array('type'=>'digital','tooltip'=>'部门','default'=>0),
			'department_identity'=>array('type'=>'digital','tooltip'=>'部门','default'=>0),
			'employee_identity'=>array('type'=>'digital','tooltip'=>'部门','default'=>0),
			'introduce'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$prospectusId = $this->argument('prospectusId');
		
		$setarr = array(
			'company_identity' => $this->argument('company_identity'),
			'department_identity' => $this->argument('department_identity'),
			'employee_identity' => $this->argument('employee_identity'),
			'title' => $this->argument('title'),
			'introduce' => $this->argument('introduce'),
			'remark' => $this->argument('remark')
		);
		
		if($prospectusId){
			$this->service('OrganizationProspectus')->update($setarr,$prospectusId);
		}else{
			
			if($this->service('OrganizationProspectus')->checkProspectusTitle($setarr['title'],$setarr['employee_identity'])){
				
				$this->info('计划已存在',4001);
			}
			
			$this->service('OrganizationProspectus')->insert($setarr);
		}
	}
}
?>