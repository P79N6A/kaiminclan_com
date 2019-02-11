<?php
/**
 *
 * 渠道编辑
 *
 * 20180301
 *
 */
class MediumSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'mediumId'=>array('type'=>'digital','tooltip'=>'渠道ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题'),
			'content'=>array('type'=>'doc','tooltip'=>'介绍'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>''),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		$mediumId = $this->argument('mediumId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'content' => $this->argument('content'),
			'remark' => $this->argument('remark'),
		);
		
		$this->model('RecruitmentMedium')->start();
		
		if($mediumId){
			$result = $this->service('RecruitmentMedium')->update($setarr,$mediumId);
			if($result < 0){
				$this->info('渠道修改失败',400002);
			}
		}else{
			if($this->service('RecruitmentMedium')->checkMediumTitle($setarr['mobile'])){
				$this->info('此渠道已存在',400001);
			}
			$mediumId = $this->service('RecruitmentMedium')->insert($setarr);
		}
		$this->model('RecruitmentMedium')->commit();
		
		$this->assign('mediumId',$mediumId);
	}
}
?>