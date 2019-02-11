<?php
/**
 *
 * 客户
 *
 * 账户
 *
 */
class  CivilizationColumnService extends Service {
	
	
	/**
	 *
	 * 收藏列表
	 *
	 * @return array $where 条件;
	 * @return int $start 当前页;
	 * @return int $perpage 单页数量;
	 * @return string $orderby 排序;
	 *
	 * @return array 订购列表;
	 */
	public function getColumnList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('CivilizationColumn')->where($where)->count();
		if($count){
			$columnHandle = $this->model('CivilizationColumn')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$columnHandle = $columnHandle->limit($start,$perpage,$count);
			}
			$listdata = $columnHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	/**
	 *
	 * 收藏信息
	 *
	 * @param $columnIds 收藏ID
	 *
	 * @reutrn int;
	 */
	public function getColumnInfo($columnIds){
		$columnData = array();
		
		$where = array(
			'identity'=>$columnIds
		);
		
		$columnList = $this->model('CivilizationColumn')->where($where)->select();
		if($columnList){
			
			if(is_array($columnIds)){
				$columnData = $columnList;
			}else{
				$columnData = current($columnList);
			}
			
			
		}
		
		
		return $columnData;
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $columnId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeColumnId($columnId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$columnId
		);
		
		$columnData = $this->model('CivilizationColumn')->where($where)->count();
		if($columnData){
			
			$output = $this->model('CivilizationColumn')->where($where)->delete();
		}
		
		return $output;
	}
		
	/**
	 *
	 * 检测收藏
	 *
	 * @param $title 数据类型
	 *
	 * @reutrn int;
	 */
	public function checkColumnTitle($title){
		$where = array(
			'title'=>$title
		);
		
		
		return $this->model('CivilizationColumn')->where($where)->count();
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $columnId 收藏ID
	 * @param $columnNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($columnNewData,$columnId){
		$where = array(
			'identity'=>$columnId
		);
		
		$columnData = $this->model('CivilizationColumn')->where($where)->find();
		if($columnData){
			
			
			$columnNewData['lastupdate'] = $this->getTime();
			$this->model('CivilizationColumn')->data($columnNewData)->where($where)->save();
			
			
		}
	}
	
	/**
	 *
	 * 新收藏
	 *
	 * @param $id 收藏信息
	 * @param $idtype 收藏信息
	 *
	 * @reutrn int;
	 */
	public function insert($columnData){
		$dateline = $this->getTime();
		$columnData['subscriber_identity'] = $this->session('uid');
		$columnData['dateline'] = $dateline;
		$columnData['lastupdate'] = $dateline;
			
		
		return $this->model('CivilizationColumn')->data($columnData)->add();
		
	}
}