<?php
/**
 *
 * 调账
 *
 * 资金
 *
 */
class  BudgetClassifyService extends Service {
	
	
	
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
	public function getClassifyList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BudgetClassify')->where($where)->count();
		if($count){
			$classifyHandle = $this->model('BudgetClassify')->where($where)->orderby($orderby);
			if($start && $perpage){
				$classifyHandle = $classifyHandle->limit($start,$perpage,$count);
			}
			$listdata = $classifyHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $classifyIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getClassifyInfo($classifyIds){
		$classifyData = array();
		
		$where = array(
			'identity'=>$classifyIds
		);
		
		$classifyList = $this->model('BudgetClassify')->where($where)->select();
		if($classifyList){
			
			if(is_array($classifyIds)){
				$classifyData = $classifyList;
			}else{
				$classifyData = current($classifyList);
			}
			
			
		}
		
		
		return $classifyData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $classifyId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeClassifyId($classifyId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$classifyId
		);
		
		$classifyData = $this->model('BudgetClassify')->where($where)->count();
		if($classifyData){
			
			$output = $this->model('BudgetClassify')->where($where)->delete();
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
	public function checkClassify($idtype,$id,$uid){
		$classifyId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$classifyList = $this->model('BudgetClassify')->field('identity,id')->where($where)->select();
		
		if($classifyList){
			
			foreach($classifyList as $key=>$classify){
				$classifyId[$classify['identity']] = $classify['id'];
			}
		}
		return $classifyId;
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
	public function getClassifyByIdtypeIds($idtype,$id,$uid){
		$classifyData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$classifyList = $this->model('BudgetClassify')->field('identity,id')->where($where)->select();

		if($classifyList){
			foreach($id as $key=>$val){
				$classifyData[$key] = array('id'=>$val,'checked'=>0);
				foreach($classifyList as $cnt=>$classify){
					if($classify['id'] == $val)
					{
						$classifyData[$key] = array('id'=>$val,'checked'=>$classify['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$classifyData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $classifyData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $classifyId 收藏ID
	 * @param $classifyNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($classifyNewData,$classifyId){
		$where = array(
			'identity'=>$classifyId
		);
		
		$classifyData = $this->model('BudgetClassify')->where($where)->find();
		if($classifyData){
			
			
			$classifyNewData['lastupdate'] = $this->getTime();
			$this->model('BudgetClassify')->data($classifyNewData)->where($where)->save();
			
			
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
	public function insert($classifyData){
			$dateline = $this->getTime();
			$classifyData['subscriber_identity'] = $this->session('uid');
			$classifyData['dateline'] = $dateline;
		$classifyData['sn'] = $this->get_sn();
			$classifyData['lastupdate'] = $dateline;
			
		
		return $this->model('BudgetClassify')->data($classifyData)->add();
		
	}
}