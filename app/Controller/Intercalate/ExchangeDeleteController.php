<?php
/**
 *
 * 删除交易所
 *
 * 20180301
 *
 */
class ExchangeDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'exchangeId'=>array('type'=>'digital','tooltip'=>'交易所ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$exchangeId = $this->argument('exchangeId');
		
		$exchangeInfo = $this->service('IntercalateExchange')->getExchangeInfo($exchangeId);
		
		if(!$exchangeInfo){
			$this->info('交易所不存在',4101);
		}
		if(!is_array($exchangeId)){
			$exchangeInfo = array($exchangeInfo);
		}
		
		$removeExchangeIds = array();
		foreach($exchangeInfo as $key=>$exchange){
			if($exchange['product_num'] < 1){
				$removeExchangeIds[] = $exchange['identity'];
			}
		}
		
		$this->service('IntercalateExchange')->removeExchangeId($removeExchangeIds);
		
		$sourceTotal = count($exchangeId);
		$successNum = count($removeExchangeIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>