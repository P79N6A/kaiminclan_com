<?php
/**
 *
 * 支出编辑
 *
 * 20180301
 *
 */
class SubjectSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'subjectId'=>array('type'=>'digital','tooltip'=>'支出ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
            'domain'=>array('type'=>'string','tooltip'=>'介绍'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
            'deadline'=>array('type'=>'digital','tooltip'=>'工期'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$subjectId = $this->argument('subjectId');
		
		$setarr = array(
			'title' => $this->argument('title'),
            'remark' => $this->argument('remark'),
            'domain' => $this->argument('domain'),
			'deadline' => $this->argument('deadline'),
			'content' => $this->argument('content')
		);
		
		if($subjectId){
			$this->service('ProjectSubject')->update($setarr,$subjectId);
		}else{
			
			if($this->service('ProjectSubject')->checkSubjectTitle($setarr['title'])){
				
				$this->info('支出已存在',4001);
			}
			
			$this->service('ProjectSubject')->insert($setarr);
		}
	}
}
?>