<?php
/**
 *
 * 附件
 *
 * 资源库
 *
 */
class AttachmentServerService extends Service {
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $field 附件字段
	 * @param $status 附件状态
	 *
	 * @reutrn array;
	 */
	public function getServerList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('AttachmentServer')->where($where)->count();
		if($count){
			$clienteteHandle = $this->model('AttachmentServer')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$clienteteHandle = $clienteteHandle->limit($start,$perpage,$count);
			}
			$listdata = $clienteteHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	public function getServerFullUrl($serverId){
		$serverId = intval($serverId);
		
		$where = array(
			'identity'=>$serverId
		);
		
		return $this->model('AttachmentServer')->field('identity,url')->where($where)->select();
		
	}
	
	
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $attachmentId 附件ID
	 *
	 * @reutrn array;
	 */
	public function getServerInfo($attachmentId,$field = '*'){
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachSetting = $this->config('attach.local');
		
		$attachmentData = $this->model('AttachmentServer')->field($field)->where($where)->find();
		if($attachmentData){
			$attachmentData['catalog'] = $this->service('ResourcesCatalog')->getCatalogInfo($attachmentData['catalog_identity'],'identity,title');
			unset($attachmentData['catalog_identity']);
			
			if($attachmentData['remote']){
				$attachmentData['attach'] = $this->service('ResourcesServer')->getServerFullUrl($attachmentData['server_identity'],$attachmentData['attach']);
			}else{
				$attachmentData['attach'] = $attachSetting['attach_url'].$attachmentData['attach'];
			}
		}
		
		return $attachmentData;
	}
	
	/**
	 *
	 * 删除附件
	 *
	 * @param $attachmentId 附件ID
	 *
	 * @reutrn int;
	 */
	public function removeServerId($attachmentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachmentData = $this->model('AttachmentServer')->where($where)->count();
		if($attachmentData){
			
			$output = $this->model('AttachmentServer')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 附件修改
	 *
	 * @param $attachmentId 附件ID
	 * @param $attachmentNewData 附件数据
	 *
	 * @reutrn int;
	 */
	public function update($attachmentNewData,$attachmentId){
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachmentData = $this->model('AttachmentServer')->where($where)->find();
		if($attachmentData){
			
			$attachmentNewData['lastupdate'] = $this->getTime();
			$this->model('AttachmentServer')->data($attachmentNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新附件
	 *
	 * @param $attachmentData 附件信息
	 *
	 * @reutrn int;
	 */
	public function insert($attachmentData){
		
		$attachmentData['business_identity'] = $this->session('business_identity');
		$attachmentData['subscriber_identity'] = $this->session('uid');
		$attachmentData['dateline'] = $this->getTime();
			
		$attachmentData['lastupdate'] = $attachmentData['dateline'];
		return $this->model('AttachmentServer')->data($attachmentData)->add();
	}
	
	public function getAvailableServerId(){
		
		$serverId = 0;
		$where = array(
			'status'=>AttachmentServerModel::ATTACHMENT_SERVER_STATUS_ENABLE
		);
		$list = $this->model('AttachmentServer')->field('identity,weight')->where($where)->select();
		if($list){
			foreach($list as $key=>$data){
				$weight = $data['weight'];
				$setting[$weight] = $data['identity'];
				$randData[$weight] = $weight*0.1; 
			}
			$index = helper_random::lucky($randData);
			$serverId = $setting[$index];
		}
		return $serverId;
	}
}