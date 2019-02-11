<?php
/**
 *
 * 删除科目
 *
 * 20180301
 *
 */
class WebsiteDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'websiteId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$websiteId = $this->argument('websiteId');
		
		$groupInfo = $this->service('FriendshipWebsite')->getWebsiteInfoById($websiteId);
		
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		if(!is_array($websiteueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('FriendshipWebsite')->removeWebsiteId($removeGroupIds);
		
		$sourceTotal = count($websiteueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>