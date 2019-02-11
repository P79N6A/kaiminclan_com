<?php
/**
 *
 * 品种
 *
 * 权益
 *
 */
class InviolableSymbolService extends Service
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
	public function getSymbolList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('InviolableSymbol')->where($where)->count();
		if($count){
			$handle = $this->model('InviolableSymbol')->where($where);
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
	public function checkSymbolTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('InviolableSymbol')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $symbolId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSymbolInfo($symbolId){
		
		$where = array(
			'identity'=>$symbolId
		);
		
		$symbolData = $this->model('InviolableSymbol')->where($where)->select();
		
		return $symbolData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $symbolId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeSymbolId($symbolId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$symbolId
		);
		
		$symbolData = $this->model('InviolableSymbol')->where($where)->find();
		if($symbolData){
			
			$output = $this->model('InviolableSymbol')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $symbolId 模块ID
	 * @param $symbolNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($symbolNewData,$symbolId){
		$where = array(
			'identity'=>$symbolId
		);
		
		$symbolData = $this->model('InviolableSymbol')->where($where)->find();
		if($symbolData){
			
			$symbolNewData['lastupdate'] = $this->getTime();
			$this->model('InviolableSymbol')->data($symbolNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $symbolNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($symbolNewData){
		
		$symbolNewData['subscriber_identity'] =$this->session('uid');
		$symbolNewData['dateline'] = $this->getTime();
			
		$symbolNewData['lastupdate'] = $symbolNewData['dateline'];
		$this->model('InviolableSymbol')->data($symbolNewData)->add();
	}
}