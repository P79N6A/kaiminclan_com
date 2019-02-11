<?php
/**
 *
 * 授信启用
 *
 * 20180301
 *
 */
class CreditEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'creditId'=>array('type'=>'digital','tooltip'=>'授信ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$creditId = $this->argument('creditId');
		
		$groupInfo = $this->service('PermanentCredit')->getCreditInfo($creditId);
		if(!$groupInfo){
			$this->info('授信不存在',4101);
		}
		
		if($groupInfo['status'] == PermanentCreditModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('PermanentCredit')->update(array('status'=>PermanentCreditModel::PAGINATION_BLOCK_STATUS_ENABLE),$creditId);
		}
		
		
	}
}
?>