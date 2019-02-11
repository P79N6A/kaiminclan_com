<?php
/**
 *
 * 经纪
 *
 * 账户
 *
 */
class  IntercalateBrokerService extends Service {
	
	
	
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
	public function getBrokerList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('IntercalateBroker')->where($where)->count();
		if($count){
			$brokerHandle = $this->model('IntercalateBroker')->where($where)->orderby($orderby);
			if($perpage){
				$brokerHandle = $brokerHandle->limit($start,$perpage,$count);
			}
			$listdata = $brokerHandle->select();
			
			$superIds = array();
			foreach($listdata as $key=>$data){
				$superIds[] = $data['supervise_identity'];	
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>IntercalateBrokerModel::getStatusTitle($data['status'])
				);
			}
			
			$superviseData = $this->service('IntercalateSupervise')->getSuperviseInfo($superIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['supervise'] = isset($superviseData[$data['supervise_identity']])?$superviseData[$data['supervise_identity']]:array();
			}
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $brokerIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getBrokerInfo($brokerIds){
		$brokerData = array();
		
		$where = array(
			'identity'=>$brokerIds
		);
		
		$brokerList = $this->model('IntercalateBroker')->where($where)->select();
		if($brokerList){
			
			if(is_array($brokerIds)){
				$brokerData = $brokerList;
			}else{
				$brokerData = current($brokerList);
			}
			
			
		}
		
		
		return $brokerData;
	}
	
	public function adjustAccount($brokerId,$quantity = 1){
		
		$brokerId = $this->getInt($brokerId);
		if(!$brokerId){
			return 0;
		}
		$where = array(
			'identity'=>$brokerId
		);
		
		if(strpos($quantity,'-1') !== false){
			$this->model('IntercalateBroker')->where($where)->setDec('account_num',substr($quantity,1));
		}else{
		
			$this->model('IntercalateBroker')->where($where)->setInc('account_num',$quantity);
		}
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $brokerId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeBrokerId($brokerId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$brokerId
		);
		
		$brokerData = $this->model('IntercalateBroker')->where($where)->count();
		if($brokerData){
			
			$output = $this->model('IntercalateBroker')->where($where)->delete();
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
	public function checkBroker($idtype,$id,$uid){
		$brokerId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$brokerList = $this->model('IntercalateBroker')->field('identity,id')->where($where)->select();
		
		if($brokerList){
			
			foreach($brokerList as $key=>$broker){
				$brokerId[$broker['identity']] = $broker['id'];
			}
		}
		return $brokerId;
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
	public function getBrokerByIdtypeIds($idtype,$id,$uid){
		$brokerData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$brokerList = $this->model('IntercalateBroker')->field('identity,id')->where($where)->select();

		if($brokerList){
			foreach($id as $key=>$val){
				$brokerData[$key] = array('id'=>$val,'checked'=>0);
				foreach($brokerList as $cnt=>$broker){
					if($broker['id'] == $val)
					{
						$brokerData[$key] = array('id'=>$val,'checked'=>$broker['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$brokerData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $brokerData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $brokerId 收藏ID
	 * @param $brokerNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($brokerNewData,$brokerId){
		$where = array(
			'identity'=>$brokerId
		);
		
		$brokerData = $this->model('IntercalateBroker')->where($where)->find();
		if($brokerData){
			
			
			$brokerNewData['lastupdate'] = $this->getTime();
			$this->model('IntercalateBroker')->data($brokerNewData)->where($where)->save();
			
			
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
	public function insert($brokerData){
			$dateline = $this->getTime();
			$brokerData['sn'] = $this->get_sn();
			$brokerData['subscriber_identity'] = $this->session('uid');
			$brokerData['dateline'] = $dateline;
			$brokerData['lastupdate'] = $dateline;
			
		
		return $this->model('IntercalateBroker')->data($brokerData)->add();
		
	}
}