<?php
/**
 *
 * 分类启用
 *
 * 20180301
 *
 */
class ClassifyEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'classifyId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$classifyId = $this->argument('classifyId');
		
		$groupInfo = $this->service('FriendshipClassify')->getClassifyInfo($classifyId);
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		
		if($groupInfo['status'] == FriendshipClassifyModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FriendshipClassify')->update(array('status'=>FriendshipClassifyModel::PAGINATION_BLOCK_STATUS_ENABLE),$classifyId);
		}
		
		
	}
}
?>