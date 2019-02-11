<?php
/**
 *
 * 岗位启用
 *
 * 20180301
 *
 */
class QuartersEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'quartersId'=>array('type'=>'digital','tooltip'=>'岗位ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$quartersId = $this->argument('quartersId');
		
		$groupInfo = $this->service('OrganizationQuarters')->getQuartersInfo($quartersId);
		if(!$groupInfo){
			$this->info('岗位不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationQuartersModel::PAGINATION_TEMPLATE_STATUS_DISABLED){
			$this->service('OrganizationQuarters')->update(array('status'=>OrganizationQuartersModel::PAGINATION_TEMPLATE_STATUS_ENABLE),$quartersId);
		}
		
		
	}
}
?>