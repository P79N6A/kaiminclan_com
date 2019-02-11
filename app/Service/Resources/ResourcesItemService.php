<?php
/**
 *
 * 数据
 *
 * 页面
 *
 */
class ResourcesItemService extends Service
{
	
	/**
	 *
	 * 条目信息
	 *
	 * @param $field 条目字段
	 * @param $status 条目状态
	 *
	 * @reutrn array;
	 */
	public function getItemList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ResourcesItem')->where($where)->count();
		if($count){
			$listdata = $this->model('ResourcesItem')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 条目信息
	 *
	 * @param $itemId 条目ID
	 *
	 * @reutrn array;
	 */
	public function getItemInfo($itemId,$field = '*'){
		
		$where = array(
			'identity'=>$itemId
		);
		
		$itemData = $this->model('ResourcesItem')->field($field)->where($where)->find();
		
		return $itemData;
	}
	
	/**
	 *
	 * 删除条目
	 *
	 * @param $itemId 条目ID
	 *
	 * @reutrn int;
	 */
	public function removeItemId($itemId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$itemId
		);
		
		$itemData = $this->model('ResourcesItem')->where($where)->find();
		if($itemData){
			
			$output = $this->model('ResourcesItem')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 删除模块下所有条目
	 *
	 * @param $blockId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeBlockIdAllItem($blockId){
		
		$output = 0;
		
		$where = array(
			'block_identity'=>$blockId
		);
		
		$itemData = $this->model('ResourcesItem')->where($where)->find();
		if($itemData){
			
			$output = $this->model('ResourcesItem')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 条目修改
	 *
	 * @param $itemId 条目ID
	 * @param $itemNewData 条目数据
	 *
	 * @reutrn int;
	 */
	public function update($itemNewData,$itemId){
		$where = array(
			'identity'=>$itemId
		);
		
		$itemData = $this->model('ResourcesItem')->where($where)->find();
		if($itemData){
			
			$itemNewData['lastupdate'] = $this->getTime();
			$this->model('ResourcesItem')->data($itemNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新条目
	 *
	 * @param $itemNewData 条目数据
	 *
	 * @reutrn int;
	 */
	public function insert($itemNewData){
		
		$itemNewData['subscriber_identity'] =$this->session('uid');
		$itemNewData['dateline'] = $this->getTime();
			
		$itemNewData['lastupdate'] = $itemNewData['dateline'];
		$this->model('ResourcesItem')->data($itemNewData)->add();
	}
}