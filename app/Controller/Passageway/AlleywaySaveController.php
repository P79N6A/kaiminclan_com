<?php
/**
 *
 * 渠道编辑
 *
 * 20180301
 *
 */
class AlleywaySaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'alleywayId'=>array('type'=>'digital','tooltip'=>'渠道ID','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'名称','length'=>60),
			'channel_identity'=>array('type'=>'digital','tooltip'=>'分类'),
			'content'=>array('type'=>'doc','tooltip'=>'地址'),
			'remark'=>array('type'=>'doc','tooltip'=>'备注','length'=>200,'default'=>''),
			'status'=>array('type'=>'digital','tooltip'=>'状态','default'=>PassagewayAlleywayModel::PASSAGEWAY_ALLEYWAY_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$alleywayId = $this->argument('alleywayId');
		
		$AlleywayData = array(
			'title' => $this->argument('title'),
			'channel_identity' => $this->argument('channel_identity'),
			'content' => $this->argument('content'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		if($alleywayId){
			$this->service('PassagewayAlleyway')->update($AlleywayData,$alleywayId);
		}else{
			
			if($this->service('PassagewayAlleyway')->checkAlleyway($title)){
				
				$this->info('渠道已存在',4001);
			}
			
			$this->service('PassagewayAlleyway')->insert($AlleywayData);
		}
	}
}
?>