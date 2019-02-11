<?php
/**
 *
 * 禁用产品
 *
 * 20180301
 *
 */
class ProductDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'productId'=>array('type'=>'digital','tooltip'=>'产品ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$productId = $this->argument('productId');
		
		$groupInfo = $this->service('FundProduct')->getProductInfo($productId);
		if(!$groupInfo){
			$this->info('产品不存在',4101);
		}
		
		if($groupInfo['status'] == FundProductModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('FundProduct')->update(array('status'=>FundProductModel::PAGINATION_BLOCK_STATUS_DISABLED),$productId);
		}
	}
}
?>