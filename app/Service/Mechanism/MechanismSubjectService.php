<?php
/**
 *
 * 科目
 *
 * 财务
 *
 */
class MechanismSubjectService extends Service
{
	
	public function adjustAmount($subjectId,$amount){
		
		if($subjectId < 1){
			return -1;
		}
		
		
		$where = array(
			'identity'=>$subjectId
		);
		if(strpos($amount,'-') !== false){
			$this->model('MechanismSubject')->where($where)->setDec('amount',substr($amount,1));
		}else{
			$this->model('MechanismSubject')->where($where)->setInc('amount',$amount);
		}
		
	}
	/**
	 *
	 * 科目信息
	 *
	 * @param $field 科目字段
	 * @param $status 科目状态
	 *
	 * @reutrn array;
	 */
	public function getSubjectList($where,$start,$perpage,$order = ''){
		
		$count = $this->model('MechanismSubject')->where($where)->count();
		if($count){
			$handle = $this->model('MechanismSubject')->where($where);
			if($start > 0 && $perpage > 0){
				$handle->orderby($order)->limit($start,$perpage,$count);
			}
			$listdata = $handle->select();
			foreach($listdata as $key=>$data){
				$listdata[$key]['status'] = array(
					'value'=>$data['status'],
					'label'=>MechanismSubjectModel::getStatusTitle($data['status'])
				);
			}
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	/**
	 *
	 * 检测科目名称
	 *
	 * @param $subscriberName 账户名称
	 *
	 * @reutrn int;
	 */
	public function checkSubjectTitle($title,$subjectId){
		if($title){
				$where = array(
					'title'=>$title,
					'subject_identity'=>$subjectId,
				);
			return $this->model('MechanismSubject')->where($where)->count();
		}
		return 0;
	}
	
	/**
	 *
	 * 科目信息
	 *
	 * @param $blockId 科目ID
	 *
	 * @reutrn array;
	 */
	public function getSubjectInfo($subjectId,$field = '*'){
		$subjectData = array();
		
		if(!is_array($subjectId)){
			$subjectId = array($subjectId);
		}
		
		$subjectId = array_filter(array_map('intval',$subjectId));
		
		if(!empty($subjectId)){
		
			$where = array(
				'identity'=>$subjectId
			);
			
			$subjectData = $this->model('MechanismSubject')->field($field)->where($where)->select();
		}
		
		return $subjectData;
	}
	
	/**
	 *
	 * 删除科目
	 *
	 * @param $blockId 科目ID
	 *
	 * @reutrn int;
	 */
	public function removeSubjectId($blockId){
		
		$output = 0;
		
		$where = array(
			'identity'=>$blockId
		);
		
		$blockData = $this->model('MechanismSubject')->where($where)->find();
		if($blockData){
			
			$output = $this->model('MechanismSubject')->where($where)->delete();
			$this->pushJson();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 科目修改
	 *
	 * @param $blockId 科目ID
	 * @param $blockNewData 科目数据
	 *
	 * @reutrn int;
	 */
	public function update($blockNewData,$blockId){
		$where = array(
			'identity'=>$blockId
		);
		
		$blockData = $this->model('MechanismSubject')->where($where)->find();
		if($blockData){
			
			$blockNewData['lastupdate'] = $this->getTime();
			$this->model('MechanismSubject')->data($blockNewData)->where($where)->save();
			$this->pushJson();
		}
	}
	
	/**
	 *
	 * 新科目
	 *
	 * @param $blockNewData 科目数据
	 *
	 * @reutrn int;
	 */
	public function insert($blockNewData){
		
		$blockNewData['subscriber_identity'] =$this->session('uid');
		$blockNewData['dateline'] = $this->getTime();
		$blockNewData['sn'] = $this->get_sn();
			
		$blockNewData['lastupdate'] = $blockNewData['dateline'];
		$this->model('MechanismSubject')->data($blockNewData)->add();
		$this->pushJson();
	}
	
	private function pushJson(){
		set_time_limit(0);
		$treeList = array();
		$where = array('status'=>0);
		$listdata = $this->model('MechanismSubject')->field('identity as id,subject_identity as pid,code,title')->where($where)->select();
		
		if($listdata){
			foreach($listdata as $key=>$data){
				if($data['pid']) continue;
				foreach($listdata as $cnt=>$sub_data){
					if($sub_data['pid'] == $data['id']){
						foreach($listdata as $sub_cnt=>$sub_sub_data){
							if($sub_sub_data['pid'] == $sub_data['id']){
								foreach($listdata as $sub_sub_cnt=>$sub_sub_sub_data){
									if($sub_sub_sub_data['pid'] == $sub_sub_data['id']){
										$sub_sub_data['s'][] = $sub_sub_sub_data;
									}
								}
								$sub_data['s'][] = $sub_sub_data;
							}
						}
                        $data['s'][] = $sub_data;
					}
				}
                $treeList[] = $data;
			}
		}

		
		$folder = __DATA__.'/json/finance';
		if(!is_dir($folder)){
			mkdir($folder,0777,1);
		}
		file_put_contents($folder.'/subject.json',json_encode($treeList,JSON_UNESCAPED_UNICODE));
	}
}