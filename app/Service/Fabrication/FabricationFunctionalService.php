<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class FabricationFunctionalService extends Service {
	
	
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
	public function getFunctionalList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('FabricationFunctional')->where($where)->count();
		if($count){
			$selectHandle = $this->model('FabricationFunctional')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();	
			$attachmentIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FabricationFunctionalModel::getStatusTitle($data['status'])
				);
				$subscriberIds[] = $data['subscriber_identity'];
				$subjectIds[] = $data['subject_identity'];
				$attachmentIds[] = $data['attachment_identity'];
			}

			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($subjectIds);
			
			$subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
				$listdata[$key]['subscriber'] = isset($accountData[$data['subscriber_identity']])?$accountData[$data['subscriber_identity']]:array();
				$listdata[$key]['attach'] = isset($attachPathData[$data['attachment_identity']])?$attachPathData[$data['attachment_identity']]:array();
			}
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $functionalId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function getFunctionalInfo($functionalId){
		
		$functionalData = array();
		
		$where = array(
			'identity'=>$functionalId
		);
		
		$functionalList = $this->model('FabricationFunctional')->where($where)->select();
		if($functionalList){
		}
		
		if(!is_array($functionalId)){
			$functionalData = current($functionalList);
		}
		
		return $functionalData;
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $functionalId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function checkFunctionalTitle($title){
		
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('FabricationFunctional')->where($where)->count();
	}
	
	/**
	 *
	 * 删除反馈
	 *
	 * @param $functionalId 反馈ID
	 *
	 * @reutrn int;
	 */
	public function removeFunctionalId($functionalId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$functionalId
		);
		
		$functionalData = $this->model('FabricationFunctional')->where($where)->select();
		if($functionalData){
			$output = $this->model('FabricationFunctional')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 反馈修改
	 *
	 * @param $functionalId 反馈ID
	 * @param $functionalNewData 反馈数据
	 *
	 * @reutrn int;
	 */
	public function update($functionalNewData,$functionalId){
		$where = array(
			'identity'=>$functionalId
		);
		
		$functionalData = $this->model('FabricationFunctional')->where($where)->find();
		if($functionalData){
			
			$functionalNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FabricationFunctional')->data($functionalNewData)->where($where)->save();
			
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
		return $this->model('FabricationFunctional')->where($where)->count();
	}
	
	/**
	 *
	 * 新反馈
	 *
	 * @param $functionalNewData 反馈信息
	 *
	 * @reutrn int;
	 */
	public function insert($functionalNewData){
		$functionalNewData['subscriber_identity'] =$this->session('uid');		
		$functionalNewData['dateline'] = $this->getTime();
			
		$functionalNewData['lastupdate'] = $functionalNewData['dateline'];
		
		$functionalNewData['sn'] = date('Ymd').'-'.mt_rand(1,1000);
		
		$functionalId = $this->model('FabricationFunctional')->data($functionalNewData)->add();
		
		return $functionalId;
		
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