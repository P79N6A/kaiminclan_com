<?php
/**
 *
 * 交易流水
 *
 * 资金
 *
 */
class  BudgetSubjectService extends Service {
	
	
	
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
	public function getSubjectList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BudgetSubject')->where($where)->count();
		if($count){
			$subjectHandle = $this->model('BudgetSubject')->where($where)->orderby($orderby);
			if($perpage){
				$subjectHandle = $subjectHandle->limit($start,$perpage,$count);
			}
			$listdata = $subjectHandle->select();
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	public function checkSubjectTitle($title){
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('BudgetSubject')->where($where)->count();
	}
	/**
	 *
	 * 根据科目名称获取科目ID
	 *
	 * @param $title 科目标题
	 *
	 * @reutrn int;
	 */
	public function getSubjectIdByTitle($title){
		$subjectId = 0;
		
		$where = array(
			'title'=>$title
		);
		
		$subjectData = $this->model('BudgetSubject')->field('identity')->where($where)->find();
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
	public function getSubjectInfoById($subjectIds){
		$subjectData = array();
		
		$where = array(
			'identity'=>$subjectIds
		);
		
		$subjectList = $this->model('BudgetSubject')->where($where)->select();
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
	public function removeSubjectId($subjectId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subjectId
		);
		
		$subjectData = $this->model('BudgetSubject')->where($where)->count();
		if($subjectData){
			
			$output = $this->model('BudgetSubject')->where($where)->delete();
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
		
		$subjectData = $this->model('BudgetSubject')->where($where)->find();
		if($subjectData){
			
			
			$subjectNewData['lastupdate'] = $this->getTime();
			$this->model('BudgetSubject')->data($subjectNewData)->where($where)->save();
			
			
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
		
		$subjectId = $this->model('BudgetSubject')->data($subjectData)->add();
		
		return $subjectId;
		
		
	}
}