<?php
/**
 *
 * 删除军衔
 *
 * 20180301
 *
 */
class HarbourDeleteController extends Controller {
	
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
		
		$harbourInfo = $this->service('OrganizationHarbour')->getHarbourInfo($harbourId);
		
		if(!$harbourInfo){
			$this->info('军衔不存在',4101);
		}
		
		$this->service('OrganizationHarbour')->removeHarbourId($harbourId);
		
		$sourceTotal = count($harbourueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>