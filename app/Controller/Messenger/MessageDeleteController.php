<?php
/**
 *
 * 删除栏目
 *
 * 20180301
 *
 */
class MessageDeleteController extends Controller {
	
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
		if(!is_array($messageId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('MessengerMessage')->removeMessageId($removeGroupIds);
		
		$sourceTotal = count($messageId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>