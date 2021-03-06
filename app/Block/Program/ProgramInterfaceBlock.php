<?php
class ProgramInterfaceBlock extends Block {
	public function getdata($param){
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		
		$cultivateId = isset($param['cultivateId'])?$param['cultivateId']:0;
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$status = isset($param['status'])?$param['status']:0;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($cultivateId){
			$where['identity'] = $cultivateId;
		}
		
		
		$listdata = $this->service('ProgramInterface')->getInterfaceList($where,$start,$perpage,$order);
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