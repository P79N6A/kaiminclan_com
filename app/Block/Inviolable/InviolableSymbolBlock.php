<?php
/***
 *
 * 品种
 * 权益
 */
class InviolableSymbolBlock extends Block {
	/**
	 * @param 参数集
	 */
	public function getdata($param){
		
		$roleId = isset($param['symbolId'])?$param['symbolId']:0;
		$symbolId = isset($param['kw'])?$param['kw']:0;
		$perpage = isset($param['perpage'])?$param['perpage']:0;
		$start = isset($param['start'])?$param['start']:0;
		$mode = isset($param['mode'])?$param['mode']:0;
		
		
		$where = array();
		
		if($symbolId){
			$where['identity'] = $symbolId;
		}
		
		if($keyword){
			$where['title'] = array('like','%'.$keyword.'%');
		}
		
		if($mode){
			$authorizeData = $this->service('InviolablePermission')->getAuthorizeByEmployeeId($this->session('employee_identity'));
			$where['idtype'] = $authorizeData['industry'];
			if(!empty($authorizeData['symbol'])){
				$where['id'] = $authorizeData['symbol'];
			}
			if(!empty($authorizeData['column'])){
				$where['column_identity'] = $authorizeData['column'];
			}
		}
		
		$order = 'identity desc';
		
		
		$listdata = $this->service('InviolableSymbol')->getSymbolList($where,$start,$perpage,$order);
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