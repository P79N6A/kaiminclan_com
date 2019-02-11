<?php
/**
 *
 * 删除客户
 *
 * 20180301
 *
 */
class ClienteteDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'clienteteId'=>array('type'=>'digital','tooltip'=>'客户ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$clienteteId = $this->argument('clienteteId');
		
		$clienteteList = $this->service('CustomerClientete')->getClienteteInfo($clienteteId);
		
		if(!$clienteteList){
			$this->info('客户不存在',4101);
		}
		
		$this->service('CustomerClientete')->removeClienteteId($clienteteId);
		
		
	}
}
?>