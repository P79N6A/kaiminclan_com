<?php
class FoundationIndicentBlock extends Block {
	public function getdata($param){
		
		$keyword = isset($param['kw'])?$param['kw']:0;
		
		$districtId = isset($param['districtId'])?$param['districtId']:0;
		
		$perpage = isset($param['perpage'])?$param['perpage']:1;
		$start = isset($param['start'])?$param['start']:1;
		
		$mode = isset($param['mode'])?intval($param['mode']):0;
		
		$order = 'identity desc';
		$where = array();
		
		
		$listdata = $this->service('FoundationIndicent')->getIndicentList($where,$start,$perpage,$order);
		
		return array(
			'data'=>$listdata['list'],
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}