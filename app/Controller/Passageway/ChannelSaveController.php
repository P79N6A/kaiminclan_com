<?php
/**
 *
 * 分类编辑
 *
 * 20180301
 *
 */
class ChannelSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'channelId'=>array('type'=>'digital','tooltip'=>'分类ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'分类名称','length'=>60),
			'remark'=>array('type'=>'doc','tooltip'=>'分类介绍','length'=>200,'default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'分类状态','default'=>PassagewayChannelModel::PASSAGEWAY_CHANNEL_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$channelId = $this->argument('channelId');
		
		$title = $this->argument('title');
		$remark = $this->argument('remark');
		$status = $this->argument('status');
		
		if($channelId){
			$this->service('PassagewayChannel')->update(array('title'=>$title,'remark'=>$remark),$channelId);
		}else{
			
			if($this->service('PassagewayChannel')->checkChannel($title)){
				
				$this->info('分类已存在',4001);
			}
			
			$this->service('PassagewayChannel')->insert(array('title'=>$title,'remark'=>$remark));
		}
	}
}
?>