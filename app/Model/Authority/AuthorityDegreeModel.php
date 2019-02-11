<?php
/**
 *
 * 身份
 *
 * 权限
 *
 */
class AuthorityDegreeModel extends Model
{
    protected $_name = 'authority_degree';

    protected $_primary = '';
	
	//账户类型
	
	const AUTHORITY_DEGREE_IDTYPE_BUSINESS = 1;
	
	const AUTHORITY_DEGREE_IDTYPE_CLIENT = 2;
		
	const AUTHORITY_DEGREE_IDTYPE_DOCTOR = 3;

	const AUTHORITY_DEGREE_IDTYPE_STAFF = 4;


}
