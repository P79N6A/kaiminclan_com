<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class DealingsRevenueService extends Service
{
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getRevenueList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('DealingsRevenue')->where($where)->count();
		if($count){
			$handle = $this->model('DealingsRevenue')->where($where);
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
	public function checkRevenueTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('DealingsRevenue')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $revenueId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getRevenueInfo($revenueId,$field = '*'){
		
		$where = array(
			'identity'=>$revenueId
		);
		
		$revenueData = $this->model('DealingsRevenue')->field($field)->where($where)->find();
		
		return $revenueData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $revenueId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeRevenueId($revenueId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$revenueId
		);
		
		$revenueData = $this->model('DealingsRevenue')->where($where)->find();
		if($revenueData){
			
			$output = $this->model('DealingsRevenue')->where($where)->delete();
			
			$this->service('PaginationItem')->removeRevenueIdAllItem($revenueId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $revenueId 模块ID
	 * @param $revenueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($revenueNewData,$revenueId){
		$where = array(
			'identity'=>$revenueId
		);
		
		$revenueData = $this->model('DealingsRevenue')->where($where)->find();
		if($revenueData){
			
			$revenueNewData['lastupdate'] = $this->getTime();
			$this->model('DealingsRevenue')->data($revenueNewData)->where($where)->save();

            if($revenueNewData['account_identity'] != $revenueData['account_identity']){
                $this->service('MechanismAccount')->adjustAmount($revenueNewData['account_identity'],$revenueNewData['amount']);
                $this->service('MechanismAccount')->adjustAmount($revenueData['account_identity'],-$revenueData['amount']);
            }
			if($revenueData['first_subject_identity'] != $revenueNewData['first_subject_identity'] || $revenueNewData['account_identity'] != $revenueNewData['account_identity']){
				$this->service('DealingsSubsidiary')->removeFlowing($revenueData['account_identity'],$revenueData['first_subject_identity']);
				$this->service('DealingsSubsidiary')->newFlowing($revenueNewData['account_identity'],$revenueNewData['first_subject_identity'],$revenueNewData['amount']);
			}
			elseif($revenueData['amount'] != $revenueNewData['amount']){
				$this->service('DealingsSubsidiary')->changeFlowingAmount($revenueData['account_identity'],$revenueData['first_subject_identity'],$revenueData['amount']);
                $this->service('MechanismAccount')->adjustAmount($revenueData['account_identity'],-$revenueData['amount']);
                $this->service('MechanismAccount')->adjustAmount($revenueData['account_identity'],$revenueNewData['amount']);
			}
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $revenueNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($revenueNewData){
		
		$revenueNewData['subscriber_identity'] =$this->session('uid');
		$revenueNewData['dateline'] = $this->getTime();
		$revenueNewData['sn'] = $this->get_sn();
			
		$revenueNewData['lastupdate'] = $revenueNewData['dateline'];
		$revenueId = $this->model('DealingsRevenue')->data($revenueNewData)->add();
		if($revenueId){
			$this->service('DealingsSubsidiary')->newFlowing($revenueNewData['account_identity'],$revenueNewData['first_subject_identity'],$revenueNewData['amount']);
            $this->service('MechanismAccount')->adjustAmount($revenueNewData['account_identity'],$revenueNewData['amount']);
		}
		return $revenueId;
	}

	public function push($accountId,$title,$amount){
        return $this->insert(array('account_identity'=>$accountId,'title'=>$title,'happen_date'=>$this->getTime(),'amount'=>$amount,'status'=>1));
    }
}