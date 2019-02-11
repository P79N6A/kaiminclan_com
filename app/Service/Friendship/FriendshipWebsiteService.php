<?php
/**
 *
 * 交易流水
 *
 * 资金
 *
 */
class  FriendshipWebsiteService extends Service {
	
	
	
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
	public function getWebsiteList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('FriendshipWebsite')->where($where)->count();
		if($count){
			$subjectHandle = $this->model('FriendshipWebsite')->where($where)->orderby($orderby);
			if($perpage){
				$subjectHandle = $subjectHandle->limit($start,$perpage,$count);
			}
			$listdata = $subjectHandle->select();
			$classifyIds = array();
			foreach($listdata as $key=>$data){
				$classifyIds[] = $data['classify_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FriendshipWebsiteModel::getStatusTitle($data['status'])
				);
			}
			$classifyData = $this->service('FriendshipClassify')->getClassifyInfo($classifyIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['classify'] = isset($classifyData[$data['classify_identity']])?$classifyData[$data['classify_identity']]:array();
			}
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
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
	public function checkWebsiteTitle($title){
		$accountId = array();		
		$where = array(
			'title'=>$title,
		);	
		
		return $this->model('BankrollAccount')->where($where)->count();
	}
	/**
	 *
	 * 根据科目名称获取科目ID
	 *
	 * @param $title 科目标题
	 *
	 * @reutrn int;
	 */
	public function getWebsiteIdByTitle($title){
		$subjectId = 0;
		
		$where = array(
			'title'=>$title
		);
		
		$subjectData = $this->model('FriendshipWebsite')->field('identity')->where($where)->find();
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
	public function getWebsiteInfoById($subjectIds){
		$subjectData = array();
		
		$where = array(
			'identity'=>$subjectIds
		);
		
		$subjectList = $this->model('FriendshipWebsite')->where($where)->select();
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
	 * 删除科目
	 *
	 * @param $subjectId 科目ID
	 *
	 * @reutrn int;
	 */
	public function removeWebsiteId($subjectId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('FriendshipWebsite')->where($where)->count();
		if($subjectData){
			
			$output = $this->model('FriendshipWebsite')->where($where)->delete();
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
		
		$subjectData = $this->model('FriendshipWebsite')->where($where)->find();
		if($subjectData){
			
			
			$subjectNewData['lastupdate'] = $this->getTime();
			$this->model('FriendshipWebsite')->data($subjectNewData)->where($where)->save();
			if($subjectNewData['classify_identity'] != $subjectData['classify_identity']){
				$this->serivce('FriendshipClassify')->adjustWebsiteQuantity($subjectData['classify_identity'],-1);
				$this->serivce('FriendshipClassify')->adjustWebsiteQuantity($subjectNewData['classify_identity'],1);
			}
			
			
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
		
		$subjectId = $this->model('FriendshipWebsite')->data($subjectData)->add();
		if($subjectId){
			$this->service('FriendshipClassify')->adjustWebsiteQuantity($subjectData['classify_identity'],1);
		}
		
		return $subjectId;
		
		
	}
}