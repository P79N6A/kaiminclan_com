<?php
/**
 *
 * 交易流水
 *
 * 资金
 *
 */
class  BankrollSubsidiaryService extends Service {
	
	
	
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
	public function getSubsidiaryList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BankrollSubsidiary')->where($where)->count();
		if($count){
			$subsidiaryHandle = $this->model('BankrollSubsidiary')->where($where)->orderby($orderby);
			if($perpage){
				$subsidiaryHandle = $subsidiaryHandle->limit($start,$perpage,$count);
			}
			$listdata = $subsidiaryHandle->select();
			
			$accountIds = array();
			foreach($listdata as $key=>$subsidiary){
				$accountIds[] = $subsidiary['account_identity'];
			}
			$accountData = $this->service('BankrollAccount')->getAccountInfo($accountIds);
			
			foreach($listdata as $key=>$subsidiary){
				$listdata[$key]['account'] = $accountData[$subsidiary['account_identity']];
				$listdata[$key]['direction'] = array(
					'value'=>$subsidiary['direction'],'label'=>BankrollSubsidiaryModel::getDirectionTitle($subsidiary['direction'])
				);
			}
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $subsidiaryIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getSubsidiaryInfoById($subsidiaryIds){
		$subsidiaryData = array();
		
		$where = array(
			'identity'=>$subsidiaryIds
		);
		
		$subsidiaryList = $this->model('BankrollSubsidiary')->where($where)->select();
		if($subsidiaryList){
			$idtypeData = array();
			foreach($subsidiaryList as $key=>$subsidiary){
				$idtypeData[$subsidiary['idtype']][] = $subsidiary['id'];
			}
			
			foreach($idtypeData as $idtype=>$ids){
				switch($idtype){
					case BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_GOOD:
						$goodsData = $this->service('Goods')->getGoodsCommonListByIds($ids);
						if($goodsData){
							foreach($subsidiaryList as $key=>$subsidiary){
								if($subsidiary['idtype'] != BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_GOOD) continue;
								$subsidiaryList[$key]['good'] = $goodsData[$subsidiary['id']];
							}
						}
						break;
					case BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_FOOD:	
						break;
					case BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_ARTICLE:	
						break;
					case BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_COMMENT:	
						break;
					case BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_USER:	
						break;
					case BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_BUSINESS:	
						$businessData = $this->service('SupplierBusiness')->getBusinessInfobyIds($ids);
						if($businessData){
							foreach($subsidiaryList as $key=>$subsidiary){
								if($subsidiary['idtype'] != BankrollSubsidiaryModel::AUTHORITY_COLLECTION_IDTYPE_BUSINESS) continue;
								$subsidiaryList[$key]['business'] = $businessData[$subsidiary['id']];
							}
						}
						break;
				}
			}
			
			if(is_array($subsidiaryIds)){
				foreach($subsidiaryList as $key=>$subsidiary){
					$subsidiaryData[$subsidiary['identity']] = $subsidiary;
				}
			}else{
				$subsidiaryData = current($subsidiaryList);
			}
			
			
		}
		
		
		return $subsidiaryData;
	}
	
	
	/**
	 *
	 * 收藏信息
	 *
	 * @param $subsidiaryIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getUserSubsidiaryInfoById($subsidiaryIds,$uid){
		$subsidiaryData = array();
		
		$where = array(
			'identity'=>$subsidiaryIds,
			'subscriber_identity'=>$uid
		);
				
		return $this->model('BankrollSubsidiary')->where($where)->select();
	}
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $subsidiaryId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeSubsidiaryId($subsidiaryId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subsidiaryId
		);
		
		$subsidiaryData = $this->model('BankrollSubsidiary')->where($where)->count();
		if($subsidiaryData){
			
			$output = $this->model('BankrollSubsidiary')->where($where)->delete();
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
	public function checkSubsidiary($idtype,$id,$uid){
		$subsidiaryId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$subsidiaryList = $this->model('BankrollSubsidiary')->field('identity,id')->where($where)->select();
		
		if($subsidiaryList){
			
			foreach($subsidiaryList as $key=>$subsidiary){
				$subsidiaryId[$subsidiary['identity']] = $subsidiary['id'];
			}
		}
		return $subsidiaryId;
	}
	
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $subsidiaryId 收藏ID
	 * @param $subsidiaryNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($subsidiaryNewData,$subsidiaryId){
		$where = array(
			'identity'=>$subsidiaryId
		);
		
		$subsidiaryData = $this->model('BankrollSubsidiary')->where($where)->find();
		if($subsidiaryData){
			
			
			$subsidiaryNewData['lastupdate'] = $this->getTime();
			$this->model('BankrollSubsidiary')->data($subsidiaryNewData)->where($where)->save();
			
			
		}
	}
	
	public function newLeave($accountId,$amount){
		
		$subsidiaryData = array(
			'account_identity'=>$accountId,
			'happen_date'=>$this->getTime(),
			'amount'=>$amount,
			'direction'=>2,
		);
		return $this->insert($subsidiaryData);
	}
	
	public function newIncome($accountId,$amount){
		
		$subsidiaryData = array(
			'account_identity'=>$accountId,
			'happen_date'=>$this->getTime(),
			'amount'=>$amount,
			'direction'=>1,
		);
		return $this->insert($subsidiaryData);
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
	public function insert($subsidiaryData){
		
		$dateline = $this->getTime();
		$subsidiaryData['subscriber_identity'] = $this->session('uid');
		$subsidiaryData['dateline'] = $dateline;
		$subsidiaryData['sn'] = $this->get_sn();
		$subsidiaryData['lastupdate'] = $dateline;
		
		$subsidiaryId = $this->model('BankrollSubsidiary')->data($subsidiaryData)->add();
		return $subsidiaryId;
		
		
	}
}