<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class BolsterSubscriberService extends Service {
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getSubscriberList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('BolsterSubscriber')->where($where)->count();
		if($count){
			$handle = $this->model('BolsterSubscriber')->where($where);
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
					'label'=>BolsterSubscriberModel::getStatusTitle($data['status'])
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
	public function checkSubscriberTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('BolsterSubscriber')->where($where)->count();
		}
		return 0;
	}
	
	public function getSubscriberBySymbol($symbol){
		$where = array(
			'code'=>strtolower($symbol)
		);
		return $this->model('BolsterSubscriber')->field('identity')->where($where)->find();
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $subscriberId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSubscriberInfo($subscriberId,$field = '*'){
		
		$where = array(
			'identity'=>$subscriberId
		);
		
		$subscriberData = $this->model('BolsterSubscriber')->field($field)->where($where)->select();
		
		return $subscriberData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $subscriberId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeSubscriberId($subscriberId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$subscriberId
		);
		
		$subscriberData = $this->model('BolsterSubscriber')->where($where)->find();
		if($subscriberData){
			
			$output = $this->model('BolsterSubscriber')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $subscriberId 模块ID
	 * @param $subscriberNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($subscriberNewData,$subscriberId){
		$where = array(
			'identity'=>$subscriberId
		);
		
		$subscriberData = $this->model('BolsterSubscriber')->where($where)->find();
		if($subscriberData){
			
			$subscriberNewData['lastupdate'] = $this->getTime();
			$this->model('BolsterSubscriber')->data($subscriberNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $subscriberNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($subscriberNewData){
		
		$subscriberNewData['subscriber_identity'] =$this->session('uid');
		$subscriberNewData['dateline'] = $this->getTime();
		$subscriberNewData['sn'] = $this->get_sn();
			
		$subscriberNewData['lastupdate'] = $subscriberNewData['dateline'];
		return $this->model('BolsterSubscriber')->data($subscriberNewData)->add();
	}
	
}