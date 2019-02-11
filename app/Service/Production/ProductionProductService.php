<?php
/**
 *
 * 模块
 *
 * 科技
 *
 */
class ProductionProductService extends Service
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
	public function getProductList($where,$start,$perpage,$order = 'identity DESC'){
		
		$count = $this->model('ProductionProduct')->where($where)->count();
		if($count){
			$handle = $this->model('ProductionProduct')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$productIds =$deviceIds = array();
			foreach($listdata as $key=>$data){
				$productIds[] = $data['subject_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>ProductionProductModel::getStatusTitle($data['status'])
				);
			}
			
			$subjectData = $this->service('ProjectSubject')->getSubjectInfo($productIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['subject'] = isset($subjectData[$data['subject_identity']])?$subjectData[$data['subject_identity']]:array();
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
	public function checkProductTitle($title,$subjectId){
		if($title){
				$where = array(
					'title'=>$title,
					'subject_identity'=>$subjectId
				);
			return $this->model('ProductionProduct')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $productId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getProductInfo($productId,$field = '*'){
		
		$where = array(
			'identity'=>$productId
		);
		
		$productData = $this->model('ProductionProduct')->field($field)->where($where)->find();
		
		return $productData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $productId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeProductId($productId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$productId
		);
		
		$productData = $this->model('ProductionProduct')->where($where)->find();
		if($productData){
			
			$output = $this->model('ProductionProduct')->where($where)->delete();
			
			$this->service('PaginationItem')->removeProductIdAllItem($productId);
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $productId 模块ID
	 * @param $productNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($productNewData,$productId){
		$where = array(
			'identity'=>$productId
		);
		
		$productData = $this->model('ProductionProduct')->where($where)->find();
		if($productData){
			
			$productNewData['lastupdate'] = $this->getTime();
			$this->model('ProductionProduct')->data($productNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $productNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($productNewData){
		
		$productNewData['subscriber_identity'] =$this->session('uid');
		$productNewData['dateline'] = $this->getTime();
		$productNewData['sn'] = $this->get_sn();
			
		$productNewData['lastupdate'] = $productNewData['dateline'];
		$productId = $this->model('ProductionProduct')->data($productNewData)->add();
		
		return $productId;
	}
}