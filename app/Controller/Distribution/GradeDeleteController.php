<?php
/**
 *
 * 删除分销等级
 *
 * 20180301
 *
 */
class GradeDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'gradeId'=>array('type'=>'digital','tooltip'=>'分销等级ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$gradeId = $this->argument('gradeId');
		
		$gradeInfo = $this->service('DistributionGrade')->getGradeInfo($gradeId);
		
		if(!$gradeInfo){
			$this->info('分销等级不存在',4101);
		}
		if(!is_array($gradeueId)){
			$gradeInfo = array($gradeInfo);
		}
		
		$removeGradeIds = array();
		foreach($gradeInfo as $key=>$grade){
				$removeGradeIds[] = $grade['identity'];
		}
		
		$this->service('DistributionGrade')->removeGradeId($removeGradeIds);
		
		$sourceTotal = count($gradeueId);
		$successNum = count($removeGradeIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>