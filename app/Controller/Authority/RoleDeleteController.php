<?php
/**
 *
 * 删除角色
 *
 * 20180301
 *
 */
class RoleDeleteController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'roleId'=>array('type'=>'digital','tooltip'=>'角色ID'),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$roleId = $this->argument('roleId');
		
		$groupInfo = $this->service('AuthorityRole')->getRoleInfo($roleId);
		
		if(!$groupInfo){
			$this->info('角色不存在',4101);
		}
		if(!is_array($roleId)){
			$groupInfo = array($groupInfo);
		}
		
		$removeRoleIds = array();
		foreach($groupInfo as $key=>$group){
			if($group['subscriber_num'] < 1){
				$removeRoleIds[] = $group['identity'];
			}
		}
		
		$this->service('AuthorityRole')->removeRoleId($removeRoleIds);
		
		$sourceTotal = count($roleId);
		$successNum = count($removeRoleIds);
		
		$this->assign('success_num',$successNum);
		$this->assign('failed_num',$sourceTotal-$successNum);
		
	}
}
?>