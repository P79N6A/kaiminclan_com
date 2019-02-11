<?php
class PropertyCapitalBlock extends Block {
	public function getdata($param){
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		
		$capitalId = isset($param['capitalId'])?$param['capitalId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($capitalId){
			$where['identity'] = $capitalId;
		}
		
		if($parentId != -1){
			$where['capital_identity'] = $parentId;
		}
		
		
		$listdata = $this->service('PropertyCapital')->getCapitalList($where,$start,$perpage,$order);
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