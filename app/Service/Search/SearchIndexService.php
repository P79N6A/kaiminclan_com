<?php
/**
 *
 * 索引
 *
 * 搜索
 *
 */
class  SearchIndexService extends Service {
	
	
	/**
	 *
	 * 敏感词列表
	 *
	 * @param $field 角色字段
	 * @param $status 角色状态
	 *
	 * @reutrn array;
	 */
	public function getIndexList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('SecurityReduction')->where($where)->count();
		if($count){
			$listdata = $this->model('SecurityReduction')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
}