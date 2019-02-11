<?php
/**
 *
 * 收藏
 *
 * 权限
 *
 */
class  AuthorityCollectionService extends Service {
	
	
	
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
	public function getAllCollectionList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('AuthorityCollection')->where($where)->count();
		if($count){
			$collectionHandle = $this->model('AuthorityCollection')->where($where)->orderby($orderby);
			if($start && $perpage){
				$collectionHandle = $collectionHandle->limit($start,$perpage,$count);
			}
			$listdata = $collectionHandle->select();
			
			$collectionIds = array();
			foreach($listdata as $key=>$collection){
				$collectionIds[] = $collection['identity'];
			}
			$collectionData = $this->getCollectionInfoById($collectionIds);
			
			foreach($listdata as $key=>$collection){
				$list[$collection['identity']] = $collectionData[$collection['identity']];
			}
			
		}
		
		return array('list'=>$list,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $collectionIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getCollectionInfoById($collectionIds){
		$collectionData = array();
		
		$where = array(
			'identity'=>$collectionIds
		);
		
		$collectionList = $this->model('AuthorityCollection')->where($where)->select();
		if($collectionList){
			$idtypeData = array();
			foreach($collectionList as $key=>$collection){
				$idtypeData[$collection['idtype']][] = $collection['id'];
			}
			
			foreach($idtypeData as $idtype=>$ids){
				switch($idtype){
					case AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_GOOD:
						$goodsData = $this->service('Goods')->getGoodsCommonListByIds($ids);
						if($goodsData){
							foreach($collectionList as $key=>$collection){
								if($collection['idtype'] != AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_GOOD) continue;
								$collectionList[$key]['good'] = $goodsData[$collection['id']];
							}
						}
						break;
					case AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_FOOD:	
						break;
					case AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_ARTICLE:	
						break;
					case AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_COMMENT:	
						break;
					case AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_USER:	
						break;
					case AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_BUSINESS:	
						$businessData = $this->service('SupplierBusiness')->getBusinessInfobyIds($ids);
						if($businessData){
							foreach($collectionList as $key=>$collection){
								if($collection['idtype'] != AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_BUSINESS) continue;
								$collectionList[$key]['business'] = $businessData[$collection['id']];
							}
						}
						break;
				}
			}
			
			if(is_array($collectionIds)){
				foreach($collectionList as $key=>$collection){
					$collectionData[$collection['identity']] = $collection;
				}
			}else{
				$collectionData = current($collectionList);
			}
			
			
		}
		
		
		return $collectionData;
	}
	
	
	/**
	 *
	 * 收藏信息
	 *
	 * @param $collectionIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getUserCollectionInfoById($collectionIds,$uid){
		$collectionData = array();
		
		$where = array(
			'identity'=>$collectionIds,
			'subscriber_identity'=>$uid
		);
				
		return $this->model('AuthorityCollection')->where($where)->select();
	}
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $collectionId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeCollectionId($collectionId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$collectionId
		);
		
		$collectionData = $this->model('AuthorityCollection')->where($where)->count();
		if($collectionData){
			
			$output = $this->model('AuthorityCollection')->where($where)->delete();
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
	public function checkCollection($idtype,$id,$uid){
		$collectionId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$collectionList = $this->model('AuthorityCollection')->field('identity,id')->where($where)->select();
		
		if($collectionList){
			
			foreach($collectionList as $key=>$collection){
				$collectionId[$collection['identity']] = $collection['id'];
			}
		}
		return $collectionId;
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
	public function getCollectionByIdtypeIds($idtype,$id,$uid){
		$collectionData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$collectionList = $this->model('AuthorityCollection')->field('identity,id')->where($where)->select();

		if($collectionList){
			foreach($id as $key=>$val){
				$collectionData[$key] = array('id'=>$val,'checked'=>0);
				foreach($collectionList as $cnt=>$collection){
					if($collection['id'] == $val)
					{
						$collectionData[$key] = array('id'=>$val,'checked'=>$collection['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$collectionData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $collectionData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $collectionId 收藏ID
	 * @param $collectionNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($collectionNewData,$collectionId){
		$where = array(
			'identity'=>$collectionId
		);
		
		$collectionData = $this->model('AuthorityCollection')->where($where)->find();
		if($collectionData){
			
			
			$collectionNewData['lastupdate'] = $this->getTime();
			$this->model('AuthorityCollection')->data($collectionNewData)->where($where)->save();
			
			
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
	public function insert($id,$idtype){
		if(!$id || !$idtype){
			return -1;
		}
		$idtype = intval($idtype);
		
		if(!is_array($id)){
			$id = array($id);
		}
		
		$uid = intval($this->session('uid'));
		$dateline = $this->getTime();

		
		$collectionList = array();
		foreach($id as $key=>$val){
			$collectionList['id'][] = $val;
			$collectionList['idtype'][] = $idtype;
			$collectionList['subscriber_identity'][] = $uid;
			$collectionList['dateline'][] = $dateline;
			$collectionList['lastupdate'][] = $dateline;
		}
		
		$this->model('AuthorityCollection')->data($collectionList)->addMulti();
		
		switch($idtype){
			case AuthorityCollectionModel::AUTHORITY_COLLECTION_IDTYPE_BUSINESS:
				$this->service('SupplierBusiness')->adjustCollectionNum($id,1);
				break;
		}
		
	}
}