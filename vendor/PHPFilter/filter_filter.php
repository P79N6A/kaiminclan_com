<?php
class filter_filter
{
	protected $data;
	public function __construst()
	{
	}
	
	public function init($data)
	{
		$this->data=  $data;
	}
	
	public function __toString()
	{
		return $this->data;
	}
}
?>