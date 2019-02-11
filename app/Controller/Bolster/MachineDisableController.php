<?php
/**
 *
 * 禁用机器
 *
 * 20180301
 *
 */
class MachineDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'machineId'=>array('type'=>'digital','tooltip'=>'机器ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$machineId = $this->argument('machineId');
		
		$groupInfo = $this->service('BolsterMachine')->getMachineInfo($machineId);
		if(!$groupInfo){
			$this->info('机器不存在',4101);
		}
		
		if($groupInfo['status'] == BolsterMachineModel::BOLSTER_MACHINE_STATUS_ENABLE){
			$this->service('BolsterMachine')->update(array('status'=>BolsterMachineModel::BOLSTER_MACHINE_STATUS_DISABLED),$machineId);
		}
	}
}
?>