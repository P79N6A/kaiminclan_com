<?php
/**
 *
 * 解除服务器锁定
 *
 * 20180301
 *
 */
class serverUnlockController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'serverId'=>array('type'=>'digital','tooltip'=>'服务器ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$serverId = $this->argument('serverId');
		
		$groupInfo = $this->service('ResourcesServer')->getServerInfo($serverId);
		if(!$groupInfo){
			$this->info('服务器不存在',4101);
		}
		
		if($groupInfo['status'] == ResourcesServerModel::RESOURCES_SERVER_STATUS_LOCKED){
			$this->service('ResourcesServer')->update(array('status'=>ResourcesServerModel::RESOURCES_SERVER_STATUS_ENABLE),$serverId);
		}
		
		
	}
}
?>