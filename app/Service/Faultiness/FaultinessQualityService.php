<?php

class FaultinessQualityService extends Service {

	/**
	 *
	 * 质量计划列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 质量计划列表;
	 */
	public function getQualityList($where = array(),$start = 0,$perpage = 0,$order = 'identity desc'){
		
		
		$count = $this->model('FaultinessQuality')->where($where)->count();

		$listdata = array();
		if($count){
			$subscriberHandle  = $this->model('FaultinessQuality')->where($where)->orderby($order);
			
			if($perpage){
				$subscriberHandle->limit($start,$perpage,$count);
			}
			$listdata = $subscriberHandle->select();
			$idtypeData = $subscriberIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'label'=>FaultinessQualityModel::getStatusTitle($data['status']),
					'value'=>$data['status']
				);
				$idtypeData[$data['idtype']][] = $data['id'];
				$listdata[$key]['idtype'] = array(
					'label'=>FaultinessQualityModel::getIdtypeTitle($data['idtype']),
					'value'=>$data['idtype']
				);
				$subjectIds[] = $data['subject_identity'];
				$subscriberIds[] = $data['subscriber_identity'];
				$subscriberIds[] = $data['liability_subscriber_identity'];
			}
			$where =array();
			$where['identity'] = $subjectIds;
			$subjectList = $this->model('ProjectSubject')->field('identity,title')->where($where)->select();
			if($subjectList){
				foreach($subjectList as $key=>$subject){
					foreach($listdata as $cnt=>$member){
						if($member['subject_identity'] !=  $subject['identity']) continue;
						$listdata[$cnt]['subject'] = $subject;
					}
				}
			}
			if($accountList){
				foreach($accountList as $uid=>$account){
					foreach($listdata as $cnt=>$joggle){
						if($joggle['subscriber_identity'] ==  $uid) {
						$listdata[$cnt]['subscriber'] = $account;
						}
						if($joggle['liability_subscriber_identity'] ==  $uid) {
							$listdata[$cnt]['liability'] = $account;
						}
					}
				}
			}
			
			foreach($idtypeData as $idtype=>$ids){
				switch($idtype){
					case FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_PRODUCT:
						$productData = $this->service('ProductionProduct')->getProductInfo($ids);
						foreach($listdata as $key=>$data){
							if($data['idtype']['value'] != $idtype) continue;
							$listdata[$key]['product'] = isset($productData[$data['id']])?$productData[$data['id']]:array();
						}
						break;
					case FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_DEMAND:
						$demandData = $this->service('ProductionDemand')->getDemandInfo($ids);
						foreach($listdata as $key=>$data){
							if($data['idtype']['value'] != $idtype) continue;
							$listdata[$key]['demand'] = isset($demandData[$data['id']])?$demandData[$data['id']]:array();
						}
						break;
					case FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_BULLETIN:
						$bulletinData = $this->service('FaultinessBulletin')->getBulletinInfo($ids);
						foreach($listdata as $key=>$data){
							if($data['idtype']['value'] != $idtype) continue;
							$listdata[$key]['bulletin'] = isset($bulletinData[$data['id']])?$bulletinData[$data['id']]:array();
						}
						break;
					case FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_JOGGLE:
						$joggleData = $this->service('FabricationJoggle')->getJoggleInfo($ids);
						foreach($listdata as $key=>$data){
							if($data['idtype']['value'] != $idtype) continue;
							$listdata[$key]['joggle'] = isset($joggleData[$data['id']])?$joggleData[$data['id']]:array();
						}
						break;
					case FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_FRONTEND:
						$frontendData = $this->service('ProductionFrontend')->getFrontendInfo($ids);
						foreach($listdata as $key=>$data){
							if($data['idtype']['value'] != $idtype) continue;
							$listdata[$key]['frontend'] = isset($frontendData[$data['id']])?$frontendData[$data['id']]:array();
						}
						break;
					case FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_PLATFORM:
						$platformData = $this->service('ProductionPlatform')->getPlatformInfo($ids);
						foreach($listdata as $key=>$data){
							if($data['idtype']['value'] != $idtype) continue;
							$listdata[$key]['platform'] = isset($platformData[$data['id']])?$platformData[$data['id']]:array();
						}
						break;
				}
			}

		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function getQualityData($qualityId){
		
		$qualityData = array();
		
		$qualityId = $this->getInt($qualityId);
		if(empty($qualityId)){
			return $qualityData;
		}
		
		$where = array(
			'identity'=>$qualityId
		);
		
		$listdata =  $this->model('FaultinessQuality')->where($where)->select();
		if($listdata){
			$qualityData = $listdata;
			if(!is_array($qualityId)){
				$qualityData = current($qualityData);
			}
		}
		return $qualityData;
	}
	
	/**
	 *
	 * 质量计划信息
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 质量计划列表;
	 */
	public function getAllQualityList($where = array(),$orderby = 'identity desc',$start = 0,$perpage = 0){
		$qualityData = array();
		
		
		$count = $this->model('FaultinessQuality')->where($where)->count();
		
		if($count){
			
			$qualityHandle = $this->model('FaultinessQuality')->where($where);
			if($start && $perpage){
				$qualityHandle->limit($start,$perpage,$count);
			}
			$qualityList = $qualityHandle->select();
			
			$attachIds = $groupingIds = array();
			foreach($qualityList as $key=>$quality){
				$groupingIds[] = $quality['grouping_identity'];
				$attachId = $quality['attachment_identity'];
				if(strpos($attachId,'.') !== false){
					$attachId = explode(',',$attachId);
				}
				if(is_array($attachId)){
					$attachIds[] = array_merge($attachIds,$attachId);
				}else{
					$attachIds[] = $attachId;
				}
			}
			
			$attachData = $this->service('ResourcesAttachment')->getAttachUrl($attachIds);
			$groupingData = $this->service('FaultinessQuality')->getGroupInfo($quality['group_identity'],'identity,title');
			
			foreach($qualityList as $key=>$quality){
				
				$groupingId = $quality['grouping_identity'];
				
				if(isset($groupingData[$groupingId])){
					$qualityList[$key]['grouping'] = $groupingData[$groupingId];
				}else{
					$qualityList[$key]['grouping'] = array();
				}
				unset($qualityList[$key]['grouping_identity']);
				
				$attachIds = $quality['attachment_identity'];
				if(strpos($attachIds,'.') !== false){
					$attachIds = explode(',',$attachIds);
				}else{
					$attachIds = array($attachIds);
				}
				
				$qualityAttach = array();
				foreach($attachIds as $key=>$aid){
					if(isset($attachData[$aid])){
						$qualityAttach[] = $attachData;
					}
				}
				$qualityList[$key]['attach'] = $qualityAttach;
				
				unset($qualityList[$key]['attachment_identity']);
				
				foreach($qualityList as $key=>$quality){
					$qualityList[$quality['identity']] = $quality;
				}
					
			}
			
			$qualityData = $qualityList;
			
		}
		return array('total'=>$count,'list'=>$qualityData);
	}
	
	/**
	 *
	 * 质量计划信息
	 *
	 * @param $qualityId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getQualityInfo($qualityId){
		
		$qualityData = array();
		
		if(!is_array($qualityId)){
			$qualityId = array($qualityId);
		}
		$qualityId = array_filter(array_filter($qualityId));
		if(empty($qualityId)){
			return array();
		}
		
		$where = array(
			'identity'=>$qualityId
		);
		
		$qualityList = $this->model('FaultinessQuality')->where($where)->select();
		
		if(!is_array($qualityId)){
			$qualityData = current($qualityData);
		}
		
		return $qualityData;
	}
	
	/**
	 *
	 * 质量计划基本信息
	 *
	 * @param $qualityId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getQualityIdByKw($keyword){
		
		$storeId = array();
		
		$where = array(
			'title'=>array('like','%'.$keyword.'%')
		);
		
		$listdata = $this->model('FaultinessQuality')->field('identity')->where($where)->select();
		if($listdata){
			foreach($listdata as $key=>$quality){
				$storeId[] = $quality['identity'];
			}
		}
		
		
		return $storeId;
	}
	
	/**
	 *
	 * 质量计划基本信息
	 *
	 * @param $qualityId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getQualityBaseInfo($qualityId){
		
		$qualityData = array();
		
		$where = array(
			'identity'=>$qualityId
		);
		
		$qualityList = $this->model('FaultinessQuality')->where($where)->select();
		if($qualityList){
			if(!is_array($qualityId)){
				$qualityData = current($qualityList);
			}
		}
		
		
		return $qualityData;
	}
	
	/**
	 *
	 * 根据质量计划名称查找质量计划ID
	 *
	 * @param $qualityId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getQualityIdsLikeName($storeName){
		
		if(!$storeName){
			return array();
		}
		
		$where = array(
			'title'=>array('like','%'.$storeName.'%')
		);
		
		$ids = array();
		
		$qualityList = $this->model('FaultinessQuality')->field('identity')->where($where)->select();
		
		if($qualityList){
			foreach($qualityList as $key=>$quality){
				$ids[] = $quality['identity'];
			}
		}
		
		return $ids;
	}
	
	/**
	 *
	 * 检测质量计划名称
	 *
	 * @param $qualityName 质量计划名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($qualityName){
		if($qualityName){
			$where = array(
				'title'=>$qualityName
			);
			return $this->model('FaultinessQuality')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除质量计划
	 *
	 * @param $qualityId 质量计划ID
	 *
	 * @reutrn int;
	 */
	public function removeQualityId($qualityId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$qualityId
		);
		
		$qualityData = $this->model('FaultinessQuality')->where($where)->select();
		if($qualityData){
			
			
			$output = $this->model('FaultinessQuality')->where($where)->delete();
		}
		
		return $output;
	}
	
	public function newQualityByProduct($productId,$subjectId)
	{
		return $this->newQuality(FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_PRODUCT,$productId,$subjectId);
	}
	
	public function newQualityByPlatform($platformId,$subjectId)
	{
		return $this->newQuality(FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_PLATFORM,$platformId,$subjectId);
	}
	
	public function newQualityByJoggle($joggleId,$subjectId)
	{
		return $this->newQuality(FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_JOGGLE,$joggleId,$subjectId);
	}
	
	public function newQualityByFrontend($frontendId,$subjectId)
	{
		return $this->newQuality(FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_FRONTEND,$frontendId,$subjectId);
	}
	
	public function newQualityByBulletin($bulletinId,$subjectId)
	{
		return $this->newQuality(FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_BULLETIN,$bulletinId,$subjectId);
	}
	
	public function newQualityByDemand($demandId,$subjectId)
	{
		return $this->newQuality(FaultinessQualityModel::FAULTINESS_QUALITY_IDTYPE_DEMAND,$demandId,$subjectId);
	}
	
	public function newQuality($idtype,$id,$subjectId){
		
		$qualityData = array(
			'idtype'=>$idtype,
			'id'=>$id,
			'subject_identity'=>$subjectId,
		);
		return $this->insert($qualityData);
		
	}
	
	/**
	 *
	 * 质量计划修改
	 *
	 * @param $qualityId 质量计划ID
	 * @param $qualityNewData 质量计划数据
	 *
	 * @reutrn int;
	 */
	public function update($qualityNewData,$qualityId){
		$where = array(
			'identity'=>$qualityId
		);
		
		
		$qualityData = $this->model('FaultinessQuality')->where($where)->find();
		if($qualityData){
		
			$qualityNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FaultinessQuality')->data($qualityNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新质量计划
	 *
	 * @param $qualityNewData 质量计划信息
	 *
	 * @reutrn int;
	 */
	public function insert($qualityNewData){
				
		$qualityNewData['subscriber_identity'] =$this->session('uid');		
		$qualityNewData['dateline'] = $this->getTime();
		$qualityNewData['sn'] = $this->get_sn();
		$qualityNewData['lastupdate'] = $qualityNewData['dateline'];
		
		$qualityNewData['liability_subscriber_identity'] = 28;
		$qualityNewData['status'] = FaultinessQualityModel::FAULTINESS_QUALITY_STATUS_WAIT_HANDLE;
		
		$qualityId = $this->model('FaultinessQuality')->data($qualityNewData)->add();
		
		return $qualityId;
	}
}