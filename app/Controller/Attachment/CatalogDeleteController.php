<?php
/**
 *
 * 删除资源目录
 *
 * 20180301
 *
 */
class CatalogDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogId'=>array('type'=>'digital','tooltip'=>'目录ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$groupInfo = $this->service('ResourcesCatalog')->getCatalogInfo($catalogId);
		
		if(!$groupInfo){
			$this->info('目录不存在',4101);
		}
		if(!is_array($catalogueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ResourcesCatalog')->removeCatalogId($removeGroupIds);
		
		$sourceTotal = count($catalogueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>