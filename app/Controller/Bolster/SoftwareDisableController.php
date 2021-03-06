<?php
/**
 *
 * 禁用维护
 *
 * 20180301
 *
 */
class SoftwareDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'softwareId'=>array('type'=>'digital','tooltip'=>'维护ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$softwareId = $this->argument('softwareId');
		
		$groupInfo = $this->service('BolsterSoftware')->getSoftwareInfo($softwareId);
		if(!$groupInfo){
			$this->info('维护不存在',4101);
		}
		
		if($groupInfo['status'] == BolsterSoftwareModel::BOLSTER_MACHINE_STATUS_ENABLE){
			$this->service('BolsterSoftware')->update(array('status'=>BolsterSoftwareModel::BOLSTER_MACHINE_STATUS_DISABLED),$softwareId);
		}
	}
}
?>