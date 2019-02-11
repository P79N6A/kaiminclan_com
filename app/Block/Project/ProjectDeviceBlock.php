<?php
class ProjectDeviceBlock extends Block {
	public function getdata($param){
		
		$deviceId = isset($param['deviceId'])?$param['deviceId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$order = 'identity desc';
		$where = array();
		if($deviceId){
			$where['identity'] = $deviceId;
		}
		
		$listdata = $this->service('ProjectDevice')->getDeviceList($where,$start,$perpage,$order);
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