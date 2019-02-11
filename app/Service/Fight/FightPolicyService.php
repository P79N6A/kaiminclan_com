<?php
/**
 *
 * 产品
 *
 * 基金
 *
 */
class  FightPolicyService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getPolicyList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FightPolicy')->where($where)->count();
		if($count){
			$handle = $this->model('FightPolicy')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$currencyIds = $catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['channel_identity'];
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FightPolicyModel::getStatusTitle($data['status'])
				);
				
			}
			
			$catalogueData = $this->service('FightChannel')->getChannelInfo($catalogueIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['channel'] = isset($catalogueData[$data['channel_identity']])?$catalogueData[$data['channel_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function adjustQuotientNum($policyId,$quantity){
		
		if(is_array($policyId)){
			$policyId = array($policyId);
		}
		
		$policyId = array_map('intval',$policyId);
		
		if(empty($policyId)){
			return 0;
		}
		if($quantity === 0){
			return 0;
		}
		
		$where = array();
		$where['identity'] = $policyId;
		
		if($quantity < 0){
			$quantity = substr($quantity,1);
			$this->model('FightPolicy')->where($where)->setDec('quotient_num',$quantity);
		}else{
			$this->model('FightPolicy')->where($where)->setInc('quotient_num',$quantity);
		}
		
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $policyId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getPolicyInfo($policyId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$policyId
		);
		
		$policyData = array();
		if(is_array($policyId)){
			$policyList = $this->model('FightPolicy')->field($field)->where($where)->select();
			if($policyList){
				foreach($policyList as $key=>$policy){
					$policyData[$policy['identity']] = $policy;
				}
			}
		}else{
			$policyData = $this->model('FightPolicy')->field($field)->where($where)->find();
		}
		return $policyData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $policyName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($policyName){
		if($policyName){
			$where = array(
				'title'=>$policyName
			);
			return $this->model('FightPolicy')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $policyId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removePolicyId($policyId){
		
		$output = 0;
		
		if(count($policyId) < 1){
			return $output;
		}		
		
		$where = array(
			'identity'=>$policyId
		);
		
		$policyData = $this->model('FightPolicy')->field('channel_identity')->where($where)->select();
		if($policyData){
			
			$output = $this->model('FightPolicy')->where($where)->delete();			
			
			$catlaogueIds = array();
			foreach($policyData as $key=>$policy){
				$catalogueIds[] = $policy['channel_identity'];
			}
			
			$this->service('FightChannel')->adjustPolicyNum($catalogueIds,'-'.count($catalogueIds));
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $policyId 分类ID
	 * @param $policyNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($policyNewData,$policyId){
		$where = array(
			'identity'=>$policyId
		);
		
		$policyData = $this->model('FightPolicy')->where($where)->find();
		if($policyData){
			
			$policyNewData['lastupdate'] = $this->getTime();
			$this->model('FightPolicy')->data($policyNewData)->where($where)->save();
			if($policyNewData['channel_identity'] != $policyData['channel_identity']){
				$this->service('FightChannel')->adjustPolicyNum($policyNewData['channel_identity'],1);
				$this->service('FightChannel')->adjustPolicyNum($policyData['channel_identity'],-1);
			}
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $policyNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($policyNewData){
		if(!$policyNewData){
			return -1;
		}
		$policyNewData['sn'] = $this->get_sn();
		$policyNewData['subscriber_identity'] =$this->session('uid');
		$policyNewData['dateline'] = $this->getTime();
		$policyNewData['lastupdate'] = $policyNewData['dateline'];
		
		$policyId = $this->model('FightPolicy')->data($policyNewData)->add();
		if($policyId){
			$this->service('FightChannel')->adjustPolicyNum($policyNewData['channel_identity'],1);
		}
		
		return $policyId;
	}
}