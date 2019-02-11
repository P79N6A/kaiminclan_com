<?php
/**
 *
 * 科目启用
 *
 * 20180301
 *
 */
class PrincipalEnableController extends Controller {
	
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
		
		if($groupInfo['status'] == QuotationPrincipalModel::QUOTATION_PRINCIPAL_STATUS_DISABLED){
			$this->service('QuotationPrincipal')->update(array('status'=>QuotationPrincipalModel::QUOTATION_PRINCIPAL_STATUS_ENABLE),$principalId);
		}
		
		
	}
}
?>