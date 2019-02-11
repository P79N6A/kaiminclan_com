<?php
/**
 *
 * 删除资源分类
 *
 * 20180301
 *
 */
class ChannelDeleteController extends Controller {
	
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
		if(!is_array($channelueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('PassagewayChannel')->removeChannelId($removeGroupIds);
		
		$sourceTotal = count($channelueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>