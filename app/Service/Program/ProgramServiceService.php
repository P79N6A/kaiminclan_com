<?php
/**
 *
 * 服务
 *
 * 资源库
 *
 */
class ProgramServiceService extends Service {
	
	
	/**
	 *
	 * 服务信息
	 *
	 * @param $field 服务字段
	 * @param $status 服务状态
	 *
	 * @reutrn array;
	 */
	public function getServiceList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProgramService')->where($where)->count();
		if($count){
			$listdata = $this->model('ProgramService')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	
	
	
	/**
	 *
	 * 服务信息
	 *
	 * @param $serviceId 服务ID
	 *
	 * @reutrn array;
	 */
	public function getServiceInfo($serviceId,$field = '*'){
		
		$where = array(
			'identity'=>$serviceId
		);
		
		$serviceData = $this->model('ProgramService')->field($field)->where($where)->find();
		if($serviceData){
			$serviceData['catalog'] = $this->service('ResourcesCatalog')->getCatalogInfo($serviceData['catalog_identity'],'identity,title');
			
		}
		
		return $serviceData;
	}
	
	/**
	 *
	 * 删除服务
	 *
	 * @param $serviceId 服务ID
	 *
	 * @reutrn int;
	 */
	public function removeServiceId($serviceId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$serviceId
		);
		
		$serviceData = $this->model('ProgramService')->where($where)->find();
		if($serviceData){
			
			$output = $this->model('ProgramService')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 服务修改
	 *
	 * @param $serviceId 服务ID
	 * @param $serviceNewData 服务数据
	 *
	 * @reutrn int;
	 */
	public function update($serviceNewData,$serviceId){
		$where = array(
			'identity'=>$serviceId
		);
		
		$serviceData = $this->model('ProgramService')->where($where)->find();
		if($serviceData){
			
			$serviceNewData['lastupdate'] = $this->getTime();
			$this->model('ProgramService')->data($serviceNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新服务
	 *
	 * @param $serviceData 服务信息
	 *
	 * @reutrn int;
	 */
	public function insert($serviceData){
		
		$serviceData['subscriber_identity'] = $this->session('uid');
		$serviceData['dateline'] = $this->getTime();
			
		$serviceData['lastupdate'] = $serviceData['dateline'];
		$this->model('ProgramService')->data($serviceData)->add();
	}
}