<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: telephone.php 2016-07-28 10:21:25Z jianqimin $
 */




/** 
 * 电话号码
 */
class filter_telephone
{
	private $data;
	private function format($data)
	{
		return preg_match('/\d{3}-\d{8}|\d{4}-\d{7}/',$data);
	}
	public function filter($data,$format = '')
	{
		$this->data = $data;
		if(is_array($data))
		{
			foreach($data as $key=>$val)
			{
				if(!$this->format($val) && !$this->isMobile($val))
				{
					return false;
				}
			}
		}else{
			if(!$this->format($data) && !$this->isMobile($data))
			{
				return false;
			}
		}
		return true;
	}
	
	private function isMobile($data){
		
		if(!is_numeric($data) || strcmp(strlen($data),11) !== 0)
		{
			return false;
		}
		return true;
	}
	public function __toString()
	{
		return $this->data;
	}
}