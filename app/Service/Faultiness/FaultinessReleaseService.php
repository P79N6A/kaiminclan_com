<?php

class FaultinessReleaseService extends Service {

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
	public function getReleaseList($where = array(),$start = 0,$perpage = 0,$order = 'identity desc'){
		
		
		$count = $this->model('FaultinessRelease')->where($where)->count();

		$listdata = array();
		if($count){
			$subscriberHandle  = $this->model('FaultinessRelease')->where($where)->orderby($order);
			
			if($perpage){
				$subscriberHandle->limit($start,$perpage,$count);
			}
			$listdata = $subscriberHandle->select();
			$idtypeData = $subscriberIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'label'=>FaultinessReleaseModel::getStatusTitle($data['status']),
					'value'=>$data['status']
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
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function getReleaseData($releaseId){
		
		$releaseData = array();
		
		$releaseId = $this->getInt($releaseId);
		if(empty($releaseId)){
			return $releaseData;
		}
		
		$where = array(
			'identity'=>$releaseId
		);
		
		$listdata =  $this->model('FaultinessRelease')->where($where)->select();
		if($listdata){
			$releaseData = $listdata;
			if(!is_array($releaseId)){
				$releaseData = current($releaseData);
			}
		}
		return $releaseData;
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
	public function getAllReleaseList($where = array(),$orderby = 'identity desc',$start = 0,$perpage = 0){
		$releaseData = array();
		
		
		$count = $this->model('FaultinessRelease')->where($where)->count();
		
		if($count){
			
			$releaseHandle = $this->model('FaultinessRelease')->where($where);
			if($start && $perpage){
				$releaseHandle->limit($start,$perpage,$count);
			}
			$releaseList = $releaseHandle->select();
			
			$attachIds = $groupingIds = array();
			foreach($releaseList as $key=>$release){
				$groupingIds[] = $release['grouping_identity'];
				$attachId = $release['attachment_identity'];
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
			$groupingData = $this->service('FaultinessRelease')->getGroupInfo($release['group_identity'],'identity,title');
			
			foreach($releaseList as $key=>$release){
				
				$groupingId = $release['grouping_identity'];
				
				if(isset($groupingData[$groupingId])){
					$releaseList[$key]['grouping'] = $groupingData[$groupingId];
				}else{
					$releaseList[$key]['grouping'] = array();
				}
				unset($releaseList[$key]['grouping_identity']);
				
				$attachIds = $release['attachment_identity'];
				if(strpos($attachIds,'.') !== false){
					$attachIds = explode(',',$attachIds);
				}else{
					$attachIds = array($attachIds);
				}
				
				$releaseAttach = array();
				foreach($attachIds as $key=>$aid){
					if(isset($attachData[$aid])){
						$releaseAttach[] = $attachData;
					}
				}
				$releaseList[$key]['attach'] = $releaseAttach;
				
				unset($releaseList[$key]['attachment_identity']);
				
				foreach($releaseList as $key=>$release){
					$releaseList[$release['identity']] = $release;
				}
					
			}
			
			$releaseData = $releaseList;
			
		}
		return array('total'=>$count,'list'=>$releaseData);
	}
	
	/**
	 *
	 * 质量计划信息
	 *
	 * @param $releaseId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getReleaseInfo($releaseId){
		
		$releaseData = array();
		
		if(!is_array($releaseId)){
			$releaseId = array($releaseId);
		}
		$releaseId = array_filter(array_filter($releaseId));
		if(empty($releaseId)){
			return array();
		}
		
		$where = array(
			'identity'=>$releaseId
		);
		
		$releaseList = $this->model('FaultinessRelease')->where($where)->select();
		
		if(!is_array($releaseId)){
			$releaseData = current($releaseData);
		}
		
		return $releaseData;
	}
	
	/**
	 *
	 * 质量计划基本信息
	 *
	 * @param $releaseId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getReleaseIdByKw($keyword){
		
		$storeId = array();
		
		$where = array(
			'title'=>array('like','%'.$keyword.'%')
		);
		
		$listdata = $this->model('FaultinessRelease')->field('identity')->where($where)->select();
		if($listdata){
			foreach($listdata as $key=>$release){
				$storeId[] = $release['identity'];
			}
		}
		
		
		return $storeId;
	}
	
	/**
	 *
	 * 质量计划基本信息
	 *
	 * @param $releaseId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getReleaseBaseInfo($releaseId){
		
		$releaseData = array();
		
		$where = array(
			'identity'=>$releaseId
		);
		
		$releaseList = $this->model('FaultinessRelease')->where($where)->select();
		if($releaseList){
			if(!is_array($releaseId)){
				$releaseData = current($releaseList);
			}
		}
		
		
		return $releaseData;
	}
	
	/**
	 *
	 * 根据质量计划名称查找质量计划ID
	 *
	 * @param $releaseId 质量计划ID
	 *
	 * @reutrn array;
	 */
	public function getReleaseIdsLikeName($storeName){
		
		if(!$storeName){
			return array();
		}
		
		$where = array(
			'title'=>array('like','%'.$storeName.'%')
		);
		
		$ids = array();
		
		$releaseList = $this->model('FaultinessRelease')->field('identity')->where($where)->select();
		
		if($releaseList){
			foreach($releaseList as $key=>$release){
				$ids[] = $release['identity'];
			}
		}
		
		return $ids;
	}
	
	/**
	 *
	 * 检测质量计划名称
	 *
	 * @param $releaseName 质量计划名称
	 *
	 * @reutrn int;
	 */
	public function checkReleaseTitle($releaseName){
		if($releaseName){
			$where = array(
				'title'=>$releaseName
			);
			return $this->model('FaultinessRelease')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除质量计划
	 *
	 * @param $releaseId 质量计划ID
	 *
	 * @reutrn int;
	 */
	public function removeReleaseId($releaseId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$releaseId
		);
		
		$releaseData = $this->model('FaultinessRelease')->where($where)->select();
		if($releaseData){
			
			
			$output = $this->model('FaultinessRelease')->where($where)->delete();
		}
		
		return $output;
	}
	
	public function newRelease($idtype,$id,$subjectId){
		
		$releaseData = array(
			'idtype'=>$idtype,
			'id'=>$id,
			'subject_identity'=>$subjectId,
		);
		return $this->insert($releaseData);
		
	}
	
	/**
	 *
	 * 质量计划修改
	 *
	 * @param $releaseId 质量计划ID
	 * @param $releaseNewData 质量计划数据
	 *
	 * @reutrn int;
	 */
	public function update($releaseNewData,$releaseId){
		$where = array(
			'identity'=>$releaseId
		);
		
		
		$releaseData = $this->model('FaultinessRelease')->where($where)->find();
		if($releaseData){
		
			$releaseNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FaultinessRelease')->data($releaseNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新质量计划
	 *
	 * @param $releaseNewData 质量计划信息
	 *
	 * @reutrn int;
	 */
	public function insert($releaseNewData){
				
		$releaseNewData['subscriber_identity'] =$this->session('uid');		
		$releaseNewData['dateline'] = $this->getTime();
		$releaseNewData['sn'] = $this->get_sn();
		$releaseNewData['lastupdate'] = $releaseNewData['dateline'];
		
		$releaseId = $this->model('FaultinessRelease')->data($releaseNewData)->add();
		
		return $releaseId;
	}
}