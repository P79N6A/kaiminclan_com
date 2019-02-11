<?php
/**
 *
 * 禁用成员
 *
 * 20180301
 *
 */
class LeaguerDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'leaguerId'=>array('type'=>'digital','tooltip'=>'成员ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$leaguerId = $this->argument('leaguerId');
		
		$groupInfo = $this->service('ProjectLeaguer')->getLeaguerInfo($leaguerId);
		if(!$groupInfo){
			$this->info('成员不存在',4101);
		}
		
		if($groupInfo['status'] == ProjectLeaguerModel::PAGINATION_BLOCK_STATUS_ENABLE){
			$this->service('ProjectLeaguer')->update(array('status'=>ProjectLeaguerModel::PAGINATION_BLOCK_STATUS_DISABLED),$leaguerId);
		}
	}
}
?>