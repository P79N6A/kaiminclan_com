<?php
/**
 *
 * 删除支出
 *
 * 20180301
 *
 */
class SubjectDeleteController extends Controller {
	
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
		
		$groupInfo = $this->service('ProjectSubject')->getSubjectInfo($subjectId);
		
		if(!$groupInfo){
			$this->info('支出不存在',4101);
		}
		if(!is_array($subjectueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ProjectSubject')->removeSubjectId($removeGroupIds);
		
		$sourceTotal = count($subjectueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>