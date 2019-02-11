<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class BolsterSoftwareService extends Service {
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getSoftwareList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('BolsterSoftware')->where($where)->count();
		if($count){
			$handle = $this->model('BolsterSoftware')->where($where);
			if($perpage > 0){
				$handle = $handle->limit($start,$perpage,$count);
			}
			if($order){
				$handle = $handle->orderby($order);
			}
			$listdata = $handle ->select();
			
			$currencyIds = array();
			foreach($listdata as $key=>$data){
				$currencyIds[] = $data['currency_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>BolsterSoftwareModel::getStatusTitle($data['status'])
				);
			}
			
			$currencyData = $this->service('ForeignCurrency')->getCurrencyInfo($currencyIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['currency'] = isset($currencyData[$data['currency_identity']])?$currencyData[$data['currency_identity']]:array();
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
	public function checkSoftwareTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('BolsterSoftware')->where($where)->count();
		}
		return 0;
	}
	
	public function getSoftwareBySymbol($symbol){
		$where = array(
			'code'=>strtolower($symbol)
		);
		return $this->model('BolsterSoftware')->field('identity')->where($where)->find();
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $softwareId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getSoftwareInfo($softwareId,$field = '*'){
		
		$where = array(
			'identity'=>$softwareId
		);
		
		$softwareData = $this->model('BolsterSoftware')->field($field)->where($where)->select();
		
		return $softwareData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $softwareId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeSoftwareId($softwareId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$softwareId
		);
		
		$softwareData = $this->model('BolsterSoftware')->where($where)->find();
		if($softwareData){
			
			$output = $this->model('BolsterSoftware')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $softwareId 模块ID
	 * @param $softwareNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($softwareNewData,$softwareId){
		$where = array(
			'identity'=>$softwareId
		);
		
		$softwareData = $this->model('BolsterSoftware')->where($where)->find();
		if($softwareData){
			
			$softwareNewData['lastupdate'] = $this->getTime();
			$this->model('BolsterSoftware')->data($softwareNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $softwareNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($softwareNewData){
		
		$softwareNewData['subscriber_identity'] =$this->session('uid');
		$softwareNewData['dateline'] = $this->getTime();
		$softwareNewData['sn'] = $this->get_sn();
			
		$softwareNewData['lastupdate'] = $softwareNewData['dateline'];
		return $this->model('BolsterSoftware')->data($softwareNewData)->add();
	}
	
}