<?php
/**
 *
 * 禁用合约
 *
 * 20180301
 *
 */
class ContactDisableController extends Controller {
	
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
		
		if($groupInfo['status'] == ForeignContactModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('ForeignContact')->update(array('status'=>ForeignContactModel::PAGINATION_BLOCK_STATUS_DISABLED),$contactId);
		}
	}
}
?>