<?php
/**
 *
 * 禁用账户类型
 *
 * 20180301
 *
 */
class TypologicalDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'typologicalId'=>array('type'=>'digital','tooltip'=>'账户类型ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$typologicalId = $this->argument('typologicalId');
		
		$groupInfo = $this->service('MechanismTypological')->getTypologicalInfo($typologicalId);
		if(!$groupInfo){
			$this->info('账户类型不存在',4101);
		}
		
		if($groupInfo['status'] == MechanismTypologicalModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('MechanismTypological')->update(array('status'=>MechanismTypologicalModel::PAGINATION_BLOCK_STATUS_DISABLED),$typologicalId);
		}
	}
}
?>