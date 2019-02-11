<?php
/**
 *
 * 渠道
 *
 */
class  PassagewayAlleywayService extends Service {
	



	
	/**
	 *
	 * 评论信息
	 *
	 * @param $field 评论字段
	 * @param $status 评论状态
	 *
	 * @reutrn array;
	 */
	public function getAlleywayList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		
		$count = $this->model('PassagewayAlleyway')->where($where)->count();
		
		if($count){
			$alleywawyHandle = $this->model('PassagewayAlleyway')->where($where);
			if($perpage){
				$alleywawyHandle->limit($start,$perpage,$count);
			}
			$listdata = $alleywawyHandle->select();
			$channelIds = array();
			foreach($listdata as $key=>$data){
				$channelIds[] = $data['channel_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>PassagewayAlleywayModel::getStatusTitle($data['status'])
				);
			}
			
			$channelData = $this->service('PassagewayChannel')->getChannelInfo($channelIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['channel'] = isset($channelData[$data['channel_identity']])?$channelData[$data['channel_identity']]:array();
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
	public function getAlleywayBaseInfo($id,$idtype){
		
		$alleywayData = array();
		
		$where = array(
			'id'=>$id,
			'idtype'=>$idtype,
		);
		
		return $this->model('PassagewayAlleyway')->where($where)->find();
	}
	
	/**
	 *
	 * 评论信息
	 *
	 * @param $alleywayId 评论ID
	 *
	 * @reutrn array;
	 */
	public function getAlleywayInfo($alleywayId){
		
		$alleywayData = array();
		
		$where = array(
			'identity'=>$alleywayId
		);
		
		$alleywayList = $this->model('PassagewayAlleyway')->where($where)->select();
		if($alleywayList){
			$alleywayData = $alleywayList;
			if(!is_array($alleywayId)){
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
	public function checkAlleyway($title){
		if($alleywayName){
			$where = array(
				'title'=>$title
			);
			return $this->model('PassagewayAlleyway')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除评论
	 *
	 * @param $alleywayId 评论ID
	 *
	 * @reutrn int;
	 */
	public function removeAlleywayId($alleywayId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$alleywayId
		);
		
		$alleywayData = $this->model('PassagewayAlleyway')->where($where)->select();
		if($alleywayData){
			$output = $this->model('PassagewayAlleyway')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 评论修改
	 *
	 * @param $alleywayId 评论ID
	 * @param $alleywayNewData 评论数据
	 *
	 * @reutrn int;
	 */
	public function update($alleywayNewData,$alleywayId){
		$where = array(
			'identity'=>$alleywayId
		);
		
		$alleywayData = $this->model('PassagewayAlleyway')->where($where)->find();
		if($alleywayData){
			
			$alleywayNewData['lastupdate'] = $this->getTime();
			$result = $this->model('PassagewayAlleyway')->data($alleywayNewData)->where($where)->save();
			if($alleywayData['channel_identity'] != $alleywayNewData['channel_identity']){
				$this->service('PassagewayChannel')->adjustAlleywayQuantity($alleywayNewData['channel_identity']);
				$this->service('PassagewayChannel')->adjustAlleywayQuantity($alleywayData['channel_identity'],-1);
			}
		}
		return $result;
	}
	
	/**
	 *
	 * 新评论
	 *
	 * @param $alleywayNewData 评论信息
	 *
	 * @reutrn int;
	 */
	public function insert($alleywayNewData){
		$alleywayNewData['subscriber_identity'] =$this->session('uid');		
		$alleywayNewData['dateline'] = $this->getTime();
			
		$alleywayNewData['lastupdate'] = $alleywayNewData['dateline'];
		$alleywayNewData['sn'] = $this->get_sn();
		$alleywayId = $this->model('PassagewayAlleyway')->data($alleywayNewData)->add();
		if($alleywayId){
			$this->service('PassagewayChannel')->adjustAlleywayQuantity($alleywayNewData['channel_identity']);
		}
		return $alleywayId;
	}
}