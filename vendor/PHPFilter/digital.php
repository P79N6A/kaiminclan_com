<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: digital.php 2016-07-28 10:21:25Z jianqimin $
123123234
123123123.234
-123234
 */




/** 
 * 数字
 */
class filter_digital
{
	private $data;
	public function filter($data,$format = '')
	{
		$this->data = $data;
		if(is_array($data))
		{
			foreach($data as $key=>$val)
			{
				if(!is_numeric($val))
				{
					return false;
				}
			}
		}else{
			if(!is_numeric($data))
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