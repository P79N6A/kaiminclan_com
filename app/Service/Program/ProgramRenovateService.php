<?php
/**
 *
 * 附件
 *
 * 资源库
 *
 */
class ProgramRenovateService extends Service {
	
	
	/**
	 *
	 * 附件信息
	 *
	 * @param $field 附件字段
	 * @param $status 附件状态
	 *
	 * @reutrn array;
	 */
	public function getRenovateList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('ProgramRenovate')->where($where)->count();
		if($count){
			$listdata = $this->model('ProgramRenovate')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
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
		
		$where = array(
			'status'=>ResourcesAttachmentModel::SUPPLIER_ATTACHMENT_STATUS_ENABLE
		);
		
		$attachmentList = $this->model('ResourcesAttachment')->field('identity,server_identity,attach')->where($where)->select();
		if($attachmentList){
			$attach = $serverIds = array();
			foreach($attachmentList as $key=>$attach){
				$serverIds[] = $attach['server_identity'];
				$attach[$attach['server_identity']][$attach['identity']] = $attach['attach'];
			}
			
			$attachServerList = $this->service('ResourcesServer')->getServerFullUrl($serverIds,$attach);
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
	public function getAttachmentInfo($attachmentId,$field = '*'){
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachmentData = $this->model('ResourcesAttachment')->field($field)->where($where)->find();
		if($attachmentData){
			$attachmentData['catalog'] = $this->service('ResourcesCatalog')->getCatalogInfo($attachmentData['catalog_identity'],'identity,title');
			unset($attachmentData['catalog_identity']);
			if($attachmentData['remote']){
				$attachmentData['attach'] = $this->service('ResourcesServer')->getServerFullUrl($attachmentData['server_identity'],$attachmentData['attach']);
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
	public function removeAttachmentId($attachmentId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$attachmentId
		);
		
		$attachmentData = $this->model('ResourcesAttachment')->where($where)->find();
		if($attachmentData){
			
			$output = $this->model('ResourcesAttachment')->where($where)->delete();
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
		
		$attachmentData = $this->model('ResourcesAttachment')->where($where)->find();
		if($attachmentData){
			
			$attachmentNewData['lastupdate'] = $this->getTime();
			$this->model('ResourcesAttachment')->data($attachmentNewData)->where($where)->save();
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
		$this->model('ResourcesAttachment')->data($attachmentData)->add();
	}
}