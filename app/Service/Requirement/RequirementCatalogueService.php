<?php
/**
 *
 * 需求
 *
 * 权限
 *
 */
class  RequirementCatalogueService extends Service {
	
	
	
	public function adjustDemandTotal($catalogueId,$quantity = 1){
		
		$catalogueId = $this->getInt($catalogueId);
		if(!empty($catalogueId)){
			$where = array(
				'identity'=>$catalogueId
			);
			if(strpos($quantity,'-') !== false){
				$this->model('RequirementCatalogue')->where($where)->setDec('demand_num',substr($quantity,1));
			}else{
				$this->model('RequirementCatalogue')->where($where)->setInc('demand_num',$quantity);
			}
		}
	}
	/**
	 *
	 * 需求信息
	 *
	 * @param $field 需求字段
	 * @param $status 需求状态
	 *
	 * @reutrn array;
	 */
	public function getCatalogueList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('RequirementCatalogue')->where($where)->count();
		
		if($count){
			$subscriberHandle = $this->model('RequirementCatalogue')->where($where);
			if($start &&  $perpage){
				$subscriberHandle->limit($start,$perpage,$count);
			}
			$listdata = $subscriberHandle->select();
			
			foreach($listdata as $key=>$data){
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>RequirementCatalogueModel::getStatusTitle($data['status'])
				);
			}
		}
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 需求信息
	 *
	 * @param $catalogueId 需求ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogueInfo($catalogueId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = array();
		if(is_array($catalogueId)){
			$catalogueList = $this->model('RequirementCatalogue')->field($field)->where($where)->select();
			if($catalogueList){
				foreach($catalogueList as $key=>$catalogue){
					$catalogueData[$catalogue['identity']] = $catalogue;
				}
			}
		}else{
			$catalogueData = $this->model('RequirementCatalogue')->field($field)->where($where)->find();
		}
		return $catalogueData;
	}
	/**
	 *
	 * 检测需求名称
	 *
	 * @param $catalogueName 需求名称
	 *
	 * @reutrn int;
	 */
	public function checkCatalogueTitle($catalogueName){
		if($catalogueName){
			$where = array(
				'title'=>$catalogueName
			);
			return $this->model('RequirementCatalogue')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除需求
	 *
	 * @param $catalogueId 需求ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogueId($catalogueId){
		
		$output = 0;
		
		if(count($catalogueId) < 1){
			return $output;
		}
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('RequirementCatalogue')->where($where)->select();
		if($catalogueData){
			
			$output = $this->model('RequirementCatalogue')->where($where)->delete();
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
	
	public function newQuartersCatalogue($quartersId,$quartersTitle){
		
		$catalogue_identity = 2;
		$where = array();
		$where['title'] = $quartersTitle;
		$where['catalogue_identity'] = $catalogue_identity;
		$count = $this->model('RequirementCatalogue')->where($where)->count();
		if($count){
			return -1;
		}
		$catalogueData= array(
			'id'=>$quartersId,
			'idtype'=>2,
			'title'=>$quartersTitle,
			'catalogue_identity'=>$catalogue_identity
		);
		return $this->insert($catalogueData);
	}
	
	/**
	 *
	 * 需求修改
	 *
	 * @param $catalogueId 需求ID
	 * @param $catalogueNewData 需求数据
	 *
	 * @reutrn int;
	 */
	public function update($catalogueNewData,$catalogueId){
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('RequirementCatalogue')->where($where)->find();
		if($catalogueData){
			
			$catalogueNewData['lastupdate'] = $this->getTime();
			$this->model('RequirementCatalogue')->data($catalogueNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新需求
	 *
	 * @param $catalogueNewData 需求信息
	 *
	 * @reutrn int;
	 */
	public function insert($catalogueNewData){
		if(!$catalogueNewData){
			return -1;
		}
		$catalogueNewData['subscriber_identity'] =$this->session('uid');
		$catalogueNewData['dateline'] = $this->getTime();
		$catalogueNewData['lastupdate'] = $this->getTime();
		$catalogueNewData['sn'] = $this->get_sn();
		
		$catalogueId = $this->model('RequirementCatalogue')->data($catalogueNewData)->add();
		
		return $catalogueId;
	}
}