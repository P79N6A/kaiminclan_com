<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class BolsterMachineService extends Service {
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getMachineList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('BolsterMachine')->where($where)->count();
		if($count){
			$handle = $this->model('BolsterMachine')->where($where);
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
					'label'=>BolsterMachineModel::getStatusTitle($data['status'])
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
	public function checkMachineTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('BolsterMachine')->where($where)->count();
		}
		return 0;
	}
	
	public function getMachineBySymbol($symbol){
		$where = array(
			'code'=>strtolower($symbol)
		);
		return $this->model('BolsterMachine')->field('identity')->where($where)->find();
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $machineId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getMachineInfo($machineId,$field = '*'){
		
		$where = array(
			'identity'=>$machineId
		);
		
		$machineData = $this->model('BolsterMachine')->field($field)->where($where)->select();
		
		return $machineData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $machineId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeMachineId($machineId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$machineId
		);
		
		$machineData = $this->model('BolsterMachine')->where($where)->find();
		if($machineData){
			
			$output = $this->model('BolsterMachine')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $machineId 模块ID
	 * @param $machineNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($machineNewData,$machineId){
		$where = array(
			'identity'=>$machineId
		);
		
		$machineData = $this->model('BolsterMachine')->where($where)->find();
		if($machineData){
			
			$machineNewData['lastupdate'] = $this->getTime();
			$this->model('BolsterMachine')->data($machineNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $machineNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($machineNewData){
		
		$machineNewData['subscriber_identity'] =$this->session('uid');
		$machineNewData['dateline'] = $this->getTime();
		$machineNewData['sn'] = $this->get_sn();
			
		$machineNewData['lastupdate'] = $machineNewData['dateline'];
		return $this->model('BolsterMachine')->data($machineNewData)->add();
	}
	
}