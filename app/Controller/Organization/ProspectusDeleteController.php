<?php
/**
 *
 * 删除计划
 *
 * 20180301
 *
 */
class ProspectusDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'prospectusId'=>array('type'=>'digital','tooltip'=>'计划ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeProspectusIds = $this->argument('prospectusId');
		
		$prospectusInfo = $this->service('OrganizationProspectus')->getProspectusInfo($removeProspectusIds);
		
		if(!$prospectusInfo){
			$this->info('计划不存在',4101);
		}
		
		$this->service('OrganizationProspectus')->removeProspectusId($removeProspectusIds);
		
		$sourceTotal = count($prospectusueId);
		$successNum = count($removeProspectusIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>