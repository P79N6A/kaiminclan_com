<?php
/**
 *
 * 科目启用
 *
 * 20180301
 *
 */
class ReligionEnableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'religionId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$religionId = $this->argument('religionId');
		
		$groupInfo = $this->service('GeographyReligion')->getReligionInfo($religionId);
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		if($groupInfo['status'] == GeographyReligionModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('GeographyReligion')->update(array('status'=>GeographyReligionModel::PAGINATION_BLOCK_STATUS_ENABLE),$religionId);
		}
		
		
	}
}
?>