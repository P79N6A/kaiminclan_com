<?php
/**
 *
 * 模块
 *
 * 模板
 *
 */
class  TemplateLayoutService extends Service {
	
	
	/**
	 *
	 * 模块列表
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getLayoutList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('TemplateLayout')->where($where)->count();
		if($count){
			$listdata = $this->model('TemplateLayout')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $layoutId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getLayoutInfo($layoutId,$field = '*'){
		
		$where = array(
			'identity'=>$layoutId
		);
		
		$layoutData = array();
		if(is_array($layoutId)){
			$layoutList = $this->model('TemplateLayout')->field($field)->where($where)->select();
			if($layoutList){
				foreach($layoutList as $key=>$layout){
					$layoutData[$layout['identity']] = $layout;
				}
			}
		}else{
			$layoutData = $this->model('TemplateLayout')->field($field)->where($where)->find();
		}
		return $layoutData;
	}
	/**
	 *
	 * 检测模块名称
	 *
	 * @param $layoutName 模块名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($layoutName){
		if($layoutName){
			$where = array(
				'title'=>$layoutName
			);
			return $this->model('TemplateLayout')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $layoutId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeLayoutId($layoutId){
		
		$output = 0;
		
		if(count($layoutId) < 1){
			return $output;
		}
		
		$disabledLayoutIds = TemplateLayoutModel::getLayoutTypeList();
		foreach($layoutId as $key=>$rid){
			if(in_array($rid,$disabledLayoutIds)){
				unset($layoutId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$layoutId
		);
		
		$layoutData = $this->model('TemplateLayout')->where($where)->select();
		if($layoutData){
			
			$output = $this->model('TemplateLayout')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $layoutId 模块ID
	 * @param $layoutNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($layoutNewData,$layoutId){
		$where = array(
			'identity'=>$layoutId
		);
		
		$layoutData = $this->model('TemplateLayout')->where($where)->find();
		if($layoutData){
			
			$layoutNewData['lastupdate'] = $this->getTime();
			$this->model('TemplateLayout')->data($layoutNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $layoutNewData 模块信息
	 *
	 * @reutrn int;
	 */
	public function insert($layoutNewData){
		if(!$layoutNewData){
			return -1;
		}
		$layoutNewData['subscriber_identity'] =$this->session('uid');
		$layoutNewData['dateline'] = $this->getTime();
		
		$this->model('TemplateLayout')->data($layoutNewData)->add();
	}
}