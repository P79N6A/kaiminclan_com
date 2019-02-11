<?php
/**
 *
 * 禁用维护
 *
 * 20180301
 *
 */
class SubscriberDisableController extends Controller {
	
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
		
		if($groupInfo['status'] == BolsterSubscriberModel::BOLSTER_MACHINE_STATUS_ENABLE){
			$this->service('BolsterSubscriber')->update(array('status'=>BolsterSubscriberModel::BOLSTER_MACHINE_STATUS_DISABLED),$subscriberId);
		}
	}
}
?>