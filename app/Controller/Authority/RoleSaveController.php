<?php
/**
 *
 * 角色编辑
 *
 * 20180301
 *
 */
class RoleSaveController extends Controller {
	
	protected $permission = 'user';
	protected $method = 'post';
	
	/***
	 * 输入参数
	 */
	protected function setting(){
		return array(
			'roleId'=>array('type'=>'digital','tooltip'=>'角色ID','default'=>0),
			'code'=>array('type'=>'letter','tooltip'=>'角色编码','default'=>''),
			'role_identity'=>array('type'=>'digital','tooltip'=>'隶属角色','default'=>0),
			'title'=>array('type'=>'string','tooltip'=>'角色名称','length'=>60),
			'remark'=>array('type'=>'doc','tooltip'=>'角色介绍','length'=>200,'default'=>''),
			'permission'=>array('type'=>'digital','tooltip'=>'角色权限','default'=>0),
			'status'=>array('type'=>'digital','tooltip'=>'角色状态','default'=>AuthorityRoleModel::AUTHORITY_ROLE_STATUS_ENABLE),
		);
	}
	/**
	 * 业务
	 */
	public function fire(){
		
		$roleId = $this->argument('roleId');
		
		$permission = json_encode($this->argument('permission'));
			
		$roleData = array(
			'code' => $this->argument('code'),
			'role_identity' => $this->argument('role_identity'),
			'title' => $this->argument('title'),
			'remark' => $this->argument('remark'),
			'status' => $this->argument('status')
		);
		
		if(!$roleData['role_identity']){
			$roleData['role_identity'] = $this->session('roleId');
		}
		
		if($roleId){
			$this->service('AuthorityRole')->update($roleData,$roleId);
		}else{
			
			if($this->service('AuthorityRole')->checkTitle($roleData['title'])){
				
				$this->info('角色已存在',4001);
			}
			
			$this->service('AuthorityRole')->insert($roleData);
		}
	}
}
?>