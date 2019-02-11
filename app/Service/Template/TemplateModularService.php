<?php
/**
 *
 * 模块
 *
 * 模板
 *
 */
class  TemplateModularService extends Service {
	
	
	/**
	 *
	 * 模块列表
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getModularList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('TemplateModular')->where($where)->count();
		if($count){
			$listdata = $this->model('TemplateModular')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $modularId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getModularInfo($modularId,$field = '*'){
		
		$where = array(
			'identity'=>$modularId
		);
		
		$modularData = array();
		if(is_array($modularId)){
			$modularList = $this->model('TemplateModular')->field($field)->where($where)->select();
			if($modularList){
				foreach($modularList as $key=>$modular){
					$modularData[$modular['identity']] = $modular;
				}
			}
		}else{
			$modularData = $this->model('TemplateModular')->field($field)->where($where)->find();
		}
		return $modularData;
	}
	/**
	 *
	 * 检测模块名称
	 *
	 * @param $modularName 模块名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($modularName){
		if($modularName){
			$where = array(
				'title'=>$modularName
			);
			return $this->model('TemplateModular')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $modularId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeModularId($modularId){
		
		$output = 0;
		
		if(count($modularId) < 1){
			return $output;
		}
		
		$disabledModularIds = TemplateModularModel::getModularTypeList();
		foreach($modularId as $key=>$rid){
			if(in_array($rid,$disabledModularIds)){
				unset($modularId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$modularId
		);
		
		$modularData = $this->model('TemplateModular')->where($where)->select();
		if($modularData){
			
			$output = $this->model('TemplateModular')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $modularId 模块ID
	 * @param $modularNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($modularNewData,$modularId){
		$where = array(
			'identity'=>$modularId
		);
		
		$modularData = $this->model('TemplateModular')->where($where)->find();
		if($modularData){
			
			$modularNewData['lastupdate'] = $this->getTime();
			$this->model('TemplateModular')->data($modularNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $modularNewData 模块信息
	 *
	 * @reutrn int;
	 */
	public function insert($modularNewData){
		if(!$modularNewData){
			return -1;
		}
		$modularNewData['subscriber_identity'] =$this->session('uid');
		$modularNewData['dateline'] = $this->getTime();
		
		$this->model('TemplateModular')->data($modularNewData)->add();
	}
}