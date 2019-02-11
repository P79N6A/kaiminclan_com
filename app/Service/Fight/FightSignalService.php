<?php
/**
 *
 * 产品
 *
 * 基金
 *
 */
class  FightSignalService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getSignalList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FightSignal')->where($where)->count();
		if($count){
			$handle = $this->model('FightSignal')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$currencyIds = $catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['channel_identity'];
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FightSignalModel::getStatusTitle($data['status'])
				);
				
			}
			
			$catalogueData = $this->service('FightChannel')->getChannelInfo($catalogueIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['channel'] = isset($catalogueData[$data['channel_identity']])?$catalogueData[$data['channel_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function adjustQuotientNum($signalsId,$quantity){
		
		if(is_array($signalsId)){
			$signalsId = array($signalsId);
		}
		
		$signalsId = array_map('intval',$signalsId);
		
		if(empty($signalsId)){
			return 0;
		}
		if($quantity === 0){
			return 0;
		}
		
		$where = array();
		$where['identity'] = $signalsId;
		
		if($quantity < 0){
			$quantity = substr($quantity,1);
			$this->model('FightSignal')->where($where)->setDec('quotient_num',$quantity);
		}else{
			$this->model('FightSignal')->where($where)->setInc('quotient_num',$quantity);
		}
		
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $signalsId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getSignalInfo($signalsId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$signalsId
		);
		
		$signalsData = array();
		if(is_array($signalsId)){
			$signalsList = $this->model('FightSignal')->field($field)->where($where)->select();
			if($signalsList){
				foreach($signalsList as $key=>$signals){
					$signalsData[$signals['identity']] = $signals;
				}
			}
		}else{
			$signalsData = $this->model('FightSignal')->field($field)->where($where)->find();
		}
		return $signalsData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $signalsName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($signalsName){
		if($signalsName){
			$where = array(
				'title'=>$signalsName
			);
			return $this->model('FightSignal')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $signalsId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeSignalId($signalsId){
		
		$output = 0;
		
		if(count($signalsId) < 1){
			return $output;
		}		
		
		$where = array(
			'identity'=>$signalsId
		);
		
		$signalsData = $this->model('FightSignal')->field('channel_identity')->where($where)->select();
		if($signalsData){
			
			$output = $this->model('FightSignal')->where($where)->delete();			
			
			$catlaogueIds = array();
			foreach($signalsData as $key=>$signals){
				$catalogueIds[] = $signals['channel_identity'];
			}
			
			$this->service('FightChannel')->adjustSignalNum($catalogueIds,'-'.count($catalogueIds));
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $signalsId 分类ID
	 * @param $signalsNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($signalsNewData,$signalsId){
		$where = array(
			'identity'=>$signalsId
		);
		
		$signalsData = $this->model('FightSignal')->where($where)->find();
		if($signalsData){
			
			$signalsNewData['lastupdate'] = $this->getTime();
			$this->model('FightSignal')->data($signalsNewData)->where($where)->save();
			if($signalsNewData['channel_identity'] != $signalsData['channel_identity']){
				$this->service('FightChannel')->adjustSignalNum($signalsNewData['channel_identity'],1);
				$this->service('FightChannel')->adjustSignalNum($signalsData['channel_identity'],-1);
			}
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $signalsNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($signalsNewData){
		if(!$signalsNewData){
			return -1;
		}
		$signalsNewData['sn'] = $this->get_sn();
		$signalsNewData['subscriber_identity'] =$this->session('uid');
		$signalsNewData['dateline'] = $this->getTime();
		$signalsNewData['lastupdate'] = $signalsNewData['dateline'];
		
		$signalsId = $this->model('FightSignal')->data($signalsNewData)->add();
		if($signalsId){
			$this->service('FightChannel')->adjustSignalNum($signalsNewData['channel_identity'],1);
		}
		
		return $signalsId;
	}
}