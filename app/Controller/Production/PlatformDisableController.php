<?php
/**
 *
 * 禁用平台
 *
 * 20180301
 *
 */
class PlatformDisableController extends Controller {
	
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
		
		if($groupInfo['status'] == ProductionPlatformModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('ProductionPlatform')->update(array('status'=>ProductionPlatformModel::PAGINATION_BLOCK_STATUS_DISABLED),$platformId);
		}
	}
}
?>