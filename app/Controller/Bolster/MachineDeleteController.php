<?php
/**
 *
 * 删除机器
 *
 * 20180301
 *
 */
class MachineDeleteController extends Controller {
	
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
		
		$machineInfo = $this->service('BolsterMachine')->getMachineInfo($machineId);
		
		if(!$machineInfo){
			$this->info('机器不存在',4101);
		}
		if(!is_array($machineueId)){
			$machineInfo = array($machineInfo);
		}
		
		$removeMachineIds = array();
		foreach($machineInfo as $key=>$machine){
			if($machine['attachment_num'] < 1){
				$removeMachineIds[] = $machine['identity'];
			}
		}
		
		$this->service('BolsterMachine')->removeMachineId($removeMachineIds);
		
		$sourceTotal = count($machineueId);
		$successNum = count($removeMachineIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>