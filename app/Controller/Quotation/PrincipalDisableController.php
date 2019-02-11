<?php
/**
 *
 * 禁用科目
 *
 * 20180301
 *
 */
class PrincipalDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'principalId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$principalId = $this->argument('principalId');
		
		$groupInfo = $this->service('QuotationPrincipal')->getPrincipalInfo($principalId);
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		if($groupInfo['status'] == QuotationPrincipalModel::QUOTATION_PRINCIPAL_STATUS_ENABLE){
			$this->service('QuotationPrincipal')->update(array('status'=>QuotationPrincipalModel::QUOTATION_PRINCIPAL_STATUS_DISABLED),$principalId);
		}
	}
}
?>