<?php
class AuthorityIncidentService extends Service {
	
	public function getIncidentList($where = array(),$start = 1,$perpage = 10,$orderby = 'identity desc'){
		
		$count = $this->model('AuthorityIncident')->where($where)->count();
		if($count){
			$selectHandle = $this->model('AuthorityIncident')->where($where);
			if($perpage > 0){
				$selectHandle->limit($start,$perpage,$count);
			}
			if($orderby){
				$selectHandle ->order($orderby);
			}
			$listdata = $selectHandle->select();
			
		}
		
		return array('total'=>$count,'list'=>$listdata);
	}
	
	public function pushPage($pageId){
		if($pageId < 1){
			return 0;
		}
		$incidentData = array(
			'idtype'=>2,
			'id'=>$pageId
		);
		
		
		return $this->insert($incidentData);
	}
	
	
	public function pushAction($actionId){
		if($actionId < 1){
			return 0;
		}
		$incidentData = array(
			'idtype'=>1,
			'id'=>$actionId
		);
		
		return $this->insert($incidentData);
	}
	
	public function insert($incidentData){
		
		$incidentData['ip'] = $this->getClientIp();
		$incidentData['agent'] = __AGENT__;
		$incidentData['subscriber_identity'] = $this->getUID();
		$incidentData['dateline'] = $this->getTime();
		
		return $this->model('AuthorityIncident')->data($incidentData)->add();
	}
}