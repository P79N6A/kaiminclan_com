<?php
/**
 *
 * 转入
 *
 * 资金
 *
 */
class  BudgetProjectService extends Service {
	
	
	
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
	public function getProjectList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BudgetProject')->where($where)->count();
		if($count){
			$projectHandle = $this->model('BudgetProject')->where($where)->orderby($orderby);
			if($start && $perpage){
				$projectHandle = $projectHandle->limit($start,$perpage,$count);
			}
			$listdata = $projectHandle->select();
			$subjectIds = array();
			foreach($listdata as $key=>$data){
				$subjectIds[] = $data['subject_identity'];
			}
			
			$subjectData = $this->service('BankrollSubject')->getSubjectInfoById($subjectIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $projectIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getProjectInfo($projectIds){
		$projectData = array();
		
		$where = array(
			'identity'=>$projectIds
		);
		
		$projectList = $this->model('BudgetProject')->where($where)->select();
		if($projectList){
			
			if(is_array($projectIds)){
				$projectData = $projectList;
			}else{
				$projectData = current($projectList);
			}
			
			
		}
		
		
		return $projectData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $projectId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeProjectId($projectId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$projectId
		);
		
		$projectData = $this->model('BudgetProject')->where($where)->count();
		if($projectData){
			
			$output = $this->model('BudgetProject')->where($where)->delete();
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
	public function checkProject($idtype,$id,$uid){
		$projectId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$projectList = $this->model('BudgetProject')->field('identity,id')->where($where)->select();
		
		if($projectList){
			
			foreach($projectList as $key=>$project){
				$projectId[$project['identity']] = $project['id'];
			}
		}
		return $projectId;
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
	public function getProjectByIdtypeIds($idtype,$id,$uid){
		$projectData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$projectList = $this->model('BudgetProject')->field('identity,id')->where($where)->select();

		if($projectList){
			foreach($id as $key=>$val){
				$projectData[$key] = array('id'=>$val,'checked'=>0);
				foreach($projectList as $cnt=>$project){
					if($project['id'] == $val)
					{
						$projectData[$key] = array('id'=>$val,'checked'=>$project['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$projectData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $projectData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $projectId 收藏ID
	 * @param $projectNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($projectNewData,$projectId){
		$where = array(
			'identity'=>$projectId
		);
		
		$projectData = $this->model('BudgetProject')->where($where)->find();
		if($projectData){
			
			
			$projectNewData['lastupdate'] = $this->getTime();
			$this->model('BudgetProject')->data($projectNewData)->where($where)->save();
			
			
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
	public function insert($projectData){
		$dateline = $this->getTime();
		if($projectData['account_identity']){
			
		}
		$projectData['subscriber_identity'] = $this->session('uid');
		$projectData['dateline'] = $dateline;
		$projectData['sn'] = $this->get_sn();
		$projectData['lastupdate'] = $dateline;
		$projectId = $this->model('BudgetProject')->data($projectData)->add();
		if($projectId){
			$this->service('BankrollSubsidiary')->newIncome($projectData['account_identity'],$projectData['amount']);
		}
		return $projectId;
		
	}
}