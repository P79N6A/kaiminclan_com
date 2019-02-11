<?php
/**
 *
 * 删除职位
 *
 * 20180301
 *
 */
class PositionDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'positionId'=>array('type'=>'digital','tooltip'=>'职位ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$removePositionIds = $this->argument('positionId');
		
		$positionInfo = $this->service('OrganizationPosition')->getPositionInfo($removePositionIds);
		
		if(!$positionInfo){
			$this->info('职位不存在',4101);
		}
		
		$this->service('OrganizationPosition')->removePositionId($removePositionIds);
		
		$sourceTotal = count($positionueId);
		$successNum = count($removePositionIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>