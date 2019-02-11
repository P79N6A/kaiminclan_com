<?php
/**
 *
 * 转出
 *
 * 资金
 *
 */
class  BudgetProcedureService extends Service {
	
	
	
	/**
	 *
	 * 支出列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getProcedureList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BudgetProcedure')->where($where)->count();
		if($count){
			$procedureHandle = $this->model('BudgetProcedure')->where($where)->orderby($orderby);
			if($start && $perpage){
				$procedureHandle = $procedureHandle->limit($start,$perpage,$count);
			}
			$listdata = $procedureHandle->select();
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
	 * 支出信息
	 *
	 * @param $procedureIds 支出ID
	 *
	 * @reutrn int;
	 */
	public function getProcedureInfo($procedureIds){
		$procedureData = array();
		
		$where = array(
			'identity'=>$procedureIds
		);
		
		$procedureList = $this->model('BudgetProcedure')->where($where)->select();
		if($procedureList){
			
			if(is_array($procedureIds)){
				$procedureData = $procedureList;
			}else{
				$procedureData = current($procedureList);
			}
			
			
		}
		
		
		return $procedureData;
	}
	
	
		
	/**
	 *
	 * 删除支出
	 *
	 * @param $procedureId 支出ID
	 *
	 * @reutrn int;
	 */
	public function removeProcedureId($procedureId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$procedureId
		);
		
		$procedureData = $this->model('BudgetProcedure')->where($where)->count();
		if($procedureData){
			
			$output = $this->model('BudgetProcedure')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测支出
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkProcedure($idtype,$id,$uid){
		$procedureId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$procedureList = $this->model('BudgetProcedure')->field('identity,id')->where($where)->select();
		
		if($procedureList){
			
			foreach($procedureList as $key=>$procedure){
				$procedureId[$procedure['identity']] = $procedure['id'];
			}
		}
		return $procedureId;
	}
	
	/**
	 *
	 * 检测支出
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function getProcedureByIdtypeIds($idtype,$id,$uid){
		$procedureData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$procedureList = $this->model('BudgetProcedure')->field('identity,id')->where($where)->select();

		if($procedureList){
			foreach($id as $key=>$val){
				$procedureData[$key] = array('id'=>$val,'checked'=>0);
				foreach($procedureList as $cnt=>$procedure){
					if($procedure['id'] == $val)
					{
						$procedureData[$key] = array('id'=>$val,'checked'=>$procedure['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$procedureData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $procedureData;
	}
	
	/**
	 *
	 * 支出修改
	 *
	 * @param $procedureId 支出ID
	 * @param $procedureNewData 支出数据
	 *
	 * @reutrn int;
	 */
	public function update($procedureNewData,$procedureId){
		$where = array(
			'identity'=>$procedureId
		);
		
		$procedureData = $this->model('BudgetProcedure')->where($where)->find();
		if($procedureData){
			
			
			$procedureNewData['lastupdate'] = $this->getTime();
			$this->model('BudgetProcedure')->data($procedureNewData)->where($where)->save();
			
			
		}
	}
	
	/**
	 *
	 * 新支出
	 *
	 * @param $id 支出信息
	 * @param $idtype 支出信息
	 *
	 * @reutrn int;
	 */
	public function insert($procedureData){
		
		$dateline = $this->getTime();
		$procedureData['subscriber_identity'] = $this->session('uid');
		$procedureData['dateline'] = $dateline;
		$procedureData['sn'] = $this->get_sn();
		$procedureData['lastupdate'] = $dateline;
		$procedureId = $this->model('BudgetProcedure')->data($procedureData)->add();
			
		if($procedureId){
			$this->service('BankrollSubsidiary')->newLeave($procedureData['account_identity'],$procedureData['amount']);
		}
		
		return $procedureId;
		
	}
}