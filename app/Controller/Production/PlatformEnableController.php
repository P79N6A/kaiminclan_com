<?php
/**
 *
 * 平台启用
 *
 * 20180301
 *
 */
class PlatformEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'platformId'=>array('type'=>'digital','tooltip'=>'平台ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$platformId = $this->argument('platformId');
		
		$groupInfo = $this->service('ProductionPlatform')->getPlatformInfo($platformId);
		if(!$groupInfo){
			$this->info('平台不存在',4101);
		}
		
		if($groupInfo['status'] == ProductionPlatformModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('ProductionPlatform')->update(array('status'=>ProductionPlatformModel::PAGINATION_BLOCK_STATUS_ENABLE),$platformId);
		}
		
		
	}
}
?>