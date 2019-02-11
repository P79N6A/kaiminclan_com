<?php
/**
 *
 * 禁用计划
 *
 * 20180301
 *
 */
class ProspectusDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'prospectusId'=>array('type'=>'digital','tooltip'=>'计划ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$prospectusId = $this->argument('prospectusId');
		
		$groupInfo = $this->service('OrganizationProspectus')->getProspectusInfo($prospectusId);
		if(!$groupInfo){
			$this->info('计划不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationProspectusModel::PAGINATION_TEMPLATE_STATUS_ENABLE){
			$this->service('OrganizationProspectus')->update(array('status'=>OrganizationProspectusModel::PAGINATION_TEMPLATE_STATUS_DISABLED),$prospectusId);
		}
	}
}
?>