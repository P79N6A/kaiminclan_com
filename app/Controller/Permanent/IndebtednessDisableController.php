<?php
/**
 *
 * 禁用债务
 *
 * 20180301
 *
 */
class IndebtednessDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'indebtednessId'=>array('type'=>'digital','tooltip'=>'债务ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$indebtednessId = $this->argument('indebtednessId');
		
		$groupInfo = $this->service('OrganizationIndebtedness')->getIndebtednessInfo($indebtednessId);
		if(!$groupInfo){
			$this->info('债务不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationIndebtednessModel::PAGINATION_ITEM_STATUS_ENABLE){
			$this->service('OrganizationIndebtedness')->update(array('status'=>OrganizationIndebtednessModel::PAGINATION_ITEM_STATUS_DISABLED),$indebtednessId);
		}
	}
}
?>