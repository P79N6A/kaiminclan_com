<?php
/**
 *
 * 合约启用
 *
 * 20180301
 *
 */
class ContractBySymbolController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	protected $accept = 'application/json';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'symbol'=>array('type'=>'letter','tooltip'=>'代码'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$symbol = $this->argument('symbol');
		
		$this->assign('contactData',$this->service('ForeignContact')->getContactBySymbol($symbol));
		
		
	}
}
?>