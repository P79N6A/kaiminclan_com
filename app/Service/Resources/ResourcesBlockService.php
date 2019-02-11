<?php
/**
 *
 * 模块
 *
 * 页面
 *
 */
class ResourcesBlockService extends Service
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
	public function getBlockList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ResourcesBlock')->where($where)->count();
		if($count){
			$listdata = $this->model('ResourcesBlock')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $blockId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getBlockInfo($blockId,$field = '*'){
		
		$where = array(
			'identity'=>$blockId
		);
		
		$blockData = $this->model('ResourcesBlock')->field($field)->where($where)->find();
		
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
	public function removeBlockId($blockId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$blockId
		);
		
		$blockData = $this->model('ResourcesBlock')->where($where)->find();
		if($blockData){
			
			$output = $this->model('ResourcesBlock')->where($where)->delete();
			
			$this->service('ResourcesItem')->removeBlockIdAllItem($blockId);
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
		
		$blockData = $this->model('ResourcesBlock')->where($where)->find();
		if($blockData){
			
			$blockNewData['lastupdate'] = $this->getTime();
			$this->model('ResourcesBlock')->data($blockNewData)->where($where)->save();
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
			
		$blockNewData['lastupdate'] = $blockNewData['dateline'];
		$this->model('ResourcesBlock')->data($blockNewData)->add();
	}
}