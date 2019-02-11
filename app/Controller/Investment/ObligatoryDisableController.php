<?php
/**
 *
 * 禁用债权
 *
 * 20180301
 *
 */
class ObligatoryDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'obligatoryId'=>array('type'=>'digital','tooltip'=>'债权ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$obligatoryId = $this->argument('obligatoryId');
		
		$groupInfo = $this->service('OrganizationObligatory')->getObligatoryInfo($obligatoryId);
		if(!$groupInfo){
			$this->info('债权不存在',4101);
		}
		
		if($groupInfo['status'] == OrganizationObligatoryModel::PAGINATION_ITEM_STATUS_ENABLE){
			$this->service('OrganizationObligatory')->update(array('status'=>OrganizationObligatoryModel::PAGINATION_ITEM_STATUS_DISABLED),$obligatoryId);
		}
	}
}
?>