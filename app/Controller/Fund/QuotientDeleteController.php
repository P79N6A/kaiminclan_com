<?php
/**
 *
 * 删除份额
 *
 * 20180301
 *
 */
class QuotientDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'quotientId'=>array('type'=>'digital','tooltip'=>'份额ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$quotientId = $this->argument('quotientId');
		
		$quotientInfo = $this->service('FundQuotient')->getQuotientInfo($quotientId);
		
		if(!$quotientInfo){
			$this->info('份额不存在',4101);
		}
		
		if(!is_array($quotientId)){
			$quotientInfo = array($quotientInfo);
		}
		
		
		$removeQuotientIds = array();
		foreach($quotientInfo as $key=>$quotient){
			$removeQuotientIds[] = $quotient['identity'];
		}
		
		$this->service('FundQuotient')->removeQuotientId($removeQuotientIds);
		
		$sourceTotal = count($quotientId);
		$successNum = count($removeQuotientIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>