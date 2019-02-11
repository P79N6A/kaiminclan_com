<?php
/**
 *
 * 模块
 *
 * 模板
 *
 */
class  TemplateDocumentService extends Service {
	
	
	/**
	 *
	 * 模块列表
	 *
	 * @param $field 模块字段
	 * @param $status 模块状态
	 *
	 * @reutrn array;
	 */
	public function getDocumentList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('TemplateDocument')->where($where)->count();
		if($count){
			$listdata = $this->model('TemplateDocument')->where($where)->orderby($order)->limit($start,$perpage,$count)->select();
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $documentId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getDocumentInfo($documentId,$field = '*'){
		
		$where = array(
			'identity'=>$documentId
		);
		
		$documentData = array();
		if(is_array($documentId)){
			$documentList = $this->model('TemplateDocument')->field($field)->where($where)->select();
			if($documentList){
				foreach($documentList as $key=>$document){
					$documentData[$document['identity']] = $document;
				}
			}
		}else{
			$documentData = $this->model('TemplateDocument')->field($field)->where($where)->find();
		}
		return $documentData;
	}
	/**
	 *
	 * 检测模块名称
	 *
	 * @param $documentName 模块名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($documentName){
		if($documentName){
			$where = array(
				'title'=>$documentName
			);
			return $this->model('TemplateDocument')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $documentId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeDocumentId($documentId){
		
		$output = 0;
		
		if(count($documentId) < 1){
			return $output;
		}
		
		$disabledDocumentIds = TemplateDocumentModel::getDocumentTypeList();
		foreach($documentId as $key=>$rid){
			if(in_array($rid,$disabledDocumentIds)){
				unset($documentId[$key]);
			}
		}
		
		$where = array(
			'identity'=>$documentId
		);
		
		$documentData = $this->model('TemplateDocument')->where($where)->select();
		if($documentData){
			
			$output = $this->model('TemplateDocument')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $documentId 模块ID
	 * @param $documentNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($documentNewData,$documentId){
		$where = array(
			'identity'=>$documentId
		);
		
		$documentData = $this->model('TemplateDocument')->where($where)->find();
		if($documentData){
			
			$documentNewData['lastupdate'] = $this->getTime();
			$this->model('TemplateDocument')->data($documentNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $documentNewData 模块信息
	 *
	 * @reutrn int;
	 */
	public function insert($documentNewData){
		if(!$documentNewData){
			return -1;
		}
		$documentNewData['subscriber_identity'] =$this->session('uid');
		$documentNewData['dateline'] = $this->getTime();
		
		$this->model('TemplateDocument')->data($documentNewData)->add();
	}
}