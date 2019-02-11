<?php
/**
 *
 * 删除合作伙伴
 *
 * 20180301
 *
 */
class OriginateDeleteController extends Controller {
	
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
		
		$originateList = $this->service('OrganizationOriginate')->getOriginateInfo($originateId);
		
		if(!$originateList){
			$this->info('合作伙伴不存在',4101);
		}
		
		$this->service('OrganizationOriginate')->removeOriginateId($originateId);
		
		
	}
}
?>