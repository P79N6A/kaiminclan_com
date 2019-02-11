<?php
/**
 *
 * 数据收集
 *
 * 统计分析
 *
 */
class QuotationPositionService extends QuotationToolService 
{
	//账户ID
	private $accountId = 0;
	//开仓
	private $purchase;
	//平仓
	private $shipments;
	//盈利
	private $profit;
	//时间
	private $period;
	
	private $positionData = array();
	public function account($accountId){
		$this->accountId= $accountId;
		return $this;
	}
	
	
	public function purchase($purchase){
		$this->positionData['purchase'] = $purchase;
		return $this;
	}
	
	
	public function shipments($shipments){
		$this->positionData['shipments'] = $shipments;
		return $this;
	}
	
	
	public function profit($profit){
		$this->positionData['profit'] = $profit;
		return $this;
	}
	
	
	public function period($period){
		$this->period= $period;
		return $this;
	}
	
	public function push(){
		$subTable = date('Y_m');
		
		$cycle = $this->getRevolutionTime(1440,$this->period);
		if(substr($cycle,0,4) !=  date('Y')){
            return 0;
        }
		
		$where = array(
			'account_identity'=>$this->accountId,
			'cycle'=>$cycle
		);
		
		$positionData = $this->model('QuotationPosition')->subtable($subTable)->where($where)->find();
		if($positionData){
			
			if(isset($this->positionData['shipments'])){
				$this->positionData['shipments'] += $positionData['shipments'];
			}
			
			if(isset($this->positionData['purchase'])){
				$this->positionData['purchase'] += $positionData['purchase'];
			}
			
			$this->positionData['valume'] = $positionData['valume']+1;
			if(isset($this->positionData['profit'])){
				if($this->positionData['profit'] < 0){
					$this->positionData['fail'] = $positionData['fail']+1;
					$this->positionData['win'] = $positionData['win'];
				}else{
					$this->positionData['fail'] = $positionData['fail'];
					$this->positionData['win'] = $positionData['win']+1;
				}
				$this->positionData['profit'] += $positionData['profit'];
			}
			
			$this->model('QuotationPosition')->subtable($subTable)->data($this->positionData)->where($where)->save();
		}else{
			
			$this->positionData['account_identity'] = $this->accountId;
			$this->positionData['cycle'] = $cycle;
			if(isset($this->positionData['profit'])){
				if($this->positionData['profit'] < 0){
					$this->positionData['fail'] = 1;
					$this->positionData['win'] = 0;
				}else{
					$this->positionData['fail'] = 0;
					$this->positionData['win'] = 1;
				}
			}
			$this->model('QuotationPosition')->subtable($subTable)->data($this->positionData)->add();
		}
	}
	
}