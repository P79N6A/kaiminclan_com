<?php
/**
 *
 * 交易所
 *
 * 账户
 *
 */
class  IntercalateExchangeService extends Service {
	
	
	
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
	public function getExchangeList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('IntercalateExchange')->where($where)->count();
		if($count){
			$exchangeHandle = $this->model('IntercalateExchange')->where($where)->orderby($orderby);
			if($start && $perpage){
				$exchangeHandle = $exchangeHandle->limit($start,$perpage,$count);
			}
			$listdata = $exchangeHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $exchangeIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getExchangeInfo($exchangeIds){
		$exchangeData = array();
		
		$where = array(
			'identity'=>$exchangeIds
		);
		
		$exchangeList = $this->model('IntercalateExchange')->where($where)->select();
		if($exchangeList){
			
			if(is_array($exchangeIds)){
				$exchangeData = $exchangeList;
			}else{
				$exchangeData = current($exchangeList);
			}
			
			
		}
		
		
		return $exchangeData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $exchangeId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeExchangeId($exchangeId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$exchangeId
		);
		
		$exchangeData = $this->model('IntercalateExchange')->where($where)->count();
		if($exchangeData){
			
			$output = $this->model('IntercalateExchange')->where($where)->delete();
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
	public function checkExchangeTitle($title){
		$exchangeId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		
		return $this->model('IntercalateExchange')->where($where)->count();
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
	public function getExchangeByIdtypeIds($idtype,$id,$uid){
		$exchangeData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$exchangeList = $this->model('IntercalateExchange')->field('identity,id')->where($where)->select();

		if($exchangeList){
			foreach($id as $key=>$val){
				$exchangeData[$key] = array('id'=>$val,'checked'=>0);
				foreach($exchangeList as $cnt=>$exchange){
					if($exchange['id'] == $val)
					{
						$exchangeData[$key] = array('id'=>$val,'checked'=>$exchange['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$exchangeData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $exchangeData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $exchangeId 收藏ID
	 * @param $exchangeNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($exchangeNewData,$exchangeId){
		$where = array(
			'identity'=>$exchangeId
		);
		
		$exchangeData = $this->model('IntercalateExchange')->where($where)->find();
		if($exchangeData){
			
			
			$exchangeNewData['lastupdate'] = $this->getTime();
			$this->model('IntercalateExchange')->data($exchangeNewData)->where($where)->save();
			
			
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
	public function insert($exchangeData){
			$dateline = $this->getTime();
			$exchangeData['sn'] = $this->get_sn();
			$exchangeData['subscriber_identity'] = $this->session('uid');
			$exchangeData['dateline'] = $dateline;
			$exchangeData['lastupdate'] = $dateline;
			
		
		return $this->model('IntercalateExchange')->data($exchangeData)->add();
		
	}

	public function adjustStockQuantity($exchangeId,$quantity){
	    $where = array(
	        'identity'=>$exchangeId
        );
        $this->model('IntercalateExchange')->where($where)->setInc('stock_num',$quantity);
    }
}