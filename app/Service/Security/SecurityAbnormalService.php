<?php
/**
 *
 * 动态感知
 *
 * 安全中心
 *
 */
class  SecurityAbnormalService extends Service {
	
	
	/**
	 *
	 * 敏感词列表
	 *
	 *
	 * @reutrn array;
	 */
	public function getAbnormalList($where,$start = 0,$perpage = 10,$order = 0){
		
		$count = $this->model('SecurityAbnormal')->where($where)->count();
		if($count){
			$handle = $this->model('SecurityAbnormal')->where($where);
			if($perpage){
				$handle->limit($start,$perpage,$count);

			}
			if(isset($order)){
				$handle->order($order);
			}
			
			$listdata = $handle ->select();
			
		}
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function pushLogin($uid){
	}
	
	/**
	 *
	 * 删除行为
	 *
	 * @param $abnormalId 行为ID
	 *
	 * @reutrn int;
	 */
	public function removeAbnormalId($abnormalId){
		
		$output = 0;
		
		if(count($abnormalId) < 1){
			return $output;
		}
		
		$where = array(
			'identity'=>$abnormalId
		);
		
		$abnormalData = $this->model('SecurityAbnormal')->where($where)->select();
		if($abnormalData){
			
			$output = $this->model('SecurityAbnormal')->where($where)->delete();
		}
		
		return $output;
	}
	
	/**
	 *
	 * 行为修改
	 *
	 * @param $abnormalId 行为ID
	 * @param $abnormalNewData 行为数据
	 *
	 * @reutrn int;
	 */
	public function update($abnormalNewData,$abnormalId){
		$where = array(
			'identity'=>$abnormalId
		);
		
		$abnormalData = $this->model('SecurityAbnormal')->where($where)->find();
		if($abnormalData){
			
			$abnormalNewData['lastupdate'] = $this->getTime();
			$this->model('SecurityAbnormal')->data($abnormalNewData)->where($where)->save();
		}
	}
	
	/**
	 *
	 * 新行为
	 *
	 * @param $abnormalNewData 行为信息
	 *
	 * @reutrn int;
	 */
	public function insert($abnormalNewData){
		if(!$abnormalNewData){
			return -1;
		}
		$abnormalNewData['subscriber_identity'] =$this->session('uid');
		$abnormalNewData['dateline'] = $this->getTime();
		$abnormalNewData['sn'] = $this->get_sn();
		$abnormalNewData['agent'] = __AGENT__;
		$abnormalNewData['hash'] = md5($abnormalNewData['agent'].$abnormalNewData['title'].$abnormalNewData['remark']);
        $abnormalNewData['hash'] = md5($abnormalNewData['hash'].$abnormalNewData['ip1'].$abnormalNewData['ip2'].$abnormalNewData['ip3'].$abnormalNewData['ip4']);
		$abnormalNewData['lastupdate'] = $abnormalNewData['dateline'];
		
		$abnormalId = $this->model('SecurityAbnormal')->data($abnormalNewData)->add();
		return $abnormalId;
	}
	
	public function push($title,$msg = ''){
		list($ip1,$ip2,$ip3,$ip4) = explode('.',long2ip($this->getClientIP()));

		$abnormalData = array(
            'ip1'=>$ip1,
            'ip2'=>$ip2,
            'ip3'=>$ip3,
            'ip4'=>$ip4,
            'remark'=>$msg,
            'title'=>$title
        );
		$abnormalData['agent'] = __AGENT__;
		$hash = md5(__AGENT__.$title.$msg);
		$has = md5($hash.$ip1.$ip2.$ip3.$ip4);

		$where = array(
		    'hash'=>$hash
        );

		$oldAbnormalData = $this->model('SecurityAbnormal')->where($where)->find();
		if(!$oldAbnormalData){
            $this->insert($abnormalData);
        }else{
		    $where = array(
		        'identity'=>$hash
            );
            $this->model('SecurityAbnormal')->where($where)->setInc('num',1);
        }
	}
}