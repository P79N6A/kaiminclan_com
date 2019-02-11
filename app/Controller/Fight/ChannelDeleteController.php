<?php
/**
 *
 * 删除分类
 *
 * 20180301
 *
 */
class ChannelDeleteController extends Controller {
	
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
		
		$channelInfo = $this->service('FightChannel')->getChannelInfo($channelId);
		
		if(!$channelInfo){
			$this->info('分类不存在',4101);
		}
		if(!is_array($channelueId)){
			$channelInfo = array($channelInfo);
		}
		
		$removeChannelIds = array();
		foreach($channelInfo as $key=>$channel){
				$removeChannelIds[] = $channel['identity'];
		}
		
		$this->service('FightChannel')->removeChannelId($removeChannelIds);
		
		$sourceTotal = count($channelueId);
		$successNum = count($removeChannelIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>