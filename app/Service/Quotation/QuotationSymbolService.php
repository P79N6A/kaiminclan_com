<?php
/**
 *
 * 品种
 *
 * 价格
 *
 */
class QuotationSymbolService extends Service
{
	
	/**
	 *
	 * 品种信息
	 *
	 * @param $field 品种字段
	 * @param $status 品种状态
	 *
	 * @reutrn array;
	 */
	public function getSymbolList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('QuotationSymbol')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationSymbol')->where($where);
			if($order){
				$handle->orderby($order);
			}
			
			if($start > 0 && $perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkSymbolTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('QuotationSymbol')->where($where)->count();
		}
		return 0;
	}
	/**
	 *
	 * 品种信息
	 *
	 * @param $symbolId 品种ID
	 *
	 * @reutrn array;
	 */
	public function getSymbolInfoById($symbolId){
		
		$symbolData = array();
		
		if(is_array($symbolId)){
			$symbolId = array_map('intval',$symbolId);
		}else{
			$symbolId = intval($symbolId);
		}
		
		if(empty($symbolId)){
			return $symbolData;
		}
		
		$where = array(
			'identity'=>$symbolId
		);
		
		$symbolData = $this->model('QuotationSymbol')->field('identity,title')->where($where)->select();
		if(!is_array($symbolId)){
			$symbolData = current($symbolData);
		}
		return $symbolData;
	}
	
	/**
	 *
	 * 品种信息
	 *
	 * @param $symbolId 品种ID
	 *
	 * @reutrn array;
	 */
	public function getSymbolInfo($symbolId,$field = '*'){
		
		$where = array(
			'identity'=>$symbolId
		);
		
		$symbolData = $this->model('QuotationSymbol')->field($field)->where($where)->find();
		
		return $symbolData;
	}
	
	/**
	 *
	 * 删除品种
	 *
	 * @param $symbolId 品种ID
	 *
	 * @reutrn int;
	 */
	public function removeSymbolId($symbolId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$symbolId
		);
		
		$symbolData = $this->model('QuotationSymbol')->where($where)->find();
		if($symbolData){
			
			$output = $this->model('QuotationSymbol')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 品种修改
	 *
	 * @param $symbolId 品种ID
	 * @param $symbolNewData 品种数据
	 *
	 * @reutrn int;
	 */
	public function update($symbolNewData,$symbolId){
		$where = array(
			'identity'=>$symbolId
		);
		
		$symbolData = $this->model('QuotationSymbol')->where($where)->find();
		if($symbolData){
			
			$symbolNewData['lastupdate'] = $this->getTime();
			$this->model('QuotationSymbol')->data($symbolNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新品种
	 *
	 * @param $symbolNewData 品种数据
	 *
	 * @reutrn int;
	 */
	public function insert($symbolNewData){
		
		$symbolNewData['dateline'] = $this->getTime();
			
		return $this->model('QuotationSymbol')->data($symbolNewData)->add();
	}
	public function newBond($bondId,$code,$title){
		$bondId = intval($bondId);
		if($bondId < 1){
			return -1;
		}
		$symboleData = array(
			'id'=>$bondId,
			'idtype'=>QuotationSymbolModel::QUOTATION_SYMBOL_IDTYPE_BOND,
			'code'=>$code,
			'title'=>$title
		);
		return $this->insert($symboleData);
	}
	
	public function newStock($stockId,$code,$title){
		$stockId = intval($stockId);
		if($stockId < 1){
			return -1;
		}
		$symboleData = array(
			'id'=>$stockId,
			'idtype'=>QuotationSymbolModel::QUOTATION_SYMBOL_IDTYPE_STOCK,
			'code'=>$code,
			'title'=>$title
		);
		return $this->insert($symboleData);
	}
	
	public function newFutures($futuresId,$code,$title){
		$futuresId = intval($futuresId);
		if($futuresId < 1){
			return -1;
		}
		$symboleData = array(
			'id'=>$futuresId,
			'idtype'=>QuotationSymbolModel::QUOTATION_SYMBOL_IDTYPE_FUTURES,
			'code'=>$code,
			'title'=>$title
		);
		return $this->insert($symboleData);
	}
}