<?php
/**
 *
 * 文章
 *
 * 新闻
 *
 */
class IntelligenceDocumentationService extends Service
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
	public function getDocumentationList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('IntelligenceDocumentation')->where($where)->count();
		if($count){
			$handle = $this->model('IntelligenceDocumentation')->where($where);
			if($perpage){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$originateIds = $catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['catalogue_identity'];
				$originateIds[] = $data['originate_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>IntelligenceDocumentationModel::getStatusTitle($data['status'])
				);
			}
			
			$catData = $this->service('IntelligenceCatalogue')->getCatalogueInfo($catalogueIds);
			$originateData = $this->service('IntelligenceOriginate')->getOriginateInfo($originateIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['catalogue'] = isset($catData[$data['catalogue_identity']])?$catData[$data['catalogue_identity']]:array();
				$listdata[$key]['originate'] = isset($originateData[$data['originate_identity']])?$originateData[$data['originate_identity']]:array();
				$listdata[$key]['url'] = '/news/view_'.$data['identity'].'.html';
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
	public function checkDocumentationTitle($title,$catId){
		if($title){
			$where = array(
				'title'=>$title,
				'catalogue_identity'=>$catId
			);
			return $this->model('IntelligenceDocumentation')->where($where)->count();
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
		
		$documentationData = $this->model('IntelligenceDocumentation')->field($field)->where($where)->find();
		
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
		
		$documentationData = $this->model('IntelligenceDocumentation')->where($where)->find();
		if($documentationData){
			
			$output = $this->model('IntelligenceDocumentation')->where($where)->delete();
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
		
		$documentationData = $this->model('IntelligenceDocumentation')->where($where)->find();
		if($documentationData){
			
			$documentationNewData['lastupdate'] = $this->getTime();
			$this->model('IntelligenceDocumentation')->data($documentationNewData)->where($where)->save();
			if($documentationData['catalogue_identity'] != $documentationNewData['catalogue_identity']){
				$this->service('IntelligenceCatalogue')->adjustDocumentationQuantity($documentationData['catalogue_identity'],-1);
				$this->service('IntelligenceCatalogue')->adjustDocumentationQuantity($documentationNewData['catalogue_identity'],1);
			}
		}
	}
	
	public function getUrl($articleData){
		return '/'.date('Ymd',$articleData['release_time'] < 1? $articleData['dateline']:$articleData['release_time']).'/'.$articleData['identity'].'.html';
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
		if(isset($documentationNewData['content'])){
			$content = $documentationNewData['content'];
			unset($documentationNewData['content']);
		}
		$documentationNewData['subscriber_identity'] =$this->session('uid');
		$documentationNewData['dateline'] = $this->getTime();
		
		$documentationNewData['url'] = $this->getUrl($documentationNewData);
			
		$documentationNewData['lastupdate'] = $documentationNewData['dateline'];
		$documentationId = $this->model('IntelligenceDocumentation')->data($documentationNewData)->add();
		if($documentationId){
			
			$this->service('IntelligenceSubstance')->save($content,$documentationId,$documentationNewData['title']);
				$this->service('IntelligenceCatalogue')->adjustDocumentationQuantity($documentationNewData['catalogue_identity'],1);
			
		}
		return $documentationId;
	}
}