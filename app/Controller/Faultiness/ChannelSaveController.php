<?php
/**
 *
 * 测试类型编辑
 *
 * 20180301
 *
 */
class ChannelSaveController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'channelId'=>array('type'=>'digital','tooltip'=>'测试类型ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'标题','length'=>80),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','default'=>0),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$channelId = $this->argument('channelId');
		
		$setarr = array(
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark')
		);
		
		if($channelId){
			$this->service('FaultinessChannel')->update($setarr,$channelId);
		}else{
			
			if($this->service('FaultinessChannel')->checkChannelTitle($setarr['title'])){
				
				$this->info('测试类型已存在',4001);
			}
			
			$this->service('FaultinessChannel')->insert($setarr);
		}
	}
}
?>