<?php
/***
 *
 * 收藏模块
 *
 */
class AuthorityCollectionBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:1;
		$start = isset($param['start'])?$param['start']:1;
		
		$idtype = isset($param['idtype'])?$param['idtype']:'good';
		$idtypeData = array('good'=>1,'store'=>6);
		
		$where = array();
		$where['idtype'] = $idtypeData[$idtype];
		$where['subscriber_identity'] = $this->session('uid');
				
		$listdata = $this->service('AuthorityCollection')->getAllCollectionList($where,$start,$perpage);
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}