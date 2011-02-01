<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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

/* --------------------------------
	Tabs Configurations
   -------------------------------- */
	// Admin module: "rights and roles"
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
	// "System configuration" module tabs
Todoyu::$CONFIG['EXT']['sysmanager']['configTabs'] = array(
	array(
		'id'	=> 'logo',
		'label'	=> 'LLL:sysmanager.config.tab.logo'
	)
);
	// Settings for uploadable company logo
Todoyu::$CONFIG['EXT']['sysmanager']['logoUpload'] = array(
	'width'	=> 190,
	'height'=> 60,
	'path'	=> 'config/img/logo.png'
);




/* ------------------------------------
	Configure listing for roles
   ------------------------------------ */
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
		'title'			=> 30,
		'description'	=> 20
	)
);



Todoyu::$CONFIG['EXT']['sysmanager']['update'] = array(
//	'connectionCheckUrl'	=> 'http://www.todoyu.com/robots.txt'
	'connectionCheckUrl'	=> 'http://ferni42.srv05/robots.txt'
)

?>