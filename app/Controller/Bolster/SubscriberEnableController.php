<?php
/**
 *
 * 维护启用
 *
 * 20180301
 *
 */
class SubscriberEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'subscriberId'=>array('type'=>'digital','tooltip'=>'维护ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$subscriberId = $this->argument('subscriberId');
		
		$groupInfo = $this->service('BolsterSubscriber')->getSubscriberInfo($subscriberId);
		if(!$groupInfo){
			$this->info('维护不存在',4101);
		}
		
		if($groupInfo['status'] == BolsterSubscriberModel::BOLSTER_MACHINE_STATUS_DISABLED){
			$this->service('BolsterSubscriber')->update(array('status'=>BolsterSubscriberModel::BOLSTER_MACHINE_STATUS_ENABLE),$subscriberId);
		}
		
		
	}
}
?>