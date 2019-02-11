<?php
/**
 *
 * 删除维护
 *
 * 20180301
 *
 */
class SubscriberDeleteController extends Controller {
	
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
		
		$subscriberInfo = $this->service('BolsterSubscriber')->getSubscriberInfo($subscriberId);
		
		if(!$subscriberInfo){
			$this->info('维护不存在',4101);
		}
		if(!is_array($subscriberueId)){
			$subscriberInfo = array($subscriberInfo);
		}
		
		$removeSubscriberIds = array();
		foreach($subscriberInfo as $key=>$subscriber){
			if($subscriber['attachment_num'] < 1){
				$removeSubscriberIds[] = $subscriber['identity'];
			}
		}
		
		$this->service('BolsterSubscriber')->removeSubscriberId($removeSubscriberIds);
		
		$sourceTotal = count($subscriberueId);
		$successNum = count($removeSubscriberIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>