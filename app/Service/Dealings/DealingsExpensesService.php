<?php
/**
 *
 * 支出
 *
 * 页面
 *
 */
class DealingsExpensesService extends Service
{
	
	/**
	 *
	 * 支出信息
	 *
	 * @param $field 支出字段
	 * @param $status 支出状态
	 *
	 * @reutrn array;
	 */
	public function getExpensesList($where,$start,$perpage,$order = ''){
		$count = $this->model('DealingsExpenses')->where($where)->count();
		if($count){
			$handle = $this->model('DealingsExpenses')->where($where);
			if($start && $perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkExpensesTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('DealingsExpenses')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 支出信息
	 *
	 * @param $expensesId 支出ID
	 *
	 * @reutrn array;
	 */
	public function getExpensesInfo($expensesId,$field = '*'){
		
		$where = array(
			'identity'=>$expensesId
		);
		
		$expensesData = $this->model('DealingsExpenses')->field($field)->where($where)->find();
		
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
		
		$expensesData = $this->model('DealingsExpenses')->where($where)->find();
		if($expensesData){
			
			$output = $this->model('DealingsExpenses')->where($where)->delete();
			
		}
		
		return $output;
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
		
		$expensesData = $this->model('DealingsExpenses')->where($where)->find();
		if($expensesData){
			
			$expensesNewData['lastupdate'] = $this->getTime();
			$this->model('DealingsExpenses')->data($expensesNewData)->where($where)->save();


            if($expensesNewData['account_identity'] != $expensesData['account_identity']){
                $this->service('MechanismAccount')->adjustAmount($expensesNewData['account_identity'],-$expensesNewData['amount']);
                $this->service('MechanismAccount')->adjustAmount($expensesData['account_identity'],$expensesData['amount']);
            }
			if($expensesData['first_subject_identity'] != $expensesNewData['first_subject_identity'] || $expensesData['account_identity'] != $expensesNewData['account_identity']){
				$this->service('DealingsSubsidiary')->removeFlowing($expensesData['account_identity'],$expensesData['first_subject_identity']);
				$this->service('DealingsSubsidiary')->newFlowing($expensesNewData['account_identity'],$expensesNewData['first_subject_identity'],$expensesNewData['amount']);
			}
			elseif($expensesData['amount'] != $expensesNewData['amount']){
				$this->service('DealingsSubsidiary')->changeFlowingAmount($expensesNewData['account_identity'],$expensesNewData['first_subject_identity'],$expensesNewData['amount']);
                $this->service('MechanismAccount')->adjustAmount($expensesNewData['account_identity'],$expensesData['amount']);
                $this->service('MechanismAccount')->adjustAmount($expensesNewData['account_identity'],-$expensesNewData['amount']);
			}



		}
	}
	
	/**
	 *
	 * 新支出
	 *
	 * @param $expensesNewData 支出数据
	 *
	 * @reutrn int;
	 */
	public function insert($expensesNewData){
		
		$expensesNewData['subscriber_identity'] =$this->session('uid');
		$expensesNewData['dateline'] = $this->getTime();
		$expensesNewData['sn'] = $this->get_sn();
			
		$expensesNewData['lastupdate'] = $expensesNewData['dateline'];
		$expensesId = $this->model('DealingsExpenses')->data($expensesNewData)->add();
		if($expensesId){
			$this->service('DealingsSubsidiary')->newFlowing($expensesNewData['account_identity'],$expensesNewData['first_subject_identity'],$expensesNewData['amount']);
			switch($expensesNewData['idtype']){
				case 1:
					$this->service('PermanentPayable')->closePayable($expensesId);
					break;
			}
			//$title,$content,$amount,$currencyId,$subjectId
			$this->service('MechanismAccount')->adjustAmount($expensesNewData['account_identity'],-$expensesNewData['amount'],array(
				$expensesNewData['title'],
				$expensesNewData['content'],
				$expensesNewData['amount'],
				$expensesNewData['currency_identity'],
				$expensesNewData['first_subject_identity']
			));
		}
		return $expensesId;
	}

    public function push($accountId,$title,$amount){
        return $this->insert(array('account_identity'=>$accountId,'title'=>$title,'happen_date'=>$this->getTime(),'amount'=>$amount,'status'=>1));
    }
}