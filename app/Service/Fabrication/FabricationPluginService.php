<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class FabricationPluginService extends Service {
	
	
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
	public function getPluginList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('FabricationPlugin')->where($where)->count();
		if($count){
			$selectHandle = $this->model('FabricationPlugin')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();	
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FabricationPluginModel::getStatusTitle($data['status'])
				);
				$subjectIds[] = $data['subject_identity'];
			}
			
			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($subjectIds);
			
			$subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
			}
			
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $pluginId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function getPluginInfo($pluginId){
		
		$pluginData = array();
		
		$where = array(
			'identity'=>$pluginId
		);
		
		$pluginList = $this->model('FabricationPlugin')->where($where)->select();
		if($pluginList){
			if(!is_array($pluginId)){
				$pluginData = current($pluginList);
			}else{
				$pluginData = $pluginList;
			}
		}
		
		
		return $pluginData;
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $pluginId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function checkPluginTitle($title){
		
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('FabricationPlugin')->where($where)->count();
	}
	
	/**
	 *
	 * 删除反馈
	 *
	 * @param $pluginId 反馈ID
	 *
	 * @reutrn int;
	 */
	public function removePluginId($pluginId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$pluginId
		);
		
		$pluginData = $this->model('FabricationPlugin')->where($where)->select();
		if($pluginData){
			$output = $this->model('FabricationPlugin')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 反馈修改
	 *
	 * @param $pluginId 反馈ID
	 * @param $pluginNewData 反馈数据
	 *
	 * @reutrn int;
	 */
	public function update($pluginNewData,$pluginId){
		$where = array(
			'identity'=>$pluginId
		);
		
		$pluginData = $this->model('FabricationPlugin')->where($where)->find();
		if($pluginData){
			
			$pluginNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FabricationPlugin')->data($pluginNewData)->where($where)->save();
			
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
		return $this->model('FabricationPlugin')->where($where)->count();
	}
	
	/**
	 *
	 * 新反馈
	 *
	 * @param $pluginNewData 反馈信息
	 *
	 * @reutrn int;
	 */
	public function insert($pluginNewData){
		$pluginNewData['subscriber_identity'] =$this->session('uid');		
		$pluginNewData['dateline'] = $this->getTime();
			
		$pluginNewData['lastupdate'] = $pluginNewData['dateline'];
		
		$pluginNewData['sn'] = date('Ymd').'-'.mt_rand(1,1000);
		
		$pluginId = $this->model('FabricationPlugin')->data($pluginNewData)->add();
		
		return $pluginId;
		
	}
	
}