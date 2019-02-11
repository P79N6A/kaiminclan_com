<?php
/**
 *
 * 功能
 *
 *
 * 应用/扩展
 *
 */
class ProgramFunctionalService extends Service {
	
	
	/**
	 *
	 * 功能信息
	 *
	 * @param $field 功能字段
	 * @param $status 功能状态
	 *
	 * @reutrn array;
	 */
	public function getFunctionalList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProgramFunctional')->where($where)->count();
		if($count){
			$listdata = $this->model('ProgramFunctional')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function getListByApplicationIds($appIds){
		
		$where = array(
			'application_identity'=>$functionalIds
		);
		$where['status'] = ProgramFunctionalModel::PROGRAM_FUNCTIONAL_STATUS_FINISH;
		return $this->model('ProgramFunctional')->where($where)->select();
	}
	
	
	
	
	/**
	 *
	 * 删除功能
	 *
	 * @param $functionalId 功能ID
	 *
	 * @reutrn int;
	 */
	public function removeFunctionalId($functionalId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$functionalId
		);
		
		$functionalData = $this->model('ProgramFunctional')->where($where)->find();
		if($functionalData){
			
			$output = $this->model('ProgramFunctional')->where($where)->delete();
			$this->service('ProgramApplication')->relaese();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 功能修改
	 *
	 * @param $functionalId 功能ID
	 * @param $functionalNewData 功能数据
	 *
	 * @reutrn int;
	 */
	public function update($functionalNewData,$functionalId){
		$where = array(
			'identity'=>$functionalId
		);
		
		$functionalData = $this->model('ProgramFunctional')->where($where)->find();
		if($functionalData){
			
			$functionalNewData['lastupdate'] = $this->getTime();
			$this->model('ProgramFunctional')->data($functionalNewData)->where($where)->save();
			$this->service('ProgramApplication')->relaese();
		}
	}
	
	/**
	 *
	 * 新功能
	 *
	 * @param $functionalData 功能信息
	 *
	 * @reutrn int;
	 */
	public function insert($functionalData){
		
		$functionalData['subscriber_identity'] = $this->session('uid');
		$functionalData['dateline'] = $this->getTime();
			
		$functionalData['lastupdate'] = $functionalData['dateline'];
		$this->model('ProgramFunctional')->data($functionalData)->add();
		$this->service('ProgramApplication')->relaese();
	}
}