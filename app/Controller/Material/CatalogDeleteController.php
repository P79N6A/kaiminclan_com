<?php
/**
 *
 * 删除栏目
 *
 * 20180301
 *
 */
class CatalogDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogId'=>array('type'=>'digital','tooltip'=>'栏目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$groupInfo = $this->service('MaterialCatalog')->getCatalogInfo($catalogId);
		
		if(!$groupInfo){
			$this->info('栏目不存在',4101);
		}
		if(!is_array($catalogId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('MaterialCatalog')->removeCatalogId($removeGroupIds);
		
		$sourceTotal = count($catalogId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>