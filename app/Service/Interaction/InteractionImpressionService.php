<?php
/**
 *
 * 印象/标签
 * 互动
 */
class  InteractionImpressionService extends Service {
	
	
	/**
	 *
	 * 调整印象使用数量
	 *
	 * @param $commentId 印象ID
	 * @param $quantity  数量
	 *
	 * @reutrn minxed;
	 */
	public function adjustImpressionUseNum($impressionId,$quantity = 1){
		
		$where = array(
			'identity' =>$impressionId
		);
		
		if($amount < 0){
			$this->model('InteractionImpression')->where($where)->setDec('comment_num',$quantity);
		}else{
			$this->model('InteractionImpression')->where($where)->setInc('comment_num',$quantity);
		}
		
	}
	
	/**
	 *
	 * 印象信息
	 *
	 * @param $field 印象字段
	 * @param $status 印象状态
	 *
	 * @reutrn array;
	 */
	public function getAllImpressionList($field = 'identity,title',$status = InteractionImpressionModel::SUPPLIER_ImpressionING_STATUS_ENABLE){
		
		$where = array(
			'status'=>$status
		);
		
		$impressionData = $this->model('InteractionImpression')->field($field)->where($where)->select();
		
		return $impressionData;
	}
	
	/**
	 *
	 * 印象信息
	 *
	 * @param $id 印象ID
	 *
	 * @reutrn array;
	 */
	public function getImpressionBaseInfo($id,$idtype){
		
		$impressionData = array();
		
		$where = array(
			'id'=>$id,
			'idtype'=>$idtype,
		);
		
		return $this->model('InteractionImpression')->where($where)->find();
	}
	
	/**
	 *
	 * 印象信息
	 *
	 * @param $impressionId 印象ID
	 *
	 * @reutrn array;
	 */
	public function getImpressionInfo($impressionId){
		
		$impressionData = array();
		
		$where = array(
			'identity'=>$impressionId
		);
		
		$impressionList = $this->model('InteractionImpression')->where($where)->select();
		if($impressionList){
			
		}
		
		if(!is_array($impressionId)){
			$impressionData = current($impressionData);
		}
		
		return $impressionData;
	}
	/**
	 *
	 * 检测印象名称
	 *
	 * @param $impressionName 印象名称
	 *
	 * @reutrn int;
	 */
	public function checkImpression($impressionName){
		if($impressionName){
			$where = array(
				'title'=>$impressionName
			);
			return $this->model('InteractionImpression')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除印象
	 *
	 * @param $impressionId 印象ID
	 *
	 * @reutrn int;
	 */
	public function removeImpressionId($impressionId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$impressionId
		);
		
		$impressionData = $this->model('InteractionImpression')->where($where)->select();
		if($impressionData){
			$output = $this->model('InteractionImpression')->where($where)->delete();
			if($output){
				$this->service('InteractionCommentImpression')->removeImpressionIdComment($impressionId);
			}
		}
		
		return $output;
	}
	
	/**
	 *
	 * 印象修改
	 *
	 * @param $impressionId 印象ID
	 * @param $impressionNewData 印象数据
	 *
	 * @reutrn int;
	 */
	public function update($impressionNewData,$impressionId){
		$where = array(
			'identity'=>$impressionId
		);
		
		$impressionData = $this->model('InteractionImpression')->where($where)->find();
		if($impressionData){
			
			$impressionNewData['lastupdate'] = $this->getTime();
			$result = $this->model('InteractionImpression')->data($impressionNewData)->where($where)->save();
		}
		return $result;
	}
	
	/**
	 *
	 * 新印象
	 *
	 * @param $impressionNewData 印象信息
	 *
	 * @reutrn int;
	 */
	public function insert($impressionNewData){
		$impressionNewData['subscriber_identity'] =$this->session('uid');		
		$impressionNewData['dateline'] = $this->getTime();
			
		$impressionNewData['lastupdate'] = $impressionNewData['dateline'];
		$impressionId = $this->model('InteractionImpression')->data($impressionNewData)->add();
		
		
	}
}