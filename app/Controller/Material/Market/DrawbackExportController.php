<?php
/**
 *
 * 售后导出
 *
 * 营销
 *
 */
class DrawbackExportController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	//protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'drawbackId'=>array('type'=>'digital','tooltip'=>'申请ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$drawbackId = $this->argument('drawbackId');
		
		$drawbackList = $this->service('MarketDrawback')->getdrawbackInfo($drawbackId);
		
	}
}
?>