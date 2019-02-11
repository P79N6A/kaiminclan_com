<?php
/**
 *
 * 角色锁定
 *
 * 20180301
 *
 */
class RoleLockedController extends Controller {
	
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
		
		if($groupInfo['status'] == AuthorityRoleModel::AUTHORITY_ROLE_STATUS_ENABLE){
			$this->service('AuthorityRole')->update(array('status'=>AuthorityRoleModel::AUTHORITY_ROLE_STATUS_DISABLED),$roleId);
		}
	}
}
?>