<?php
/**
 *
 * 禁用支出
 *
 * 20180301
 *
 */
class SubjectDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'subjectId'=>array('type'=>'digital','tooltip'=>'支出ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$subjectId = $this->argument('subjectId');
		
		$groupInfo = $this->service('ProjectSubject')->getTemplateInfo($subjectId);
		if(!$groupInfo){
			$this->info('支出不存在',4101);
		}
		
		if($groupInfo['status'] == ProjectSubjectModel::PAGINATION_TEMPLATE_STATUS_ENABLE){
			$this->service('ProjectSubject')->update(array('status'=>ProjectSubjectModel::PAGINATION_TEMPLATE_STATUS_DISABLED),$subjectId);
		}
	}
}
?>