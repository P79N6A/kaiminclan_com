<?php
/**
 *
 * 调账
 *
 * 资金
 *
 */
class  BankrollAdjustmentService extends Service {
	
	
	
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
	public function getAdjustmentList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BankrollAdjustment')->where($where)->count();
		if($count){
			$adjustmentHandle = $this->model('BankrollAdjustment')->where($where)->orderby($orderby);
			if($start && $perpage){
				$adjustmentHandle = $adjustmentHandle->limit($start,$perpage,$count);
			}
			$listdata = $adjustmentHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $adjustmentIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getAdjustmentInfo($adjustmentIds){
		$adjustmentData = array();
		
		$where = array(
			'identity'=>$adjustmentIds
		);
		
		$adjustmentList = $this->model('BankrollAdjustment')->where($where)->select();
		if($adjustmentList){
			
			if(is_array($adjustmentIds)){
				$adjustmentData = $adjustmentList;
			}else{
				$adjustmentData = current($adjustmentList);
			}
			
			
		}
		
		
		return $adjustmentData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $adjustmentId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeAdjustmentId($adjustmentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$adjustmentId
		);
		
		$adjustmentData = $this->model('BankrollAdjustment')->where($where)->count();
		if($adjustmentData){
			
			$output = $this->model('BankrollAdjustment')->where($where)->delete();
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
	public function checkAdjustment($idtype,$id,$uid){
		$adjustmentId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$adjustmentList = $this->model('BankrollAdjustment')->field('identity,id')->where($where)->select();
		
		if($adjustmentList){
			
			foreach($adjustmentList as $key=>$adjustment){
				$adjustmentId[$adjustment['identity']] = $adjustment['id'];
			}
		}
		return $adjustmentId;
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
	public function getAdjustmentByIdtypeIds($idtype,$id,$uid){
		$adjustmentData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$adjustmentList = $this->model('BankrollAdjustment')->field('identity,id')->where($where)->select();

		if($adjustmentList){
			foreach($id as $key=>$val){
				$adjustmentData[$key] = array('id'=>$val,'checked'=>0);
				foreach($adjustmentList as $cnt=>$adjustment){
					if($adjustment['id'] == $val)
					{
						$adjustmentData[$key] = array('id'=>$val,'checked'=>$adjustment['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$adjustmentData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $adjustmentData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $adjustmentId 收藏ID
	 * @param $adjustmentNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($adjustmentNewData,$adjustmentId){
		$where = array(
			'identity'=>$adjustmentId
		);
		
		$adjustmentData = $this->model('BankrollAdjustment')->where($where)->find();
		if($adjustmentData){
			
			
			$adjustmentNewData['lastupdate'] = $this->getTime();
			$this->model('BankrollAdjustment')->data($adjustmentNewData)->where($where)->save();
			
			
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
	public function insert($adjustmentData){
			$dateline = $this->getTime();
			$adjustmentData['subscriber_identity'] = $this->session('uid');
			$adjustmentData['dateline'] = $dateline;
		$adjustmentData['sn'] = $this->get_sn();
			$adjustmentData['lastupdate'] = $dateline;
			
		
		return $this->model('BankrollAdjustment')->data($adjustmentData)->add();
		
	}
}