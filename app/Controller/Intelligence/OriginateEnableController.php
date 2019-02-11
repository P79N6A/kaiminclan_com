<?php
/**
 *
 * 合作伙伴启用
 *
 * 20180301
 *
 */
class OriginateEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'originateId'=>array('type'=>'digital','tooltip'=>'合作伙伴ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$originateId = $this->argument('originateId');
		
		$groupInfo = $this->service('OrganizationOriginate')->getOriginateInfo($originateId);
		if(!$groupInfo){
			$this->info('合作伙伴不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationOriginateModel::PAGINATION_ITEM_STATUS_DISABLED){
			$this->service('OrganizationOriginate')->update(array('status'=>OrganizationOriginateModel::PAGINATION_ITEM_STATUS_ENABLE),$originateId);
		}
		
		
	}
}
?>