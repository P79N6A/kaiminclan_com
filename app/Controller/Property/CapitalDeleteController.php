<?php
/**
 *
 * 删除公司
 *
 * 20180301
 *
 */
class CapitalDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'capitalId'=>array('type'=>'digital','tooltip'=>'公司ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$capitalId = $this->argument('capitalId');
		
		$capitalList = $this->service('PropertyCapital')->getCapitalInfo($capitalId);
		
		if(!$capitalList){
			$this->info('公司不存在',4101);
		}
		
		$this->service('PropertyCapital')->removeCapitalId($capitalId);
		
		
	}
}
?>