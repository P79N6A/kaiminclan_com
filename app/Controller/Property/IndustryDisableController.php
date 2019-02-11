<?php
/**
 *
 * 禁用需求
 *
 * 20180301
 *
 */
class IndustryDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'industryId'=>array('type'=>'digital','tooltip'=>'需求ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$industryId = $this->argument('industryId');
		
		$industryInfo = $this->service('PropertyIndustry')->getIndustryInfo($industryId);
		if(!$industryInfo){
			$this->info('需求不存在',4101);
		}
		
		if($industryInfo['status'] == PropertyIndustryModel::CUSTOMER_DEMAND_STATUS_ENABLE){
			$this->service('PropertyIndustry')->update(array('status'=>PropertyIndustryModel::CUSTOMER_DEMAND_STATUS_DISABLED),$industryId);
		}
	}
}
?>