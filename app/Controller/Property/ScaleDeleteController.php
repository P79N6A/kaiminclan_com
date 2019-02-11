<?php
/**
 *
 * 删除客户
 *
 * 20180301
 *
 */
class ScaleDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'scaleId'=>array('type'=>'digital','tooltip'=>'客户ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$scaleId = $this->argument('scaleId');
		
		$scaleList = $this->service('PropertyScale')->getScaleInfo($scaleId);
		
		if(!$scaleList){
			$this->info('客户不存在',4101);
		}
		
		$this->service('PropertyScale')->removeScaleId($scaleId);
		
		
	}
}
?>