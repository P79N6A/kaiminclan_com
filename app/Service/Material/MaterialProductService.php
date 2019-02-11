<?php
/**
 *
 * 产品
 *
 * 大宗
 *
 */
class MaterialProductService extends Service
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
	public function getProductList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MaterialProduct')->where($where)->count();
		if($count){
			$handle = $this->model('MaterialProduct')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$catalogIds = array();
			foreach($listdata as $key=>$data){
				$catalogIds[] = $data['category_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MaterialProductModel::getStatusTitle($data['status'])
				);
			}
			
			$catalogData = $this->service('MaterialCatalog')->getCatalogInfo($catalogIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['category'] = isset($catalogData[$data['category_identity']])?$catalogData[$data['category_identity']]:array();
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
	public function checkProductTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('MaterialProduct')->where($where)->count();
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
		
		$productData = $this->model('MaterialProduct')->field($field)->where($where)->select();
		
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
		
		$productData = $this->model('MaterialProduct')->where($where)->find();
		if($productData){
			
			$output = $this->model('MaterialProduct')->where($where)->delete();
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
		
		$productData = $this->model('MaterialProduct')->where($where)->find();
		if($productData){
			
			$productNewData['lastupdate'] = $this->getTime();
			$this->model('MaterialProduct')->data($productNewData)->where($where)->save();
            $this->service('PropertyCapital')->pushFuturesCapital($productId,$productNewData['title'],$productNewData['category_identity']);
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
			
		$productNewData['lastupdate'] = $productNewData['dateline'];
		$productId =$this->model('MaterialProduct')->data($productNewData)->add();
		if($productId){
            $this->service('PropertyCapital')->pushFuturesCapital($productId,$productNewData['title'],$productNewData['category_identity']);
        }
        return $productId;
	}
}