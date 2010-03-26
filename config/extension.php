<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

	// Configure sub tabs of admin module "rights and roles"
Todoyu::$CONFIG['EXT']['sysmanager']['rightsTabs'] = array(
	array(
		'id'	=> 'rights',
		'label'	=> 'LLL:sysmanager.rights.tab.rights'
	),
	array(
		'id'	=> 'roles',
		'label'	=> 'LLL:sysmanager.rights.tab.roles'
	)
);



	// Configure listing for roles
Todoyu::$CONFIG['EXT']['sysmanager']['listing']['roles'] = array(
	'name'		=> 'roles',
	'update'	=> 'sysmanager/role/listing',
	'dataFunc'	=> 'TodoyuRoleEditorManager::getRoleListingData',
	'columns'	=> array(
		'icon'		=> '',
		'title'		=> 'LLL:core.title',
		'description'=>'LLL:core.description',
		'persons'	=> 'LLL:sysmanager.roles.numPersons',
		'actions'	=> ''
	),
	'truncate'	=> array(
		'title'			=> 20,
		'description'	=> 20
	)

);

?>