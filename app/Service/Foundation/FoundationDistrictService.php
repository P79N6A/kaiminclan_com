<?php
/**
 *
 * 地区
 *
 * 基础
 *
 */
class  FoundationDistrictService extends Service {
	
	
	public function getGlobalTree($filename = 'citydata'){
		set_time_limit(0);
		$treeList = array();
		$where = array('status'=>0,'level'=>array('lt',3));
		$listdata = $this->model('FoundationDistrict')->field('identity as id,district_identity as pid,title,fullname')->where($where)->select();
		
		if($listdata){
			foreach($listdata as $key=>$data){
				if($data['pid']) continue;
				foreach($listdata as $cnt=>$sub_data){
					if($sub_data['pid'] == $data['id']){
						foreach($listdata as $sub_cnt=>$sub_sub_data){
							if($sub_sub_data['pid'] == $sub_data['id']){
								foreach($listdata as $sub_sub_cnt=>$sub_sub_sub_data){
									if($sub_sub_sub_data['pid'] == $sub_sub_data['id']){
										$sub_sub_data['s'][] = $sub_sub_sub_data;
									}
								}
								$sub_data['s'][] = $sub_sub_data;
							}
						}
					$treeList[] = $sub_data;
					}
				}
			}
		}
		
		file_put_contents(__DATA__.'/json/'.$filename.'.json',json_encode($treeList,JSON_UNESCAPED_UNICODE));
	}
	
	public function getTreeList($districtId = 30,$level = 2,$filename = 'citydata'){
		set_time_limit(0);
		$treeList = array();
		$where = array('status'=>0);
		$listdata = $this->model('FoundationDistrict')->field('identity as id,district_identity as pid,title,fullname')->where($where)->select();
		
		if($listdata){
			foreach($listdata as $key=>$data){
				if($districtId != $data['id']){
					continue;
				}
				foreach($listdata as $cnt=>$sub_data){
					if($sub_data['pid'] == $data['id']){
						foreach($listdata as $sub_cnt=>$sub_sub_data){
							if($sub_sub_data['pid'] == $sub_data['id']){
								foreach($listdata as $sub_sub_cnt=>$sub_sub_sub_data){
									if($sub_sub_sub_data['pid'] == $sub_sub_data['id']){
										$sub_sub_data['s'][] = $sub_sub_sub_data;
									}
								}
								$sub_data['s'][] = $sub_sub_data;
							}
						}
					$treeList[] = $sub_data;
					}
				}
			}
		}
		
		file_put_contents(__DATA__.'/json/'.$filename.'.json',json_encode($treeList,JSON_UNESCAPED_UNICODE));
	}
	
	private function getList($districtId){
		$where = array();
		$where['district_identity'] = intval($districtId);
		
		return $this->model('FoundationDistrict')->field('identity as id,district_identity as pid,title,fullname')->where($where)->select();
	}
	/**
	 *
	 * 获取完整信息
	 *
	 * @param $districtId地区ID 
	 * @param $type 返回类型，0数组，1字符 
	 * @param $level 深度 
	 *
	 * @reutrn array;
	 */
	public function getFullName($districtId,$type = 0,$level = FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_COUNTRY){
		
		$districtId = intval($districtId);
		if(!$districtId){
			return array();
		}
		
		$districtList = $where = array();
		$where['identity'] = $districtId;
		$district_data = $this->model('FoundationDistrict')->field('identity,fullname as title,district_identity')->where($where)->find();
		if($district_data){
			$districtList[] = $district_data['title'];
			while($district_data['district_identity']){
				$where['identity'] = $district_data['district_identity'];
				$district_data = $this->model('FoundationDistrict')->field('identity,fullname as title,district_identity')->where($where)->find();
				if(!$district_data){
					break;
				}
				$districtList[] = $district_data['title'];
			}
		}
		krsort($districtList);
		if($type){
			return $districtList;
		}
		return implode('',$districtList);
	}
	/**
	 *
	 * 地区信息
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getDistrictList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc',$field='*'){
		$count = $this->model('FoundationDistrict')->where($where)->count();
		if($count){
			$shoppingHandle = $this->model('FoundationDistrict')->field($field)->where($where)->orderby($orderby);
			if($start && $perpage){
				$shoppingHandle = $shoppingHandle->limit($start,$perpage,$count);
			}
			$listdata = $shoppingHandle->select();
			
		}
		return array('count'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 查询地区伏击信息
	 *
	 * @param $districtId 地区ID
	 *
	 * @reutrn array;
	 */
	public function getFatherData($districtIds){
		
		$output = array();
		if(!is_array($districtIds)){
			$districtIds = array($districtIds);
		}
		
		foreach($districtIds as $key=>$districtId){
			$subDistrictId = $districtId;
			while(true){
					
				$where = array(
					'identity'=>$districtId
				);
				$districtData = $this->model('FoundationDistrict')->field('identity,level,title,district_identity')->where($where)->find();
				if(!$districtData){
					break;
				}
				$districtId = $districtData['district_identity'];
				//深度【级别【0洲，1地区，2国，3省，4市，5县/区，6镇，7乡/村】】	
				switch($districtData['level']){
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_CONTINENT:
						$output[$subDistrictId]['continent'] = $districtData;
						break;
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_REGION:
						$output[$subDistrictId]['region'] = $districtData;
						break;
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_COUNTRY:
						$output[$subDistrictId]['country'] = $districtData;
						break;
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_PROVINCE:
						$output[$subDistrictId]['province'] = $districtData;
						break;
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_CITY:
						$output[$subDistrictId]['city'] = $districtData;
						break;
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_COUNTY_DISTRICT:
						$output[$subDistrictId]['county'] = $districtData;
						break;
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_TOWN:
						$output[$subDistrictId]['town'] = $districtData;
						break;
					case FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_TOWNSHIP_VILLAGE:
						$output[$subDistrictId]['viliage'] = $districtData;
						break;
				}
				if(!$districtId){
					break;
				}
			}
		}
		
		return $output;
	}
	
	/**
	 *
	 * 查询指定级别ID
	 *
	 * @param $districtId 地区ID
	 * @param $level 层级
	 *
	 * @reutrn array;
	 */
	public function getDistrictLevelId($districtId,$level = FoundationDistrictModel::FOUNDATION_DISTRICT_LEVEL_COUNTRY){
		
		while(true){
				
			$where = array(
				'identity'=>$districtId
			);
			$districtData = $this->model('FoundationDistrict')->field('identity,district_identity,level')->where($where)->find();
			if(!$districtData){
				$districtId = 0;
				break;
			}
			$districtId = $districtData['district_identity'];
			if($districtData['level'] == $level){
				$districtId = $districtData['identity'];
				break;
			}
			$where = array(
				'identity'=>$districtId
			);
		}
		
		return $districtId;
	}
	
	/**
	 *
	 * 地区信息
	 *
	 * @param $districtId 地区ID
	 * @param $field 查询的字段
	 *
	 * @reutrn array;
	 */
	public function getDistrictInfo($districtId,$field = 'identity,title'){
		
		$districtData = array();
		
		$where = array(
			'identity'=>$districtId
		);
		
		$districtList = $this->model('FoundationDistrict')->field($field)->where($where)->select();
		if($districtList){
			foreach($districtList as $key=>$data){
				$districtData[$data['identity']] = $data;
			}
		}
		if(!is_array($districtId)){
			$districtData = current($districtData);
		}
		
		
		return $districtData;
	}
	/**
	 *
	 * 获取地区ID
	 *
	 * @param $areaName 地区信息
	 *
	 * @reutrn int;
	 */
	public function fetchDistrictIdByTitle($areaName){
		$districtId = 0;
		if($areaName){
			$where = array(
				'title'=>$areaName,
			);
			$districtData = $this->model('FoundationDistrict')->field('identity')->where($where)->find();
			if($districtData){
				$districtId = $districtData['identity'];
			}
		}
		return $districtId;
	}
	/**
	 *
	 * 检测地区
	 *
	 * @param $telephone 电话号码
	 *
	 * @reutrn int;
	 */
	public function checkDistrictName($districtName){
		if($districtName){
			$where = array(
				'title'=>$districtName,
			);
			return $this->model('FoundationDistrict')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除地区
	 *
	 * @param $districtId 地区ID
	 *
	 * @reutrn int;
	 */
	public function removeDistrictId($districtId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$districtId
		);
		
		$districtData = $this->model('FoundationDistrict')->where($where)->select();
		if($districtData){
			$output = $this->model('FoundationDistrict')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 取消默认地区
	 *
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function cannelDefaultDistrictByUid($uid){
		$where = array(
			'subscriber_identity'=>$uid
		);
		$districtNewData = array(
			'secleted'=>FoundationDistrictModel::MARKET_CONTACT_SELECTED_NO
		);
		$districtNewData['lastupdate'] = $this->getTime();
		$result = $this->model('FoundationDistrict')->data($districtNewData)->where($where)->save();
			
	}
	
	/**
	 *
	 * 地区修改
	 *
	 * @param $districtId 地区ID
	 * @param $districtNewData 地区数据
	 *
	 * @reutrn int;
	 */
	public function update($districtNewData,$districtId){
		$where = array(
			'identity'=>$districtId
		);
		
		$districtData = $this->model('FoundationDistrict')->where($where)->find();
		if($districtData){
			
			$districtNewData['lastupdate'] = $this->getTime();
			$result = $this->model('FoundationDistrict')->data($districtNewData)->where($where)->save();
			if($result){
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新地区
	 *
	 * @param $districtNewData 地区信息
	 *
	 * @reutrn int;
	 */
	public function insert($districtNewData){
		$districtNewData['subscriber_identity'] =$this->session('uid');		
		$districtNewData['dateline'] = $this->getTime();
			
		$districtNewData['lastupdate'] = $districtNewData['dateline'];
		$districtId = $this->model('FoundationDistrict')->data($districtNewData)->add();
		
		
	}
}