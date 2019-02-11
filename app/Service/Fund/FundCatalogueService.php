<?php
/**
 *
 * 分类
 *
 * 基金
 *
 */
class  FundCatalogueService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getCatalogueList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FundCatalogue')->where($where)->count();
		if($count){
			$start = intval($start);
			$perpage = intval($perpage);
			
			$handle = $this->model('FundCatalogue')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $catalogueId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getCatalogueInfo($catalogueId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$catalogueId
		);
		$catalogueData = $this->model('FundCatalogue')->field($field)->where($where)->select();
		if($catalogueData){
			if(!is_array($catalogueId)){
				$catalogueData = current($catalogueData);
			}
		}
		return $catalogueData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $catalogueName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($catalogueName){
		if($catalogueName){
			$where = array(
				'title'=>$catalogueName
			);
			return $this->model('FundCatalogue')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $catalogueId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeCatalogueId($catalogueId){
		
		$output = 0;
		
		if(count($catalogueId) < 1){
			return $output;
		}
		
		$disabledCatalogueIds = FundCatalogueModel::getCatalogueTypeList();
		foreach($catalogueId as $key=>$rid){
			if(in_array($rid,$disabledCatalogueIds)){
				unset($catalogueId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('FundCatalogue')->where($where)->select();
		if($catalogueData){
			
			$output = $this->model('FundCatalogue')->where($where)->delete();
		}
		
		return $output;
	}
	
	public function adjustProductNum($catalogueId,$quantity){
		
		if(is_array($catalogueId)){
			$catalogueId = array($catalogueId);
		}
		
		$catalogueId = array_map('intval',$catalogueId);
		
		if(empty($catalogueId)){
			return 0;
		}
		if($quantity === 0){
			return 0;
		}
		
		$where = array();
		$where['identity'] = $catalogueId;
		
		if($quantity < 0){
			$quantity = substr($quantity,1);
			$this->model('FundCatalogue')->where($where)->setDec('product_num',$quantity);
		}else{
			$this->model('FundCatalogue')->where($where)->setInc('product_num',$quantity);
		}
		
	}
	
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $catalogueId 分类ID
	 * @param $catalogueNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($catalogueNewData,$catalogueId){
		$where = array(
			'identity'=>$catalogueId
		);
		
		$catalogueData = $this->model('FundCatalogue')->where($where)->find();
		if($catalogueData){
			
			$catalogueNewData['lastupdate'] = $this->getTime();
			$this->model('FundCatalogue')->data($catalogueNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $catalogueNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($catalogueNewData){
		if(!$catalogueNewData){
			return -1;
		}
		$catalogueNewData['subscriber_identity'] =$this->session('uid');
		$catalogueNewData['dateline'] = $this->getTime();
		$catalogueNewData['lastupdate'] = $catalogueNewData['dateline'];
		
		$catalogueId = $this->model('FundCatalogue')->data($catalogueNewData)->add();
		
		return $catalogueId;
	}
}