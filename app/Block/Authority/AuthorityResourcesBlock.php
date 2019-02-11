<?php
/***
 *
 * 账户模块
 *
 */
class AuthorityResourcesBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		$idtype = isset($param['idtype'])?$param['idtype']:0;
		$id = isset($param['id'])?$param['id']:0;
		
		$where = array();
		$where['idtype'] = $idtype;
		$where['id'] = $id;
		$listdata = $this->service('AuthorityResources')->getResourcesList($where,0,0);
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}