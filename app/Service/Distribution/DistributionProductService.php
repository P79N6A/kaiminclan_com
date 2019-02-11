<?php
/**
 *
 * 等级
 *
 * 分销
 *
 */
class  DistributionProductService extends Service {
	
	
	/**
	 *
	 * 活动列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getProductList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('DistributionProduct')->where($where)->count();
		if($count){
			$productHandle = $this->model('DistributionProduct')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$productHandle = $productHandle->limit($start,$perpage,$count);
			}
			$listdata = $productHandle->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>DistributionProductModel::getStatusTitle($data['status'])
				);
			}
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 活动信息
	 *
	 * @param $productIds 活动ID
	 *
	 * @reutrn int;
	 */
	public function getProductInfo($productIds){
		$productData = array();
		
		$where = array(
			'identity'=>$productIds
		);
		
		$productList = $this->model('DistributionProduct')->where($where)->select();
		if($productList){
			
			if(is_array($productIds)){
				$productData = $productList;
			}else{
				$productData = current($productList);
			}
			
			
		}
		
		
		return $productData;
	}
	
	
		
	/**
	 *
	 * 删除活动
	 *
	 * @param $productId 活动ID
	 *
	 * @reutrn int;
	 */
	public function removeProductId($productId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$productId
		);
		
		$productData = $this->model('DistributionProduct')->where($where)->count();
		if($productData){
			
			$output = $this->model('DistributionProduct')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测活动
	 *
	 * @param $mobile 手机号码
	 *
	 * @reutrn int;
	 */
	public function checkProductTitle($title){
		$productId = array();		
		$where = array(
			'title'=>$title,
		);
		
		
		return $this->model('DistributionProduct')->where($where)->count();
	}
	
	/**
	 *
	 * 活动修改
	 *
	 * @param $productId 活动ID
	 * @param $productNewData 活动数据
	 *
	 * @reutrn int;
	 */
	public function update($productNewData,$productId){
		$where = array(
			'identity'=>$productId
		);
		
		$productData = $this->model('DistributionProduct')->where($where)->find();
		if($productData){
			
			
			$productNewData['lastupdate'] = $this->getTime();
			$this->model('DistributionProduct')->data($productNewData)->where($where)->save();
			
		}
	}
	
	/**
	 *
	 * 新活动
	 *
	 * @param $id 活动信息
	 * @param $idtype 活动信息
	 *
	 * @reutrn int;
	 */
	public function insert($productData){
		$dateline = $this->getTime();
		$productData['subscriber_identity'] = $this->session('uid');
		$productData['dateline'] = $dateline;
		$productData['lastupdate'] = $dateline;
		$productData['sn'] = $this->get_sn();
			
		$productId = $this->model('DistributionProduct')->data($productData)->add();
		if($productId){
		}
		return $productId;
	}
}