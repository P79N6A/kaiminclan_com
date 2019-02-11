<?php
/**
 *
 * 监管
 *
 * 账户
 *
 */
class  IntercalateSuperviseService extends Service {
	
	
	
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
	public function getSuperviseList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('IntercalateSupervise')->where($where)->count();
		if($count){
			$superviseHandle = $this->model('IntercalateSupervise')->where($where)->orderby($orderby);
			if($start && $perpage){
				$superviseHandle = $superviseHandle->limit($start,$perpage,$count);
			}
			$listdata = $superviseHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $superviseIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getSuperviseInfo($superviseIds){
		$superviseData = array();
		
		$where = array(
			'identity'=>$superviseIds
		);
		
		$superviseList = $this->model('IntercalateSupervise')->where($where)->select();
		if($superviseList){
			
			if(is_array($superviseIds)){
				$superviseData = $superviseList;
			}else{
				$superviseData = current($superviseList);
			}
			
			
		}
		
		
		return $superviseData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $superviseId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeSuperviseId($superviseId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$superviseId
		);
		
		$superviseData = $this->model('IntercalateSupervise')->where($where)->count();
		if($superviseData){
			
			$output = $this->model('IntercalateSupervise')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $title 数据类型
	 *
	 * @reutrn int;
	 */
	public function checkSupervise($title){
		$superviseId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('IntercalateSupervise')->where($where)->count();
		
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
	public function getSuperviseByIdtypeIds($idtype,$id,$uid){
		$superviseData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$superviseList = $this->model('IntercalateSupervise')->field('identity,id')->where($where)->select();

		if($superviseList){
			foreach($id as $key=>$val){
				$superviseData[$key] = array('id'=>$val,'checked'=>0);
				foreach($superviseList as $cnt=>$supervise){
					if($supervise['id'] == $val)
					{
						$superviseData[$key] = array('id'=>$val,'checked'=>$supervise['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$superviseData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $superviseData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $superviseId 收藏ID
	 * @param $superviseNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($superviseNewData,$superviseId){
		$where = array(
			'identity'=>$superviseId
		);
		
		$superviseData = $this->model('IntercalateSupervise')->where($where)->find();
		if($superviseData){
			
			
			$superviseNewData['lastupdate'] = $this->getTime();
			$this->model('IntercalateSupervise')->data($superviseNewData)->where($where)->save();
			
			
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
	public function insert($superviseData){
		$dateline = $this->getTime();
		$superviseData['sn'] = $this->get_sn();
		$superviseData['subscriber_identity'] = $this->session('uid');
		$superviseData['dateline'] = $dateline;
		$superviseData['lastupdate'] = $dateline;
			
		
		return $this->model('IntercalateSupervise')->data($superviseData)->add();
		
	}
}