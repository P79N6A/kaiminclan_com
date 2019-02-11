<?php
/**
 *
 * 岗位启用
 *
 * 20180301
 *
 */
class QuartersEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'quartersId'=>array('type'=>'digital','tooltip'=>'岗位ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$quartersId = $this->argument('quartersId');
		
		$quartersInfo = $this->service('RecruitmentQuarters')->getQuartersInfo($quartersId);
		if(!$quartersInfo){
			$this->info('岗位不存在',4101);
		}
		
		if($quartersInfo['status'] == RecruitmentQuartersModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('RecruitmentQuarters')->update(array('status'=>RecruitmentQuartersModel::PAGINATION_BLOCK_STATUS_ENABLE),$quartersId);
		}
		
		
	}
}
?>