<?php
/**
 *
 * 结算
 *
 * 销售
 *
 */
class  MarketSettlementService extends Service {
		
	
	/**
	 *
	 * 结算信息
	 *
	 * @param $field 结算字段
	 * @param $status 结算状态
	 *
	 * @reutrn array;
	 */
	public function getAllSettlementList($field = 'identity,title',$status = MarketSettlementModel::SUPPLIER_settlementING_STATUS_ENABLE){
		
		$where = array(
			'status'=>$status
		);
		
		$settlementData = $this->model('MarketSettlement')->field($field)->where($where)->select();
		
		return $settlementData;
	}
	
	/**
	 *
	 * 结算信息
	 *
	 * @param $settlementId 结算ID
	 *
	 * @reutrn array;
	 */
	public function getSettlementInfo($settlementId){
		
		$settlementData = array();
		
		$where = array(
			'identity'=>$settlementId
		);
		
		$settlementData = $this->model('MarketSettlement')->where($where)->select();
		
		if(!is_array($settlementId)){
			$settlementData = current($settlementData);
		}
		
		return $settlementData;
	}
	/**
	 *
	 * 检测结算名称
	 *
	 * @param $settlementName 结算名称
	 *
	 * @reutrn int;
	 */
	public function checkSettlementName($settlementName){
		if($settlementName){
			$where = array(
				'title'=>$settlementName
			);
			return $this->model('MarketSettlement')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除结算
	 *
	 * @param $settlementId 结算ID
	 *
	 * @reutrn int;
	 */
	public function removeSettlementId($settlementId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$settlementId
		);
		
		$settlementData = $this->model('MarketSettlement')->where($where)->select();
		if($settlementData){
			
			$output = $this->model('MarketSettlement')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 结算修改
	 *
	 * @param $settlementId 结算ID
	 * @param $settlementNewData 结算数据
	 *
	 * @reutrn int;
	 */
	public function update($settlementNewData,$settlementId){
		$where = array(
			'identity'=>$settlementId
		);
		
		$settlementData = $this->model('MarketSettlement')->where($where)->find();
		if($settlementData){
			
			$settlementNewData['lastupdate'] = $this->getTime();
			$result = $this->model('MarketSettlement')->data($settlementNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新结算
	 *
	 * @param $settlementNewData 结算信息
	 *
	 * @reutrn int;
	 */
	public function insert($settlementNewData){
		$settlementNewData['subscriber_identity'] =$this->session('uid');		
		$settlementNewData['dateline'] = $this->getTime();
			
		$settlementNewData['lastupdate'] = $settlementNewData['dateline'];
		$settlementId = $this->model('MarketSettlement')->data($settlementNewData)->add();		
		
	}
}