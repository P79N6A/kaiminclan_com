<?php
/**
 *
 * 信号
 *
 * 统计分析
 *
 */
class QuotationStructureService extends Service
{
	
	/**
	 *
	 * 信号信息
	 *
	 * @param $field 信号字段
	 * @param $status 信号状态
	 *
	 * @reutrn array;
	 */
	public function getStructureList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('QuotationStructure')->where($where)->count();
		if($count){
			$handle = $this->model('QuotationStructure')->where($where);
			if($order){
				$handle->orderby($order);
			}
			
			if($perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$symbolIds = array();
			foreach($listdata as $key=>$data){
				$symbolIds[] = $data['symbol_identity'];
			}
			
			$symbolData = $this->service('QuotationSymbol')->getSymbolInfoById($symbolIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['symbol'] = isset($symbolData[$data['symbol_identity']])?$symbolData[$data['symbol_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测信号名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkStructureTitle($title,$indicatrixId){
		if($title){
			$where = array(
				'title'=>$title,
				'indicatrix_identity'=>$indicatrix_identity
			);
			return $this->model('QuotationStructure')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 信号信息
	 *
	 * @param $structureId 信号ID
	 *
	 * @reutrn array;
	 */
	public function getStructureInfo($structureId,$field = '*'){
		
		$where = array(
			'identity'=>$structureId
		);
		
		$structureData = $this->model('QuotationStructure')->field($field)->where($where)->find();
		
		return $structureData;
	}
	
	/**
	 *
	 * 删除信号
	 *
	 * @param $structureId 信号ID
	 *
	 * @reutrn int;
	 */
	public function removeStructureId($structureId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$structureId
		);
		
		$structureData = $this->model('QuotationStructure')->where($where)->find();
		if($structureData){
			
			$output = $this->model('QuotationStructure')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 信号修改
	 *
	 * @param $structureId 信号ID
	 * @param $structureNewData 信号数据
	 *
	 * @reutrn int;
	 */
	public function update($structureNewData,$structureId){
		
		$afterRows = 0;
		$where = array(
			'identity'=>$structureId
		);
		
		$structureData = $this->model('QuotationStructure')->where($where)->find();
		if($structureData){
			
			$structureNewData['lastupdate'] = $this->getTime();
			$afterRows = $this->model('QuotationStructure')->data($structureNewData)->where($where)->save();
		}
		
		return $afterRows;
	}
	
	/**
	 *
	 * 新信号
	 *
	 * @param $structureNewData 信号数据
	 *
	 * @reutrn int;
	 */
	public function insert($structureNewData){
		
		$structureNewData['subscriber_identity'] =$this->session('uid');
		$structureNewData['dateline'] = $this->getTime();
			
		$structureNewData['lastupdate'] = $structureNewData['dateline'];
		return $this->model('QuotationStructure')->data($structureNewData)->add();
	}
}