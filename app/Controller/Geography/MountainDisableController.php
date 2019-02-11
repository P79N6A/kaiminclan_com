<?php
/**
 *
 * 禁用科目
 *
 * 20180301
 *
 */
class MountainDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'mountainId'=>array('type'=>'digital','tooltip'=>'科目ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$mountainId = $this->argument('mountainId');
		
		$groupInfo = $this->service('GeographyMountain')->getMountainInfo($mountainId);
		if(!$groupInfo){
			$this->info('科目不存在',4101);
		}
		
		if($groupInfo['status'] == GeographyMountainModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('GeographyMountain')->update(array('status'=>GeographyMountainModel::PAGINATION_BLOCK_STATUS_DISABLED),$mountainId);
		}
	}
}
?>