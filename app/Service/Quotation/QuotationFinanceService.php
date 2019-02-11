<?php
/**
 *
 * 财务
 *
 * 收入
 * 按业务，按地区，按账户（银行账户，企业主题），按科目
 *
 * 支出
 * 按业务，按地区，按账户，按科目
 * 调账
 * 按业务，按地区，按账户
 * 投资
 * 主体数，资金数，投资数
 * 按业务，按地区，按项目
 * 融资
 * 机构数，资金数，融资数
 * 税务
 *
 */
class QuotationFinanceService extends QuotationToolService
{
	//账户ID
	private $accountId = 0;
	//时间
	private $period;

	
	private $financeData = array();
	public function account($accountId){
		$this->accountId= $accountId;
		return $this;
	}

    /**
     * 转出金额
     * @param $rollout
     *
     * @return $this
     */
	public function rollout($rollout){
		$this->financeData['rollout'] = $rollout;
		return $this;
	}

    /**
     * 转入金额
         * @param $shiftto
     *
     * @return $this
     */
    public function shiftto($shiftto){
        $this->financeData['shiftto'] = $shiftto;
        return $this;
    }


    /**
     * 收入
     * @param $revenue
     *
     * @return $this
     */
	public function revenue($revenue){
		$this->financeData['revenue'] = $revenue;
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