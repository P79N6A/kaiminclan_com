<?php
class PHPEma  {
	/**
	 * 指数平滑移动平均
	 *
	 * @param $ema 
	 * @param $price 
	 * @param $day 
	 *
	 */
	public function ema($ema,$price,$day){
		return round((2*$price+($day-1)*$ema)/($day+1),8);
	}
}