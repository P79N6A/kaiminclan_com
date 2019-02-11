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
			'code'=>array('type'=>'letter','tooltip'=>'编码'),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'exchange_identity'=>array('type'=>'digital','tooltip'=>'交易所'),
			'last_trade_day'=>array('type'=>'digital','tooltip'=>'最后交易日','length'=>80),
			'last_delivery_day'=>array('type'=>'digital','tooltip'=>'最后交割日','length'=>80),
			'contract_month'=>array('type'=>'string','tooltip'=>'合约月份','length'=>80),
			'trade_unit'=>array('type'=>'string','tooltip'=>'交易单位','length'=>80),
			'quotation_unit'=>array('type'=>'string','tooltip'=>'报价单位','length'=>80),
			'min_variable_price'=>array('type'=>'string','tooltip'=>'最小变动价位','length'=>80),
			'bail'=>array('type'=>'string','tooltip'=>'保证金','length'=>80),
			'category_identity'=>array('type'=>'digital','tooltip'=>'目录'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$productId = $this->argument('productId');
		
		$setarr = array(
			'code' => $this->argument('code'),
			'title' => $this->argument('title'),
			'exchange_identity' => $this->argument('exchange_identity'),
			'last_trade_day' => $this->argument('last_trade_day'),
			'last_delivery_day' => $this->argument('last_delivery_day'),
			'contract_month' => $this->argument('contract_month'),
			'trade_unit' => $this->argument('trade_unit'),
			'quotation_unit' => $this->argument('quotation_unit'),
			'min_variable_price' => $this->argument('min_variable_price'),
			'bail' => $this->argument('bail'),
			'category_identity' => $this->argument('category_identity'),
			'remark' => $this->argument('remark')
		);
		
		if($productId){
			$this->service('MaterialProduct')->update($setarr,$productId);
		}else{
			
			if($this->service('MaterialProduct')->checkProductTitle($setarr['title'])){
				
				$this->info('产品已存在',4001);
			}
			
			$this->service('MaterialProduct')->insert($setarr);
		}
	}
}
?>