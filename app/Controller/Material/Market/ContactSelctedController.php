<?php
/**
 *
 * 地址默认
 *
 * 营销
 *
 */
class ContactSelctedController extends Controller {
	
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
			$this->info('联系人不存在',40012);
		}
		
		$uid = (int)$this->session('uid');
		if($contactData['subscriber_identity'] != $uid){
				$this->info('谨允许操作自己的地址信息',40003);
		}
		
		$newContactData = array();
		switch($contactData['secleted']){
			case MarketContactModel::MARKET_CONTACT_SELECTED_YES: $newContactData['secleted'] = MarketContactModel::MARKET_CONTACT_SELECTED_NO; break;
			case MarketContactModel::MARKET_CONTACT_SELECTED_NO: $newContactData['secleted'] = MarketContactModel::MARKET_CONTACT_SELECTED_YES;  break;
		}
		
		if($newContactData['secleted'] == MarketContactModel::MARKET_CONTACT_SELECTED_YES){
			$this->service('MarketContact')->cannelDefaultContactByUid($this->session('uid'));
		}
		
		$this->service('MarketContact')->update($newContactData,$contactId);
		
		
		
	}
}
?>