<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ip.php 2016-07-28 10:21:25Z jianqimin $
 */



/** 
 * IP地址
 */
class filter_ip
{
	private $data;
	private function format($data)
	{
		return preg_match('/d+.d+.d+.d+/',$data);
	}
	public function filter($data,$format = '')
	{
		$this->data = $data;
		if(is_array($data))
		{
			foreach($data as $key=>$val)
			{
				if(!$this->format($val))
				{
					return false;
				}
			}
		}else{
			if(!$this->format($data))
			{
				return false;
			}
		}
		return true;
	}
	public function __toString()
	{
		return $this->data;
	}
}