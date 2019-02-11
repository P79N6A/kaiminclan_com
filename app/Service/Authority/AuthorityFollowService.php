<?php
/**
 *
 * 关注
 *
 * 权限
 *
 */
class  AuthorityFollowService extends Service {
	
	
	
	/**
	 *
	 * 关注列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getAllFollowList($where = array(),$orderby = 'identity desc',$start = 0,$perpage = 0){
		$_where = array(
			'subscriber_identity'=>$this->session('uid')
		);
		$where = array_merge($where,$_where);
		
		$count = $this->model('AuthorityFollow')->where($where)->count();
		if($count){
			$followHandle = $this->model('AuthorityFollow')->where($where)->orderby($orderby);
			if($start && $perpage){
				$followHandle = $followHandle->limit($start,$perpage,$count);
			}
			$listdata = $followHandle->select();
			
			$followIds = array();
			foreach($followList as $key=>$follow){
				$followIds[] = $follow['id'];
			}
			$followData = $this->getFollowInfoById($followIds);
			
			foreach($listdata as $key=>$follow){
				$listdata[$key] = $followData[$follow['id']];
			}
		}
		
		return $listdata;
	}
	/**
	 *
	 * 关注信息
	 *
	 * @param $followIds 关注ID
	 *
	 * @reutrn int;
	 */
	public function getFollowInfoById($followIds){
		$followData = array();
		
		$where = array(
			'identity'=>$followIds
		);
		
		$followList = $this->model('AuthorityFollow')->where($where)->select();
		if($followList){
			$idtypeData = array();
			foreach($followList as $key=>$follow){
				$idtypeData[$follow['idtype']][] = $follow['id'];
			}
			
			foreach($idtypeData as $idtype=>$ids){
				switch($idtype){
					case AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_GOOD:
						$goodsData = $this->service('Goods')->getGoodsInfobyIds($ids);
						if($goodsData){
							foreach($followList as $key=>$follow){
								if($follow['idtype'] !== AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_GOOD) continue;
								$followList[$key]['good'] = $goodsData[$shopping['id']];
							}
						}
						break;
					case AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_FOOD:	
						break;
					case AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_ARTICLE:	
						break;
					case AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_COMMENT:	
						break;
					case AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_USER:	
						$subscriberData = $this->service('AuthoritySubscriber')->getSubscriberInfobyIds($ids);
						if($subscriberData){
							foreach($followList as $key=>$subscriber){
								if($subscriber['idtype'] !== AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_USER) continue;
								$followList[$key]['subscriber'] = $subscriberData[$subscriber['id']];
							}
						}
						break;
					case AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_BUSINESS:	
						$businessData = $this->service('SupplierBusiness')->getBusinessInfobyIds($ids);
						if($businessData){
							foreach($followList as $key=>$follow){
								if($follow['idtype'] !== AuthorityFollowModel::AUTHORITY_FOLLOW_IDTYPE_BUSINESS) continue;
								$followList[$key]['business'] = $businessData[$shopping['id']];
							}
						}
						break;
				}
			}
			
			if(is_array($followIds)){
				foreach($followList as $key=>$follow){
					$followData[$follow['identity']] = $follow;
				}
			}else{
				$followData = current($followList);
			}
			
			
		}
		
		return $followData;
	}
	
	
	/**
	 *
	 * 关注信息
	 *
	 * @param $followIds 关注ID
	 *
	 * @reutrn int;
	 */
	public function getUserFollowInfoById($followIds,$uid){
		$followData = array();
		
		$where = array(
			'identity'=>$followIds,
			'subscriber_identity'=>$uid
		);
				
		return $this->model('AuthorityFollow')->where($where)->select();
	}
		
	/**
	 *
	 * 删除关注
	 *
	 * @param $followId 关注ID
	 *
	 * @reutrn int;
	 */
	public function removeFollowId($followId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$followId
		);
		
		$followData = $this->model('AuthorityFollow')->where($where)->count();
		if($followData){
			
			$output = $this->model('AuthorityFollow')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测关注
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkFollow($idtype,$id,$uid){
		$followId = 0;		
		$where = array(
			'idtype'=>$idtype,
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		$followData = $this->model('AuthorityFollow')->field('identity')->where($where)->find();
		if($followData){
			$followId = $followData['identity'];
		}
		return $followId;
	}
	
	/**
	 *
	 * 检测关注
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function getFollowByIdtypeIds($idtype,$id,$uid){
		$followData = array();
		
		if(is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		$followList = $this->model('AuthorityFollow')->field('identity,id')->where($where)->select();
		if($followList){
			foreach($id as $key=>$val){
				$followData[$key] = array('id'=>$val,'checked'=>0);
				foreach($followList as $cnt=>$follow){
					if($follow['id'] == $val)
					{
						$followData[$key] = array('id'=>$val,'checked'=>$follow['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$followData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $followData;
	}
	
	/**
	 *
	 * 关注修改
	 *
	 * @param $followId 关注ID
	 * @param $followNewData 关注数据
	 *
	 * @reutrn int;
	 */
	public function update($followNewData,$followId){
		$where = array(
			'identity'=>$followId
		);
		
		$followData = $this->model('AuthorityFollow')->where($where)->find();
		if($followData){
			
			
			$followNewData['lastupdate'] = $this->getTime();
			$this->model('AuthorityFollow')->data($followNewData)->where($where)->save();
			
			
		}
	}
	
	/**
	 *
	 * 新关注
	 *
	 * @param $followNewData 关注信息
	 *
	 * @reutrn int;
	 */
	public function insert($followNewData){
		if(!$followNewData){
			return -1;
		}
			
			
		$followNewData['subscriber_identity'] =$this->session('uid');
		$followNewData['dateline'] = $this->getTime();
		$followNewData['lastupdate'] = $followNewData['dateline'];
		
		$this->model('AuthorityFollow')->data($followNewData)->add();
		
	}
}