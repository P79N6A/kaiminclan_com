<?php
/**
 *
 * 目录启用
 *
 * 20180301
 *
 */
class FrameworkEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'exchangeId'=>array('type'=>'digital','tooltip'=>'目录ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$exchangeId = $this->argument('exchangeId');
		
		$groupInfo = $this->service('QuotationFramework')->getCatalogInfo($exchangeId);
		if(!$groupInfo){
			$this->info('目录不存在',4101);
		}
		
		if($groupInfo['status'] == QuotationFrameworkModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('QuotationFramework')->update(array('status'=>QuotationFrameworkModel::PAGINATION_BLOCK_STATUS_ENABLE),$exchangeId);
		}
		
		
	}
}
?>