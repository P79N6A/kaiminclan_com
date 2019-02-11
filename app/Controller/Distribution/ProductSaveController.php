<?php
/**
 *
 * 产品编辑
 *
 * 20180301
 *
 */
class ProductSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'productId'=>array('type'=>'digital','tooltip'=>'产品ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'catalogue_identity'=>array('type'=>'string','tooltip'=>'目录'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$productId = $this->argument('productId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'catalogue_identity' => $this->argument('catalogue_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($productId){
			$this->service('DistributionProduct')->update($setarr,$productId);
		}else{
			
			if($this->service('DistributionProduct')->checkProductTitle($setarr['title'])){
				
				$this->info('产品已存在',4001);
			}
			
			$this->service('DistributionProduct')->insert($setarr);
		}
	}
}
?>