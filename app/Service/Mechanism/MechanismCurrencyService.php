<?php
/**
 *
 * 类型
 *
 * 财务
 *
 */
class MechanismCurrencyService extends Service
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
	public function getCurrencyList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MechanismCurrency')->where($where)->count();
		if($count){
			$handle = $this->model('MechanismCurrency')->where($where);
			if($start > 0 && $perpage > 0){
				$handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MechanismCurrencyModel::getStatusTitle($data['status'])
				);
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}

	public function getAllowedCurrecnyList(){
	    $where = array(
	        'status'=>MechanismCurrencyModel::MECHANISM_BANKCARD_STATUS_ENABLE
        );
	    return $this->model('MechanismCurrency')->where($where)->field('identity,title')->select();
    }
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkCurrencyTitle($title){
		if($title){
				$where = array(
					'title'=>$title,
				);
			return $this->model('MechanismCurrency')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $currencyId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCurrencyById($currencyId){
		
		$where = array(
			'identity'=>$currencyId
		);
		
		$currencyData = $this->model('MechanismCurrency')->field('identity,title')->where($where)->select();
		if($currencyData){
			if(!is_array($currencyId)){
				$currencyData = current($currencyData);
			}
		}
		return $currencyData;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $currencyId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getCurrencyInfo($currencyId,$field = '*'){
		
		$where = array(
			'identity'=>$currencyId
		);
		
		$currencyData = $this->model('MechanismCurrency')->field($field)->where($where)->find();
		
		return $currencyData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $currencyId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeCurrencyId($currencyId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$currencyId
		);
		
		$currencyData = $this->model('MechanismCurrency')->where($where)->find();
		if($currencyData){
			
			$output = $this->model('MechanismCurrency')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $currencyId 模块ID
	 * @param $currencyNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($currencyNewData,$currencyId){
		$where = array(
			'identity'=>$currencyId
		);
		
		$currencyData = $this->model('MechanismCurrency')->where($where)->find();
		if($currencyData){
			
			$currencyNewData['lastupdate'] = $this->getTime();
			$this->model('MechanismCurrency')->data($currencyNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $currencyNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($currencyNewData){
		
		$currencyNewData['subscriber_identity'] =$this->session('uid');
		$currencyNewData['dateline'] = $this->getTime();
		$currencyNewData['sn'] = $this->get_sn();
		$currencyNewData['lastupdate'] = $currencyNewData['dateline'];
		$currencyId = $this->model('MechanismCurrency')->data($currencyNewData)->add();
		return $currencyId;
	}
}