<?php
/**
 *
 * 产品启用
 *
 * 20180301
 *
 */
class ProductEnableController extends Controller {
	
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
		
		$groupInfo = $this->service('MaterialProduct')->getProductInfo($productId);
		if(!$groupInfo){
			$this->info('产品不存在',4101);
		}
		
		if($groupInfo['status'] == MaterialProductModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('MaterialProduct')->update(array('status'=>MaterialProductModel::PAGINATION_BLOCK_STATUS_ENABLE),$productId);
		}
		
		
	}
}
?>