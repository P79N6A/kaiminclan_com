<?php
/**
 *
 * 转入
 *
 * 资金
 *
 */
class  BankrollRevenueService extends Service {
	
	
	
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
	public function getRevenueList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('BankrollRevenue')->where($where)->count();
		if($count){
			$revenueHandle = $this->model('BankrollRevenue')->where($where)->orderby($orderby);
			if($start && $perpage){
				$revenueHandle = $revenueHandle->limit($start,$perpage,$count);
			}
			$listdata = $revenueHandle->select();
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
	 * @param $revenueIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getRevenueInfo($revenueIds){
		$revenueData = array();
		
		$where = array(
			'identity'=>$revenueIds
		);
		
		$revenueList = $this->model('BankrollRevenue')->where($where)->select();
		if($revenueList){
			
			if(is_array($revenueIds)){
				$revenueData = $revenueList;
			}else{
				$revenueData = current($revenueList);
			}
			
			
		}
		
		
		return $revenueData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $revenueId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeRevenueId($revenueId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$revenueId
		);
		
		$revenueData = $this->model('BankrollRevenue')->where($where)->count();
		if($revenueData){
			
			$output = $this->model('BankrollRevenue')->where($where)->delete();
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
	public function checkRevenue($idtype,$id,$uid){
		$revenueId = array();		
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>intval($uid),
		);
		
		
		$revenueList = $this->model('BankrollRevenue')->field('identity,id')->where($where)->select();
		
		if($revenueList){
			
			foreach($revenueList as $key=>$revenue){
				$revenueId[$revenue['identity']] = $revenue['id'];
			}
		}
		return $revenueId;
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
	public function getRevenueByIdtypeIds($idtype,$id,$uid){
		$revenueData = array();
		
		if(!is_array($id)){
			$id = array($id);
		}
		$where = array(
			'idtype'=>intval($idtype),
			'id'=>$id,
			'subscriber_identity'=>$uid,
		);
		
		
		$revenueList = $this->model('BankrollRevenue')->field('identity,id')->where($where)->select();

		if($revenueList){
			foreach($id as $key=>$val){
				$revenueData[$key] = array('id'=>$val,'checked'=>0);
				foreach($revenueList as $cnt=>$revenue){
					if($revenue['id'] == $val)
					{
						$revenueData[$key] = array('id'=>$val,'checked'=>$revenue['identity']);
					}
				}
			}
		}else{
			foreach($id as $key=>$val){
				$revenueData[] = array('id'=>$val,'checked'=>0);
			}
		}
		
		return $revenueData;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $revenueId 收藏ID
	 * @param $revenueNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($revenueNewData,$revenueId){
		$where = array(
			'identity'=>$revenueId
		);
		
		$revenueData = $this->model('BankrollRevenue')->where($where)->find();
		if($revenueData){
			
			
			$revenueNewData['lastupdate'] = $this->getTime();
			$this->model('BankrollRevenue')->data($revenueNewData)->where($where)->save();
			
			
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
	public function insert($revenueData){
		$dateline = $this->getTime();

        $financeAccountId = $this->service('BankrollTrusteeship')->getFinanceAccountIdByAccountId($revenueData['account_identity']);

        $avabileBalance = $this->service('MechanismAccount')->getBalanceByAid($financeAccountId[$revenueData['account_identity']]);
        if($avabileBalance < $revenueData['amount']){
            return -1;
        }

		$revenueData['subscriber_identity'] = $this->session('uid');
		$revenueData['dateline'] = $dateline;
		$revenueData['sn'] = $this->get_sn();
		$revenueData['lastupdate'] = $dateline;
		$revenueId = $this->model('BankrollRevenue')->data($revenueData)->add();
		if($revenueId){
		    $this->service('BankrollAccount')->adjustAmount($expensesData['account_identity'],$revenueData['amount']);
		}
		return $revenueId;
		
	}
}