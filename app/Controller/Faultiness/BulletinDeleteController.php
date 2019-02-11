<?php
/**
 *
 * 删除缺陷
 *
 * 20180301
 *
 */
class BulletinDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'bulletinId'=>array('type'=>'digital','tooltip'=>'缺陷ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$bulletinId = $this->argument('bulletinId');
		
		$groupInfo = $this->service('FaultinessBulletin')->getBulletinInfo($bulletinId);
		
		if(!$groupInfo){
			$this->info('缺陷不存在',4101);
		}
		if(!is_array($bulletinueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('FaultinessBulletin')->removeBulletinId($removeGroupIds);
		
		$sourceTotal = count($bulletinueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>