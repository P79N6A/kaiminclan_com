<?php
/**
 *
 * 附件
 *
 * 资源库
 *
 */
class AttachmentCatalogService extends Service {
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $field 附件字段
	 * @param $status 附件状态
	 *
	 * @reutrn array;
	 */
	public function getCatalogList($where = array(),$start = 0,$perpage = 0,$orderby = 'identity desc'){
		
		$list = array();
		
		$count = $this->model('AttachmentCatalog')->where($where)->count();
		if($count){
			$clienteteHandle = $this->model('AttachmentCatalog')->where($where)->orderby($orderby);
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
		
		$attachmentList = $this->model('AttachmentCatalog')->field('identity,server_identity,attach')->where($where)->select();
		if($attachmentList){
			$attachList = $serverIds = array();
			foreach($attachmentList as $key=>$attach){
				$serverIds[] = $attach['server_identity'];
				if($attach['server_identity'] < 1){
					$attach['attach'] = '/data/attachment'.$attach['attach'];
				}
				$attachList[$attach['server_identity']][$attach['identity']] = $attach['attach'];
			}
			$attachServerList = $this->service('ResourcesServer')->getServerFullUrl($serverIds,$attachList);
			if($attachServerList){
				foreach($attachServerList as $key=>$attachList){
					foreach($attachList as $aid=>$path){
						
						$attachUrl[$aid] = $path;
						
					}
				}
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
	public function getCatalogInfo($attachmentId,$field = '*'){
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachSetting = $this->config('attach.local');
		
		$attachmentData = $this->model('AttachmentCatalog')->field($field)->where($where)->find();
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
	public function removeCatalogId($attachmentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachmentData = $this->model('AttachmentCatalog')->where($where)->count();
		if($attachmentData){
			
			$output = $this->model('AttachmentCatalog')->where($where)->delete();
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
		
		$attachmentData = $this->model('AttachmentCatalog')->where($where)->find();
		if($attachmentData){
			
			$attachmentNewData['lastupdate'] = $this->getTime();
			$this->model('AttachmentCatalog')->data($attachmentNewData)->where($where)->save();
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
		return $this->model('AttachmentCatalog')->data($attachmentData)->add();
	}
}