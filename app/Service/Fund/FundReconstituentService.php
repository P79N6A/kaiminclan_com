<?php
/**
 *
 * 产品
 *
 * 基金
 *
 */
class  FundReconstituentService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getReconstituentList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FundReconstituent')->where($where)->count();
		if($count){
			$handle = $this->model('FundReconstituent')->where($where);
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
					'label'=>FundReconstituentModel::getStatusTitle($data['status'])
				);
				
			}
			
			$catalogueData = $this->service('FundProduct')->getCatalogueInfo($catalogueIds);
			$currencyData = $this->service('MechanismCurrency')->getCurrencyById($currencyIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['catalogue'] = isset($catalogueData[$data['catalogue_identity']])?$catalogueData[$data['catalogue_identity']]:array();
				$listdata[$key]['currency'] = isset($currencyData[$data['currency_identity']])?$currencyData[$data['currency_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function adjustQuotientNum($reconstituentId,$quantity){
		
		if(is_array($reconstituentId)){
			$reconstituentId = array($reconstituentId);
		}
		
		$reconstituentId = array_map('intval',$reconstituentId);
		
		if(empty($reconstituentId)){
			return 0;
		}
		if($quantity === 0){
			return 0;
		}
		
		$where = array();
		$where['identity'] = $reconstituentId;
		
		if($quantity < 0){
			$quantity = substr($quantity,1);
			$this->model('FundReconstituent')->where($where)->setDec('quotient_num',$quantity);
		}else{
			$this->model('FundReconstituent')->where($where)->setInc('quotient_num',$quantity);
		}
		
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $reconstituentId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getReconstituentInfo($reconstituentId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$reconstituentId
		);
		
		$reconstituentData = array();
		if(is_array($reconstituentId)){
			$reconstituentList = $this->model('FundReconstituent')->field($field)->where($where)->select();
			if($reconstituentList){
				foreach($reconstituentList as $key=>$reconstituent){
					$reconstituentData[$reconstituent['identity']] = $reconstituent;
				}
			}
		}else{
			$reconstituentData = $this->model('FundReconstituent')->field($field)->where($where)->find();
		}
		return $reconstituentData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $reconstituentName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkSymbolExists($id,$idtype){
		$where = array(
			'id'=>$id,
			'idtype'=>$idtype
		);
		return $this->model('FundReconstituent')->where($where)->count();
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $reconstituentId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeReconstituentId($reconstituentId){
		
		$output = 0;
		
		if(count($reconstituentId) < 1){
			return $output;
		}
		
		$disabledReconstituentIds = FundReconstituentModel::getReconstituentTypeList();
		foreach($reconstituentId as $key=>$rid){
			if(in_array($rid,$disabledReconstituentIds)){
				unset($reconstituentId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$reconstituentId
		);
		
		$reconstituentData = $this->model('FundReconstituent')->field('catalogue_identity')->where($where)->select();
		if($reconstituentData){
			
			$output = $this->model('FundReconstituent')->where($where)->delete();			
			
			$catlaogueIds = array();
			foreach($reconstituentData as $key=>$reconstituent){
				$catalogueIds[] = $reconstituent['catalogue_identity'];
			}
			
			$this->service('FundProduct')->adjustReconstituentNum($catalogueIds,'-'.count($catalogueIds));
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $reconstituentId 分类ID
	 * @param $reconstituentNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($reconstituentNewData,$reconstituentId){
		$where = array(
			'identity'=>$reconstituentId
		);
		
		$reconstituentData = $this->model('FundReconstituent')->where($where)->find();
		if($reconstituentData){
			
			$reconstituentNewData['lastupdate'] = $this->getTime();
			$this->model('FundReconstituent')->data($reconstituentNewData)->where($where)->save();
			if($reconstituentNewData['catalogue_identity'] != $reconstituentData['catalogue_identity']){
				$this->service('FundProduct')->adjustReconstituentNum($reconstituentNewData['catalogue_identity'],1);
				$this->service('FundProduct')->adjustReconstituentNum($reconstituentData['catalogue_identity'],-1);
			}
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $reconstituentNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($reconstituentNewData){
		if(!$reconstituentNewData){
			return -1;
		}
		$reconstituentNewData['sn'] = $this->get_sn();
		$reconstituentNewData['subscriber_identity'] =$this->session('uid');
		$reconstituentNewData['dateline'] = $this->getTime();
		$reconstituentNewData['lastupdate'] = $reconstituentNewData['dateline'];
		
		$reconstituentId = $this->model('FundReconstituent')->data($reconstituentNewData)->add();
		if($reconstituentId){
			$this->service('FundProduct')->adjustReconstituentNum($reconstituentNewData['catalogue_identity'],1);
		}
		
		return $reconstituentId;
	}
}