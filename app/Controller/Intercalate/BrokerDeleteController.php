<?php
/**
 *
 * 删除经纪
 *
 * 20180301
 *
 */
class BrokerDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'brokerId'=>array('type'=>'digital','tooltip'=>'经纪ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeBrokerIds = $this->argument('brokerId');
		
		$groupInfo = $this->service('IntercalateBroker')->getBrokerInfo($removeBrokerIds);
		
		if(!$groupInfo){
			$this->info('经纪不存在',4101);
		}
		
		$this->service('IntercalateBroker')->removeBrokerId($removeBrokerIds);
		
		$sourceTotal = count($brokerId);
		$successNum = count($removeBrokerIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>