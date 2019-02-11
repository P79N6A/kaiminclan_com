<?php
/**
 *
 * 禁用科目
 *
 * 20180301
 *
 */
class WebsiteDisableController extends Controller {
	
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
		
		$groupInfo = $this->service('FriendshipWebsite')->getWebsiteInfo($websiteId);
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		if($groupInfo['status'] == FriendshipWebsiteModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('FriendshipWebsite')->update(array('status'=>FriendshipWebsiteModel::PAGINATION_BLOCK_STATUS_DISABLED),$websiteId);
		}
	}
}
?>