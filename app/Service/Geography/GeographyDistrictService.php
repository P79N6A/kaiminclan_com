<?php
/**
 *
 * 区域
 *
 * 地理
 *
 */
class  GeographyDistrictService extends Service {
	
	
	/**
	 *
	 * 区域列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getDistrictList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('GeographyDistrict')->where($where)->count();
		if($count){
			$districtHandle = $this->model('GeographyDistrict')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$districtHandle = $districtHandle->limit($start,$perpage,$count);
			}
			$listdata = $districtHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 区域信息
	 *
	 * @param $districtIds 区域ID
	 *
	 * @reutrn int;
	 */
	public function getDistrictInfo($districtIds){
		$districtData = array();
		
		$where = array(
			'identity'=>$districtIds
		);
		
		$districtList = $this->model('GeographyDistrict')->where($where)->select();
		if($districtList){
			
			if(is_array($districtIds)){
				$districtData = $districtList;
			}else{
				$districtData = current($districtList);
			}
			
			
		}
		
		
		return $districtData;
	}
	
	
		
	/**
	 *
	 * 删除区域
	 *
	 * @param $districtId 区域ID
	 *
	 * @reutrn int;
	 */
	public function removeDistrictId($districtId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$districtId
		);
		
		$oldList = $this->model('GeographyDistrict')->field('identity,district_identity')->where($where)->select();
		if($oldList){
			
			$output = $this->model('GeographyDistrict')->where($where)->delete();
			foreach($oldList as $key=>$data){
				$this->pushJson($data['district_identity']);
			}
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测区域
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkDistrictTitle($title,$districtId){
		$districtId = array();		
		$where = array(
			'title'=>$title,
			'district_identity'=>intval($districtId),
		);
		
		
		return $this->model('GeographyDistrict')->where($where)->count();
	}
	
	/**
	 *
	 * 区域修改
	 *
	 * @param $districtId 区域ID
	 * @param $districtNewData 区域数据
	 *
	 * @reutrn int;
	 */
	public function update($districtNewData,$districtId){
		$where = array(
			'identity'=>$districtId
		);
		
		$districtData = $this->model('GeographyDistrict')->where($where)->find();
		if($districtData){
			
			
			$districtNewData['lastupdate'] = $this->getTime();
			$this->model('GeographyDistrict')->data($districtNewData)->where($where)->save();
			$this->pushJson($districtNewData['district_identity']);
			
			
		}
	}
	
	/**
	 *
	 * 新区域
	 *
	 * @param $id 区域信息
	 * @param $idtype 区域信息
	 *
	 * @reutrn int;
	 */
	public function insert($districtData){
		$dateline = $this->getTime();
		$districtData['subscriber_identity'] = $this->session('uid');
		$districtData['dateline'] = $dateline;
		$districtData['lastupdate'] = $dateline;
		
		$districtId = $this->model('GeographyDistrict')->data($districtData)->add();

		$this->pushJson($districtData['district_identity']);
		
		return $districtId;
		
	}
	
	private function pushJson($districtId = 0){
		
		$list = array();
		$where = array(
			'status'=>0,
			'district_identity'=>intval($districtId)
		);
		$listdata = $this->model('GeographyDistrict')->field('identity as id,district_identity as pid,code,title')->where($where)->select();
		if($listdata){
			$sIds = array();
			foreach($listdata as $key=>$data){
				$sIds[] = $data['id'];
				$list[$key] = $data;
			}
			$where = array(
				'status'=>0,
				'district_identity'=>$sIds
			);
			$listdata = $this->model('GeographyDistrict')->field('identity as id,district_identity as pid,code,title')->where($where)->select();			
			
			if($listdata){
				$sIds = array();
				foreach($list as $cnt=>$district){
					foreach($listdata as $key=>$data){
						$sIds[] = $data['id'];
						if($district['id'] != $data['pid']) continue;
						$list[$cnt]['s'][] = $data;
					}
				}
				$where = array(
					'status'=>0,
					'district_identity'=>$sIds
				);
				$listdata = $this->model('GeographyDistrict')->field('identity as id,district_identity as pid,code,title')->where($where)->select();		
			
				if($listdata){
					foreach($list as $cnt=>$ditrict){
						foreach($ditrict['s'] as $_cnt=>$_ditrict){
							foreach($listdata as $key=>$data){
								if($_ditrict['id'] != $data['pid']) continue;
								//$list[$cnt]['s'][$_cnt]['s'][] = $data;
								$list[$cnt]['s'][$_cnt]['s'][] = $data;
							}
						}
					}
				}
			}
		}
		
		
		$folder = __DATA__.'/json/geography';
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		
		if($districtId){
			$filename = 'district_'.$districtId;
		}else{
			$filename = 'district_global';
		}
		
		file_put_contents($folder.'/'.$districtId.'.json',json_encode($list,JSON_UNESCAPED_UNICODE));
	}
}