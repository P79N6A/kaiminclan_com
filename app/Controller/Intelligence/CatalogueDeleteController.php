<?php
/**
 *
 * 删除栏目
 *
 * 20180301
 *
 */
class CatalogueDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'栏目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$groupInfo = $this->service('IntelligenceCatalogue')->getCatalogInfo($catalogueId);
		
		if(!$groupInfo){
			$this->info('栏目不存在',4101);
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
		
		$this->service('IntelligenceCatalogue')->removeCatalogId($removeGroupIds);
		
		$sourceTotal = count($catalogueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>