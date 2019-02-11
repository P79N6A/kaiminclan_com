<?php
/***
 *
 * 监管
 *
 */
class IntercalateSuperviseBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$superviseId = isset($param['superviseId'])?$param['superviseId']:0;
		
		$where = array();
		if($superviseId){
			$where['identity'] = $superviseId;
		}
				
		$listdata = $this->service('IntercalateSupervise')->getSuperviseList($where,$start,$perpage);
		if($listdata['total'] > 0 && $perpage == 1){
			$listdata['list'] = current($listdata['list']);
		}
		
		return array('data'=>$listdata['list'],'total'=>$listdata['total'],'perpage'=>$perpage,'start'=>$start);
	}
}