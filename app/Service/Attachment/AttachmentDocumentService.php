<?php
/**
 *
 * 附件
 *
 * 资源库
 *
 */
class AttachmentDocumentService extends Service {
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $field 附件字段
	 * @param $status 附件状态
	 *
	 * @reutrn array;
	 */
	public function getDocumentList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('AttachmentDocument')->where($where)->count();
		if($count){
			$clienteteHandle = $this->model('AttachmentDocument')->where($where)->orderby($orderby);
			$start = intval($start);
			$perpage = intval($perpage);
			
			if($perpage > 0){
				$clienteteHandle = $clienteteHandle->limit($start,$perpage,$count);
			}
			$listdata = $clienteteHandle->select();
			
			
		}
		
		return array('list'=>$listdata,'total'=>$count);
	}
	
	/**
	 *
	 * 附件地址
	 *
	 * @param $attachmentId 附件ID
	 *
	 * @reutrn array;
	 */
	public function getAttachUrl($attachmentId){
		
		$attachUrl = array();
		
		if(!is_array($attachmentId)){
			$attachmentId = array($attachmentId);
		}
		
		$attachmentId = array_unique(array_filter(array_map('intval',$attachmentId)));
		if(empty($attachmentId)){
			return $attachUrl;
		}
		
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachmentList = $this->model('AttachmentDocument')->field('identity,server_identity,attach')->where($where)->select();
		if($attachmentList){
			$attachList = $serverIds = array();
			foreach($attachmentList as $key=>$attach){
				$serverIds[] = $attach['server_identity'];
				$attachList[$attach['server_identity']][$attach['identity']] = $attach['attach'];
			}
			$serverData = $this->service('AttachmentServer')->getServerFullUrl($serverIds);
			foreach($attachmentList as $key=>$attach){
				$attachUrl[$attach['identity']]['attach'] = $serverData[$attach['server_identity']]['url'].$attach['attach'];
			}
		}
		
		return $attachUrl;
	}
	
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $attachmentId 附件ID
	 *
	 * @reutrn array;
	 */
	public function getDocumentInfo($attachmentId,$field = '*'){
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachSetting = $this->config('attach.local');
		
		$attachmentData = $this->model('AttachmentDocument')->field($field)->where($where)->find();
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
	public function removeDocumentId($attachmentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachmentData = $this->model('AttachmentDocument')->where($where)->count();
		if($attachmentData){
			
			$output = $this->model('AttachmentDocument')->where($where)->delete();
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
		
		$attachmentData = $this->model('AttachmentDocument')->where($where)->find();
		if($attachmentData){
			
			$attachmentNewData['lastupdate'] = $this->getTime();
			$this->model('AttachmentDocument')->data($attachmentNewData)->where($where)->save();
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
		
		$attachmentData['subscriber_identity'] = $this->session('uid');
		$attachmentData['dateline'] = $this->getTime();
			
		$attachmentData['lastupdate'] = $attachmentData['dateline'];
		return $this->model('AttachmentDocument')->data($attachmentData)->add();
	}
}