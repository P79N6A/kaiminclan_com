<?php
/**
 *
 * 目录
 *
 * 权益
 *
 */
class InviolableColumnService extends Service
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
	public function getColumnList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('InviolableColumn')->where($where)->count();
		if($count){
			$handle = $this->model('InviolableColumn')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
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
	public function checkColumnTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('InviolableColumn')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $columnId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getColumnInfo($columnId,$field = '*'){
		$catalogData = array();
		
		if(!is_array($columnId)){
			$columnId = array($columnId);
		}
		
		$columnId = array_filter(array_map('intval',$columnId));
		
		if(!empty($columnId)){
		
			$where = array(
				'identity'=>$columnId
			);
			
			$columnData = $this->model('InviolableColumn')->field($field)->where($where)->select();
		}
		return $columnData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $columnId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeColumnId($columnId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$columnId
		);
		
		$columnData = $this->model('InviolableColumn')->where($where)->find();
		if($columnData){
			
			$output = $this->model('InviolableColumn')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $columnId 模块ID
	 * @param $columnNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($columnNewData,$columnId){
		$where = array(
			'identity'=>$columnId
		);
		
		$columnData = $this->model('InviolableColumn')->where($where)->find();
		if($columnData){
			
			$columnNewData['lastupdate'] = $this->getTime();
			$this->model('InviolableColumn')->data($columnNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $columnNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($columnNewData){
		
		$columnNewData['subscriber_identity'] =$this->session('uid');
		$columnNewData['dateline'] = $this->getTime();
			
		$columnNewData['lastupdate'] = $columnNewData['dateline'];
		$this->model('InviolableColumn')->data($columnNewData)->add();
	}
}