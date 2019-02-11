<?php
/**
 *
 * 禁用应用
 *
 * 20180301
 *
 */
class FunctionalDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'functionalId'=>array('type'=>'digital','tooltip'=>'应用ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$functionalId = $this->argument('functionalId');
		
		$groupInfo = $this->service('ProgramFunctional')->getFunctionalInfo($functionalId);
		if(!$groupInfo){
			$this->info('应用不存在',4101);
		}
		
		if($groupInfo['status'] == ProgramFunctionalModel::PROGRAM_FUNCTIONAL_STATUS_ENABLE){
			$this->service('ProgramFunctional')->update(array('status'=>ProgramFunctionalModel::PROGRAM_FUNCTIONAL_STATUS_DISABLED),$functionalId);
		}
	}
}
?>