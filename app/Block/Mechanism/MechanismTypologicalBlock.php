<?php
class MechanismTypologicalBlock extends Block {
	public function getdata($param){
		
		$typologicalId = isset($param['typologicalId'])?$param['typologicalId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:10;
		$order = 'identity desc';
		$where = array();
		if($typologicalId){
			$where['identity'] = $typologicalId;
			
		}
		
		$listdata = $this->service('MechanismTypological')->getTypologicalList($where,$start,$perpage,$order);
		if($perpage < 2){
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