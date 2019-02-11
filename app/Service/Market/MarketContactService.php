<?php
/**
 *
 * 地址
 *
 * 销售
 *
 */
class  MarketContactService extends Service {
	
	
	/**
	 *
	 * 地址信息
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getAllContactList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$contactList = array();
		$count = $this->model('MarketContact')->where($where)->count();
		if($count){
			$shoppingHandle = $this->model('MarketContact')->where($where)->orderby($orderby);
			if($start && $perpage){
				$shoppingHandle = $shoppingHandle->limit($start,$perpage,$count);
			}
			$listdata = $shoppingHandle->select();
			
			$hospitalIds = $districtIds = array();
			foreach($listdata as $key=>$data){
				$districtIds[] = $data['district_identity'];
				$hospitalIds[] = $data['hospital_identity'];
			}
			
			$districtData = $this->service('FoundationDistrict')->getDistrictInfo($districtIds);
			$hospitalData = $this->service('KnowledgeHospital')->getHospitalInfo($hospitalIds);
			
			foreach($listdata as $key=>$data){
				$data['district'] = isset($districtData[$data['district_identity']])?$districtData[$data['district_identity']]:array();
				$data['hospital'] = isset($districtData[$data['hospital_identity']])?$districtData[$data['hospital_identity']]:array();
				
				$data['full_addr'] = $districtData[$data['district_identity']]['title'].$data['address'];
				$contactList[$data['identity']] = $data;
			}
			
		}
		return array('count'=>$count,'list'=>$contactList);
	}
	
	/**
	 *
	 * 地址信息
	 *
	 * @param $contactId 地址ID
	 *
	 * @reutrn array;
	 */
	public function getContactInfo($contactId){
		
		$contactData = array();
		
		$where = array(
			'identity'=>$contactId
		);
		
		
		$listdata = $this->getAllContactList($where);
		if($listdata['count']){
			$contactData = $listdata['list'];
		}
		
		if(!is_array($contactId)){
			$contactData = current($contactData);
		}
		
		return $contactData;
	}
	/**
	 *
	 * 检测电话号码
	 *
	 * @param $telephone 电话号码
	 *
	 * @reutrn int;
	 */
	public function checkTelephone($telephone){
		if($telephone){
			$where = array(
				'telephone'=>$telephone,
				'subscriber_identity'=>$this->session('uid')
			);
			return $this->model('MarketContact')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除地址
	 *
	 * @param $contactId 地址ID
	 *
	 * @reutrn int;
	 */
	public function removeContactId($contactId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$contactId
		);
		
		$contactData = $this->model('MarketContact')->where($where)->select();
		if($contactData){
			$output = $this->model('MarketContact')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 取消默认地址
	 *
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function cannelDefaultContactByUid($uid){
		$where = array(
			'subscriber_identity'=>$uid
		);
		$contactNewData = array(
			'secleted'=>MarketContactModel::MARKET_CONTACT_SELECTED_NO
		);
		$contactNewData['lastupdate'] = $this->getTime();
		$result = $this->model('MarketContact')->data($contactNewData)->where($where)->save();
			
	}
	
	/**
	 *
	 * 地址修改
	 *
	 * @param $contactId 地址ID
	 * @param $contactNewData 地址数据
	 *
	 * @reutrn int;
	 */
	public function update($contactNewData,$contactId){
		$where = array(
			'identity'=>$contactId
		);
		
		$contactData = $this->model('MarketContact')->where($where)->find();
		if($contactData){
			
			
			if($contactNewData['secleted']){
				$this->cannelDefaultContactByUid($contactNewData['subscriber_identity']);
			}
				
			$contactNewData['lastupdate'] = $this->getTime();
			$result = $this->model('MarketContact')->data($contactNewData)->where($where)->save();
			if($result){
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新地址
	 *
	 * @param $contactNewData 地址信息
	 *
	 * @reutrn int;
	 */
	public function insert($contactNewData){
		$contactNewData['subscriber_identity'] =$this->session('uid');		
		$contactNewData['dateline'] = $this->getTime();
			
		if($contactNewData['secleted']){
			$this->cannelDefaultContactByUid($contactNewData['subscriber_identity']);
		}
		$contactNewData['lastupdate'] = $contactNewData['dateline'];
		$contactId = $this->model('MarketContact')->data($contactNewData)->add();
		
		
		
	}
}