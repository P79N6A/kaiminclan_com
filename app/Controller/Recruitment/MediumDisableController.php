<?php
/**
 *
 * 禁用渠道
 *
 * 20180301
 *
 */
class MediumDisableController extends Controller {
	
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
		
		$mediumInfo = $this->service('RecruitmentMedium')->getMediumInfo($mediumId);
		if(!$mediumInfo){
			$this->info('渠道不存在',4101);
		}
		
		if($mediumInfo['status'] == RecruitmentMediumModel::BILLBOARD_CATALOGUE_STATUS_ENABLE){
			$this->service('RecruitmentMedium')->update(array('status'=>RecruitmentMediumModel::BILLBOARD_CATALOGUE_STATUS_DISABLED),$mediumId);
		}
	}
}
?>