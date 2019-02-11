<?php
/**
 *
 * 删除产品
 *
 * 20180301
 *
 */
class ProductDeleteController extends Controller {
	
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
		
		$productInfo = $this->service('FundProduct')->getProductInfo($productId);
		
		if(!$productInfo){
			$this->info('产品不存在',4101);
		}
		
		if(!is_array($productId)){
			$productInfo = array($productInfo);
		}
		
		
		$removeProductIds = array();
		foreach($productInfo as $key=>$product){
			$removeProductIds[] = $product['identity'];
		}
		
		$this->service('FundProduct')->removeProductId($removeProductIds);
		
		$sourceTotal = count($productId);
		$successNum = count($removeProductIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>