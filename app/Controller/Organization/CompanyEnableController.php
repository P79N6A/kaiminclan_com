<?php
/**
 *
 * 单位启用
 *
 * 20180301
 *
 */
class CompanyEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'companyId'=>array('type'=>'digital','tooltip'=>'单位ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$companyId = $this->argument('companyId');
		
		$groupInfo = $this->service('OrganizationCompany')->getCompanyInfo($companyId);
		if(!$groupInfo){
			$this->info('单位不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationCompanyModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('OrganizationCompany')->update(array('status'=>OrganizationCompanyModel::PAGINATION_BLOCK_STATUS_ENABLE),$companyId);
		}
		
		
	}
}
?>