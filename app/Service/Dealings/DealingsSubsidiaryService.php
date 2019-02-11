<?php
/**
 *
 * 流水
 *
 * 页面
 *
 */
class DealingsSubsidiaryService extends Service
{
	
	/**
	 *
	 * 流水信息
	 *
	 * @param $field 流水字段
	 * @param $status 流水状态
	 *
	 * @reutrn array;
	 */
	public function getSubsidiaryList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('DealingsSubsidiary')->where($where)->count();
		if($count){
			$handle = $this->model('DealingsSubsidiary')->where($where);
			if($start && $perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$accountIds = $subjectIds = array();
			foreach($listdata as $key=>$data){
				$accountIds[] = $data['account_identity'];
				$subjectIds[] = $data['subject_identity'];
			}
			
			$accountData = $this->service('MechanismAccount')->getAccountInfo($accountIds);
			$subjectData = $this->service('MechanismSubject')->getSubjectInfo($subjectIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['account'] = isset($accountData[$data['account_identity']])?$accountData[$data['account_identity']]:array();
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
			}
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
	public function checkSubsidiaryTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('DealingsSubsidiary')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 流水信息
	 *
	 * @param $subsidiaryId 流水ID
	 *
	 * @reutrn array;
	 */
	public function getSubsidiaryInfo($subsidiaryId,$field = '*'){
		
		$where = array(
			'identity'=>$subsidiaryId
		);
		
		$subsidiaryData = $this->model('DealingsSubsidiary')->field($field)->where($where)->find();
		
		return $subsidiaryData;
	}
	
	/**
	 *
	 * 删除流水
	 *
	 * @param $subsidiaryId 流水ID
	 *
	 * @reutrn int;
	 */
	public function removeSubsidiaryId($subsidiaryId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subsidiaryId
		);
		
		$subsidiaryData = $this->model('DealingsSubsidiary')->where($where)->find();
		if($subsidiaryData){
			
			$output = $this->model('DealingsSubsidiary')->where($where)->delete();
			
			$this->service('PaginationItem')->removeSubsidiaryIdAllItem($subsidiaryId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 流水修改
	 *
	 * @param $subsidiaryId 流水ID
	 * @param $subsidiaryNewData 流水数据
	 *
	 * @reutrn int;
	 */
	public function update($subsidiaryNewData,$subsidiaryId){
		$where = array(
			'identity'=>$subsidiaryId
		);
		
		$subsidiaryData = $this->model('DealingsSubsidiary')->where($where)->find();
		if($subsidiaryData){
			
			$subsidiaryNewData['lastupdate'] = $this->getTime();
			$this->model('DealingsSubsidiary')->data($subsidiaryNewData)->where($where)->save();
		}
	}
	
	public function newFlowing($accountId,$subjectId,$amount){
		return $this->insert(array('account_identity'=>$accountId,'subject_identity'=>$subjectId,'amount'=>$amount));
	}
	
	public function removeFlowing($accountId,$subjectId){
		$where = array();
		$where['account_identity'] = $accountId;
		$where['subject_identity'] = $subjectId;		
		
		$this->model('DealingsSubsidiary')->where($where)->delete();
		
	}
	
	public function changeFlowingAmount($accountId,$subjectId,$amount){
		$where = array();
		$where['account_identity'] = $accountId;
		$where['subject_identity'] = $subjectId;		
		
		$this->model('DealingsSubsidiary')->data(array('amount'=>$amount,'lastupdate'=>$this->getTime()))->where($where)->save();
		
	}
	
	/**
	 *
	 * 新流水
	 *
	 * @param $subsidiaryNewData 流水数据
	 *
	 * @reutrn int;
	 */
	public function insert($subsidiaryNewData){
		
		$subsidiaryNewData['subscriber_identity'] =$this->session('uid');
		$subsidiaryNewData['dateline'] = $this->getTime();
		
		$subsidiaryNewData['sn'] = $this->get_sn();
			
		$subsidiaryNewData['lastupdate'] = $subsidiaryNewData['dateline'];
		$this->model('DealingsSubsidiary')->data($subsidiaryNewData)->add();
	}
}