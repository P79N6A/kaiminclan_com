<?php
/**
 *
 * 解除分类锁定
 *
 * 20180301
 *
 */
class ChannelUnlockController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'channelId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$channelId = $this->argument('channelId');
		
		$groupInfo = $this->service('PassagewayChannel')->getChannelInfo($channelId);
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		
		if($groupInfo['status'] == PassagewayChannelModel::PASSAGEWAY_CHANNEL_STATUS_LOCKED){
			$this->service('PassagewayChannel')->update(array('status'=>PassagewayChannelModel::PASSAGEWAY_CHANNEL_STATUS_ENABLE),$channelId);
		}
		
		
	}
}
?>