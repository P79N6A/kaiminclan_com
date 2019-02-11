<?php
class SecuritiesIndustryBlock extends Block {
	public function getdata($param){
		
		$industryId = isset($param['industryId'])?$param['industryId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:0;
		$start = 1;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$status = isset($param['status'])?$param['status']:-1;
		$order = 'identity desc';
		$where = array();
		
		if($industryId){
			$where['identity'] = $industryId;
		}
		
		if($status != -1){
			$where['status'] = $status;
		}
		
		
		$where['industry_identity'] = $parentId;
		
		$listdata = $this->service('SecuritiesIndustry')->getIndustryList($where,$start,$perpage,$order);
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