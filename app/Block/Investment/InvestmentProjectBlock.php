<?php
class InvestmentProjectBlock extends Block {
	public function getdata($param){
		
		$projectId = isset($param['projectId'])?$param['projectId']:0;
		$industryId = isset($param['industryId'])?$param['industryId']:0;
		$kw = isset($param['kw'])?$param['kw']:'';
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:-1;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		
		if($projectId){
			$where['identity'] = $projectId;
		}
		if($industryId){
			$where['industry_identity'] = $industryId;
		}
		
		if($kw){
			$where['title'] = array('like','%'.$kw.'%');
		}
		
		$listdata = $this->service('InvestmentProject')->getProjectList($where,$start,$perpage,$order);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage,
			'filter'=>$where
		);
	}
}