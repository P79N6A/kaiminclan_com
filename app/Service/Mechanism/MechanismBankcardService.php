<?php
/**
 *
 * 账户
 *
 * 财务
 *
 */
class MechanismBankcardService extends Service
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
	public function getBankcardList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MechanismBankcard')->where($where)->count();
		if($count){
			$handle = $this->model('MechanismBankcard')->where($where);
			if($start > 0 && $perpage > 0){
				$handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle->select();
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
	public function checkBankcardTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('MechanismBankcard')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $bankcardId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getBankcardInfo($bankcardId){
		
		$bankcardId = $this->getInt($bankcardId);
		if(!$bankcardId){
			return array();
		}
		
		$where = array(
			'identity'=>$bankcardId
		);
		
		$bankcardData = $this->model('MechanismBankcard')->where($where)->select();
		if(!is_array($bankcardId)){
			$bankcardData = current($bankcardData);
		}
		
		return $bankcardData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $bankcardId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeBankcardId($bankcardId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$bankcardId
		);
		
		$bankcardData = $this->model('MechanismBankcard')->where($where)->find();
		if($bankcardData){
			
			$output = $this->model('MechanismBankcard')->where($where)->delete();
			
			$this->service('PaginationItem')->removeBankcardIdAllItem($bankcardId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $bankcardId 模块ID
	 * @param $bankcardNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($bankcardNewData,$bankcardId){
		$where = array(
			'identity'=>$bankcardId
		);
		
		$bankcardData = $this->model('MechanismBankcard')->where($where)->find();
		if($bankcardData){
			
			$bankcardNewData['lastupdate'] = $this->getTime();
			$this->model('MechanismBankcard')->data($bankcardNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $bankcardNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($bankcardNewData){
		
		$bankcardNewData['subscriber_identity'] =$this->session('uid');
		$bankcardNewData['dateline'] = $this->getTime();
		$bankcardNewData['sn'] = $this->get_sn();
			
		$bankcardNewData['lastupdate'] = $bankcardNewData['dateline'];
		$this->model('MechanismBankcard')->data($bankcardNewData)->add();
	}
}