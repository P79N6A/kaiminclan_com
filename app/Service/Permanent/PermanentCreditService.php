<?php
/**
 *
 * 授信
 *
 * 融资
 *
 */
class PermanentCreditService extends Service
{
	public function adjustAmount($creditId,$amount,$indebtednessData = array()){
		
		if($creditId < 1){
			return -1;
		}
		
		if(strcmp($amount,0) === 0){
			return -2;
		}
		
		$where = array(
			'identity'=>$creditId
		);
		
		$creditData = $this->model('PermanentCredit')->where($where)->find();
		if(!$creditData){
			return -3;
		}
		
		if($amount < 0 ){
			//借
			$creditNewData = array(
				'available_amount'=>$creditData['available_amount']-$amount,
				'frozen_amount'=>$creditData['frozen_amount']+$amount,
				'lastupdate'=>$this->getTime()
			);
		}else{
			//还
			$creditNewData = array(
				'available_amount'=>$creditData['available_amount']+$amount,
				'frozen_amount'=>$creditData['frozen_amount']-$amount,
				'lastupdate'=>$this->getTime()
			);
		}
		if($indebtednessData){
			//是否产生债务记录
			list($title,$content,$amount,$currencyId,$subjectId) = $indebtednessData;
			$this->service('PermanentIndebtedness')->push($creditId,$title,$content,$amount,$currencyId,$subjectId);
		}
		
		$this->model('PermanentCredit')->data($creditNewData)->where($where)->save();
	}
	
	/**
	 *
	 * 授信信息
	 *
	 * @param $field 授信字段
	 * @param $status 授信状态
	 *
	 * @reutrn array;
	 */
	public function getCreditList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('PermanentCredit')->where($where)->count();
		if($count){
			$handle = $this->model('PermanentCredit')->where($where);
			if($start > 0 && $perpage > 0){
				$handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle->select();
			
			$channelIds = array();
			foreach($listdata as $key=>$data){
				$channelIds[] = $data['channel_identity'];
				$listdata[$key]['style'] = array(
					'value'=>$data['style'],
					'label'=>PermanentCreditModel::getStyleTitle($data['style'])
				);
			}
			
			$channelData  = $this->service('PermanentFashion')->getFashionInfo($channelIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['channel'] = isset($channelData[$data['channel_identity']])?$channelData[$data['channel_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测授信单位
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkCreditTitle($title){
		if($title){
				$where = array(
					'title'=>$title
				);
			return $this->model('PermanentCredit')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 授信信息
	 *
	 * @param $creditId 授信ID
	 *
	 * @reutrn array;
	 */
	public function getCreditInfo($creditId,$field = '*'){
		
		$where = array(
			'identity'=>$creditId
		);
		
		$creditData = $this->model('PermanentCredit')->field($field)->where($where)->select();
		
		return $creditData;
	}
	
	/**
	 *
	 * 删除授信
	 *
	 * @param $creditId 授信ID
	 *
	 * @reutrn int;
	 */
	public function removeCreditId($creditId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$creditId
		);
		
		$creditData = $this->model('PermanentCredit')->where($where)->find();
		if($creditData){
			
			$output = $this->model('PermanentCredit')->where($where)->delete();
			
			$this->service('PaginationItem')->removeCreditIdAllItem($creditId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 授信修改
	 *
	 * @param $creditId 授信ID
	 * @param $creditNewData 授信数据
	 *
	 * @reutrn int;
	 */
	public function update($creditNewData,$creditId){
		$where = array(
			'identity'=>$creditId
		);
		
		$creditData = $this->model('PermanentCredit')->where($where)->find();
		if($creditData){
			
			$creditNewData['lastupdate'] = $this->getTime();
			$this->model('PermanentCredit')->data($creditNewData)->where($where)->save();
			if($creditNewData['channel_identity'] != $creditData['channel_identity']){
				
				$this->service('PermanentFashion')->adjustCredit($creditNewData['channel_identity'],1);
				$this->service('PermanentFashion')->adjustCredit($creditData['channel_identity'],-1);
			}
            $this->service('MechanismAccount')->newCreditAccount($creditId,$creditNewData['title'],$creditNewData['amount'],$creditNewData['bank_identity']);
		}
	}
	
	/**
	 *
	 * 新授信
	 *
	 * @param $creditNewData 授信数据
	 *
	 * @reutrn int;
	 */
	public function insert($creditNewData){
		
		$creditNewData['subscriber_identity'] =$this->session('uid');
		$creditNewData['dateline'] = $this->getTime();
		$creditNewData['sn'] = $this->get_sn();
		
		$creditNewData['available_amount'] = $creditNewData['amount'];
		$creditNewData['frozen_amount'] = $creditNewData['amount'];
			
		$creditNewData['lastupdate'] = $creditNewData['dateline'];
		$this->service('PermanentFashion')->adjustCredit($creditNewData['channel_identity'],1);
		$creditId = $this->model('PermanentCredit')->data($creditNewData)->add();
		if($creditId){
		    $this->service('MechanismAccount')->newCreditAccount($creditId,$creditNewData['title'],$creditNewData['amount'],$creditNewData['bank_identity']);
        }
		return $creditId;
	}
}