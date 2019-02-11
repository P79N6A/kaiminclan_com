<?php
/**
 *
 * 删除资源渠道
 *
 * 20180301
 *
 */
class AlleywayDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'alleywayId'=>array('type'=>'digital','tooltip'=>'渠道ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$alleywayId = $this->argument('alleywayId');
		
		$groupInfo = $this->service('PassagewayAlleyway')->getAlleywayInfo($alleywayId);
		
		if(!$groupInfo){
			$this->info('渠道不存在',4101);
		}
		if(!is_array($AlleywayueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('PassagewayAlleyway')->removeAlleywayId($removeGroupIds);
		
		$sourceTotal = count($AlleywayueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>