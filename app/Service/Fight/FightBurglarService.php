<?php
/**
 *
 * 产品
 *
 * 基金
 *
 */
class  FightBurglarService extends Service {
	
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $field 分类字段
	 * @param $status 分类状态
	 *
	 * @reutrn array;
	 */
	public function getBurglarList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('FightBurglar')->where($where)->count();
		if($count){
			$handle = $this->model('FightBurglar')->where($where);
			if($perpage > 0){
				$handle = $handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle ->select();
			$currencyIds = $catalogueIds = array();
			foreach($listdata as $key=>$data){
				$catalogueIds[] = $data['channel_identity'];
				
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>FightBurglarModel::getStatusTitle($data['status'])
				);
				
			}
			
			$catalogueData = $this->service('FightChannel')->getChannelInfo($catalogueIds);
			
			foreach($listdata as $key=>$data){
				$listdata[$key]['channel'] = isset($catalogueData[$data['channel_identity']])?$catalogueData[$data['channel_identity']]:array();
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function adjustQuotientNum($burglarId,$quantity){
		
		if(is_array($burglarId)){
			$burglarId = array($burglarId);
		}
		
		$burglarId = array_map('intval',$burglarId);
		
		if(empty($burglarId)){
			return 0;
		}
		if($quantity === 0){
			return 0;
		}
		
		$where = array();
		$where['identity'] = $burglarId;
		
		if($quantity < 0){
			$quantity = substr($quantity,1);
			$this->model('FightBurglar')->where($where)->setDec('quotient_num',$quantity);
		}else{
			$this->model('FightBurglar')->where($where)->setInc('quotient_num',$quantity);
		}
		
	}
	
	/**
	 *
	 * 分类信息
	 *
	 * @param $burglarId 分类ID
	 *
	 * @reutrn array;
	 */
	public function getBurglarInfo($burglarId,$field = 'identity,title'){
		
		$where = array(
			'identity'=>$burglarId
		);
		
		$burglarData = array();
		if(is_array($burglarId)){
			$burglarList = $this->model('FightBurglar')->field($field)->where($where)->select();
			if($burglarList){
				foreach($burglarList as $key=>$burglar){
					$burglarData[$burglar['identity']] = $burglar;
				}
			}
		}else{
			$burglarData = $this->model('FightBurglar')->field($field)->where($where)->find();
		}
		return $burglarData;
	}
	/**
	 *
	 * 检测分类名称
	 *
	 * @param $burglarName 分类名称
	 *
	 * @reutrn int;
	 */
	public function checkTitle($burglarName){
		if($burglarName){
			$where = array(
				'title'=>$burglarName
			);
			return $this->model('FightBurglar')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 删除分类
	 *
	 * @param $burglarId 分类ID
	 *
	 * @reutrn int;
	 */
	public function removeBurglarId($burglarId){
		
		$output = 0;
		
		if(count($burglarId) < 1){
			return $output;
		}		
		
		$where = array(
			'identity'=>$burglarId
		);
		
		$burglarData = $this->model('FightBurglar')->field('channel_identity')->where($where)->select();
		if($burglarData){
			
			$output = $this->model('FightBurglar')->where($where)->delete();			
			
			$catlaogueIds = array();
			foreach($burglarData as $key=>$burglar){
				$catalogueIds[] = $burglar['channel_identity'];
			}
			
			$this->service('FightChannel')->adjustBurglarNum($catalogueIds,'-'.count($catalogueIds));
		}
		
		return $output;
	}
	
	
	/**
	 *
	 * 分类修改
	 *
	 * @param $burglarId 分类ID
	 * @param $burglarNewData 分类数据
	 *
	 * @reutrn int;
	 */
	public function update($burglarNewData,$burglarId){
		$where = array(
			'identity'=>$burglarId
		);
		
		$burglarData = $this->model('FightBurglar')->where($where)->find();
		if($burglarData){
			
			$burglarNewData['lastupdate'] = $this->getTime();
			$this->model('FightBurglar')->data($burglarNewData)->where($where)->save();
			if($burglarNewData['channel_identity'] != $burglarData['channel_identity']){
				$this->service('FightChannel')->adjustBurglarNum($burglarNewData['channel_identity'],1);
				$this->service('FightChannel')->adjustBurglarNum($burglarData['channel_identity'],-1);
			}
		}
	}
	
	/**
	 *
	 * 新分类
	 *
	 * @param $burglarNewData 分类信息
	 *
	 * @reutrn int;
	 */
	public function insert($burglarNewData){
		if(!$burglarNewData){
			return -1;
		}
		$burglarNewData['sn'] = $this->get_sn();
		$burglarNewData['subscriber_identity'] =$this->session('uid');
		$burglarNewData['dateline'] = $this->getTime();
		$burglarNewData['lastupdate'] = $burglarNewData['dateline'];
		
		$burglarId = $this->model('FightBurglar')->data($burglarNewData)->add();
		if($burglarId){
			$this->service('FightChannel')->adjustBurglarNum($burglarNewData['channel_identity'],1);
		}
		
		return $burglarId;
	}
}