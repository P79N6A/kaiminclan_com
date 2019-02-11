<?php
/**
 *
 * 军衔启用
 *
 * 20180301
 *
 */
class HarbourEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'harbourId'=>array('type'=>'digital','tooltip'=>'军衔ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$harbourId = $this->argument('harbourId');
		
		$groupInfo = $this->service('OrganizationHarbour')->getHarbourInfo($harbourId);
		if(!$groupInfo){
			$this->info('军衔不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationHarbourModel::PAGINATION_TEMPLATE_STATUS_DISABLED){
			$this->service('OrganizationHarbour')->update(array('status'=>OrganizationHarbourModel::PAGINATION_TEMPLATE_STATUS_ENABLE),$harbourId);
		}
		
		
	}
}
?>