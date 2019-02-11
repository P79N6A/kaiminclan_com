<?php
/**
 *
 * 删除维护
 *
 * 20180301
 *
 */
class MaintainDeleteController extends Controller {
	
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
		
		$maintainInfo = $this->service('BolsterMaintain')->getMaintainInfo($maintainId);
		
		if(!$maintainInfo){
			$this->info('维护不存在',4101);
		}
		if(!is_array($maintainueId)){
			$maintainInfo = array($maintainInfo);
		}
		
		$removeMaintainIds = array();
		foreach($maintainInfo as $key=>$maintain){
			if($maintain['attachment_num'] < 1){
				$removeMaintainIds[] = $maintain['identity'];
			}
		}
		
		$this->service('BolsterMaintain')->removeMaintainId($removeMaintainIds);
		
		$sourceTotal = count($maintainueId);
		$successNum = count($removeMaintainIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>