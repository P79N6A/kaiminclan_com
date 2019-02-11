<?php
/**
 *
 * 缺陷启用
 *
 * 20180301
 *
 */
class BulletinEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bulletinId'=>array('type'=>'digital','tooltip'=>'缺陷ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bulletinId = $this->argument('bulletinId');
		
		$groupInfo = $this->service('FaultinessBulletin')->getBulletinInfo($bulletinId);
		if(!$groupInfo){
			$this->info('缺陷不存在',4101);
		}
		
		if($groupInfo['status'] == FaultinessBulletinModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FaultinessBulletin')->update(array('status'=>FaultinessBulletinModel::PAGINATION_BLOCK_STATUS_ENABLE),$bulletinId);
		}
		
		
	}
}
?>