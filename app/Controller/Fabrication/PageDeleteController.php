<?php
/**
 *
 * 删除页面
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
		
		$pageInfo = $this->service('FabricationPage')->getPageInfo($pageId);
		
		if(!$pageInfo){
			$this->info('页面不存在',4101);
		}
		if(!is_array($pageueId)){
			$pageInfo = array($pageInfo);
		}
		
		$removePageIds = array();
		foreach($pageInfo as $key=>$page){
			if($page['attachment_num'] < 1){
				$removePageIds[] = $page['identity'];
			}
		}
		
		$this->service('FabricationPage')->removePageId($removePageIds);
		
		$sourceTotal = count($pageueId);
		$successNum = count($removePageIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>