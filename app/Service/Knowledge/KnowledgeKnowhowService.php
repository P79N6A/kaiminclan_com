<?php
/**
 *
 * 目录
 *
 * 文档库
 *
 */
class KnowledgeKnowhowService extends Service
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
	public function getKnowhowList($where,$start,$perpage,$order = 'identity desc'){
		
		$count = $this->model('KnowledgeKnowhow')->where($where)->count();
		if($count){
			$handle = $this->model('KnowledgeKnowhow')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['catalogue_identity'];
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>KnowledgeKnowhowModel::getStatusTitle($data['status'])
				);
			}
			
			$catData = $this->service('KnowledgeCatalogue')->getCatalogueInfo($catalogueIds);
			foreach($listdata as $key=>$data){
				$listdata[$key]['catalogue'] = isset($catData[$data['catalogue_identity']])?$catData[$data['catalogue_identity']]:array();
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
	public function checkKnowhowTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('KnowledgeKnowhow')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $knowhowId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getKnowhowInfo($knowhowId,$field = '*'){
		$knowhowData = array();
		if(!is_array($knowhowId)){
			$knowhowId = array($knowhowId);
		}
		$knowhowId = array_filter(array_map('intval',$knowhowId));
		if($knowhowId){
		
		$where = array(
			'identity'=>$knowhowId
		);
		
		$knowhowData = $this->model('KnowledgeKnowhow')->field($field)->where($where)->find();
		}
		return $knowhowData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $knowhowId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeKnowhowId($knowhowId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$knowhowId
		);
		
		$knowhowData = $this->model('KnowledgeKnowhow')->where($where)->find();
		if($knowhowData){
			
			$output = $this->model('KnowledgeKnowhow')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $knowhowId 模块ID
	 * @param $knowhowNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($knowhowNewData,$knowhowId){
		$where = array(
			'identity'=>$knowhowId
		);
		
		$knowhowData = $this->model('KnowledgeKnowhow')->where($where)->find();
		if($knowhowData){
			
			$knowhowNewData['lastupdate'] = $this->getTime();
			$this->model('KnowledgeKnowhow')->data($knowhowNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $knowhowNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($knowhowNewData){
		
		$knowhowNewData['subscriber_identity'] =$this->session('uid');
		$knowhowNewData['dateline'] = $this->getTime();
			
		$knowhowNewData['lastupdate'] = $knowhowNewData['dateline'];
		$this->model('KnowledgeKnowhow')->data($knowhowNewData)->add();
	}
}