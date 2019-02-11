<?php
/**
 *
 * 债务
 *
 * 页面
 *
 */
class PermanentIndebtednessService extends Service
{
	
	/**
	 *
	 * 债务信息
	 *
	 * @param $field 债务字段
	 * @param $status 债务状态
	 *
	 * @reutrn array;
	 */
	public function getIndebtednessList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PermanentIndebtedness')->where($where)->count();
		if($count){
			$handle = $this->model('PermanentIndebtedness')->where($where);
			if($start > 0 && $perpage > 0){
				$handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle->select();
			
			$creditIds = array();
			foreach($listdata as $key=>$data){
				$creditIds[] = $data['credit_identity'];
			}
			
			$creditData = $this->service('PermanentCredit')->getCreditInfo($creditIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['credit'] = isset($creditData[$data['credit_identity']])?$creditData[$data['credit_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 债务信息
	 *
	 * @param $indebtednessId 债务ID
	 *
	 * @reutrn array;
	 */
	public function getIndebtednessInfo($indebtednessId,$field = '*'){
		
		$where = array(
			'identity'=>$indebtednessId
		);
		
		$indebtednessData = $this->model('PermanentIndebtedness')->field($field)->where($where)->find();
		
		return $indebtednessData;
	}
	
	/**
	 *
	 * 删除债务
	 *
	 * @param $indebtednessId 债务ID
	 *W
	 * @reutrn int;
	 */
	public function removeIndebtednessId($indebtednessId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$indebtednessId
		);
		
		$indebtednessData = $this->model('PermanentIndebtedness')->where($where)->find();
		if($indebtednessData){
			
			$output = $this->model('PermanentIndebtedness')->where($where)->delete();
			
			$this->service('PaginationItem')->removeIndebtednessIdAllItem($indebtednessId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 债务修改
	 *
	 * @param $indebtednessId 债务ID
	 * @param $indebtednessNewData 债务数据
	 *
	 * @reutrn int;
	 */
	public function update($indebtednessNewData,$indebtednessId){
		$where = array(
			'identity'=>$indebtednessId
		);
		
		$indebtednessData = $this->model('PermanentIndebtedness')->where($where)->find();
		if($indebtednessData){
			
			$indebtednessNewData['lastupdate'] = $this->getTime();
			$this->model('PermanentIndebtedness')->data($indebtednessNewData)->where($where)->save();
			
			if($indebtednessData['account_identity'] != $indebtednessNewData['account_identity']){
				
				$this->service('MechanismAccount')->adjustAmount($indebtednessData['account_identity'],-$indebtednessNewData['amount']);
			
				$this->service('MechanismAccount')->adjustAmount($indebtednessNewData['account_identity'],$indebtednessNewData['amount']);
			}else{
				if($indebtednessData['amount'] != $indebtednessNewData['amount']){
					$this->service('MechanismAccount')->adjustAmount($indebtednessNewData['account_identity'],-$indebtednessData['amount']);			
					$this->service('MechanismAccount')->adjustAmount($indebtednessNewData['account_identity'],$indebtednessNewData['amount']);
				}
			}
			
			if($indebtednessData['subject_identity'] != $indebtednessNewData['subject_identity']){
				
				$this->service('MechanismSubject')->adjustAmount($indebtednessNewData['subject_identity'],$indebtednessNewData['amount']);
				$this->service('MechanismSubject')->adjustAmount($indebtednessData['subject_identity'],-$indebtednessData['amount']);
			}
			if($indebtednessData['credit_identity'] != $indebtednessNewData['credit_identity']){
				$this->service('PermanentCredit')->adjustAmount($indebtednessData['credit_identity'],$indebtednessData['amount']);
				$this->service('PermanentCredit')->adjustAmount($indebtednessNewData['credit_identity'],-$indebtednessNewData['amount']);
			}else{
				if($indebtednessData['amount'] != $indebtednessNewData['amount']){
					$this->service('PermanentCredit')->adjustAmount($indebtednessData['credit_identity'],$indebtednessData['amount']);
					$this->service('PermanentCredit')->adjustAmount($indebtednessNewData['credit_identity'],-$indebtednessNewData['amount']);
					
					$this->service('MechanismSubject')->adjustAmount($indebtednessData['subject_identity'],-$indebtednessData['amount']);
					
					$this->service('MechanismSubject')->adjustAmount($indebtednessNewData['subject_identity'],$indebtednessNewData['amount']);
				}
			}
		}
	}
	
	public function push($creditId,$title,$content,$amount,$currencyId,$subjectId,$deadline = 60*60*24*31){
		$indebtednessData = array(
			'title'=>$title,
			'content'=>'自动导入',
			'amount'=>$amount,
			'credit_identity'=>$creditId,
			'currency_identity'=>$currencyId,
			'subject_identity'=>$subjectId,
			'start_date'=>$this->getTime()
		);
		$indebtednessData['stop_date'] = $indebtednessData['start_date']+60*60*$deadline;
		
		return $this->insert($indebtednessData,0);
	}
	
	/**
	 *
	 * 新债务
	 *
	 * @param $indebtednessNewData 债务数据
	 *
	 * @reutrn int;
	 */
	public function insert($indebtednessNewData,$isUpdate = 1){
		
		$indebtednessNewData['subscriber_identity'] =$this->session('uid');
		$indebtednessNewData['dateline'] = $this->getTime();
		$indebtednessNewData['sn'] = $this->get_sn();
			
		$indebtednessNewData['lastupdate'] = $indebtednessNewData['dateline'];
		$indebtednessId = $this->model('PermanentIndebtedness')->data($indebtednessNewData)->add();
		if($indebtednessId && $isUpdate){
			$this->service('MechanismSubject')->adjustAmount($indebtednessNewData['subject_identity'],$indebtednessNewData['amount']);
			$this->service('PermanentCredit')->adjustAmount($indebtednessNewData['credit_identity'],-$indebtednessNewData['amount']);
			$this->service('MechanismAccount')->adjustAmount($indebtednessNewData['account_identity'],$indebtednessNewData['amount']);
		}
		
		return $indebtednessId;
	}
}