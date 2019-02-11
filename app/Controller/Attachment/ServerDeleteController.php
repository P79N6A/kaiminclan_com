<?php
/**
 *
 * 删除资源服务器
 *
 * 20180301
 *
 */
class serverDeleteController extends Controller {
	
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
		if(!is_array($serverueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ResourcesServer')->removeServerId($removeGroupIds);
		
		$sourceTotal = count($serverueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>