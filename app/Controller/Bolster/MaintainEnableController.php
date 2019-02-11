<?php
/**
 *
 * 维护启用
 *
 * 20180301
 *
 */
class MaintainEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'maintainId'=>array('type'=>'digital','tooltip'=>'维护ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$maintainId = $this->argument('maintainId');
		
		$groupInfo = $this->service('BolsterMaintain')->getMaintainInfo($maintainId);
		if(!$groupInfo){
			$this->info('维护不存在',4101);
		}
		
		if($groupInfo['status'] == BolsterMaintainModel::BOLSTER_MACHINE_STATUS_DISABLED){
			$this->service('BolsterMaintain')->update(array('status'=>BolsterMaintainModel::BOLSTER_MACHINE_STATUS_ENABLE),$maintainId);
		}
		
		
	}
}
?>