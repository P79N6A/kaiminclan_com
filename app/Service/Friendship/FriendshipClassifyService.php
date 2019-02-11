<?php
/**
 *
 * 交易流水
 *
 * 资金
 *
 */
class  FriendshipClassifyService extends Service {
	
	
	
	/**
	 *
	 * 科目列表
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
		
		$count = $this->model('FriendshipClassify')->where($where)->count();
		if($count){
			$subjectHandle = $this->model('FriendshipClassify')->where($where)->orderby($orderby);
			if($perpage){
				$subjectHandle = $subjectHandle->limit($start,$perpage,$count);
			}
			$listdata = $subjectHandle->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FriendshipClassifyModel::getStatusTitle($data['status'])
				);
			}
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	public function adjustWebsiteQuantity($classifyId,$quantity = 1){
		
		$classifyIds = $this->getInt($classifyIds);
		if(!$classifyIds){
			return -1;
		}
		if($quantity === 0){
			return -2;
		}
		
		$where = array(
			'identity'=>$classifyId
		);
		
		if(strpos($quantity,'-') === false){
			$this->model('FriendshipClassify')->where($where)->setDec('website_num',substr($quantity,1));
		}else{
			$this->model('FriendshipClassify')->where($where)->setDec('website_num',$quantity);
		}
		
		
	}
	
	public function getClassifyInfo($classifyIds){
		
		$classifyIds = $this->getInt($classifyIds);
		if(!$classifyIds){
			return array();
		}
		
		$where = array(
			'identity'=>$classifyIds
		);
		
		return $this->model('FriendshipClassify')->where($where)->select();
	}
	/**
	 *
	 * 根据科目名称获取科目ID
	 *
	 * @param $title 科目标题
	 *
	 * @reutrn int;
	 */
	public function getClassifyIdByTitle($title){
		$subjectId = 0;
		
		$where = array(
			'title'=>$title
		);
		
		$subjectData = $this->model('FriendshipClassify')->field('identity')->where($where)->find();
		if(!$subjectData){		
			$subjectId = $this->insert(array('title'=>$title));
		}else{
			$subjectId = $subjectData['identity'];
		}
		
		
		return $subjectId;
	}
	/**
	 *
	 * 科目信息
	 *
	 * @param $subjectIds 科目ID
	 *
	 * @reutrn int;
	 */
	public function getClassifyInfoById($subjectIds){
		$subjectData = array();
		
		$where = array(
			'identity'=>$subjectIds
		);
		
		$subjectList = $this->model('FriendshipClassify')->where($where)->select();
		if($subjectList){

			
			if(is_array($subjectIds)){
				foreach($subjectList as $key=>$subject){
					$subjectData[$subject['identity']] = $subject;
				}
			}else{
				$subjectData = current($subjectList);
			}
			
			
		}
		
		
		return $subjectData;
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
	public function checkClassifyTitle($title){
		$accountId = array();		
		$where = array(
			'title'=>$title,
		);	
		
		return $this->model('FriendshipClassify')->where($where)->count();
	}
	
		
	/**
	 *
	 * 删除科目
	 *
	 * @param $subjectId 科目ID
	 *
	 * @reutrn int;
	 */
	public function removeClassifyId($subjectId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('FriendshipClassify')->where($where)->count();
		if($subjectData){
			
			$output = $this->model('FriendshipClassify')->where($where)->delete();
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 科目修改
	 *
	 * @param $subjectId 科目ID
	 * @param $subjectNewData 科目数据
	 *
	 * @reutrn int;
	 */
	public function update($subjectNewData,$subjectId){
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('FriendshipClassify')->where($where)->find();
		if($subjectData){
			
			
			$subjectNewData['lastupdate'] = $this->getTime();
			$this->model('FriendshipClassify')->data($subjectNewData)->where($where)->save();
			
			
		}
	}
	
	/**
	 *
	 * 新科目
	 *
	 * @param $id 科目信息
	 * @param $idtype 科目信息
	 *
	 * @reutrn int;
	 */
	public function insert($subjectData){
		
		$dateline = $this->getTime();
		$subjectData['subscriber_identity'] = $this->session('uid');
		$subjectData['dateline'] = $dateline;
		$subjectData['sn'] = $this->get_sn();
		$subjectData['lastupdate'] = $dateline;
		
		$subjectId = $this->model('FriendshipClassify')->data($subjectData)->add();
		
		return $subjectId;
		
		
	}
}