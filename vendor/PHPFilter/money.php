<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: money.php 2016-07-28 10:21:25Z jianqimin $
 */




/** 
 * 价格
 */
class filter_money
{
	private $data;
	public function filter($data,$format = '')

	{
		$this->data = $data;
		if(is_array($data)){
			foreach($data as $cnt=>$val){
				if(!is_numeric($val))
				{
					return false;
				}
				
				if(strcmp($val,0) < 0)
				{
					return false;
				}
			}
		}else{
			if(!is_numeric($data))
			{
				return false;
			}
			
			if(strcmp($data,0) < 0)
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