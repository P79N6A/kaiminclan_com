<?php
/**
 *
 * 产品/服务
 *
 * 路由信息
 *
 */
class BolsterLoopholeService extends Service {
	/**
	 *
	 * 模块信息
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getLoopholeList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('BolsterLoophole')->where($where)->count();
		if($count){
			$handle = $this->model('BolsterLoophole')->where($where);
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
					'label'=>BolsterLoopholeModel::getStatusTitle($data['status'])
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
	public function checkLoopholeTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('BolsterLoophole')->where($where)->count();
		}
		return 0;
	}
	
	public function getLoopholeBySymbol($symbol){
		$where = array(
			'code'=>strtolower($symbol)
		);
		return $this->model('BolsterLoophole')->field('identity')->where($where)->find();
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $loopholeId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getLoopholeInfo($loopholeId,$field = '*'){
		
		$where = array(
			'identity'=>$loopholeId
		);
		
		$loopholeData = $this->model('BolsterLoophole')->field($field)->where($where)->select();
		
		return $loopholeData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $loopholeId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeLoopholeId($loopholeId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$loopholeId
		);
		
		$loopholeData = $this->model('BolsterLoophole')->where($where)->find();
		if($loopholeData){
			
			$output = $this->model('BolsterLoophole')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $loopholeId 模块ID
	 * @param $loopholeNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($loopholeNewData,$loopholeId){
		$where = array(
			'identity'=>$loopholeId
		);
		
		$loopholeData = $this->model('BolsterLoophole')->where($where)->find();
		if($loopholeData){
			
			$loopholeNewData['lastupdate'] = $this->getTime();
			$this->model('BolsterLoophole')->data($loopholeNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $loopholeNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($loopholeNewData){
		
		$loopholeNewData['subscriber_identity'] =$this->session('uid');
		$loopholeNewData['dateline'] = $this->getTime();
		$loopholeNewData['sn'] = $this->get_sn();
			
		$loopholeNewData['lastupdate'] = $loopholeNewData['dateline'];
		return $this->model('BolsterLoophole')->data($loopholeNewData)->add();
	}
	
}