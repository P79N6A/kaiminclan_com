<?php
/**
 *
 * 删除成员
 *
 * 20180301
 *
 */
class LeaguerDeleteController extends Controller {
	
	protected $permission = 'admin';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'leaguerId'=>array('type'=>'digital','tooltip'=>'成员ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$leaguerId = $this->argument('leaguerId');
		
		$groupInfo = $this->service('ProjectLeaguer')->getLeaguerInfo($leaguerId);
		
		if(!$groupInfo){
			$this->info('成员不存在',4101);
		}
		if(!is_array($leaguerueId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeGroupIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['attachment_num'] < 1){
				$removeGroupIds[] = $group['identity'];
			}
		}
		
		$this->service('ProjectLeaguer')->removeLeaguerId($removeGroupIds);
		
		$sourceTotal = count($leaguerueId);
		$successNum = count($removeGroupIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>