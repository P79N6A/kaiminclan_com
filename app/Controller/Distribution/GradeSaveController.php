<?php
/**
 *
 * 分销等级编辑
 *
 * 20180301
 *
 */
class GradeSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'gradeId'=>array('type'=>'digital','tooltip'=>'分销等级ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'content'=>array('type'=>'html','tooltip'=>'介绍','length'=>2000),
			'maximum'=>array('type'=>'digital','tooltip'=>'最大积分'),
			'minimum'=>array('type'=>'digital','tooltip'=>'最小积分'),
			'attachment_identity'=>array('type'=>'digital','tooltip'=>'','default'=>0),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0)
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$gradeId = $this->argument('gradeId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'attachment_identity' => $this->argument('attachment_identity'),
			'maximum' => $this->argument('maximum'),
			'minimum' => $this->argument('minimum'),
			'remark' => $this->argument('remark')
		);
		
		if($gradeId){
			$this->service('DistributionGrade')->update($setarr,$gradeId);
		}else{
			
			if($this->service('DistributionGrade')->checkGradeTitle($setarr['title'])){
				
				$this->info('分销等级已存在',4001);
			}
			
			$this->service('DistributionGrade')->insert($setarr);
		}
	}
}
?>