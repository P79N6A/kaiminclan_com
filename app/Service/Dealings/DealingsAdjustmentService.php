<?php
/**
 *
 * 转账
 *
 * 页面
 *
 */
class DealingsAdjustmentService extends Service
{
	
	/**
	 *
	 * 转账信息
	 *
	 * @param $field 转账字段
	 * @param $status 转账状态
	 *
	 * @reutrn array;
	 */
	public function getAdjustmentList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('DealingsAdjustment')->where($where)->count();
		if($count){
			$handle = $this->model('DealingsAdjustment')->where($where);
			if($start && $perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测转账名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkAdjustmentTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('DealingsAdjustment')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 转账信息
	 *
	 * @param $adjustmentId 转账ID
	 *
	 * @reutrn array;
	 */
	public function getAdjustmentInfo($adjustmentId,$field = '*'){
		
		$where = array(
			'identity'=>$adjustmentId
		);
		
		$adjustmentData = $this->model('DealingsAdjustment')->field($field)->where($where)->find();
		
		return $adjustmentData;
	}
	
	/**
	 *
	 * 删除转账
	 *
	 * @param $adjustmentId 转账ID
	 *
	 * @reutrn int;
	 */
	public function removeAdjustmentId($adjustmentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$adjustmentId
		);
		
		$adjustmentData = $this->model('DealingsAdjustment')->where($where)->find();
		if($adjustmentData){
			
			$output = $this->model('DealingsAdjustment')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 转账修改
	 *
	 * @param $adjustmentId 转账ID
	 * @param $adjustmentNewData 转账数据
	 *
	 * @reutrn int;
	 */
	public function update($adjustmentNewData,$adjustmentId){
		$where = array(
			'identity'=>$adjustmentId
		);
		
		$adjustmentData = $this->model('DealingsAdjustment')->where($where)->find();
		if($adjustmentData){
			
			$adjustmentNewData['lastupdate'] = $this->getTime();
			$this->model('DealingsAdjustment')->data($adjustmentNewData)->where($where)->save();

			if($adjustmentData['rollout_account_identity'] != $adjustmentNewData['rollout_account_identity']){
                $this->service('MechanismAccount')->adjustAmount($adjustmentData['rollout_account_identity'],$adjustmentNewData['amount']);
                $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['rollout_account_identity'],-$adjustmentNewData['amount']);
            }

			if($adjustmentNewData['into_account_identity'] != $adjustmentData['into_account_identity']){

                $this->service('MechanismAccount')->adjustAmount($adjustmentData['into_account_identity'],-$adjustmentNewData['amount']);
                $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['into_account_identity'],$adjustmentNewData['amount']);
            }

			if($adjustmentData['subject_identity'] != $adjustmentNewData['subject_identity'] || $adjustmentData['into_account_identity'] != $adjustmentNewData['into_account_identity']){
				$this->service('DealingsSubsidiary')->removeFlowing($adjustmentData['into_account_identity'],$adjustmentData['first_subject_identity']);
				$this->service('DealingsSubsidiary')->newFlowing($adjustmentNewData['into_account_identity'],$adjustmentNewData['first_subject_identity'],$adjustmentNewData['amount']);
			}
			elseif($adjustmentData['amount'] != $adjustmentNewData['amount']){
				$this->service('DealingsSubsidiary')->changeFlowingAmount($adjustmentNewData['into_account_identity'],$adjustmentNewData['subject_identity'],$adjustmentNewData['amount']);
			}
			
			if($adjustmentData['subject_identity'] != $adjustmentNewData['subject_identity'] || $adjustmentData['rollout_account_identity'] != $adjustmentNewData['rollout_account_identity']){
				$this->service('DealingsSubsidiary')->removeFlowing($adjustmentData['rollout_account_identity'],$adjustmentData['subject_identity']);
				$this->service('DealingsSubsidiary')->newFlowing($adjustmentNewData['rollout_account_identity'],$adjustmentNewData['subject_identity'],$adjustmentNewData['amount']);
			}
			elseif($adjustmentData['amount'] != $adjustmentNewData['amount']){
				$this->service('DealingsSubsidiary')->changeFlowingAmount($adjustmentNewData['into_account_identity'],$adjustmentNewData['subject_identity'],$adjustmentNewData['amount']);
				$this->service('DealingsSubsidiary')->changeFlowingAmount($adjustmentNewData['rollout_account_identity'],$adjustmentNewData['subject_identity'],$adjustmentNewData['amount']);


                $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['into_account_identity'],-$adjustmentNewData['amount']);
                $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['into_account_identity'],$adjustmentNewData['amount']);

                $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['rollout_account_identity'],$adjustmentNewData['amount']);
                $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['rollout_account_identity'],-$adjustmentNewData['amount']);
			}
		}
	}
	
	/**
	 *
	 * 新转账
	 *
	 * @param $adjustmentNewData 转账数据
	 *
	 * @reutrn int;
	 */
	public function insert($adjustmentNewData){
		
		$adjustmentNewData['subscriber_identity'] =$this->session('uid');
		$adjustmentNewData['dateline'] = $this->getTime();
		$adjustmentNewData['sn'] = $this->get_sn();

		$bankData = $this->service('MechanismBank')->getBankInfo($adjustmentNewData['bank_identity']);
		if($bankData){
            $adjustmentNewData['brokerage'] = $adjustmentNewData['amount']*$bankData['alleyway'];
        }
			
		$adjustmentNewData['lastupdate'] = $adjustmentNewData['dateline'];
		$adjustmentId = $this->model('DealingsAdjustment')->data($adjustmentNewData)->add();
		if($adjustmentId){
			$this->service('DealingsSubsidiary')->newFlowing($adjustmentNewData['into_account_identity'],$adjustmentNewData['first_subject_identity'],$adjustmentNewData['amount']);
			$this->service('DealingsSubsidiary')->newFlowing($adjustmentNewData['rollout_account_identity'],$adjustmentNewData['rollout_account_identity'],$adjustmentNewData['amount']);


            $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['rollout_account_identity'],-$adjustmentNewData['amount']);
            $this->service('MechanismAccount')->adjustAmount($adjustmentNewData['into_account_identity'],$adjustmentNewData['amount']);
		}
		return $adjustmentId;
	}
}