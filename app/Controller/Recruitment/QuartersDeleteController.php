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
		
		$quartersId = $this->argument('quartersId');
		
		$quartersInfo = $this->service('RecruitmentQuarters')->getQuartersInfo($quartersId);
		
		if(!$quartersInfo){
			$this->info('岗位不存在',4101);
		}
		if(!is_array($quartersId)){
			$quartersInfo = array($quartersInfo);
		}
		
		$removeGroupIds = array();
		foreach($quartersInfo as $key=>$quarters){
				$removeGroupIds[] = $quarters['identity'];
		}
		
		$this->service('RecruitmentQuarters')->removeQuartersId($removeGroupIds);
		
		$sourceTotal = count($quartersId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>