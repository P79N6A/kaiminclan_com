<?php
class GeographyDistrictBlock extends Block {
	public function getdata($param){
		
		$districtId = isset($param['districtId'])?$param['districtId']:0;
		$parentId = isset($param['parentId'])?$param['parentId']:-1;
		$start = isset($param['start'])?$param['start']:-1;
		$perpage = isset($param['perpage'])?$param['perpage']:-1;
		
		$status = isset($param['status'])?$param['status']:-1;
		$order = 'identity desc';
		$where = array();
		if($status != -1){
			$where['status'] = $status;
		}
		if($districtId){
			$where['identity'] = $districtId;
			
		}
		if($parentId != -1){
			$where['district_identity'] = $parentId;
			
		}
		
		$listdata = $this->service('GeographyDistrict')->getDistrictList($where,$start,$perpage,$order);
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