<?php
/**
 *
 * 栏目启用
 *
 * 20180301
 *
 */
class MessageEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'messageId'=>array('type'=>'digital','tooltip'=>'栏目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$messageId = $this->argument('messageId');
		
		$groupInfo = $this->service('MessengerMessage')->getMessageInfo($messageId);
		if(!$groupInfo){
			$this->info('栏目不存在',4101);
		}
		
		if($groupInfo['status'] == MessengerMessageModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('MessengerMessage')->update(array('status'=>MessengerMessageModel::PAGINATION_BLOCK_STATUS_ENABLE),$messageId);
		}
		
		
	}
}
?>