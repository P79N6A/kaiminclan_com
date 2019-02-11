<?php
class GeographyGlobalAreaConsole extends Console {
	public function fire(){
		
		$this->service('FoundationDistrict')->getGlobalTree('globalarea');
	}
}
?>