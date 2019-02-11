<?php
/**
 *
 * 删除业务
 *
 * 20180301
 *
 */
class CatalogueDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'catalogueId'=>array('type'=>'digital','tooltip'=>'业务ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$groupInfo = $this->service('ProductionCatalogue')->getCatalogueInfo($catalogueId);
		
		if(!$groupInfo){
			$this->info('业务不存在',4101);
		}
		if(!is_array($catalogueueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['frontend_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ProductionCatalogue')->removeCatalogueId($removeGroupIds);
		
		$sourceTotal = count($catalogueueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>