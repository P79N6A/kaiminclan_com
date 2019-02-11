<?php
class IntelligenceSubstanceBlock extends Block {
	public function getdata($param){
		
		$substanceId = isset($param['substanceId'])?$param['substanceId']:0;
		$documentationId = isset($param['documentationId'])?$param['documentationId']:0;
		
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:5;
		$order = 'identity desc';
		$where = array();
		
		$where['documentation_identity'] = $documentationId;
		$listdata = $this->service('IntelligenceSubstance')->getSubstanceList($where,$start,$perpage,$order);
		
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