<?php
/**
 *
 * 地址删除
 *
 * 营销
 *
 */
class ContactDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'contactId'=>array('type'=>'digital','tooltip'=>'联系人ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
				
		$contactId = $this->argument('contactId');
		
		$contactData = $this->service('MarketContact')->getContactInfo($contactId);
			
		if(!$contactData){
			$this->info('联系人不存在',40002);
		}
			
		if(!is_array($contactId)){
			$contactData = array($contactData);
			
		}
		
		$uid = $this->session('uid');
		foreach($contactData as $key=>$contact){
			if($contactData['subscriber_identity'] != $uid){
				$this->info('谨也许操作自己的地址信息',40013);
			}
		}
		
		$this->service('MarketContact')->removeContactId($contactId);
		
		
	}
}
?>