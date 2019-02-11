<?php

class WorkflowTestConsole extends Console {
	
	public function fire(){
		$missionId = $this->service('Workflow')->start(1,12,35);
		$this->info('发起流程：'.$missionId);
		$cnt = 1;
		while($missionId > 0){
			$missionId = $this->service('Workflow')->execute($missionId,$cnt > 2?mt_rand(1,3):1);
			$cnt++;
			$this->info('同意：'.$missionId);
		}
		
		//$result = $this->service('Workflow')->execute(1,2);
		//$this->info('退回发起人：'.$result);
		
		//$result = $this->service('Workflow')->execute(1,4);
		//$this->info('退回流程发起人：'.$result);
	}
}