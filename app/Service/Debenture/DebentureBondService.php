<?php
/**
 *
 * 货币
 *
 * 外汇
 *
 */
class DebentureBondService extends Service
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
	public function getBondList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('DebentureBond')->where($where)->count();
		if($count){
			$handle = $this->model('DebentureBond')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$catalogIds = array();
			foreach($listdata as $key=>$data){
				$catalogIds[] = $data['catalogue_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MaterialProductModel::getStatusTitle($data['status'])
				);
			}
			
			$catalogData = $this->service('DebentureCatalogue')->getCatalogueInfo($catalogIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['catalogue'] = isset($catalogData[$data['catalogue_identity']])?$catalogData[$data['catalogue_identity']]:array();
			}
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
	public function checkBondTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('DebentureBond')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $bondId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getBondInfo($bondId,$field = '*'){
		
		$where = array(
			'identity'=>$bondId
		);
		
		$bondData = $this->model('DebentureBond')->field($field)->where($where)->select();
		
		return $bondData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $bondId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeBondId($bondId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$bondId
		);
		
		$bondData = $this->model('DebentureBond')->where($where)->find();
		if($bondData){
			
			$output = $this->model('DebentureBond')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $bondId 模块ID
	 * @param $bondNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($bondNewData,$bondId){
		$where = array(
			'identity'=>$bondId
		);
		
		$bondData = $this->model('DebentureBond')->where($where)->find();
		if($bondData){
			
			$bondNewData['lastupdate'] = $this->getTime();
			$this->model('DebentureBond')->data($bondNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $bondNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($bondNewData){
		
		$bondNewData['subscriber_identity'] =$this->session('uid');
		$bondNewData['dateline'] = $this->getTime();
			
		$bondNewData['lastupdate'] = $bondNewData['dateline'];
		$this->model('DebentureBond')->data($bondNewData)->add();
	}
}