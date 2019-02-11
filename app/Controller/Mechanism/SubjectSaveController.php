<?php
/**
 *
 * 科目编辑
 *
 * 20180301
 *
 */
class SubjectSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'subjectId'=>array('type'=>'digital','tooltip'=>'科目ID','default'=>0),
			'subject_identity'=>array('type'=>'digital','tooltip'=>'隶属科目','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$subjectId = $this->argument('subjectId');
		
		$setarr = array(
			'subject_identity' => $this->argument('subject_identity'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($subjectId){
			$this->service('MechanismSubject')->update($setarr,$subjectId);
		}else{
			
			if($this->service('MechanismSubject')->checkSubjectTitle($setarr['title'],$setarr['subject_identity'])){
				
				$this->info('科目已存在',4001);
			}
			
			$this->service('MechanismSubject')->insert($setarr);
		}
	}
}
?>