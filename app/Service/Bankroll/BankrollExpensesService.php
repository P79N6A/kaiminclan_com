<?php
/**
 *
 * 转出
 *
 * 资金
 *
 */
class  BankrollExpensesService extends Service {
	
	
	
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
	public function getExpensesList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BankrollExpenses')->where($where)->count();
		if($count){
			$expensesHandle = $this->model('BankrollExpenses')->where($where)->orderby($orderby);
			if($start && $perpage){
				$expensesHandle = $expensesHandle->limit($start,$perpage,$count);
			}
			$listdata = $expensesHandle->select();
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
	 * @param $expensesIds 支出ID
	 *
	 * @reutrn int;
	 */
	public function getExpensesInfo($expensesIds){
		$expensesData = array();
		
		$where = array(
			'identity'=>$expensesIds
		);
		
		$expensesList = $this->model('BankrollExpenses')->where($where)->select();
		if($expensesList){
			
			if(is_array($expensesIds)){
				$expensesData = $expensesList;
			}else{
				$expensesData = current($expensesList);
			}
			
			
		}
		
		
		return $expensesData;
	}
	
	
		
	/**
	 *
	 * 删除支出
	 *
	 * @param $expensesId 支出ID
	 *
	 * @reutrn int;
	 */
	public function removeExpensesId($expensesId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$expensesId
		);
		
		$expensesData = $this->model('BankrollExpenses')->where($where)->count();
		if($expensesData){
			
			$output = $this->model('BankrollExpenses')->where($where)->delete();
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
	public function checkExpenses($idtype,$id,$uid){
		$expensesId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$expensesList = $this->model('BankrollExpenses')->field('identity,id')->where($where)->select();
		
		if($expensesList){
			
			foreach($expensesList as $key=>$expenses){
				$expensesId[$expenses['identity']] = $expenses['id'];
			}
		}
		return $expensesId;
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
	public function getExpensesByIdtypeIds($idtype,$id,$uid){
		$expensesData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$expensesList = $this->model('BankrollExpenses')->field('identity,id')->where($where)->select();

		if($expensesList){
			foreach($id as $key=>$val){
				$expensesData[$key] = array('id'=>$val,'checked'=>0);
				foreach($expensesList as $cnt=>$expenses){
					if($expenses['id'] == $val)
					{
						$expensesData[$key] = array('id'=>$val,'checked'=>$expenses['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$expensesData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $expensesData;
	}
	
	/**
	 *
	 * 支出修改
	 *
	 * @param $expensesId 支出ID
	 * @param $expensesNewData 支出数据
	 *
	 * @reutrn int;
	 */
	public function update($expensesNewData,$expensesId){
		$where = array(
			'identity'=>$expensesId
		);
		
		$expensesData = $this->model('BankrollExpenses')->where($where)->find();
		if($expensesData){
			
			
			$expensesNewData['lastupdate'] = $this->getTime();
			$this->model('BankrollExpenses')->data($expensesNewData)->where($where)->save();
			
			
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
	public function insert($expensesData){

	    $accountAmount = $this->service('BankrollAccount')->getBalanceByAid($expensesData['account_identity']);
        if($accountAmount < $expensesData['amount']){
            return -1;
        }
	    $dateline = $this->getTime();
		$expensesData['subscriber_identity'] = $this->session('uid');
		$expensesData['dateline'] = $dateline;
		$expensesData['sn'] = $this->get_sn();
		$expensesData['lastupdate'] = $dateline;


		$expensesId = $this->model('BankrollExpenses')->data($expensesData)->add();

		if($expensesId){
		    $this->service('BankrollAccount')->adjustAmount($expensesData['account_identity'],-$expensesData['amount']);
		}

		return $expensesId;
		
	}
}