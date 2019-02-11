<?php
/**
 *
 * 目录
 *
 * 文档库
 *
 */
class KnowledgeDocumentationService extends Service
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
	public function getDocumentationList($where,$start,$perpage,$order = 'identity desc'){
		
		$count = $this->model('KnowledgeDocumentation')->where($where)->count();
		if($count){
			$handle = $this->model('KnowledgeDocumentation')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['knowhow_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>KnowledgeDocumentationModel::getStatusTitle($data['status'])
				);
			}
			
			$catData = $this->service('KnowledgeKnowhow')->getKnowhowInfo($catalogueIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['knowhow'] = isset($catData[$data['knowhow_identity']])?$catData[$data['knowhow_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测岗位名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkDocumentationTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('KnowledgeDocumentation')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $documentationId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getDocumentationInfo($documentationId,$field = '*'){
		
		$where = array(
			'identity'=>$documentationId
		);
		
		$documentationData = $this->model('KnowledgeDocumentation')->field($field)->where($where)->find();
		
		return $documentationData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $documentationId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeDocumentationId($documentationId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$documentationId
		);
		
		$documentationData = $this->model('KnowledgeDocumentation')->where($where)->find();
		if($documentationData){
			
			$output = $this->model('KnowledgeDocumentation')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $documentationId 模块ID
	 * @param $documentationNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($documentationNewData,$documentationId){
		$where = array(
			'identity'=>$documentationId
		);
		
		$documentationData = $this->model('KnowledgeDocumentation')->where($where)->find();
		if($documentationData){
			
			$documentationNewData['lastupdate'] = $this->getTime();
			$this->model('KnowledgeDocumentation')->data($documentationNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $documentationNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($documentationNewData){
		
		$documentationNewData['subscriber_identity'] =$this->session('uid');
		$documentationNewData['dateline'] = $this->getTime();
			
		$documentationNewData['lastupdate'] = $documentationNewData['dateline'];
		$this->model('KnowledgeDocumentation')->data($documentationNewData)->add();
	}
}