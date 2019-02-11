<?php
/***
 *
 * 角色模块
 *
 */
class AuthorityRoleBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$roleId = isset($param['roleId'])?$param['roleId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:0;
		$keyword = isset($param['kw'])?$param['kw']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$order = isset($param['order'])?$param['order']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$where = array();
		
		if($roleId){
			$where['identity'] = $roleId;
		}
		
		if($keyword){
			$where['title'] = array('like','%'.$keyword.'%');
		}
		if($parentId != -1){
			$where['role_identity'] = $parentId;
		}
		if($status != -1){
			$where['status'] = $status;
		}
		$order = 'identity desc';
		
		$listdata = $this->service('AuthorityRole')->getRoleList($where,$start,$perpage,$order);
		if($listdata['total'] && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}