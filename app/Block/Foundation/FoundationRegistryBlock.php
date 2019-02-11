<?php
class FoundationRegistryBlock extends Block {
	public function getdata($param){
		
		$bz = isset($param['bz'])?$param['bz']:0;
		
		$where = array();
		$where['code'] = $bz;
		
		
		$listdata = $this->service('FoundationRegistry')->getRegistryByCode($bz);
		
		return array(
			'data'=>$listdata,
			'total'=>$listdata['total'],
			'start'=>$start,
			'perpage'=>$perpage
		);
	}
}