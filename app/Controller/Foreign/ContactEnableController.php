<?php
/**
 *
 * 合约启用
 *
 * 20180301
 *
 */
class ContactEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'contactId'=>array('type'=>'digital','tooltip'=>'合约ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$contactId = $this->argument('contactId');
		
		$groupInfo = $this->service('ForeignContact')->getContactInfo($contactId);
		if(!$groupInfo){
			$this->info('合约不存在',4101);
		}
		
		if($groupInfo['status'] == ForeignContactModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('ForeignContact')->update(array('status'=>ForeignContactModel::PAGINATION_BLOCK_STATUS_ENABLE),$contactId);
		}
		
		
	}
}
?>