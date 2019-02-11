<?php
/**
 *
 * 身份
 *
 * 权限
 *
 */
class  AuthorityDegreeService extends Service {
	
	/**
	 *
	 *
	 * 按账户ID提取账户类型
	 *
	 */
	public function fetchTypeByUid($subscriberId){
		$idType = array();
		
		$where = array();
		$where['subscriber_identity'] = intval($subscriberId);
		
		$degreeList = $this->model('AuthorityDegree')->field('idtype')->where($where)->select();
		if($degreeList){
			foreach($degreeList as $key=>$data){
				$idType[] = $data['idtype'];
			}
		}
		
		return $idType;
	}
	public function fetchUid($idtype,$id){
		$uid = array();
		
		$where = array();
		$where['idtype'] = intval($idtype);
		$where['id'] = $id;
		
		$degreeList = $this->model('AuthorityDegree')->field('id,subscriber_identity')->where($where)->select();
		if($degreeList){
			foreach($degreeList as $key=>$data){
				$uid[$data['id']] = $data['subscriber_identity'];
			}
		}
		
		return $uid;
	}
	
	public function fetchIdByIdtypeUid($idtype,$uid){
		$id = 0;
		
		$where = array();
		$where['idtype'] = $idtype;
		$where['subscriber_identity'] = $uid;
		
		$degreeData = $this->model('AuthorityDegree')->field('id')->where($where)->find();
		if($degreeData){
			$id = $degreeData['id'];
		}
		
		return $id;
	}
	public function fetchDegreeBySusbscriberId($subscriberId){
		$subscriberId = intval($subscriberId);
		if($subscriberId < 1){
			return array();
		}
		
		$where = array();
		$where['subscriber_identity'] = $subscriberId;
		
		return $this->model('AuthorityDegree')->where($where)->select();
		
	}
	public function newCustomer($clientId,$subscriberId = 0){
		$clientId = intval($clientId);
		if($clientId < 1){
			return -1;
		}
		$subscriberId = intval($subscriberId);
		if($subscriberId < 1){
			$subscriberId = $this->session('uid');
		}
		
		$where = array(
			'idtype'=>AuthorityDegreeModel::AUTHORITY_DEGREE_IDTYPE_CLIENT,
			'subscriber_identity'=>$subscriberId
		);
		if($this->model('AuthorityDegree')->where($where)->count()){
			return -2;
		}
		
		$degreeNewData = array(
			'idtype'=>AuthorityDegreeModel::AUTHORITY_DEGREE_IDTYPE_CLIENT,
			'id'=>$clientId,
			'subscriber_identity'=>$subscriberId
		);
		
		return $this->insert($degreeNewData);
	}
	public function newDoctor($doctorId,$subscriberId = 0){
		$doctorId = intval($doctorId);
		if($doctorId < 1){
			return -1;
		}
		$subscriberId = intval($subscriberId);
		if($subscriberId < 1){
			$subscriberId = $this->session('uid');
		}
		
		$where = array(
			'idtype'=>AuthorityDegreeModel::AUTHORITY_DEGREE_IDTYPE_DOCTOR,
			'subscriber_identity'=>$subscriberId
		);

		if($this->model('AuthorityDegree')->where($where)->count()){
			return -2;
		}
		
		$degreeNewData = array(
			'idtype'=>AuthorityDegreeModel::AUTHORITY_DEGREE_IDTYPE_DOCTOR,
			'id'=>$doctorId,
			'subscriber_identity'=>$subscriberId
		);
		
		return $this->insert($degreeNewData);
	}
	public function newBusiness($businessId,$subscriberId = 0){
		$businessId = intval($businessId);
		if($businessId < 1){
			return -1;
		}
		$subscriberId = intval($subscriberId);
		if($subscriberId < 1){
			$subscriberId = $this->session('uid');
		}
		$where = array(
			'idtype'=>AuthorityDegreeModel::AUTHORITY_DEGREE_IDTYPE_BUSINESS,
			'subscriber_identity'=>$subscriberId
		);
		
		if($this->model('AuthorityDegree')->where($where)->count()){
			return -2;
		}
		
		$degreeNewData = array(
			'idtype'=>AuthorityDegreeModel::AUTHORITY_DEGREE_IDTYPE_BUSINESS,
			'id'=>$businessId,
			'subscriber_identity'=>$subscriberId
		);
		return $this->insert($degreeNewData);
	}
	
	/**
	 *
	 * 新身份
	 *
	 * @param $degreeNewData 身份信息
	 *
	 * @reutrn int;
	 */
	public function insert($degreeNewData){
		if(!$degreeNewData){
			return -1;
		}
		
		$degreeNewData['clientip'] = $this->getClientIp();
		$degreeNewData['dateline'] = $this->getTime();
		
		$this->model('AuthorityDegree')->data($degreeNewData)->add();
	}

    /**
     * 获取用户所有身份
     * @param $subscriberIds
     */
	public function getDegreeIdBySubscriber($subscriberIds){
	    $id = $subscriberIds;
	    if(!is_array($subscriberIds)){
            $subscriberIds = array($subscriberIds);
        }
        $subscriberIds = array_filter($subscriberIds);
	    if(empty($subscriberIds)){
	        return array();
        }

        $where = array(
            'subscriber_identity'=>$subscriberIds
        );

	    $degreeList = $this->model('AuthorityDegree')->where($where)->select();

	    $output = array();
	    if($degreeList){
	        foreach($degreeList as $key=>$value){
                $output[$value['subscriber_identity']][] = $value['idtype'];
            }
        }
        if(!is_array($id)){
            $output = current($output);
        }
	    return $output;
    }
	
	public function fetchDegreeDataBySubscriberIds($subscriberIds){
		$degreeData = array();
		$subscriberIds = $this->getInt($subscriberIds);
		if(!$subscriberIds){
			return $degreeData;
		}
		
		$where = array(
			'subscriber_identity'=>$subscriberIds
		);
		
		$listdata = $this->model('AuthorityDegree')->where($where)->select();
		if(!$listdata){
			return $degreeData;
		}
		
		$idtypeList = array();
		foreach($listdata as $key=>$data){
			$idtypeList[$data['idtype']][] = $id;
		}
		
		foreach ($idtypeList as $idtype => $ids) {
			switch ($idtype) {
				case AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_BUSINESS:
					$busienssData = $this->service('SupplierBusiness')->getBusinessInfobyIds($ids);
					if ($busienssData) {
						foreach ($listdata as $cnt => $degree) {
							if ($degree['idtype'] != $idtype) continue;
							$degreeData[$degree['subscriber_identity']]['business'] = $busienssData[$degree['id']];
						}
					}
					break;
				case AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_STAFF:
					$busienssData = $this->service('SupplierBusiness')->getBusinessInfobyIds($ids);

					if ($busienssData) {
						foreach ($listdata as $cnt => $degree) {
							if ($degree['idtype'] != $idtype) continue;
							$degreeData[$degree['subscriber_identity']]['business'] = $busienssData[$degree['id']];
						}
					}
					break;
				case AuthorityDegreeModel::AUTHORITY_DEGREE_IDTYPE_DOCTOR:
				 	$medicinerData = $this->service('Mediciner')->getMedicinerData($ids);

					if ($medicinerData) {
						foreach ($listdata as $cnt => $degree) {
							if ($degree['idtype'] != $idtype) continue;
							$degreeData[$degree['subscriber_identity']]['mediciner'] = $medicinerData[$degree['id']];
						}
					}
					break;
				case AuthoritySubscriberModel::AUTHORITY_SUBSCRIBER_IDTYPE_CLIENT:
					$customerData = $this->service('Customer')->getCustomerInfoByIds($ids);
					if ($customerData) {
						foreach ($listdata as $cnt => $degree) {
							if ($degree['idtype'] != $idtype) continue;
							$degreeData[$degree['subscriber_identity']]['customer'] = $customerData[$degree['id']];
						}
					}
					break;
			}
		}
		
		
		return $degreeData;
	}

    /**
     * 获取用户所有身份
     * @param $subscriberIds
     */
    public function getDegreeGroupByIdtype($subscriberIds){
        if(!is_array($subscriberIds)){
            $subscriberIds = array($subscriberIds);
        }
        $subscriberIds = array_filter($subscriberIds);
        if(empty($subscriberIds)){
            return array();
        }

        $where = array(
            'subscriber_identity'=>$subscriberIds
        );

        $degreeList = $this->model('AuthorityDegree')->where($where)->select();

        $groupByIdtype = $groupBySubscriber = array();
        if($degreeList){
            foreach($degreeList as $key=>$value){
                $groupBySubscriber[$value['subscriber_identity']][] = $value;
                $groupByIdtype[$value['idtype']][] = $value['id'];
            }
        }
        return array('groupByIdtype'=>$groupByIdtype,'groupBySubscriber'=>$groupBySubscriber);
    }

}