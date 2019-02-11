<?php
/**
 *
 * 分类
 *
 * 基金
 *
 */
class  FightChannelService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getChannelList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FightChannel')->where($where)->count();
		if($count){
			$start = intval($start);
			$perpage = intval($perpage);
			
			$handle = $this->model('FightChannel')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $channelId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getChannelInfo($channelId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$channelId
		);
		$channelData = $this->model('FightChannel')->field($field)->where($where)->select();
		if($channelData){
			if(!is_array($channelId)){
				$channelData = current($channelData);
			}
		}
		return $channelData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $channelName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($channelName){
		if($channelName){
			$where = array(
				'title'=>$channelName
			);
			return $this->model('FightChannel')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $channelId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeChannelId($channelId){
		
		$output = 0;
		
		if(count($channelId) < 1){
			return $output;
		}
		
		$disabledChannelIds = FightChannelModel::getChannelTypeList();
		foreach($channelId as $key=>$rid){
			if(in_array($rid,$disabledChannelIds)){
				unset($channelId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$channelId
		);
		
		$channelData = $this->model('FightChannel')->where($where)->select();
		if($channelData){
			
			$output = $this->model('FightChannel')->where($where)->delete();
		}
		
		return $output;
	}
	
	public function adjustPolicyNum($channelId,$quantity){
		
		if(is_array($channelId)){
			$channelId = array($channelId);
		}
		
		$channelId = array_map('intval',$channelId);
		
		if(empty($channelId)){
			return 0;
		}
		if($quantity === 0){
			return 0;
		}
		
		$where = array();
		$where['identity'] = $channelId;
		
		if($quantity < 0){
			$quantity = substr($quantity,1);
			$this->model('FightChannel')->where($where)->setDec('policy_num',$quantity);
		}else{
			$this->model('FightChannel')->where($where)->setInc('policy_num',$quantity);
		}
		
	}
	
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $channelId 分类ID
	 * @param $channelNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($channelNewData,$channelId){
		$where = array(
			'identity'=>$channelId
		);
		
		$channelData = $this->model('FightChannel')->where($where)->find();
		if($channelData){
			
			$channelNewData['lastupdate'] = $this->getTime();
			$this->model('FightChannel')->data($channelNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $channelNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($channelNewData){
		if(!$channelNewData){
			return -1;
		}
		$channelNewData['sn'] = $this->get_sn();
		$channelNewData['subscriber_identity'] =$this->session('uid');
		$channelNewData['dateline'] = $this->getTime();
		$channelNewData['lastupdate'] = $channelNewData['dateline'];
		
		$channelId = $this->model('FightChannel')->data($channelNewData)->add();
		
		return $channelId;
	}
}