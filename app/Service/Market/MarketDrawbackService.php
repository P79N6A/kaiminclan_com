<?php
/**
 *
 * 售后
 *
 * 销售
 *
 */
class  MarketDrawbackService extends Service {
		
	/**
	 *
	 * 获取售后流水号
	 *
	 *
	 * @reutrn array;
	 */
	public function getDrawbackCode(){
		
		$where = array();
		
		$where['dateline'] = array('GT',strtotime(date('Y-m-d',strtotime('-1 day')))+(60*60*16-1));
		$count = $this->model('MarketDrawback')->where()->count();
		
		return date('Ymd').sprintf('%06s', $count+1);
	}
	/**
	 *
	 * 售后信息
	 *
	 * @param $field 售后字段
	 * @param $status 售后状态
	 *
	 * @reutrn array;
	 */
	public function getDrawbackList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('MarketDrawback')->where($where)->count();
		if($count){
			$orderddHandle = $this->model('MarketDrawback')->where($where)->orderby($orderby);
			if($start && $perpage){
				$orderddHandle = $orderddHandle->limit($start,$perpage,$count);
			}
			$listdata = $orderddHandle->select();
			
			$attachIds = $examineIds = $subscriberIds = $shoppingIds = array();
			foreach($listdata as $key=>$data){
				if($data['attachment_identity']){
					$attachIds = array_merge($attachIds,strpos($data['attachment_identity'],',') !== false?explode(',',$data['attachment_identity']):$data['attachment_identity']);
				}
				$listdata[$key]['status'] = array(
					'label'=>MarketDrawbackModel::getStatusTitle($data['status']),
					'value'=>$data['value']
				);
				$shoppingIds[] = $data['shopping_identity'];
				$subscriberIds[] = $data['subscriber_identity'];
				$subscriberIds[] = $data['handle_subscriber_identity'];
			}
			
			$subscriberData = $this->service('AuthoritySubscriber')->getSubscriberInfobyIds($subscriberIds);
			$shoppingData = $this->service('MarketShopping')->getShoppingDetail($shoppingIds);
			$attachData = $this->service('ResourcesAttachment')->getAttachUrl($attachIds);
			
			foreach($listdata as $key=>$data){
				$attachList = $attachIds = array();
				if(strpos($data['attachment_identity'],',') !== false){
					$attachIds = array_filter(explode(',',$data['attachment_identity']));
				}else{
					$attachIds[] = $data['attachment_identity'];
				}
				
				if(!empty($attachIds)){
					foreach($attachIds as $cnt=>$attachId){
						$attachList[] = array('identity'=>$attachId,'attach'=>$attachData[$attachId]);	
					}
				}
				
				$listdata[$key]['attach'] = $attachList;
				
				$listdata[$key]['subscriber'] =$subscriberData[$data['subscriber_identity']];
				$listdata[$key]['good'] =$shoppingData[$data['shopping_identity']]['good'];
				if($data['handle_subscriber_identity']){
					$listdata[$key]['handle_subscriber'] =$subscriberData[$data['handle_subscriber_identity']];
				}
			}
			
			
					
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 售后信息
	 *
	 * @param $drawbackId 售后ID
	 *
	 * @reutrn array;
	 */
	public function getdrawbackInfo($drawbackId){
		
		$drawbackData = array();
		
		$where = array(
			'identity'=>$drawbackId
		);
		
		$drawbackList = $this->model('MarketDrawback')->where($where)->select();
		if($drawbackList){
			
		}
		
		if(!is_array($drawbackId)){
			$drawbackData = current($drawbackData);
		}
		
		return $drawbackData;
	}
	/**
	 *
	 * 检测申请状态
	 *
	 * @param $orderddId 订单ID
	 * @param $shoppingId 订购ID
	 *
	 * @reutrn int;
	 */
	public function checkDrawback($orderddId,$shoppingId = 0){
		$where = array(
			'orderdd_identity'=>$orderddId
		);
		$where['shopping_identity'] = $shoppingId;
		
		return $this->model('MarketDrawback')->where($where)->find();
		
	}
	
	/**
	 *
	 * 删除售后
	 *
	 * @param $drawbackId 售后ID
	 *
	 * @reutrn int;
	 */
	public function removedrawbackId($drawbackId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$drawbackId
		);
		
		$drawbackData = $this->model('MarketDrawback')->where($where)->select();
		if($drawbackData){
			$groupingIds = array();
			foreach($drawbackData as $key=>$drawback){
				$groupingIds[] = $drawback['group_identity'];
			}
						
			$output = $this->model('MarketDrawback')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 售后修改
	 *
	 * @param $drawbackId 售后ID
	 * @param $drawbackNewData 售后数据
	 *
	 * @reutrn int;
	 */
	public function update($drawbackNewData,$drawbackId){
		$where = array(
			'identity'=>$drawbackId
		);
		
		$drawbackData = $this->model('MarketDrawback')->where($where)->find();
		if($drawbackData){
			
			$drawbackNewData['lastupdate'] = $this->getTime();
			$result = $this->model('MarketDrawback')->data($drawbackNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新售后
	 *
	 * @param $drawbackNewData 售后信息
	 *
	 * @reutrn int;
	 */
	public function insert($drawbackNewData){
		$drawbackNewData['subscriber_identity'] =$this->session('uid');		
		$drawbackNewData['dateline'] = $this->getTime();
			
		$drawbackNewData['lastupdate'] = $drawbackNewData['dateline'];
		$drawbackId = $this->model('MarketDrawback')->data($drawbackNewData)->add();
		return $drawbackId;
		
	}
}