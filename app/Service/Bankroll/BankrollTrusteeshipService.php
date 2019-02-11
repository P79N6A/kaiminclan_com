<?php
/**
 *
 * 转入
 *
 * 资金
 *
 */
class  BankrollTrusteeshipService extends Service {
	
	
	
	/**
	 *
	 * 托管列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getTrusteeshipList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BankrollTrusteeship')->where($where)->count();
		if($count){
			$trusteeshipHandle = $this->model('BankrollTrusteeship')->where($where)->orderby($orderby);
			if($start && $perpage){
				$trusteeshipHandle = $trusteeshipHandle->limit($start,$perpage,$count);
			}
			$listdata = $trusteeshipHandle->select();
			$accountIds = $bankcardIds = array();
			foreach($listdata as $key=>$data){
				$bankcardIds[] = $data['finance_account_identity'];
				$accountIds[] = $data['account_identity'];
			}
			
			$accountData = $this->service('BankrollAccount')->getAccountInfo($accountIds);
			$bankcardData = $this->service('MechanismBankcard')->getBankcardInfo($bankcardIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['account'] = isset($accountData[$data['account_identity']])?$accountData[$data['account_identity']]:array();
				$listdata[$key]['bankcard'] = isset($bankcardData[$data['finance_account_identity']])?$bankcardData[$data['finance_account_identity']]:array();
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}

	public function getFinanceAccountIdByAccountId($accountId){
        $accountData = array();

        $where = array(
            'account_identity'=>$accountId
        );

        $listdata = $this->model('BankrollTrusteeship')->where($where)->select();
        if(!$listdata){
            return $accountData;
        }
        foreach ($listdata as $key=>$data){
            $accountData[$data['account_identity']] = $data['finance_account_identity'];
        }

        return $accountData;
    }
	/**
	 *
	 * 托管信息
	 *
	 * @param $trusteeshipIds 托管ID
	 *
	 * @reutrn int;
	 */
	public function getTrusteeshipInfo($trusteeshipIds){
		$trusteeshipData = array();
		
		$where = array(
			'identity'=>$trusteeshipIds
		);
		
		$trusteeshipList = $this->model('BankrollTrusteeship')->where($where)->select();
		if($trusteeshipList){
			
			if(is_array($trusteeshipIds)){
				$trusteeshipData = $trusteeshipList;
			}else{
				$trusteeshipData = current($trusteeshipList);
			}
			
			
		}
		
		
		return $trusteeshipData;
	}
	
	
		
	/**
	 *
	 * 删除托管
	 *
	 * @param $trusteeshipId 托管ID
	 *
	 * @reutrn int;
	 */
	public function removeTrusteeshipId($trusteeshipId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$trusteeshipId
		);
		
		$trusteeshipData = $this->model('BankrollTrusteeship')->where($where)->count();
		if($trusteeshipData){
			
			$output = $this->model('BankrollTrusteeship')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测托管
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkTrusteeship($accountId,$bankcardId){
		$trusteeshipId = array();		
		$where = array(
			'account_identity'=>intval($accountId),
			'bankcard_identity'=>intval($cardId),
		);
		
		return $this->model('BankrollTrusteeship')->where($where)->count();
	}
	
	/**
	 *
	 * 托管修改
	 *
	 * @param $trusteeshipId 托管ID
	 * @param $trusteeshipNewData 托管数据
	 *
	 * @reutrn int;
	 */
	public function update($trusteeshipNewData,$trusteeshipId){
		$where = array(
			'identity'=>$trusteeshipId
		);
		
		$trusteeshipData = $this->model('BankrollTrusteeship')->where($where)->find();
		if($trusteeshipData){
			
			
			$trusteeshipNewData['lastupdate'] = $this->getTime();
			$this->model('BankrollTrusteeship')->data($trusteeshipNewData)->where($where)->save();
			
			
		}
	}
	
	/**
	 *
	 * 新托管
	 *
	 * @param $id 托管信息
	 * @param $idtype 托管信息
	 *
	 * @reutrn int;
	 */
	public function insert($trusteeshipData){
		$dateline = $this->getTime();
		
		$trusteeshipData['subscriber_identity'] = $this->session('uid');
		$trusteeshipData['dateline'] = $dateline;
		$trusteeshipData['sn'] = $this->get_sn();
		$trusteeshipData['lastupdate'] = $dateline;
		$trusteeshipId = $this->model('BankrollTrusteeship')->data($trusteeshipData)->add();
		
		return $trusteeshipId;
		
	}
}