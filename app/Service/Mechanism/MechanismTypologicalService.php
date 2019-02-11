<?php
/**
 *
 * 类型
 *
 * 财务
 *
 */
class MechanismTypologicalService extends Service
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
	public function getTypologicalList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MechanismTypological')->where($where)->count();
		if($count){
			$listdata = $this->model('MechanismTypological')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MechanismTypologicalModel::getStatusTitle($data['status'])
				);
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
	public function checkTypologicalTitle($title){
		if($title){
				$where = array(
					'title'=>$title,
				);
			return $this->model('MechanismTypological')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $blockId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getTypologicalInfo($blockId){
		
		$where = array(
			'identity'=>$blockId
		);
		
		$blockData = $this->model('MechanismTypological')->where($where)->select();
		
		return $blockData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $blockId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeTypologicalId($blockId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$blockId
		);
		
		$blockData = $this->model('MechanismTypological')->where($where)->find();
		if($blockData){
			
			$output = $this->model('MechanismTypological')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $blockId 模块ID
	 * @param $blockNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($blockNewData,$blockId){
		$where = array(
			'identity'=>$blockId
		);
		
		$blockData = $this->model('MechanismTypological')->where($where)->find();
		if($blockData){
			
			$blockNewData['lastupdate'] = $this->getTime();
			$this->model('MechanismTypological')->data($blockNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $blockNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($blockNewData){
		
		$blockNewData['subscriber_identity'] =$this->session('uid');
		$blockNewData['dateline'] = $this->getTime();
		$blockNewData['sn'] = $this->get_sn();
			
		$blockNewData['lastupdate'] = $blockNewData['dateline'];
		$this->model('MechanismTypological')->data($blockNewData)->add();
	}
}