<?php
/**
 *
 * 删除测试类型
 *
 * 20180301
 *
 */
class ChannelDeleteController extends Controller {
	
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
		
		$groupInfo = $this->service('FaultinessChannel')->getChannelInfo($channelId);
		
		if(!$groupInfo){
			$this->info('测试类型不存在',4101);
		}
		
		$this->service('FaultinessChannel')->removeChannelId($removeGroupIds);
		
		$sourceTotal = count($channelueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>