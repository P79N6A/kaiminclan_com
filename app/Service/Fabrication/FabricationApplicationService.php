<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class FabricationApplicationService extends Service {
	
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
	public function getApplicationList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('FabricationApplication')->where($where)->count();
		if($count){
			$selectHandle = $this->model('FabricationApplication')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();	
			
			$liabilitySubscriberIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FabricationApplicationModel::getStatusTitle($data['status'])
				);
				$subjectIds[] = $data['subject_identity'];
				$liabilitySubscriberIds[] = $data['liability_subscriber_identity'];
				$attachmentIds[] = $data['attachment_identity'];
			}

			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($subjectIds);
			
			$subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['liability'] = isset($accountData[$data['liability_subscriber_identity']])?$accountData[$data['liability_subscriber_identity']]:array();
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
				$listdata[$key]['attach'] = isset($attachPathData[$data['attachment_identity']])?$attachPathData[$data['attachment_identity']]:array();
			}
			
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $channelId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function getApplicationInfo($channelId){
		
		$channelData = array();
		
		$where = array(
			'identity'=>$channelId
		);
		
		$channelList = $this->model('FabricationApplication')->where($where)->select();
		if($channelList){
		}
		
		if(!is_array($channelId)){
			$channelData = current($channelList);
		}
		
		return $channelData;
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $channelId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function checkApplicationTitle($title){
		
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('FabricationApplication')->where($where)->count();
	}
	
	/**
	 *
	 * 删除反馈
	 *
	 * @param $channelId 反馈ID
	 *
	 * @reutrn int;
	 */
	public function removeApplicationId($channelId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$channelId
		);
		
		$channelData = $this->model('FabricationApplication')->where($where)->select();
		if($channelData){
			$output = $this->model('FabricationApplication')->where($where)->delete();
		}
		
		return $output;
	}
	
	
	public function newApp($appName,$subjectId){
		$subjectId = $this->getInt($subjectId);
		if(!$subjectId){
			return -1;
		}
		if(empty($appName)){
			return -2;
		}
		
		
		$appData = array(
			'title'=>$appName,
			'subject_identity'=>$subjectId
		);
		
		$count = $this->model('FabricationApplication')->where($appData)->count();
		if($count){
			return -3;
		}
		
		return $this->insert($appData);
		
	}
	
	public function insert($applicationData){
		
		$applicationData['sn'] = $this->get_sn();
		$applicationData['subscriber_identity'] = $this->getUID();
		$applicationData['dateline'] = $this->getTime();
		$applicationData['lastupdate'] = $applicationData['dateline'];
		
		return $this->model('FabricationApplication')->data($applicationData)->add();
	}

	public function saveJson(){
		
		$settingFile = __DATA__.'/json/global/program.json';
		
		$menuData = array();
		
		$where = array();
		$where['status'] = FabricationApplicationModel::FABRICATION_APPLICATION_STATUS_FINISH;
		$appList = $this->model('FabricationApplication')->field('identity,title')->where($where)->select();
		if($appList){
			$where = array();
			$where['status'] = FabricationFunctionalModel::FABRICATION_FUNCTIONAL_STATUS_FINISH;
			$funcList = $this->model('FabricationFunctional')->field('identity,title,application_identity')->where($where)->select();
			$programList = array();
			foreach($appList as $key=>$data){		
						$data['title'] = trim($data['title']);		
				foreach($funcList as $cnt=>$func){
					if($func['application_identity'] == $data['identity']){
						$func['title'] = trim($func['title']);
						$data['s'][] = $func;
					}
				}
				$programList[] = $data;
			}	
		}
		
		$folder = dirname($settingFile);
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		
		file_put_contents($settingFile,json_encode($programList,JSON_UNESCAPED_UNICODE));		
		
	}
	
}