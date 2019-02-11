<?php
/**
 *
 * 分类
 *
 * 基金
 *
 */
class  FundQuotientService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getQuotientList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FundQuotient')->where($where)->count();
		if($count){
			$start = intval($start);
			$perpage = intval($perpage);
			
			$handle = $this->model('FundQuotient')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$clienteteIds = $productIds = array();
			foreach($listdata as $key=>$data){
				$clienteteIds[] = $data['clientete_identity'];
				$productIds[] = $data['product_identity'];
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FundQuotientModel::getStatusTitle($data['status'])
				);
				
			}
			
			$productData = $this->service('FundProduct')->getProductInfo($productIds);
			$clienteteData = $this->service('CustomerClientete')->getClienteteInfo($clienteteIds);
			$catalogueIds = array();
			foreach($listdata as $key=>$data){
				$listdata[$key]['product'] = isset($productData[$data['product_identity']])?$productData[$data['product_identity']]:array();
				$listdata[$key]['clientete'] = isset($clienteteData[$data['clientete_identity']])?$clienteteData[$data['clientete_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $quotientId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getQuotientInfo($quotientId){
		
		$where = array(
			'identity'=>$quotientId
		);
		$quotientData = $this->model('FundQuotient')->where($where)->select();
		if($quotientData){
			if(!is_array($quotientId)){
				$quotientData = current($quotientData);
			}
		}
		return $quotientData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $quotientName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($quotientName){
		if($quotientName){
			$where = array(
				'title'=>$quotientName
			);
			return $this->model('FundQuotient')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $quotientId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeQuotientId($quotientId){
		
		$output = 0;
		
		if(count($quotientId) < 1){
			return $output;
		}
		
		
		$where = array(
			'identity'=>$quotientId
		);
		
		$quotientData = $this->model('FundQuotient')->field('product_identity')->where($where)->select();
		if($quotientData){
			
			$output = $this->model('FundQuotient')->where($where)->delete();	
			
			$productIdS = array();
			foreach($productData as $key=>$product){
				$productIdS[] = $product['product_identity'];
			}
			
			$this->service('FundProduct')->adjustQuotientNum($productIdS,'-'.count($productIdS));
		}
		
		return $output;
	}
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $quotientId 分类ID
	 * @param $quotientNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($quotientNewData,$quotientId){
		$where = array(
			'identity'=>$quotientId
		);
		
		$quotientData = $this->model('FundQuotient')->where($where)->find();
		if($quotientData){
			
			$quotientNewData['lastupdate'] = $this->getTime();
			$this->model('FundQuotient')->data($quotientNewData)->where($where)->save();
			if($quotientNewData['product_identity'] != $quotientData['product_identity']){
				$this->service('FundProduct')->adjustQuotientNum($quotientNewData['product_identity'],1);
				$this->service('FundProduct')->adjustQuotientNum($quotientData['product_identity'],-1);
			}
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $quotientNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($quotientNewData){
		if(!$quotientNewData){
			return -1;
		}
		
		$quotientNewData['sn'] = $this->get_sn();
		$quotientNewData['subscriber_identity'] =$this->session('uid');
		$quotientNewData['dateline'] = $this->getTime();
		$quotientNewData['lastupdate'] = $quotientNewData['dateline'];
		
		$quotientId = $this->model('FundQuotient')->data($quotientNewData)->add();
		if($quotientId){
			$this->service('FundProduct')->adjustQuotientNum($productNewData['product_identity'],1);
		}
		
		return $quotientId;
	}
}