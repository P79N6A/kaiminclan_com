<?php
/**
 *
 * 注册表
 *
 * 基础
 *
 */
class  FoundationRegistryWebsiteService extends FoundationRegistryService {
	
	protected $bz = 'website';
	public function save($siteData){
		$where = array(
			'code'=>$this->bz
		);
		
		$setarr = array(
			'code'=>$this->bz,
			'setting'=>json_encode($siteData,1)
		);
		
		$settingData = $this->model('FoundationRegistry')->where($where)->find();
		if(!$settingData){
			$this->insert($setarr);
		}else{
			$this->update($setarr,$settingData['identity']);
		}
	}
}