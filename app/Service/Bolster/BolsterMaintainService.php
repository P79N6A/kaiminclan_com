<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class BolsterMaintainService extends Service {
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getMaintainList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('BolsterMaintain')->where($where)->count();
		if($count){
			$handle = $this->model('BolsterMaintain')->where($where);
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
					'label'=>BolsterMaintainModel::getStatusTitle($data['status'])
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
	public function checkMaintainTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('BolsterMaintain')->where($where)->count();
		}
		return 0;
	}
	
	public function getMaintainBySymbol($symbol){
		$where = array(
			'code'=>strtolower($symbol)
		);
		return $this->model('BolsterMaintain')->field('identity')->where($where)->find();
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $maintainId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getMaintainInfo($maintainId,$field = '*'){
		
		$where = array(
			'identity'=>$maintainId
		);
		
		$maintainData = $this->model('BolsterMaintain')->field($field)->where($where)->select();
		
		return $maintainData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $maintainId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeMaintainId($maintainId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$maintainId
		);
		
		$maintainData = $this->model('BolsterMaintain')->where($where)->find();
		if($maintainData){
			
			$output = $this->model('BolsterMaintain')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $maintainId 模块ID
	 * @param $maintainNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($maintainNewData,$maintainId){
		$where = array(
			'identity'=>$maintainId
		);
		
		$maintainData = $this->model('BolsterMaintain')->where($where)->find();
		if($maintainData){
			
			$maintainNewData['lastupdate'] = $this->getTime();
			$this->model('BolsterMaintain')->data($maintainNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $maintainNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($maintainNewData){
		
		$maintainNewData['subscriber_identity'] =$this->session('uid');
		$maintainNewData['dateline'] = $this->getTime();
		$maintainNewData['sn'] = $this->get_sn();
			
		$maintainNewData['lastupdate'] = $maintainNewData['dateline'];
		return $this->model('BolsterMaintain')->data($maintainNewData)->add();
	}
}