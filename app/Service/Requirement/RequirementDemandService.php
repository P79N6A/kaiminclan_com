<?php
/**
 *
 * 需求
 *
 * 权限
 *
 */
class  RequirementDemandService extends Service {
	
	
	/**
	 *
	 * 需求信息
	 *
	 * @param $field 需求字段
	 * @param $status 需求状态
	 *
	 * @reutrn array;
	 */
	public function getDemandList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('RequirementDemand')->where($where)->count();
		
		if($count){
			$subscriberHandle = $this->model('RequirementDemand')->where($where);
			if($start &&  $perpage){
				$subscriberHandle->limit($start,$perpage,$count);
			}
			$listdata = $subscriberHandle->select();
			
			foreach($listdata as $key=>$data){
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>RequirementDemandModel::getStatusTitle($data['status'])
				);
			}
		}
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 需求信息
	 *
	 * @param $demandId 需求ID
	 *
	 * @reutrn array;
	 */
	public function getDemandInfo($demandId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$demandId
		);
		
		$demandData = array();
		if(is_array($demandId)){
			$demandList = $this->model('RequirementDemand')->field($field)->where($where)->select();
			if($demandList){
				foreach($demandList as $key=>$demand){
					$demandData[$demand['identity']] = $demand;
				}
			}
		}else{
			$demandData = $this->model('RequirementDemand')->field($field)->where($where)->find();
		}
		return $demandData;
	}
	/**
	 *
	 * 检测需求名称
	 *
	 * @param $demandName 需求名称
	 *
	 * @reutrn int;
	 */
	public function checkDemandTitle($demandName,$clientete_identity){
		if($demandName){
			$where = array(
				'title'=>$demandName,
				'clientete_identity'=>$clientete_identity
			);
			return $this->model('RequirementDemand')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除需求
	 *
	 * @param $demandId 需求ID
	 *
	 * @reutrn int;
	 */
	public function removeDemandId($demandId){
		
		$output = 0;
		
		if(count($demandId) < 1){
			return $output;
		}
		
		
		$where = array(
			'identity'=>$demandId
		);
		
		$demandData = $this->model('RequirementDemand')->where($where)->select();
		if($demandData){
			
			$output = $this->model('RequirementDemand')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 新岗位
	 *
	 * @param $quartersId 需求ID
	 * @param $quartersTitle 需求数据
	 *
	 * @reutrn int;
	 */
	
	public function newQuartersDemand($quartersId,$quartersTitle){
		
		$demand_identity = 2;
		$where = array();
		$where['title'] = $quartersTitle;
		$where['demand_identity'] = $demand_identity;
		$count = $this->model('RequirementDemand')->where($where)->count();
		if($count){
			return -1;
		}
		$demandData= array(
			'id'=>$quartersId,
			'idtype'=>2,
			'title'=>$quartersTitle,
			'demand_identity'=>$demand_identity
		);
		return $this->insert($demandData);
	}
	
	/**
	 *
	 * 需求修改
	 *
	 * @param $demandId 需求ID
	 * @param $demandNewData 需求数据
	 *
	 * @reutrn int;
	 */
	public function update($demandNewData,$demandId){
		$where = array(
			'identity'=>$demandId
		);
		
		$demandData = $this->model('RequirementDemand')->where($where)->find();
		if($demandData){
			
			$demandNewData['lastupdate'] = $this->getTime();
			$this->model('RequirementDemand')->data($demandNewData)->where($where)->save();
			if($demandData['catalogue_identity'] != $demandNewData['catalogue_identity']){
				$this->service('RequirementCatalogue')->adjustDemandTotal($demandData['catalogue_identity'],-1);
				$this->service('RequirementCatalogue')->adjustDemandTotal($demandNewData['catalogue_identity']);
			}
		}
	}
	
	/**
	 *
	 * 新需求
	 *
	 * @param $demandNewData 需求信息
	 *
	 * @reutrn int;
	 */
	public function insert($demandNewData){
		if(!$demandNewData){
			return -1;
		}
		$demandNewData['subscriber_identity'] =$this->session('uid');
		$demandNewData['sn'] = $this->get_sn();
		$demandNewData['dateline'] = $this->getTime();
		$demandNewData['lastupdate'] = $this->getTime();
		
		$demandId = $this->model('RequirementDemand')->data($demandNewData)->add();
		if($demandId){
			$this->service('RequirementCatalogue')->adjustDemandTotal($demandNewData['catalogue_identity']);
		}
		
		return $demandId;
	}
}