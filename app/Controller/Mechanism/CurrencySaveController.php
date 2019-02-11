<?php
/**
 *
 * 货币编辑
 *
 * 20180301
 *
 */
class CurrencySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'currencyId'=>array('type'=>'digital','tooltip'=>'货币ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$currencyId = $this->argument('currencyId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($currencyId){
			$this->service('MechanismCurrency')->update($setarr,$currencyId);
		}else{
			
			if($this->service('MechanismCurrency')->checkCurrencyTitle($setarr['title'])){
				
				$this->info('货币已存在',4001);
			}
			
			$this->service('MechanismCurrency')->insert($setarr);
		}
	}
}
?>