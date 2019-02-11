<?php
/**
 *
 * 删除目录
 *
 * 20180301
 *
 */
class FrameworkDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'exchangeId'=>array('type'=>'digital','tooltip'=>'目录ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$exchangeId = $this->argument('exchangeId');
		
		$exchangeInfo = $this->service('QuotationFramework')->getFrameworkInfo($exchangeId);
		
		if(!$exchangeInfo){
			$this->info('目录不存在',4101);
		}
		if(!is_array($exchangeId)){
			$exchangeInfo = array($exchangeInfo);
		}
		
		$removeFrameworkIds = array();
		foreach($exchangeInfo as $key=>$exchange){
			if($exchange['product_num'] < 1){
				$removeFrameworkIds[] = $exchange['identity'];
			}
		}
		
		$this->service('QuotationFramework')->removeFrameworkId($removeFrameworkIds);
		
		$sourceTotal = count($exchangeId);
		$successNum = count($removeFrameworkIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>