<?php
class PermanentFashionBlock extends Block {
	public function getdata($param){
		
		$fashionId = isset($param['fashionId'])?$param['fashionId']:0;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($fashionId){
			$where['identity'] = $fashionId;
		}
		$listdata = $this->service('PermanentFashion')->getFashionList($where,$start,$perpage,$order);
		if($perpage == 1){
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