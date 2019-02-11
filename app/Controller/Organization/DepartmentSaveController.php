<?php
/**
 *
 * 部门编辑
 *
 * 20180301
 *
 */
class DepartmentSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'departmentId'=>array('type'=>'digital','tooltip'=>'部门ID','default'=>0),
			'department_identity'=>array('type'=>'digital','tooltip'=>'隶属部门','default'=>0),
			'company_identity'=>array('type'=>'digital','tooltip'=>'隶属单位'),
			'responsibility'=>array('type'=>'doc','tooltip'=>'职能'),
			'obligation'=>array('type'=>'doc','tooltip'=>'职责'),
			'weight'=>array('type'=>'digital','tooltip'=>'排序','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$departmentId = $this->argument('departmentId');
		
		$setarr = array(
			'company_identity' => $this->argument('company_identity'),
			'department_identity' => $this->argument('department_identity'),
			'responsibility' => $this->argument('responsibility'),
			'weight' => $this->argument('weight'),
			'obligation' => $this->argument('obligation'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
		);
		
		if($departmentId){
			$this->service('OrganizationDepartment')->update($setarr,$departmentId);
		}else{
			
			if($this->service('OrganizationDepartment')->checkTitle($setarr['title'],$setarr['company_identity'],$setarr['department_identity'])){
				
				$this->info('部门已存在',4001);
			}
			
			$this->service('OrganizationDepartment')->insert($setarr);
		}
	}
}
?>