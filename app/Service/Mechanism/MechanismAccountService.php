<?php
/**
 *
 * 账户
 *
 * 财务
 *
 */
class MechanismAccountService extends Service
{
	
	
	public function adjustAmount($accountId,$amount,$remarkData){
		
		if($accountId < 1){
			return -1;
		}

		$accountData = $this->getAccountInfo($accountId);
		if(isset($accountData[$accountId])){
			$accountData = $accountData[$accountId];
		}
		switch($accountData['idtype']){
            case MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_CREDIT:
                $this->service('PermanentCredit')->adjustAmount($accountData['id'],$amount,$remarkData);
                break;
        }
		
		
		$where = array(
			'identity'=>$accountId
		);
		if(strpos($amount,'-') !== false){
			$this->model('MechanismAccount')->where($where)->setDec('amount',substr($amount,1));
		}else{
			$this->model('MechanismAccount')->where($where)->setInc('amount',$amount);
		}
		
	}
	/**
	 *
	 * 账户信息
	 *
	 * @param $field 账户字段
	 * @param $status 账户状态
	 *
	 * @reutrn array;
	 */
	public function getAccountList($where,$start,$perpage,$order = 'identity desc'){
		
		$count = $this->model('MechanismAccount')->where($where)->count();
		if($count){
			$handle = $this->model('MechanismAccount')->where($where);
			if($order){
				$handle->orderby($order);
			}
			if($perpage > 0){
				$handle->limit($start,$perpage,$count);
			}
			$listdata = $handle->select();
			$typologicalIds = array();
			foreach($listdata as $key=>$data){
				$typologicalIds[] = $data['typological_identity'];
			}
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MechanismAccountModel::getStatusTitle($data['status'])
				);
			}
			$typologicalData = $this->service('MechanismTypological')->getTypologicalInfo($typologicalIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['typological'] = isset($typologicalData[$data['typological_identity']])?$typologicalData[$data['typological_identity']]:array();
			}
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
	public function checkAccountTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('MechanismAccount')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 账户信息
	 *
	 * @param $accountId 账户ID
	 *
	 * @reutrn array;
	 */
	public function getAccountInfo($accountId,$field = '*'){
		$accountData = array();
		
		if(!is_array($accountId)){
			$accountId = array($accountId);
		}
		
		$accountId = array_filter(array_map('intval',$accountId));
		
		if(!empty($accountId)){
		
			$where = array(
				'identity'=>$accountId
			);
			
			$accountData = $this->model('MechanismAccount')->field($field)->where($where)->select();
		}
		
		return $accountData;
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
        $accountData = $this->model('MechanismAccount')->where($where)->find();
        if(!$accountData){
            return 0;
        }
        return $accountData['amount'];
    }
	
	/**
	 *
	 * 删除账户
	 *
	 * @param $accountId 账户ID
	 *
	 * @reutrn int;
	 */
	public function removeAccountId($accountId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$accountId
		);
		
		$accountData = $this->model('MechanismAccount')->where($where)->find();
		if($accountData){
			
			$output = $this->model('MechanismAccount')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 账户修改
	 *
	 * @param $accountId 账户ID
	 * @param $accountNewData 账户数据
	 *
	 * @reutrn int;
	 */
	public function update($accountNewData,$accountId){
		$where = array(
			'identity'=>$accountId
		);
		
		$accountData = $this->model('MechanismAccount')->where($where)->find();
		if($accountData){
			
			$accountNewData['lastupdate'] = $this->getTime();
			$this->model('MechanismAccount')->data($accountNewData)->where($where)->save();
		}
	}
	
	/**
	 * 修改企业账户
	 */
	public function changeCompany($companyId,$companyName){
				
		$where = array(
			'id'=>$companyId,
			'idtype'=>MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_COMPANY
		);
		
		
		$accountData = $this->model('MechanismAccount')->data('identity')->where($where)->find();
		if(!$accountData){
			return -2;
		}
		$accountNewData = array();
		$accountNewData['title'] = $companyName;
		
		return $this->update($accountNewData,$accountData['identity']);
	}
	
	/**
	 *
	 * 新企业账户
	 *
	 */
	public function newCompany($companyId,$companyName){
		
		$companyId = intval($companyId);
		if($companyId < 1){
			return -1;
		}
		
		
		$accountNewData = array(
			'id'=>$companyId,
			'idtype'=>MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_COMPANY
		);
		
		$where = $accountNewData;
		
		if($this->model('MechanismAccount')->where($where)->count()){
			return -2;
		}
		
		$accountNewData['title'] = $companyName;
		
		return $this->insert($accountNewData);
		
	}
	
	/**
	 *
	 * 新账户
	 *
	 * @param $accountNewData 账户数据
	 *
	 * @reutrn int;
	 */
	public function insert($accountNewData){
		
		$accountNewData['subscriber_identity'] =$this->session('uid');
		$accountNewData['dateline'] = $this->getTime();
		$accountNewData['sn'] = $this->get_sn();
			
		$accountNewData['lastupdate'] = $accountNewData['dateline'];
		return $this->model('MechanismAccount')->data($accountNewData)->add();
	}

    /**
     *
     * 融资账户
     * @param $creditId
     * @param $creditName
     * @param $amount
     * @param $bankId
     */
	public function newCreditAccount($creditId,$creditName,$amount,$bankId){

	    $where = array(
            'idtype'=>MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_CREDIT,
            'id'=>$creditId
        );

	    $accountData = $this->model('MechanismAccount')->where($where)->find();
        if($accountData){
            if($accountData['title'] != $creditName){
                $this->update(array('title'=>$creditName),$accountData['identity']);
            }
        }else{
            return $this->insert(array(
                'idtype'=>MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_CREDIT,
                'id'=>$creditId,
                'company_identity'=>$this->session('company_identity'),
                'typological_identity'=>3,
                'title'=>$creditName,
                'bank_identity'=>$bankId,
                'amount'=>$amount
            ));

        }
    }

    public function newPropertyCapitalAccount($capitalId,$capitalName){
	    $companyId = $this->session('company_identity');
        $typeologicalId = 5;

	    $where = array(
            'idtype'=>MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_CAPITAL,
            'id'=>$capitalId
        );

	    $accountData = $this->model('MechanismAccount')->where($where)->find();
		if($accountData){
			$indexAccountId = $accountData['identity'];
		}else{
			$indexAccountId = $this->insert(array(
				'idtype'=>MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_CAPITAL,
				'id'=>$capitalId,
				'company_identity'=>$companyId,
				'typological_identity'=>$typeologicalId,
				'title'=>$capitalName
			));
		}

	    $childAccount = array();
	    $currecnyList = $this->service('MechanismCurrency')->getAllowedCurrecnyList();

        $typeologicalId = 4;
	    foreach ($currecnyList as $key=>$data){
	        $childAccount['idtype'][] = MechanismAccountModel::MECHANISM_ACCOUNT_IDTYPE_CAPITAL;
            $childAccount['id'][] = $capitalId;
            $childAccount['company_identity'][] = $companyId;
            $childAccount['typological_identity'][] = $typeologicalId;
            $childAccount['account_identitty'][] = $indexAccountId;
            $childAccount['title'][] = $capitalName.'【'.$data['title'].'】';
            $childAccount['currency_identity'][] = $data['identity'];
			
			$childAccount['sn'][] = $this->get_sn();
        }
        $this->model('MechanismAccount')->data($childAccount)->addMulti();

        return $indexAccountId;
    }
}