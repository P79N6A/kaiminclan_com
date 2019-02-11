<?php
/**
 *
 * 分销等级启用
 *
 * 20180301
 *
 */
class GradeEnableController extends Controller {
	
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
		
		$groupInfo = $this->service('DistributionGrade')->getGradeInfo($gradeId);
		if(!$groupInfo){
			$this->info('分销等级不存在',4101);
		}
		
		if($groupInfo['status'] == DistributionGradeModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('DistributionGrade')->update(array('status'=>DistributionGradeModel::PAGINATION_BLOCK_STATUS_ENABLE),$gradeId);
		}
		
		
	}
}
?>