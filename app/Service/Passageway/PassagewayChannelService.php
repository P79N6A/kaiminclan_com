<?php
/**
 *
 * 分类
 *
 */
class  PassagewayChannelService extends Service {
	


	public function adjustAlleywayQuantity($channelId,$quantity = 1){
		
		$channelId = $this->getInt($channelId);
		$quantity = $this->getInt($quantity);
		if(!$channelId || !$quantity){
			return 0;
		}
		
		$where = array(
			'identity'=>$channelId
		);
		
		if(strpos($quantity,'-') !== false){
			$this->model('PassagewayChannel')->where($where)->setDec('alleyway_num',substr($quantity,1));
		}else{
			$this->model('PassagewayChannel')->where($where)->setInc('alleyway_num',$quantity);
		}
		
		
	}

	
	/**
	 *
	 * 评论信息
	 *
	 * @param $field 评论字段
	 * @param $status 评论状态
	 *
	 * @reutrn array;
	 */
	public function getChannelList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('PassagewayChannel')->where($where)->count();
		
		if($count){
			$subscriberHandle = $this->model('PassagewayChannel')->where($where);
			if($start &&  $perpage){
				$subscriberHandle->limit($start,$perpage,$count);
			}
			$listdata = $subscriberHandle->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>PassagewayChannelModel::getStatusTitle($data['status'])
				);
			}
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 话题信息
	 *
	 * @param $id 话题ID
	 * @param $idtype 话题类型
	 *
	 * @reutrn array;
	 */
	public function getChannelBaseInfo($id,$idtype){
		
		$channelData = array();
		
		$where = array(
			'id'=>$id,
			'idtype'=>$idtype,
		);
		
		return $this->model('PassagewayChannel')->where($where)->find();
	}
	
	/**
	 *
	 * 评论信息
	 *
	 * @param $channelId 评论ID
	 *
	 * @reutrn array;
	 */
	public function getChannelInfo($channelId){
		
		$alleywayData = array();
		
		$where = array(
			'identity'=>$channelId
		);
		
		$alleywayList = $this->model('PassagewayChannel')->where($where)->select();
		if($alleywayList){
			$alleywayData = $alleywayList;
			if(!is_array($channelId)){
				$alleywayData = current($alleywayData);
			}
		}
		
		
		return $alleywayData;
	}
	/**
	 *
	 * 检测评论名称
	 *
	 * @param $id 数据ID
	 * @param $idtype 数据类型
	 *
	 * @reutrn int;
	 */
	public function checkChannel($title){
		if($channelName){
			$where = array(
				'titel'=>$title
			);
			return $this->model('PassagewayChannel')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除评论
	 *
	 * @param $channelId 评论ID
	 *
	 * @reutrn int;
	 */
	public function removeChannelId($channelId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$channelId
		);
		
		$channelData = $this->model('PassagewayChannel')->where($where)->select();
		if($channelData){
			$output = $this->model('PassagewayChannel')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 评论修改
	 *
	 * @param $channelId 评论ID
	 * @param $channelNewData 评论数据
	 *
	 * @reutrn int;
	 */
	public function update($channelNewData,$channelId){
		$where = array(
			'identity'=>$channelId
		);
		
		$channelData = $this->model('PassagewayChannel')->where($where)->find();
		if($channelData){
			
			$channelNewData['lastupdate'] = $this->getTime();
			$result = $this->model('PassagewayChannel')->data($channelNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新评论
	 *
	 * @param $channelNewData 评论信息
	 *
	 * @reutrn int;
	 */
	public function insert($channelNewData){
		$channelNewData['subscriber_identity'] =$this->session('uid');		
		$channelNewData['dateline'] = $this->getTime();
			
		$channelNewData['lastupdate'] = $channelNewData['dateline'];
		$channelNewData['sn'] = $this->get_sn();
		$channelId = $this->model('PassagewayChannel')->data($channelNewData)->add();
		
		
	}
}