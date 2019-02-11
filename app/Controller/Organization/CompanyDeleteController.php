<?php
/**
 *
 * 删除单位
 *
 * 20180301
 *
 */
class CompanyDeleteController extends Controller {
	
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
		
		$companyInfo = $this->service('OrganizationCompany')->getCompanyInfo($companyId);
		
		if(!$companyInfo){
			$this->info('单位不存在',4101);
		}
		
		$removeCompanyIds = array();
		foreach($companyInfo as $key=>$company){
			if($company['department_num'] < 1 && $company['employee_num'] < 1 && $company['position_num'] < 1 && $company['quarters_num'] < 1){
				$removeCompanyIds[] = $company['identity'];
			}
		}
		
		$this->service('OrganizationCompany')->removeCompanyId($removeCompanyIds);
		
		$sourceTotal = count($companyueId);
		$successNum = count($removeCompanyIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>