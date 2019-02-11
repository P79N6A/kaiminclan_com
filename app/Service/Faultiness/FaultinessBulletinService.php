<?php

class FaultinessBulletinService extends Service {
	
	
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
	public function getBulletinList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('FaultinessBulletin')->where($where)->count();
		if($count){
			$selectHandle = $this->model('FaultinessBulletin')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();	
			
			$liabilitySubscriberIdentity = $subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FaultinessBulletinModel::getStatusTitle($data['status'])
				);
				$subjectIds[] = $data['subject_identity'];
				$platformIds[] = $data['platform_identity'];
				$liabilitySubscriberIdentity[] = $data['liability_subscriber_identity'];
				$liabilitySubscriberIdentity[] = $data['subscriber_identity'];
			}
			
			$platformData = $this->service('ProductionPlatform')->getPlatformInfo($platformIds);
			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($subjectIds);
			
			$subjectIds = $platformIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['platform'] = isset($platformData[$data['platform_identity']])?$platformData[$data['platform_identity']]:array();
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
				$listdata[$key]['subscriber'] = isset($subscriberData[$data['subscriber_identity']])?$subscriberData[$data['subscriber_identity']]:array();
				$listdata[$key]['liability'] = isset($subscriberData[$data['liability_subscriber_identity']])?$subscriberData[$data['liability_subscriber_identity']]:array();
			}
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $bulletinId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function getBulletinInfo($bulletinId){
		
		$bulletinData = array();
		
		$where = array(
			'identity'=>$bulletinId
		);
		
		$bulletinList = $this->model('FaultinessBulletin')->where($where)->select();
		if($bulletinList){
			if(!is_array($bulletinId)){
				$bulletinData = current($bulletinList);
			}else{
				$bulletinData = $bulletinList;
			}
		
		}
		
		return $bulletinData;
	}
	
	/**
	 *
	 * 反馈信息
	 *
	 * @param $bulletinId 反馈ID
	 *
	 * @reutrn array;
	 */
	public function checkBulletinTitle($title){
		
		
		$where = array(
			'title'=>$title
		);
		
		return $this->model('FaultinessBulletin')->where($where)->count();
	}
	
	/**
	 *
	 * 删除反馈
	 *
	 * @param $bulletinId 反馈ID
	 *
	 * @reutrn int;
	 */
	public function removeBulletinId($bulletinId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$bulletinId
		);
		
		$bulletinData = $this->model('FaultinessBulletin')->where($where)->select();
		if($bulletinData){
			$output = $this->model('FaultinessBulletin')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 反馈修改
	 *
	 * @param $bulletinId 反馈ID
	 * @param $bulletinNewData 反馈数据
	 *
	 * @reutrn int;
	 */
	public function update($bulletinNewData,$bulletinId){
		$where = array(
			'identity'=>$bulletinId
		);
		
		$bulletinData = $this->model('FaultinessBulletin')->where($where)->find();
		if($bulletinData){
			
			$bulletinNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FaultinessBulletin')->data($bulletinNewData)->where($where)->save();
			
			if($bulletinNewData['status'] == FaultinessBulletinModel::PRODUCTION_DEMAND_STATUS_DEVELOP){
				$bulletinNewData['identity'] = $bulletinId;
				$this->send($bulletinNewData);
			}
			
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
		return $this->model('FaultinessBulletin')->where($where)->count();
	}
	
	public function send($bulletinData){
		
		$liabilitySubscriberIdentity = 0;
		
		if($bulletinData['subject_identity']){
			//查找平台
			$where = array();
			$where['identity'] = $bulletinData['subject_identity'];
			$platformData = $this->model('ProjectSubject')->field('liability_subscriber_identity')->where($where)->find();
			if($platformData){
				$liabilitySubscriberIdentity = $platformData['liability_subscriber_identity'];
			}
		}
		if($bulletinData['platform_identity']){
			//查找平台
			$where = array();
			$where['identity'] = $bulletinData['platform_identity'];
			$platformData = $this->model('ProductionPlatform')->field('liability_subscriber_identity')->where($where)->find();
			if($platformData){
				$liabilitySubscriberIdentity = $platformData['liability_subscriber_identity'];
			}
		}
		if($liabilitySubscriberIdentity < 1){
			//未指定责任人
			
			return -1;	
		}
				
				
		$where = array();
		$where['identity'] = $this->service('Account')->getRealTypeId($liabilitySubscriberIdentity,'employee');
		$memberData = $this->model('ProjectMember')->where($where)->find();
				
		$href = 'http://'.__HOST__.'.'.__SITE_URL__.'//Production/BulletinDossier/bulletinId/'.$bulletinData['identity'];
		
		$content .= $memberData['fullname'].'：<br /><br /><br />';

		$content .= '以下任务需要你及时处理。请点击以下链接查看任务详情：<br /><br />';
		
		$content .= '(please click on the following link to view the details of the task:)<br /><br />';
		
		$content .= '<a href="'.$href.'" target="_blank">'.$href.'</a><br /><br />';
		
		$content .= '如果你的email程序不支持链接点击，请将上面的地址拷贝至你的浏览器(例如IE)的地址栏进入。<br /><br />';
	
		
		$content .= '(这是一封自动产生的email，请勿回复。)<br />';
		
		
		$toEmail = $memberData['email'];
		$result = $this->service('MessengerMessage')->sendEmail($toEmail,'需求处理['.$bulletinData['title'].']',$content);
	}
	
	/**
	 *
	 * 新反馈
	 *
	 * @param $bulletinNewData 反馈信息
	 *
	 * @reutrn int;
	 */
	public function insert($bulletinNewData){
		$bulletinNewData['subscriber_identity'] =$this->session('uid');		
		$bulletinNewData['dateline'] = $this->getTime();
			
		$bulletinNewData['lastupdate'] = $bulletinNewData['dateline'];
		
		$bulletinNewData['sn'] = date('Ymd').'-'.mt_rand(1,1000);
		
		$bulletinId = $this->model('FaultinessBulletin')->data($bulletinNewData)->add();
		if($bulletinId){
			if($bulletinNewData['status'] == FaultinessBulletinModel::FAULTINESS_BULLETIN_STATUS_ENABLE){
				$bulletinNewData['identity'] = $bulletinId;
				$this->send($bulletinNewData);
			}
		}
		return $bulletinId;
	}
}