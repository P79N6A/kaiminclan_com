<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class ForeignContactService extends Service
{
	
	/**
	 *
	 * 货币合约信息
	 *
	 * @param $field 货币合约字段
	 * @param $status 货币合约状态
	 *
	 * @reutrn array;
	 */
	public function getContactList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ForeignContact')->where($where)->count();
		if($count){
			$handle = $this->model('ForeignContact')->where($where);
			if($perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			if($order){
				$handle = $handle->orderby($order);
			}
			$listdata = $handle ->select();
			
			$currencyIds = array();
			foreach($listdata as $key=>$data){
				$currencyIds[] = $data['currency_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>ForeignContactModel::getStatusTitle($data['status'])
				);
			}
			
			$currencyData = $this->service('ForeignCurrency')->getCurrencyInfo($currencyIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['currency'] = isset($currencyData[$data['currency_identity']])?$currencyData[$data['currency_identity']]:array();
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
	public function checkContactTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('ForeignContact')->where($where)->count();
		}
		return 0;
	}
	
	public function getContactBySymbol($symbol){
		$where = array(
			'code'=>strtolower($symbol)
		);
		return $this->model('ForeignContact')->field('identity')->where($where)->find();
	}
	
	/**
	 *
	 * 货币合约信息
	 *
	 * @param $contactId 货币合约ID
	 *
	 * @reutrn array;
	 */
	public function getContactInfo($contactId,$field = '*'){
		
		$where = array(
			'identity'=>$contactId
		);
		
		$contactData = $this->model('ForeignContact')->field($field)->where($where)->select();
		
		return $contactData;
	}
	
	/**
	 *
	 * 删除货币合约
	 *
	 * @param $contactId 货币合约ID
	 *
	 * @reutrn int;
	 */
	public function removeContactId($contactId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$contactId
		);
		
		$contactData = $this->model('ForeignContact')->where($where)->find();
		if($contactData){
			
			$output = $this->model('ForeignContact')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 货币合约修改
	 *
	 * @param $contactId 货币合约ID
	 * @param $contactNewData 货币合约数据
	 *
	 * @reutrn int;
	 */
	public function update($contactNewData,$contactId){
		$where = array(
			'identity'=>$contactId
		);
		
		$contactData = $this->model('ForeignContact')->where($where)->find();
		if($contactData){
			
			$contactNewData['lastupdate'] = $this->getTime();
			$this->model('ForeignContact')->data($contactNewData)->where($where)->save();
            $this->service('PropertyCapital')->pushContractCapital($contactId,$contactNewData['title'],$contactNewData['currency_identity']);
		}
	}
	
	/**
	 *
	 * 新货币合约
	 *
	 * @param $contactNewData 货币合约数据
	 * 合约，资产主体，结算账户
	 * @reutrn int;
	 */
	public function insert($contactNewData){
		
		$contactNewData['subscriber_identity'] =$this->session('uid');
		$contactNewData['dateline'] = $this->getTime();
		$contactNewData['sn'] = $this->get_sn();
			
		$contactNewData['lastupdate'] = $contactNewData['dateline'];
		$contactId =  $this->model('ForeignContact')->data($contactNewData)->add();
		if($contactId){
            $this->service('PropertyCapital')->pushContractCapital($contactId,$contactNewData['title'],$contactNewData['currency_identity']);
        }
		return $contactId;
	}
}