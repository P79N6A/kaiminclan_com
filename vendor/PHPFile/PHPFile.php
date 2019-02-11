<?php

class PHPFile{
	
	const FILEDATA_APPEND_ENABLE = 1;
	
	private $data = '';
	
	public function data($data){
		$this->data = $data;
	}
	
	public function write($append = 0){
		$result = file_put_contents($this->filename,$this->data,($append == self::FILEDATA_APPEND_ENABLE? FILE_APPEND:''));
	}
}
?>