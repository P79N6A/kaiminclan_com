<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class SecuritiesIndustryService extends Service
{
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getIndustryList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('SecuritiesIndustry')->where($where)->count();
		if($count){
			$handle = $this->model('SecuritiesIndustry')->where($where);
			if($perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkIndustryTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('SecuritiesIndustry')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $industryId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getIndustryInfo($industryId,$field = '*'){
		
		$where = array(
			'identity'=>$industryId
		);
		
		$industryData = $this->model('SecuritiesIndustry')->field($field)->where($where)->find();
		
		return $industryData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $industryId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeIndustryId($industryId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$industryId
		);
		
		$industryData = $this->model('SecuritiesIndustry')->where($where)->find();
		if($industryData){
			
			$output = $this->model('SecuritiesIndustry')->where($where)->delete();
            $this->pushJson();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $industryId 模块ID
	 * @param $industryNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($industryNewData,$industryId){
		$where = array(
			'identity'=>$industryId
		);
		
		$industryData = $this->model('SecuritiesIndustry')->where($where)->find();
		if($industryData){
			
			$industryNewData['lastupdate'] = $this->getTime();
			$this->model('SecuritiesIndustry')->data($industryNewData)->where($where)->save();
            $this->service('PropertyCapital')->pushIndustryCapital($industryId,$industryNewData['title']);
            $this->pushJson();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $industryNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($industryNewData){
		
		$industryNewData['subscriber_identity'] =$this->session('uid');
		$industryNewData['dateline'] = $this->getTime();
			
		$industryNewData['lastupdate'] = $industryNewData['dateline'];
		$industryId = $this->model('SecuritiesIndustry')->data($industryNewData)->add();
		if($industryId){
            $this->service('PropertyCapital')->pushIndustryCapital($industryId,$industryNewData['title']);
		    $this->pushJson();
        }
		return $industryId;
	}

	
	private function pushJson(){
		set_time_limit(0);
		$treeList = array();
		$where = array('status'=>0);
		$listdata = $this->model('SecuritiesIndustry')->field('identity as id,industry_identity as pid,code,title')->where($where)->select();
		
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
                        $data['s'][] = $sub_data;
					}
				}
                $treeList[] = $data;
			}
		}

		
		$folder = __DATA__.'/json/securities';
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		file_put_contents($folder.'/industry.json',json_encode($treeList,JSON_UNESCAPED_UNICODE));
	}
}