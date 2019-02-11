<?php
/**
 *
 * 禁用分类
 *
 * 20180301
 *
 */
class RiversDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'riversId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$riversId = $this->argument('riversId');
		
		$groupInfo = $this->service('GeographyRivers')->getRiversInfo($riversId);
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		
		if($groupInfo['status'] == GeographyRiversModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('GeographyRivers')->update(array('status'=>GeographyRiversModel::PAGINATION_BLOCK_STATUS_DISABLED),$riversId);
		}
	}
}
?>