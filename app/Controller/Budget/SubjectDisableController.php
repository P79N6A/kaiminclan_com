<?php
/**
 *
 * 禁用科目
 *
 * 20180301
 *
 */
class SubjectDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'subjectId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$subjectId = $this->argument('subjectId');
		
		$groupInfo = $this->service('BudgetSubject')->getSubjectInfo($subjectId);
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		if($groupInfo['status'] == BudgetSubjectModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('BudgetSubject')->update(array('status'=>BudgetSubjectModel::PAGINATION_BLOCK_STATUS_DISABLED),$subjectId);
		}
	}
}
?>