<?php
class PHPMa  {
	/**
	 * 移动平均
	 * 算法：[M*X+(N-M)*Y']/N
	 * @param $ema 
	 * @param $price 
	 * @param $day 
	 *
	 */
	private function ma($ma,$price,$day,$weight){
		return ($weight*$price+($day-$weight)*$ma)/$day;
	}
}