<?php
/**
 *
 * 账户
 *
 * 财务
 *
 */
class MechanismBankService extends Service
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
	public function getBankList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MechanismBank')->where($where)->count();
		if($count){
			$handle = $this->model('MechanismBank')->where($where);
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
	public function checkBankTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('MechanismBank')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $bankId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getBankInfo($bankId){
		
		$bankId = $this->getInt($bankId);
		if(!$bankId){
			return array();
		}
		
		$where = array(
			'identity'=>$bankId
		);
		
		$bankData = $this->model('MechanismBank')->where($where)->select();
		if(!is_array($bankId)){
			$bankData = current($bankData);
		}
		
		return $bankData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $bankId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeBankId($bankId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$bankId
		);
		
		$bankData = $this->model('MechanismBank')->where($where)->find();
		if($bankData){
			
			$output = $this->model('MechanismBank')->where($where)->delete();
			
			$this->service('PaginationItem')->removeBankIdAllItem($bankId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $bankId 模块ID
	 * @param $bankNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($bankNewData,$bankId){
		$where = array(
			'identity'=>$bankId
		);
		
		$bankData = $this->model('MechanismBank')->where($where)->find();
		if($bankData){
			
			$bankNewData['lastupdate'] = $this->getTime();
			$this->model('MechanismBank')->data($bankNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $bankNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($bankNewData){
		
		$bankNewData['subscriber_identity'] =$this->session('uid');
		$bankNewData['dateline'] = $this->getTime();
		$bankNewData['sn'] = $this->get_sn();
			
		$bankNewData['lastupdate'] = $bankNewData['dateline'];
		$this->model('MechanismBank')->data($bankNewData)->add();
	}
}