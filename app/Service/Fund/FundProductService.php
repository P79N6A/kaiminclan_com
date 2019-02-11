<?php
/**
 *
 * 产品
 *
 * 基金
 *
 */
class  FundProductService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getProductList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FundProduct')->where($where)->count();
		if($count){
			$handle = $this->model('FundProduct')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$currencyIds = $catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['catalogue_identity'];
				$currencyIds[] = $data['currency_identity'];
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FundProductModel::getStatusTitle($data['status'])
				);
				
			}
			
			$catalogueData = $this->service('FundCatalogue')->getCatalogueInfo($catalogueIds);
			$currencyData = $this->service('MechanismCurrency')->getCurrencyById($currencyIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['catalogue'] = isset($catalogueData[$data['catalogue_identity']])?$catalogueData[$data['catalogue_identity']]:array();
				$listdata[$key]['currency'] = isset($currencyData[$data['currency_identity']])?$currencyData[$data['currency_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function adjustQuotientNum($productId,$quantity){
		
		if(is_array($productId)){
			$productId = array($productId);
		}
		
		$productId = array_map('intval',$productId);
		
		if(empty($productId)){
			return 0;
		}
		if($quantity === 0){
			return 0;
		}
		
		$where = array();
		$where['identity'] = $productId;
		
		if($quantity < 0){
			$quantity = substr($quantity,1);
			$this->model('FundProduct')->where($where)->setDec('quotient_num',$quantity);
		}else{
			$this->model('FundProduct')->where($where)->setInc('quotient_num',$quantity);
		}
		
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $productId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getProductInfo($productId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$productId
		);
		
		$productData = array();
		if(is_array($productId)){
			$productList = $this->model('FundProduct')->field($field)->where($where)->select();
			if($productList){
				foreach($productList as $key=>$product){
					$productData[$product['identity']] = $product;
				}
			}
		}else{
			$productData = $this->model('FundProduct')->field($field)->where($where)->find();
		}
		return $productData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $productName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($productName){
		if($productName){
			$where = array(
				'title'=>$productName
			);
			return $this->model('FundProduct')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $productId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeProductId($productId){
		
		$output = 0;
		
		if(count($productId) < 1){
			return $output;
		}		
		
		$where = array(
			'identity'=>$productId
		);
		
		$productData = $this->model('FundProduct')->field('catalogue_identity')->where($where)->select();
		if($productData){
			
			$output = $this->model('FundProduct')->where($where)->delete();			
			
			$catlaogueIds = array();
			foreach($productData as $key=>$product){
				$catalogueIds[] = $product['catalogue_identity'];
			}
			
			$this->service('FundCatalogue')->adjustProductNum($catalogueIds,'-'.count($catalogueIds));
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $productId 分类ID
	 * @param $productNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($productNewData,$productId){
		$where = array(
			'identity'=>$productId
		);
		
		$productData = $this->model('FundProduct')->where($where)->find();
		if($productData){
			
			$productNewData['lastupdate'] = $this->getTime();
			$this->model('FundProduct')->data($productNewData)->where($where)->save();
			if($productNewData['catalogue_identity'] != $productData['catalogue_identity']){
				$this->service('FundCatalogue')->adjustProductNum($productNewData['catalogue_identity'],1);
				$this->service('FundCatalogue')->adjustProductNum($productData['catalogue_identity'],-1);
			}
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $productNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($productNewData){
		if(!$productNewData){
			return -1;
		}
		$productNewData['sn'] = $this->get_sn();
		$productNewData['subscriber_identity'] =$this->session('uid');
		$productNewData['dateline'] = $this->getTime();
		$productNewData['lastupdate'] = $productNewData['dateline'];
		
		$productId = $this->model('FundProduct')->data($productNewData)->add();
		if($productId){
			$this->service('FundCatalogue')->adjustProductNum($productNewData['catalogue_identity'],1);
		}
		
		return $productId;
	}
}