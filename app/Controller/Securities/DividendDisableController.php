<?php
/**
 *
 * 禁用调账
 *
 * 20180301
 *
 */
class DividendDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'dividendId'=>array('type'=>'digital','tooltip'=>'调账ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$dividendId = $this->argument('dividendId');
		
		$groupInfo = $this->service('SecuritiesDividend')->getDividendInfo($dividendId);
		if(!$groupInfo){
			$this->info('调账不存在',4101);
		}
		
		if($groupInfo['status'] == SecuritiesDividendModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('SecuritiesDividend')->update(array('status'=>SecuritiesDividendModel::PAGINATION_BLOCK_STATUS_DISABLED),$dividendId);
		}
	}
}
?>