<?php
/**
 *
 * 账户
 *
 * 账户
 *
 */
class  BankrollAccountService extends Service {
	
	public function adjustAmount($accountId,$amount){
		if(is_array($accountId)){
			list($accountId) = $accountId;
		}
		
		if(!empty($accountId)){
			$where = array(
				'identity'=>$accountId
			);
			
			$accountInfo = $this->getAccountInfo($accountId);
			if(isset($accountInfo[$accountId])){
				$accountInfo = $accountInfo[$accountId];
			}

			$accountData = $this->service('BankrollTrusteeship')->getFinanceAccountIdByAccountId($accountId);

			if(strpos($amount,'-') !== false){
				$this->model('BankrollAccount')->where($where)->setDec('amount',substr($amount,1));
				$this->service('BankrollSubsidiary')->newLeave(($accountId),$amount);
				//增加收入
                $this->service('DealingsRevenue')->push($accountData[$accountId],$accountInfo['title'].'【资金调出】',substr($amount,1));
			}else{
				$this->model('BankrollAccount')->where($where)->setInc('amount',$amount);
				$this->service('BankrollSubsidiary')->newIncome(($accountId),$amount);
                //增加支出
                $this->service('DealingsExpenses')->push($accountData[$accountId],$accountInfo['title'].'【资金补充】',-$amount);
			}
		}
	}


    /**
     *
     * 获取账户可用余额
     *
     * @param $accountId
     *
     * @return int
     */
	public function getBalanceByAid($accountId){
	    $where = array(
	        'identity'=>$accountId
        );
	    $accountData = $this->model('BankrollAccount')->where($where)->find();
	    if(!$accountData){
	        return 0;
        }
	    return $accountData['amount'];
    }
	
	/**
	 *
	 * 按账户编号获取账户信息
	 *
	 * @param $code 代码
	 *
	 * @reutrn array;
	 */
	public function getStockInfoByCode($code){
		
		$where = array(
			'code'=>$code
		);
		
		
		return $this->model('BankrollAccount')->where($where)->find();
	}
	
	/**
	 *
	 * 收藏列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getAccountList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BankrollAccount')->where($where)->count();
		if($count){
			$accountHandle = $this->model('BankrollAccount')->where($where)->orderby($orderby);
			if($perpage){
				$accountHandle = $accountHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$accountHandle->order($orderby);
			}
			$listdata = $accountHandle->select();
			
			$currencyIds = $capitalIds = array();
			foreach($listdata as $key=>$data){
				$capitalIds[] = $data['capital_identity'];
				$currencyIds[] = $data['currency_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>BankrollAccountModel::getStatusTitle($data['status'])
				);
			}
			$capitalData = $this->service('PropertyCapital')->getCapitalInfo($capitalIds);
			$currencyData = $this->service('MechanismCurrency')->getCurrencyInfo($currencyIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['capital'] = isset($capitalData[$data['capital_identity']])?$capitalData[$data['capital_identity']]:array();
				$listdata[$key]['currency'] = isset($currencyData[$data['currency_identity']])?$currencyData[$data['currency_identity']]:array();
			}
			
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	public function getAccountData(){
		$where = array();
		$where['employee_identity'] = $this->session('employee_identity');
		$where['status'] = BankrollAccountModel::AUTHORITY_COLLECTION_STATUS_ENABLE;
		
		$accountData = $this->model('BankrollAccount')->where($where)->find();
		
		return $accountData;
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $accountIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getAccountInfo($accountIds){
		$accountData = array();
		
		$where = array(
			'identity'=>$accountIds
		);
		
		$accountList = $this->model('BankrollAccount')->where($where)->select();
		if($accountList){
			
			if(is_array($accountIds)){
				$accountData = $accountList;
			}else{
				$accountData = current($accountList);
			}
			
			
		}
		
		
		return $accountData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $accountId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeAccountId($accountId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$accountId
		);
		
		$accountData = $this->model('BankrollAccount')->where($where)->count();
		if($accountData){
			
			$output = $this->model('BankrollAccount')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkAccount($idtype,$id,$uid){
		$accountId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$accountList = $this->model('BankrollAccount')->field('identity,id')->where($where)->select();
		
		if($accountList){
			
			foreach($accountList as $key=>$account){
				$accountId[$account['identity']] = $account['id'];
			}
		}
		return $accountId;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $accountId 收藏ID
	 * @param $accountNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($accountNewData,$accountId){
		$where = array(
			'identity'=>$accountId
		);
		
		$accountData = $this->model('BankrollAccount')->where($where)->find();
		if($accountData){
			
			
			$accountNewData['lastupdate'] = $this->getTime();
			$this->model('BankrollAccount')->data($accountNewData)->where($where)->save();
			if($accountData['broker_identity'] != $accountNewData['broker_identity']){
				$this->service('IntercalateBroker')->adjustAccount($accountNewData['broker_identity']);
				$this->service('IntercalateBroker')->adjustAccount($accountData['broker_identity'],-1);
			}
			if($accountNewData['status'] == BankrollAccountModel::BANKROLL_ACCOUNT_STATUS_ENABLE){
				$this->service('AuthoritySubscriber')->newTradeUser($accountId,$accountNewData['code'],$accountNewData['title']);
			}
			
			
		}
	}
	
	/**
	 *
	 * 新收藏
	 *
	 * @param $id 收藏信息
	 * @param $idtype 收藏信息
	 *
	 * @reutrn int;
	 */
	public function insert($accountData){
		$dateline = $this->getTime();
		$accountData['subscriber_identity'] = $this->session('uid');
		$accountData['dateline'] = $dateline;
		$accountData['lastupdate'] = $dateline;
		$accountData['sn'] = $this->get_sn();
		
		$accountId = $this->model('BankrollAccount')->data($accountData)->add();
		if($accountId){
			$this->service('IntercalateBroker')->adjustAccount($accountData['broker_identity']);
			if($accountData['status'] == BankrollAccountModel::BANKROLL_ACCOUNT_STATUS_ENABLE){
				$this->service('AuthoritySubscriber')->newTradeUser($accountId,$accountData['code'],$accountData['title']);
			}
		}
		return $accountId;
	}
}