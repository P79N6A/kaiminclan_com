<?php
class SecuritiesConceptBlock extends Block {
	public function getdata($param){
		
		$conceptId = isset($param['conceptId'])?$param['conceptId']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		
		$status = isset($param['status'])?$param['status']:0;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($conceptId){
			$where['identity'] = $conceptId;
		}
		
		$listdata = $this->service('SecuritiesConcept')->getConceptList($where,$start,$perpage,$order);
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