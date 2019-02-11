<?php
/**
 *
 * 份额启用
 *
 * 20180301
 *
 */
class QuotientEnableController extends Controller {
	
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
		
		$groupInfo = $this->service('FundQuotient')->getQuotientInfo($quotientId);
		if(!$groupInfo){
			$this->info('份额不存在',4101);
		}
		
		if($groupInfo['status'] == FundQuotientModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('FundQuotient')->update(array('status'=>FundQuotientModel::PAGINATION_BLOCK_STATUS_ENABLE),$quotientId);
		}
		
		
	}
}
?>