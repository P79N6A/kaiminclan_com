<?php
/**
 *
 * 删除债权
 *
 * 20180301
 *
 */
class ObligatoryDeleteController extends Controller {
	
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
		
		$obligatoryList = $this->service('OrganizationObligatory')->getObligatoryInfo($obligatoryId);
		
		if(!$obligatoryList){
			$this->info('债权不存在',4101);
		}
		
		$this->service('OrganizationObligatory')->removeObligatoryId($obligatoryId);
		
		
	}
}
?>