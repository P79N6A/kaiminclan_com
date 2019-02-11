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
		
		$removeProductIds = $this->argument('productId');
		
		$productInfo = $this->service('MaterialProduct')->getProductInfo($removeProductIds);
		
		if(!$productInfo){
			$this->info('产品不存在',4101);
		}
		if(!is_array($productueId)){
			$productInfo = array($productInfo);
		}

		
		$this->service('MaterialProduct')->removeProductId($removeProductIds);
		
		$sourceTotal = count($productueId);
		$successNum = count($removeProductIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>