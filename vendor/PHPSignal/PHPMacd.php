<?php
class PHPMacd  {
	/**
	 * MACD线
	 *
	 * @param $short 
	 * @param $long 
	 * @param $mid
		DIF:EMA(CLOSE,SHORT)-EMA(CLOSE,LONG);
		DEA:EMA(DIF,MID);
		MACD:(DIF-DEA)*2,COLORSTICK;
	 *
	 */
	private function macd($setting,$quotation,$macd){
		
		list($short,$long,$mid) = $setting;
		list($open,$high,$low,$close,$symbol) = $quotation;
		list($shortEma,$longEma) = $macd;
		
		$dif = $this->ema($shortEma,$close,$short)-$this->ema($longEma,$close,$long);
		$dea = $this->ema($dif,$short);
		$macd = ($dif-$dea)*2;
		return array($dif,$dea,$macd);
	}
}