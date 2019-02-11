<?php
/**
 *
 * 禁用测试类型
 *
 * 20180301
 *
 */
class ChannelDisableController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'channelId'=>array('type'=>'digital','tooltip'=>'测试类型ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$channelId = $this->argument('channelId');
		
		$groupInfo = $this->service('FaultinessChannel')->getTemplateInfo($channelId);
		if(!$groupInfo){
			$this->info('测试类型不存在',4101);
		}
		
		if($groupInfo['status'] == FaultinessChannelModel::PAGINATION_TEMPLATE_STATUS_ENABLE){
			$this->service('FaultinessChannel')->update(array('status'=>FaultinessChannelModel::PAGINATION_TEMPLATE_STATUS_DISABLED),$channelId);
		}
	}
}
?>