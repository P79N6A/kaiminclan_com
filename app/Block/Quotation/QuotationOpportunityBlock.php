<?php
/***
 *
 * 股票行情
 * 统计分析
 */
class QuotationOpportunityBlock extends Block {
	public function getdata($param){
		
		$mode = isset($param['mode'])?$param['mode']:0;
		$start = isset($param['start'])?$param['start']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$cycle = isset($param['cycle'])?$param['cycle']:1440;
		
		$where = array();
		$where['cycle'] = $this->service('QuotationTool')->getRevolutionTime($cycle,strtotime('-1 day'));
		
		
		$listdata = $this->service('QuotationOpportunity')->getOpportunityList($where,$start,$perpage,$mode);
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