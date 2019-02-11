<?php
class ShouchangBaikeConsole extends Console {
	
	public function fire(){
		var_dump(IN_COMMAND,__REQUEST_METHOD__);
		echo 'tset';
	}
}
?>