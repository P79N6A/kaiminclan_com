<?php
/**
 *
 * 接口
 *
 * 应用/扩展
 *
 */
class ProgramInterfaceService extends Service {
	
	
	/**
	 *
	 * 接口信息
	 *
	 * @param $field 接口字段
	 * @param $status 接口状态
	 *
	 * @reutrn array;
	 */
	public function getInterfaceList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProgramInterface')->where($where)->count();
		if($count){
			$listdata = $this->model('ProgramInterface')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function getListByFunctionalIds($functionalIds){
		
		$where = array(
			'functional_identity'=>$functionalIds
		);
		$where['status'] = ProgramInterfaceModel::PROGRAM_INTERFACE_STATUS_FINISH;
		return $this->model('ProgramInterface')->where($where)->select();
	}
	
	
	
	/**
	 *
	 * 删除接口
	 *
	 * @param $interfaceId 接口ID
	 *
	 * @reutrn int;
	 */
	public function removeInterfaceId($interfaceId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$interfaceId
		);
		
		$interfaceData = $this->model('ProgramInterface')->where($where)->find();
		if($interfaceData){
			
			$output = $this->model('ProgramInterface')->where($where)->delete();
			$this->service('ProgramApplication')->relaese();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 接口修改
	 *
	 * @param $interfaceId 接口ID
	 * @param $interfaceNewData 接口数据
	 *
	 * @reutrn int;
	 */
	public function update($interfaceNewData,$interfaceId){
		$where = array(
			'identity'=>$interfaceId
		);
		
		$interfaceData = $this->model('ProgramInterface')->where($where)->find();
		if($interfaceData){
			
			$interfaceNewData['lastupdate'] = $this->getTime();
			$this->model('ProgramInterface')->data($interfaceNewData)->where($where)->save();
			$this->service('ProgramApplication')->relaese();
		}
	}
	
	/**
	 *
	 * 新接口
	 *
	 * @param $interfaceData 接口信息
	 *
	 * @reutrn int;
	 */
	public function insert($interfaceData){
		
		$interfaceData['subscriber_identity'] = $this->getUid();
		$interfaceData['dateline'] = $this->getTime();
			
		$interfaceData['lastupdate'] = $interfaceData['dateline'];
		$this->model('ProgramInterface')->data($interfaceData)->add();
	}
}