<?php
/**
 *
 * 调账启用
 *
 * 20180301
 *
 */
class IndustryEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'industryId'=>array('type'=>'digital','tooltip'=>'调账ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$industryId = $this->argument('industryId');
		
		$groupInfo = $this->service('SecuritiesIndustry')->getIndustryInfo($industryId);
		if(!$groupInfo){
			$this->info('调账不存在',4101);
		}
		
		if($groupInfo['status'] == SecuritiesIndustryModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('SecuritiesIndustry')->update(array('status'=>SecuritiesIndustryModel::PAGINATION_BLOCK_STATUS_ENABLE),$industryId);
		}
		
		
	}
}
?>