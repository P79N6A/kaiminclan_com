<?php
/**
 *
 * 合作伙伴
 *
 * 新闻
 *
 */
class SecuritiesConceptService extends Service
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
	public function getConceptList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('SecuritiesConcept')->where($where)->count();
		if($count){
			$handle = $this->model('SecuritiesConcept')->where($where);
			if($start > 0 && $perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
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
	public function checkConceptTitle($title){
		if($title){
			$where = array(
				'title'=>$title
			);
			return $this->model('SecuritiesConcept')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 模块信息
	 *
	 * @param $conceptId 模块ID
	 *
	 * @reutrn array;
	 */
	public function getConceptInfo($conceptId,$field = '*'){
		
		$where = array(
			'identity'=>$conceptId
		);
		
		$conceptData = $this->model('SecuritiesConcept')->field($field)->where($where)->find();
		
		return $conceptData;
	}
	
	/**
	 *
	 * 删除模块
	 *
	 * @param $conceptId 模块ID
	 *
	 * @reutrn int;
	 */
	public function removeConceptId($conceptId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$conceptId
		);
		
		$conceptData = $this->model('SecuritiesConcept')->where($where)->find();
		if($conceptData){
			
			$output = $this->model('SecuritiesConcept')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 模块修改
	 *
	 * @param $conceptId 模块ID
	 * @param $conceptNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function update($conceptNewData,$conceptId){
		$where = array(
			'identity'=>$conceptId
		);
		
		$conceptData = $this->model('SecuritiesConcept')->where($where)->find();
		if($conceptData){
			
			$conceptNewData['lastupdate'] = $this->getTime();
			$this->model('SecuritiesConcept')->data($conceptNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新模块
	 *
	 * @param $conceptNewData 模块数据
	 *
	 * @reutrn int;
	 */
	public function insert($conceptNewData){
		
		$conceptNewData['subscriber_identity'] =$this->session('uid');
		$conceptNewData['dateline'] = $this->getTime();
			
		$conceptNewData['lastupdate'] = $conceptNewData['dateline'];
		$this->model('SecuritiesConcept')->data($conceptNewData)->add();
	}
}