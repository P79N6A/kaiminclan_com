<?php
/**
 *
 * 合约
 *
 * 大宗
 *
 */
class MaterialContractService extends Service
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
	public function getContractList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MaterialContract')->where($where)->count();
		if($count){
			$handle = $this->model('MaterialContract')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$productIds = array();
			foreach($listdata as $key=>$data){
				$productIds[] = $data['product_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MaterialContractModel::getStatusTitle($data['status'])
				);
			}
			
			$productData = $this->service('MaterialProduct')->getProductInfo($productIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['product'] = isset($productData[$data['product_identity']])?$productData[$data['product_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测合约名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkContractTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('MaterialContract')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $contractId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getContractInfo($contractId,$field = '*'){
		
		$where = array(
			'identity'=>$contractId
		);
		
		$contractData = $this->model('MaterialContract')->field($field)->where($where)->select();
		
		return $contractData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $contractId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeContractId($contractId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$contractId
		);
		
		$contractData = $this->model('MaterialContract')->where($where)->find();
		if($contractData){
			
			$output = $this->model('MaterialContract')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $contractId 模块ID
	 * @param $contractNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($contractNewData,$contractId){
		$where = array(
			'identity'=>$contractId
		);
		
		$contractData = $this->model('MaterialContract')->where($where)->find();
		if($contractData){
			
			$contractNewData['lastupdate'] = $this->getTime();
			$this->model('MaterialContract')->data($contractNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $contractNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($contractNewData){
		
		$contractNewData['subscriber_identity'] =$this->session('uid');
		$contractNewData['dateline'] = $this->getTime();
			
		$contractNewData['lastupdate'] = $contractNewData['dateline'];
		$this->model('MaterialContract')->data($contractNewData)->add();
	}
}