<?php
/**
 *
 * 应用启用
 *
 * 20180301
 *
 */
class ApplicationEnableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'applicationId'=>array('type'=>'digital','tooltip'=>'应用ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$applicationId = $this->argument('applicationId');
		
		$groupInfo = $this->service('ProgramApplication')->getApplicationInfo($applicationId);
		if(!$groupInfo){
			$this->info('应用不存在',4101);
		}
		
		if($groupInfo['status'] == ProgramApplicationModel::PAGINATION_BLOCK_STATUS_DISABLED){
			$this->service('ProgramApplication')->update(array('status'=>ProgramApplicationModel::PAGINATION_BLOCK_STATUS_ENABLE),$applicationId);
		}
		
		
	}
}
?>