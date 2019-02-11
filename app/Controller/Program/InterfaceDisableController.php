<?php
/**
 *
 * 禁用应用
 *
 * 20180301
 *
 */
class InterfaceDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'interfaceId'=>array('type'=>'digital','tooltip'=>'应用ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$interfaceId = $this->argument('interfaceId');
		
		$groupInfo = $this->service('ProgramInterface')->getInterfaceInfo($interfaceId);
		if(!$groupInfo){
			$this->info('应用不存在',4101);
		}
		
		if($groupInfo['status'] == ProgramInterfaceModel::PROGRAM_FUNCTIONAL_STATUS_ENABLE){
			$this->service('ProgramInterface')->update(array('status'=>ProgramInterfaceModel::PROGRAM_FUNCTIONAL_STATUS_DISABLED),$interfaceId);
		}
	}
}
?>