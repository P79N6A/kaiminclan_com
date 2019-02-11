<?php
/**
 *
 * 删除行业
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
			'catalogId'=>array('type'=>'digital','tooltip'=>'行业ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogId = $this->argument('catalogId');
		
		$groupInfo = $this->service('InvestmentCatalog')->getCatalogInfo($catalogId);
		
		if(!$groupInfo){
			$this->info('行业不存在',4101);
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
		
		$this->service('InvestmentCatalog')->removeCatalogId($removeGroupIds);
		
		$sourceTotal = count($catalogueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>