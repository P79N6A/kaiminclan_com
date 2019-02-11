<?php
/**
 *
 * 删除债务
 *
 * 20180301
 *
 */
class IndebtednessDeleteController extends Controller {
	
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
		
		$indebtednessList = $this->service('OrganizationIndebtedness')->getIndebtednessInfo($indebtednessId);
		
		if(!$indebtednessList){
			$this->info('债务不存在',4101);
		}
		
		$this->service('OrganizationIndebtedness')->removeIndebtednessId($indebtednessId);
		
		
	}
}
?>