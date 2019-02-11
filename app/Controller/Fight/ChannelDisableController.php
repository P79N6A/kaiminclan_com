<?php
/**
 *
 * 禁用分类
 *
 * 20180301
 *
 */
class ChannelDisableController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'channelId'=>array('type'=>'digital','tooltip'=>'分类ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$channelId = $this->argument('channelId');
		
		$groupInfo = $this->service('FightChannel')->getChannelInfo($channelId);
		if(!$groupInfo){
			$this->info('分类不存在',4101);
		}
		
		if($groupInfo['status'] == FightChannelModel::FUND_CATALOGUE_STATUS_ENABLE){
			$this->service('FightChannel')->update(array('status'=>FightChannelModel::FUND_CATALOGUE_STATUS_DISABLED),$channelId);
		}
	}
}
?>