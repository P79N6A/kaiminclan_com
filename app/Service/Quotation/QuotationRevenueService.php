<?php
/**
 *
 * 财务
 *
 * 收入统计
 * 统计对象
 * 账户
 * 组织
 *
 * 按业务，按地区，按科目
 *
 */
class QuotationRevenueService extends QuotationToolService
{
	//账户ID
	private $accountId = 0;
	//时间
	private $period;

	
	private $revenueData = array();
	public function account($accountId){
		$this->accountId= $accountId;
		return $this;
	}

    /**
     * 支出
     * @param $expenses
     *
     * @return $this
     */
    public function expenses($expenses){
        $this->financeData['expenses'] = $expenses;
        return $this;
    }

	
	public function period($period){
		$this->period= $period;
		return $this;
	}
	
	public function push(){
		$subTable = date('Y_m');
		
		$cycle = $this->getRevolutionTime(1440,$this->period);
		
		$where = array(
			'account_identity'=>$this->accountId,
			'cycle'=>$cycle
		);
		
		$financeData = $this->model('QuotationFinance')->subtable($subTable)->where($where)->find();
		if($financeData){
			
			if(isset($this->financeData['expenses'])){
				$this->financeData['expenses'] += $financeData['expenses'];
			}
			
			if(isset($this->financeData['revenue'])){
				$this->financeData['revenue'] += $financeData['revenue'];
			}

            if(isset($this->financeData['shiftto'])){
                $this->financeData['shiftto'] += $financeData['shiftto'];
            }
            if(isset($this->financeData['rollout'])){
                $this->financeData['rollout'] += $financeData['rollout'];
            }
			
			$this->model('QuotationFinance')->subtable($subTable)->data($this->financeData)->where($where)->save();
		}else{
			
			$this->financeData['account_identity'] = $this->accountId;
			$this->financeData['cycle'] = $cycle;

			$this->model('QuotationFinance')->subtable($subTable)->data($this->financeData)->add();
		}
	}
	
}