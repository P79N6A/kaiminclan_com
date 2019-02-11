<?php
/**
 *　
 * 模板
 *
 * 消息
 *
 */
class MessengerReceiveService extends Service {
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $field 附件字段
	 * @param $status 附件状态
	 *
	 * @reutrn array;
	 */
	public function getTemplateList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MessengerReceive')->where($where)->count();
		if($count){
			$listdata = $this->model('MessengerReceive')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测收藏
	 *
	 * @param $idtype 数据类型
	 * @param $id 数据ID
	 * @param $uid 用户ID
	 *
	 * @reutrn int;
	 */
	public function checkTemplateTitle($title){
		$accountId = array();		
		$where = array(
			'title'=>$title
		);
		
		
		return $this->model('MessengerReceive')->where($where)->count();
	}
	
	
		
	/**
	 *
	 * 删除收藏
	 *
	 * @param $accountId 收藏ID
	 *
	 * @reutrn int;
	 */
	public function removeAccountId($accountId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$accountId
		);
		
		$accountData = $this->model('MessengerReceive')->where($where)->count();
		if($accountData){
			
			$output = $this->model('MessengerReceive')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 收藏修改
	 *
	 * @param $accountId 收藏ID
	 * @param $accountNewData 收藏数据
	 *
	 * @reutrn int;
	 */
	public function update($accountNewData,$accountId){
		$where = array(
			'identity'=>$accountId
		);
		
		$accountData = $this->model('MessengerReceive')->where($where)->find();
		if($accountData){
			
			
			$accountNewData['lastupdate'] = $this->getTime();
			$this->model('MessengerReceive')->data($accountNewData)->where($where)->save();
			
			
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
	public function insert($accountData){
			$dateline = $this->getTime();
			$accountData['subscriber_identity'] = $this->session('uid');
			$accountData['dateline'] = $dateline;
			$accountData['lastupdate'] = $dateline;
			$accountData['sn'] = $this->get_sn();
			
		
		return $this->model('MessengerReceive')->data($accountData)->add();
		
	}
}