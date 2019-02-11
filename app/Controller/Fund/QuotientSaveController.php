<?php
/**
 *
 * 份额编辑
 *
 * 20180301
 *
 */
class QuotientSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'quotientId'=>array('type'=>'digital','tooltip'=>'份额ID','default'=>0),
			'product_identity'=>array('type'=>'digital','tooltip'=>'产品'),
			'quantity'=>array('type'=>'digital','tooltip'=>'数量'),
			'amount'=>array('type'=>'money','tooltip'=>'金额'),
			'clientete_identity'=>array('type'=>'digital','tooltip'=>'客户',),
			'univalent'=>array('type'=>'money','tooltip'=>'单价'),
			'remark'=>array('type'=>'string','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$quotientId = $this->argument('quotientId');
		
		$setarr = array(
			'product_identity' => $this->argument('product_identity'),
			'quantity' => $this->argument('quantity'),
			'amount' => $this->argument('amount'),
			'clientete_identity' => $this->argument('clientete_identity'),
			'univalent' => $this->argument('univalent'),
			'remark' => $this->argument('remark')
		);
		
		if($quotientId){
			$this->service('FundQuotient')->update($setarr,$quotientId);
		}else{
			
			
			$this->service('FundQuotient')->insert($setarr);
		}
	}
}
?>