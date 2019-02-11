<?php
/**
 *
 * 删除需求
 *
 * 20180301
 *
 */
class IndustryDeleteController extends Controller {
	
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
		
		$industryList = $this->service('PropertyIndustry')->getIndustryInfo($industryId);
		
		if(!$industryList){
			$this->info('需求不存在',4101);
		}
		
		$this->service('PropertyIndustry')->removeIndustryId($industryId);
		
		
	}
}
?>