<?php
/**
 *
 * 禁用页面
 *
 * 20180301
 *
 */
class FrontendDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'frontendId'=>array('type'=>'digital','tooltip'=>'页面ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$frontendId = $this->argument('frontendId');
		
		$groupInfo = $this->service('ProductionFrontend')->getFrontendInfo($frontendId);
		if(!$groupInfo){
			$this->info('页面不存在',4101);
		}
		
		if($groupInfo['status'] == ProductionFrontendModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('ProductionFrontend')->update(array('status'=>ProductionFrontendModel::PAGINATION_BLOCK_STATUS_DISABLED),$frontendId);
		}
	}
}
?>