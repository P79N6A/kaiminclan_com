<?php
/**
 *
 * 敏感词
 *
 * 安全中心
 *
 */
class  SecurityBlacklistService extends Service {
	
	
	/**
	 *
	 * 敏感词列表
	 *
	 * @param $field 角色字段
	 * @param $status 角色状态
	 *
	 * @reutrn array;
	 */
	public function getBlacklistList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('SecurityBlacklist')->where($where)->count();
		if($count){
			$listdata = $this->model('SecurityBlacklist')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 角色信息
	 *
	 * @param $roleId 角色ID
	 *
	 * @reutrn array;
	 */
	public function getRoleInfo($roleId,$field = '*'){
		
		$where = array(
			'identity'=>$roleId
		);
		
		$roleData = array();
		if(is_array($roleId)){
			$roleList = $this->model('AuthorityRole')->field($field)->where($where)->select();
			if($roleList){
				foreach($roleList as $key=>$role){
					$roleData[$role['identity']] = $role;
				}
			}
		}else{
			$roleData = $this->model('AuthorityRole')->field($field)->where($where)->find();
		}
		return $roleData;
	}
	/**
	 *
	 * 检测角色名称
	 *
	 * @param $roleName 角色名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($roleName){
		if($roleName){
			$where = array(
				'title'=>$roleName
			);
			return $this->model('AuthorityRole')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除角色
	 *
	 * @param $roleId 角色ID
	 *
	 * @reutrn int;
	 */
	public function removeRoleId($roleId){
		
		$output = 0;
		
		if(count($roleId) < 1){
			return $output;
		}
		
		$disabledRoleIds = AuthorityRoleModel::getRoleTypeList();
		foreach($roleId as $key=>$rid){
			if(in_array($rid,$disabledRoleIds)){
				unset($roleId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$roleId
		);
		
		$roleData = $this->model('AuthorityRole')->where($where)->select();
		if($roleData){
			
			$output = $this->model('AuthorityRole')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 角色修改
	 *
	 * @param $roleId 角色ID
	 * @param $roleNewData 角色数据
	 *
	 * @reutrn int;
	 */
	public function update($roleNewData,$roleId){
		$where = array(
			'identity'=>$roleId
		);
		
		$roleData = $this->model('AuthorityRole')->where($where)->find();
		if($roleData){
			
			$roleNewData['lastupdate'] = $this->getTime();
			$this->model('AuthorityRole')->data($roleNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新角色
	 *
	 * @param $roleNewData 角色信息
	 *
	 * @reutrn int;
	 */
	public function insert($roleNewData){
		if(!$roleNewData){
			return -1;
		}
		$roleNewData['subscriber_identity'] =$this->session('uid');
		$roleNewData['dateline'] = $this->getTime();
		
		$this->model('AuthorityRole')->data($roleNewData)->add();
	}
}