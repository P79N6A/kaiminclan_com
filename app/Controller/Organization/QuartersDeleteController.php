<?php
/**
 *
 * 删除岗位
 *
 * 20180301
 *
 */
class QuartersDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'quartersId'=>array('type'=>'digital','tooltip'=>'岗位ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removeQuartersIds = $this->argument('quartersId');
		
		$quartersInfo = $this->service('OrganizationQuarters')->getQuartersInfo($removeQuartersIds);
		
		if(!$quartersInfo){
			$this->info('岗位不存在',4101);
		}
		
		$this->service('OrganizationQuarters')->removeQuartersId($removeQuartersIds);
		
		$sourceTotal = count($quartersueId);
		$successNum = count($removeQuartersIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>