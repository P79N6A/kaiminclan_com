<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class FabricationJoggleService extends Service {
	
	
	/**
	 *
	 * 反馈信息
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 反馈列表;
	 */
	public function getJoggleList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('FabricationJoggle')->where($where)->count();
		if($count){
			$selectHandle = $this->model('FabricationJoggle')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();	
			
			$attachmentIds = $subscriberIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FabricationJoggleModel::getStatusTitle($data['status'])
				);
				$subscriberIds[] = $data['subscriber_identity'];
				$subjectIds[] = $data['subject_identity'];
				$applicationIds[] = $data['application_identity'];
				$functionalIds[] = $data['functional_identity'];
				$subscriberIds[] = $data['subscriber_identity'];
				$attachmentIds[] = $data['finally_attach_id'];
			}
			
			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($subjectIds);
			
			$subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['platform'] = isset($platformData[$data['platform_identity']])?$platformData[$data['platform_identity']]:array();
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
				$listdata[$key]['subscriber'] = isset($accountData[$data['subscriber_identity']])?$accountData[$data['subscriber_identity']]:array();
				$listdata[$key]['logic'] = isset($attachPathData[$data['logic_attach_id']])?$attachPathData[$data['logic_attach_id']]:array();
				$listdata[$key]['finally'] = isset($attachPathData[$data['finally_attach_id']])?$attachPathData[$data['finally_attach_id']]:array();
			}
			
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $joggleId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function getJoggleInfo($joggleId){
		
		$joggleData = array();
		
		$where = array(
			'identity'=>$joggleId
		);
		
		$joggleList = $this->model('FabricationJoggle')->where($where)->select();
		if($joggleList){
			$joggleData = $joggleList;
			if(!is_array($joggleId)){
				$joggleData = current($joggleList);
			}
		}
		
		
		return $joggleData;
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $joggleId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function checkJoggleTitle($title){
		
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('FabricationJoggle')->where($where)->count();
	}
	
	/**
	 *
	 * 删除反馈
	 *
	 * @param $joggleId 反馈ID
	 *
	 * @reutrn int;
	 */
	public function removeJoggleId($joggleId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$joggleId
		);
		
		$joggleData = $this->model('FabricationJoggle')->where($where)->select();
		if($joggleData){
			$output = $this->model('FabricationJoggle')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 反馈修改
	 *
	 * @param $joggleId 反馈ID
	 * @param $joggleNewData 反馈数据
	 *
	 * @reutrn int;
	 */
	public function update($joggleNewData,$joggleId){
		$where = array(
			'identity'=>$joggleId
		);
		
		$joggleData = $this->model('FabricationJoggle')->where($where)->find();
		if($joggleData){
			
			$joggleNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FabricationJoggle')->data($joggleNewData)->where($where)->save();
			
		}
		return $result;
	}
	
	public function getCode($content){
		return md5($content.$this->getClientIp().$this->getDeviceCode());
	}
	
	/**
	 *
	 * 检测消息码是否存在
	 *
	 * @param $code 识别码
	 *
	 * @reutrn int;
	 */
	public function checkCode($code){
		$where = array();
		$where['code'] = $code;
		return $this->model('FabricationJoggle')->where($where)->count();
	}
	
	/**
	 *
	 * 新反馈
	 *
	 * @param $joggleNewData 反馈信息
	 *
	 * @reutrn int;
	 */
	public function insert($joggleNewData){
		$joggleNewData['subscriber_identity'] =$this->session('uid');		
		$joggleNewData['dateline'] = $this->getTime();
			
		$joggleNewData['lastupdate'] = $joggleNewData['dateline'];
		
		$joggleNewData['sn'] = date('Ymd').'-'.mt_rand(1,1000);
		
		$joggleId = $this->model('FabricationJoggle')->data($joggleNewData)->add();
		
		return $joggleId;
		
	}
}