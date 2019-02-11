<?php
/**
 *
 * 模块
 *
 * 模板
 *
 */
class  TemplateThemeService extends Service {
	
	
	/**
	 *
	 * 模块列表
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getThemeList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('TemplateTheme')->where($where)->count();
		if($count){
			$listdata = $this->model('TemplateTheme')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $themeId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getThemeInfo($themeId,$field = '*'){
		
		$where = array(
			'identity'=>$themeId
		);
		
		$themeData = array();
		if(is_array($themeId)){
			$themeList = $this->model('TemplateTheme')->field($field)->where($where)->select();
			if($themeList){
				foreach($themeList as $key=>$theme){
					$themeData[$theme['identity']] = $theme;
				}
			}
		}else{
			$themeData = $this->model('TemplateTheme')->field($field)->where($where)->find();
		}
		return $themeData;
	}
	/**
	 *
	 * 检测模块名称
	 *
	 * @param $themeName 模块名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($themeName){
		if($themeName){
			$where = array(
				'title'=>$themeName
			);
			return $this->model('TemplateTheme')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $themeId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeThemeId($themeId){
		
		$output = 0;
		
		if(count($themeId) < 1){
			return $output;
		}
		
		$disabledThemeIds = TemplateThemeModel::getThemeTypeList();
		foreach($themeId as $key=>$rid){
			if(in_array($rid,$disabledThemeIds)){
				unset($themeId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$themeId
		);
		
		$themeData = $this->model('TemplateTheme')->where($where)->select();
		if($themeData){
			
			$output = $this->model('TemplateTheme')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $themeId 模块ID
	 * @param $themeNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($themeNewData,$themeId){
		$where = array(
			'identity'=>$themeId
		);
		
		$themeData = $this->model('TemplateTheme')->where($where)->find();
		if($themeData){
			
			$themeNewData['lastupdate'] = $this->getTime();
			$this->model('TemplateTheme')->data($themeNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $themeNewData 模块信息
	 *
	 * @reutrn int;
	 */
	public function insert($themeNewData){
		if(!$themeNewData){
			return -1;
		}
		$themeNewData['subscriber_identity'] =$this->session('uid');
		$themeNewData['dateline'] = $this->getTime();
		
		$this->model('TemplateTheme')->data($themeNewData)->add();
	}
}