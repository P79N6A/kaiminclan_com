<?php
/**
 *
 * 删除渠道
 *
 * 20180301
 *
 */
class MediumDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'mediumId'=>array('type'=>'digital','tooltip'=>'渠道ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$mediumId = $this->argument('mediumId');
		
		$mediumList = $this->service('RecruitmentMedium')->getMediumInfo($mediumId);
		
		if(!$mediumList){
			$this->info('渠道不存在',4101);
		}
		
		$this->service('RecruitmentMedium')->removeMediumId($mediumId);
		
		
	}
}
?>