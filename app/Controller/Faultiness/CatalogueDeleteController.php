<?php
/**
 *
 * 删除用例类型
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
			'catalogueId'=>array('type'=>'digital','tooltip'=>'用例类型ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$catalogueId = $this->argument('catalogueId');
		
		$groupInfo = $this->service('FaultinessCatalogue')->getCatalogueInfo($catalogueId);
		
		if(!$groupInfo){
			$this->info('用例类型不存在',4101);
		}
		if(!is_array($catalogueueId)){
			$groupInfo = array($groupInfo);
		}
		
		$this->service('FaultinessCatalogue')->removeCatalogueId($removeGroupIds);
		
		$sourceTotal = count($catalogueueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>