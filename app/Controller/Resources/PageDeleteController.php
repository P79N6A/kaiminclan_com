<?php
/**
 *
 * 删除资源页面
 *
 * 20180301
 *
 */
class PageDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'pageId'=>array('type'=>'digital','tooltip'=>'页面ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$pageId = $this->argument('pageId');
		
		$groupInfo = $this->service('PaginationPage')->getPageInfo($pageId);
		
		if(!$groupInfo){
			$this->info('页面不存在',4101);
		}
		if(!is_array($pageueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('PaginationPage')->removePageId($removeGroupIds);
		
		$sourceTotal = count($pageueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>