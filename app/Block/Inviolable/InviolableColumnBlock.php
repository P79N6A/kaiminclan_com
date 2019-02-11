<?php
/***
 *
 * 目录
 * 权益
 */
class InviolableColumnBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$roleId = isset($param['roleId'])?$param['roleId']:0;
		$keyword = isset($param['kw'])?$param['kw']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		
		$where = array();
		
		if($roleId){
			$where['identity'] = $roleId;
		}
		
		if($keyword){
			$where['title'] = array('like','%'.$keyword.'%');
		}
		$order = 'identity desc';
		
		$listdata = $this->service('InviolableColumn')->getColumnList($where,$start,$perpage,$order);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}