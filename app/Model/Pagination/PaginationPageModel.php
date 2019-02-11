<?php
/**
 *
 * 页面
 *
 * 页面
 *
 */
class PaginationPageModel extends Model
{
    protected $_name = 'pagination_page';
    protected $_primary = 'identity';
	
	protected $_database = 'base';
	
	//状态【0:启用，1:草稿，2:禁用，3:删除，4:审核】
	const PAGINATION_PAGE_STATUS_ENABLE = 0;
	
	const PAGINATION_PAGE_STATUS_DRAFT = 1;
	
	const PAGINATION_PAGE_STATUS_DISABLED = 2;	
	
	const PAGINATION_PAGE_STATUS_REMOVED = 3;
	
	const PAGINATION_PAGE_STATUS_WAIT_EXAMINE = 4;

    //权限【1运维,2运营,3供应商,4客户,5游客,6公共】\
    const PAGINATION_PAGE_PERMISSION_ADMIN = 1;

    const PAGINATION_PAGE_PERMISSION_USER = 2;

    const PAGINATION_PAGE_PERMISSION_SUPPLIER = 3;

    const PAGINATION_PAGE_PERMISSION_CLIENT = 4;

    const PAGINATION_PAGE_PERMISSION_GUEST = 5;

    const PAGINATION_PAGE_PERMISSION_PUBLIC = 6;
	
	/**
	 * 获取状态列表
	 *
	 * @return array
	 */
	public static function getStatusList(){
		return array(
			array('value'=>self::PAGINATION_PAGE_STATUS_ENABLE,'label'=>'正常'),
			array('value'=>self::PAGINATION_PAGE_STATUS_DRAFT,'label'=>'草稿'),
			array('value'=>self::PAGINATION_PAGE_STATUS_DISABLED,'label'=>'禁用'),
			array('value'=>self::PAGINATION_PAGE_STATUS_REMOVED,'label'=>'回收站'),
			array('value'=>self::PAGINATION_PAGE_STATUS_WAIT_EXAMINE,'label'=>'待审核'),
		);
	}
	
	/**
	 * 获取页面状态
	 *
	 * @param $status 页面状态
	 *
	 * @return string
	 */
	public static function getStatusTitle($status){
		$statusTitle = '';
		$statusData = self::getStatusList();
		foreach($statusData as $key=>$data){
			if($data['value'] == $status){
				$statusTitle = $data['label'];
				break;
			}
		}
		
		return $statusTitle;
	}

    /**
     * 获取权限列表
     *
     * @return array
     *
     *
     */
    public static function getPermissionList(){
        return array(
            array('value'=>self::PAGINATION_PAGE_PERMISSION_ADMIN,'label'=>'运维'),
            array('value'=>self::PAGINATION_PAGE_PERMISSION_USER,'label'=>'运营'),
            array('value'=>self::PAGINATION_PAGE_PERMISSION_SUPPLIER,'label'=>'供应商'),
            array('value'=>self::PAGINATION_PAGE_PERMISSION_CLIENT,'label'=>'客户'),
            array('value'=>self::PAGINATION_PAGE_PERMISSION_GUEST,'label'=>'游客'),
            array('value'=>self::PAGINATION_PAGE_PERMISSION_PUBLIC,'label'=>'公共'),
        );
    }

    /**
     * 获取页面权限
     *
     * @param $permission 页面权限
     *
     * @return string
     */
    public static function getPermissionTitle($permission){
        $permissionTitle= '';
        $permissionData = self::getPermissionList();
        foreach($permissionData as $key=>$data){
            if($data['value'] == $permission){
                $permissionTitle = $data['label'];
                break;
            }
        }

        return $permissionTitle;
    }
}
