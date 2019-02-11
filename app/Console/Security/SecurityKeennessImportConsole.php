<?php
class KeennessImportConsole extends Console {
	public function fire(){
		$keennessFile = __DATA__.'/mgc@20181223.txt';
		
		$fileData = file($keennessFile);
		
		foreach($fileData as $key=>$val){
			$where = array(
				'title'=>$val
			);
			
			$keennessId = 0;
			$total = $this->model('SecurityKeenness')->where($where)->count();
			if($total < 1){
				 $keennessId = $this->model('SecurityKeenness')->data(array(
					'title'=>$val,
					'sn'=>$this->get_sn(),
					'dateline'=>$this->getTime(),
					'lastupdate'=>$this->getTime()
				 ))->add();
			}
			$this->info($keennessId);
		}
	}
}